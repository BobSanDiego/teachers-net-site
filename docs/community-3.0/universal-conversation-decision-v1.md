# Universal Conversation Decision v1

## Plain-language decision

Standalone and attached discussions share one Community post/thread engine.
Standalone topics are their own subject. Lessons and Articles remain subject
objects owned by their products, with Community holding an explicit validated
subject reference. A new universal conversations table is not required now.

Before thread metadata implementation, add a subject-reference value object,
validate owner/type/ID namespaces, reserve nullable attachment fields, define
same-subject access checks, and test that embedded and standalone views share
one identity. Do not execute schema work until those tests are accepted.

Chatboard replacement keeps its current standalone path while gaining a future
compatibility seam. Lesson discussions can attach later without synthetic
topics or URL coupling. Unified feeds and notifications can consume a common
conversation/activity identity, but ranking, recommendations, subscriber
delivery, and personalization remain deferred.

The exact next implementation ticket is **C3-ARCH002 — Community Thread Branch
and Reply-Target Data Support**, expanded only with the subject-reference
compatibility tests identified by this audit. No attached Lesson UI, feed,
notification, migration, JavaScript, or production work should be combined.
