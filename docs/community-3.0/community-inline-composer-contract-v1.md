# Community Inline Composer Contract v1

There is one logical inline composer state: `{closed|open, target_post_id,
text, dirty, focus_origin}`. Opening another composer while the current one is
empty moves the composer immediately. If dirty, the user must confirm before
retargeting, navigation, reload, closing, or browser beforeunload.

Cancel clears text, closes the composer, and restores focus to the originating
Reply control. Successful submission clears state, returns through the server
redirect, and focuses the stable reply anchor. Failure preserves text and
target, shows an adjacent error, and returns focus to the form.

The visible context is “Replying to [safe target]”; nested intent is explicit
even though rendering is visually flat. Keyboard users reach the composer from
the Reply button, enter the form in document order, and can cancel without
losing context. `aria-describedby` links errors and target context to the
textarea. JavaScript may manage one-open state, dirty warnings, focus, and
interception, but the no-JavaScript POST form is the authority.

Draft retention is in-memory for the page and may be retained only through a
confirmed navigation flow; no cross-session draft storage is authorized in v1.
