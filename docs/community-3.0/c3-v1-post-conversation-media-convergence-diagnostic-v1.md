# C3 post, conversation, and media convergence diagnostic v1

Status: diagnostic complete on 2026-09-16.  This is the authority for the
next bounded implementation objective; it does not authorize a product patch
by itself.

## Scope and carried authority

`C3-V1-COMMUNITY-MEDIA-DISCOVERY-IMPLEMENTATION001` remains complete.  Its
gallery, ordered C3 media projection, processing/delivery, Shared Shell, and
responsive acceptance remain `PROVEN_NATIVE`.  The C3 media registry,
direct-to-quarantine uploader, READY gate, ordered `post_media` persistence,
and `media.teachers.net` delivery remain valid capabilities, but the earlier
controlled two-image proof is not proof that every currently exposed composer
or presentation path consumes them.

This diagnostic used the authenticated `community.qa` session at
`teachers-net-live.ddev.site`.  The app browser binding still fails before
discovery with `sandboxCwd is not a local file URI`; the already-approved
Windows Chrome/CDP QA session was callable and supplied the native evidence
below.  No post, attachment, S3 object, AWS configuration, or production
state was created or changed.  An in-memory, network-disabled file-input
probe was followed by a cache-bypassed reload.

## Current owner map

| Area | Current owner(s) | Current behavior / diagnosis |
|---|---|---|
| A. Feed topic composer | `class-tnet-community-landing-controller.php`; `class-tnet-community-topic-composer-controller.php` | The authenticated feed dialog consumes the modal `embedded_form(..., true)`.  Its JavaScript owns the `items[]` collection, presign/upload/status retry, removal, ordering, and serialized `media_attachments`. |
| A. Direct topic composer | `TNet_Community_Topic_Composer_Controller::embedded_form()` | Its non-modal branch is a hybrid legacy form (`multipart/form-data`, one file input and legacy preview markup) served by the same global-selector script.  It is not a second clean component configuration. |
| A. Reply / nested reply | `class-tnet-community-thread-controller.php`; `class-tnet-community-composer-view.php` | This is a separate one-file WordPress-upload composer, with its own inline script, presentation, target link wiring, and wrapper script.  It does not use the C3 registry. |
| B. C3 asset lifecycle | `class-tnet-community-media-registry.php`; `class-tnet-community-media-aws.php` | REST presign creates a media ID and quarantine key; READY assets are prepared in request order; `post_media` is written transactionally; reload projects relationships ordered by `position`. |
| B. Legacy reply media lifecycle | `TNet_Community_Composer_Contracts::upload_image()` through `TNet_Community_Thread_Controller::submit()` | One traditional `image_file` is uploaded and stored only in compatibility JSON.  It bypasses C3 presign, READY reconciliation, `post_media`, and ordered sibling semantics. |
| C. Feed/post rendering | `class-tnet-community-landing-controller.php`; `class-tnet-community-attachment.php`; `assets/community-visual-language-v1.css` | Feed cards render every attachment as a plain independent `<img>`.  There is no attachment-set/collage renderer, selected-image state, or viewer trigger. |
| D. Conversation interaction | `TNet_Community_Landing_Controller::card()` and its inline dialog script; `class-tnet-community-thread-controller.php` | Feed reply/count opens a summary-only native dialog.  Canonical route opens a separate standalone page; there is no full conversation modal, reply focus intent, modal owner resolution, or modal-state controller. |
| E. Route/history | `class-tnet-community-canonical-route.php`; `class-tnet-community-thread-controller.php`; feed-card click script injected by `tnet-community.php` | `/community/{community}/{thread}/` is the canonical server representation.  Feed-card/title behavior performs direct navigation.  No `pushState`, `popstate`, same-/cross-community resolution, or modal history layer exists. |
| F. Media viewer | none | No reusable lightbox/carousel/viewer exists in the Community plugin.  A viewer must consume the already ordered post attachment collection; it does not require an attachment-level social object. |

The live runtime hashes for the topic composer, thread controller, and landing
controller matched the registered Community source, so these are active owner
findings rather than stale-source deductions.

## Native evidence and causal chains

### Attachment accumulation and replacement

The feed modal was opened natively.  With all network requests deliberately
disabled, two sequential valid in-memory file-input changes produced two
distinct staged cards, serialized two ordered empty-media placeholders, and
disabled Post.  Therefore the active feed modal's `items.push(item)` path
*does* accumulate A then B before an actual upload; it does not establish the
reported universal A-to-B replacement.

The exact replacement chain is instead present in the current reply owner:

1. `reply-image-file` is single-valued and its change/drop handlers call
   `stage(f.files[0])` / `stage(e.dataTransfer.files[0])`.
2. `stage()` revokes the prior object URL, replaces the input's `FileList`,
   replaces the preview, and exposes one remove control.
3. Reply submission calls `upload_image()` once and persists an array that can
   contain only that one returned compatibility attachment.

Thus first-level reply and nested-reply authoring cannot satisfy A+B, remove B
while retaining A, ordered siblings, or retry isolation.  The direct topic
form's divergent one-file markup/global selectors are a parallel risk and
must be consolidated into the same capability rather than patched separately.

### Published attachment disappearance

For a modern feed topic, the established persistence chain is:

`media_attachments` -> `prepare_for_publication()` -> draft `media_items` ->
`persist_publication_in_transaction()` -> `attach_in_transaction()` ->
`post_media(position)` -> `attachments_for_post()` -> feed/thread projection.

The source path is transactional and preserves input order.  No current,
cleanup-governed A+B+C publish was created in this diagnostic, so it would be
false to name a first *observed* loss seam for the modern topic path.

For the current reply path, the first loss is already decisive: it never enters
that chain.  It is reduced to one WordPress upload in `compatibility_json`,
which explains the native feed's legacy `wp-content/uploads` and historical
attachment forms.  The next implementation objective must add bounded
instrumentation at every modern boundary above and exercise the exact
`A -> A+B -> A+B+C -> A+C -> A+C+D -> publish -> reload` journey with a
pre-approved, exclusively owned cleanup closure.  It must identify the first
boundary whose ordered IDs differ; it must not infer a registry defect from a
legacy reply projection.

### Drag/drop navigation

Native event probing on the standalone reply composer found `dragover`
prevented only on `#reply-media-zone`; its textarea returned
`defaultPrevented: false`.  The topic composer similarly registers
`dragenter`/`dragover`/`drop` only on `#dropzone`.  A valid file dropped over
the textarea or other composer surface therefore reaches browser default file
navigation.  The fix owner is the reusable composer/media interaction root:
it must capture valid image drag enter/over/drop across the entire composer,
prevent default, expose one armed state, and delegate files to the collection.
The Photo control stays a picker only.

### Reply presentation and target activation

The standalone thread natively showed `Formatting help`, `Post Reply`, and
`Reply to this comment`.  Clicking the latter changed only the URL fragment to
`#reply-composer`; the hidden parent target remained the root, the context did
not change, and no textarea acquired focus.

`reply_form_normalized()` emits its target-listener script before the reply
rows and their `data-reply-target` links exist.  `TNet_Community_Composer_View`
adds a second early listener/wrapper.  Neither binds the later links.  This is
one ownership/lifecycle defect, not an isolated label defect.  The consolidated
reply capability must render/bind after the target controls exist, use the
accepted copy `Reply`, retarget the actual parent, focus the reply composer,
remove obsolete formatting-help UI, and consume the standard C3 action-blue
CTA token.

### Feed, standalone route, and viewer gap

Native feed title/content behavior and the injected feed-card script navigate
to the canonical standalone route.  Native reply/count opens only a dialog
containing author/text and an `Open discussion page` link; it does not render
thread rows or a reply composer.  The canonical deep URL server-renders the
standalone thread and has no enclosing Community context/modal state.

`TNet_Community_Attachment::render()` returns an unwrapped plain image for
each image attachment and the feed loops it.  Image click has no viewer owner.
Consequently the current implementation has neither bounded 1/2/3/4/5+
collage behavior nor selected-image sibling traversal.  There is no existing
reusable viewer to preserve.

## Recommended single implementation boundary

Create one objective:

`C3-V1-POST-CONVERSATION-MEDIA-IMPLEMENTATION001` — **canonical composer,
conversation, and post-media presentation convergence**.

It should introduce one reusable, context-configured composer/media capability
for topic and reply contexts, while retaining thin controller-owned nonce,
idempotency, parent/thread validation, PRG, and publication boundaries.  It
must route both contexts through the existing C3 registry and ordered
`post_media` collection; do not merge the topic/reply controllers or alter
the signer/AWS architecture.

The same objective should establish one conversation surface controller that:

1. renders a full thread in a modal over its resolved owning Community when
   opened from feed content or reply/count;
2. focuses/contextualizes reply intent for reply/count entry;
3. keeps the existing canonical thread URL as the server/non-JavaScript
   representation, enhances cold/deep entry to the same browser experience,
   and supplies bounded back/forward semantics without creating a second
   canonical URL;
4. makes the Community/right rail/Create Post surface inert and dimmed while
   leaving logo, bell, and account behavior available as directed; and
5. closes/replaces modal context without modal stacking.

Within that surface, add a post-media renderer that owns the bounded collage
and a distinct modal media viewer.  The viewer receives the selected
attachment plus the post's ordered siblings, supports previous/next traversal,
and retains an explicit route back to the originating conversation.  Reactions
and replies stay post-owned; no attachment-level social schema is needed.

Consolidate/remove the legacy direct-topic markup, one-file reply uploader,
inline reply target scripts, composer-view string replacement, and per-card
summary dialog once their responsibilities are replaced.  Do not merely add
parallel handlers or Media-only CSS.

## Acceptance ledger for that objective

| Seam | State | Required decisive evidence |
|---|---|---|
| C3 S3/processor/CloudFront/signer/IaC | `PROVEN_NATIVE` carried forward | Do not retest unless owner changes. |
| C3 registry, READY gate, ordered relationship model | `PROVEN_NATIVE` capability; current-path revalidation required | Browser+state trace through the full A/B/C/remove/retry/publish/reload fixture. |
| Feed modal client collection | `PROVEN_NATIVE` for network-disabled sequential staging | Reuse, extend to whole-composer drop, and prove real direct uploads with a cleanup plan. |
| Reply media collection | `FAILED_NATIVE` | A+B/C, individual removal/retry/order, C3-only lifecycle, publication/reload. |
| Whole-composer drag/drop | `FAILED_NATIVE` | Picker and valid drops over textarea/composer, browser navigation prevented, visible armed state. |
| Nested reply activation | `FAILED_NATIVE` | `Reply` retargets parent, opens/focuses correct composer, persists threaded result. |
| Feed/canonical conversation behavior | `FAILED_NATIVE` against Director target | Same-community, cold/deep, cross-community, content/reply/count, close/back/forward, inert/focus behavior. |
| Feed collage and media viewer | `MISSING` | 1/2/3/4/5+ responsive layouts, +N, selected image, sibling next/previous, return to conversation. |
| Existing Media discovery/gallery | `PROVEN_NATIVE` carried forward | Gallery-to-owning-community/post/attachment entry remains coherent. |

## Decisions and boundaries

No new persistence/schema owner, canonical URL change, attachment-level social
object, privacy/visibility decision, or AWS mutation is required by the
evidence.  The existing post-media relation already supplies ordered
attachment identity.  A normal implementation choice may use noncanonical
browser history state for modal/viewer navigation, provided the canonical
thread URL remains the server/SEO representation and deep entry resolves to
the same experience.

The only prerequisite for real acceptance data is a disposable fixture plan
that proves complete exclusive database and S3 dependency ownership before
creation and deletion.  The prior Media QA closure is absent: cache-bypassed
Media now reports no eligible images and read-only database checks return zero
for its roots, relationships, three asset IDs, and variants.  It must not be
resurrected or assumed available.
