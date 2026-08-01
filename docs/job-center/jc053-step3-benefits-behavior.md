# JC053 Step 3 Benefits Behavior

The Benefits section is an optional collapsed disclosure in the shared Step 3
authoring renderer and uses the same native disclosure owner as the neighboring
optional sections. Its disclosure state affects only authoring visibility; it
does not suppress selected benefits or Additional benefits from the live
preview.

Selected benefits are rendered as lightweight, comma-separated whole-item
buttons under the `Benefits offered:` label. Activating an item removes it and
exposes the accessible name `Remove <benefit>`. Category options remain inline
toggle buttons with `aria-pressed` state, and selected summary items match
their typography. Guidance is hidden while collapsed and appears only in the
expanded body immediately beneath the title, in italic normal-weight secondary
text: `Click items to add or remove benefits from your job listing.` Selected
category options use underline and normal weight. The Additional benefits
counter follows the textarea visibility and remains hidden while disabled.
The canonical review URL is `http://127.0.0.1:8768/#step-03-job-description`.
Additional benefits remains progressively revealed, limited to 300 characters,
and retains its session state when disabled.
