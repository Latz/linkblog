/**
 * Playwright — UI tests for the LinkBlog dashboard.
 *
 * Logs into wp-admin and verifies the plugin dashboard renders correctly.
 *
 * Run with: npm run test:e2e:ui
 */

import { test, expect } from '@playwright/test';
import { createRequire }  from 'node:module';
const require = createRequire(import.meta.url);
const { WP_ENV } = require('../../../constants.json');

const ADMIN_URL     = `${WP_ENV.BASE_URL}/wp-admin`;
const DASHBOARD_URL = `${ADMIN_URL}/admin.php?page=linkblog`;

// Shared login helper — reused across tests.
async function wpLogin(page) {
    await page.goto(`${ADMIN_URL}/`);
    await page.fill('#user_login', WP_ENV.ADMIN_USER);
    await page.fill('#user_pass', process.env.WP_ADMIN_PASSWORD ?? WP_ENV.ADMIN_PASSWORD);
    await page.click('#wp-submit');
    await page.waitForURL(/wp-admin/);
}

// ---------------------------------------------------------------------------
// Dashboard presence
// ---------------------------------------------------------------------------
test.describe('LinkBlog dashboard', () => {
    test.beforeEach(async ({ page }) => {
        await wpLogin(page);
    });

    test('page loads without a PHP fatal', async ({ page }) => {
        await page.goto(DASHBOARD_URL);
        // A PHP fatal would render "Parse error" or "Fatal error" in the body.
        await expect(page.locator('body')).not.toContainText('Fatal error');
        await expect(page.locator('body')).not.toContainText('Parse error');
    });

    test('stats header is visible', async ({ page }) => {
        await page.goto(DASHBOARD_URL);
        // The compact stats header added during the dashboard redesign.
        await expect(page.locator('.linkblog-stats, #linkblog-stats')).toBeVisible();
    });

    test('link list renders in the page', async ({ page }) => {
        await page.goto(DASHBOARD_URL);
        // The links table or empty-state message should be present.
        await expect(
            page.locator('.linkblog-links-table, .linkblog-empty-state')
        ).toBeVisible();
    });
});

// ---------------------------------------------------------------------------
// Trash / delete confirmation (inline, not native confirm())
// ---------------------------------------------------------------------------
test('clicking trash shows inline confirmation, not browser dialog', async ({ page }) => {
    await wpLogin(page);
    await page.goto(DASHBOARD_URL);

    const trashBtn = page.locator('[data-action="trash"], .linkblog-trash').first();

    // Only run if there is at least one link to trash.
    if (await trashBtn.count() === 0) {
        test.skip();
    }

    // No browser dialog should appear — the key decision from CLAUDE.local.md.
    page.on('dialog', (dialog) => {
        throw new Error(`Unexpected native dialog: ${dialog.message()}`);
    });

    await trashBtn.click();

    // Inline confirm UI should appear instead.
    await expect(page.locator('[data-role="delete-confirm"], .linkblog-confirm')).toBeVisible();
});
