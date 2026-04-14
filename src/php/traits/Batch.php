<?php

declare(strict_types=1);

trait LinkBlog_Batch {

    public function batchPublishLinks(mixed $link_ids, bool $as_draft = false): array {
        $success_count = 0;
        $failed_count = 0;
        $messages = array();

        if (empty($link_ids) || !is_array($link_ids)) {
            return array(
                'success' => 0,
                'failed' => 0,
                'messages' => array(__('No links to publish.', 'linkblog'))
            );
        }

        foreach ($link_ids as $link_id) {
            $result = $this->createBlogPost($link_id, $as_draft);

            if ($result['success']) {
                $success_count++;
            } else {
                $failed_count++;
                $link = get_post($link_id);
                $messages[] = sprintf(
                    __('Failed to publish "%s": %s', 'linkblog'),
                    $link ? $link->post_title : '#' . $link_id,
                    $result['message']
                );
            }
        }

        return array(
            'success' => $success_count,
            'failed' => $failed_count,
            'messages' => $messages
        );
    }

    public function createRoundupPost(mixed $link_ids, string $post_title, bool $as_draft = false): array {
        // Check permissions
        if (!current_user_can('publish_posts')) {
            return array(
                'success' => false,
                'post_id' => 0,
                'message' => __('You do not have permission to publish posts.', 'linkblog'),
                'error_code' => 'no_permission'
            );
        }

        if (empty($link_ids) || !is_array($link_ids)) {
            return array(
                'success' => false,
                'post_id' => 0,
                'message' => __('No links to publish.', 'linkblog'),
                'error_code' => 'no_links'
            );
        }

        if (empty($post_title)) {
            $post_title = sprintf(__('Links Roundup - %s', 'linkblog'), date('F j, Y'));
        }

        // Group links by their primary linkblog_category
        $links_by_category = array(); // slug => ['term' => $term, 'links' => [link_id, ...]]
        $uncategorized_links = array();
        $all_categories = array();
        $all_tags = array();
        $published_count = 0;

        foreach ($link_ids as $link_id) {
            $link = get_post($link_id);
            if (!$link || $link->post_type !== 'linkblog') {
                continue;
            }

            $link_categories = get_the_terms($link_id, 'linkblog_category');
            if ($link_categories && !is_wp_error($link_categories)) {
                $primary = $link_categories[0];
                if (!isset($links_by_category[$primary->slug])) {
                    $links_by_category[$primary->slug] = array('term' => $primary, 'links' => array());
                }
                $links_by_category[$primary->slug]['links'][] = $link_id;
                foreach ($link_categories as $cat) {
                    $all_categories[] = $cat;
                }
            } else {
                $uncategorized_links[] = $link_id;
            }

            $link_tags = get_the_terms($link_id, 'linkblog_tag');
            if ($link_tags && !is_wp_error($link_tags)) {
                foreach ($link_tags as $tag) {
                    $all_tags[] = $tag;
                }
            }

            $published_count++;
        }

        if ($published_count === 0) {
            return array(
                'success' => false,
                'post_id' => 0,
                'message' => __('No valid links to publish.', 'linkblog'),
                'error_code' => 'no_valid_links'
            );
        }

        // Build post content grouped by category
        $post_content = '';

        $render_link_list = function(array $ids) use (&$post_content) {
            $post_content .= "<ul>\n";
            foreach ($ids as $link_id) {
                $link = get_post($link_id);
                $url  = get_post_meta($link_id, '_linkblog_url', true);
                $desc = trim($link->post_content);

                $post_content .= '<li>';
                if (!empty($url)) {
                    $post_content .= '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html($link->post_title) . '</a>';
                } else {
                    $post_content .= esc_html($link->post_title);
                }
                if (!empty($desc)) {
                    $post_content .= '<br>' . wp_kses_post($desc);
                }
                $post_content .= "</li>\n";
            }
            $post_content .= "</ul>\n\n";
        };

        foreach ($links_by_category as $group) {
            $post_content .= '<h2>' . esc_html($group['term']->name) . "</h2>\n\n";
            $render_link_list($group['links']);
        }

        if (!empty($uncategorized_links)) {
            $post_content .= '<h2>' . __('Other', 'linkblog') . "</h2>\n\n";
            $render_link_list($uncategorized_links);
        }

        // Create the roundup post
        $post_data = array(
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => $as_draft ? 'draft' : 'publish',
            'post_type'    => 'post',
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id) || !$post_id) {
            return array(
                'success' => false,
                'post_id' => 0,
                'message' => __('Failed to create roundup post.', 'linkblog'),
                'error_code' => 'insert_failed'
            );
        }

        // Map unique categories
        if (!empty($all_categories)) {
            $unique_categories = array();
            foreach ($all_categories as $cat) {
                $existing_cat = get_category_by_slug($cat->slug);
                if ($existing_cat) {
                    $unique_categories[$existing_cat->term_id] = $existing_cat->term_id;
                } else {
                    $new_cat = wp_insert_term($cat->name, 'category');
                    if (!is_wp_error($new_cat)) {
                        $unique_categories[$new_cat['term_id']] = $new_cat['term_id'];
                    }
                }
            }
            if (!empty($unique_categories)) {
                wp_set_post_categories($post_id, array_values($unique_categories));
            }
        }

        // Map unique tags
        if (!empty($all_tags)) {
            $unique_tag_names = array_unique(wp_list_pluck($all_tags, 'name'));
            wp_set_post_tags($post_id, $unique_tag_names);
        }

        // Mark all links as published (pointing to the same roundup post)
        foreach ($link_ids as $link_id) {
            $link = get_post($link_id);
            if ($link && $link->post_type === 'linkblog') {
                update_post_meta($link_id, '_linkblog_published_post_id', $post_id);
                update_post_meta($link_id, '_linkblog_publish_status', $as_draft ? 'draft' : 'published');
                update_post_meta($link_id, '_linkblog_published_date', current_time('mysql'));
            }
        }

        return array(
            'success' => true,
            'post_id' => $post_id,
            'link_count' => $published_count,
            'message' => sprintf(
                __('Roundup post created successfully with %d link(s).', 'linkblog'),
                $published_count
            )
        );
    }
}
