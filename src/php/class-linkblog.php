<?php

declare(strict_types=1);

class LinkBlog {

    private const META_COMPARE_NOT_EXISTS = 'NOT EXISTS'; // NOSONAR — used in traits via self::
    private const META_COMPARE_NOT_IN     = 'NOT IN';     // NOSONAR — used in traits via self::

    use LinkBlog_PostType;
    use LinkBlog_MetaBoxes;
    use LinkBlog_Publishing;
    use LinkBlog_Batch;
    use LinkBlog_Queries;
    use LinkBlog_RestApi;
    use LinkBlog_Scheduler;
    use LinkBlog_Admin_Menu;
    use LinkBlog_Admin_Dashboard;
    use LinkBlog_Admin_LinksPage;
    use LinkBlog_Admin_AddLink;

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
        add_action('rest_api_init', [$instance, 'addCorsHeaders']);
        add_action('created_linkblog_category', [$instance, 'invalidateCategoriesCache']);
        add_action('edited_linkblog_category',  [$instance, 'invalidateCategoriesCache']);
        add_action('delete_linkblog_category',  [$instance, 'invalidateCategoriesCache']);

        // Admin menu & assets
        add_action('admin_menu', [$instance, 'adminMenu']);
        add_action('admin_enqueue_scripts', [$instance, 'enqueueAdminAssets']);
        add_action('wp_dashboard_setup', [$instance, 'addDashboardWidget']);

        // Menu highlighting for taxonomy pages
        add_filter('parent_file', [$instance, 'parentFileFilter']);
        add_filter('submenu_file', [$instance, 'submenuFileFilter']);
    }
}
