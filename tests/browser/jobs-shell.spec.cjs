const { expect, test } = require('@playwright/test');

const CONTAINER_SELECTORS = [
  '.tnet-jobs-app-topbar-inner',
  '.tnet-jobs-browse-hero',
  '.tnet-jobs-public-inner',
  '.tnet-jobs-app-footer-inner'
];

async function collectPageErrors(page) {
  const errors = [];

  page.on('console', (message) => {
    if (message.type() === 'error') {
      errors.push(`console: ${message.text()}`);
    }
  });

  page.on('pageerror', (error) => {
    errors.push(`pageerror: ${error.message}`);
  });

  return errors;
}

async function verifyJobsPublicPage(page, path) {
  const errors = await collectPageErrors(page);
  const response = await page.goto(path, { waitUntil: 'domcontentloaded' });

  expect(response, `${path} should return a response`).not.toBeNull();
  expect(response.status(), `${path} should load successfully`).toBeLessThan(400);

  await page.waitForLoadState('networkidle', { timeout: 5000 }).catch(() => {});

  const pageMetrics = await page.evaluate((selectors) => {
    const containers = selectors
      .map((selector) => {
        const element = document.querySelector(selector);

        if (!element) {
          return null;
        }

        const rect = element.getBoundingClientRect();

        return {
          selector,
          width: rect.width,
          left: rect.left,
          right: rect.right
        };
      })
      .filter(Boolean);

    return {
      clientWidth: document.documentElement.clientWidth,
      scrollWidth: document.documentElement.scrollWidth,
      containers
    };
  }, CONTAINER_SELECTORS);

  expect(
    pageMetrics.scrollWidth,
    `${path} should not introduce horizontal overflow`
  ).toBeLessThanOrEqual(pageMetrics.clientWidth + 1);

  expect(pageMetrics.containers.length, `${path} should expose Jobs layout containers`).toBeGreaterThan(0);

  for (const container of pageMetrics.containers) {
    expect(
      container.width,
      `${path} ${container.selector} should stay within the 1200px shell`
    ).toBeLessThanOrEqual(1201);
  }

  expect(errors, `${path} should not emit console/page errors`).toEqual([]);
}

test.describe('Jobs public shell', () => {
  test('browse landing loads and uses the canonical shell', async ({ page }) => {
    await verifyJobsPublicPage(page, '/jobs/');
  });

  test('alerts management route loads and uses the canonical shell', async ({ page }) => {
    await verifyJobsPublicPage(page, '/jobs/alerts/');
  });
});
