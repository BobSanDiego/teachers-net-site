# Community Thread View QA v1

Local smoke checks seed data, request the raw-colon local URL, and verify 200
status plus topic/direct/nested reply text and `noindex`. Verify missing IDs
return 404-style output. The route is usable without JavaScript and uses
semantic headings/articles, escaped content, visible relationship indentation,
and responsive bounded CSS.

Human visual QA was attempted through the browser bridge but initialization
failed in this session; screenshots and rendered-success claims are therefore
not available. Manual widths remain: 1440, 1024, 768, and mobile. Check line
length, wrapping, tombstone clarity, focus/keyboard behavior, and contrast when
the browser bridge is available.
