<?php

declare(strict_types=1);

trait LinkDigest_Admin_AddLink {

    public function addLinkPage(): void {
        [$message, $error] = $this->processAddLinkSubmission();

        // Pre-process POST values for form repopulation (nonce verified in processAddLinkSubmission).
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_title   = isset($_POST['linkdigest_title'])   ? sanitize_text_field(wp_unslash($_POST['linkdigest_title']))   : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_url     = isset($_POST['linkdigest_url'])     ? esc_url_raw(wp_unslash($_POST['linkdigest_url']))             : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_content = isset($_POST['linkdigest_content']) ? wp_kses_post(wp_unslash($_POST['linkdigest_content']))        : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_tags    = isset($_POST['linkdigest_tags'])    ? sanitize_text_field(wp_unslash($_POST['linkdigest_tags']))    : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $current_cats    = isset($_POST['linkdigest_categories']) ? array_map('intval', $_POST['linkdigest_categories']) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Get all categories
        $all_categories = get_terms(array(
            'taxonomy'   => 'linkdigest_category',
            'hide_empty' => false,
        ));
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Add New Link', 'LinkDigest'); ?></h1>

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
                <?php wp_nonce_field('linkdigest_add_link', 'linkdigest_add_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="linkdigest_title"><?php esc_html_e('Title', 'LinkDigest'); ?> <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" name="linkdigest_title" id="linkdigest_title" class="regular-text" value="<?php echo esc_attr($current_title); ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkdigest_url"><?php esc_html_e('URL', 'LinkDigest'); ?></label>
                        </th>
                        <td>
                            <input type="url" name="linkdigest_url" id="linkdigest_url" class="regular-text" value="<?php echo esc_attr($current_url); ?>" placeholder="https://example.com">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkdigest_content"><?php esc_html_e('Text/Description', 'LinkDigest'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_editor($current_content, 'linkdigest_content', array(
                                'textarea_name' => 'linkdigest_content',
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                            ));
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <span><?php esc_html_e('Categories', 'LinkDigest'); ?></span>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php esc_html_e('Categories', 'LinkDigest'); ?></legend>
                                <?php if (!empty($all_categories)) : ?>
                                    <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                                        <?php foreach ($all_categories as $category) : ?>
                                            <label style="display: block; margin-bottom: 5px;">
                                                <input type="checkbox" name="linkdigest_categories[]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array((int) $category->term_id, $current_cats, true) ? 'checked' : ''; ?>>
                                                <?php echo esc_html($category->name); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <p><?php esc_html_e('No categories available. Create categories first in LinkDigest > Categories.', 'LinkDigest'); ?></p>
                                <?php endif; ?>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="linkdigest_tags"><?php esc_html_e('Tags', 'LinkDigest'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="linkdigest_tags" id="linkdigest_tags" class="regular-text" value="<?php echo esc_attr($current_tags); ?>" placeholder="<?php esc_attr_e('Separate tags with commas', 'LinkDigest'); ?>">
                            <p class="description"><?php esc_html_e('Separate multiple tags with commas (e.g., tag1, tag2, tag3)', 'LinkDigest'); ?></p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="linkdigest_add_submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Add Link', 'LinkDigest'); ?>">
                    <a href="<?php echo esc_url(admin_url(self::ADMIN_LINKS_PAGE)); ?>" class="button"><?php esc_html_e('Cancel', 'LinkDigest'); ?></a>
                </p>
            </form>
        </div>
        <?php
    }

    private function processAddLinkSubmission(): array {
        if (!isset($_POST['linkdigest_add_submit'])) {
            return ['', ''];
        }

        $nonce = isset($_POST['linkdigest_add_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkdigest_add_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'linkdigest_add_link')) {
            return ['', __('Security check failed.', 'LinkDigest')];
        }

        $input = $this->validateAddLinkInput();
        return ($input['error'] !== '') ? ['', $input['error']] : $this->insertNewLink($input);
    }

    private function validateAddLinkInput(): array {
        $nonce = isset($_POST['linkdigest_add_nonce']) ? sanitize_text_field(wp_unslash($_POST['linkdigest_add_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'linkdigest_add_link')) {
            return ['title' => '', 'url' => '', 'content' => '', 'categories' => [], 'tags' => '', 'error' => ''];
        }

        $title      = isset($_POST['linkdigest_title'])   ? sanitize_text_field(wp_unslash($_POST['linkdigest_title']))   : '';
        $url        = isset($_POST['linkdigest_url'])     ? esc_url_raw(wp_unslash($_POST['linkdigest_url']))             : '';
        $content    = isset($_POST['linkdigest_content']) ? wp_kses_post(wp_unslash($_POST['linkdigest_content']))        : '';
        $categories = isset($_POST['linkdigest_categories']) ? array_map('intval', $_POST['linkdigest_categories']) : array();
        $tags       = isset($_POST['linkdigest_tags'])    ? sanitize_text_field(wp_unslash($_POST['linkdigest_tags']))    : '';

        $error = empty($title) ? __('Title is required.', 'LinkDigest') : '';
        return compact('title', 'url', 'content', 'categories', 'tags', 'error');
    }

    private function insertNewLink(array $input): array {
        $post_id = wp_insert_post(array(
            'post_title'   => $input['title'],
            'post_content' => $input['content'],
            'post_type'    => 'linkdigest',
            'post_status'  => 'publish',
        ));

        if (!$post_id) {
            return ['', __('Failed to add link.', 'LinkDigest')];
        }

        if (!empty($input['url'])) {
            update_post_meta($post_id, '_linkdigest_url', $input['url']);
        }
        if (!empty($input['categories'])) {
            wp_set_object_terms($post_id, $input['categories'], 'linkdigest_category');
        }
        if (!empty($input['tags'])) {
            wp_set_object_terms($post_id, array_map('trim', explode(',', $input['tags'])), 'linkdigest_tag');
        }

        $_POST = array();
        return [__('Link added successfully!', 'LinkDigest'), ''];
    }
}
