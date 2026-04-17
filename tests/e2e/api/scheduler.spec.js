/**
 * Playwright — E2E tests for the LinkBlog scheduler.
 *
 * Verifies that POST /schedule/run publishes unpublished links into a roundup
 * post and marks each link's _linkblog_publish_status as 'published'.
 *
 * Run with: npm run test:e2e:api
 */

import { test, expect } from '@playwright/test';
import constants from '../../../constants.json' assert { type: 'json' };

const { REST_NAMESPACE, ROUTES } = constants;

const api   = (route) => `/wp-json/${REST_NAMESPACE}${route}`;
const wpApi = (route) => `/wp-json/wp/v2${route}`;

test.describe('Scheduler publishing', () => {
    let linkId;

    test('POST /schedule/run publishes links and creates a roundup post', async ({ request }) => {
        // 1. Create a test link.
        const addRes = await request.post(api(ROUTES.ADD_LINK), {
            data: { title: 'Scheduler E2E Test Link', url: 'https://example.com/scheduler-e2e' },
        });
        expect(addRes.status()).toBe(200);
        const { post_id } = await addRes.json();
        linkId = post_id;
        expect(typeof linkId).toBe('number');

        // 2. Save a daily schedule so any unpublished link triggers publishing.
        const saveRes = await request.post(api(ROUTES.SCHEDULE), {
            data: { mode: 'daily', times: ['09:00'], recurrence: {}, trigger: {} },
        });
        expect(saveRes.status()).toBe(200);

        // 3. Run the scheduler.
        const runRes = await request.post(api('/schedule/run'));
        expect(runRes.status()).toBe(200);
        expect((await runRes.json()).success).toBe(true);

        // 4. Verify a roundup post was created (title starts with "Links:").
        const postsRes = await request.get(wpApi('/posts?search=Links%3A'));
        expect(postsRes.status()).toBe(200);
        const posts = await postsRes.json();
        expect(posts.length).toBeGreaterThan(0);

        // 5. Verify the link's publish status meta was updated.
        const linkRes = await request.get(wpApi(`/linkblog/${linkId}?context=edit`));
        expect(linkRes.status()).toBe(200);
        const link = await linkRes.json();
        expect(link.meta._linkblog_publish_status).toBe('published');
    });
});
