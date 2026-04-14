<?php

declare(strict_types=1);

trait LinkBlog_RestApi {

    public static function registerRestRoutes(): void {
        register_rest_route(LINKBLOG_REST_NAMESPACE, '/add-link', array(
            'methods' => 'POST',
            'callback' => [self::class, 'restAddLink'],
            'permission_callback' => [self::class, 'restPermissionCheck'],
            'args' => array(
                'title' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'url' => array(
                    'required' => false,
                    'type' => 'string',
                    'sanitize_callback' => 'esc_url_raw',
                ),
                'content' => array(
                    'required' => false,
                    'type' => 'string',
                    'sanitize_callback' => 'wp_kses_post',
                ),
                'categories' => array(
                    'required' => false,
                    'type' => 'array',
                ),
                'tags' => array(
                    'required' => false,
                    'type' => 'string',
                ),
            ),
        ));

        register_rest_route(LINKBLOG_REST_NAMESPACE, '/categories', array(
            'methods' => 'GET',
            'callback' => [self::class, 'restGetCategories'],
            'permission_callback' => [self::class, 'restPermissionCheck'],
        ));

        register_rest_route(LINKBLOG_REST_NAMESPACE, '/links/(?P<id>\d+)', array(
            'methods'             => 'DELETE',
            'callback'            => [self::class, 'restDeleteLink'],
            'permission_callback' => function() { return current_user_can('delete_posts'); },
        ));

        register_rest_route(LINKBLOG_REST_NAMESPACE, '/schedule', array(
            array(
                'methods'             => 'GET',
                'callback'            => [self::class, 'getSchedule'],
                'permission_callback' => function() { return current_user_can('manage_options'); },
            ),
            array(
                'methods'             => 'POST',
                'callback'            => [self::class, 'saveSchedule'],
                'permission_callback' => function() { return current_user_can('manage_options'); },
            ),
        ));
    }

    public static function restDeleteLink(\WP_REST_Request $request): \WP_REST_Response|\WP_Error {
        $link_id = (int) $request['id'];
        if (get_post_type($link_id) !== 'linkblog') {
            return new \WP_Error('invalid_link', 'Link not found', array('status' => 404));
        }
        $result = wp_delete_post($link_id, true);
        if (!$result) {
            return new \WP_Error('delete_failed', 'Could not delete link', array('status' => 500));
        }
        return new \WP_REST_Response(null, 204);
    }

    public static function getSchedule(): mixed {
        $default = array(
            'mode'       => 'daily',
            'recurrence' => array(
                'interval'  => 1,
                'weekdays'  => array(),
                'monthDays' => array(array('type' => 'day', 'value' => 1, 'nth' => 1, 'weekday' => 'MO')),
                'nthWeek'   => null,
            ),
            'trigger' => array('count' => 10, 'tag_id' => null, 'days' => 7),
            'times'   => array('09:00'),
        );
        $config = get_option('linkblog_schedule', $default);
        return rest_ensure_response($config);
    }

    public static function saveSchedule(\WP_REST_Request $request): mixed {
        $data = $request->get_json_params();
        if (empty($data) || !isset($data['mode'])) {
            return new \WP_Error('invalid_data', __('Invalid schedule data', 'linkblog'), array('status' => 400));
        }
        update_option('linkblog_schedule', $data);
        return rest_ensure_response(array('success' => true));
    }

    public static function restPermissionCheck(\WP_REST_Request $request): bool {
        // Check for API key in header
        $api_key = $request->get_header('X-LinkBlog-API-Key');
        $stored_key = get_option('linkblog_api_key');

        if (!empty($api_key) && !empty($stored_key) && hash_equals($stored_key, $api_key)) {
            return true;
        }

        // Fallback to WordPress authentication
        return current_user_can('edit_posts');
    }

    public static function restAddLink(\WP_REST_Request $request): mixed {
        $title = $request->get_param('title');
        $url = $request->get_param('url');
        $content = $request->get_param('content');
        $categories = $request->get_param('categories');
        $tags = $request->get_param('tags');

        if (empty($title)) {
            return new \WP_Error('missing_title', __('Title is required.', 'linkblog'), array('status' => 400));
        }

        // Create the post
        $post_data = array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_type'    => 'linkblog',
            'post_status'  => 'publish',
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            return new \WP_Error('insert_failed', __('Failed to create link.', 'linkblog'), array('status' => 500));
        }

        // Save URL
        if (!empty($url)) {
            update_post_meta($post_id, '_linkblog_url', $url);
        }

        // Set categories
        if (!empty($categories) && is_array($categories)) {
            $category_ids = array();
            foreach ($categories as $cat_name) {
                $term = get_term_by('name', $cat_name, 'linkblog_category');
                if (!$term) {
                    $term = wp_insert_term($cat_name, 'linkblog_category');
                    if (!is_wp_error($term)) {
                        $category_ids[] = $term['term_id'];
                    }
                } else {
                    $category_ids[] = $term->term_id;
                }
            }
            if (!empty($category_ids)) {
                wp_set_object_terms($post_id, $category_ids, 'linkblog_category');
            }
        }

        // Set tags
        if (!empty($tags)) {
            $tag_names = array_map('trim', explode(',', $tags));
            wp_set_object_terms($post_id, $tag_names, 'linkblog_tag');
        }

        return rest_ensure_response(array(
            'success' => true,
            'post_id' => $post_id,
            'message' => __('Link added successfully!', 'linkblog'),
        ));
    }

    public static function restGetCategories(\WP_REST_Request $request): mixed {
        $cache_key = 'linkblog_api_categories_list';
        $category_list = get_transient($cache_key);

        if (false === $category_list) {
            $categories = get_terms(array(
                'taxonomy'   => 'linkblog_category',
                'hide_empty' => false,
            ));

            if (is_wp_error($categories)) {
                return new \WP_Error('fetch_failed', __('Failed to fetch categories.', 'linkblog'), array('status' => 500));
            }

            $category_list = array();
            foreach ($categories as $category) {
                $category_list[] = array(
                    'id'   => $category->term_id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                );
            }
            set_transient($cache_key, $category_list, HOUR_IN_SECONDS);
        }
        return rest_ensure_response($category_list);
    }

    public static function invalidateCategoriesCache(): void {
        delete_transient('linkblog_api_categories_list');
    }

    public static function addCorsHeaders(): void {
        // Get the origin from the request
        $origin = get_http_origin();

        // Allow requests from Chrome extensions
        if (strpos($origin, 'chrome-extension://') === 0) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Content-Type, X-LinkBlog-API-Key, Authorization');
        }
    }

    public static function handlePreflight(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $origin = get_http_origin();
            if (strpos($origin, 'chrome-extension://') === 0) {
                header('Access-Control-Allow-Origin: ' . $origin);
                header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Headers: Content-Type, X-LinkBlog-API-Key, Authorization');
                header('Access-Control-Max-Age: 86400');
                exit;
            }
        }
    }
}
