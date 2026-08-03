# Community Markdown Implementation v1

The bounded rendering profile supports bold, italic, inline code, block quotes, unordered and ordered lists, thematic breaks, and HTTPS Markdown links. Source is escaped before transformation; links are passed through WordPress URL escaping. The renderer is shared by Feed and Thread View so the same normalized body remains recognizable across contexts.

This is not a WYSIWYG editor, formatting ribbon, HTML paste editor, or arbitrary document model. Unsupported syntax remains text. Future expansion requires authority review.
