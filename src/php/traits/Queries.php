<?php

declare(strict_types=1);

trait LinkDigest_Queries {

    public function getPublishStatistics(): array {
        // Static prevents repeated DB hits within a single request (e.g., widget + dashboard page).
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        // Transient caches across requests; invalidated by markLinksAsPublished() and unpublishLink().
        $cached = get_transient('linkdigest_publish_stats');
        if ($cached !== false) {
            return $cache = $cached;
        }

        $counts = wp_count_posts('linkdigest');
        $total_links = (int) ($counts->publish ?? 0);

        // posts_per_page=1 + no_found_rows=false: skip fetching rows, use found_posts for the count.
        $q_published = new \WP_Query([
            'post_type'                  => 'linkdigest',
            'posts_per_page'             => 1,
            'fields'                     => 'ids',
            'no_found_rows'              => false,
            'update_post_meta_cache'     => false,
            'update_post_term_cache'     => false,
            'meta_query'                 => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => '_linkdigest_publish_status',
                    'value'   => 'published',
                    'compare' => '='
                ]
            ]
        ]);
        $published_links = (int) $q_published->found_posts;

        $q_draft = new \WP_Query([
            'post_type'                  => 'linkdigest',
            'posts_per_page'             => 1,
            'fields'                     => 'ids',
            'no_found_rows'              => false,
            'update_post_meta_cache'     => false,
            'update_post_term_cache'     => false,
            'meta_query'                 => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => '_linkdigest_publish_status',
                    'value'   => 'draft',
                    'compare' => '='
                ]
            ]
        ]);
        $draft_links = (int) $q_draft->found_posts;

        $cache = [
            'total_links'       => $total_links,
            'published_links'   => $published_links,
            'draft_links'       => $draft_links,
            'unpublished_links' => $total_links - $published_links - $draft_links
        ];
        set_transient('linkdigest_publish_stats', $cache, 300);
        return $cache;
    }

    public function getLinksGroupedByCategory(): array {
        $all_links = get_posts([
            'post_type'                  => 'linkdigest',
            'posts_per_page'             => -1,
            'orderby'                    => 'date',
            'order'                      => 'DESC',
            'update_post_term_cache'     => false,
        ]);
        if (empty($all_links)) {
            return [];
        }

        $link_ids = wp_list_pluck($all_links, 'ID');
        // One batch query primes the term cache; every get_the_terms() call below becomes a cache hit.
        update_object_term_cache($link_ids, 'linkdigest');

        $grouped = [];
        $uncategorized_key = __('Uncategorized', 'linkdigest');
        foreach ($all_links as $link) {
            $cats = get_the_terms($link->ID, 'linkdigest_category');
            $group_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : $uncategorized_key;
            $grouped[$group_name][] = $link;
        }
        return $grouped;
    }

    public function unpublishLink(int $link_id): array {
        // Get published post ID
        $published_post_id = get_post_meta($link_id, '_linkdigest_published_post_id', true);

        if (!$published_post_id) {
            return array(
                'success' => false,
                'message' => __('This link has not been published.', 'linkdigest')
            );
        }

        // Move blog post to trash
        $trashed = wp_trash_post($published_post_id);

        if (!$trashed) {
            return array(
                'success' => false,
                'message' => __('Failed to unpublish link.', 'linkdigest')
            );
        }

        // Reset meta fields
        delete_post_meta($link_id, '_linkdigest_published_post_id');
        delete_post_meta($link_id, '_linkdigest_publish_status');
        delete_post_meta($link_id, '_linkdigest_published_date');
        delete_transient('linkdigest_publish_stats');

        return array(
            'success' => true,
            'message' => __('Link unpublished successfully.', 'linkdigest')
        );
    }
}
