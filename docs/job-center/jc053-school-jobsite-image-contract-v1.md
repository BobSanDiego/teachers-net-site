# JC053 School / Jobsite Image Contract v1

Status: Approved V1 implementation contract
Ticket: JC053-STEP1-JOBSITE-IMAGE-CONTRACT
Scope: Persistent School / Jobsite resource imagery

This contract governs the optional image attached to a persistent School /
Jobsite resource. It does not authorize per-job image overrides, which remain a
separate future capability.

## Authority and ownership

- Jobs owns the relationship between a School / Jobsite and its image.
- WordPress owns media storage, attachment records, generated derivatives, and
  filesystem delivery.
- The canonical Jobs reference is `resource_media.attachment_id`.
- The existing `resource_media` binding is reused; no duplicate upload or
  storage subsystem is permitted.
- One School / Jobsite image may be reused by any number of job postings. A job
  must not create a duplicate attachment merely because it references the same
  resource.

## Product behavior

- The image is optional.
- If no valid primary image exists, Teachers.Net renders the approved default
  image.
- Missing imagery must never block School / Jobsite creation or job publication.
- V1 provides Browse Image, selected-image preview, validation feedback,
  Replace, and Remove.
- V1 has no focal-point editor and no user-facing crop editor.
- The canonical presentation frame is 4:3, consistent with the minimum
  guidance of 400 × 300px.
- The browser receives the canonical display derivative, not the arbitrary
  original upload.

## Accepted uploads and limits

Accepted source types:

- JPEG/JPG
- PNG
- WebP

Reject:

- SVG
- animated formats
- unsupported MIME types
- executable or polyglot content

Limits:

- Intake ceiling: 5 MB per uploaded source file.
- Minimum dimensions: 400 × 300px.
- No separate maximum dimension is required initially; oversized images are
  resized during processing.
- The 5 MB value is an upload ceiling, not a storage or delivery target.

## Processing

Use WordPress-native attachment and image-processing infrastructure.

1. Validate the declared MIME and actual file contents.
2. Normalize EXIF orientation.
3. Sanitize filename and attachment metadata.
4. Resize oversized images and generate the canonical 4:3 display derivative.
5. Compress derivatives for web delivery, targeting approximately 100–250 KB
   where image content permits. This is an operational target, not a hard
   acceptance guarantee.
6. Strip unnecessary metadata from derivatives.
7. Never serve the original upload unless a later approved requirement demands
   it.

The original attachment may be retained under normal WordPress media policy,
but the implementation must not assume that retaining every original is the
long-term product requirement. Cleanup and retention are governed below.

## Accessibility

- The contract includes alt-text support.
- V1 may generate fallback alt text from the resource `display_name`, falling
  back to `full_name`, when an author-supplied alt text is absent.
- Generated text must be escaped and must not expose private resource data to a
  user who cannot retrieve the resource.
- A future contract may add explicit employer-authored alt text editing; that is
  not required for the first implementation unless the UI ticket expands scope.

## Lifecycle and transactions

### Upload

Upload is durable as part of the School / Jobsite save transaction. A temporary
preview mechanism may exist, but it must not become the canonical resource
reference before save.

The ordered operation is:

`validate source → create WordPress attachment → process derivatives → bind
attachment ID to resource_media → commit School / Jobsite save`

Failure must leave the prior resource image and binding unchanged.

### Replace

`create new attachment → validate/process → switch resource_media binding →
retain previous attachment until reference-aware orphan policy applies`

The old attachment must not be deleted merely because the binding changed.

### Remove

Remove or archive the Jobs binding, then render the Teachers.Net default image.
Do not automatically delete the WordPress attachment.

### Orphan cleanup

Orphan cleanup is a separate, reference-aware maintenance capability. It may
delete an attachment only after confirming that no School / Jobsite or other
approved consumer references it and that retention policy permits deletion.
No implementation may infer orphan status from one missing binding alone.

## Authorization and security

Every read or mutation must enforce the established Jobs authority boundary.

Mutation requires:

- authenticated WordPress user;
- active employer relationship to the target School / Jobsite;
- `media.update` authorization;
- WordPress nonce/CSRF validation.

Read requires:

- resource visibility authorization;
- `media.retrieve` where the service boundary requires it.

The media service must:

- enforce target-resource ownership/relationship before binding;
- validate actual file content rather than trusting filename or client MIME;
- reject executable, polyglot, SVG, and unsupported content;
- sanitize filenames and metadata;
- escape returned URLs and alt text;
- avoid exposing private media to unauthorized users;
- prevent attachment IDs belonging to another private resource from being
  attached through a forged request.

## Required implementation boundaries

The next implementation work must be split into these bounded stages:

1. Media Service — WordPress-native validation, attachment creation,
   orientation, derivatives, compression, alt-text fallback, and authorization.
2. Upload Transport — authenticated, nonce-protected save/replace/remove
   request path.
3. Attachment Processing — canonical 4:3 derivative and safe metadata policy.
4. Resource Binding — transactional `resource_media` create/update/archive and
   reference-aware replacement behavior.
5. UI Integration — reuse the approved Step 1 optional disclosure and add the
   minimum Browse/preview/Replace/Remove states without redesigning the form.
6. Browser Certification — canonical production route, authenticated resource
   flows, fallback, replacement, removal, responsive behavior, console state,
   and human visual QA.

## Explicit exclusions

This contract does not authorize:

- per-job/listing-specific image storage;
- duplicate attachments per job;
- custom filesystem or CDN storage;
- SVG support;
- animated image support;
- focal-point or crop editing;
- mandatory imagery;
- automatic attachment deletion;
- schema changes beyond the existing `resource_media` binding;
- implementation of any Step 2+ image behavior.

## Implementation readiness gate

Before implementation begins, confirm the deployment image editor and native
derivative support in each target environment, confirm the approved default
image asset/delivery policy, and preserve the JC053 authority manifest as the
current source-of-truth for the workbench.
