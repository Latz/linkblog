<?php

declare(strict_types=1);

/**
 * Add Link page
 */
function linkblog_add_link_page() {
    $message = '';
    $error = '';

    // Handle form submission
    if (isset($_POST['linkblog_add_submit']) && wp_verify_nonce($_POST['linkblog_add_nonce'], 'linkblog_add_link')) {
        $title = sanitize_text_field($_POST['linkblog_title']);
        $url = esc_url_raw($_POST['linkblog_url']);
        $content = wp_kses_post($_POST['linkblog_content']);
        $categories = isset($_POST['linkblog_categories']) ? array_map('intval', $_POST['linkblog_categories']) : array();
        $tags = sanitize_text_field($_POST['linkblog_tags']);

        if (empty($title)) {
            $error = __('Title is required.', 'linkblog');
        } else {
            // Create the post
            $post_data = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_type'    => 'linkblog',
                'post_status'  => 'publish',
            );

            $post_id = wp_insert_post($post_data);

            if ($post_id) {
                // Save URL
                if (!empty($url)) {
                    update_post_meta($post_id, '_linkblog_url', $url);
                }

                // Set categories
                if (!empty($categories)) {
                    wp_set_object_terms($post_id, $categories, 'linkblog_category');
                }

                // Set tags
                if (!empty($tags)) {
                    $tag_names = array_map('trim', explode(',', $tags));
                    wp_set_object_terms($post_id, $tag_names, 'linkblog_tag');
                }

                $message = __('Link added successfully!', 'linkblog');

                // Clear form
                $_POST = array();
            } else {
                $error = __('Failed to add link.', 'linkblog');
            }
        }
    }

    // Get all categories
    $all_categories = get_terms(array(
        'taxonomy'   => 'linkblog_category',
        'hide_empty' => false,
    ));
    ?>
    <div class="wrap">
        <h1><?php _e('Add New Link', 'linkblog'); ?></h1>

        <?php if ($message) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error) : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('linkblog_add_link', 'linkblog_add_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="linkblog_title"><?php _e('Title', 'linkblog'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="linkblog_title" id="linkblog_title" class="regular-text" value="<?php echo isset($_POST['linkblog_title']) ? esc_attr($_POST['linkblog_title']) : ''; ?>" required>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_url"><?php _e('URL', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <input type="url" name="linkblog_url" id="linkblog_url" class="regular-text" value="<?php echo isset($_POST['linkblog_url']) ? esc_attr($_POST['linkblog_url']) : ''; ?>" placeholder="https://example.com">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_content"><?php _e('Text/Description', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <?php
                        $content = isset($_POST['linkblog_content']) ? $_POST['linkblog_content'] : '';
                        wp_editor($content, 'linkblog_content', array(
                            'textarea_name' => 'linkblog_content',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                        ));
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_categories"><?php _e('Categories', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <?php if (!empty($all_categories)) : ?>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                                <?php foreach ($all_categories as $category) : ?>
                                    <label style="display: block; margin-bottom: 5px;">
                                        <input type="checkbox" name="linkblog_categories[]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo isset($_POST['linkblog_categories']) && in_array($category->term_id, $_POST['linkblog_categories']) ? 'checked' : ''; ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p><?php _e('No categories available. Create categories first in LinkBlog > Categories.', 'linkblog'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_tags"><?php _e('Tags', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="linkblog_tags" id="linkblog_tags" class="regular-text" value="<?php echo isset($_POST['linkblog_tags']) ? esc_attr($_POST['linkblog_tags']) : ''; ?>" placeholder="<?php _e('Separate tags with commas', 'linkblog'); ?>">
                        <p class="description"><?php _e('Separate multiple tags with commas (e.g., tag1, tag2, tag3)', 'linkblog'); ?></p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="linkblog_add_submit" id="submit" class="button button-primary" value="<?php _e('Add Link', 'linkblog'); ?>">
                <a href="<?php echo admin_url('admin.php?page=linkblog-admin'); ?>" class="button"><?php _e('Cancel', 'linkblog'); ?></a>
            </p>
        </form>
    </div>
    <?php
}
