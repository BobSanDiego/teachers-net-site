#!/usr/bin/env python3
"""Serve the committed JC053 workbench with build identity and no-store headers."""
from __future__ import annotations

import argparse
import subprocess
from datetime import datetime, timezone
from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path


MARKERS = ("step3PlainPasteHtml", "step3Benefits")


def git(repo: Path, *args: str) -> str:
    return subprocess.run(["git", "-C", str(repo), *args], check=True, capture_output=True, text=True).stdout.strip()


def build_info(repo: Path, root: Path) -> dict[str, str]:
    branch, commit = git(repo, "branch", "--show-current"), git(repo, "rev-parse", "--short", "HEAD")
    if not branch.startswith("JOB-CENTER-"):
        raise SystemExit(f"wrong branch: expected JOB-CENTER-*, got {branch or '(detached)'}")
    js = (root / "mockup.js").read_text()
    missing = [marker for marker in MARKERS if marker not in js]
    if missing:
        raise SystemExit(f"stale workbench asset: missing markers {', '.join(missing)}")
    return {"branch": branch, "commit": commit, "asset": f"jc053-{commit}", "root": str(root), "built": datetime.now(timezone.utc).isoformat(timespec="seconds")}


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--root", type=Path, default=Path.cwd() / "tmp/jc053-wizard-workbench")
    parser.add_argument("--port", type=int, default=8768)
    args = parser.parse_args()
    root = args.root.resolve()
    repo = Path(git(root, "rev-parse", "--show-toplevel"))
    info = build_info(repo, root)

    class Handler(SimpleHTTPRequestHandler):
        def __init__(self, *handler_args, **handler_kwargs):
            super().__init__(*handler_args, directory=str(root), **handler_kwargs)

        def end_headers(self):
            self.send_header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0")
            self.send_header("Pragma", "no-cache")
            self.send_header("Expires", "0")
            super().end_headers()

        def do_GET(self):
            if self.path.split("?", 1)[0] in {"/", "/index.html"}:
                content = (root / "index.html").read_text()
                banner = (f'<span class="jc053-build-banner" id="jc053-build-banner" role="status">'
                          f'branch={info["branch"]} · commit={info["commit"]} · asset={info["asset"]} · '
                          f'root={info["root"]} · built={info["built"]}</span>')
                content = content.replace("<!-- JC053_BUILD_BANNER -->", banner)
                content = content.replace("mockup.css?v=jc053-20260730-compact-01", f'mockup.css?v={info["asset"]}')
                content = content.replace("mockup.js?v=jc053-20260730-navbar-01", f'mockup.js?v={info["asset"]}')
                payload = content.encode()
                self.send_response(200)
                self.send_header("Content-Type", "text/html; charset=utf-8")
                self.send_header("Content-Length", str(len(payload)))
                self.end_headers()
                self.wfile.write(payload)
                return
            super().do_GET()

    print(f"Serving JC053 workbench at http://127.0.0.1:{args.port}/")
    print("Build identity:", info)
    ThreadingHTTPServer(("127.0.0.1", args.port), Handler).serve_forever()


if __name__ == "__main__":
    main()
