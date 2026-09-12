"""C3 ordinary-photo quarantine processor for AWS Lambda.

The handler deliberately owns only validation, normalization, derivative
creation, and quarantine-to-ready object movement. C3 application code owns
media identity, authorization, association, and lifecycle policy.
"""

from __future__ import annotations

import json
import logging
import os
import re
import warnings
from io import BytesIO
from pathlib import PurePosixPath
from typing import Any
from urllib.parse import unquote_plus

import boto3
from PIL import Image, ImageCms, ImageOps


LOGGER = logging.getLogger(__name__)
LOGGER.setLevel(logging.INFO)

MAX_BYTES = 10 * 1024 * 1024
MAX_EDGE = 10_000
MAX_PIXELS = 40_000_000
READY_PREFIX = "ready/"
QUARANTINE_PREFIX = "quarantine/"
ALLOWED_TYPES = {"image/jpeg": "JPEG", "image/png": "PNG", "image/webp": "WEBP"}
MEDIA_ID_RE = re.compile(r"^[A-Za-z0-9][A-Za-z0-9._-]{0,127}$")

S3 = boto3.client("s3")


class ProcessorError(Exception):
    """Expected, safe-to-log processing failure."""

    def __init__(self, code: str):
        self.code = code
        super().__init__(code)


def _event(name: str, **fields: Any) -> None:
    """Emit bounded structured logs without object keys, bytes, or payloads."""
    safe = {"event": name}
    for key in ("media_id", "format", "width", "height", "output_count", "error_code"):
        if key in fields:
            safe[key] = fields[key]
    LOGGER.info(json.dumps(safe, separators=(",", ":"), sort_keys=True))


def _content_type(value: str | None) -> str:
    return (value or "").split(";", 1)[0].strip().lower()


def _signature(data: bytes) -> str | None:
    if data.startswith(b"\xff\xd8\xff"):
        return "JPEG"
    if data.startswith(b"\x89PNG\r\n\x1a\n"):
        return "PNG"
    if len(data) >= 12 and data[:4] == b"RIFF" and data[8:12] == b"WEBP":
        return "WEBP"
    return None


def _bounded_image(image: Image.Image) -> Image.Image:
    width, height = image.size
    if width < 1 or height < 1 or width > MAX_EDGE or height > MAX_EDGE:
        raise ProcessorError("dimensions_exceeded")
    if width * height > MAX_PIXELS:
        raise ProcessorError("pixels_exceeded")
    return image


def _flatten_or_rgb(image: Image.Image) -> Image.Image:
    if image.mode in ("RGBA", "LA") or (image.mode == "P" and "transparency" in image.info):
        rgba = image.convert("RGBA")
        background = Image.new("RGB", rgba.size, (255, 255, 255))
        background.paste(rgba, mask=rgba.getchannel("A"))
        return background
    return image.convert("RGB")


def _srgb(image: Image.Image) -> Image.Image:
    icc = image.info.get("icc_profile")
    if not icc:
        return _flatten_or_rgb(image)
    try:
        source = ImageCms.ImageCmsProfile(BytesIO(icc))
        target = ImageCms.createProfile("sRGB")
        return ImageCms.profileToProfile(image, source, target, outputMode="RGB")
    except (ImageCms.PyCMSError, ValueError, OSError) as exc:
        raise ProcessorError("invalid_color_profile") from exc


def _reopen_and_check(data: bytes, expected_format: str, max_edge: int) -> tuple[int, int]:
    try:
        with warnings.catch_warnings():
            warnings.simplefilter("error", Image.DecompressionBombWarning)
            with Image.open(BytesIO(data)) as image:
                if image.format != expected_format:
                    raise ProcessorError("encoded_format_mismatch")
                image.verify()
            with Image.open(BytesIO(data)) as image:
                image.load()
                if getattr(image, "n_frames", 1) != 1:
                    raise ProcessorError("animated_image")
                width, height = image.size
                if max(width, height) > max_edge:
                    raise ProcessorError("output_dimensions_exceeded")
                if image.getexif():
                    raise ProcessorError("output_metadata_present")
                return width, height
    except ProcessorError:
        raise
    except (Image.DecompressionBombError, Image.DecompressionBombWarning, OSError, ValueError) as exc:
        raise ProcessorError("output_decode_failed") from exc


def normalize_image(data: bytes, expected_content_type: str) -> dict[str, Any]:
    """Validate and encode one ordinary image entirely in bounded memory."""
    if len(data) > MAX_BYTES:
        raise ProcessorError("encoded_bytes_exceeded")
    expected = _content_type(expected_content_type)
    expected_format = ALLOWED_TYPES.get(expected)
    if expected_format is None:
        raise ProcessorError("content_type_not_allowed")
    if _signature(data) != expected_format:
        raise ProcessorError("signature_content_type_mismatch")

    try:
        with warnings.catch_warnings():
            warnings.simplefilter("error", Image.DecompressionBombWarning)
            with Image.open(BytesIO(data), formats=("JPEG", "PNG", "WEBP")) as probe:
                if probe.format != expected_format:
                    raise ProcessorError("detected_format_mismatch")
                probe.verify()
            with Image.open(BytesIO(data), formats=("JPEG", "PNG", "WEBP")) as source:
                _bounded_image(source)
                if getattr(source, "n_frames", 1) != 1:
                    raise ProcessorError("animated_image")
                source.load()
                oriented = ImageOps.exif_transpose(source)
                normalized = _srgb(oriented)
                _bounded_image(normalized)
    except ProcessorError:
        raise
    except (Image.DecompressionBombError, Image.DecompressionBombWarning, OSError, ValueError) as exc:
        raise ProcessorError("decode_failed") from exc

    def resized(edge: int) -> Image.Image:
        if max(normalized.size) <= edge:
            return normalized.copy()
        scale = edge / max(normalized.size)
        return normalized.resize(
            (max(1, round(normalized.width * scale)), max(1, round(normalized.height * scale))),
            Image.Resampling.LANCZOS,
        )

    encoded: dict[str, tuple[bytes, str, int]] = {}
    master = resized(2048)
    master_buffer = BytesIO()
    master.save(master_buffer, format="JPEG", quality=88, optimize=True, exif=b"")
    encoded["master.jpg"] = (master_buffer.getvalue(), "image/jpeg", 2048)

    for edge in (1440, 960, 480):
        derivative = resized(edge)
        output = BytesIO()
        derivative.save(output, format="WEBP", quality=84, method=4, exif=b"", xmp=b"")
        encoded[f"{edge}.webp"] = (output.getvalue(), "image/webp", edge)

    dimensions: dict[str, tuple[int, int]] = {}
    for name, (output, content_type, edge) in encoded.items():
        dimensions[name] = _reopen_and_check(output, ALLOWED_TYPES[content_type], edge)
    return {"outputs": encoded, "dimensions": dimensions, "source_format": expected_format}


def _media_id_from_key(key: str) -> str:
    if not key.startswith(QUARANTINE_PREFIX):
        raise ProcessorError("quarantine_prefix_required")
    relative = key[len(QUARANTINE_PREFIX) :]
    path = PurePosixPath(relative)
    if len(path.parts) != 2 or path.parts[0] in ("", ".", "..") or path.parts[1] in ("", ".", ".."):
        raise ProcessorError("invalid_quarantine_key")
    media_id = path.parts[0]
    if not MEDIA_ID_RE.fullmatch(media_id):
        raise ProcessorError("invalid_media_id")
    return media_id


def _extension_format(key: str) -> str | None:
    suffix = PurePosixPath(key).suffix.lower()
    if not suffix:
        return None
    formats = {".jpg": "JPEG", ".jpeg": "JPEG", ".png": "PNG", ".webp": "WEBP"}
    if suffix not in formats:
        raise ProcessorError("extension_not_allowed")
    return formats[suffix]


def _read_limited(body: Any) -> bytes:
    data = body.read(MAX_BYTES + 1)
    if len(data) > MAX_BYTES:
        raise ProcessorError("encoded_bytes_exceeded")
    return data


def _process_record(record: dict[str, Any]) -> None:
    bucket = record["s3"]["bucket"]["name"]
    key = unquote_plus(record["s3"]["object"]["key"])
    configured_bucket = os.environ["C3_MEDIA_BUCKET"]
    if bucket != configured_bucket:
        raise ProcessorError("unexpected_bucket")
    media_id = _media_id_from_key(key)

    head = S3.head_object(Bucket=bucket, Key=key)
    content_type = _content_type(head.get("ContentType"))
    extension_format = _extension_format(key)
    if extension_format is not None and ALLOWED_TYPES.get(content_type) != extension_format:
        raise ProcessorError("extension_content_type_mismatch")
    content_length = int(head.get("ContentLength", 0))
    if content_length < 1 or content_length > MAX_BYTES:
        raise ProcessorError("encoded_bytes_exceeded")
    result = normalize_image(_read_limited(S3.get_object(Bucket=bucket, Key=key)["Body"]), content_type)

    output_count = 0
    for name, (data, output_type, _edge) in result["outputs"].items():
        output_key = f"{READY_PREFIX}{media_id}/{name}"
        S3.put_object(
            Bucket=bucket,
            Key=output_key,
            Body=data,
            ContentType=output_type,
            ServerSideEncryption="AES256",
        )
        verified = S3.head_object(Bucket=bucket, Key=output_key)
        if int(verified.get("ContentLength", -1)) != len(data) or _content_type(verified.get("ContentType")) != output_type:
            raise ProcessorError("output_verification_failed")
        output_count += 1

    if output_count != 4:
        raise ProcessorError("output_set_incomplete")
    S3.delete_object(Bucket=bucket, Key=key)
    _event("image_released", media_id=media_id, format=result["source_format"], output_count=output_count)


def handler(event: dict[str, Any], context: Any) -> dict[str, list[dict[str, str]]]:
    """Process SQS records with Lambda partial-batch failure semantics."""
    failures: list[dict[str, str]] = []
    for record in event.get("Records", []):
        item_id = str(record.get("messageId", ""))
        try:
            body = record.get("body", "")
            nested = json.loads(body) if isinstance(body, str) else body
            _process_record(nested.get("Records", [nested])[0])
        except ProcessorError as exc:
            _event("image_rejected", error_code=exc.code)
            failures.append({"itemIdentifier": item_id})
        except (KeyError, TypeError, ValueError, json.JSONDecodeError):
            _event("image_rejected", error_code="invalid_event")
            failures.append({"itemIdentifier": item_id})
        except Exception:
            # Do not log provider/object details; SQS retries and the existing DLQ handle it.
            _event("image_retryable_failure", error_code="transient_failure")
            failures.append({"itemIdentifier": item_id})
    return {"batchItemFailures": failures}
