# C3 media processor container

This is the first production-shaped, local-only implementation for the
approved C3 ordinary-photo pipeline. It runs as an arm64 AWS Lambda Python
3.13 container behind the existing SQS processing queue and uses the existing
private media bucket anchors.

## Contract

- Input is an S3 notification wrapped in an SQS record for one object under
  `quarantine/<media-id>/...`. The configured `C3_MEDIA_BUCKET` must match the
  notification bucket.
- The S3 `ContentType`, magic signature, and Pillow-detected format must agree.
  JPEG, PNG, and WebP are accepted; animated, malformed, oversized, and
  over-pixel-limit inputs fail closed. Encoded input is capped at 10 MiB,
  each edge at 10,000px, and the decoded raster at 40,000,000 pixels.
- Images are auto-oriented, converted to sRGB/RGB, stripped of metadata, and
  never upscaled. The output set is exactly `master.jpg` (2048px max edge) and
  `1440.webp`, `960.webp`, and `480.webp` (each at its named max edge).
- Every output is locally reopened and checked, then written with SSE-S3 and
  verified with `HeadObject`. The quarantine source is deleted only after all
  four outputs verify. Any failure returns the SQS item for retry and leaves
  the source in quarantine; the existing DLQ remains the terminal retry path.
- Logs are bounded JSON allowlists containing only event, media id, format,
  dimensions/counts, or stable error codes. Image bytes, metadata, keys, URLs,
  request bodies, credentials, and tokens are not logged.

The processor does not own upload authorization, media registry state,
post-association, moderation, public URL issuance, lifecycle configuration,
or infrastructure mutation.

## Local/container verification

The tests use deterministic in-memory JPEG, PNG, and WebP fixtures so no
external media is fetched or committed. Run them in the pinned container:

```sh
docker buildx build --platform linux/arm64 --load \
  -t tnet-c3-media-processor:test .
docker run --rm --platform linux/arm64 \
  --entrypoint /var/lang/bin/python3.13 \
  tnet-c3-media-processor:test \
  -m unittest discover -s /var/task/tests -v
```

On hosts without arm64 execution support, use the native exact-Pillow test
environment from the repository and retain the arm64 image inspection as the
architecture evidence; Lambda/RIE execution must then be performed by the
approved runtime operator before function activation.

The deployment authority is an immutable version tag and resolved ECR digest;
`latest` is never used.
