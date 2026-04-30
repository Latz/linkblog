<?php

declare(strict_types=1);

trait LinkDigest_Admin_Categories {

    // -------------------------------------------------------------------------
    // Page entry point
    // -------------------------------------------------------------------------

    public function categoriesPage(): void {
        $notice = null;

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( isset( $_POST['linkdigest_add_category'] ) ) {
                $error  = $this->handleAddCategory();
                $notice = $error
                    ? array( 'type' => 'error',   'msg' => $error )
                    : array( 'type' => 'success', 'msg' => __( 'Category added.', 'linkdigest' ) );

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
                    <?php $this->renderCategoryForm(); ?>
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
        $desc   = isset( $_POST['cat_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cat_description'] ) ) : '';
        $result = wp_insert_term( $name, 'linkdigest_category', array( 'description' => $desc ) );
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
        if ( empty( $terms ) ) {
            echo '<p>' . esc_html__( 'No categories yet. Use the form to add your first category.', 'linkdigest' ) . '</p>';
            return;
        }
        ?>
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
                    $count = (int) ( $counts[ $term->term_id ] ?? 0 );
                ?>
                <tr class="lb-cat-row"
                    data-id="<?php echo (int) $term->term_id; ?>"
                    data-name="<?php echo esc_attr( $term->name ); ?>"
                    data-description="<?php echo esc_attr( $term->description ); ?>"
                    data-slug="<?php echo esc_attr( $term->slug ); ?>"
                    data-count="<?php echo (int) $count; ?>">
                    <td class="lb-cat-cell-name"><strong><?php echo esc_html( $term->name ); ?></strong></td>
                    <td class="lb-cat-cell-description lb-cat-desc"><?php echo esc_html( $term->description ); ?></td>
                    <td class="lb-cat-cell-slug"><code><?php echo esc_html( $term->slug ); ?></code></td>
                    <td class="lb-cat-count-col"><?php echo (int) $count; ?></td>
                    <td class="lb-cat-actions">
                        <button type="button" class="button-link lb-cat-edit-btn"><?php esc_html_e( 'Edit', 'linkdigest' ); ?></button>
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
        <?php
    }

    private function renderCategoryForm(): void {
        ?>
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php esc_html_e( 'Add New Category', 'linkdigest' ); ?></h2>
            </div>
            <div class="inside">
                <form method="post" action="">
                    <?php wp_nonce_field( 'linkdigest_add_category', 'linkdigest_cat_nonce' ); ?>
                    <p>
                        <label for="cat_name"><strong><?php esc_html_e( 'Name', 'linkdigest' ); ?> *</strong></label><br>
                        <input type="text" id="cat_name" name="cat_name" class="regular-text" required>
                    </p>
                    <p>
                        <label for="cat_description">
                            <strong><?php esc_html_e( 'Description', 'linkdigest' ); ?></strong>
                            <span class="lb-optional"><?php esc_html_e( '(optional)', 'linkdigest' ); ?></span>
                        </label><br>
                        <textarea id="cat_description" name="cat_description" class="regular-text" rows="3"></textarea>
                    </p>
                    <p>
                        <button type="submit" name="linkdigest_add_category" class="button button-primary">
                            <?php esc_html_e( 'Add Category', 'linkdigest' ); ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    private function renderCategoriesJs( array $terms, array $counts ): void {
        $term_data = array();
        foreach ( $terms as $term ) {
            $term_data[ $term->term_id ] = array(
                'name'  => $term->name,
                'count' => (int) ( $counts[ $term->term_id ] ?? 0 ),
            );
        }
        $js_data = wp_json_encode( array(
            'restUrl' => rest_url( LINKDIGEST_REST_NAMESPACE . '/categories/' ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
            'labels'  => array(
                'edit'            => __( 'Edit', 'linkdigest' ),
                'save'            => __( 'Save', 'linkdigest' ),
                'cancel'          => __( 'Cancel', 'linkdigest' ),
                'saving'          => __( 'Saving…', 'linkdigest' ),
                'saveError'       => __( 'Save failed.', 'linkdigest' ),
                'nameRequired'    => __( 'Name is required.', 'linkdigest' ),
                'descPlaceholder' => __( 'Description (optional)', 'linkdigest' ),
                'slugPlaceholder' => __( 'Leave blank to keep current', 'linkdigest' ),
                'deleteOne'       => __( 'link will become uncategorized.', 'linkdigest' ),
                'deleteMany'      => __( 'links will become uncategorized.', 'linkdigest' ),
            ),
        ) );
        ?>
        <script>
        (function() {
            var cfg = <?php echo $js_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;

            // ── Delete confirmation ───────────────────────────────────────────
            document.querySelectorAll('.lb-cat-delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    var name  = form.dataset.name;
                    var count = parseInt(form.dataset.count, 10);
                    var msg   = count > 0
                        ? "Delete '" + name + "'? " + count + ' ' + (count === 1 ? cfg.labels.deleteOne : cfg.labels.deleteMany)
                        : "Delete '" + name + "'?";
                    if (!confirm(msg)) { e.preventDefault(); }
                });
            });

            // ── Inline edit ───────────────────────────────────────────────────
            document.addEventListener('click', function(e) {
                var editBtn   = e.target.closest('.lb-cat-edit-btn');
                var cancelBtn = e.target.closest('.lb-cat-cancel-btn');
                var saveBtn   = e.target.closest('.lb-cat-save-btn');
                if (editBtn)   { enterEdit(editBtn.closest('tr'));   return; }
                if (cancelBtn) { exitEdit(cancelBtn.closest('tr'), null); return; }
                if (saveBtn)   { saveEdit(saveBtn.closest('tr')); }
            });

            function enterEdit(tr) {
                if (tr.classList.contains('lb-cat-editing')) { return; }
                tr.classList.add('lb-cat-editing');

                var nameInput = mkInput('text',     tr.dataset.name,        '');
                var descInput = mkTextarea(          tr.dataset.description, cfg.labels.descPlaceholder);
                var slugInput = mkInput('text',     tr.dataset.slug,        cfg.labels.slugPlaceholder);
                slugInput.classList.add('lb-cat-inline-slug');

                cell(tr, 'name').innerHTML        = '';  cell(tr, 'name').appendChild(nameInput);
                cell(tr, 'description').innerHTML = '';  cell(tr, 'description').appendChild(descInput);
                cell(tr, 'slug').innerHTML        = '';  cell(tr, 'slug').appendChild(slugInput);

                var actionsCell = tr.querySelector('.lb-cat-actions');
                // Detach delete form so it survives the innerHTML wipe
                var deleteForm = actionsCell.querySelector('.lb-cat-delete-form');
                actionsCell.innerHTML = '';

                var saveBtn   = mkBtn(cfg.labels.save,   'button button-primary lb-cat-save-btn');
                var cancelBtn = mkBtn(cfg.labels.cancel, 'button-link lb-cat-cancel-btn');
                var errSpan   = document.createElement('span');
                errSpan.className = 'lb-cat-inline-error';

                actionsCell.appendChild(saveBtn);
                actionsCell.appendChild(document.createTextNode(' '));
                actionsCell.appendChild(cancelBtn);
                actionsCell.appendChild(errSpan);
                // Re-attach hidden (needed for delete after cancel)
                if (deleteForm) {
                    deleteForm.style.display = 'none';
                    actionsCell.appendChild(deleteForm);
                }

                nameInput.focus();
            }

            function exitEdit(tr, updated) {
                tr.classList.remove('lb-cat-editing');

                var name = updated ? updated.name        : tr.dataset.name;
                var desc = updated ? updated.description : tr.dataset.description;
                var slug = updated ? updated.slug        : tr.dataset.slug;

                if (updated) {
                    tr.dataset.name        = updated.name;
                    tr.dataset.description = updated.description;
                    tr.dataset.slug        = updated.slug;
                }

                cell(tr, 'name').innerHTML        = '<strong>' + esc(name) + '</strong>';
                cell(tr, 'description').innerHTML = esc(desc);
                cell(tr, 'slug').innerHTML        = '<code>' + esc(slug) + '</code>';

                var actionsCell = tr.querySelector('.lb-cat-actions');
                var deleteForm  = actionsCell.querySelector('.lb-cat-delete-form');
                if (updated && deleteForm) { deleteForm.dataset.name = updated.name; }
                actionsCell.innerHTML = '';

                var editBtn = mkBtn(cfg.labels.edit, 'button-link lb-cat-edit-btn');
                actionsCell.appendChild(editBtn);
                actionsCell.appendChild(document.createTextNode(' | '));
                if (deleteForm) {
                    deleteForm.style.display = 'inline';
                    actionsCell.appendChild(deleteForm);
                }
            }

            async function saveEdit(tr) {
                var saveBtn = tr.querySelector('.lb-cat-save-btn');
                var errSpan = tr.querySelector('.lb-cat-inline-error');
                saveBtn.disabled    = true;
                saveBtn.textContent = cfg.labels.saving;
                errSpan.textContent = '';

                var id   = parseInt(tr.dataset.id, 10);
                var name = cell(tr, 'name').querySelector('input').value.trim();
                var desc = cell(tr, 'description').querySelector('textarea').value;
                var slug = cell(tr, 'slug').querySelector('input').value.trim();

                if (!name) {
                    errSpan.textContent = cfg.labels.nameRequired;
                    saveBtn.disabled    = false;
                    saveBtn.textContent = cfg.labels.save;
                    return;
                }

                try {
                    var res  = await fetch(cfg.restUrl + id, {
                        method:      'POST',
                        credentials: 'same-origin',
                        headers:     { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
                        body:        JSON.stringify({ name: name, description: desc, slug: slug }),
                    });
                    var data = await res.json();
                    if (!res.ok) {
                        errSpan.textContent = (data && data.message) ? data.message : cfg.labels.saveError;
                        saveBtn.disabled    = false;
                        saveBtn.textContent = cfg.labels.save;
                        return;
                    }
                    exitEdit(tr, data);
                } catch (_err) {
                    errSpan.textContent = cfg.labels.saveError;
                    saveBtn.disabled    = false;
                    saveBtn.textContent = cfg.labels.save;
                }
            }

            // ── Helpers ───────────────────────────────────────────────────────
            function cell(tr, name) { return tr.querySelector('.lb-cat-cell-' + name); }
            function mkInput(type, value, placeholder) {
                var el = document.createElement('input');
                el.type = type; el.value = value || ''; el.placeholder = placeholder;
                el.className = 'lb-cat-inline-input';
                return el;
            }
            function mkTextarea(value, placeholder) {
                var el = document.createElement('textarea');
                el.value = value || ''; el.placeholder = placeholder; el.rows = 2;
                el.className = 'lb-cat-inline-input';
                return el;
            }
            function mkBtn(label, cls) {
                var el = document.createElement('button');
                el.type = 'button'; el.textContent = label; el.className = cls;
                return el;
            }
            function esc(str) {
                return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        })();
        </script>
        <?php
    }
}
