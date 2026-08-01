# JC053 Step 3 Benefits Behavior

The Benefits section is an optional collapsed disclosure in the shared Step 3
authoring renderer. Its disclosure state affects only authoring visibility; it
does not suppress selected benefits or Additional benefits from the live
preview.

Selected benefits are rendered as lightweight, comma-separated whole-item
buttons under the `Benefits offered:` label. Activating an item removes it and
exposes the accessible name `Remove <benefit>`. Category options remain inline
toggle buttons with `aria-pressed` state, and selected summary items match
their typography. The single guidance line immediately beneath the Benefits
title is: `Click items to add or remove benefits from your job listing.`
Additional benefits remains progressively revealed, limited to 300 characters,
and retains its session state when disabled.
