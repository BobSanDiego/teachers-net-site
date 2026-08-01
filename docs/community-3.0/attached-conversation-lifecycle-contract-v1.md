# Attached Conversation Lifecycle Contract v1

The subject product owns subject publication, access, deletion, and canonical
URL. Community owns conversation posts, moderation, reply lineage, and
Community audit. A conversation may be created only when the subject resolver
returns an authorized stable subject reference.

If a subject is unpublished, private, deleted, or access-restricted, the
conversation is not automatically deleted. New writes and reads are gated by
the subject policy; audit and lineage remain retained. Restoring the subject
reopens access only through the subject authority. Subject owner, Community
moderator, product moderator, and post author permissions remain distinct.

Thread mute, member mute, block, lock, revisions, and notifications consume the
same subject/conversation visibility decision but are not implemented here.
Legacy imports preserve source identity, unresolved mappings, timestamps, and
moderation evidence; they do not synthesize an attached subject from a title.
