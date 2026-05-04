import { Page } from '@playwright/test';

export async function loginAs(
  page: Page,
  username: string,
  password: string = process.env.ELGG_ADMIN_PASSWORD || 'admin12345'
) {
  // Elgg's login form is an AJAX form (elgg-js-ajax-form), so a normal
  // submit click doesn't reliably navigate. Fetch the CSRF token from
  // /login and POST directly through page.request — the session cookie
  // travels with the page context.
  await page.goto('/login');
  const token = await page.locator('input[name="__elgg_token"]').first().getAttribute('value');
  const ts = await page.locator('input[name="__elgg_ts"]').first().getAttribute('value');
  if (!token || !ts) {
    throw new Error('Could not read Elgg CSRF token from /login');
  }
  const res = await page.request.post('/action/login', {
    form: {
      __elgg_token: token,
      __elgg_ts: ts,
      username,
      password,
    },
  });
  if (!res.ok()) {
    throw new Error(`Login POST failed: HTTP ${res.status()}`);
  }
  await page.goto('/');
}
