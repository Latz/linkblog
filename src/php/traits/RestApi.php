<?php

declare(strict_types=1);

trait LinkDigest_RestApi {

    public function registerRestRoutes(): void {
        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/add-link', array(
            'methods' => 'POST',
            'callback' => [$this, 'restAddLink'],
            'permission_callback' => [$this, 'restPermissionCheck'],
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

        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/categories', array(
            'methods' => 'GET',
            'callback' => [$this, 'restGetCategories'],
            'permission_callback' => [$this, 'restPermissionCheck'],
        ));

        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/links/(?P<id>\d+)', array(
            'methods'             => 'DELETE',
            'callback'            => [$this, 'restDeleteLink'],
            'permission_callback' => function() { return current_user_can('delete_posts'); },
        ));

        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/schedule', array(
            array(
                'methods'             => 'GET',
                'callback'            => [$this, 'getSchedule'],
                'permission_callback' => function() { return current_user_can('manage_options'); },
            ),
            array(
                'methods'             => 'POST',
                'callback'            => [$this, 'saveSchedule'],
                'permission_callback' => function() { return current_user_can('manage_options'); },
            ),
        ));

        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/schedule/run', array(
            'methods'             => 'POST',
            'callback'            => [$this, 'runScheduleNow'],
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ));

        register_rest_route(LINKDIGEST_REST_NAMESPACE, '/api-key', array(
            'methods'             => 'GET',
            'callback'            => [$this, 'restGetApiKey'],
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ));
    }

    public function restGetApiKey(): mixed {
        $key = get_option('linkdigest_api_key', '');
        if (empty($key)) {
            return new \WP_Error('no_key', __('No API key configured.', 'linkdigest'), ['status' => 404]);
        }
        return rest_ensure_response(['key' => $key]);
    }

    public function handleGetRestNonce(): void {
        $origin = get_http_origin();
        if (is_string($origin) && $this->isFromChromeExtension($origin)) {
            $this->setCorsOriginHeaders($origin);
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Forbidden', 403);
        }
        wp_send_json_success(['nonce' => wp_create_nonce('wp_rest')]);
    }

    public function restDeleteLink(\WP_REST_Request $request): \WP_REST_Response|\WP_Error {
        $link_id = (int) $request['id'];
        if (get_post_type($link_id) !== 'linkdigest') {
            return new \WP_Error('invalid_link', 'Link not found', array('status' => 404));
        }
        $result = wp_delete_post($link_id, true);
        if (!$result) {
            return new \WP_Error('delete_failed', 'Could not delete link', array('status' => 500));
        }
        return new \WP_REST_Response(null, 204);
    }

    public function getSchedule(): mixed {
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
        $config = get_option('linkdigest_schedule', $default);
        return rest_ensure_response($config);
    }

    public function saveSchedule(\WP_REST_Request $request): mixed {
        $data = $request->get_json_params();
        if (empty($data) || !isset($data['mode'])) {
            return new \WP_Error('invalid_data', __('Invalid schedule data', 'linkdigest'), array('status' => 400));
        }
        $valid_modes = ['daily', 'weekly', 'monthly', 'count', 'age', 'manual'];
        if (!in_array($data['mode'], $valid_modes, true)) {
            return new \WP_Error('invalid_mode', __('Invalid schedule mode', 'linkdigest'), array('status' => 400));
        }
        if (isset($data['times'])) {
            if (!is_array($data['times'])) {
                return new \WP_Error('invalid_times', __('times must be an array', 'linkdigest'), array('status' => 400));
            }
            foreach ($data['times'] as $t) {
                if (!is_string($t) || !preg_match('/^\d{2}:\d{2}$/', $t)) {
                    return new \WP_Error('invalid_times', __('times entries must be HH:MM strings', 'linkdigest'), array('status' => 400));
                }
            }
        }
        if (isset($data['trigger']['count']) && (int) $data['trigger']['count'] <= 0) {
            return new \WP_Error('invalid_trigger', __('trigger.count must be a positive integer', 'linkdigest'), array('status' => 400));
        }
        if (isset($data['trigger']['days']) && (int) $data['trigger']['days'] <= 0) {
            return new \WP_Error('invalid_trigger', __('trigger.days must be a positive integer', 'linkdigest'), array('status' => 400));
        }
        update_option('linkdigest_schedule', $data);
        $this->scheduleNextEvent();
        return rest_ensure_response(array('success' => true));
    }

    public function runScheduleNow(): \WP_REST_Response {
        $result = $this->executeSchedule(false);
        return rest_ensure_response($result);
    }

    public function restPermissionCheck(\WP_REST_Request $request): bool {
        // API key first: used by the Chrome extension and external callers that can't
        // hold a WP session cookie. Falls back to standard WP capability check.
        $api_key = $request->get_header('X-LinkDigest-API-Key');
        $stored_key = get_option('linkdigest_api_key');

        if (!empty($api_key) && !empty($stored_key) && hash_equals($stored_key, $api_key)) {
            return true;
        }

        return current_user_can('edit_posts');
    }

    public function restAddLink(\WP_REST_Request $request): mixed {
        $title = $request->get_param('title');
        $url = $request->get_param('url');
        $content = $request->get_param('content') ?? '';
        $categories = $request->get_param('categories');
        $tags = $request->get_param('tags');

        $validation = $this->validateRestLink($title, $url);
        if (is_wp_error($validation)) {
            return $validation;
        }

        $post_data = array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_type'    => 'linkdigest',
            'post_status'  => 'publish',
        );

        $post_id = wp_insert_post($post_data);
        if (is_wp_error($post_id)) {
            return new \WP_Error('insert_failed', __('Failed to create link.', 'linkdigest'), array('status' => 500));
        }

        if (!empty($url)) {
            update_post_meta($post_id, '_linkdigest_url', $url);
        }

        $this->applyLinkTaxonomies($post_id, $categories, $tags);

        return rest_ensure_response(array(
            'success' => true,
            'post_id' => $post_id,
            'message' => __('Link added successfully!', 'linkdigest'),
        ));
    }

    private function validateRestLink(string $title, ?string $url): bool|\WP_Error {
        if (empty($title)) {
            return new \WP_Error('missing_title', __('Title is required.', 'linkdigest'), array('status' => 400));
        }

        if (!empty($url)) {
            $existing = get_posts(array(
                'post_type'   => 'linkdigest',
                'post_status' => 'any',
                'meta_key'    => '_linkdigest_url', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
                'meta_value'  => $url, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
                'numberposts' => 1,
                'fields'      => 'ids',
            ));
            if (!empty($existing)) {
                return new \WP_Error('duplicate_url', __('This URL has already been saved.', 'linkdigest'), array('status' => 409));
            }
        }

        return true;
    }

    private function resolveOrCreateCategories(array $categories): array {
        $ids = array();
        foreach ($categories as $cat_name) {
            $term = get_term_by('name', $cat_name, 'linkdigest_category');
            if (!$term) {
                $result = wp_insert_term($cat_name, 'linkdigest_category');
                if (!is_wp_error($result)) {
                    $ids[] = $result['term_id'];
                }
            } else {
                $ids[] = $term->term_id;
            }
        }
        return $ids;
    }

    private function applyLinkTaxonomies(int $post_id, mixed $categories, mixed $tags): void {
        if (!empty($categories) && is_array($categories)) {
            $ids = $this->resolveOrCreateCategories($categories);
            if (!empty($ids)) {
                wp_set_object_terms($post_id, $ids, 'linkdigest_category');
            }
        }
        if (!empty($tags)) {
            $tag_names = array_map('trim', explode(',', $tags));
            wp_set_object_terms($post_id, $tag_names, 'linkdigest_tag');
        }
    }

    public function restGetCategories(): mixed {
        $cache_key = 'linkdigest_api_categories_list';
        $category_list = get_transient($cache_key);

        if (false === $category_list) {
            $categories = get_terms(array(
                'taxonomy'   => 'linkdigest_category',
                'hide_empty' => false,
            ));

            if (is_wp_error($categories)) {
                return new \WP_Error('fetch_failed', __('Failed to fetch categories.', 'linkdigest'), array('status' => 500));
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

    public function invalidateCategoriesCache(): void {
        delete_transient('linkdigest_api_categories_list');
    }

    public function addCorsHeaders(bool $served): bool {
        $origin = get_http_origin();
        if (is_string($origin) && $this->isFromChromeExtension($origin)) {
            $this->setCorsOriginHeaders($origin);
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, X-LinkDigest-API-Key, Authorization');
        }
        return $served;
    }

    public function handlePreflight(): void {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        if (!$request_uri || strpos($request_uri, '/wp-json/') === false) {
            return;
        }
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $origin = get_http_origin();
            if ($this->isFromChromeExtension($origin)) {
                $this->setCorsOriginHeaders($origin);
                header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, X-LinkDigest-API-Key, Authorization');
                header('Access-Control-Max-Age: 86400');
                exit;
            }
        }
    }

    private function isFromChromeExtension( string $origin ): bool {
        return strpos( $origin, 'chrome-extension://' ) === 0;
    }

    private function setCorsOriginHeaders( string $origin ): void {
        header( 'Access-Control-Allow-Origin: ' . $origin );
        header( 'Access-Control-Allow-Credentials: true' );
    }
}
