# Conversation Subject Identity Contract v1

Subject identity is an owned reference, not a URL, title, slug, WordPress row
alone, or inferred Core Term. The minimum value is `subject_type` plus a stable
`subject_id`; the owner product and immutable source/reference namespace must be
known to the resolver. A recommended v1 value object is
`owner_product`, `subject_type`, `subject_id`, optional `source_namespace`, and
`subject_revision`.

Standalone Community topics use `owner_product=community`,
`subject_type=community_topic`, and `subject_id=topic_post_id`. A Lesson or
Article supplies its own stable product-owned identity. Mutable canonical URLs
are navigation projections and may change through an audited alias, never by
changing the subject identity.

Core Terms may classify a subject or conversation when explicitly assigned; it
does not own either. Portable Views may render the same conversation in an
embedded or standalone view without creating a second conversation. `path_id`
and `group_id` remain separate references.
