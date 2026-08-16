const { defineConfig, devices } = require('@playwright/test');

const phpBinary = process.env.POLITIEKPRAAT_PHP_BIN || 'php';
const baseURL = 'http://127.0.0.1:8765';

module.exports = defineConfig({
  testDir: './tests',
  fullyParallel: false,
  workers: 1,
  timeout: 30_000,
  use: {
    baseURL,
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    reducedMotion: 'reduce',
  },
  webServer: {
    command: `"${phpBinary}" -S 127.0.0.1:8765 dev-router.php`,
    url: `${baseURL}/contact`,
    env: {
      ...process.env,
      POLITIEKPRAAT_URLROOT: baseURL,
    },
    reuseExistingServer: false,
    timeout: 30_000,
  },
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],
});
