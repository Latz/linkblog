<?php

declare(strict_types=1);

trait LinkBlog_Queries {

    public function getPublishStatistics(): array {
        $counts = wp_count_posts('linkblog');
        $total_links = (int) ($counts->publish ?? 0);

        // Count published links
        $published_args = array(
            'post_type'      => 'linkblog',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => array(
                array(
                    'key'     => '_linkblog_publish_status',
                    'value'   => 'published',
                    'compare' => '='
                )
            )
        );
        $published_links = count(get_posts($published_args));

        // Count draft links
        $draft_args = array(
            'post_type'      => 'linkblog',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => array(
                array(
                    'key'     => '_linkblog_publish_status',
                    'value'   => 'draft',
                    'compare' => '='
                )
            )
        );
        $draft_links = count(get_posts($draft_args));

        $unpublished_links = $total_links - $published_links - $draft_links;

        return array(
            'total_links'       => (int) $total_links,
            'published_links'   => (int) $published_links,
            'draft_links'       => (int) $draft_links,
            'unpublished_links' => (int) $unpublished_links
        );
    }

    public function getLinksGroupedByCategory(): array {
        // Get all categories
        $categories = get_terms(array(
            'taxonomy'   => 'linkblog_category',
            'hide_empty' => false,
        ));

        $grouped_links = array();

        // Get links for each category
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $category_links = get_posts(array(
                    'post_type'      => 'linkblog',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'linkblog_category',
                            'field'    => 'term_id',
                            'terms'    => $category->term_id,
                        ),
                    ),
                ));

                if (!empty($category_links)) {
                    $grouped_links[$category->name] = $category_links;
                }
            }
        }

        // Get uncategorized links
        $uncategorized_links = get_posts(array(
            'post_type'      => 'linkblog',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'linkblog_category',
                    'operator' => 'NOT EXISTS',
                ),
            ),
        ));

        if (!empty($uncategorized_links)) {
            $grouped_links[__('Uncategorized', 'linkblog')] = $uncategorized_links;
        }

        return $grouped_links;
    }

    public function unpublishLink(int $link_id): array {
        // Get published post ID
        $published_post_id = get_post_meta($link_id, '_linkblog_published_post_id', true);

        if (!$published_post_id) {
            return array(
                'success' => false,
                'message' => __('This link has not been published.', 'linkblog')
            );
        }

        // Move blog post to trash
        $trashed = wp_trash_post($published_post_id);

        if (!$trashed) {
            return array(
                'success' => false,
                'message' => __('Failed to unpublish link.', 'linkblog')
            );
        }

        // Reset meta fields
        delete_post_meta($link_id, '_linkblog_published_post_id');
        delete_post_meta($link_id, '_linkblog_publish_status');
        delete_post_meta($link_id, '_linkblog_published_date');

        return array(
            'success' => true,
            'message' => __('Link unpublished successfully.', 'linkblog')
        );
    }
}
