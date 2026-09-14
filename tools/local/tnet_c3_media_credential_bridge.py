#!/usr/bin/env python3
"""Ephemeral DDEV QA bridge for the existing approved AWS credential helper.

This process is intentionally host-side and local-only. It never stores AWS
credentials, exposes them in logs, or mounts an AWS credentials file into DDEV.
The application receives the helper's temporary credential-process response
over a Unix socket and immediately exchanges it for the existing signer role.
"""

from __future__ import annotations

import argparse
import json
import os
import signal
import socket
import struct
import subprocess
import sys
from pathlib import Path


HELPER = Path("/home/bobreap/bin/tnet-c3-media-iac-credentials")
MAX_RESPONSE = 16 * 1024


def valid_credentials(value: object) -> bool:
    if not isinstance(value, dict):
        return False
    return all(isinstance(value.get(key), str) and value[key] for key in (
        "AccessKeyId", "SecretAccessKey", "SessionToken", "Expiration"
    ))


def peer_uid(connection: socket.socket) -> int | None:
    try:
        _, uid, _ = struct.unpack("3i", connection.getsockopt(socket.SOL_SOCKET, socket.SO_PEERCRED, 12))
    except (AttributeError, OSError, struct.error):
        return None
    return uid


def helper_credentials() -> dict[str, object] | None:
    try:
        result = subprocess.run(
            [str(HELPER)],
            check=True,
            capture_output=True,
            timeout=12,
            env={**os.environ, "AWS_PAGER": ""},
        )
        if len(result.stdout) > MAX_RESPONSE:
            return None
        value = json.loads(result.stdout)
    except (OSError, subprocess.SubprocessError, UnicodeDecodeError, json.JSONDecodeError):
        return None
    return value if valid_credentials(value) else None


def serve(socket_path: Path) -> None:
    socket_path.parent.mkdir(mode=0o700, parents=True, exist_ok=True)
    if socket_path.exists():
        if not socket_path.is_socket():
            raise RuntimeError(f"refusing non-socket path: {socket_path}")
        socket_path.unlink()
    server = socket.socket(socket.AF_UNIX, socket.SOCK_STREAM)
    server.bind(str(socket_path))
    os.chmod(socket_path, 0o600)
    server.listen(4)
    stopping = False

    def stop(_signum: int, _frame: object) -> None:
        nonlocal stopping
        stopping = True
        server.close()

    signal.signal(signal.SIGTERM, stop)
    signal.signal(signal.SIGINT, stop)
    try:
        while not stopping:
            try:
                connection, _ = server.accept()
            except OSError:
                if stopping:
                    break
                raise
            with connection:
                if peer_uid(connection) != os.getuid():
                    continue
                request = connection.recv(128)
                if not request:
                    continue
                credentials = helper_credentials()
                if credentials is None:
                    continue
                connection.sendall(json.dumps(credentials, separators=(",", ":")).encode() + b"\n")
    finally:
        server.close()
        if socket_path.exists() and socket_path.is_socket():
            socket_path.unlink()


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--socket", type=Path, default=Path("/home/bobreap/.ddev/tnet-c3-media-local-credentials/credentials.sock"))
    args = parser.parse_args()
    serve(args.socket)
    return 0


if __name__ == "__main__":
    sys.exit(main())
