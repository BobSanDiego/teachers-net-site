# Decision Log

- Core Terms stabilized before Jobs begins.
- Jobs is the first consumer plugin after Core Terms.
- Jobs must be separate from Core Terms.
- Core Terms remains folder/repo `profilaxes` for now.
- Do not rename CFM namespaces, DB tables, slugs, or file paths yet.
- Jobs should use custom tables, not CPT as primary storage.
- Employer is first-class.
- Employer is not a Core Term.
- Classification belongs in Core Terms.
- Job-specific lifecycle belongs in Jobs.
- Promotion and billing must not contaminate the jobs table.
- External apply is acceptable for launch, but apply tracking must be future-compatible.
- ATS, resumes, candidate search, interviews, offers, and hires are reserved future objects.
- Candidate search should not launch before marketplace liquidity exists.
- Resume search should eventually be gated by verification, entitlement/paywall, and candidate consent.
- Users may hold multiple Jobs identities; avoid ranked single-role ladders.
- Permissions should be capability-based and employer-scoped.
