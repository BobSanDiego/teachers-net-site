# Download and unpack the pinned arm64 wheel without executing target-architecture
# code. This keeps the build usable on a host without arm64 emulation.
FROM --platform=$BUILDPLATFORM python:3.13-slim AS pillow-deps

COPY requirements.lock /tmp/requirements.lock
RUN python -m pip download --no-deps --only-binary=:all: \
    --platform manylinux_2_28_aarch64 \
    --python-version 3.13 --implementation cp --abi cp313 \
    --require-hashes -r /tmp/requirements.lock -d /tmp/wheels \
    && mkdir -p /opt/python \
    && python -c "import glob, zipfile; zipfile.ZipFile(glob.glob('/tmp/wheels/*.whl')[0]).extractall('/opt/python')"

# AWS Lambda Python 3.13 arm64 base, pinned to the approved architecture-specific digest.
FROM --platform=$TARGETPLATFORM public.ecr.aws/lambda/python:3.13@sha256:011546b5829a78d5564e328a65c8c783a839091afe31dc54de4d9cf6cda3928d

COPY --from=pillow-deps /opt/python/ ${LAMBDA_TASK_ROOT}/

COPY app.py ${LAMBDA_TASK_ROOT}/app.py
COPY tests ${LAMBDA_TASK_ROOT}/tests

CMD ["app.handler"]
