import fs from 'node:fs/promises';
import path from 'node:path';

const playwrightModule = process.env.C3_PLAYWRIGHT_MODULE || 'file:///C:/Users/bobre/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright/index.mjs';
const { chromium } = await import(playwrightModule);

const baseUrl = process.env.C3_REVIEW_URL || 'https://teachers-net-community3.ddev.site';
const output = path.resolve(process.env.C3_SCREENSHOT_DIR || 'tmp/qa/runtime-'.concat(new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d+Z$/, 'Z')));
const viewports = [1440, 1024, 768, 390];
const routes = [
  ['feed', '/community/'],
  ['composer', '/community/new/'],
  ['thread', '/community/thread/post:8d59f528a2e11564/'],
];

await fs.mkdir(output, { recursive: true });
const cdpUrl = process.env.C3_CDP_URL || 'http://127.0.0.1:9222';
const browser = process.env.C3_HEADLESS === '1'
  ? await chromium.launch({ headless: true })
  : await chromium.connectOverCDP(cdpUrl);
const context = browser.contexts()[0] || await browser.newContext({ ignoreHTTPSErrors: true });
const manifest = { baseUrl, output, captures: [], generatedAt: new Date().toISOString() };
const saveManifest = () => fs.writeFile(path.join(output, 'manifest.json'), JSON.stringify(manifest, null, 2) + '\n');
for (const width of viewports) {
  for (const [name, route] of routes) {
    const url = new URL(route, baseUrl).toString();
    const capture = { name, route, url, width, status: null, screenshot: null, badge: null, error: null };
    let page;
    try {
      page = await context.newPage({ viewport: { width, height: 1000 } });
      const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
      capture.status = response?.status() ?? null;
      if (page.url().includes('/wp-login.php') && process.env.C3_QA_USER && process.env.C3_QA_PASS) {
        await page.locator('#user_login').fill(process.env.C3_QA_USER);
        await page.locator('#user_pass').fill(process.env.C3_QA_PASS);
        await page.locator('#wp-submit').click();
        await page.waitForLoadState('domcontentloaded');
      }
      capture.badge = await page.locator('.c3-runtime-badge').first().evaluate((el) => ({
        text: el.innerText,
        status: el.dataset.runtimeStatus,
        host: el.dataset.runtimeCanonical_hostname,
        project: el.dataset.runtimeDdev_project,
        commit: el.dataset.runtimeGit_commit,
      })).catch(() => null);
      capture.screenshot = `${name}-${width}.png`;
      await page.screenshot({ path: path.join(output, capture.screenshot), fullPage: true });
    } catch (error) {
      capture.error = String(error?.message || error);
    } finally {
      manifest.captures.push(capture);
      await saveManifest();
      if (page) await page.close().catch(() => {});
    }
  }
}
await saveManifest();
if (process.env.C3_HEADLESS === '1') await browser.close();
console.log(JSON.stringify(manifest, null, 2));
