# JC053-STEP3-DIAG003 — Recurring Rich Paste Regression

Status: corrected and pushed, with one evidence limit: no raw clipboard capture from the reported Indeed listing was attached.

Canonical review URL: http://127.0.0.1:8768/?#step-03-job-description

## Root cause

The original rich-paste restoration (`9d5d880`) introduced HTML-first insertion and preserved top-level div paragraphs. The subsequent wrapper correction (`f8269c2`, carried into `320f598`) classified `div` elements in document order. For nested wrappers, the outer wrapper was unwrapped before its child div paragraphs were converted to paragraphs, so the child text was concatenated and later normalized as one paragraph. This explains the recurring regression without requiring a second paste listener or a sanitizer bypass.

## Correction

`step3Sanitized` now processes div elements deepest-first. Child div paragraphs are converted to `p` blocks before a block-containing wrapper is unwrapped. The existing HTML/plain-text branch, single paste listener, and shared preview normalization remain unchanged.

Commit: `6f07e96` (`JC053 diagnose nested rich paste regression`), pushed to `origin/JOB-CENTER-JC053-wizard-workbench`.

## Verification

Runtime identity after restart:

- worktree: `/home/bobreap/projects/teachers-net-jobcenter`
- branch: `JOB-CENTER-JC053-wizard-workbench`
- commit/banner: `6f07e96`, asset `jc053-6f07e96`
- docroot: `/home/bobreap/projects/teachers-net-jobcenter/tmp/jc053-wizard-workbench`
- server: `python3 -u tools/qa/serve-jc053-workbench.py --port 8768`

Cache-bypassed browser checks at the canonical URL passed for nested wrapper divs, Indeed-shaped div paragraphs, Word-shaped headings/lists, and unsafe script/event markup. The nested case now produces two editor and preview paragraphs:

```html
<p>First paragraph.</p><p>Second <em>formatted</em> paragraph.</p>
```

`git diff --check` and bundled Node syntax checking passed. A raw Indeed clipboard payload remains required for final source-specific confirmation; the available Indeed screenshots do not contain clipboard HTML and were not treated as that evidence.
