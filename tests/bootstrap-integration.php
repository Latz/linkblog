<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__, 2) . '/');
}

/**
 * Bootstrap for Integration tests.
 *
 * Requires the WordPress test suite to be installed first:
 *
 *   bin/install-wp-tests.sh <db> <user> <pass> [host] [wp-version]
 *
 * The WP_TESTS_DIR environment variable must point to that suite, OR you can
 * set it in .env.testing and export it before running Pest.
 *
 * Quick start (adjust values):
 *   export WP_TESTS_DIR=/tmp/wordpress-tests-lib
 *   bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
 *   vendor/bin/pest --testsuite=Integration
 */

$linkblog_wp_tests_dir = getenv('WP_TESTS_DIR') ?: '/tmp/wordpress-tests-lib';

if (! is_dir($linkblog_wp_tests_dir)) {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo "\nERROR: WP test suite not found at {$linkblog_wp_tests_dir}.\n";
    echo "Run bin/install-wp-tests.sh or set WP_TESTS_DIR.\n\n";
    exit(1);
}

// Load the plugin under test before WP boots.
define('LINKBLOG_TESTS_DIR', dirname(__DIR__));
require_once $linkblog_wp_tests_dir . '/includes/functions.php';

// WP test suite bootstrap — this loads WordPress and creates the test DB.
require_once $wpTestsDir . '/includes/functions.php';

tests_add_filter('muplugins_loaded', static function (): void {
    require_once LINKBLOG_TESTS_DIR . '/linkblog.php';
});

require_once $wpTestsDir . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';
