<?php

declare(strict_types=1);

trait LinkBlog_Admin_Menu {

    public function adminMenu(): void {
        add_menu_page(
            __('LinkBlog', 'linkblog'),
            __('LinkBlog', 'linkblog'),
            'read',
            'linkblog-dashboard',
            [$this, 'dashboardPage'],
            plugins_url('assets/icon-20x20.png', LINKBLOG_PLUGIN_FILE),
            6
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Dashboard', 'linkblog'),
            __('Dashboard', 'linkblog'),
            'read',
            'linkblog-dashboard',
            [$this, 'dashboardPage']
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Show Links', 'linkblog'),
            __('All Links', 'linkblog'),
            'read',
            'linkblog-admin',
            [$this, 'showLinksPage']
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Add Link', 'linkblog'),
            __('Add Link', 'linkblog'),
            'read',
            'linkblog-add',
            [$this, 'addLinkPage']
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Categories', 'linkblog'),
            __('Categories', 'linkblog'),
            'manage_categories',
            'edit-tags.php?taxonomy=linkblog_category&post_type=linkblog'
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Tags', 'linkblog'),
            __('Tags', 'linkblog'),
            'manage_categories',
            'edit-tags.php?taxonomy=linkblog_tag&post_type=linkblog'
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Settings', 'linkblog'),
            __('Settings', 'linkblog'),
            'manage_options',
            'linkblog-settings',
            [$this, 'settingsPage']
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Setting X', 'linkblog'),
            __('Setting X', 'linkblog'),
            'manage_options',
            'linkblog-setting-x',
            [$this, 'settingXPage']
        );

        add_submenu_page(
            'linkblog-dashboard',
            __('Schedule', 'linkblog'),
            __('Schedule', 'linkblog'),
            'manage_options',
            'linkblog-schedule',
            [$this, 'schedulePage']
        );
    }

    public function parentFileFilter(string $parent_file): string {
        global $pagenow;
        if ($pagenow === 'edit-tags.php') {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
            if ($taxonomy === 'linkblog_category' || $taxonomy === 'linkblog_tag') {
                return 'linkblog-dashboard';
            }
        }
        return $parent_file;
    }

    public function submenuFileFilter(?string $submenu_file): string {
        global $pagenow;
        if ($pagenow === 'edit-tags.php') {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
            if ($taxonomy === 'linkblog_category') {
                return 'edit-tags.php?taxonomy=linkblog_category&post_type=linkblog';
            }
            if ($taxonomy === 'linkblog_tag') {
                return 'edit-tags.php?taxonomy=linkblog_tag&post_type=linkblog';
            }
        }
        return $submenu_file ?? '';
    }

    public function settingsPage(): void {
        // Handle API key generation
        $nonce = isset($_POST['linkblog_settings_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkblog_settings_nonce'])) : '';
        if (isset($_POST['linkblog_generate_api_key']) && wp_verify_nonce($nonce, 'linkblog_settings')) {
            $api_key = wp_generate_password(32, false);
            update_option('linkblog_api_key', $api_key);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('New API key generated successfully!', 'linkblog') . '</p></div>';
        }

        $api_key = get_option('linkblog_api_key');
        $site_url = get_site_url();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('LinkBlog Settings', 'linkblog'); ?></h1>

            <div class="card" style="max-width: 800px;">
                <h2><?php esc_html_e('Chrome Extension Access Data', 'linkblog'); ?></h2>
                <p><?php esc_html_e('Use these credentials to connect the LinkBlog Chrome extension to your WordPress site.', 'linkblog'); ?></p>

                <div style="margin: 20px 0;">
                    <label for="linkblog-api-endpoint" style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <?php esc_html_e('API Endpoint:', 'linkblog'); ?>
                    </label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input
                            type="text"
                            id="linkblog-api-endpoint"
                            value="<?php echo esc_attr($site_url . '/wp-json/linkblog/v1'); ?>"
                            readonly
                            onclick="this.select();"
                            style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
                        >
                        <button type="button" class="button linkblog-copy-btn" data-clipboard-target="linkblog-api-endpoint">
                            <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                        </button>
                    </div>
                    <p class="description">
                        <?php esc_html_e('Use this URL in the Chrome extension settings.', 'linkblog'); ?>
                        <a href="<?php echo esc_url($site_url . '/wp-json/linkblog/v1'); ?>" target="_blank" style="margin-left: 8px;">
                            <?php esc_html_e('View REST API', 'linkblog'); ?> ↗
                        </a>
                    </p>
                </div>

                <?php if ($api_key) : ?>
                    <div style="margin: 20px 0;">
                        <label for="linkblog-api-key" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e('API Key:', 'linkblog'); ?>
                        </label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input
                                type="text"
                                id="linkblog-api-key"
                                value="<?php echo esc_attr($api_key); ?>"
                                readonly
                                onclick="this.select();"
                                style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
                            >
                            <button type="button" class="button linkblog-copy-btn" data-clipboard-target="linkblog-api-key">
                                <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                            </button>
                        </div>
                        <p class="description">
                            <?php esc_html_e('Click to select and copy this key. Keep it secure!', 'linkblog'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <?php wp_nonce_field('linkblog_settings', 'linkblog_settings_nonce'); ?>
                    <button type="submit" name="linkblog_generate_api_key" class="button button-primary">
                        <?php echo $api_key ? esc_html__('Generate New API Key', 'linkblog') : esc_html__('Generate API Key', 'linkblog'); ?>
                    </button>
                    <?php if ($api_key) : ?>
                        <p class="description">
                            <?php esc_html_e('Warning: Generating a new key will invalidate the old one.', 'linkblog'); ?>
                        </p>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2><?php esc_html_e('Chrome Extension Setup', 'linkblog'); ?></h2>
                <ol>
                    <li><?php esc_html_e('Download and install the LinkBlog Chrome extension', 'linkblog'); ?></li>
                    <li><?php esc_html_e('Click the extension icon and go to Settings', 'linkblog'); ?></li>
                    <li><?php esc_html_e('Paste your API Endpoint and API Key from above', 'linkblog'); ?></li>
                    <li><?php esc_html_e('Click Save', 'linkblog'); ?></li>
                    <li><?php esc_html_e('Now you can save links directly from any webpage!', 'linkblog'); ?></li>
                </ol>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.linkblog-copy-btn').on('click', function() {
                var targetId = $(this).data('clipboard-target');
                var input = document.getElementById(targetId);

                if (input) {
                    input.select();
                    input.setSelectionRange(0, 99999); // For mobile devices

                    try {
                        document.execCommand('copy');

                        // Visual feedback
                        var originalHtml = $(this).html();
                        $(this).html('<span class="dashicons dashicons-yes" style="margin-top: 3px; color: #00a32a;"></span>');

                        setTimeout(function() {
                            $('.linkblog-copy-btn').html(originalHtml);
                        }, 2000);
                    } catch (err) { /* copy failed — ignore */ }
                }
            });
        });
        </script>
        <?php
    }

    public function schedulePage(): void {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Schedule Configuration', 'linkblog'); ?></h1>
            <div id="linkblog-schedule-root"></div>
        </div>
        <?php
    }

    public function enqueueAdminAssets(string $hook): void {
        if (strpos($hook, 'linkblog') === false) {
            return;
        }

        wp_enqueue_style('dashicons');
        wp_enqueue_style(
            'linkblog-dashboard',
            plugin_dir_url(LINKBLOG_PLUGIN_FILE) . 'dashboard.css',
            array(),
            (string) filemtime(plugin_dir_path(LINKBLOG_PLUGIN_FILE) . 'dashboard.css')
        );

        if (strpos($hook, 'linkblog-schedule') !== false) {
            $asset_file = plugin_dir_path(LINKBLOG_PLUGIN_FILE) . 'build/schedule.asset.php';
            if (file_exists($asset_file)) {
                $asset = require_once $asset_file;
            } else {
                $asset = array('dependencies' => array(), 'version' => '1.0.0');
            }

            wp_enqueue_script(
                'linkblog-schedule',
                plugin_dir_url(LINKBLOG_PLUGIN_FILE) . 'build/schedule.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );

            if (file_exists(plugin_dir_path(LINKBLOG_PLUGIN_FILE) . 'build/schedule.css')) {
                wp_enqueue_style(
                    'linkblog-schedule-style',
                    plugin_dir_url(LINKBLOG_PLUGIN_FILE) . 'build/schedule.css',
                    array('wp-components'),
                    $asset['version']
                );
            }
        }
    }

    public function registerSettingX(): void {
        register_setting('linkblog_x_group', 'linkblog_x_settings', [
            'sanitize_callback' => [$this, 'sanitizeSettingX'],
        ]);
    }

    public function sanitizeSettingX(mixed $input): array {
        $toggles = ['ext_enabled', 'ext_save_as_draft', 'ext_auto_title', 'ext_show_tags',
                    'post_add_source', 'post_auto_excerpt', 'post_archive',
                    'ui_compact_view', 'ui_category_badges',
                    'api_public', 'api_cors', 'api_debug', 'api_auto_trash'];
        $clean = [];
        foreach ($toggles as $key) {
            $clean[$key] = !empty($input[$key]) ? 1 : 0;
        }
        $clean['ext_default_category']  = sanitize_text_field($input['ext_default_category'] ?? '');
        $clean['post_default_status']   = in_array($input['post_default_status'] ?? '', ['draft', 'publish'], true)
                                          ? $input['post_default_status'] : 'draft';
        $clean['post_author']           = absint($input['post_author'] ?? 0);
        $clean['ui_accent_color']       = sanitize_hex_color($input['ui_accent_color'] ?? '') ?: '#2271b1';
        $clean['ui_links_per_page']     = max(1, absint($input['ui_links_per_page'] ?? 20));
        $clean['ui_date_format']        = in_array($input['ui_date_format'] ?? '', ['relative', 'absolute'], true)
                                          ? $input['ui_date_format'] : 'relative';
        $clean['api_cache_minutes']     = max(1, absint($input['api_cache_minutes'] ?? 60));
        return $clean;
    }

    public function settingXPage(): void {
        $defaults = [
            'ext_enabled'          => 0,
            'ext_default_category' => '',
            'ext_save_as_draft'    => 1,
            'ext_auto_title'       => 1,
            'ext_show_tags'        => 1,
            'post_default_status'  => 'draft',
            'post_add_source'      => 0,
            'post_auto_excerpt'    => 1,
            'post_archive'         => 0,
            'post_author'          => 0,
            'ui_accent_color'      => '#2271b1',
            'ui_compact_view'      => 0,
            'ui_links_per_page'    => 20,
            'ui_category_badges'   => 1,
            'ui_date_format'       => 'relative',
            'api_public'           => 0,
            'api_cors'             => 0,
            'api_cache_minutes'    => 60,
            'api_debug'            => 0,
            'api_auto_trash'       => 0,
        ];
        $o = wp_parse_args((array) get_option('linkblog_x_settings', []), $defaults);

        $tog = static function(bool $val): string {
            return $val ? __('Enabled', 'linkblog') : __('Disabled', 'linkblog');
        };
        ?>
        <div class="wrap">
            <h1>
                <?php esc_html_e('Setting X', 'linkblog'); ?>
                <span class="lb-x-badge"><?php esc_html_e('Experimental', 'linkblog'); ?></span>
            </h1>

            <div class="lb-x-controls">
                <strong><?php esc_html_e('Quick View:', 'linkblog'); ?></strong>
                <button type="button" class="button js-lb-expand-all"><?php esc_html_e('Expand All', 'linkblog'); ?></button>
                <button type="button" class="button js-lb-collapse-all"><?php esc_html_e('Collapse All', 'linkblog'); ?></button>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields('linkblog_x_group'); ?>

                <div class="lb-expansion-list">

                    <!-- Chrome Extension -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-admin-plugins"></span>
                            <h2><?php esc_html_e('Chrome Extension', 'linkblog'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Extension enabled', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_enabled'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ext_enabled]" value="1" <?php checked(1, $o['ext_enabled']); ?>>
                                <?php esc_html_e('Enable the Chrome extension integration', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Default category', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($o['ext_default_category'] ?: __('Not set', 'linkblog')); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="text" name="linkblog_x_settings[ext_default_category]" value="<?php echo esc_attr($o['ext_default_category']); ?>" class="regular-text">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Save as draft', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_save_as_draft'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ext_save_as_draft]" value="1" <?php checked(1, $o['ext_save_as_draft']); ?>>
                                <?php esc_html_e('Save links as drafts by default', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-fill title', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_auto_title'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ext_auto_title]" value="1" <?php checked(1, $o['ext_auto_title']); ?>>
                                <?php esc_html_e('Pre-fill title from page title', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Show tag input', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_show_tags'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ext_show_tags]" value="1" <?php checked(1, $o['ext_show_tags']); ?>>
                                <?php esc_html_e('Show tag input field in the extension popup', 'linkblog'); ?></label>
                            </div>
                        </div>
                    </div>

                    <!-- Post Settings -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-admin-post"></span>
                            <h2><?php esc_html_e('Post Settings', 'linkblog'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Default post status', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html(ucfirst($o['post_default_status'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <select name="linkblog_x_settings[post_default_status]">
                                    <option value="draft" <?php selected('draft', $o['post_default_status']); ?>><?php esc_html_e('Draft', 'linkblog'); ?></option>
                                    <option value="publish" <?php selected('publish', $o['post_default_status']); ?>><?php esc_html_e('Publish', 'linkblog'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Add source URL to content', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_add_source'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[post_add_source]" value="1" <?php checked(1, $o['post_add_source']); ?>>
                                <?php esc_html_e('Append source URL to post content', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-generate excerpt', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_auto_excerpt'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[post_auto_excerpt]" value="1" <?php checked(1, $o['post_auto_excerpt']); ?>>
                                <?php esc_html_e('Generate excerpt from link description', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Archive posts after roundup', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_archive'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[post_archive]" value="1" <?php checked(1, $o['post_archive']); ?>>
                                <?php esc_html_e('Move source posts to trash after roundup is published', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Override post author ID', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo $o['post_author'] ? esc_html((string) $o['post_author']) : esc_html__('Default', 'linkblog'); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkblog_x_settings[post_author]" value="<?php echo esc_attr((string) $o['post_author']); ?>" min="0" style="width:80px;">
                                <p class="description"><?php esc_html_e('0 = use current user', 'linkblog'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- UI / Appearance -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-art"></span>
                            <h2><?php esc_html_e('UI / Appearance', 'linkblog'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Accent color', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($o['ui_accent_color']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="color" name="linkblog_x_settings[ui_accent_color]" value="<?php echo esc_attr($o['ui_accent_color']); ?>">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Compact dashboard', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ui_compact_view'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ui_compact_view]" value="1" <?php checked(1, $o['ui_compact_view']); ?>>
                                <?php esc_html_e('Use compact spacing on the dashboard', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Links per page', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html((string) $o['ui_links_per_page']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkblog_x_settings[ui_links_per_page]" value="<?php echo esc_attr((string) $o['ui_links_per_page']); ?>" min="1" max="200" style="width:80px;">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Show category badges', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ui_category_badges'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[ui_category_badges]" value="1" <?php checked(1, $o['ui_category_badges']); ?>>
                                <?php esc_html_e('Display category badges on link items', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Date format', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html(ucfirst($o['ui_date_format'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <select name="linkblog_x_settings[ui_date_format]">
                                    <option value="relative" <?php selected('relative', $o['ui_date_format']); ?>><?php esc_html_e('Relative (e.g. 2 hours ago)', 'linkblog'); ?></option>
                                    <option value="absolute" <?php selected('absolute', $o['ui_date_format']); ?>><?php esc_html_e('Absolute (e.g. 2026-04-20)', 'linkblog'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced / API -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-rest-api"></span>
                            <h2><?php esc_html_e('Advanced / API', 'linkblog'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Enable public API', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_public'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[api_public]" value="1" <?php checked(1, $o['api_public']); ?>>
                                <?php esc_html_e('Allow unauthenticated read access to the REST API', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Allow CORS', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_cors'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[api_cors]" value="1" <?php checked(1, $o['api_cors']); ?>>
                                <?php esc_html_e('Send CORS headers for cross-origin requests', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Cache duration (min)', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html((string) $o['api_cache_minutes']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkblog_x_settings[api_cache_minutes]" value="<?php echo esc_attr((string) $o['api_cache_minutes']); ?>" min="1" style="width:80px;">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Debug mode', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_debug'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[api_debug]" value="1" <?php checked(1, $o['api_debug']); ?>>
                                <?php esc_html_e('Log API requests and errors', 'linkblog'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-trash after publishing', 'linkblog'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_auto_trash'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkblog_x_settings[api_auto_trash]" value="1" <?php checked(1, $o['api_auto_trash']); ?>>
                                <?php esc_html_e('Automatically trash links after they are published', 'linkblog'); ?></label>
                            </div>
                        </div>
                    </div>

                </div><!-- .lb-expansion-list -->

                <div class="lb-x-sticky-bar">
                    <div class="bar-inner">
                        <span><?php esc_html_e('20 total options available.', 'linkblog'); ?></span>
                        <?php submit_button(__('Save All Changes', 'linkblog'), 'primary', 'submit', false); ?>
                    </div>
                </div>
            </form>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.lb-expansion-trigger').on('click', function() {
                $(this).closest('.lb-expansion-row').toggleClass('is-open');
            });
            $('.js-lb-expand-all').on('click', function() { $('.lb-expansion-row').addClass('is-open'); });
            $('.js-lb-collapse-all').on('click', function() { $('.lb-expansion-row').removeClass('is-open'); });
        });
        </script>
        <?php
    }

    public function hideCategoryFields(): void {
        global $pagenow;
        if ($pagenow !== 'edit-tags.php') {
            return;
        }
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
        if ($taxonomy !== 'linkblog_category') {
            return;
        }
        echo '<style>.term-description-wrap{display:none}</style>';
    }

    public function addDashboardWidget(): void {
        wp_add_dashboard_widget(
            'linkblog_dashboard_widget',
            __('LinkBlog Summary', 'linkblog'),
            [$this, 'dashboardWidgetContent']
        );
    }
}
