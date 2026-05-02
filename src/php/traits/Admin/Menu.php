<?php

declare(strict_types=1);

trait LinkDigest_Admin_Menu {

    public function adminMenu(): void {
        add_menu_page(
            __('linkdigest', 'linkdigest'),
            __('linkdigest', 'linkdigest'),
            'read',
            'linkdigest-dashboard',
            [$this, 'dashboardPage'],
            plugins_url('assets/icon-menu.png', LINKDIGEST_PLUGIN_FILE),
            6
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Dashboard', 'linkdigest'),
            __('Dashboard', 'linkdigest'),
            'read',
            'linkdigest-dashboard',
            [$this, 'dashboardPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Show Links', 'linkdigest'),
            __('All Links', 'linkdigest'),
            'read',
            'linkdigest-admin',
            [$this, 'showLinksPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Add Link', 'linkdigest'),
            __('Add Link', 'linkdigest'),
            'read',
            'linkdigest-add',
            [$this, 'addLinkPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Categories', 'linkdigest'),
            __('Categories', 'linkdigest'),
            'manage_categories',
            'linkdigest-categories',
            [$this, 'categoriesPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Tags', 'linkdigest'),
            __('Tags', 'linkdigest'),
            'manage_categories',
            'edit-tags.php?taxonomy=linkdigest_tag&post_type=linkdigest'
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Chrome Extension', 'linkdigest'),
            __('Chrome Extension', 'linkdigest'),
            'manage_options',
            'linkdigest-settings',
            [$this, 'settingsPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Settings', 'linkdigest'),
            __('Settings', 'linkdigest'),
            'manage_options',
            'linkdigest-setting-x',
            [$this, 'settingXPage']
        );

        add_submenu_page(
            'linkdigest-dashboard',
            __('Schedule', 'linkdigest'),
            __('Schedule', 'linkdigest'),
            'manage_options',
            'linkdigest-schedule',
            [$this, 'schedulePage']
        );
    }

    public function parentFileFilter(string $parent_file): string {
        global $pagenow;
        if ($pagenow === 'edit-tags.php') {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
            if ($taxonomy === 'linkdigest_tag') {
                return 'linkdigest-dashboard';
            }
        }
        return $parent_file;
    }

    public function submenuFileFilter(?string $submenu_file): string {
        global $pagenow;
        if ($pagenow === 'edit-tags.php') {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : '';
            if ($taxonomy === 'linkdigest_tag') {
                return 'edit-tags.php?taxonomy=linkdigest_tag&post_type=linkdigest';
            }
        }
        return $submenu_file ?? '';
    }

    private function renderCopyableField(string $id, string $value): void {
        ?>
        <div style="display: flex; gap: 8px; align-items: center;">
            <input
                type="text"
                id="<?php echo esc_attr($id); ?>"
                value="<?php echo esc_attr($value); ?>"
                readonly
                onclick="this.select();"
                style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
            >
            <button type="button" class="button linkdigest-copy-btn" data-clipboard-target="<?php echo esc_attr($id); ?>">
                <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
            </button>
        </div>
        <?php
    }

    public function settingsPage(): void {
        // Handle API key generation
        $nonce = isset($_POST['linkdigest_settings_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkdigest_settings_nonce'])) : '';
        if (isset($_POST['linkdigest_generate_api_key']) && wp_verify_nonce($nonce, 'linkdigest_settings')) {
            $api_key = wp_generate_password(32, false);
            update_option('linkdigest_api_key', $api_key);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('New API key generated successfully!', 'linkdigest') . '</p></div>';
        }

        $api_key     = get_option('linkdigest_api_key');
        $site_url    = get_site_url();
        $endpoint    = $site_url . '/wp-json/linkdigest/v1';
        $has_key_attr = $api_key ? 'data-has-key="1"' : '';
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('LinkDigest Extension', 'linkdigest'); ?></h1>

            <div class="card" style="max-width: 800px;">
                <h2><?php esc_html_e('Chrome Extension Access Data', 'linkdigest'); ?></h2>
                <p><?php esc_html_e('Use these credentials to connect the LinkDigest Chrome extension to your WordPress site.', 'linkdigest'); ?></p>

                <div style="margin: 20px 0;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <?php esc_html_e('API Endpoint', 'linkdigest'); ?>
                        <span style="font-weight: 400; color: #666; font-size: 12px;">(<?php esc_html_e('read-only', 'linkdigest'); ?>)</span>
                    </label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input
                            type="text"
                            id="linkdigest-api-endpoint"
                            value="<?php echo esc_attr($endpoint); ?>"
                            disabled
                            style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1; color: #a0a0a0; cursor: not-allowed;"
                        >
                        <button type="button" class="button linkdigest-copy-btn" data-clipboard-target="linkdigest-api-endpoint">
                            <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                        </button>
                    </div>
                    <p class="description">
                        <?php esc_html_e('Use this URL in the Chrome extension settings.', 'linkdigest'); ?>
                        <a href="<?php echo esc_url($endpoint); ?>" target="_blank" style="margin-left: 8px;">
                            <?php esc_html_e('View REST API', 'linkdigest'); ?> ↗
                        </a>
                    </p>
                </div>

                <?php if ($api_key) : ?>
                    <div style="margin: 20px 0;">
                        <label for="linkdigest-api-key" style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <?php esc_html_e('API Key:', 'linkdigest'); ?>
                        </label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input
                                type="password"
                                id="linkdigest-api-key"
                                value="<?php echo esc_attr($api_key); ?>"
                                readonly
                                style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
                            >
                            <button type="button" class="button linkdigest-toggle-key" title="<?php esc_attr_e('Show / hide API key', 'linkdigest'); ?>">
                                <span class="dashicons dashicons-visibility" style="margin-top: 3px;"></span>
                            </button>
                            <button type="button" class="button linkdigest-copy-btn" data-clipboard-target="linkdigest-api-key">
                                <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                            </button>
                        </div>
                        <p class="description" style="margin-top: 6px;">
                            <?php esc_html_e('Keep this key secure. Use the copy button to transfer it without revealing it.', 'linkdigest'); ?>
                        </p>
                        <div style="margin-top: 12px;">
                            <button type="button" id="linkdigest-test-connection" class="button">
                                <?php esc_html_e('Test Connection', 'linkdigest'); ?>
                            </button>
                            <span id="linkdigest-connection-status" style="margin-left: 10px; font-weight: 600;"></span>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="post" action="" id="linkdigest-generate-form" <?php echo $has_key_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php wp_nonce_field('linkdigest_settings', 'linkdigest_settings_nonce'); ?>
                    <?php if ($api_key) : ?>
                        <div class="notice notice-warning inline" style="margin: 0 0 12px; padding: 8px 12px;">
                            <p><?php esc_html_e('Warning: Generating a new key will permanently invalidate the current one. You will need to update the Chrome extension with the new key.', 'linkdigest'); ?></p>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="linkdigest_generate_api_key" class="button button-primary">
                        <?php echo $api_key ? esc_html__('Generate New API Key', 'linkdigest') : esc_html__('Generate API Key', 'linkdigest'); ?>
                    </button>
                </form>
            </div>

            <details style="max-width: 800px; margin-top: 20px;" <?php echo $api_key ? '' : 'open'; ?>>
                <summary style="cursor: pointer; list-style: none; outline: none;">
                    <div class="card" style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                        <span class="dashicons dashicons-arrow-right-alt2" id="linkdigest-setup-arrow" style="margin-top: 2px; transition: transform 0.2s;"></span>
                        <h2 style="margin: 0; font-size: 14px;"><?php esc_html_e('Chrome Extension Setup', 'linkdigest'); ?></h2>
                    </div>
                </summary>
                <div class="card" style="border-top: none; margin-top: -1px;">
                    <ol>
                        <li><?php esc_html_e('Download and install the LinkDigest Chrome extension', 'linkdigest'); ?></li>
                        <li><?php esc_html_e('Click the extension icon and go to Settings', 'linkdigest'); ?></li>
                        <li><?php esc_html_e('Paste your API Endpoint and API Key from above', 'linkdigest'); ?></li>
                        <li><?php esc_html_e('Click Save', 'linkdigest'); ?></li>
                        <li><?php esc_html_e('Now you can save links directly from any webpage!', 'linkdigest'); ?></li>
                    </ol>
                </div>
            </details>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Copy to clipboard
            $('.linkdigest-copy-btn').on('click', function() {
                var targetId = $(this).data('clipboard-target');
                var input = document.getElementById(targetId);
                if (!input) { return; }

                var $btn = $(this);
                var value = input.value;

                function showSuccess() {
                    var originalHtml = $btn.html();
                    $btn.html('<span class="dashicons dashicons-yes" style="margin-top: 3px; color: #00a32a;"></span>');
                    setTimeout(function() { $btn.html(originalHtml); }, 2000);
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(value).then(showSuccess).catch(function() {});
                } else {
                    var prevType = input.type;
                    var wasDisabled = input.disabled;
                    input.type = 'text';
                    input.disabled = false;
                    input.select();
                    input.setSelectionRange(0, 99999);
                    try { document.execCommand('copy'); showSuccess(); } catch (err) {}
                    input.disabled = wasDisabled;
                    input.type = prevType;
                }
            });

            // Show / hide API key toggle
            $('.linkdigest-toggle-key').on('click', function() {
                var input = document.getElementById('linkdigest-api-key');
                if (!input) { return; }
                var $icon = $(this).find('.dashicons');
                if (input.type === 'password') {
                    input.type = 'text';
                    $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                } else {
                    input.type = 'password';
                    $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                }
            });

            // Confirm before regenerating API key
            $('#linkdigest-generate-form').on('submit', function(e) {
                if ($(this).data('has-key')) {
                    var ok = window.confirm('<?php echo esc_js(__('This will permanently invalidate your current API key. You will need to update the Chrome extension with the new key. Continue?', 'linkdigest')); ?>');
                    if (!ok) { e.preventDefault(); }
                }
            });

            // Test Connection
            $('#linkdigest-test-connection').on('click', function() {
                var endpoint = (document.getElementById('linkdigest-api-endpoint') || {}).value || '';
                var apiKey   = (document.getElementById('linkdigest-api-key') || {}).value || '';
                var $status  = $('#linkdigest-connection-status');

                if (!endpoint || !apiKey) {
                    $status.css('color', '#d63638').text('<?php echo esc_js(__('Missing endpoint or API key.', 'linkdigest')); ?>');
                    return;
                }

                var $btn = $(this);
                $btn.prop('disabled', true);
                $status.css('color', '#666').text('<?php echo esc_js(__('Testing…', 'linkdigest')); ?>');

                fetch(endpoint.replace(/\/$/, '') + '/categories', {
                    headers: { 'X-Api-Key': apiKey }
                })
                .then(function(res) {
                    if (res.ok) {
                        $status.css('color', '#00a32a').text('✓ <?php echo esc_js(__('Connected successfully.', 'linkdigest')); ?>');
                    } else {
                        $status.css('color', '#d63638').text('✗ <?php echo esc_js(__('Connection failed', 'linkdigest')); ?> (HTTP ' + res.status + ')');
                    }
                })
                .catch(function() {
                    $status.css('color', '#d63638').text('✗ <?php echo esc_js(__('Could not reach endpoint.', 'linkdigest')); ?>');
                })
                .finally(function() { $btn.prop('disabled', false); });
            });

            // Rotate setup section arrow on toggle
            var $details = $('details[style*="800px"]');
            $details.on('toggle', function() {
                var $arrow = $('#linkdigest-setup-arrow');
                if (this.open) {
                    $arrow.css('transform', 'rotate(90deg)');
                } else {
                    $arrow.css('transform', 'rotate(0deg)');
                }
            });
            if ($details[0] && $details[0].open) {
                $('#linkdigest-setup-arrow').css('transform', 'rotate(90deg)');
            }
        });
        </script>
        <?php
    }

    public function schedulePage(): void {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Schedule Configuration', 'linkdigest'); ?></h1>
            <div id="linkdigest-schedule-root"></div>
        </div>
        <?php
    }

    public function enqueueAdminAssets(string $hook): void {
        if (strpos($hook, 'linkdigest') === false) {
            return;
        }

        if (strpos($hook, 'linkdigest-dashboard') !== false) {
            wp_enqueue_script('postbox');
        }

        wp_enqueue_style('dashicons');
        wp_enqueue_style(
            'linkdigest-dashboard',
            plugin_dir_url(LINKDIGEST_PLUGIN_FILE) . 'dashboard.css',
            array(),
            (string) filemtime(plugin_dir_path(LINKDIGEST_PLUGIN_FILE) . 'dashboard.css')
        );

        if (strpos($hook, 'linkdigest-schedule') !== false) {
            $asset_file = plugin_dir_path(LINKDIGEST_PLUGIN_FILE) . 'build/schedule.asset.php';
            if (file_exists($asset_file)) {
                $asset = require_once $asset_file;
            } else {
                $asset = array('dependencies' => array(), 'version' => '1.0.0');
            }

            wp_enqueue_script(
                'linkdigest-schedule',
                plugin_dir_url(LINKDIGEST_PLUGIN_FILE) . 'build/schedule.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );

            wp_localize_script('linkdigest-schedule', 'linkdigestSchedule', array(
                'allModes'     => array_column(\ScheduleMode::cases(), 'value'),
                'timeModes'    => array_column(\ScheduleMode::timeBased(), 'value'),
                'triggerModes' => array_column(\ScheduleMode::triggerBased(), 'value'),
                'timezone'     => wp_timezone_string(),
            ));

            if (file_exists(plugin_dir_path(LINKDIGEST_PLUGIN_FILE) . 'build/schedule.css')) {
                wp_enqueue_style(
                    'linkdigest-schedule-style',
                    plugin_dir_url(LINKDIGEST_PLUGIN_FILE) . 'build/schedule.css',
                    array('wp-components'),
                    $asset['version']
                );
            }
        }
    }

    public function registerSettingX(): void {
        register_setting('linkdigest_x_group', 'linkdigest_x_settings', [
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
        $o = wp_parse_args((array) get_option('linkdigest_x_settings', []), $defaults);

        $tog = static function(bool $val): string {
            return $val ? __('Enabled', 'linkdigest') : __('Disabled', 'linkdigest');
        };
        ?>
        <div class="wrap">
            <h1>
                <?php esc_html_e('Setting X', 'linkdigest'); ?>
                <span class="lb-x-badge"><?php esc_html_e('Experimental', 'linkdigest'); ?></span>
            </h1>

            <div class="lb-x-controls">
                <strong><?php esc_html_e('Quick View:', 'linkdigest'); ?></strong>
                <button type="button" class="button js-lb-expand-all"><?php esc_html_e('Expand All', 'linkdigest'); ?></button>
                <button type="button" class="button js-lb-collapse-all"><?php esc_html_e('Collapse All', 'linkdigest'); ?></button>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields('linkdigest_x_group'); ?>

                <div class="lb-expansion-list">

                    <!-- Chrome Extension -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-admin-plugins"></span>
                            <h2><?php esc_html_e('Chrome Extension', 'linkdigest'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Extension enabled', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_enabled'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ext_enabled]" value="1" <?php checked(1, $o['ext_enabled']); ?>>
                                <?php esc_html_e('Enable the Chrome extension integration', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Default category', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($o['ext_default_category'] ?: __('Not set', 'linkdigest')); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="text" name="linkdigest_x_settings[ext_default_category]" value="<?php echo esc_attr($o['ext_default_category']); ?>" class="regular-text">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Save as draft', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_save_as_draft'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ext_save_as_draft]" value="1" <?php checked(1, $o['ext_save_as_draft']); ?>>
                                <?php esc_html_e('Save links as drafts by default', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-fill title', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_auto_title'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ext_auto_title]" value="1" <?php checked(1, $o['ext_auto_title']); ?>>
                                <?php esc_html_e('Pre-fill title from page title', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Show tag input', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ext_show_tags'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ext_show_tags]" value="1" <?php checked(1, $o['ext_show_tags']); ?>>
                                <?php esc_html_e('Show tag input field in the extension popup', 'linkdigest'); ?></label>
                            </div>
                        </div>
                    </div>

                    <!-- Post Settings -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-admin-post"></span>
                            <h2><?php esc_html_e('Post Settings', 'linkdigest'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Default post status', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html(ucfirst($o['post_default_status'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <select name="linkdigest_x_settings[post_default_status]">
                                    <option value="draft" <?php selected('draft', $o['post_default_status']); ?>><?php esc_html_e('Draft', 'linkdigest'); ?></option>
                                    <option value="publish" <?php selected('publish', $o['post_default_status']); ?>><?php esc_html_e('Publish', 'linkdigest'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Add source URL to content', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_add_source'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[post_add_source]" value="1" <?php checked(1, $o['post_add_source']); ?>>
                                <?php esc_html_e('Append source URL to post content', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-generate excerpt', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_auto_excerpt'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[post_auto_excerpt]" value="1" <?php checked(1, $o['post_auto_excerpt']); ?>>
                                <?php esc_html_e('Generate excerpt from link description', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Archive posts after roundup', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['post_archive'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[post_archive]" value="1" <?php checked(1, $o['post_archive']); ?>>
                                <?php esc_html_e('Move source posts to trash after roundup is published', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Override post author ID', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo $o['post_author'] ? esc_html((string) $o['post_author']) : esc_html__('Default', 'linkdigest'); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkdigest_x_settings[post_author]" value="<?php echo esc_attr((string) $o['post_author']); ?>" min="0" style="width:80px;">
                                <p class="description"><?php esc_html_e('0 = use current user', 'linkdigest'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- UI / Appearance -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-art"></span>
                            <h2><?php esc_html_e('UI / Appearance', 'linkdigest'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Accent color', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($o['ui_accent_color']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="color" name="linkdigest_x_settings[ui_accent_color]" value="<?php echo esc_attr($o['ui_accent_color']); ?>">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Compact dashboard', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ui_compact_view'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ui_compact_view]" value="1" <?php checked(1, $o['ui_compact_view']); ?>>
                                <?php esc_html_e('Use compact spacing on the dashboard', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Links per page', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html((string) $o['ui_links_per_page']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkdigest_x_settings[ui_links_per_page]" value="<?php echo esc_attr((string) $o['ui_links_per_page']); ?>" min="1" max="200" style="width:80px;">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Show category badges', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['ui_category_badges'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[ui_category_badges]" value="1" <?php checked(1, $o['ui_category_badges']); ?>>
                                <?php esc_html_e('Display category badges on link items', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Date format', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html(ucfirst($o['ui_date_format'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <select name="linkdigest_x_settings[ui_date_format]">
                                    <option value="relative" <?php selected('relative', $o['ui_date_format']); ?>><?php esc_html_e('Relative (e.g. 2 hours ago)', 'linkdigest'); ?></option>
                                    <option value="absolute" <?php selected('absolute', $o['ui_date_format']); ?>><?php esc_html_e('Absolute (e.g. 2026-04-20)', 'linkdigest'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced / API -->
                    <div class="lb-category-block">
                        <div class="lb-category-header">
                            <span class="dashicons dashicons-rest-api"></span>
                            <h2><?php esc_html_e('Advanced / API', 'linkdigest'); ?></h2>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Enable public API', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_public'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[api_public]" value="1" <?php checked(1, $o['api_public']); ?>>
                                <?php esc_html_e('Allow unauthenticated read access to the REST API', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Allow CORS', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_cors'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[api_cors]" value="1" <?php checked(1, $o['api_cors']); ?>>
                                <?php esc_html_e('Send CORS headers for cross-origin requests', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Cache duration (min)', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html((string) $o['api_cache_minutes']); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <input type="number" name="linkdigest_x_settings[api_cache_minutes]" value="<?php echo esc_attr((string) $o['api_cache_minutes']); ?>" min="1" style="width:80px;">
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Debug mode', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_debug'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[api_debug]" value="1" <?php checked(1, $o['api_debug']); ?>>
                                <?php esc_html_e('Log API requests and errors', 'linkdigest'); ?></label>
                            </div>
                        </div>

                        <div class="lb-expansion-row">
                            <button type="button" class="lb-expansion-trigger">
                                <span class="lb-setting-title"><?php esc_html_e('Auto-trash after publishing', 'linkdigest'); ?></span>
                                <span class="lb-setting-summary"><?php echo esc_html($tog((bool) $o['api_auto_trash'])); ?></span>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <div class="lb-expansion-content">
                                <label><input type="checkbox" name="linkdigest_x_settings[api_auto_trash]" value="1" <?php checked(1, $o['api_auto_trash']); ?>>
                                <?php esc_html_e('Automatically trash links after they are published', 'linkdigest'); ?></label>
                            </div>
                        </div>
                    </div>

                </div><!-- .lb-expansion-list -->

                <div class="lb-x-sticky-bar">
                    <div class="bar-inner">
                        <span><?php esc_html_e('20 total options available.', 'linkdigest'); ?></span>
                        <?php submit_button(__('Save All Changes', 'linkdigest'), 'primary', 'submit', false); ?>
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

    public function addDashboardWidget(): void {
        wp_add_dashboard_widget(
            'linkdigest_dashboard_widget',
            __('LinkDigest Summary', 'linkdigest'),
            [$this, 'dashboardWidgetContent']
        );
    }
}
