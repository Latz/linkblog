<?php

declare(strict_types=1);

class LinkBlog {
    use LinkBlog_PostType;
    use LinkBlog_MetaBoxes;
    use LinkBlog_Publishing;
    use LinkBlog_Batch;
    use LinkBlog_Queries;
    use LinkBlog_RestApi;
    use LinkBlog_Admin_Menu;
    use LinkBlog_Admin_Dashboard;
    use LinkBlog_Admin_LinksPage;
    use LinkBlog_Admin_AddLink;

    public static function register(): void {
        // Post type & taxonomies
        add_action('init', [self::class, 'registerPostType'], 0);
        add_action('init', [self::class, 'registerTaxonomies'], 0);

        // Preflight must fire early on init
        add_action('init', [self::class, 'handlePreflight'], 1);

        // Meta boxes
        add_action('add_meta_boxes', [self::class, 'addMetaBoxes']);
        add_action('save_post_linkblog', [self::class, 'saveUrl']);

        // REST API
        add_action('rest_api_init', [self::class, 'registerRestRoutes']);
        add_action('rest_api_init', [self::class, 'addCorsHeaders']);
        add_action('created_linkblog_category', [self::class, 'invalidateCategoriesCache']);
        add_action('edited_linkblog_category',  [self::class, 'invalidateCategoriesCache']);
        add_action('delete_linkblog_category',  [self::class, 'invalidateCategoriesCache']);

        // Admin menu & assets
        add_action('admin_menu', [self::class, 'adminMenu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
        add_action('wp_dashboard_setup', [self::class, 'addDashboardWidget']);

        // Menu highlighting for taxonomy pages
        add_filter('parent_file', [self::class, 'parentFileFilter']);
        add_filter('submenu_file', [self::class, 'submenuFileFilter']);
    }
}
