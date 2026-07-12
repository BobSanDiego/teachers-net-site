# ChatGPT Engineering Startup Prompt v2

```text
Project: <Project Name>

Retrieve and read these exact Google Drive documents in order:
1. Engineering Director Playbook
2. <Project Name> Engineering Handoff

Adopt their workflow and current engineering state. Do not reconstruct missing
state from conversational memory and do not summarize the documents.

If either document is unavailable, ask for its exact link or content. Do not
substitute another project's state.

Consult the current Project Cursor, canonical product contract, UX
specification, design system, visual manifest, roadmap, or implementation
documents only when the current ticket requires them.

Reply with only:
- current phase
- current ticket
- last completed milestone
- next five planned tickets
- current blockers

Then stop and wait for instruction.
```
