<?php

declare(strict_types=1);

/**
 * Add custom meta box for URL field
 */
function linkblogAddMetaBoxes() {
    add_meta_box(
        'linkblog_url',
        __('Link URL', 'linkblog'),
        'linkblogUrlCallback',
        'linkblog',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'linkblogAddMetaBoxes');

/**
 * Meta box callback for URL field
 */
function linkblogUrlCallback($post) {
    wp_nonce_field('linkblog_save_url', 'linkblog_url_nonce');
    $url = get_post_meta($post->ID, '_linkblog_url', true);
    ?>
    <p>
        <label for="linkblog_url"><?php _e('URL:', 'linkblog'); ?></label><br>
        <input type="url" id="linkblog_url" name="linkblog_url" value="<?php echo esc_attr($url); ?>" size="50" placeholder="https://example.com" style="width: 100%;">
    </p>
    <?php
}

/**
 * Save URL meta data
 */
function linkblogSaveUrl($post_id) {
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
add_action('save_post_linkblog', 'linkblogSaveUrl');
