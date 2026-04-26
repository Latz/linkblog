<?php

declare(strict_types=1);

trait LinkDigest_Publishing {

    public function validateLinkForPublish(int $link_id): ?array {
        if (!current_user_can('publish_posts')) {
            return array('success' => false, 'post_id' => 0, 'message' => __('You do not have permission to publish posts.', 'linkdigest'), 'error_code' => 'no_permission');
        }
        $link = get_post($link_id);
        if (!$link || $link->post_type !== 'linkdigest') {
            return array('success' => false, 'post_id' => 0, 'message' => __('Invalid link ID.', 'linkdigest'), 'error_code' => 'invalid_link');
        }
        return $this->validateLinkState($link, $link_id);
    }

    private function validateLinkState(\WP_Post $link, int $link_id): ?array {
        if (empty($link->post_title)) {
            return array('success' => false, 'post_id' => 0, 'message' => __('Link must have a title to publish.', 'linkdigest'), 'error_code' => 'missing_title');
        }
        $published_post_id = get_post_meta($link_id, '_linkdigest_published_post_id', true);
        // get_post() check: re-publish is allowed when the blog post was manually deleted.
        if ($published_post_id && get_post($published_post_id)) {
            return array('success' => false, 'post_id' => 0, 'message' => __('This link has already been published.', 'linkdigest'), 'error_code' => 'already_published');
        }
        return null;
    }

    public function buildPostContent(string $title, int $link_id, string $url, string $description): string {
        $post_content = '<h2>' . esc_html($title) . '</h2>';
        if (!empty($description)) {
            $post_content .= "\n\n" . wp_kses_post($description);
        }
        if (!empty($url)) {
            $post_content .= "\n\n" . '<p>Read more: <a href="' . esc_url($url) . '">' . esc_html($url) . '</a></p>';
        }
        // Allows themes/plugins to override or extend the generated post HTML.
        return apply_filters('linkdigest_blog_post_content', $post_content, $link_id, $url, $description);
    }

    public function mapTaxonomies(int $post_id, int $link_id): void {
        $linkdigest_categories = get_the_terms($link_id, 'linkdigest_category');
        if ($linkdigest_categories && !is_wp_error($linkdigest_categories)) {
            $category_ids = $this->resolveWpCategoryIds($linkdigest_categories);
            if (!empty($category_ids)) {
                wp_set_post_categories($post_id, $category_ids);
            }
        }

        $linkdigest_tags = get_the_terms($link_id, 'linkdigest_tag');
        if ($linkdigest_tags && !is_wp_error($linkdigest_tags)) {
            wp_set_post_tags($post_id, wp_list_pluck($linkdigest_tags, 'name'));
        }
    }

    private function resolveWpCategoryIds(array $linkdigest_categories): array {
        $category_ids = array();
        foreach ($linkdigest_categories as $linkdigest_cat) {
            $existing_cat = get_category_by_slug($linkdigest_cat->slug);
            if ($existing_cat) {
                $category_ids[] = $existing_cat->term_id;
            } else {
                $new_cat = wp_insert_term($linkdigest_cat->name, 'category');
                if (!is_wp_error($new_cat)) {
                    $category_ids[] = $new_cat['term_id'];
                }
            }
        }
        return $category_ids;
    }

    public function createBlogPost(int $link_id, bool $as_draft = false): array {
        $validation_error = $this->validateLinkForPublish($link_id);
        if ($validation_error !== null) {
            return $validation_error;
        }

        $link = get_post($link_id);
        $url = get_post_meta($link_id, '_linkdigest_url', true);
        $post_content = $this->buildPostContent($link->post_title, $link_id, $url, $link->post_content);

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
                'message' => __('Failed to create blog post.', 'linkdigest'),
                'error_code' => 'insert_failed'
            );
        }

        $this->mapTaxonomies($post_id, $link_id);

        update_post_meta($link_id, '_linkdigest_published_post_id', $post_id);
        update_post_meta($link_id, '_linkdigest_publish_status', $as_draft ? 'draft' : 'published');
        update_post_meta($link_id, '_linkdigest_published_date', current_time('mysql'));

        do_action('linkdigest_after_publish', $link_id, $post_id, $as_draft);

        return array(
            'success' => true,
            'post_id' => $post_id,
            'message' => $as_draft
                ? __('Link saved as draft successfully.', 'linkdigest')
                : __('Link published successfully.', 'linkdigest')
        );
    }
}
