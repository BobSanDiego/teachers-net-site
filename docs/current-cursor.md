# Current Cursor

Current project phase:
Teachers.Net Jobs foundation implementation after Core Terms stabilization.

Core Terms:
- Stabilized and tagged v0.6.0.
- Plugin folder/repo remains `profilaxes`.
- Visible product name is Core Terms.
- Owns term hierarchy, assignment, compilation, stable IDs, APIs, hooks, and Labs diagnostics.
- Does not own jobs, resumes, billing, ATS, recruiter permissions, or candidate search.

Jobs:
- Plugin exists at `wordpress/wp-content/plugins/tnet-jobs`.
- Remote: `git@github.com:BobSanDiego/tnet-jobs.git`.
- Published milestone: `c9cb881 Add employer membership admin management`.
- Published tag: `v0.9.0-employer-membership-foundation`.
- Six MVP custom tables exist:
  - `tnet_jobs`
  - `tnet_jobs_employers`
  - `tnet_jobs_employer_users`
  - `tnet_jobs_terms`
  - `tnet_jobs_events`
  - `tnet_jobs_signals`
- Dependency and schema health checks exist.
- Event repository exists.
- Employer repository exists.
- Employer-user membership repository exists.
- Employer-user membership service exists.
- Admin UI can view, create, and deactivate employer memberships.

Next safe task:
J11 — Job Repository.

Implement low-level persistence for the `tnet_jobs` table only:
- `TNet_Jobs_Job_Repository`
- no services yet unless explicitly approved
- no public frontend
- no REST endpoints
- no sample data
- no schema changes
- no Core Terms writes

Key decisions:
- Jobs should use custom tables, not WordPress posts as primary storage.
- Employer is first-class, not `company_name` on job.
- Classification attaches through Core Terms, not hardcoded job columns.
- Jobs may later store Core Terms IDs in its own `tnet_jobs_terms` bridge table.
- Jobs must not write to Core Terms.
- Promotion/billing are separate from Jobs.
- ATS/resume/candidate search are future-compatible but deferred.
- Applications, interviews, offers, and hires remain deferred.
- Public frontend and REST endpoints remain deferred.
