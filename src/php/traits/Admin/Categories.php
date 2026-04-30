<?php

declare(strict_types=1);

trait LinkDigest_Admin_Categories {

    // -------------------------------------------------------------------------
    // Page entry point
    // -------------------------------------------------------------------------

    public function categoriesPage(): void {
        $notice      = null;
        $edit_term   = null;
        $action      = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        // POST handlers — run before any output so we can mutate state.
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( isset( $_POST['linkdigest_add_category'] ) ) {
                $error  = $this->handleAddCategory();
                $notice = $error
                    ? array( 'type' => 'error',   'msg' => $error )
                    : array( 'type' => 'success', 'msg' => __( 'Category added.', 'linkdigest' ) );

            } elseif ( isset( $_POST['linkdigest_edit_category'] ) ) {
                $error  = $this->handleEditCategory();
                $notice = $error
                    ? array( 'type' => 'error',   'msg' => $error )
                    : array( 'type' => 'success', 'msg' => __( 'Category updated.', 'linkdigest' ) );
                // On success, drop back to the list view.
                if ( ! $error ) {
                    $action = '';
                }

            } elseif ( isset( $_POST['linkdigest_delete_category'] ) ) {
                $deleted = $this->handleDeleteCategory();
                $notice  = array(
                    'type' => $deleted ? 'success' : 'error',
                    'msg'  => $deleted
                        ? __( 'Category deleted.', 'linkdigest' )
                        : __( 'Could not delete category.', 'linkdigest' ),
                );
            }
        }

        // Load edit term for right-column form.
        if ( $action === 'edit' && isset( $_GET['tag_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $edit_term = get_term( (int) $_GET['tag_id'], 'linkdigest_category' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if ( is_wp_error( $edit_term ) ) {
                $edit_term = null;
            }
        }

        $terms  = get_terms( array( 'taxonomy' => 'linkdigest_category', 'hide_empty' => false ) );
        $terms  = is_wp_error( $terms ) ? array() : $terms;
        $counts = $this->getCategoryLinkCounts();

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Link Categories', 'linkdigest' ); ?></h1>

            <?php if ( $notice ) : ?>
                <div class="notice notice-<?php echo esc_attr( $notice['type'] === 'success' ? 'success' : 'error' ); ?> is-dismissible">
                    <p><?php echo esc_html( $notice['msg'] ); ?></p>
                </div>
            <?php endif; ?>

            <div class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container">
                    <?php $this->renderCategoriesTable( $terms, $counts ); ?>
                </div>

                <div id="postbox-container-2" class="postbox-container">
                    <?php $this->renderCategoryForm( $edit_term ); ?>
                </div>
            </div>
        </div>

        <?php $this->renderCategoriesJs( $terms, $counts ); ?>
        <?php
    }

    // -------------------------------------------------------------------------
    // POST handlers
    // -------------------------------------------------------------------------

    private function handleAddCategory(): ?string {
        $nonce = isset( $_POST['linkdigest_cat_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_cat_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_add_category' ) ) {
            return __( 'Security check failed.', 'linkdigest' );
        }
        $name = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
        if ( empty( $name ) ) {
            return __( 'Category name is required.', 'linkdigest' );
        }
        $desc = isset( $_POST['cat_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cat_description'] ) ) : '';
        $result = wp_insert_term( $name, 'linkdigest_category', array( 'description' => $desc ) );
        if ( is_wp_error( $result ) ) {
            return $result->get_error_message();
        }
        delete_transient( 'linkdigest_api_categories_list' );
        return null;
    }

    private function handleEditCategory(): ?string {
        $nonce = isset( $_POST['linkdigest_cat_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_cat_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_edit_category' ) ) {
            return __( 'Security check failed.', 'linkdigest' );
        }
        $term_id = isset( $_POST['cat_term_id'] ) ? (int) $_POST['cat_term_id'] : 0;
        if ( ! $term_id ) {
            return __( 'Invalid category.', 'linkdigest' );
        }
        $name = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
        if ( empty( $name ) ) {
            return __( 'Category name is required.', 'linkdigest' );
        }
        $desc = isset( $_POST['cat_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cat_description'] ) ) : '';
        $slug = isset( $_POST['cat_slug'] ) ? sanitize_title( wp_unslash( $_POST['cat_slug'] ) ) : '';
        $args = array( 'name' => $name, 'description' => $desc );
        if ( ! empty( $slug ) ) {
            $args['slug'] = $slug;
        }
        $result = wp_update_term( $term_id, 'linkdigest_category', $args );
        if ( is_wp_error( $result ) ) {
            return $result->get_error_message();
        }
        delete_transient( 'linkdigest_api_categories_list' );
        return null;
    }

    private function handleDeleteCategory(): bool {
        $nonce = isset( $_POST['linkdigest_cat_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['linkdigest_cat_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'linkdigest_delete_category' ) ) {
            return false;
        }
        $term_id = isset( $_POST['cat_term_id'] ) ? (int) $_POST['cat_term_id'] : 0;
        if ( ! $term_id ) {
            return false;
        }
        $result = wp_delete_term( $term_id, 'linkdigest_category' );
        if ( is_wp_error( $result ) || $result === false ) {
            return false;
        }
        delete_transient( 'linkdigest_api_categories_list' );
        return true;
    }

    // -------------------------------------------------------------------------
    // Queries
    // -------------------------------------------------------------------------

    private function getCategoryLinkCounts(): array {
        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results(
            "SELECT tt.term_id, COUNT(p.ID) AS cnt
             FROM {$wpdb->term_taxonomy} tt
             LEFT JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
             LEFT JOIN {$wpdb->posts} p ON tr.object_id = p.ID
                AND p.post_status IN ('linkdigest_pending','linkdigest_published','linkdigest_draft')
             WHERE tt.taxonomy = 'linkdigest_category'
             GROUP BY tt.term_id",
            ARRAY_A
        );
        if ( ! $rows ) {
            return array();
        }
        return array_column( $rows, 'cnt', 'term_id' );
    }

    // -------------------------------------------------------------------------
    // Rendering
    // -------------------------------------------------------------------------

    private function renderCategoriesTable( array $terms, array $counts ): void {
        $base_url = admin_url( 'admin.php?page=linkdigest-categories' );
        ?>
        <?php if ( empty( $terms ) ) : ?>
            <p><?php esc_html_e( 'No categories yet. Use the form to add your first category.', 'linkdigest' ); ?></p>
        <?php else : ?>
        <table class="wp-list-table widefat striped lb-cat-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Name', 'linkdigest' ); ?></th>
                    <th><?php esc_html_e( 'Description', 'linkdigest' ); ?></th>
                    <th><?php esc_html_e( 'Slug', 'linkdigest' ); ?></th>
                    <th class="lb-cat-count-col"><?php esc_html_e( 'Links', 'linkdigest' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'linkdigest' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $terms as $term ) :
                    $count    = (int) ( $counts[ $term->term_id ] ?? 0 );
                    $edit_url = esc_url( add_query_arg( array( 'action' => 'edit', 'tag_id' => $term->term_id ), $base_url ) );
                ?>
                <tr>
                    <td><strong><?php echo esc_html( $term->name ); ?></strong></td>
                    <td class="lb-cat-desc"><?php echo esc_html( $term->description ); ?></td>
                    <td><code><?php echo esc_html( $term->slug ); ?></code></td>
                    <td class="lb-cat-count-col">
                        <?php echo (int) $count; ?>
                    </td>
                    <td class="lb-cat-actions">
                        <a href="<?php echo $edit_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Edit', 'linkdigest' ); ?></a>
                        &nbsp;|&nbsp;
                        <form method="post" action="" class="lb-cat-delete-form" style="display:inline;"
                              data-name="<?php echo esc_attr( $term->name ); ?>"
                              data-count="<?php echo (int) $count; ?>">
                            <?php wp_nonce_field( 'linkdigest_delete_category', 'linkdigest_cat_nonce' ); ?>
                            <input type="hidden" name="cat_term_id" value="<?php echo (int) $term->term_id; ?>">
                            <button type="submit" name="linkdigest_delete_category" class="button-link lb-cat-delete-btn">
                                <?php esc_html_e( 'Delete', 'linkdigest' ); ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <?php
    }

    private function renderCategoryForm( ?\WP_Term $edit_term ): void {
        $is_edit = $edit_term !== null;
        $base_url = admin_url( 'admin.php?page=linkdigest-categories' );
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle">
                    <?php echo $is_edit ? esc_html__( 'Edit Category', 'linkdigest' ) : esc_html__( 'Add New Category', 'linkdigest' ); ?>
                </h2>
            </div>
            <div class="inside">
                <form method="post" action="<?php echo esc_url( $base_url ); ?>">
                    <?php if ( $is_edit ) : ?>
                        <?php wp_nonce_field( 'linkdigest_edit_category', 'linkdigest_cat_nonce' ); ?>
                        <input type="hidden" name="cat_term_id" value="<?php echo (int) $edit_term->term_id; ?>">
                    <?php else : ?>
                        <?php wp_nonce_field( 'linkdigest_add_category', 'linkdigest_cat_nonce' ); ?>
                    <?php endif; ?>

                    <p>
                        <label for="cat_name"><strong><?php esc_html_e( 'Name', 'linkdigest' ); ?> *</strong></label><br>
                        <input type="text" id="cat_name" name="cat_name" class="regular-text" required
                            value="<?php echo $is_edit ? esc_attr( $edit_term->name ) : ''; ?>">
                    </p>

                    <p>
                        <label for="cat_description">
                            <strong><?php esc_html_e( 'Description', 'linkdigest' ); ?></strong>
                            <span class="lb-optional"><?php esc_html_e( '(optional)', 'linkdigest' ); ?></span>
                        </label><br>
                        <textarea id="cat_description" name="cat_description" class="regular-text" rows="3"><?php echo $is_edit ? esc_textarea( $edit_term->description ) : ''; ?></textarea>
                    </p>

                    <?php if ( $is_edit ) : ?>
                    <p>
                        <label for="cat_slug"><strong><?php esc_html_e( 'Slug', 'linkdigest' ); ?></strong></label><br>
                        <input type="text" id="cat_slug" name="cat_slug" class="regular-text"
                            value="<?php echo esc_attr( $edit_term->slug ); ?>">
                        <span class="description"><?php esc_html_e( 'URL-friendly identifier. Leave blank to keep current.', 'linkdigest' ); ?></span>
                    </p>
                    <?php endif; ?>

                    <p>
                        <?php if ( $is_edit ) : ?>
                            <button type="submit" name="linkdigest_edit_category" class="button button-primary">
                                <?php esc_html_e( 'Save Changes', 'linkdigest' ); ?>
                            </button>
                            &nbsp;
                            <a href="<?php echo esc_url( $base_url ); ?>" class="button">
                                <?php esc_html_e( 'Cancel', 'linkdigest' ); ?>
                            </a>
                        <?php else : ?>
                            <button type="submit" name="linkdigest_add_category" class="button button-primary">
                                <?php esc_html_e( 'Add Category', 'linkdigest' ); ?>
                            </button>
                        <?php endif; ?>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    private function renderCategoriesJs( array $terms, array $counts ): void {
        // Build a JS map of term_id => { name, count } for confirmation messages.
        $term_data = array();
        foreach ( $terms as $term ) {
            $term_data[ $term->term_id ] = array(
                'name'  => $term->name,
                'count' => (int) ( $counts[ $term->term_id ] ?? 0 ),
            );
        }
        ?>
        <script>
        (function() {
            var terms = <?php echo wp_json_encode( $term_data ); ?>;

            document.querySelectorAll('.lb-cat-delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    var name  = form.dataset.name;
                    var count = parseInt(form.dataset.count, 10);
                    var msg   = count > 0
                        ? name + '<?php echo esc_js( __( "' delete? ", 'linkdigest' ) ); ?>' + count + '<?php echo esc_js( _n( ' link will become uncategorized.', ' links will become uncategorized.', 1, 'linkdigest' ) ); ?>'
                        : '<?php echo esc_js( __( "Delete category '", 'linkdigest' ) ); ?>' + name + "'?";
                    if (count > 0) {
                        msg = '<?php echo esc_js( __( "Delete category '", 'linkdigest' ) ); ?>' + name + "'? " + count + ' ' + (count === 1 ? '<?php echo esc_js( __( 'link will become uncategorized.', 'linkdigest' ) ); ?>' : '<?php echo esc_js( __( 'links will become uncategorized.', 'linkdigest' ) ); ?>');
                    } else {
                        msg = '<?php echo esc_js( __( "Delete category '", 'linkdigest' ) ); ?>' + name + "'?";
                    }
                    if (!confirm(msg)) {
                        e.preventDefault();
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
