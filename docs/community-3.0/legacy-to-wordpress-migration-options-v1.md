# Legacy-to-WordPress Migration Options v1

| Strategy | Benefits | Risks/cost | Assessment |
|---|---|---|---|
| A. Port substantially as-is | Maximum short-term behavior similarity | Carries Perl CGI, SSI, path trust, dual state, and weak identity boundaries | Reject as foundation |
| B. Wrap legacy behind WordPress | Fast URL/content bridge; preserves archive output | Keeps Sandy in the write path, complicates auth, groups, notifications, rollback, and observability | Transitional read-only bridge only |
| C. Extract rules and rebuild natively | Clear WordPress auth, groups, moderation, Portable Views, Core Terms, and notification boundaries | Requires characterization, mapping, import, URL compatibility, and staged rollout | Preferred |
| D. Replace with compatibility-only archive | Lowest new-system complexity | Risks loss of active behavior, moderation evidence, and user expectations | Suitable only after product/archive decisions |

## Preferred strategy

Choose C, preceded by a bounded B-style read-only compatibility layer for legacy
URLs and immutable content. Do not proxy new writes to the CGI. Re-express
validation, threading, authorship, moderation, and archive rules as explicit
WordPress-native contracts. Make WordPress post/group identity canonical;
retain `chat_posts`, static files, and legacy URL fields as migration evidence
or compatibility records until reconciliation is complete.

The strategy supports Community 3.0 groups, privacy, moderation, notifications,
Portable Views, and Core Terms because each can receive explicit modern
boundaries rather than inheriting filesystem conventions. Rollback is a staged
route switch plus immutable legacy reads, not a return to an uncharacterized
dual writer.
