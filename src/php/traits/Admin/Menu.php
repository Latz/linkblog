<?php

declare(strict_types=1);

trait LinkBlog_Admin_Menu {

    public function adminMenu(): void {
        add_menu_page(
            __('linkblog', 'linkblog'),
            __('linkblog', 'linkblog'),
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
                    } catch (err) {
                        console.error('Failed to copy:', err);
                    }
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
            '1.0.0'
        );

        if (strpos($hook, 'linkblog-schedule') !== false) {
            $asset_file = plugin_dir_path(LINKBLOG_PLUGIN_FILE) . 'build/schedule.asset.php';
            $asset = file_exists($asset_file)
                ? require_once $asset_file
                : array('dependencies' => array(), 'version' => '1.0.0');

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

    public function addDashboardWidget(): void {
        wp_add_dashboard_widget(
            'linkblog_dashboard_widget',
            __('LinkBlog Summary', 'linkblog'),
            [$this, 'dashboardWidgetContent']
        );
    }
}
