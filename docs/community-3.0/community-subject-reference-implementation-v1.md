# Community Subject Reference Implementation v1

`TNet_Community_Subject_Reference` is the compatibility boundary for subject
identity. It validates an owner/type pair, a bounded subject identifier, and
optional source namespace and revision tokens before the values enter a post
record. Its accepted namespaces are intentionally explicit:

| owner_product | subject_type | current meaning |
|---|---|---|
| `community` | `community_topic` | standalone Community topic |
| `lesson-bank` | `lesson` | synthetic attached Lesson Bank subject |
| `teachers-net` | `article` | synthetic attached Teachers.Net subject |

The object serializes to the eight persisted compatibility fields without
requiring a UI or a future product to share Community internals. Topic
creation uses the generated topic post ID as `subject_id`; reply creation
inherits the parent subject reference. Unsupported pairs and malformed tokens
return `SUBJECT_REFERENCE_INVALID`.
