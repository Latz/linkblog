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

        // wp_count_posts reads the indexed post_status column — no meta joins needed.
        $counts          = wp_count_posts('linkdigest');
        $published_links = (int) ($counts->linkdigest_published ?? 0);
        $draft_links     = (int) ($counts->linkdigest_draft     ?? 0);
        $pending_links   = (int) ($counts->linkdigest_pending   ?? 0);
        $total_links     = $published_links + $draft_links + $pending_links;

        $cache = [
            'total_links'       => $total_links,
            'published_links'   => $published_links,
            'draft_links'       => $draft_links,
            'unpublished_links' => $pending_links,
        ];
        set_transient('linkdigest_publish_stats', $cache, 300);
        return $cache;
    }

    public function getLinksGroupedByCategory(): array {
        $all_links = get_posts([
            'post_type'              => 'linkdigest',
            'post_status'            => ['linkdigest_pending', 'linkdigest_published', 'linkdigest_draft'],
            'posts_per_page'         => -1,
            'orderby'                => 'date',
            'order'                  => 'DESC',
            'update_post_term_cache' => false,
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

    public function maybeRunMigration(): void {
        if (get_option('linkdigest_schema_version') === '2') {
            return;
        }
        global $wpdb;

        // Bulk-migrate existing 'publish'-status linkdigest posts to custom statuses.
        // Three SQL UPDATEs are far faster than iterating with wp_update_post() for large sites.
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm
                ON pm.post_id = p.ID
               AND pm.meta_key = '_linkdigest_publish_status'
               AND pm.meta_value = 'published'
             SET p.post_status = 'linkdigest_published'
             WHERE p.post_type = %s AND p.post_status = 'publish'",
            'linkdigest'
        ));

        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm
                ON pm.post_id = p.ID
               AND pm.meta_key = '_linkdigest_publish_status'
               AND pm.meta_value = 'draft'
             SET p.post_status = 'linkdigest_draft'
             WHERE p.post_type = %s AND p.post_status = 'publish'",
            'linkdigest'
        ));

        // All remaining 'publish' linkdigest posts have no status meta → they are pending.
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->posts}
             SET post_status = 'linkdigest_pending'
             WHERE post_type = %s AND post_status = 'publish'",
            'linkdigest'
        ));

        delete_transient('linkdigest_publish_stats');
        update_option('linkdigest_schema_version', '2');
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

        // Reset post status and meta fields
        wp_update_post(['ID' => $link_id, 'post_status' => 'linkdigest_pending']);
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
