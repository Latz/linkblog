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
            <h1 class="wp-heading-inline"><?php esc_html_e('LinkBlog - All Links', 'LinkBlog'); ?></h1>
            <a href="<?php echo esc_url(admin_url('admin.php?page=linkblog-add')); ?>" class="page-title-action"><?php esc_html_e('Add New', 'LinkBlog'); ?></a>
            <hr class="wp-header-end">

            <?php if ($action_message) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post($action_message); ?></p></div>
            <?php endif; ?>

            <?php if ($action_error) : ?>
                <div class="notice notice-error is-dismissible"><p><?php echo esc_html($action_error); ?></p></div>
            <?php endif; ?>

            <?php if (!$has_links) : ?>
                <p><?php esc_html_e('No links found. Add your first link!', 'LinkBlog'); ?></p>
            <?php else : ?>
                <?php foreach ($grouped_links as $category_name => $category_links) : ?>
                    <div class="lb-category-section">
                        <h2 class="lb-category-heading"><?php echo esc_html($category_name); ?></h2>

                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th style="width: 25%;"><?php esc_html_e('Title', 'LinkBlog'); ?></th>
                                    <th style="width: 25%;"><?php esc_html_e('URL', 'LinkBlog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Status', 'LinkBlog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Published Date', 'LinkBlog'); ?></th>
                                    <th style="width: 10%;"><?php esc_html_e('Date', 'LinkBlog'); ?></th>
                                    <th style="width: 20%;"><?php esc_html_e('Actions', 'LinkBlog'); ?></th>
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
                                        <td>
                                            <?php if ($publish_status === 'published') : ?>
                                                <span class="lb-status-badge lb-status-published">✓ <?php esc_html_e('Published', 'LinkBlog'); ?></span>
                                            <?php elseif ($publish_status === 'draft') : ?>
                                                <span class="lb-status-badge lb-status-draft">📝 <?php esc_html_e('Draft', 'LinkBlog'); ?></span>
                                            <?php else : ?>
                                                <span class="lb-status-badge lb-status-unpublished"><?php esc_html_e('Unpublished', 'LinkBlog'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($published_date) : ?>
                                                <?php echo esc_html(mysql2date('Y-m-d', $published_date)); ?>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html(get_the_date('Y-m-d', $link->ID)); ?></td>
                                        <td>
                                            <?php if ($publish_status === 'unpublished') : ?>
                                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID)); ?>"><?php esc_html_e('Publish', 'LinkBlog'); ?></a> |
                                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=draft_link&link_id=' . $link->ID), 'draft_link_' . $link->ID)); ?>"><?php esc_html_e('Save as Draft', 'LinkBlog'); ?></a> |
                                            <?php elseif ($publish_status === 'published') : ?>
                                                <a href="<?php echo esc_url(get_permalink($published_post_id)); ?>" target="_blank"><?php esc_html_e('View Post', 'LinkBlog'); ?></a> |
                                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID)); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to unpublish this link?', 'LinkBlog'); ?>');"><?php esc_html_e('Unpublish', 'LinkBlog'); ?></a> |
                                            <?php elseif ($publish_status === 'draft') : ?>
                                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID)); ?>"><?php esc_html_e('Publish', 'LinkBlog'); ?></a> |
                                                <a href="<?php echo esc_url(get_edit_post_link($published_post_id)); ?>" target="_blank"><?php esc_html_e('View Draft', 'LinkBlog'); ?></a> |
                                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID)); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to unpublish this link?', 'LinkBlog'); ?>');"><?php esc_html_e('Unpublish', 'LinkBlog'); ?></a> |
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url(get_edit_post_link($link->ID)); ?>"><?php esc_html_e('Edit', 'LinkBlog'); ?></a> |
                                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=delete&link_id=' . $link->ID), 'delete_link_' . $link->ID)); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this link?', 'LinkBlog'); ?>');"><?php esc_html_e('Delete', 'LinkBlog'); ?></a>
                                        </td>
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
            $result = $this->createBlogPost($link_id, false);
            if ($result['success']) {
                $message = $result['message'] . ' <a href="' . esc_url(get_permalink($result['post_id'])) . '" target="_blank">' . esc_html__('View Post', 'LinkBlog') . '</a>';
            } else {
                $error = $result['message'];
            }
        } elseif ($action === 'draft_link' && wp_verify_nonce($nonce, 'draft_link_' . $link_id)) {
            $result = $this->createBlogPost($link_id, true);
            if ($result['success']) {
                $message = $result['message'] . ' <a href="' . esc_url(get_edit_post_link($result['post_id'])) . '" target="_blank">' . esc_html__('Edit Draft', 'LinkBlog') . '</a>';
            } else {
                $error = $result['message'];
            }
        } elseif ($action === 'unpublish_link' && wp_verify_nonce($nonce, 'unpublish_link_' . $link_id)) {
            $result = $this->unpublishLink($link_id);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        } elseif ($action === 'delete' && wp_verify_nonce($nonce, 'delete_link_' . $link_id)) {
            wp_delete_post($link_id, true);
            $message = __('Link deleted successfully.', 'LinkBlog');
        }

        return [$message, $error];
    }
}
