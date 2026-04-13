<?php

/**
 * WordPress stub definitions for unit tests.
 *
 * Every function here is a sensible no-op default. Brain Monkey patches over
 * any of them per-test when you call Functions\expect() or Functions\when().
 */

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------------
defined('ABSPATH')          || define('ABSPATH',          '/tmp/fake-wp/');
defined('HOUR_IN_SECONDS')  || define('HOUR_IN_SECONDS',  3600);
defined('DAY_IN_SECONDS')   || define('DAY_IN_SECONDS',   86400);
defined('WEEK_IN_SECONDS')  || define('WEEK_IN_SECONDS',  604800);
defined('MONTH_IN_SECONDS') || define('MONTH_IN_SECONDS', 2592000);

// ---------------------------------------------------------------------------
// Classes
// ---------------------------------------------------------------------------
if (!class_exists('WP_Error')) {
    class WP_Error
    {
        public array $errors     = [];
        public array $error_data = [];

        public function __construct(string $code = '', string $message = '', mixed $data = '')
        {
            if ($code !== '') {
                $this->errors[$code][]    = $message;
                $this->error_data[$code]  = $data;
            }
        }

        public function get_error_code(): string
        {
            return (string) key($this->errors);
        }

        public function get_error_message(string $code = ''): string
        {
            $code = $code ?: $this->get_error_code();
            return $this->errors[$code][0] ?? '';
        }

        public function get_error_data(string $code = ''): mixed
        {
            $code = $code ?: $this->get_error_code();
            return $this->error_data[$code] ?? null;
        }
    }
}

if (!class_exists('WP_Post')) {
    class WP_Post
    {
        public int    $ID           = 0;
        public string $post_title   = '';
        public string $post_content = '';
        public string $post_type    = 'post';
        public string $post_status  = 'publish';
    }
}

if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request implements ArrayAccess
    {
        protected array $params  = [];
        protected array $headers = [];

        public function get_param(string $key): mixed
        {
            return $this->params[$key] ?? null;
        }

        public function get_json_params(): array
        {
            return $this->params;
        }

        public function get_header(string $key): ?string
        {
            return $this->headers[strtolower($key)] ?? null;
        }

        public function set_header(string $key, string $value): void
        {
            $this->headers[strtolower($key)] = $value;
        }

        public function offsetGet(mixed $key): mixed           { return $this->params[$key] ?? null; }
        public function offsetExists(mixed $key): bool         { return isset($this->params[$key]); }
        public function offsetSet(mixed $key, mixed $value): void { $this->params[$key] = $value; }
        public function offsetUnset(mixed $key): void          { unset($this->params[$key]); }
    }
}

if (!class_exists('WP_REST_Response')) {
    class WP_REST_Response
    {
        public function __construct(
            public mixed $data   = null,
            public int   $status = 200
        ) {}

        public function get_data(): mixed  { return $this->data; }
        public function get_status(): int  { return $this->status; }
    }
}

// ---------------------------------------------------------------------------
// Hook functions — pure no-ops; tests use Brain\Monkey\Actions/Filters to assert
// ---------------------------------------------------------------------------
if (!function_exists('add_action'))  { function add_action(string $hook, callable $cb, int $priority = 10, int $accepted_args = 1): void {} }
if (!function_exists('add_filter'))  { function add_filter(string $hook, callable $cb, int $priority = 10, int $accepted_args = 1): void {} }
if (!function_exists('do_action'))   { function do_action(string $hook_name, mixed ...$args): void {} }
if (!function_exists('remove_action')) { function remove_action(string $hook, callable $cb, int $priority = 10): bool { return true; } }

// ---------------------------------------------------------------------------
// i18n — return text unchanged so assertions read naturally
// ---------------------------------------------------------------------------
if (!function_exists('__'))         { function __(string $t, string $d = 'default'): string { return $t; } }
if (!function_exists('_e'))         { function _e(string $t, string $d = 'default'): void   { echo $t; } }
if (!function_exists('_x'))         { function _x(string $t, string $c, string $d = 'default'): string { return $t; } }
if (!function_exists('esc_html__')) { function esc_html__(string $t, string $d = 'default'): string { return htmlspecialchars($t); } }
if (!function_exists('sprintf'))    { /* built-in */ }

// ---------------------------------------------------------------------------
// Output escaping — identity by default; override in tests that check escaping
// ---------------------------------------------------------------------------
if (!function_exists('esc_html'))     { function esc_html(string $t): string     { return htmlspecialchars($t, ENT_QUOTES); } }
if (!function_exists('esc_attr'))     { function esc_attr(string $t): string     { return htmlspecialchars($t, ENT_QUOTES); } }
if (!function_exists('esc_url'))      { function esc_url(string $u): string      { return $u; } }
if (!function_exists('esc_url_raw'))  { function esc_url_raw(string $u): string  { return $u; } }
if (!function_exists('esc_js'))       { function esc_js(string $t): string       { return addslashes($t); } }
if (!function_exists('wp_kses_post')) { function wp_kses_post(string $d): string { return $d; } }
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field(string $s): string { return trim(strip_tags($s)); }
}

// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------
if (!function_exists('apply_filters')) {
    function apply_filters(string $hook_name, mixed $value, mixed ...$args): mixed { return $value; }
}

// ---------------------------------------------------------------------------
// Post functions
// ---------------------------------------------------------------------------
if (!function_exists('get_post')) {
    function get_post(mixed $post = null, string $output = 'OBJECT', string $filter = 'raw'): ?WP_Post { return null; }
}
if (!function_exists('get_post_type')) {
    function get_post_type(mixed $post = false): string|false { return false; }
}
if (!function_exists('get_post_meta')) {
    function get_post_meta(int $id, string $key = '', bool $single = false): mixed { return ''; }
}
if (!function_exists('update_post_meta')) {
    function update_post_meta(int $id, string $key, mixed $value, mixed $prev = ''): int|bool { return true; }
}
if (!function_exists('delete_post_meta')) {
    function delete_post_meta(int $id, string $key, mixed $value = ''): bool { return true; }
}
if (!function_exists('wp_insert_post')) {
    function wp_insert_post(array $postarr, bool $wp_error = false, bool $fire = true): int|WP_Error { return 0; }
}
if (!function_exists('wp_delete_post')) {
    function wp_delete_post(int $postid = 0, bool $force = false): WP_Post|false|null { return false; }
}
if (!function_exists('wp_trash_post')) {
    function wp_trash_post(int $post_id = 0): WP_Post|false|null { return false; }
}
if (!function_exists('get_posts')) {
    function get_posts(array $args = []): array { return []; }
}
if (!function_exists('wp_count_posts')) {
    function wp_count_posts(string $type = 'post', string $perm = ''): stdClass {
        $r = new stdClass();
        $r->publish = 0;
        $r->draft   = 0;
        $r->trash   = 0;
        return $r;
    }
}

// ---------------------------------------------------------------------------
// Taxonomy functions
// ---------------------------------------------------------------------------
if (!function_exists('get_terms')) {
    function get_terms(array|string $args = [], array|string $deprecated = ''): array|WP_Error { return []; }
}
if (!function_exists('get_term_by')) {
    function get_term_by(string $field, string $value, string $taxonomy = ''): object|array|false { return false; }
}
if (!function_exists('wp_insert_term')) {
    function wp_insert_term(string $term, string $taxonomy, array $args = []): array|WP_Error {
        return ['term_id' => 0, 'term_taxonomy_id' => 0];
    }
}
if (!function_exists('wp_set_object_terms')) {
    function wp_set_object_terms(int $object_id, mixed $terms, string $taxonomy, bool $append = false): array|WP_Error { return []; }
}
if (!function_exists('wp_set_post_categories')) {
    function wp_set_post_categories(int $post_id = 0, array $cats = [], bool $append = false): array|false { return []; }
}
if (!function_exists('wp_set_post_tags')) {
    function wp_set_post_tags(int $post_id = 0, mixed $tags = '', bool $append = false): mixed { return []; }
}
if (!function_exists('wp_list_pluck')) {
    function wp_list_pluck(array $list, string $field, ?string $index_key = null): array {
        return array_column($list, $field, $index_key);
    }
}
if (!function_exists('get_the_terms')) {
    function get_the_terms(mixed $post, string $taxonomy): array|false|WP_Error { return false; }
}
if (!function_exists('get_category_by_slug')) {
    function get_category_by_slug(string $slug): object|false { return false; }
}

// ---------------------------------------------------------------------------
// Options & transients
// ---------------------------------------------------------------------------
if (!function_exists('get_option')) {
    function get_option(string $option, mixed $default = false): mixed { return $default; }
}
if (!function_exists('update_option')) {
    function update_option(string $option, mixed $value, bool|string|null $autoload = null): bool { return true; }
}
if (!function_exists('get_transient')) {
    function get_transient(string $transient): mixed { return false; }
}
if (!function_exists('set_transient')) {
    function set_transient(string $transient, mixed $value, int $expiration = 0): bool { return true; }
}
if (!function_exists('delete_transient')) {
    function delete_transient(string $transient): bool { return true; }
}

// ---------------------------------------------------------------------------
// Capabilities & REST
// ---------------------------------------------------------------------------
if (!function_exists('current_user_can')) {
    function current_user_can(string $capability, mixed ...$args): bool { return false; }
}
if (!function_exists('is_wp_error')) {
    function is_wp_error(mixed $thing): bool { return $thing instanceof WP_Error; }
}
if (!function_exists('rest_ensure_response')) {
    function rest_ensure_response(mixed $data): mixed { return $data; }
}
if (!function_exists('get_http_origin')) {
    function get_http_origin(): string { return ''; }
}
if (!function_exists('plugins_url')) {
    function plugins_url(string $path = '', string $plugin = ''): string {
        return 'http://example.com/wp-content/plugins/' . ltrim($path, '/');
    }
}
if (!function_exists('rest_url')) {
    function rest_url(string $path = ''): string {
        return 'http://example.com/wp-json/' . ltrim($path, '/');
    }
}
if (!function_exists('current_time')) {
    function current_time(string $type, bool $gmt = false): int|string {
        return $type === 'timestamp' ? time() : date('Y-m-d H:i:s');
    }
}
