# Profile Screen 5 — Voluntary Location Context Contract

Status: COMPLETE/FROZEN following Engineering Director HUMAN_QA PASS under
`PROFILE-ONBOARDING-LOCATION-SCREEN5-CLOSEOUT001`.

Screen 5 is the optional location-context step after the accepted public
identity step. It is not an address, device-location, or member-demographics
system.

## Collected data

The canonical `TNet_Identity_Service` stores only explicitly submitted values
in WordPress user meta:

- `_tnet_profile_country_code` — an ISO 3166-1 alpha-2 country code;
- `_tnet_profile_region_code` — a U.S. postal state/District of Columbia code,
  only when the country is `US`.

The initial United States choice is presentation only. It persists nothing
until the member explicitly selects a state and continues. Choosing a country
outside the United States deletes any region code. The active mode is the only
value the server accepts; the browser clears an inactive unsaved choice on a
mode change.

Screen 5 never requests or infers city, ZIP/postal code, street address,
precise coordinates, IP-derived location, device location, locale, or time
zone. Country and state selectors are retrieval/context choices, never member
demographic classifications.

## Lifecycle

New accounts receive an Identity-owned pending marker after verification. The
accepted Screen 3 and Screen 4 steps route to the next pending step, so a
completed Screen 4 opens `/account/location/` while Location is pending. An
explicit valid location submission or Skip clears only that pending marker and
continues to `/jobs/`. Skip fabricates no location data.

The step is onboarding-only: a user without the pending marker is routed to the
ordinary post-onboarding destination rather than replaying the form.

## Ownership and exclusions

`TNet_Identity_Location_Policy` owns the bounded U.S. state/District and
country-code allowlists. `TNet_Identity_Service` owns validation and persistence.
`TNet_Identity_Public` owns the `/account/location/` route and focused-shell
rendering. The Profile avatar resolver, portrait bank, Screen 3/4 contracts,
Jobs location/address behavior, and later educator/employer/general branching
are outside this contract.
