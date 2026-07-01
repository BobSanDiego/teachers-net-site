const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/browser',
  timeout: 30000,
  retries: 0,
  use: {
    baseURL: process.env.TNET_BASE_URL || 'https://teachers-net.ddev.site',
    ignoreHTTPSErrors: true,
    trace: 'off',
    screenshot: 'off',
    video: 'off'
  },
  reporter: [['list']]
});
