# Community Rich Media Attachment QA v1

- PHP lint passed for attachment model, composer, Thread View/controller,
  repository, and bootstrap.
- Valid image, video, audio, and document fixtures validate and render.
- Missing image alt text, unsupported type, remote source, and MIME/type
  mismatch are rejected.
- Repository and Thread View round-trip passed for an image fixture, including
  alt text and readable post body.
- Safe-rendering checks found no iframe or script output.
- `git diff --check` passed.

Review URLs:

- Composer: `https://teachers-net.ddev.site/community/new/`
- Image topic: `https://teachers-net.ddev.site/community/thread/post:c58e260bb51905eb/`
- Video/audio/document topics: create through the fixture controls.

Authenticated viewport screenshots at 1440px, 1024px, 768px, and 390px remain
pending because browser control could not initialize in this WSL session. No
visual acceptance is claimed.
