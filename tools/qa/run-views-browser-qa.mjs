import { chromium } from 'playwright-core';
import { mkdirSync, statSync } from 'node:fs';

const endpoint = process.env.VIEWS_CDP_ENDPOINT ?? process.argv[2] ?? 'http://127.0.0.1:9223';
const reviewUrl = 'https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17';
const screenshot = process.env.VIEWS_QA_SCREENSHOT ?? '/home/bobreap/projects/teachers-net-site/tmp/qa/GOV-VIEWS002A-browser-proof.png';
if (!endpoint) throw new Error('VIEWS_CDP_ENDPOINT is required; run verify-views-browser-qa.sh first.');

const browser = await chromium.connectOverCDP(endpoint);
const pages = browser.contexts().flatMap((context) => context.pages());
const page = pages.find((candidate) => candidate.url().includes('page=cfm-views')) ?? pages[0];
if (!page) throw new Error('No authenticated Chrome page was discoverable.');
const consoleErrors = [];
const pageErrors = [];
page.on('console', (message) => { if (message.type() === 'error' || message.type() === 'warning') consoleErrors.push(`${message.type()}: ${message.text()}`); });
page.on('pageerror', (error) => pageErrors.push(String(error)));
await page.goto(`${reviewUrl}&_codex=gov-views-002a`);
await page.waitForLoadState('domcontentloaded');
await page.waitForTimeout(1000);
const result = await page.evaluate(() => {
  const root = document.querySelector('[data-cfm-views-workbench]');
  const branch = root?.querySelector('.cfm-views-source [data-cfm-views-toggle]');
  const before = branch?.getAttribute('aria-expanded');
  branch?.click();
  const after = branch?.getAttribute('aria-expanded');
  branch?.click();
  const restored = branch?.getAttribute('aria-expanded');
  const label = root?.querySelector('.cfm-views-source .cfm-views-term-name');
  return {
    title: document.title,
    url: location.href,
    editorFound: Boolean(root),
    libraryText: label?.textContent?.trim() ?? null,
    labelFontWeight: label ? getComputedStyle(label).fontWeight : null,
    safeClick: { before, after, restored },
  };
});
mkdirSync(screenshot.substring(0, screenshot.lastIndexOf('/')), { recursive: true });
await page.screenshot({ path: screenshot, fullPage: true });
const bytes = statSync(screenshot).size;
if (!bytes) throw new Error('Screenshot was empty.');
console.log(JSON.stringify({ endpoint, pages: pages.length, result, consoleErrors, pageErrors, screenshot, screenshotBytes: bytes }));
await browser.close();
