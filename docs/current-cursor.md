# Current Cursor

Current project phase:
Teachers.Net Jobs planning after Core Terms stabilization.

Core Terms:
- Stabilized and tagged v0.6.0.
- Plugin folder/repo remains `profilaxes`.
- Visible product name is Core Terms.
- Owns term hierarchy, assignment, compilation, stable IDs, APIs, hooks, and Labs diagnostics.
- Does not own jobs, resumes, billing, ATS, recruiter permissions, or candidate search.

Jobs:
- Not yet coded.
- Next task is J1 — Jobs Entity Model v0.1.
- No plugin skeleton until entity model and schema guardrails are accepted.

Next safe task:
Define durable Jobs entities before tables:
- Job
- Employer
- Employer User / Membership
- Job Term Assignment
- Job Event
- Job Visibility
- Apply Method
- Reserved future objects: Applications, Candidate Profiles, Resumes, Interviews, Offers, Hires

Key decisions:
- Jobs should use custom tables, not WordPress posts as primary storage.
- Employer is first-class, not `company_name` on job.
- Classification attaches through Core Terms, not hardcoded job columns.
- Promotion/billing are separate from Jobs.
- ATS/resume/candidate search are future-compatible but deferred.
