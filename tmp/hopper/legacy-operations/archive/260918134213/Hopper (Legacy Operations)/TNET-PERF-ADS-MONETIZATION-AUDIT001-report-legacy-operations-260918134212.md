# TNET-PERF-ADS-MONETIZATION-AUDIT001 — Teachers.Net Monetization Diagnostic

**Status:** COMPLETE — read-only diagnostic. No production, Google account, AdSense, GA4, consent, or advertising configuration was changed.

## Terminal diagnosis

The evidence disproves a single site-wide “missing tag” explanation.  The tested live pages use the correct Google publisher identity (`pub-5374920468878647`), the live `ads.txt` declares that publisher directly, and multiple routes successfully loaded and rendered Google Publisher Tag (GPT) inventory at desktop width.  The monetization decline instead has several contributing seams:

1. **Confirmed legacy mobile delivery failures:** `/lessons/` and `/lessonplans/grades/3-5/` throw `adsbygoogle.push()` `TagError: No slot size for availableWidth` (270 and 250 respectively) at the tested 500 px viewport. Fixed 728×90/468 px placements also overflow the viewport, while some GPT slots request into zero-sized containers.
2. **Confirmed route/template inventory divergence:** mentoring board pages suppress all observed inventory at 500 px; the root and mentor-topic route can issue a GPT request into a CSS-zero-sized element; old lesson-family pages declare five GPT slots but only a subset has nonzero rendered geometry; and the tested Gazette route resolves to an old external page with no detected ad inventory.
3. **Confirmed stranded Jobs GPT configuration:** `/jobs/` registers three GPT slot definitions but none of their target elements is present and no GPT `gampad/ads` request follows. Jobs does make AdSense requests, so this does not explain its relatively stronger AdSense Page RPM; it is a separate delivery/provenance defect.
4. **Revenue/fill/traffic effects are also material but not causally proven:** current AdSense reporting shows sharply lower Page RPM and monetizable impressions than the available historic window. Verified GA4 traffic is direct-heavy and internationally concentrated. Those facts can affect eligibility/value, but do not prove invalid traffic, bot traffic, or a particular auction/fill cause.

The immediate engineering priority is a narrowly scoped, source-owned mobile delivery repair for the legacy lessons/lessonplans renderer—not a blanket tag reinstallation.

## Scope and authority observed

- Production identity: live `teachers.net` pages in the isolated Windows Chrome/CDP audit context.
- AdSense identity: authenticated publisher account `pub-5374920468878647`; reporting site `teachers.net` was selected and verified.
- GA4 identity: account `teachers.net 5908878`, property **Teachers.Net - GA4 397062190**. An initially open unrelated GA4 property was discarded.
- Evidence sources: live browser DOM/resource/console state, current targeted legacy-source inspection, live `ads.txt`, authenticated read-only AdSense/GA4 reports.
- Boundary honored: no Google settings, ad units, Auto Ads, consent, GA4 configuration, source, production, or AWS resource was modified.

## Live runtime coverage

| Route / route family | Desktop observed | 500 px observed | Interpretation |
| --- | --- | --- | --- |
| `/` | Three GPT slots declared, requested, and rendered: top 750×90, left 160×600, right 300×250. Sidebar AdSense `1221483587` was `unfill-optimized`. | Top/left/AdSense geometry zero; right GPT requested but target geometry zero. | Global scripts/publisher are functional; responsive delivery is not reliable. |
| `/index.html` | Native browser final state was `404 Error: Document not found`; zero AdSense slots, GPT slots, and ad requests. | Not separately tested. | This high-volume AdSense URL-channel label is currently a non-inventory 404 route, not the live root route. |
| `/chat/` | Same three GPT placements requested/rendered; sidebar AdSense `1221483587` `unfill-optimized`. | Not separately retested at 500 px. | Modern-shell desktop inventory works. |
| `/mentors/` | Top and left GPT placements requested/rendered; right placement intentionally absent; sidebar AdSense `unfill-optimized`. | Top/left zero; no GPT requests; AdSense zero-sized/unfilled. | Board/list mobile route had effectively no observed visible inventory. |
| `/mentors/english/` | Three GPT placements requested/rendered; sidebar AdSense `unfill-optimized`. | Right GPT request/iframe into zero geometry; top/left/AdSense zero. | Topic route differs from board route but still has mobile geometry failure. |
| `/lessons/` | Five GPT definitions and requests; sky, top, and big box nonzero; multiunit/footer collapsed. Fixed AdSense `1692171583` was 468×15 and unfilled. | Fixed placements overflow; fresh navigation raised `No slot size for availableWidth=270`; footer requested into zero geometry. | Confirmed mobile AdSense initialization failure and legacy layout mismatch. |
| `/lessonplans/grades/3-5/` | Same five-definition pattern; sky/top/big box nonzero and multiunit/footer collapsed. | Fixed placements overflow; fresh navigation raised `No slot size for availableWidth=250`. | Same shared legacy mobile failure pattern. |
| `/jobs/` | Three GPT definitions but no target elements and no GPT ad requests. Two AdSense slots (`2885713409`, `3871492012`) requested but were `unfill-optimized`. | Both AdSense containers measured about 485×485 and unfilled; still no GPT requests; no tested AdSense TagError. | Stranded GPT configuration is separate from Jobs’ comparatively strong reported RPM. |
| `/states/` | Five GPT definitions; only sky/top/footer emitted/requested, with sky/top nonzero and footer collapsed. Fixed AdSense `1692171583` was unfilled. | Not separately retested at 500 px. | Older template has partial/collapsed inventory. |
| `/gazette/current/` | Resolves to `gazette.teachers.net/gazette/wordpress/december-2017/`; no detected ad loader, slots, or ad requests. | Same final external route. | The tested public path has zero observed inventory. |

The live response emitted no named CMP API (`__tcfapi`/`googlefc`) in this browser state and no CSP response header that would block the observed Google assets. This is limited negative evidence, not a complete privacy/compliance assessment.

## Source and configuration provenance

- Checked-in legacy theme templates `wordpress/wp-content/themes/twentysixteen-boss/sidebar-skyblock.php` and `sidebar-leftpanel.php` contain the sidebar AdSense publisher/slot `ca-pub-5374920468878647` / `1221483587`; those templates also call the named left/right GPT display locations.
- The live root GPT configuration and the Jobs `RD-*` target/configuration owner are not represented in the inspected legacy or control-plane source. This is a provenance gap: do not make a broad source change until the deployed owner is reconciled.
- The old lessons/lessonplans mobile renderer that produces the confirmed errors is likewise not fully source-addressable in the checked-in repositories. The repair ticket must first locate that deployed owner and preserve route-specific behavior.
- Duplicate legacy AdsByGoogle loader emission was observed in the older response family. It is a hygiene concern, but this audit does **not** establish it as the cause of the mobile TagErrors or RPM decline.

## Reporting evidence

### Recent AdSense URL Channels

For the verified `teachers.net` site, the recent 28-day comparison report (Aug 21–Sep 17 2026) reported 383,626 page views, $29.23 earnings, **$0.08 Page RPM**, 23,105 monetizable impressions, $1.26 impression RPM, 54.97% Active View viewability, and 175 clicks. The immediately preceding 28 days showed 660,224 page views, $0.09 Page RPM, and 36,717 impressions.

Notable current route rows: lessons $0.36 Page RPM/5,233 impressions; lessonplans $1.01/2,314; `index.html` $0.05/7,449; Jobs $1.45/773; mentors $0.01/1,281; chat $0.21/466; states $0.02/1,656. Jobs is comparatively dense despite the stranded GPT definitions.

### Historic available AdSense comparison

AdSense limited the historic report to Sep 18–Oct 15 2023, so it is not a date-matched causal comparison. In that 28-day window, the verified site reported $318.95 earnings, 248,180 page views, **$1.29 Page RPM**, and 123,719 impressions. Lessonplans reported $4.03 Page RPM/25,326 impressions; lessons $1.70/20,241; mentors $0.18/10,655; Jobs $1.07/10,390. The magnitude and direction support the Director’s reported historical degradation, while the different date window prevents a precise delta claim.

### GA4 diagnostic cues

In the verified Teachers.Net GA4 property’s recent home reporting, Direct accounted for roughly 8.9K sessions, versus 471 Organic Search, 286 Unassigned, 120 Referral, and 31 AI. Visible active-user geography was led by Hong Kong (~5.9K), followed by Singapore/United States/China (~1.1K each), then India and others. This points to a direct-heavy, international mix worth segmenting by page, device, country, and acquisition. It is not evidence by itself of invalid traffic or revenue causality.

## Recommendations — no changes made

1. **Targeted legacy mobile delivery ticket (highest priority):** recover the deployed owner for the lessons/lessonplans shared ad renderer, then make only a tested responsive slot/geometry correction. Acceptance should require no `No slot size` console error, no offscreen fixed ad geometry, and real ad-request/rendering evidence at mobile and desktop widths.
2. **Resolve deployment provenance:** map the live GPT bootstrap/header and Jobs template owner to a source-controlled deployment. Reconcile or remove the Jobs `RD-HPA-A`, `RD-Leaderboard-A`, and `RD-IMU-A` definitions only after confirming intended GAM ownership and dependencies.
3. **Audit modern-shell mobile rules by route:** repair zero-dimension request containers and the mentoring board’s zero-inventory mobile behavior only after a route-by-route product decision on intended placements.
4. **Analytics continuation, still read-only:** segment AdSense/GA4 by path, device, country, source/medium, and time period; distinguish traffic-mix/value changes from delivery/fill failures before changing monetization policy.
5. **Gazette and `index.html` ownership decision:** identify whether those URL-channel paths are retained traffic destinations, redirects, or historical artifacts; their current tested destinations produce no inventory.

## Acceptance ledger

- **PROVEN:** correct publisher identity and `ads.txt` declaration; working GPT desktop inventory on root/chat/mentor topic; no global missing-tag condition.
- **PROVEN:** legacy lessons/lessonplans mobile AdSense TagErrors and overflow geometry at 500 px; Jobs GPT definitions lack mounted target elements/requests; tested Gazette route has no inventory; `index.html` is a native 404 with no inventory.
- **DIAGNOSTIC, NOT CAUSAL:** current RPM/impression decline, direct-heavy/international GA4 mix, unfilled/optimized slots, and absence of named consent APIs in the observed state.
- **UNRESOLVED OWNER:** deployed legacy lessons/lessonplans renderer, root GPT bootstrap, and Jobs `RD-*` GPT configuration.

## Next operations objective

`TNET-PERF-LEGACY-MOBILE-AD-DELIVERY-CONVERGENCE001`: locate the deployed legacy lessons/lessonplans renderer, reproduce the two mobile TagErrors, and implement/verify a narrow responsive delivery repair without changing AdSense, GAM, GA4, consent, or account configuration.
