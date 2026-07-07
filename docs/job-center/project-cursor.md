# Job Center Project Cursor

## Project State

Active Development

## Current Phase

Teachers.Net Jobs V1 release-candidate readiness.

## Current Reference Page/Flow

Jobs Seed Dataset readiness before importer work.

## Current Primitive/Task

Review and refine `data/jobs-seed.json` so it is realistic, varied, synthetic,
safe, and useful before building a seed importer.

## Next Decision

Decide whether to run a J158 content-refinement pass before importer planning.

## Open Risks

- The generated JSON is structurally sound but content still reads too
  formulaic in places.
- No importer exists yet; `data/jobs-seed.json` has not been loaded into
  WordPress.
- V1 release-candidate declaration is still pending Engineering Director
  approval.
- Production deployment, monitoring, and rollback planning remain pending.

## Stop After

Stop after each seed dataset review/refinement pass for human review. Do not
build the importer, change schema, alter Core Terms, or reopen public UI work
unless explicitly directed.
