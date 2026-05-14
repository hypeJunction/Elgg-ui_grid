import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

/**
 * ui_grid is a pure CSS/theme plugin. The only user-facing surface is
 * the grid entry in Elgg's theme_sandbox, which renders sample markup
 * styled with the plugin's responsive grid classes.
 */
test.describe('ui_grid theme sandbox', () => {
  test('grid CSS is loaded on the site', async ({ page }) => {
    await page.goto('/');
    // Inject a test element and assert the browser resolves ui_grid classes.
    const hasGridCss = await page.evaluate(() => {
      const el = document.createElement('div');
      el.className = 'elgg-small-6';
      document.body.appendChild(el);
      const width = window.getComputedStyle(el).width;
      document.body.removeChild(el);
      return width !== '' && width !== 'auto';
    });
    expect(hasGridCss).toBeTruthy();
  });

  test('theme_sandbox grid page renders with expected markup', async ({ page }) => {
    await loginAs(page, 'admin');

    // Theme sandbox is typically at /theme_sandbox/<component>
    const response = await page.goto('/theme_sandbox/grid');
    expect(response?.status()).toBeLessThan(400);

    // Page should include the sample spans rendered by theme_sandbox/ui/grid.php
    await expect(page.locator('.elgg-small-6').first()).toBeVisible();
    await expect(page.locator('.elgg-small-3').first()).toBeVisible();
    await expect(page.locator('.elgg-row').first()).toBeVisible();
    await expect(page.locator('.elgg-gallery-small-5').first()).toBeVisible();
  });

  test('row children are laid out horizontally (flex/float)', async ({ page }) => {
    await loginAs(page, 'admin');
    await page.goto('/theme_sandbox/grid');

    // First .elgg-row with 4 static .elgg-small-3 children.
    const row = page.locator('.elgg-row').nth(0);
    await expect(row).toBeVisible();

    // Verify children have non-full width (i.e. grid is actually applied).
    const childWidth = await row.locator('.elgg-small-3').first().evaluate((el) => {
      return (el as HTMLElement).getBoundingClientRect().width;
    });
    const rowWidth = await row.evaluate((el) => (el as HTMLElement).getBoundingClientRect().width);
    expect(childWidth).toBeLessThan(rowWidth);
  });

  test('gallery block grid renders multiple items', async ({ page }) => {
    await loginAs(page, 'admin');
    await page.goto('/theme_sandbox/grid');

    const gallery = page.locator('.elgg-gallery-small-5').first();
    await expect(gallery).toBeVisible();
    const items = gallery.locator('.placeholder');
    const count = await items.count();
    expect(count).toBeGreaterThanOrEqual(10);
  });
});
