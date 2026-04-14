<?php

declare(strict_types=1);

trait LinkBlog_Admin_LinksPage {

    public function showLinksPage(): void {
        $action_message = '';
        $action_error = '';

        // Handle publish action
        if (isset($_GET['action']) && $_GET['action'] === 'publish_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce($_GET['_wpnonce'], 'publish_link_' . $_GET['link_id'])) {
                $result = $this->createBlogPost($_GET['link_id'], false);
                if ($result['success']) {
                    $action_message = $result['message'] . ' <a href="' . get_permalink($result['post_id']) . '" target="_blank">' . __('View Post', 'linkblog') . '</a>';
                } else {
                    $action_error = $result['message'];
                }
            }
        }

        // Handle draft action
        if (isset($_GET['action']) && $_GET['action'] === 'draft_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce($_GET['_wpnonce'], 'draft_link_' . $_GET['link_id'])) {
                $result = $this->createBlogPost($_GET['link_id'], true);
                if ($result['success']) {
                    $action_message = $result['message'] . ' <a href="' . get_edit_post_link($result['post_id']) . '" target="_blank">' . __('Edit Draft', 'linkblog') . '</a>';
                } else {
                    $action_error = $result['message'];
                }
            }
        }

        // Handle unpublish action
        if (isset($_GET['action']) && $_GET['action'] === 'unpublish_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce($_GET['_wpnonce'], 'unpublish_link_' . $_GET['link_id'])) {
                $result = $this->unpublishLink($_GET['link_id']);
                if ($result['success']) {
                    $action_message = $result['message'];
                } else {
                    $action_error = $result['message'];
                }
            }
        }

        // Handle delete action
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce($_GET['_wpnonce'], 'delete_link_' . $_GET['link_id'])) {
                wp_delete_post($_GET['link_id'], true);
                $action_message = __('Link deleted successfully.', 'linkblog');
            }
        }

        // Get links grouped by category
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
            <h1 class="wp-heading-inline"><?php _e('LinkBlog - All Links', 'linkblog'); ?></h1>
            <a href="<?php echo admin_url('admin.php?page=linkblog-add'); ?>" class="page-title-action"><?php _e('Add New', 'linkblog'); ?></a>
            <hr class="wp-header-end">

            <?php if ($action_message) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post($action_message); ?></p></div>
            <?php endif; ?>

            <?php if ($action_error) : ?>
                <div class="notice notice-error is-dismissible"><p><?php echo esc_html($action_error); ?></p></div>
            <?php endif; ?>

            <?php if (!$has_links) : ?>
                <p><?php _e('No links found. Add your first link!', 'linkblog'); ?></p>
            <?php else : ?>
                <?php foreach ($grouped_links as $category_name => $category_links) : ?>
                    <div class="lb-category-section">
                        <h2 class="lb-category-heading"><?php echo esc_html($category_name); ?></h2>

                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th style="width: 25%;"><?php _e('Title', 'linkblog'); ?></th>
                                    <th style="width: 25%;"><?php _e('URL', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php _e('Status', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php _e('Published Date', 'linkblog'); ?></th>
                                    <th style="width: 10%;"><?php _e('Date', 'linkblog'); ?></th>
                                    <th style="width: 20%;"><?php _e('Actions', 'linkblog'); ?></th>
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
                                                <span class="lb-status-badge lb-status-published">✓ <?php _e('Published', 'linkblog'); ?></span>
                                            <?php elseif ($publish_status === 'draft') : ?>
                                                <span class="lb-status-badge lb-status-draft">📝 <?php _e('Draft', 'linkblog'); ?></span>
                                            <?php else : ?>
                                                <span class="lb-status-badge lb-status-unpublished"><?php _e('Unpublished', 'linkblog'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($published_date) : ?>
                                                <?php echo esc_html(mysql2date('Y-m-d', $published_date)); ?>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo get_the_date('Y-m-d', $link->ID); ?></td>
                                        <td>
                                            <?php if ($publish_status === 'unpublished') : ?>
                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID); ?>"><?php _e('Publish', 'linkblog'); ?></a> |
                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=draft_link&link_id=' . $link->ID), 'draft_link_' . $link->ID); ?>"><?php _e('Save as Draft', 'linkblog'); ?></a> |
                                            <?php elseif ($publish_status === 'published') : ?>
                                                <a href="<?php echo get_permalink($published_post_id); ?>" target="_blank"><?php _e('View Post', 'linkblog'); ?></a> |
                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to unpublish this link?', 'linkblog'); ?>');"><?php _e('Unpublish', 'linkblog'); ?></a> |
                                            <?php elseif ($publish_status === 'draft') : ?>
                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID); ?>"><?php _e('Publish', 'linkblog'); ?></a> |
                                                <a href="<?php echo get_edit_post_link($published_post_id); ?>" target="_blank"><?php _e('View Draft', 'linkblog'); ?></a> |
                                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to unpublish this link?', 'linkblog'); ?>');"><?php _e('Unpublish', 'linkblog'); ?></a> |
                                            <?php endif; ?>
                                            <a href="<?php echo get_edit_post_link($link->ID); ?>"><?php _e('Edit', 'linkblog'); ?></a> |
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=delete&link_id=' . $link->ID), 'delete_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this link?', 'linkblog'); ?>');"><?php _e('Delete', 'linkblog'); ?></a>
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
}
