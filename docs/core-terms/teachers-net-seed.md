# Core Terms Teachers.Net Seed

Purpose: document the local Core Terms framework needed by Teachers.Net Jobs so the Jobs plugin can discover classification sources through the documented Core Terms public API.

## Why This Exists

Teachers.Net Jobs consumes Core Terms for classification. The Jobs adapter expects a Core Terms framework with slug `teachers-net` so the Job Categories admin screen can discover available axes and terms.

Jobs must not create, edit, or own Core Terms data. This seed belongs to Core Terms/local environment setup. Jobs only reads the framework through Core Terms APIs.

## CT1 Local State

CT1 created the minimal local framework and compiled it successfully.

- Framework slug: `teachers-net`
- Framework name: `Teachers.Net`
- Local framework ID: `1`
- Local active version ID: `1`
- Compiled terms: `27`
- Closure rows: `52`
- Relationships: `0`

The numeric IDs are local-environment details. A fresh environment may assign different IDs. The stable contract is the framework slug `teachers-net` and the term structure.

## Seeded Axes And Terms

### Grade Level

- Early Childhood
  - Pre-K
- K-2
- 3-5
- 6-8
- 9-12
- Higher Education

### Subject Area

- English / Language Arts
- Math
- Science
- Social Studies
- Art
- Music
- Special Education
- ESL / ELL
- World Languages
- Technology
- CTE
- PE / Health

### Location

- United States
- California
- Texas
- Florida
- New York

## Core Terms Mechanisms Used

CT1 used Core Terms plugin mechanisms rather than direct SQL:

- `CFM_Framework_Repository::create_framework()`
- `CFM_Framework_Repository::create_version()`
- `CFM_Compiler::compile_version()`

The setup did not modify Jobs code, Jobs tables, or Jobs mappings.

## Verification Commands

Run from the project root:

```bash
ddev wp --path=wordpress eval '$framework = CFM::get_framework("teachers-net"); echo wp_json_encode(["exists" => (bool) $framework, "id" => $framework ? (int) $framework->id : null, "slug" => $framework ? $framework->slug : null, "name" => $framework ? $framework->name : null], JSON_PRETTY_PRINT) . PHP_EOL;'
```

Expected:

- `exists`: `true`
- `slug`: `teachers-net`
- `name`: `Teachers.Net`

```bash
ddev wp --path=wordpress eval '$terms = CFM::get_terms("teachers-net"); echo count($terms) . PHP_EOL;'
```

Expected:

- `27`

```bash
ddev wp --path=wordpress eval '$adapter = new TNet_Jobs_Core_Terms_Adapter(); $discovery = $adapter->discover_sources(); echo wp_json_encode(["axes" => $discovery["available_axes"], "terms" => $discovery["available_terms"]], JSON_PRETTY_PRINT) . PHP_EOL;'
```

Expected:

- `axes`: `3`
- `terms`: `27`

## Future Reproducible Seed Mechanism

Recommended next step: add an explicit Core Terms-owned seed/import mechanism, not a Jobs-owned one.

Preferred options:

1. WP-CLI command in Core Terms.
   - Example shape: `wp core-terms seed teachers-net`
   - Should be idempotent by slug.
   - Should refuse to overwrite an existing non-empty framework unless explicitly forced.

2. Core Terms admin import/export.
   - Store the framework tree as a versioned export/import file.
   - Useful for non-CLI setup and human review.

3. Versioned seed fixture.
   - Keep a canonical JSON/PHP fixture in Core Terms.
   - A CLI command or admin action can import the fixture.

For now, CT1 is a local setup action documented here. Do not run another seed if `CFM::get_framework('teachers-net')` already returns a framework.
