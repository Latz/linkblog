<?php

declare(strict_types=1);

/**
 * Create a WordPress blog post from a linkblog entry
 *
 * @param int $link_id The linkblog post ID
 * @param bool $as_draft Whether to create as draft (default: false)
 * @return array Result with success, post_id, and message
 */
function linkblogValidateLinkForPublish($link_id) {
    if (!current_user_can('publish_posts')) {
        return array('success' => false, 'post_id' => 0, 'message' => __('You do not have permission to publish posts.', 'linkblog'), 'error_code' => 'no_permission');
    }
    $link = get_post($link_id);
    if (!$link || $link->post_type !== 'linkblog') {
        return array('success' => false, 'post_id' => 0, 'message' => __('Invalid link ID.', 'linkblog'), 'error_code' => 'invalid_link');
    }
    if (empty($link->post_title)) {
        return array('success' => false, 'post_id' => 0, 'message' => __('Link must have a title to publish.', 'linkblog'), 'error_code' => 'missing_title');
    }
    $published_post_id = get_post_meta($link_id, '_linkblog_published_post_id', true);
    if ($published_post_id && get_post($published_post_id)) {
        return array('success' => false, 'post_id' => 0, 'message' => __('This link has already been published.', 'linkblog'), 'error_code' => 'already_published');
    }
    return null;
}

function linkblogBuildPostContent($title, $link_id, $url, $description) {
    $post_content = '<h2>' . esc_html($title) . '</h2>';
    if (!empty($description)) {
        $post_content .= "\n\n" . wp_kses_post($description);
    }
    if (!empty($url)) {
        $post_content .= "\n\n" . '<p>Read more: <a href="' . esc_url($url) . '">' . esc_html($url) . '</a></p>';
    }
    return apply_filters('linkblog_blog_post_content', $post_content, $link_id, $url, $description);
}

function linkblogMapTaxonomies($post_id, $link_id) {
    $linkblog_categories = get_the_terms($link_id, 'linkblog_category');
    if ($linkblog_categories && !is_wp_error($linkblog_categories)) {
        $category_ids = array();
        foreach ($linkblog_categories as $linkblog_cat) {
            $existing_cat = get_category_by_slug($linkblog_cat->slug);
            if ($existing_cat) {
                $category_ids[] = $existing_cat->term_id;
            } else {
                $new_cat = wp_insert_term($linkblog_cat->name, 'category');
                if (!is_wp_error($new_cat)) {
                    $category_ids[] = $new_cat['term_id'];
                }
            }
        }
        if (!empty($category_ids)) {
            wp_set_post_categories($post_id, $category_ids);
        }
    }

    $linkblog_tags = get_the_terms($link_id, 'linkblog_tag');
    if ($linkblog_tags && !is_wp_error($linkblog_tags)) {
        wp_set_post_tags($post_id, wp_list_pluck($linkblog_tags, 'name'));
    }
}

function linkblogCreateBlogPost($link_id, $as_draft = false) {
    $validation_error = linkblogValidateLinkForPublish($link_id);
    if ($validation_error !== null) {
        return $validation_error;
    }

    $link = get_post($link_id);
    $url = get_post_meta($link_id, '_linkblog_url', true);
    $post_content = linkblogBuildPostContent($link->post_title, $link_id, $url, $link->post_content);

    $post_id = wp_insert_post(array(
        'post_title'   => $link->post_title,
        'post_content' => $post_content,
        'post_status'  => $as_draft ? 'draft' : 'publish',
        'post_type'    => 'post',
    ));

    if (is_wp_error($post_id) || !$post_id) {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('Failed to create blog post.', 'linkblog'),
            'error_code' => 'insert_failed'
        );
    }

    linkblogMapTaxonomies($post_id, $link_id);

    update_post_meta($link_id, '_linkblog_published_post_id', $post_id);
    update_post_meta($link_id, '_linkblog_publish_status', $as_draft ? 'draft' : 'published');
    update_post_meta($link_id, '_linkblog_published_date', current_time('mysql'));

    do_action('linkblog_after_publish', $link_id, $post_id, $as_draft);

    return array(
        'success' => true,
        'post_id' => $post_id,
        'message' => $as_draft
            ? __('Link saved as draft successfully.', 'linkblog')
            : __('Link published successfully.', 'linkblog')
    );
}
