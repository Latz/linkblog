<?php

declare(strict_types=1);

trait LinkBlog_MetaBoxes {

    public function addMetaBoxes(): void {
        add_meta_box(
            'linkblog_url',
            __('Link URL', 'linkblog'),
            [$this, 'urlMetaBoxCallback'],
            'linkblog',
            'normal',
            'high'
        );
    }

    public function urlMetaBoxCallback(\WP_Post $post): void {
        wp_nonce_field('linkblog_save_url', 'linkblog_url_nonce');
        $url = get_post_meta($post->ID, '_linkblog_url', true);
        ?>
        <p>
            <label for="linkblog_url_meta"><?php _e('URL:', 'linkblog'); ?></label><br>
            <input type="url" id="linkblog_url_meta" name="linkblog_url" value="<?php echo esc_attr($url); ?>" size="50" placeholder="https://example.com" style="width: 100%;">
        </p>
        <?php
    }

    public function saveUrl(int $post_id): void {
        // Check nonce
        if (!isset($_POST['linkblog_url_nonce']) || !wp_verify_nonce($_POST['linkblog_url_nonce'], 'linkblog_save_url')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save URL
        if (isset($_POST['linkblog_url'])) {
            $url = esc_url_raw($_POST['linkblog_url']);
            update_post_meta($post_id, '_linkblog_url', $url);
        }
    }
}
