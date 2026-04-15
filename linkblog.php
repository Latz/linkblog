<?php
/**
 * Plugin Name: LinkBlog
 * Plugin URI: https://example.com/linkblog
 * Description: Save and publish links to your blog
 * Version: 1.0.0
 * Author: Latz
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: LinkBlog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// ---------------------------------------------------------------------------
// Shared constants — single source of truth for REST namespace/routes.
// The same constants.json is imported by Vitest and Playwright tests.
// ---------------------------------------------------------------------------
$_linkblog_constants = json_decode(
    file_get_contents(__DIR__ . '/constants.json'),
    true
);
define('LINKBLOG_REST_NAMESPACE', $_linkblog_constants['REST_NAMESPACE']);
define('LINKBLOG_POST_TYPE',      $_linkblog_constants['POST_TYPE']);
unset($_linkblog_constants);

define('LINKBLOG_PLUGIN_FILE', __FILE__);

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
require_once __DIR__ . '/src/php/class-linkblog.php';

register_deactivation_hook(LINKBLOG_PLUGIN_FILE, function() {
    wp_clear_scheduled_hook('linkblog_execute_schedule');
});

LinkBlog::register();
