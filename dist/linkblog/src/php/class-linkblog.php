<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LinkDigest {

    private const META_COMPARE_NOT_EXISTS = 'NOT EXISTS';          // NOSONAR — used in traits via self::
    private const META_COMPARE_NOT_IN     = 'NOT IN';              // NOSONAR — used in traits via self::
    private const ADMIN_LINKS_PAGE        = 'admin.php?page=linkblog-admin'; // NOSONAR — used in traits via self::

    use LinkDigest_PostType;
    use LinkDigest_MetaBoxes;
    use LinkDigest_Publishing;
    use LinkDigest_Batch;
    use LinkDigest_Queries;
    use LinkDigest_RestApi;
    use LinkDigest_Scheduler;
    use LinkDigest_Admin_Menu;
    use LinkDigest_Admin_Dashboard;
    use LinkDigest_Admin_LinksPage;
    use LinkDigest_Admin_AddLink;

    public static function register(): void {
        $instance = new self();

        // Post type & taxonomies
        add_action('init', [$instance, 'registerPostType'], 0);
        add_action('init', [$instance, 'registerTaxonomies'], 0);

        // Scheduler
        add_action('init', [$instance, 'registerSchedulerHooks'], 0);

        // Preflight must fire early on init
        add_action('init', [$instance, 'handlePreflight'], 1);

        // Meta boxes
        add_action('add_meta_boxes', [$instance, 'addMetaBoxes']);
        add_action('save_post_linkblog', [$instance, 'saveUrl']);

        // REST API
        add_action('rest_api_init', [$instance, 'registerRestRoutes']);
        add_filter('rest_pre_serve_request', [$instance, 'addCorsHeaders'], 15);
        add_action('created_linkdigest_category', [$instance, 'invalidateCategoriesCache']);
        add_action('edited_linkdigest_category',  [$instance, 'invalidateCategoriesCache']);
        add_action('delete_linkdigest_category',  [$instance, 'invalidateCategoriesCache']);

        // Admin menu & assets
        add_action('admin_init', [$instance, 'registerSettingX']);
        add_action('admin_menu', [$instance, 'adminMenu']);
        add_action('admin_enqueue_scripts', [$instance, 'enqueueAdminAssets']);
        add_action('wp_dashboard_setup', [$instance, 'addDashboardWidget']);

        add_action('admin_head', [$instance, 'hideCategoryFields']);

        // Menu highlighting for taxonomy pages
        add_filter('parent_file', [$instance, 'parentFileFilter']);
        add_filter('submenu_file', [$instance, 'submenuFileFilter']);
    }
}
