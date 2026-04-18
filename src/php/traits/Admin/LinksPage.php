<?php

declare(strict_types=1);

trait LinkBlog_Admin_LinksPage {

    public function showLinksPage(): void {
        [$action_message, $action_error] = $this->processLinksPageAction();
        $grouped_links = $this->getLinksGroupedByCategory();
        $has_links = false;
        foreach ($grouped_links as $category_links) {
            if (!empty($category_links)) {
                $has_links = true;
                break;
            }
        }
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('LinkBlog - All Links', 'linkblog'); ?></h1>
            <a href="<?php echo esc_url(admin_url('admin.php?page=linkblog-add')); ?>" class="page-title-action"><?php esc_html_e('Add New', 'linkblog'); ?></a>
            <hr class="wp-header-end">

            <?php if ($action_message) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post($action_message); ?></p></div>
            <?php endif; ?>

            <?php if ($action_error) : ?>
                <div class="notice notice-error is-dismissible"><p><?php echo esc_html($action_error); ?></p></div>
            <?php endif; ?>

            <?php if (!$has_links) : ?>
                <p><?php esc_html_e('No links found. Add your first link!', 'linkblog'); ?></p>
            <?php else : ?>
                <?php foreach ($grouped_links as $category_name => $category_links) : ?>
                    <div class="lb-category-section">
                        <h2 class="lb-category-heading"><?php echo esc_html($category_name); ?></h2>

                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th style="width: 25%;"><?php esc_html_e('Title', 'linkblog'); ?></th>
                                    <th style="width: 25%;"><?php esc_html_e('URL', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Status', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Published Date', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Date', 'linkblog'); ?></th>
                                    <th style="width: 20%;"><?php esc_html_e('Actions', 'linkblog'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($category_links as $link) :
                                    $url = get_post_meta($link->ID, '_linkblog_url', true);
                                    $publish_status = get_post_meta($link->ID, '_linkblog_publish_status', true);
                                    $published_post_id = get_post_meta($link->ID, '_linkblog_published_post_id', true);
                                    $published_date = get_post_meta($link->ID, '_linkblog_published_date', true);
                                    if (empty($publish_status)) {
                                        $publish_status = 'unpublished';
                                    }
                                ?>
                                    <tr>
                                        <td><strong><?php echo esc_html($link->post_title); ?></strong></td>
                                        <td>
                                            <?php if ($url) : ?>
                                                <a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo esc_html(substr($url, 0, 50)) . (strlen($url) > 50 ? '...' : ''); ?></a>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php $this->renderLinkStatusBadge($publish_status); ?></td>
                                        <td>
                                            <?php if ($published_date) : ?>
                                                <?php echo esc_html(mysql2date('Y-m-d', $published_date)); ?>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html(get_the_date('Y-m-d', $link->ID)); ?></td>
                                        <td><?php $this->renderLinkActions($link, $publish_status, $published_post_id); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    private function processLinksPageAction(): array {
        $message = '';
        $error   = '';

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!isset($_GET['action'], $_GET['link_id'], $_GET['_wpnonce'])) {
            return [$message, $error];
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $action  = sanitize_key(wp_unslash($_GET['action']));
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $link_id = absint($_GET['link_id']);
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $nonce   = sanitize_text_field(wp_unslash($_GET['_wpnonce']));

        if ($action === 'publish_link' && wp_verify_nonce($nonce, 'publish_link_' . $link_id)) {
            [$message, $error] = $this->executePublishAction($link_id, false);
        } elseif ($action === 'draft_link' && wp_verify_nonce($nonce, 'draft_link_' . $link_id)) {
            [$message, $error] = $this->executePublishAction($link_id, true);
        } elseif ($action === 'unpublish_link' && wp_verify_nonce($nonce, 'unpublish_link_' . $link_id)) {
            [$message, $error] = $this->executeUnpublishAction($link_id);
        } elseif ($action === 'delete' && wp_verify_nonce($nonce, 'delete_link_' . $link_id)) {
            wp_delete_post($link_id, true);
            $message = __('Link deleted successfully.', 'linkblog');
        }

        return [$message, $error];
    }

    private function executePublishAction(int $link_id, bool $as_draft): array {
        $result = $this->createBlogPost($link_id, $as_draft);
        if (!$result['success']) {
            return ['', $result['message']];
        }
        if ($as_draft) {
            return [esc_html($result['message']) . ' <a href="' . esc_url(get_edit_post_link($result['post_id'])) . '" target="_blank">' . esc_html__('Edit Draft', 'linkblog') . '</a>', ''];
        }
        return [esc_html($result['message']) . ' <a href="' . esc_url(get_permalink($result['post_id'])) . '" target="_blank">' . esc_html__('View Post', 'linkblog') . '</a>', ''];
    }

    private function executeUnpublishAction(int $link_id): array {
        $result = $this->unpublishLink($link_id);
        if ($result['success']) {
            return [$result['message'], ''];
        }
        return ['', $result['message']];
    }

    private function renderLinkStatusBadge(string $publish_status): void {
        if ($publish_status === 'published') {
            echo '<span class="lb-status-badge lb-status-published">✓ ' . esc_html__('Published', 'linkblog') . '</span>';
        } elseif ($publish_status === 'draft') {
            echo '<span class="lb-status-badge lb-status-draft">📝 ' . esc_html__('Draft', 'linkblog') . '</span>';
        } else {
            echo '<span class="lb-status-badge lb-status-unpublished">' . esc_html__('Unpublished', 'linkblog') . '</span>';
        }
    }

    private function renderLinkActions(\WP_Post $link, string $publish_status, mixed $published_post_id): void {
        $confirm_unpublish = "return confirm('" . esc_js(__('Are you sure you want to unpublish this link?', 'linkblog')) . "');";
        $confirm_delete    = "return confirm('" . esc_js(__('Are you sure you want to delete this link?', 'linkblog')) . "');";
        $publish_url       = esc_url(wp_nonce_url(admin_url(self::ADMIN_LINKS_PAGE . '&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID));
        $unpublish_url     = esc_url(wp_nonce_url(admin_url(self::ADMIN_LINKS_PAGE . '&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID));

        if ($publish_status === 'unpublished') {
            $draft_url = esc_url(wp_nonce_url(admin_url(self::ADMIN_LINKS_PAGE . '&action=draft_link&link_id=' . $link->ID), 'draft_link_' . $link->ID));
            echo '<a href="' . esc_url($publish_url) . '">' . esc_html__('Publish', 'linkblog') . '</a> | ';
            echo '<a href="' . esc_url($draft_url) . '">' . esc_html__('Save as Draft', 'linkblog') . '</a> | ';
        } elseif ($publish_status === 'published') {
            echo '<a href="' . esc_url(get_permalink($published_post_id)) . '" target="_blank">' . esc_html__('View Post', 'linkblog') . '</a> | ';
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $confirm_unpublish uses esc_js() internally
            echo '<a href="' . esc_url($unpublish_url) . '" onclick="' . $confirm_unpublish . '">' . esc_html__('Unpublish', 'linkblog') . '</a> | ';
        } elseif ($publish_status === 'draft') {
            echo '<a href="' . esc_url($publish_url) . '">' . esc_html__('Publish', 'linkblog') . '</a> | ';
            echo '<a href="' . esc_url(get_edit_post_link($published_post_id)) . '" target="_blank">' . esc_html__('View Draft', 'linkblog') . '</a> | ';
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $confirm_unpublish uses esc_js() internally
            echo '<a href="' . esc_url($unpublish_url) . '" onclick="' . $confirm_unpublish . '">' . esc_html__('Unpublish', 'linkblog') . '</a> | ';
        }
        echo '<a href="' . esc_url(get_edit_post_link($link->ID)) . '">' . esc_html__('Edit', 'linkblog') . '</a> | ';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $confirm_delete uses esc_js() internally
        echo '<a href="' . esc_url(wp_nonce_url(admin_url(self::ADMIN_LINKS_PAGE . '&action=delete&link_id=' . $link->ID), 'delete_link_' . $link->ID)) . '" onclick="' . $confirm_delete . '">' . esc_html__('Delete', 'linkblog') . '</a>';
    }
}
