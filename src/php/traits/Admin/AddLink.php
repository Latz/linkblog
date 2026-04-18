<?php

declare(strict_types=1);

trait LinkBlog_Admin_AddLink {

    public function addLinkPage(): void {
        [$message, $error] = $this->processAddLinkSubmission();

        // Pre-process POST values for form repopulation (nonce verified in processAddLinkSubmission).
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_title   = isset($_POST['linkblog_title'])   ? sanitize_text_field(wp_unslash($_POST['linkblog_title']))   : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_url     = isset($_POST['linkblog_url'])     ? esc_url_raw(wp_unslash($_POST['linkblog_url']))             : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_content = isset($_POST['linkblog_content']) ? wp_kses_post(wp_unslash($_POST['linkblog_content']))        : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_tags    = isset($_POST['linkblog_tags'])    ? sanitize_text_field(wp_unslash($_POST['linkblog_tags']))    : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_cats    = isset($_POST['linkblog_categories']) ? array_map('intval', $_POST['linkblog_categories']) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Get all categories
        $all_categories = get_terms(array(
            'taxonomy'   => 'linkblog_category',
            'hide_empty' => false,
        ));
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Add New Link', 'linkblog'); ?></h1>

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
                            <label for="linkblog_title"><?php esc_html_e('Title', 'linkblog'); ?> <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" name="linkblog_title" id="linkblog_title" class="regular-text" value="<?php echo esc_attr($current_title); ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkblog_url"><?php esc_html_e('URL', 'linkblog'); ?></label>
                        </th>
                        <td>
                            <input type="url" name="linkblog_url" id="linkblog_url" class="regular-text" value="<?php echo esc_attr($current_url); ?>" placeholder="https://example.com">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkblog_content"><?php esc_html_e('Text/Description', 'linkblog'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_editor($current_content, 'linkblog_content', array(
                                'textarea_name' => 'linkblog_content',
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                            ));
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Categories', 'linkblog'); ?></label>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php esc_html_e('Categories', 'linkblog'); ?></legend>
                                <?php if (!empty($all_categories)) : ?>
                                    <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                                        <?php foreach ($all_categories as $category) : ?>
                                            <label style="display: block; margin-bottom: 5px;">
                                                <input type="checkbox" name="linkblog_categories[]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array((int) $category->term_id, $current_cats, true) ? 'checked' : ''; ?>>
                                                <?php echo esc_html($category->name); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <p><?php esc_html_e('No categories available. Create categories first in LinkBlog > Categories.', 'linkblog'); ?></p>
                                <?php endif; ?>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkblog_tags"><?php esc_html_e('Tags', 'linkblog'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="linkblog_tags" id="linkblog_tags" class="regular-text" value="<?php echo esc_attr($current_tags); ?>" placeholder="<?php esc_attr_e('Separate tags with commas', 'linkblog'); ?>">
                            <p class="description"><?php esc_html_e('Separate multiple tags with commas (e.g., tag1, tag2, tag3)', 'linkblog'); ?></p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="linkblog_add_submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Add Link', 'linkblog'); ?>">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=linkblog-admin')); ?>" class="button"><?php esc_html_e('Cancel', 'linkblog'); ?></a>
                </p>
            </form>
        </div>
        <?php
    }

    private function processAddLinkSubmission(): array {
        if (!isset($_POST['linkblog_add_submit'])) {
            return ['', ''];
        }

        $nonce = isset($_POST['linkblog_add_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkblog_add_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'linkblog_add_link')) {
            return ['', __('Security check failed.', 'linkblog')];
        }

        $input = $this->validateAddLinkInput();
        if ($input['error'] !== '') {
            return ['', $input['error']];
        }

        return $this->insertNewLink($input);
    }

    private function validateAddLinkInput(): array {
        $nonce = isset($_POST['linkblog_add_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkblog_add_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'linkblog_add_link')) {
            return ['title' => '', 'url' => '', 'content' => '', 'categories' => [], 'tags' => '', 'error' => ''];
        }

        $title      = isset($_POST['linkblog_title'])   ? sanitize_text_field(wp_unslash($_POST['linkblog_title']))   : '';
        $url        = isset($_POST['linkblog_url'])     ? esc_url_raw(wp_unslash($_POST['linkblog_url']))             : '';
        $content    = isset($_POST['linkblog_content']) ? wp_kses_post(wp_unslash($_POST['linkblog_content']))        : '';
        $categories = isset($_POST['linkblog_categories']) ? array_map('intval', $_POST['linkblog_categories']) : array();
        $tags       = isset($_POST['linkblog_tags'])    ? sanitize_text_field(wp_unslash($_POST['linkblog_tags']))    : '';

        $error = empty($title) ? __('Title is required.', 'linkblog') : '';
        return compact('title', 'url', 'content', 'categories', 'tags', 'error');
    }

    private function insertNewLink(array $input): array {
        $post_id = wp_insert_post(array(
            'post_title'   => $input['title'],
            'post_content' => $input['content'],
            'post_type'    => 'linkblog',
            'post_status'  => 'publish',
        ));

        if (!$post_id) {
            return ['', __('Failed to add link.', 'linkblog')];
        }

        if (!empty($input['url'])) {
            update_post_meta($post_id, '_linkblog_url', $input['url']);
        }
        if (!empty($input['categories'])) {
            wp_set_object_terms($post_id, $input['categories'], 'linkblog_category');
        }
        if (!empty($input['tags'])) {
            wp_set_object_terms($post_id, array_map('trim', explode(',', $input['tags'])), 'linkblog_tag');
        }

        $_POST = array();
        return [__('Link added successfully!', 'linkblog'), ''];
    }
}
