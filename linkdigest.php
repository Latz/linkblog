<?php
/**
 * Plugin Name: LinkDigest
 * Description: Save and publish links to your blog
 * Version: 1.0.0
 * Author: Latz
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: LinkDigest
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// ---------------------------------------------------------------------------
// Shared constants — single source of truth for REST namespace/routes.
// The same constants.json is imported by Vitest and Playwright tests.
// ---------------------------------------------------------------------------
$linkdigest_constants = json_decode(
    file_get_contents(__DIR__ . '/constants.json'),
    true
);
define('LINKDIGEST_REST_NAMESPACE', $linkdigest_constants['REST_NAMESPACE']);
define('LINKDIGEST_POST_TYPE',      $linkdigest_constants['POST_TYPE']);
unset($linkdigest_constants);

define('LINKDIGEST_PLUGIN_FILE', __FILE__);

// Traits (must be required before the class)
require_once __DIR__ . '/src/php/traits/PostType.php';
require_once __DIR__ . '/src/php/traits/MetaBoxes.php';
require_once __DIR__ . '/src/php/traits/Publishing.php';
require_once __DIR__ . '/src/php/traits/Batch.php';
require_once __DIR__ . '/src/php/traits/Queries.php';
require_once __DIR__ . '/src/php/traits/RestApi.php';
require_once __DIR__ . '/src/php/traits/Admin/Menu.php';
require_once __DIR__ . '/src/php/traits/Admin/Dashboard.php';
require_once __DIR__ . '/src/php/traits/Admin/LinksPage.php';
require_once __DIR__ . '/src/php/traits/Admin/AddLink.php';
require_once __DIR__ . '/src/php/traits/Scheduler.php';
require_once __DIR__ . '/src/php/class-linkdigest.php';

register_deactivation_hook(LINKDIGEST_PLUGIN_FILE, function() {
    wp_clear_scheduled_hook('linkdigest_execute_schedule');
});

LinkDigest::register();
