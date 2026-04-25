<?php

declare(strict_types=1);

trait LinkDigest_MetaBoxes {

    public function addMetaBoxes(): void {
        add_meta_box(
            'linkdigest_url',
            __('Link URL', 'LinkDigest'),
            [$this, 'urlMetaBoxCallback'],
            'linkblog',
            'normal',
            'high'
        );
    }

    public function urlMetaBoxCallback(\WP_Post $post): void {
        wp_nonce_field('linkdigest_save_url', 'linkdigest_url_nonce');
        $url = get_post_meta($post->ID, '_linkdigest_url', true);
        ?>
        <p>
            <label for="linkdigest_url_meta"><?php esc_html_e('URL:', 'LinkDigest'); ?></label><br>
            <input type="url" id="linkdigest_url_meta" name="linkdigest_url" value="<?php echo esc_attr($url); ?>" size="50" placeholder="https://example.com" style="width: 100%;">
        </p>
        <?php
    }

    public function saveUrl(int $post_id): void {
        // Check nonce
        if (!isset($_POST['linkdigest_url_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['linkdigest_url_nonce'])), 'linkdigest_save_url')) {
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
        if (isset($_POST['linkdigest_url'])) {
            $url = esc_url_raw(wp_unslash($_POST['linkdigest_url']));
            update_post_meta($post_id, '_linkdigest_url', $url);
        }
    }
}
