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
 * Text Domain: linkblog
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

require_once __DIR__ . '/src/php/post-type.php';
require_once __DIR__ . '/src/php/meta-boxes.php';
require_once __DIR__ . '/src/php/publishing.php';
require_once __DIR__ . '/src/php/batch.php';
require_once __DIR__ . '/src/php/queries.php';
require_once __DIR__ . '/src/php/rest-api.php';
require_once __DIR__ . '/src/php/admin/menu.php';
require_once __DIR__ . '/src/php/admin/dashboard.php';
require_once __DIR__ . '/src/php/admin/links-page.php';
require_once __DIR__ . '/src/php/admin/add-link.php';
