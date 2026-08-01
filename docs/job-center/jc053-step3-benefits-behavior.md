# JC053 Step 3 Benefits Behavior

The Benefits section is an optional collapsed disclosure in the shared Step 3
authoring renderer and uses the same native disclosure owner as the neighboring
optional sections. Its disclosure state affects only authoring visibility; it
does not suppress selected benefits or Additional benefits from the live
preview. Disclosure spacing is wholly owned by the shared optional-section
grid. The wrapper reuses the ordinary optional-section `<details>` and
`<summary>` structure; no Benefits-specific disclosure-controller CSS exists.

Selected benefits are rendered as lightweight, comma-separated whole-item
buttons under the `Benefits offered:` label. Activating an item removes it and
exposes the accessible name `Remove <benefit>`. Category options remain inline
toggle buttons with `aria-pressed` state, and selected summary items match
their typography. The `Benefits offered:` row is always visible. When empty,
it contains the canonical guidance `Click any benefit to add or remove it.`
with the noninteractive word `Click` using the selected-state
text/background treatment. When populated, selected removal controls replace
the guidance. Approximately 8px separates the offered row from the category
lists. Selected category options use normal weight with a subtle light-blue
background; unselected options use readable blue-gray text with pointer and
visible hover/focus underlines. No additional explanatory UI is required. The
native shared marker is the sole disclosure controller. The Additional
benefits counter and helper follow the textarea visibility and remain hidden
while disabled.
The canonical review URL is `http://127.0.0.1:8768/#step-03-job-description`.

## Deferred UX review

After the controller and contrast defects are resolved, schedule **JC053-
STEP003-UX010 — Evaluate Benefits Selector Comprehension, Scanability, and
State Clarity**. Preserve the compact text-first concept while evaluating
interaction comprehension, option scanability, and selected/unselected state
clarity. This review is deferred and does not redesign the selector here.
Additional benefits remains progressively revealed, limited to 300 characters,
and retains its session state when disabled.
