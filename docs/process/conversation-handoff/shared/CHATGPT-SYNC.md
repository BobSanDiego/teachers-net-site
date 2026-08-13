# Incremental Global ChatGPT Synchronization

## Purpose and authority boundary

This is the shared Workflow V2 owner for optional cross-project ChatGPT delta
transport. It is not a replacement for repository authority, `PREPARE HANDOFF`,
portable masters, or immutable handoff records. Reader-visible conversation is
preserved as evidence with provenance and completeness warnings.

The checked-in registry `chatgpt-sync-registry.json` is the only authority for
which exact live thread may be read. A matching title is not sufficient. An
ACTIVE entry must match its thread ID, expected title, and, when recorded,
account/project identity. REPLACED and RETIRED entries retain history; an
unresolved identity remains unregistered rather than guessed.

## Normal use

Normal work has no synchronization ritual. If structured workflow metadata
shows a material sibling-context risk, Codex may emit:

```text
GLOBAL CHATGPT SYNC RECOMMENDED
Reason: <structured signal>
Command: UPDATE CHATGPT
```

That recommendation is cheap metadata inspection only. It does not open a
conversation, retrieve text, summarize, or create a generation.

After the Engineering Director issues `UPDATE CHATGPT`, Codex:

1. reads the registry and the Shared Workflow sync ledger;
2. reads only the minimum app-reader pages for every ACTIVE exact thread;
3. gathers only reader-visible items newer than each stored source boundary;
4. stops as soon as the known boundary is found;
5. writes a normalized transient reader-page input and runs
   `tools/chatgpt_sync/sync.py build --reader-json <input>`;
6. delivers the one generated `G<n>` package without semantic reconciliation.

The builder preserves project, thread, turn/item IDs, timestamps, role order,
fixed-marker payload checksum, source boundaries, and recipient status. The
archive and durable ledger are under:

```text
tmp/hopper/shared-workflow/chatgpt-sync/
  ledger.json
  archive/G<n>-chatgpt-sync.md
  archive/G<n>-manifest.json
```

No automatic pruning is permitted. The transient input is not a portable
master and is not published as a product Report/Hopper artifact.

## Bounded reader contract

The implementation ceilings are configurable module constants and currently:

- 50,000 reader-visible characters per source;
- 125,000 reader-visible characters per generation;
- six reader pages per source.

G1 is a bounded exception: it uses one recent reader page per active source,
with a 20,000-character source ceiling and 60,000-character generation
ceiling. G1 records the exact first-included and last-included item/turn
provenance and establishes its source boundary at the oldest included item.
It explicitly marks `PRE-BASELINE HISTORY NOT INCLUDED`; it never claims the
thread is historically complete. G2 and later use the normal 50,000-character,
125,000-character-generation, six-page incremental ceilings from the stored
boundary.

Stop before exceeding a ceiling with `UPDATE CHATGPT BLOCKED — DELTA TOO
LARGE`, naming the affected source and known boundary. A first baseline is
complete only if the supplied reader pages reach the reader-visible beginning;
otherwise it also fails closed. Do not silently package an arbitrary latest
page as complete history.

Fail closed, without creating a generation, when an item is truncated, a prior
boundary is missing, a thread identity differs, the reader cannot represent
required attachment/tool context, or a required ACTIVE source is omitted. The
operator then uses the ordinary handoff/export path or obtains an explicit
Engineering Director decision; never raise the reader limits automatically.

## Acknowledgment and catch-up

Creating or delivering a generation leaves every ACTIVE recipient `PENDING`.
After a recipient ingests the whole package, it emits exactly:

```text
SYNC ACK: G<n> <payload-sha256>
```

On a later ordinary Codex interaction, a bounded exact-recipient tail read may
verify that literal assistant marker and invoke `sync.py ack`. Only that
recipient moves to `ACKNOWLEDGED`. An unacknowledged recipient remains behind
and receives later generations from its own recorded acknowledgment boundary;
acknowledgment is never advanced by package creation or another project’s ACK.
Any recovery override requires existing Engineering Director authority and
records its provenance.

## Compatibility and non-goals

The generation ID, checksum, provenance, boundary, and acknowledgment
primitives are reusable by future semantic-sync work. Raw conversation is not
semantic authority. This mechanism never reads portable masters during routine
updates, regenerates handoffs, modifies product work, or bypasses the existing
file-driven `PREPARE HANDOFF` lifecycle.
