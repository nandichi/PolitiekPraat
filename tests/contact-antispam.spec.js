const { test, expect } = require('@playwright/test');

test.describe('contactformulier anti-spam', () => {
  test('rendert sessiechallenge, honeypot en serverlimieten', async ({ page }) => {
    await page.goto('/contact');

    await expect(page.getByRole('heading', { name: 'Heb je een vraag, suggestie of tip?' })).toBeVisible();
    await expect(page.locator('input[name="csrf_token"]')).toHaveAttribute('value', /^[a-f0-9]{64}$/);
    await expect(page.locator('input[name="contact_challenge"]')).toHaveAttribute('value', /^[A-Za-z0-9_-]+$/);
    await expect(page.locator('input[name="website"]')).toHaveCount(1);
    await expect(page.locator('input[name="website"]')).toHaveAttribute('tabindex', '-1');
    await expect(page.locator('input[name="website"]')).toHaveAttribute('autocomplete', 'off');
    const honeypotStyles = await page.locator('input[name="website"]').evaluate((input) => {
      const styles = getComputedStyle(input.parentElement);
      return { position: styles.position, width: styles.width, height: styles.height, overflow: styles.overflow };
    });
    expect(honeypotStyles).toEqual({ position: 'absolute', width: '1px', height: '1px', overflow: 'hidden' });
    await expect(page.locator('#name')).toHaveAttribute('maxlength', '100');
    await expect(page.locator('#email')).toHaveAttribute('maxlength', '254');
    await expect(page.locator('#subject')).toHaveAttribute('maxlength', '160');
    await expect(page.locator('#message')).toHaveAttribute('maxlength', '5000');
  });

  test('blokkeert een ingevulde honeypot zonder mailtransport', async ({ page }) => {
    await page.goto('/contact');
    await page.locator('#name').fill('Bot Test');
    await page.locator('#email').fill('bot@example.com');
    await page.locator('#subject').fill('Geautomatiseerd bericht');
    await page.locator('#message').fill('Dit bericht mag nooit bij het mailtransport komen.');
    await page.locator('input[name="website"]').fill('https://spam.invalid', { force: true });
    await page.getByRole('button', { name: 'Verstuur bericht' }).click();

    await expect(page).toHaveURL(/\/contact$/);
    await expect(page.getByText('Het bericht kon niet veilig worden geverifieerd.')).toBeVisible();
    await expect(page.getByText('Je bericht is verzonden!')).toHaveCount(0);
  });

  test('heeft geen horizontale overflow op mobiel', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/contact');

    const dimensions = await page.evaluate(() => ({
      scrollWidth: document.documentElement.scrollWidth,
      clientWidth: document.documentElement.clientWidth,
    }));
    expect(dimensions.scrollWidth).toBeLessThanOrEqual(dimensions.clientWidth);
    await expect(page.getByRole('button', { name: 'Verstuur bericht' })).toBeVisible();
  });

  test('sluit de historische JSON-mailroute', async ({ request }) => {
    const response = await request.post('/api/contact', {
      data: {
        name: 'Bot',
        email: 'bot@example.com',
        subject: 'Bypass',
        message: 'Deze route mag nooit meer een mail versturen.',
      },
    });

    expect(response.status()).toBe(410);
    const body = await response.json();
    expect(body.success).toBe(false);
  });
});
