<?php

declare(strict_types=1);

/**
 * Example Integration test.
 *
 * Run with a real WP test DB:
 *   vendor/bin/pest --testsuite=Integration --bootstrap tests/bootstrap-integration.php
 *
 * Requires the WP test suite installed via bin/install-wp-tests.sh.
 * The WP_TESTS_DIR env var must point to that suite.
 */

it('registers the linkblog custom post type', function (): void {
    // WP is fully loaded here – post types are registered.
    expect(post_type_exists('linkblog'))->toBeTrue();
});

it('creates a link post and retrieves it', function (): void {
    $postId = wp_insert_post([
        'post_type'   => 'linkblog',
        'post_title'  => 'Test Link',
        'post_status' => 'publish',
        'meta_input'  => ['_linkblog_url' => 'https://example.com'],
    ]);

    expect($postId)->toBeInt()->toBeGreaterThan(0);

    $url = get_post_meta($postId, '_linkblog_url', true);
    expect($url)->toBe('https://example.com');
});
