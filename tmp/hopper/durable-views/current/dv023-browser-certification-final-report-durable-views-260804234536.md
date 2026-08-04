# DV-023 Browser Certification — Final Report

Date: 2026-08-04
Cycle: 260804234536
Status: COMPLETE

## Result

The authenticated DV-023 employer acceptance path passed on the local DDEV
site without bypassing Jobs authorization or reconstructing Durable View
composition in the consumer.

The existing local WordPress `admin` account was granted a temporary active
membership through `TNet_Jobs_Employer_Service::create_employer()` and
`TNet_Jobs_Employer_User_Service::add_user_to_employer()`:

- Employer: `Durable Views DV023 QA Employer` (ID 237)
- Membership: ID 155, role `poster`, status `active`
- QA URL: https://teachers-net.ddev.site/jobs/employer/new/

## Browser evidence

1. With the published binding `View 10 / Version 12` present, the authenticated
   employer form reached `Qualifications` and displayed exactly the bound
   Durable View option `Grade Level` for the Grade Level field. The page showed
   the selected employer context `Durable Views DV023 QA Employer`.
2. The protected Jobs admin control at
   https://teachers-net.ddev.site/wp-admin/admin.php?page=tnet-jobs-job-categories&tab=map&edit_field_id=4
   removed the binding through the visible `Remove Durable View binding` action
   and returned `Mapping updated.`
3. After cache-bypassed reload and the normal employer-form progression, the
   unbound fallback displayed the legacy Grade Level children: `Early
   Childhood`, `Elementary`, `Middle School`, `High School`, `Adult Education`,
   and `Higher Education`. This demonstrates that fallback is materially
   different from the one-option bound View and remains functional.
4. The original binding was restored through the established
   `TNet_Jobs_Durable_Views_Service::bind_field(4, 10, 12)` boundary.

## Cleanup

The temporary QA membership was deactivated with
`TNet_Jobs_Employer_User_Service::deactivate_membership(155, 1)`. The temporary
QA employer was archived with `TNet_Jobs_Employer_Service::archive_employer(237)`.
The existing `admin` WordPress account was preserved. No production migration,
raw SQL mutation, or authorization weakening was used.

## Verification

- Employer session authorization: PASS
- Bound Durable View options: PASS
- Jobs admin unbind action: PASS
- Employer-form legacy fallback: PASS
- Binding restored: PASS
- QA membership cleanup: PASS (membership deactivated; employer archived)
- Production migration: NOT RUN
- Screenshot attachment: unavailable; Chrome MCP reported it could not save
  the requested local screenshot path. Text snapshots and URLs above are the
  authoritative browser evidence for this cycle.

## Git handoff

- Root branch: `COMMUNITY003-semantic-community-communications-working-draft`
- Durable Views documentation commit: pending until this report is committed
- Profilaxes implementation commit: `ab3f0d5` (pushed)
- Jobs implementation commit: `e631597` (pushed)
- Push status: documentation push pending
- Git status: unrelated pre-existing root changes preserved; only the named
  Durable Views documents and this cycle artifact are in scope
- Milestone tag: none created in this ticket
