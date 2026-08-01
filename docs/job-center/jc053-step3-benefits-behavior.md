# JC053 Step 3 Benefits Behavior

The Benefits section is an optional collapsed disclosure in the shared Step 3
authoring renderer. Its disclosure state affects only authoring visibility; it
does not suppress selected benefits or Additional benefits from the live
preview.

Selected benefits are rendered as lightweight, comma-separated whole-item
buttons. Activating an item removes it and exposes the accessible name
`Remove <benefit>`. Category options remain inline toggle buttons with
`aria-pressed` state. The helper instruction explicitly explains both adding
and removing benefits. Additional benefits remains progressively revealed,
limited to 300 characters, and retains its session state when disabled.
