import io
import json
import os
import unittest
from unittest.mock import patch

from PIL import Image

import app


class Body:
    def __init__(self, value):
        self.value = value

    def read(self, size=-1):
        return self.value if size < 0 else self.value[:size]


class FakeS3:
    def __init__(self, source, content_type="image/png", fail_on_put=None, key="quarantine/media-test/original"):
        self.key = key
        self.objects = {key: (source, content_type)}
        self.deleted = []
        self.put_count = 0
        self.fail_on_put = fail_on_put

    def head_object(self, Bucket, Key):
        value = self.objects[Key]
        return {"ContentLength": len(value[0]), "ContentType": value[1]}

    def get_object(self, Bucket, Key):
        return {"Body": Body(self.objects[Key][0])}

    def put_object(self, Bucket, Key, Body, ContentType, ServerSideEncryption):
        self.put_count += 1
        if self.fail_on_put == self.put_count:
            raise RuntimeError("intentional test failure")
        self.objects[Key] = (Body, ContentType)

    def delete_object(self, Bucket, Key):
        self.deleted.append(Key)
        del self.objects[Key]


def image_bytes(fmt, size=(80, 40), exif=None):
    image = Image.new("RGB", size, (30, 120, 210))
    output = io.BytesIO()
    image.save(output, format=fmt, exif=exif or b"")
    return output.getvalue()


def sqs_event(key="quarantine%2Fmedia-test%2Foriginal"):
    return {
        "Records": [
            {
                "messageId": "message-1",
                "body": json.dumps(
                    {
                        "Records": [
                            {
                                "s3": {
                                    "bucket": {"name": "test-bucket"},
                                    "object": {"key": key},
                                }
                            }
                        ]
                    }
                ),
            }
        ]
    }


class ProcessorTests(unittest.TestCase):
    def test_jpeg_png_webp_pass_and_outputs_are_bounded_and_metadata_free(self):
        for fmt, content_type in (("JPEG", "image/jpeg"), ("PNG", "image/png"), ("WEBP", "image/webp")):
            result = app.normalize_image(image_bytes(fmt, (2400, 1200)), content_type)
            self.assertEqual(set(result["outputs"]), {"master.jpg", "1440.webp", "960.webp", "480.webp"})
            for name, (data, output_type, edge) in result["outputs"].items():
                width, height = result["dimensions"][name]
                self.assertLessEqual(max(width, height), edge)
                self.assertEqual(app._reopen_and_check(data, app.ALLOWED_TYPES[output_type], edge), (width, height))

    def test_exif_orientation_is_applied_and_output_metadata_is_absent(self):
        exif = Image.Exif()
        exif[274] = 6
        source = image_bytes("JPEG", (40, 20), exif)
        result = app.normalize_image(source, "image/jpeg")
        self.assertEqual(result["dimensions"]["master.jpg"], (20, 40))
        with Image.open(io.BytesIO(result["outputs"]["master.jpg"][0])) as output:
            self.assertFalse(output.getexif())

    def test_signature_and_declared_content_type_must_agree(self):
        with self.assertRaisesRegex(app.ProcessorError, "signature_content_type_mismatch"):
            app.normalize_image(image_bytes("JPEG"), "image/png")

    def test_malformed_and_animated_inputs_fail(self):
        with self.assertRaisesRegex(app.ProcessorError, "decode_failed"):
            app.normalize_image(b"\xff\xd8\xffnot-a-jpeg", "image/jpeg")
        first = Image.new("RGB", (20, 20), (1, 2, 3))
        second = Image.new("RGB", (20, 20), (4, 5, 6))
        animated = io.BytesIO()
        first.save(animated, format="PNG", save_all=True, append_images=[second], duration=10, loop=0)
        with self.assertRaisesRegex(app.ProcessorError, "animated_image"):
            app.normalize_image(animated.getvalue(), "image/png")

    def test_spoofed_extension_fails(self):
        fake = FakeS3(image_bytes("JPEG"), content_type="image/jpeg", key="quarantine/media-test/original.png")
        with patch.dict(os.environ, {"C3_MEDIA_BUCKET": "test-bucket"}), patch.object(app, "S3", fake):
            result = app.handler(sqs_event("quarantine%2Fmedia-test%2Foriginal.png"), None)
        self.assertEqual(result, {"batchItemFailures": [{"itemIdentifier": "message-1"}]})
        self.assertEqual(fake.deleted, [])

    def test_pixel_limit_fails_closed(self):
        with patch.object(app, "MAX_PIXELS", 100):
            with self.assertRaisesRegex(app.ProcessorError, "pixels_exceeded"):
                app.normalize_image(image_bytes("PNG", (11, 10)), "image/png")

    def test_no_upscale(self):
        result = app.normalize_image(image_bytes("JPEG", (80, 40)), "image/jpeg")
        self.assertEqual(result["dimensions"]["master.jpg"], (80, 40))
        self.assertEqual(result["dimensions"]["480.webp"], (80, 40))

    def test_source_deletes_only_after_all_outputs_verify(self):
        fake = FakeS3(image_bytes("PNG"))
        with patch.dict(os.environ, {"C3_MEDIA_BUCKET": "test-bucket"}), patch.object(app, "S3", fake):
            result = app.handler(sqs_event(), None)
        self.assertEqual(result, {"batchItemFailures": []})
        self.assertEqual(fake.deleted, ["quarantine/media-test/original"])
        self.assertEqual(fake.put_count, 4)

    def test_failure_preserves_source_for_retry(self):
        fake = FakeS3(image_bytes("PNG"), fail_on_put=3)
        with patch.dict(os.environ, {"C3_MEDIA_BUCKET": "test-bucket"}), patch.object(app, "S3", fake):
            result = app.handler(sqs_event(), None)
        self.assertEqual(result, {"batchItemFailures": [{"itemIdentifier": "message-1"}]})
        self.assertEqual(fake.deleted, [])
        self.assertIn("quarantine/media-test/original", fake.objects)


if __name__ == "__main__":
    unittest.main()
