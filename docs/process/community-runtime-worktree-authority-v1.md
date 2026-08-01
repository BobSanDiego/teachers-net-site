# Community Runtime Worktree Authority

Community 3.0 runtime work is authoritative only from:

- Worktree: `/home/bobreap/projects/teachers-net-community3`
- Branch: `COMMUNITY3-ui-working`
- DDEV project: `teachers-net-community3`
- Hostname: `teachers-net-community3.ddev.site`

The mixed repository workspace and the Job Center worktree are not Community
runtime roots. Do not repoint `teachers-net`, import production data, or share a
database between projects. The persistent worktree may use ignored local
WordPress runtime files required by DDEV, but source changes must remain on the
Community branch and secrets/generated dumps must not be committed.
