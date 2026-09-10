# Community 3.0 Publication Hook Authority v1

## Authority decision

No authoritative Community post-publication hook is currently verifiable in
the owned repository. The correct decision is **not selected** rather than
assigning authority to Core Terms, Job Center, the theme, WordPress generic
post hooks, or a guessed legacy component.

## Candidate comparison

| Candidate | Evidence | Decision |
|---|---|---|
| Core Terms / `profilaxes` | Term hierarchy and classification APIs | Reject: terms classify; they do not own posts. |
| Job Center / `tnet-jobs` | Separate job publication and email code | Reject: separate product and authority. |
| Theme templates | Rendering assets only | Reject: too late and not publication authority. |
| Generic WordPress post hook | Platform capability, no Community owner/contract | Defer until the Community publisher is identified. |
| C3-IMP009 test seam | Explicit test-owned shadow seam | Use only for bounded proof; not production authority. |

## Required future authority contract

The future Community publisher must expose stable post identity, author,
`path_id`, explicit `local_path -> group_id` mapping evidence, publication
state/time, visibility, moderation, privacy, and a safe content reference. The
notification adapter must remain downstream of publication and must never block
or alter publication. Membership remains distinct from consent.

## Verification boundary

The C3-IMP010 audit used repository inspection only. The C3-IMP009 test seam
remains disabled by default and has no persistent recorder, recipient
enumeration, queue, mail, digest, UI, production, or WordPress hook. No live
publication semantics were changed.

The exact next bounded ticket is: identify the real Community publisher source
implementation and authorize a read-only seam feasibility audit before any live
hook attachment.
