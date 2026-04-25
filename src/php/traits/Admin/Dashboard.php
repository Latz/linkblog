<?php

declare(strict_types=1);

trait LinkDigest_Admin_Dashboard {

    public function dashboardWidgetContent(): void {
        // Get statistics
        $stats = $this->getPublishStatistics();

        // Get recent unpublished links
        $recent_unpublished = get_posts(array(
            'post_type'      => 'linkdigest',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                array(
                    'key'     => '_linkdigest_publish_status',
                    'compare' => self::META_COMPARE_NOT_EXISTS,
                ),
                array(
                    'key'     => '_linkdigest_publish_status',
                    'value'   => array('published', 'draft'),
                    'compare' => self::META_COMPARE_NOT_IN,
                )
            )
        ));

        ?>
        <div class="linkdigest-widget-stats" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px;">
            <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: 600; color: #2271b1;"><?php echo esc_html(number_format($stats['total_links'])); ?></div>
                <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php esc_html_e('Total', 'LinkDigest'); ?></div>
            </div>
            <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: 600; color: #00a32a;"><?php echo esc_html(number_format($stats['published_links'])); ?></div>
                <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php esc_html_e('Published', 'LinkDigest'); ?></div>
            </div>
            <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: 600; color: #dba617;"><?php echo esc_html(number_format($stats['unpublished_links'])); ?></div>
                <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php esc_html_e('Unpublished', 'LinkDigest'); ?></div>
            </div>
        </div>

        <?php if (!empty($recent_unpublished)) : ?>
            <div style="margin-bottom: 12px;">
                <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1d2327;"><?php esc_html_e('Recent Unpublished', 'LinkDigest'); ?></h4>
                <ul style="margin: 0; padding: 0; list-style: none;">
                    <?php foreach ($recent_unpublished as $link) :
                        $url = get_post_meta($link->ID, '_linkdigest_url', true);
                    ?>
                        <li style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f1;">
                            <div style="font-weight: 500; font-size: 13px; margin-bottom: 2px;">
                                <?php echo esc_html($link->post_title); ?>
                            </div>
                            <?php if ($url) : ?>
                                <div style="font-size: 12px; color: #646970; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo esc_html($url); ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="text-align: center; padding-top: 8px; border-top: 1px solid #f0f0f1;">
            <a href="<?php echo esc_url(admin_url('admin.php?page=linkdigest-dashboard')); ?>" class="button button-primary">
                <?php esc_html_e('Go to LinkDigest', 'LinkDigest'); ?>
            </a>
        </div>
        <?php
    }

    public function getUnpublishedLinkIds(): array {
        return get_posts( array(
            'post_type'      => 'linkdigest',
            'posts_per_page' => 500,
            'fields'         => 'ids',
            'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                array( 'key' => '_linkdigest_publish_status', 'compare' => self::META_COMPARE_NOT_EXISTS ),
                array( 'key' => '_linkdigest_publish_status', 'value' => array( 'published', 'draft' ), 'compare' => self::META_COMPARE_NOT_IN ),
            ),
        ) );
    }

    public function handleBatchPublishRequest(): ?array {
        if ( ! isset( $_POST['linkdigest_batch_publish'] ) ) {
            return null;
        }
        $nonce = isset( $_POST['linkdigest_batch_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_batch_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_batch_publish' ) ) {
            return null;
        }
        $as_draft = isset( $_POST['publish_as_draft'] ) && wp_unslash( $_POST['publish_as_draft'] ) === '1';
        return $this->batchPublishLinks( $this->getUnpublishedLinkIds(), $as_draft );
    }

    public function handleRoundupRequest(): ?array {
        if ( ! isset( $_POST['linkdigest_create_roundup'] ) ) {
            return null;
        }
        $nonce = isset( $_POST['linkdigest_roundup_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_roundup_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_create_roundup' ) ) {
            return null;
        }
        $roundup_title = isset( $_POST['roundup_title'] ) ? sanitize_text_field( wp_unslash( $_POST['roundup_title'] ) ) : '';
        $as_draft      = isset( $_POST['roundup_as_draft'] ) && wp_unslash( $_POST['roundup_as_draft'] ) === '1';
        return $this->createRoundupPost( $this->getUnpublishedLinkIds(), $roundup_title, $as_draft );
    }

    public function handleQuickAddRequest(): bool {
        if ( ! isset( $_POST['linkdigest_quick_add'] ) ) {
            return false;
        }
        $nonce = isset( $_POST['linkdigest_quick_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_quick_nonce'] ) ) : '';
        $title = isset( $_POST['quick_title'] ) ? sanitize_text_field( wp_unslash( $_POST['quick_title'] ) ) : '';
        $url   = isset( $_POST['quick_url'] )   ? esc_url_raw( wp_unslash( $_POST['quick_url'] ) )           : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_quick_add_link' ) || empty( $title ) ) {
            return false;
        }
        $post_id = wp_insert_post( array(
            'post_title'  => $title,
            'post_type'   => 'linkdigest',
            'post_status' => 'publish',
        ) );
        if ( $post_id && ! empty( $url ) ) {
            update_post_meta( $post_id, '_linkdigest_url', $url );
        }
        return (bool) $post_id;
    }

    public function renderDashboardNotices( ?array $batch_result, ?array $roundup_result ): void {
        if ( $batch_result !== null ) {
            if ( $batch_result['success'] > 0 ) {
                /* translators: 1: number of successfully processed links, 2: optional failure message */
                $failed_msg = $batch_result['failed'] > 0 ? sprintf( __( '%d failed.', 'LinkDigest' ), $batch_result['failed'] ) : '';
                echo '<div class="notice notice-success"><p>';
                /* translators: 1: number of successfully processed links, 2: optional failure message */
                printf( esc_html__( 'Successfully processed %1$d link(s). %2$s', 'LinkDigest' ), (int) $batch_result['success'], esc_html( $failed_msg ) );
                echo '</p></div>';
            }
            if ( ! empty( $batch_result['messages'] ) ) {
                echo '<div class="notice notice-error"><p>' . implode( '<br>', array_map( 'esc_html', $batch_result['messages'] ) ) . '</p></div>';
            }
        }
        if ( $roundup_result !== null ) {
            if ( $roundup_result['success'] ) {
                echo '<div class="notice notice-success"><p>' . esc_html( $roundup_result['message'] );
                echo ' <a href="' . esc_url( get_permalink( $roundup_result['post_id'] ) ) . '" target="_blank">' . esc_html__( 'View Post', 'LinkDigest' ) . ' →</a></p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html( $roundup_result['message'] ) . '</p></div>';
            }
        }
    }

    public function renderUnpublishedLinksBox( array $recent_links ): void {
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php esc_html_e( 'Recent Unpublished Links', 'LinkDigest' ); ?></h2>
            </div>
            <div class="inside" style="margin:0;padding:0;">
                <?php if ( empty( $recent_links ) ) : ?>
                    <p style="padding:12px 16px;margin:0;color:#646970;"><?php esc_html_e( 'No unpublished links at the moment.', 'LinkDigest' ); ?></p>
                <?php else : ?>
                    <ul class="lb-recent-links">
                        <?php foreach ( $recent_links as $link ) :
                            $url             = get_post_meta( $link->ID, '_linkdigest_url', true );
                            $categories_list = get_the_terms( $link->ID, 'linkdigest_category' );
                            $category_name   = $categories_list && ! is_wp_error( $categories_list ) ? $categories_list[0]->name : '';
                        ?>
                            <li class="lb-link-item" data-link-id="<?php echo esc_attr( $link->ID ); ?>">
                                <div class="lb-link-item-header">
                                    <strong class="lb-link-title"><?php echo esc_html( $link->post_title ); ?></strong>
                                    <button class="lb-delete-btn" title="<?php esc_attr_e( 'Delete link', 'LinkDigest' ); ?>" data-link-id="<?php echo (int) $link->ID; ?>"><span class="dashicons dashicons-trash"></span></button>
                                </div>
                                <?php if ( $url ) : ?>
                                    <a href="<?php echo esc_url( $url ); ?>" class="lb-link-url" target="_blank" rel="noopener">
                                        <?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ); ?> ↗
                                    </a>
                                <?php endif; ?>
                                <div class="lb-link-meta">
                                    <?php if ( $category_name ) : ?>
                                        <span><?php echo esc_html( $category_name ); ?></span>
                                    <?php endif; ?>
                                    <span class="lb-date-time" data-timestamp="<?php echo esc_attr( get_the_time( 'U', $link->ID ) ); ?>">
                                        <?php echo esc_html( get_the_date( 'M j, Y', $link->ID ) ); ?> <?php echo esc_html( get_the_time( 'g:i a', $link->ID ) ); ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="padding:8px 12px;border-top:1px solid #f0f0f1;">
                        <a href="<?php echo esc_url( admin_url( self::ADMIN_LINKS_PAGE ) ); ?>" class="button">
                            <?php esc_html_e( 'View All Links', 'LinkDigest' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function renderRecentlyPublishedBox( array $recently_published ): void {
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php esc_html_e( 'Recently Published', 'LinkDigest' ); ?></h2>
            </div>
            <div class="inside" style="margin:0;padding:0;">
                <?php if ( empty( $recently_published ) ) : ?>
                    <p style="padding:12px 16px;margin:0;color:#646970;"><?php esc_html_e( 'No published links yet.', 'LinkDigest' ); ?></p>
                <?php else : ?>
                    <?php $this->renderRecentlyPublishedList( $recently_published ); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function renderPublishBox( int $unpublished_count ): void {
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php esc_html_e( 'Publish Links', 'LinkDigest' ); ?></h2>
            </div>
            <div class="inside">
                <?php if ( $unpublished_count > 0 ) : ?>
                    <?php
                    /* translators: %d is the number of unpublished links */
                    printf( '<p>' . wp_kses( __( 'You have <strong>%d</strong> unpublished link(s) ready to publish.', 'LinkDigest' ), array( 'strong' => array() ) ) . '</p>', (int) $unpublished_count );
                    ?>
                    <form method="post" action="">
                        <?php wp_nonce_field( 'linkdigest_create_roundup', 'linkdigest_roundup_nonce' ); ?>
                        <p>
                            <label for="roundup_title"><strong><?php esc_html_e( 'Post Title', 'LinkDigest' ); ?></strong></label><br>
                            <input type="text" id="roundup_title" name="roundup_title" class="regular-text"
                                value="<?php
                                /* translators: %s is the current date (e.g. "April 15, 2026") */
                                echo esc_attr( sprintf( __( 'Links Roundup - %s', 'LinkDigest' ), gmdate( 'F j, Y' ) ) );
                                ?>">
                        </p>
                        <input type="hidden" name="roundup_as_draft" value="0">
                        <p>
                            <button type="submit" name="linkdigest_create_roundup" class="button button-primary"><?php esc_html_e( 'Publish', 'LinkDigest' ); ?></button>
                            &nbsp;
                            <button type="submit" name="linkdigest_create_roundup" value="1" onclick="this.form.elements['roundup_as_draft'].value='1';" class="button"><?php esc_html_e( 'Save as Draft', 'LinkDigest' ); ?></button>
                        </p>
                    </form>
                <?php else : ?>
                    <p style="color:#646970;"><?php esc_html_e( 'No unpublished links at the moment.', 'LinkDigest' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function renderRecentlyPublishedList( array $recently_published ): void {
        ?>
        <ul class="lb-recent-links">
            <?php foreach ( $recently_published as $link ) :
                $meta = $this->getRecentlyPublishedLinkMetadata( $link );
            ?>
                <li class="lb-link-item">
                    <div class="lb-link-item-header">
                        <strong class="lb-link-title"><?php echo esc_html( $link->post_title ); ?></strong>
                        <?php $this->renderPublishedLinkBadge( $meta['publish_status'], $meta['is_draft'] ); ?>
                    </div>
                    <?php if ( $meta['published_post_id'] ) : ?>
                        <a href="<?php echo esc_url( $meta['is_draft'] ? get_edit_post_link( $meta['published_post_id'] ) : get_permalink( $meta['published_post_id'] ) ); ?>" class="lb-link-url" target="_blank" rel="noopener">
                            <?php echo $meta['is_draft'] ? esc_html__( 'View Draft', 'LinkDigest' ) : esc_html__( 'View Post', 'LinkDigest' ); ?> ↗
                        </a>
                    <?php endif; ?>
                    <div class="lb-link-meta">
                        <?php if ( $meta['category_name'] ) : ?>
                            <span><?php echo esc_html( $meta['category_name'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( $meta['published_date'] ) : ?>
                            <span><?php echo esc_html( mysql2date( 'M j, Y', $meta['published_date'] ) ); ?></span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

    private function renderPublishedLinkBadge( string $publish_status, bool $is_draft ): void {
        if ( 'published' === $publish_status ) {
            echo '<span class="lb-status-badge lb-status-published">' . esc_html__( 'Published', 'LinkDigest' ) . '</span>';
        } elseif ( $is_draft ) {
            echo '<span class="lb-status-badge lb-status-draft">' . esc_html__( 'Draft', 'LinkDigest' ) . '</span>';
        }
    }

    private function getRecentlyPublishedLinkMetadata( \WP_Post $link ): array {
        $publish_status = get_post_meta( $link->ID, '_linkdigest_publish_status', true );
        $categories_list = get_the_terms( $link->ID, 'linkdigest_category' );
        return [
            'published_post_id' => get_post_meta( $link->ID, '_linkdigest_published_post_id', true ),
            'publish_status' => $publish_status,
            'published_date' => get_post_meta( $link->ID, '_linkdigest_published_date', true ),
            'category_name' => $categories_list && ! is_wp_error( $categories_list ) ? $categories_list[0]->name : '',
            'is_draft' => $publish_status === 'draft',
        ];
    }

    public function renderQuickAddBox( bool $quick_add_success ): void {
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php esc_html_e( 'Quick Add', 'LinkDigest' ); ?></h2>
            </div>
            <div class="inside">
                <?php if ( $quick_add_success ) : ?>
                    <div class="notice notice-success inline"><p><?php esc_html_e( 'Link added successfully!', 'LinkDigest' ); ?></p></div>
                <?php endif; ?>
                <form method="post" action="">
                    <?php wp_nonce_field( 'linkdigest_quick_add_link', 'linkdigest_quick_nonce' ); ?>
                    <p>
                        <label for="quick_title"><strong><?php esc_html_e( 'Title', 'LinkDigest' ); ?> *</strong></label><br>
                        <input type="text" id="quick_title" name="quick_title" class="regular-text"
                            placeholder="<?php esc_attr_e( 'Enter link title', 'LinkDigest' ); ?>" required>
                    </p>
                    <p>
                        <label for="quick_url"><strong><?php esc_html_e( 'URL', 'LinkDigest' ); ?></strong></label><br>
                        <input type="url" id="quick_url" name="quick_url" class="regular-text"
                            placeholder="https://example.com">
                    </p>
                    <p>
                        <button type="submit" name="linkdigest_quick_add" class="button button-primary"><?php esc_html_e( 'Add Link', 'LinkDigest' ); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    public function renderDashboardJs(): void {
        $js_data = wp_json_encode( array(
            'restUrl' => rest_url( LINKDIGEST_REST_NAMESPACE . '/links/' ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
            'labels'  => array(
                'delete' => __( 'Delete?', 'LinkDigest' ),
                'yes'    => __( 'Yes', 'LinkDigest' ),
                'cancel' => __( 'Cancel', 'LinkDigest' ),
            ),
        ) );
        ?>
        <script>
        var linkdigestDash = <?php echo $js_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>;

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.lb-date-time').forEach(function(element) {
                const timestamp = Number.parseInt(element.dataset.timestamp);
                if (!timestamp) return;
                const date = new Date(timestamp * 1000);
                element.textContent = date.toLocaleString(navigator.language, {
                    year: 'numeric', month: 'short', day: 'numeric',
                    hour: 'numeric', minute: '2-digit', hour12: true
                });
            });
        });

        document.addEventListener('click', async function(e) {
            if (e.target.closest('.lb-delete-cancel')) {
                const li = e.target.closest('li');
                li.querySelector('.lb-delete-confirm-row').remove();
                li.querySelector('.lb-delete-btn').style.display = '';
                return;
            }

            if (e.target.closest('.lb-delete-confirm-yes')) {
                const btn = e.target.closest('.lb-delete-confirm-yes');
                const li = btn.closest('li');
                btn.disabled = true;
                btn.textContent = '...';
                try {
                    const res = await fetch(linkdigestDash.restUrl + li.dataset.linkId, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: { 'X-WP-Nonce': linkdigestDash.nonce }
                    });
                    if (res.ok || res.status === 204) {
                        li.remove();
                    } else {
                        li.querySelector('.lb-delete-confirm-row').remove();
                        li.querySelector('.lb-delete-btn').style.display = '';
                    }
                } catch (err) {
                    li.querySelector('.lb-delete-confirm-row').remove();
                    li.querySelector('.lb-delete-btn').style.display = '';
                }
                return;
            }

            const btn = e.target.closest('.lb-delete-btn');
            if (!btn) return;
            const li = btn.closest('li');
            if (li.querySelector('.lb-delete-confirm-row')) return;
            btn.style.display = 'none';
            const row = document.createElement('div');
            row.className = 'lb-delete-confirm-row';
            const lbl = document.createElement('span');
            lbl.className = 'lb-delete-confirm-label';
            lbl.textContent = linkdigestDash.labels.delete;
            const yes = document.createElement('button');
            yes.className = 'lb-delete-confirm-yes';
            yes.textContent = linkdigestDash.labels.yes;
            const no = document.createElement('button');
            no.className = 'lb-delete-cancel';
            no.textContent = linkdigestDash.labels.cancel;
            row.append(lbl, yes, no);
            btn.parentElement.appendChild(row);
        });
        </script>
        <?php
    }

    public function dashboardPage(): void {
        $batch_result      = $this->handleBatchPublishRequest();
        $roundup_result    = $this->handleRoundupRequest();
        $quick_add_success = $this->handleQuickAddRequest();

        $publish_stats     = $this->getPublishStatistics();
        $total_links       = $publish_stats['total_links'];
        $published_links   = $publish_stats['published_links'];
        $unpublished_links = $publish_stats['unpublished_links'];
        $total_categories  = (int) get_terms( array( 'taxonomy' => 'linkdigest_category', 'hide_empty' => false, 'fields' => 'count' ) );

        $recent_links = get_posts( array(
            'post_type'      => 'linkdigest',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                array( 'key' => '_linkdigest_publish_status', 'compare' => self::META_COMPARE_NOT_EXISTS ),
                array( 'key' => '_linkdigest_publish_status', 'value' => array( 'published', 'draft' ), 'compare' => self::META_COMPARE_NOT_IN ),
            ),
        ) );

        $recently_published = get_posts( array(
            'post_type'      => 'linkdigest',
            'posts_per_page' => 5,
            'orderby'        => 'meta_value',
            'order'          => 'DESC',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_key'       => '_linkdigest_published_date',
            'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array( 'key' => '_linkdigest_publish_status', 'value' => array( 'published', 'draft' ), 'compare' => 'IN' ),
            ),
        ) );

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'linkdigest', 'LinkDigest' ); ?></h1>

            <?php $this->renderDashboardNotices( $batch_result, $roundup_result ); ?>

            <!-- Statistics -->
            <div class="lb-stats-grid">
                <div class="lb-stat-card">
                    <span class="dashicons dashicons-admin-links lb-stat-icon"></span>
                    <div><span class="lb-stat-value"><?php echo esc_html(number_format( $total_links )); ?></span>
                    <span class="lb-stat-label"><?php esc_html_e( 'Total Links', 'LinkDigest' ); ?></span></div>
                </div>
                <div class="lb-stat-card">
                    <span class="dashicons dashicons-category lb-stat-icon"></span>
                    <div><span class="lb-stat-value"><?php echo esc_html(number_format( $total_categories )); ?></span>
                    <span class="lb-stat-label"><?php esc_html_e( 'Categories', 'LinkDigest' ); ?></span></div>
                </div>
                <div class="lb-stat-card">
                    <span class="dashicons dashicons-yes-alt lb-stat-icon"></span>
                    <div><span class="lb-stat-value"><?php echo esc_html(number_format( $published_links )); ?></span>
                    <span class="lb-stat-label"><?php esc_html_e( 'Published', 'LinkDigest' ); ?></span></div>
                </div>
                <div class="lb-stat-card">
                    <span class="dashicons dashicons-clock lb-stat-icon"></span>
                    <div><span class="lb-stat-value"><?php echo esc_html(number_format( $unpublished_links )); ?></span>
                    <span class="lb-stat-label"><?php esc_html_e( 'Unpublished', 'LinkDigest' ); ?></span></div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container">
                    <?php
                    $this->renderUnpublishedLinksBox( $recent_links );
                    $this->renderRecentlyPublishedBox( $recently_published );
                    ?>
                </div><!-- #postbox-container-1 -->

                <div id="postbox-container-2" class="postbox-container">
                    <?php
                    $this->renderPublishBox( $unpublished_links );
                    $this->renderQuickAddBox( $quick_add_success );
                    ?>
                </div><!-- #postbox-container-2 -->
            </div><!-- .metabox-holder -->
        </div>

        <?php $this->renderDashboardJs(); ?>
        <?php
    }
}
