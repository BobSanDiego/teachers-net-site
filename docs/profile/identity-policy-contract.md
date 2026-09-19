# Public Identity Policy Contract

`TNet_Identity_Policy` is the single server-side authority for permanent
usernames and non-unique Display Names. The signup and Screen 4 controllers
call it before WordPress persistence; browser code mirrors only local syntax
feedback and never receives reservation lists or category provenance.

## Username reservation comparison

Usernames remain lowercase ASCII handles using `a-z`, `0-9`, `.`, `_`, and
`-`, at 3–30 characters. Reservation comparison lowercases input, removes
permitted separators, and maps only basic obvious leetspeak (`0→o`, `1→i`,
`3→e`, `4→a`, `5→s`, `7→t`). It intentionally does not use Unicode confusable
matching.

The policy has four data-owned categories: platform/system, generic
educational/site namespace, automation/AI, and staff-reserved. Generic entries
are exact normalized reservations, never substring bans; `teacherbob`,
`mathmom`, and `principaljones` remain ordinary possible handles. Only credible
Teachers.Net/`tnet` plus platform-role combinations receive combination
protection. Rejections expose only an unavailable/choose-another result, never
staff provenance.

## Display Names

Display Names are trimmed, repeated whitespace is normalized, and the result
must be 2–50 characters with at least one Unicode letter or number. Ordinary
human-name punctuation is permitted. Control, line-break, markup and invisible
format input are rejected. Display Names are deliberately non-unique. Platform
impersonation is protected narrowly; descriptive names such as `Teacher Bob`
and `Professor Smith` remain valid.
