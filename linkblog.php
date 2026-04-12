<?php
/**
 * Plugin Name: LinkBlog
 * Plugin URI: https://example.com/linkblog
 * Description: Save and publish links to your blog
 * Version: 1.0.0
 * Author: Latz
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: linkblog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register LinkBlog custom post type
 */
function linkblog_register_post_type() {
    $labels = array(
        'name'                  => _x('Links', 'Post Type General Name', 'linkblog'),
        'singular_name'         => _x('Link', 'Post Type Singular Name', 'linkblog'),
        'menu_name'             => __('LinkBlog', 'linkblog'),
        'name_admin_bar'        => __('Link', 'linkblog'),
        'archives'              => __('Link Archives', 'linkblog'),
        'attributes'            => __('Link Attributes', 'linkblog'),
        'parent_item_colon'     => __('Parent Link:', 'linkblog'),
        'all_items'             => __('All Links', 'linkblog'),
        'add_new_item'          => __('Add New Link', 'linkblog'),
        'add_new'               => __('Add New', 'linkblog'),
        'new_item'              => __('New Link', 'linkblog'),
        'edit_item'             => __('Edit Link', 'linkblog'),
        'update_item'           => __('Update Link', 'linkblog'),
        'view_item'             => __('View Link', 'linkblog'),
        'view_items'            => __('View Links', 'linkblog'),
        'search_items'          => __('Search Link', 'linkblog'),
        'not_found'             => __('Not found', 'linkblog'),
        'not_found_in_trash'    => __('Not found in Trash', 'linkblog'),
        'featured_image'        => __('Featured Image', 'linkblog'),
        'set_featured_image'    => __('Set featured image', 'linkblog'),
        'remove_featured_image' => __('Remove featured image', 'linkblog'),
        'use_featured_image'    => __('Use as featured image', 'linkblog'),
        'insert_into_item'      => __('Insert into link', 'linkblog'),
        'uploaded_to_this_item' => __('Uploaded to this link', 'linkblog'),
        'items_list'            => __('Links list', 'linkblog'),
        'items_list_navigation' => __('Links list navigation', 'linkblog'),
        'filter_items_list'     => __('Filter links list', 'linkblog'),
    );

    $args = array(
        'label'                 => __('Link', 'linkblog'),
        'description'           => __('Links to publish on blog', 'linkblog'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'custom-fields'),
        'taxonomies'            => array('linkblog_category', 'linkblog_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => false,
        'menu_position'         => 5,
        'menu_icon'             => plugins_url('assets/icon-20x20.png', __FILE__),
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('linkblog', $args);
}
add_action('init', 'linkblog_register_post_type', 0);

/**
 * Register custom taxonomies for LinkBlog
 */
function linkblog_register_taxonomies() {
    // Register Category taxonomy
    $category_labels = array(
        'name'                       => _x('Link Categories', 'Taxonomy General Name', 'linkblog'),
        'singular_name'              => _x('Link Category', 'Taxonomy Singular Name', 'linkblog'),
        'menu_name'                  => __('Categories', 'linkblog'),
        'all_items'                  => __('All Categories', 'linkblog'),
        'parent_item'                => __('Parent Category', 'linkblog'),
        'parent_item_colon'          => __('Parent Category:', 'linkblog'),
        'new_item_name'              => __('New Category Name', 'linkblog'),
        'add_new_item'               => __('Add New Category', 'linkblog'),
        'edit_item'                  => __('Edit Category', 'linkblog'),
        'update_item'                => __('Update Category', 'linkblog'),
        'view_item'                  => __('View Category', 'linkblog'),
        'separate_items_with_commas' => __('Separate categories with commas', 'linkblog'),
        'add_or_remove_items'        => __('Add or remove categories', 'linkblog'),
        'choose_from_most_used'      => __('Choose from the most used', 'linkblog'),
        'popular_items'              => __('Popular Categories', 'linkblog'),
        'search_items'               => __('Search Categories', 'linkblog'),
        'not_found'                  => __('Not Found', 'linkblog'),
        'no_terms'                   => __('No categories', 'linkblog'),
        'items_list'                 => __('Categories list', 'linkblog'),
        'items_list_navigation'      => __('Categories list navigation', 'linkblog'),
    );

    $category_args = array(
        'labels'                     => $category_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('linkblog_category', array('linkblog'), $category_args);

    // Register Tag taxonomy
    $tag_labels = array(
        'name'                       => _x('Link Tags', 'Taxonomy General Name', 'linkblog'),
        'singular_name'              => _x('Link Tag', 'Taxonomy Singular Name', 'linkblog'),
        'menu_name'                  => __('Tags', 'linkblog'),
        'all_items'                  => __('All Tags', 'linkblog'),
        'parent_item'                => __('Parent Tag', 'linkblog'),
        'parent_item_colon'          => __('Parent Tag:', 'linkblog'),
        'new_item_name'              => __('New Tag Name', 'linkblog'),
        'add_new_item'               => __('Add New Tag', 'linkblog'),
        'edit_item'                  => __('Edit Tag', 'linkblog'),
        'update_item'                => __('Update Tag', 'linkblog'),
        'view_item'                  => __('View Tag', 'linkblog'),
        'separate_items_with_commas' => __('Separate tags with commas', 'linkblog'),
        'add_or_remove_items'        => __('Add or remove tags', 'linkblog'),
        'choose_from_most_used'      => __('Choose from the most used', 'linkblog'),
        'popular_items'              => __('Popular Tags', 'linkblog'),
        'search_items'               => __('Search Tags', 'linkblog'),
        'not_found'                  => __('Not Found', 'linkblog'),
        'no_terms'                   => __('No tags', 'linkblog'),
        'items_list'                 => __('Tags list', 'linkblog'),
        'items_list_navigation'      => __('Tags list navigation', 'linkblog'),
    );

    $tag_args = array(
        'labels'                     => $tag_labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('linkblog_tag', array('linkblog'), $tag_args);
}
add_action('init', 'linkblog_register_taxonomies', 0);

/**
 * Add custom meta box for URL field
 */
function linkblog_add_meta_boxes() {
    add_meta_box(
        'linkblog_url',
        __('Link URL', 'linkblog'),
        'linkblog_url_callback',
        'linkblog',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'linkblog_add_meta_boxes');

/**
 * Meta box callback for URL field
 */
function linkblog_url_callback($post) {
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
function linkblog_save_url($post_id) {
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
add_action('save_post_linkblog', 'linkblog_save_url');

/**
 * Create a WordPress blog post from a linkblog entry
 *
 * @param int $link_id The linkblog post ID
 * @param bool $as_draft Whether to create as draft (default: false)
 * @return array Result with success, post_id, and message
 */
function linkblog_create_blog_post($link_id, $as_draft = false) {
    // Check permissions
    if (!current_user_can('publish_posts')) {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('You do not have permission to publish posts.', 'linkblog'),
            'error_code' => 'no_permission'
        );
    }

    // Get link data
    $link = get_post($link_id);
    if (!$link || $link->post_type !== 'linkblog') {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('Invalid link ID.', 'linkblog'),
            'error_code' => 'invalid_link'
        );
    }

    // Validate title (required)
    $title = $link->post_title;
    if (empty($title)) {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('Link must have a title to publish.', 'linkblog'),
            'error_code' => 'missing_title'
        );
    }

    // Check if already published
    $published_post_id = get_post_meta($link_id, '_linkblog_published_post_id', true);
    if ($published_post_id && get_post($published_post_id)) {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('This link has already been published.', 'linkblog'),
            'error_code' => 'already_published'
        );
    }

    // Get link metadata
    $url = get_post_meta($link_id, '_linkblog_url', true);
    $description = $link->post_content;

    // Build post content
    $post_content = '<h2>' . esc_html($title) . '</h2>';

    if (!empty($description)) {
        $post_content .= "\n\n" . wp_kses_post($description);
    }

    if (!empty($url)) {
        $post_content .= "\n\n" . '<p>Read more: <a href="' . esc_url($url) . '">' . esc_html($url) . '</a></p>';
    }

    // Apply filter for customization
    $post_content = apply_filters('linkblog_blog_post_content', $post_content, $link_id, $url, $description);

    // Create the blog post
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $post_content,
        'post_status'  => $as_draft ? 'draft' : 'publish',
        'post_type'    => 'post',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id) || !$post_id) {
        return array(
            'success' => false,
            'post_id' => 0,
            'message' => __('Failed to create blog post.', 'linkblog'),
            'error_code' => 'insert_failed'
        );
    }

    // Map categories from linkblog_category to standard category
    $linkblog_categories = get_the_terms($link_id, 'linkblog_category');
    if ($linkblog_categories && !is_wp_error($linkblog_categories)) {
        $category_ids = array();
        foreach ($linkblog_categories as $linkblog_cat) {
            // Check if category exists in standard categories
            $existing_cat = get_category_by_slug($linkblog_cat->slug);
            if ($existing_cat) {
                $category_ids[] = $existing_cat->term_id;
            } else {
                // Create new category
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

    // Map tags from linkblog_tag to standard tags
    $linkblog_tags = get_the_terms($link_id, 'linkblog_tag');
    if ($linkblog_tags && !is_wp_error($linkblog_tags)) {
        $tag_names = wp_list_pluck($linkblog_tags, 'name');
        wp_set_post_tags($post_id, $tag_names);
    }

    // Update link metadata
    update_post_meta($link_id, '_linkblog_published_post_id', $post_id);
    update_post_meta($link_id, '_linkblog_publish_status', $as_draft ? 'draft' : 'published');
    update_post_meta($link_id, '_linkblog_published_date', current_time('mysql'));

    // Fire action hook
    do_action('linkblog_after_publish', $link_id, $post_id, $as_draft);

    return array(
        'success' => true,
        'post_id' => $post_id,
        'message' => $as_draft
            ? __('Link saved as draft successfully.', 'linkblog')
            : __('Link published successfully.', 'linkblog')
    );
}

/**
 * Batch publish multiple links
 *
 * @param array $link_ids Array of linkblog post IDs
 * @param bool $as_draft Whether to create as drafts (default: false)
 * @return array Summary with success count, failed count, and messages
 */
function linkblog_batch_publish_links($link_ids, $as_draft = false) {
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
        $result = linkblog_create_blog_post($link_id, $as_draft);

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

/**
 * Create a single roundup post containing multiple links
 *
 * @param array $link_ids Array of linkblog post IDs
 * @param string $post_title Title for the roundup post
 * @param bool $as_draft Whether to create as draft (default: false)
 * @return array Result with success, post_id, and message
 */
function linkblog_create_roundup_post($link_ids, $post_title, $as_draft = false) {
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

/**
 * Get publish statistics
 *
 * @return array Counts of total, published, draft, and unpublished links
 */
function linkblog_get_publish_statistics() {
    $total_links = wp_count_posts('linkblog')->publish;

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
        'total_links'       => $total_links,
        'published_links'   => $published_links,
        'draft_links'       => $draft_links,
        'unpublished_links' => $unpublished_links
    );
}

/**
 * Get links grouped by category
 *
 * @return array Links organized by category name
 */
function linkblog_get_links_grouped_by_category() {
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

/**
 * Unpublish a link (move blog post to trash, reset meta)
 *
 * @param int $link_id The linkblog post ID
 * @return array Result with success and message
 */
function linkblog_unpublish_link($link_id) {
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

/**
 * Add admin menu for LinkBlog
 */
function linkblog_admin_menu() {
    add_menu_page(
        __('LinkBlog', 'linkblog'),
        __('LinkBlog', 'linkblog'),
        'read',
        'linkblog-dashboard',
        'linkblog_dashboard_page',
        plugins_url('assets/icon-20x20.png', __FILE__),
        6
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Dashboard', 'linkblog'),
        __('Dashboard', 'linkblog'),
        'read',
        'linkblog-dashboard',
        'linkblog_dashboard_page'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Show Links', 'linkblog'),
        __('All Links', 'linkblog'),
        'read',
        'linkblog-admin',
        'linkblog_show_links_page'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Add Link', 'linkblog'),
        __('Add Link', 'linkblog'),
        'read',
        'linkblog-add',
        'linkblog_add_link_page'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Categories', 'linkblog'),
        __('Categories', 'linkblog'),
        'manage_categories',
        'edit-tags.php?taxonomy=linkblog_category&post_type=linkblog'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Tags', 'linkblog'),
        __('Tags', 'linkblog'),
        'manage_categories',
        'edit-tags.php?taxonomy=linkblog_tag&post_type=linkblog'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Settings', 'linkblog'),
        __('Settings', 'linkblog'),
        'manage_options',
        'linkblog-settings',
        'linkblog_settings_page'
    );

    add_submenu_page(
        'linkblog-dashboard',
        __('Schedule', 'linkblog'),
        __('Schedule', 'linkblog'),
        'manage_options',
        'linkblog-schedule',
        'linkblog_schedule_page'
    );
}
add_action('admin_menu', 'linkblog_admin_menu');

// Keep LinkBlog menu highlighted when on linkblog taxonomy pages.
add_filter('parent_file', function($parent_file) {
    global $pagenow;
    if ($pagenow === 'edit-tags.php') {
        $taxonomy = $_GET['taxonomy'] ?? '';
        if ($taxonomy === 'linkblog_category' || $taxonomy === 'linkblog_tag') {
            return 'linkblog-dashboard';
        }
    }
    return $parent_file;
});

add_filter('submenu_file', function($submenu_file) {
    global $pagenow;
    if ($pagenow === 'edit-tags.php') {
        $taxonomy = $_GET['taxonomy'] ?? '';
        if ($taxonomy === 'linkblog_category') {
            return 'edit-tags.php?taxonomy=linkblog_category&post_type=linkblog';
        }
        if ($taxonomy === 'linkblog_tag') {
            return 'edit-tags.php?taxonomy=linkblog_tag&post_type=linkblog';
        }
    }
    return $submenu_file;
});

/**
 * Settings page
 */
function linkblog_settings_page() {
    // Handle API key generation
    if (isset($_POST['linkblog_generate_api_key']) && wp_verify_nonce($_POST['linkblog_settings_nonce'], 'linkblog_settings')) {
        $api_key = wp_generate_password(32, false);
        update_option('linkblog_api_key', $api_key);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('New API key generated successfully!', 'linkblog') . '</p></div>';
    }

    $api_key = get_option('linkblog_api_key');
    $site_url = get_site_url();
    ?>
    <div class="wrap">
        <h1><?php _e('LinkBlog Settings', 'linkblog'); ?></h1>

        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Chrome Extension Access Data', 'linkblog'); ?></h2>
            <p><?php _e('Use these credentials to connect the LinkBlog Chrome extension to your WordPress site.', 'linkblog'); ?></p>

            <div style="margin: 20px 0;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('API Endpoint:', 'linkblog'); ?>
                </label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input
                        type="text"
                        id="linkblog-api-endpoint"
                        value="<?php echo esc_attr($site_url . '/wp-json/linkblog/v1'); ?>"
                        readonly
                        onclick="this.select();"
                        style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
                    >
                    <button type="button" class="button linkblog-copy-btn" data-clipboard-target="linkblog-api-endpoint">
                        <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                    </button>
                </div>
                <p class="description">
                    <?php _e('Use this URL in the Chrome extension settings.', 'linkblog'); ?>
                    <a href="<?php echo esc_url($site_url . '/wp-json/linkblog/v1'); ?>" target="_blank" style="margin-left: 8px;">
                        <?php _e('View REST API', 'linkblog'); ?> ↗
                    </a>
                </p>
            </div>

            <?php if ($api_key) : ?>
                <div style="margin: 20px 0;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <?php _e('API Key:', 'linkblog'); ?>
                    </label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input
                            type="text"
                            id="linkblog-api-key"
                            value="<?php echo esc_attr($api_key); ?>"
                            readonly
                            onclick="this.select();"
                            style="flex: 1; font-family: monospace; padding: 8px; background: #f0f0f1;"
                        >
                        <button type="button" class="button linkblog-copy-btn" data-clipboard-target="linkblog-api-key">
                            <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                        </button>
                    </div>
                    <p class="description">
                        <?php _e('Click to select and copy this key. Keep it secure!', 'linkblog'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <?php wp_nonce_field('linkblog_settings', 'linkblog_settings_nonce'); ?>
                <button type="submit" name="linkblog_generate_api_key" class="button button-primary">
                    <?php echo $api_key ? __('Generate New API Key', 'linkblog') : __('Generate API Key', 'linkblog'); ?>
                </button>
                <?php if ($api_key) : ?>
                    <p class="description">
                        <?php _e('Warning: Generating a new key will invalidate the old one.', 'linkblog'); ?>
                    </p>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Chrome Extension Setup', 'linkblog'); ?></h2>
            <ol>
                <li><?php _e('Download and install the LinkBlog Chrome extension', 'linkblog'); ?></li>
                <li><?php _e('Click the extension icon and go to Settings', 'linkblog'); ?></li>
                <li><?php _e('Paste your API Endpoint and API Key from above', 'linkblog'); ?></li>
                <li><?php _e('Click Save', 'linkblog'); ?></li>
                <li><?php _e('Now you can save links directly from any webpage!', 'linkblog'); ?></li>
            </ol>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.linkblog-copy-btn').on('click', function() {
            var targetId = $(this).data('clipboard-target');
            var input = document.getElementById(targetId);

            if (input) {
                input.select();
                input.setSelectionRange(0, 99999); // For mobile devices

                try {
                    document.execCommand('copy');

                    // Visual feedback
                    var originalHtml = $(this).html();
                    $(this).html('<span class="dashicons dashicons-yes" style="margin-top: 3px; color: #00a32a;"></span>');

                    setTimeout(function() {
                        $('.linkblog-copy-btn').html(originalHtml);
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }
            }
        });
    });
    </script>
    <?php
}

/**
 * Schedule configuration page
 */
function linkblog_schedule_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Schedule Configuration', 'linkblog'); ?></h1>
        <div id="linkblog-schedule-root"></div>
    </div>
    <?php
}

/**
 * Enqueue dashboard styles and scripts
 */
function linkblog_enqueue_admin_assets($hook) {
    if (strpos($hook, 'linkblog') === false) {
        return;
    }

    wp_enqueue_style('dashicons');
    wp_enqueue_style(
        'linkblog-dashboard',
        plugin_dir_url(__FILE__) . 'dashboard.css',
        array(),
        '1.0.0'
    );

    if (strpos($hook, 'linkblog-schedule') !== false) {
        $asset_file = plugin_dir_path(__FILE__) . 'build/schedule.asset.php';
        $asset = file_exists($asset_file)
            ? require($asset_file)
            : array('dependencies' => array(), 'version' => '1.0.0');

        wp_enqueue_script(
            'linkblog-schedule',
            plugin_dir_url(__FILE__) . 'build/schedule.js',
            $asset['dependencies'],
            $asset['version'],
            true
        );

        if (file_exists(plugin_dir_path(__FILE__) . 'build/schedule.css')) {
            wp_enqueue_style(
                'linkblog-schedule-style',
                plugin_dir_url(__FILE__) . 'build/schedule.css',
                array('wp-components'),
                $asset['version']
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'linkblog_enqueue_admin_assets');

/**
 * Add LinkBlog widget to WordPress dashboard
 */
function linkblog_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'linkblog_dashboard_widget',
        __('LinkBlog Summary', 'linkblog'),
        'linkblog_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'linkblog_add_dashboard_widget');

/**
 * Register REST API endpoints for LinkBlog
 */
function linkblog_register_rest_routes() {
    register_rest_route('linkblog/v1', '/add-link', array(
        'methods' => 'POST',
        'callback' => 'linkblog_rest_add_link',
        'permission_callback' => 'linkblog_rest_permission_check',
        'args' => array(
            'title' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'url' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
            ),
            'content' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'wp_kses_post',
            ),
            'categories' => array(
                'required' => false,
                'type' => 'array',
            ),
            'tags' => array(
                'required' => false,
                'type' => 'string',
            ),
        ),
    ));

    register_rest_route('linkblog/v1', '/categories', array(
        'methods' => 'GET',
        'callback' => 'linkblog_rest_get_categories',
        'permission_callback' => 'linkblog_rest_permission_check',
    ));

    register_rest_route('linkblog/v1', '/links/(?P<id>\d+)', array(
        'methods'             => 'DELETE',
        'callback'            => 'linkblog_rest_delete_link',
        'permission_callback' => function() { return current_user_can('delete_posts'); },
    ));

    register_rest_route('linkblog/v1', '/schedule', array(
        array(
            'methods'             => 'GET',
            'callback'            => 'linkblog_get_schedule',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ),
        array(
            'methods'             => 'POST',
            'callback'            => 'linkblog_save_schedule',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ),
    ));
}
add_action('rest_api_init', 'linkblog_register_rest_routes');

function linkblog_rest_delete_link(WP_REST_Request $request) {
    $link_id = (int) $request['id'];
    if (get_post_type($link_id) !== 'linkblog') {
        return new WP_Error('invalid_link', 'Link not found', array('status' => 404));
    }
    $result = wp_delete_post($link_id, true);
    if (!$result) {
        return new WP_Error('delete_failed', 'Could not delete link', array('status' => 500));
    }
    return new WP_REST_Response(null, 204);
}

function linkblog_get_schedule() {
    $default = array(
        'mode'       => 'daily',
        'recurrence' => array(
            'interval'  => 1,
            'weekdays'  => array(),
            'monthDays' => array(array('type' => 'day', 'value' => 1, 'nth' => 1, 'weekday' => 'MO')),
            'nthWeek'   => null,
        ),
        'trigger' => array('count' => 10, 'tag_id' => null, 'days' => 7),
        'times'   => array('09:00'),
    );
    $config = get_option('linkblog_schedule', $default);
    return rest_ensure_response($config);
}

function linkblog_save_schedule(WP_REST_Request $request) {
    $data = $request->get_json_params();
    if (empty($data) || !isset($data['mode'])) {
        return new WP_Error('invalid_data', __('Invalid schedule data', 'linkblog'), array('status' => 400));
    }
    update_option('linkblog_schedule', $data);
    return rest_ensure_response(array('success' => true));
}

/**
 * Permission check for REST API endpoints
 */
function linkblog_rest_permission_check($request) {
    // Check for API key in header
    $api_key = $request->get_header('X-LinkBlog-API-Key');
    $stored_key = get_option('linkblog_api_key');

    if (!empty($api_key) && !empty($stored_key) && hash_equals($stored_key, $api_key)) {
        return true;
    }

    // Fallback to WordPress authentication
    return current_user_can('edit_posts');
}

/**
 * REST API callback to add a link
 */
function linkblog_rest_add_link($request) {
    $title = $request->get_param('title');
    $url = $request->get_param('url');
    $content = $request->get_param('content');
    $categories = $request->get_param('categories');
    $tags = $request->get_param('tags');

    if (empty($title)) {
        return new WP_Error('missing_title', __('Title is required.', 'linkblog'), array('status' => 400));
    }

    // Create the post
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_type'    => 'linkblog',
        'post_status'  => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('insert_failed', __('Failed to create link.', 'linkblog'), array('status' => 500));
    }

    // Save URL
    if (!empty($url)) {
        update_post_meta($post_id, '_linkblog_url', $url);
    }

    // Set categories
    if (!empty($categories) && is_array($categories)) {
        $category_ids = array();
        foreach ($categories as $cat_name) {
            $term = get_term_by('name', $cat_name, 'linkblog_category');
            if (!$term) {
                $term = wp_insert_term($cat_name, 'linkblog_category');
                if (!is_wp_error($term)) {
                    $category_ids[] = $term['term_id'];
                }
            } else {
                $category_ids[] = $term->term_id;
            }
        }
        if (!empty($category_ids)) {
            wp_set_object_terms($post_id, $category_ids, 'linkblog_category');
        }
    }

    // Set tags
    if (!empty($tags)) {
        $tag_names = array_map('trim', explode(',', $tags));
        wp_set_object_terms($post_id, $tag_names, 'linkblog_tag');
    }

    return rest_ensure_response(array(
        'success' => true,
        'post_id' => $post_id,
        'message' => __('Link added successfully!', 'linkblog'),
    ));
}

/**
 * REST API callback to get categories
 */
function linkblog_rest_get_categories($request) {
    $cache_key = 'linkblog_api_categories_list';
    $category_list = get_transient($cache_key);

    if (false === $category_list) {
        $categories = get_terms(array(
            'taxonomy'   => 'linkblog_category',
            'hide_empty' => false,
        ));

        if (is_wp_error($categories)) {
            return new WP_Error('fetch_failed', __('Failed to fetch categories.', 'linkblog'), array('status' => 500));
        }

        $category_list = array();
        foreach ($categories as $category) {
            $category_list[] = array(
                'id'   => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
            );
        }
        set_transient($cache_key, $category_list, HOUR_IN_SECONDS);
    }
    return rest_ensure_response($category_list);
}

/**
 * Invalidate the REST API categories cache when a term is modified.
 */
function linkblog_invalidate_categories_cache() {
    delete_transient('linkblog_api_categories_list');
}
add_action('created_linkblog_category', 'linkblog_invalidate_categories_cache');
add_action('edited_linkblog_category', 'linkblog_invalidate_categories_cache');
add_action('delete_linkblog_category', 'linkblog_invalidate_categories_cache');

/**
 * Add CORS headers for REST API
 */
function linkblog_add_cors_headers() {
    // Get the origin from the request
    $origin = get_http_origin();

    // Allow requests from Chrome extensions
    if (strpos($origin, 'chrome-extension://') === 0) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type, X-LinkBlog-API-Key, Authorization');
    }
}
add_action('rest_api_init', 'linkblog_add_cors_headers');

/**
 * Handle preflight OPTIONS requests
 */
function linkblog_handle_preflight() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $origin = get_http_origin();
        if (strpos($origin, 'chrome-extension://') === 0) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Content-Type, X-LinkBlog-API-Key, Authorization');
            header('Access-Control-Max-Age: 86400');
            exit;
        }
    }
}
add_action('init', 'linkblog_handle_preflight', 1);

/**
 * Display content for LinkBlog dashboard widget
 */
function linkblog_dashboard_widget_content() {
    // Get statistics
    $stats = linkblog_get_publish_statistics();

    // Get recent unpublished links
    $recent_unpublished = get_posts(array(
        'post_type'      => 'linkblog',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_linkblog_publish_status',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key'     => '_linkblog_publish_status',
                'value'   => array('published', 'draft'),
                'compare' => 'NOT IN'
            )
        )
    ));

    ?>
    <div class="linkblog-widget-stats" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px;">
        <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
            <div style="font-size: 24px; font-weight: 600; color: #2271b1;"><?php echo number_format($stats['total_links']); ?></div>
            <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php _e('Total', 'linkblog'); ?></div>
        </div>
        <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
            <div style="font-size: 24px; font-weight: 600; color: #00a32a;"><?php echo number_format($stats['published_links']); ?></div>
            <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php _e('Published', 'linkblog'); ?></div>
        </div>
        <div style="text-align: center; padding: 12px; background: #f0f0f1; border-radius: 4px;">
            <div style="font-size: 24px; font-weight: 600; color: #dba617;"><?php echo number_format($stats['unpublished_links']); ?></div>
            <div style="font-size: 11px; color: #646970; text-transform: uppercase; margin-top: 4px;"><?php _e('Unpublished', 'linkblog'); ?></div>
        </div>
    </div>

    <?php if (!empty($recent_unpublished)) : ?>
        <div style="margin-bottom: 12px;">
            <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1d2327;"><?php _e('Recent Unpublished', 'linkblog'); ?></h4>
            <ul style="margin: 0; padding: 0; list-style: none;">
                <?php foreach ($recent_unpublished as $link) :
                    $url = get_post_meta($link->ID, '_linkblog_url', true);
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
        <a href="<?php echo admin_url('admin.php?page=linkblog-dashboard'); ?>" class="button button-primary">
            <?php _e('Go to LinkBlog', 'linkblog'); ?>
        </a>
    </div>
    <?php
}

/**
 * Dashboard page with statistics and overview
 */
function linkblog_dashboard_page() {
    // Handle batch publish form submission
    $batch_result = null;
    if (isset($_POST['linkblog_batch_publish']) && wp_verify_nonce($_POST['linkblog_batch_nonce'], 'linkblog_batch_publish')) {
        $as_draft = isset($_POST['publish_as_draft']) && $_POST['publish_as_draft'] === '1';

        // Get all unpublished links
        $unpublished_args = array(
            'post_type'      => 'linkblog',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_linkblog_publish_status',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => '_linkblog_publish_status',
                    'value'   => array('published', 'draft'),
                    'compare' => 'NOT IN'
                )
            )
        );
        $unpublished_links = get_posts($unpublished_args);

        $batch_result = linkblog_batch_publish_links($unpublished_links, $as_draft);
    }

    // Handle roundup post creation
    $roundup_result = null;
    if (isset($_POST['linkblog_create_roundup']) && wp_verify_nonce($_POST['linkblog_roundup_nonce'], 'linkblog_create_roundup')) {
        $roundup_title = sanitize_text_field($_POST['roundup_title']);
        $as_draft = isset($_POST['roundup_as_draft']) && $_POST['roundup_as_draft'] === '1';

        // Get all unpublished links
        $unpublished_args = array(
            'post_type'      => 'linkblog',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_linkblog_publish_status',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => '_linkblog_publish_status',
                    'value'   => array('published', 'draft'),
                    'compare' => 'NOT IN'
                )
            )
        );
        $unpublished_links = get_posts($unpublished_args);

        $roundup_result = linkblog_create_roundup_post($unpublished_links, $roundup_title, $as_draft);
    }

    // Handle quick add form submission
    $quick_add_success = false;
    if (isset($_POST['linkblog_quick_add']) && wp_verify_nonce($_POST['linkblog_quick_nonce'], 'linkblog_quick_add_link')) {
        $title = sanitize_text_field($_POST['quick_title']);
        $url = esc_url_raw($_POST['quick_url']);

        if (!empty($title)) {
            $post_data = array(
                'post_title'   => $title,
                'post_type'    => 'linkblog',
                'post_status'  => 'publish',
            );

            $post_id = wp_insert_post($post_data);

            if ($post_id && !empty($url)) {
                update_post_meta($post_id, '_linkblog_url', $url);
            }

            if ($post_id) {
                $quick_add_success = true;
            }
        }
    }

    // Get statistics
    $publish_stats = linkblog_get_publish_statistics();
    $total_links = $publish_stats['total_links'];
    $published_links = $publish_stats['published_links'];
    $unpublished_links = $publish_stats['unpublished_links'];

    $categories = get_terms(array(
        'taxonomy'   => 'linkblog_category',
        'hide_empty' => false,
    ));
    $total_categories = count($categories);

    $tags = get_terms(array(
        'taxonomy'   => 'linkblog_tag',
        'hide_empty' => false,
    ));
    $total_tags = count($tags);

    // Get recent unpublished links for display
    $recent_links_args = array(
        'post_type'      => 'linkblog',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_linkblog_publish_status',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key'     => '_linkblog_publish_status',
                'value'   => array('published', 'draft'),
                'compare' => 'NOT IN'
            )
        )
    );
    $recent_links = get_posts($recent_links_args);

    // Get recently published links for display
    $recently_published_args = array(
        'post_type'      => 'linkblog',
        'posts_per_page' => 5,
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_key'       => '_linkblog_published_date',
        'meta_query'     => array(
            array(
                'key'     => '_linkblog_publish_status',
                'value'   => array('published', 'draft'),
                'compare' => 'IN'
            )
        )
    );
    $recently_published = get_posts($recently_published_args);

    ?>
    <div class="wrap">
        <?php if ($batch_result !== null) : ?>
            <?php if ($batch_result['success'] > 0) : ?>
                <div class="lb-result-success">
                    <?php printf(
                        __('Successfully processed %d link(s). %s', 'linkblog'),
                        $batch_result['success'],
                        $batch_result['failed'] > 0 ? sprintf(__('%d failed.', 'linkblog'), $batch_result['failed']) : ''
                    ); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($batch_result['messages'])) : ?>
                <div class="lb-result-error">
                    <?php echo implode('<br>', array_map('esc_html', $batch_result['messages'])); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($roundup_result !== null) : ?>
            <?php if ($roundup_result['success']) : ?>
                <div class="lb-result-success">
                    <?php echo esc_html($roundup_result['message']); ?>
                    <a href="<?php echo get_permalink($roundup_result['post_id']); ?>" target="_blank" style="color: white; text-decoration: underline; margin-left: 8px;">
                        <?php _e('View Post', 'linkblog'); ?> →
                    </a>
                </div>
            <?php else : ?>
                <div class="lb-result-error">
                    <?php echo esc_html($roundup_result['message']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="lb-stats-grid">
            <div class="lb-stat-card">
                <div class="lb-stat-icon">📚</div>
                <div class="lb-stat-text">
                    <div class="lb-stat-value"><?php echo number_format($total_links); ?></div>
                    <p class="lb-stat-label"><?php _e('Total Links', 'linkblog'); ?></p>
                </div>
            </div>

            <div class="lb-stat-card">
                <div class="lb-stat-icon">📁</div>
                <div class="lb-stat-text">
                    <div class="lb-stat-value"><?php echo number_format($total_categories); ?></div>
                    <p class="lb-stat-label"><?php _e('Categories', 'linkblog'); ?></p>
                </div>
            </div>

            <div class="lb-stat-card">
                <div class="lb-stat-icon">📄</div>
                <div class="lb-stat-text">
                    <div class="lb-stat-value"><?php echo number_format($published_links); ?></div>
                    <p class="lb-stat-label"><?php _e('Published', 'linkblog'); ?></p>
                </div>
            </div>

            <div class="lb-stat-card">
                <div class="lb-stat-icon">⏳</div>
                <div class="lb-stat-text">
                    <div class="lb-stat-value"><?php echo number_format($unpublished_links); ?></div>
                    <p class="lb-stat-label"><?php _e('Unpublished', 'linkblog'); ?></p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="lb-dashboard-content">
            <!-- Left Column: Recent Links and Recently Published -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Recent Unpublished Links -->
                <div class="lb-section-card">
                    <div class="lb-section-header">
                        <h2 class="lb-section-title"><?php _e('Recent Unpublished Links', 'linkblog'); ?></h2>
                    </div>

                    <?php if (empty($recent_links)) : ?>
                        <div class="lb-empty-state">
                            <div class="lb-empty-icon">✓</div>
                            <h3 class="lb-empty-title"><?php _e('All caught up!', 'linkblog'); ?></h3>
                            <p class="lb-empty-text"><?php _e('No unpublished links at the moment', 'linkblog'); ?></p>
                        </div>
                    <?php else : ?>
                        <ul class="lb-recent-links">
                            <?php foreach ($recent_links as $link) :
                                $url = get_post_meta($link->ID, '_linkblog_url', true);
                                $categories_list = get_the_terms($link->ID, 'linkblog_category');
                                $category_name = $categories_list && !is_wp_error($categories_list) ? $categories_list[0]->name : '';
                            ?>
                                <li class="lb-link-item" data-link-id="<?php echo esc_attr($link->ID); ?>">
                                    <div class="lb-link-item-header">
                                        <h3 class="lb-link-title"><?php echo esc_html($link->post_title); ?></h3>
                                        <button class="lb-delete-btn" title="<?php esc_attr_e('Delete link', 'linkblog'); ?>" data-link-id="<?php echo (int) $link->ID; ?>"><span class="dashicons dashicons-trash"></span></button>
                                    </div>
                                    <?php if ($url) : ?>
                                        <a href="<?php echo esc_url($url); ?>" class="lb-link-url" target="_blank" rel="noopener">
                                            <?php echo esc_html(parse_url($url, PHP_URL_HOST)); ?> ↗
                                        </a>
                                    <?php endif; ?>
                                    <div class="lb-link-meta">
                                        <?php if ($category_name) : ?>
                                            <span class="lb-link-meta-item">📁 <?php echo esc_html($category_name); ?></span>
                                        <?php endif; ?>
                                        <span class="lb-link-meta-item lb-date-time" data-timestamp="<?php echo get_the_time('U', $link->ID); ?>">
                                            📅 <?php echo get_the_date('M j, Y', $link->ID); ?> at <?php echo get_the_time('g:i a', $link->ID); ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (!empty($recent_links)) : ?>
                        <div class="lb-section-body" style="padding-top: 0; text-align: center;">
                            <a href="<?php echo admin_url('admin.php?page=linkblog-admin'); ?>" class="lb-btn lb-btn-primary">
                                <?php _e('View All Links', 'linkblog'); ?> →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recently Published -->
                <div class="lb-section-card">
                    <div class="lb-section-header">
                        <h2 class="lb-section-title"><?php _e('Recently Published', 'linkblog'); ?></h2>
                    </div>

                    <?php if (empty($recently_published)) : ?>
                        <div class="lb-empty-state">
                            <div class="lb-empty-icon">📄</div>
                            <h3 class="lb-empty-title"><?php _e('No published links yet', 'linkblog'); ?></h3>
                            <p class="lb-empty-text"><?php _e('Published links will appear here', 'linkblog'); ?></p>
                        </div>
                    <?php else : ?>
                        <ul class="lb-recent-links">
                            <?php foreach ($recently_published as $link) :
                                $url = get_post_meta($link->ID, '_linkblog_url', true);
                                $published_post_id = get_post_meta($link->ID, '_linkblog_published_post_id', true);
                                $publish_status = get_post_meta($link->ID, '_linkblog_publish_status', true);
                                $published_date = get_post_meta($link->ID, '_linkblog_published_date', true);
                                $categories_list = get_the_terms($link->ID, 'linkblog_category');
                                $category_name = $categories_list && !is_wp_error($categories_list) ? $categories_list[0]->name : '';
                            ?>
                                <li class="lb-link-item">
                                    <h3 class="lb-link-title"><?php echo esc_html($link->post_title); ?></h3>
                                    <?php if ($published_post_id) : ?>
                                        <a href="<?php echo $publish_status === 'draft' ? get_edit_post_link($published_post_id) : get_permalink($published_post_id); ?>" class="lb-link-url" target="_blank" rel="noopener">
                                            <?php echo $publish_status === 'draft' ? __('View Draft', 'linkblog') : __('View Post', 'linkblog'); ?> ↗
                                        </a>
                                    <?php endif; ?>
                                    <div class="lb-link-meta">
                                        <?php if ($category_name) : ?>
                                            <span class="lb-link-meta-item">📁 <?php echo esc_html($category_name); ?></span>
                                        <?php endif; ?>
                                        <?php if ($published_date) : ?>
                                            <span class="lb-link-meta-item">📅 <?php echo mysql2date('M j, Y', $published_date); ?></span>
                                        <?php endif; ?>
                                        <span class="lb-link-meta-item">
                                            <?php if ($publish_status === 'published') : ?>
                                                <span class="lb-status-badge lb-status-published" style="font-size: 10px; padding: 2px 8px;">✓ <?php _e('Published', 'linkblog'); ?></span>
                                            <?php elseif ($publish_status === 'draft') : ?>
                                                <span class="lb-status-badge lb-status-draft" style="font-size: 10px; padding: 2px 8px;">📝 <?php _e('Draft', 'linkblog'); ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Publish Links -->
                <div class="lb-section-card">
                    <div class="lb-section-header">
                        <h2 class="lb-section-title"><?php _e('Publish Links', 'linkblog'); ?></h2>
                    </div>
                    <div class="lb-section-body">
                        <?php if ($unpublished_links > 0) : ?>
                            <p style="margin-bottom: 20px; color: var(--lb-neutral-600);">
                                <?php printf(__('You have %d unpublished link(s) ready to be published.', 'linkblog'), $unpublished_links); ?>
                            </p>

                            <form method="post" action="" class="lb-quick-form">
                                <?php wp_nonce_field('linkblog_create_roundup', 'linkblog_roundup_nonce'); ?>

                                <div class="lb-form-group">
                                    <label for="roundup_title" class="lb-form-label"><?php _e('Post Title', 'linkblog'); ?></label>
                                    <input
                                        type="text"
                                        id="roundup_title"
                                        name="roundup_title"
                                        class="lb-form-input"
                                        value="<?php echo esc_attr(sprintf(__('Links Roundup - %s', 'linkblog'), date('F j, Y'))); ?>"
                                        placeholder="<?php echo esc_attr(sprintf(__('Links Roundup - %s', 'linkblog'), date('F j, Y'))); ?>"
                                    >
                                </div>

                                <div class="lb-button-group">
                                    <button type="submit" name="linkblog_create_roundup" class="lb-btn lb-btn-primary">
                                        <?php _e('Publish', 'linkblog'); ?>
                                    </button>
                                    <button type="submit" name="linkblog_create_roundup" value="1" onclick="this.form.elements['roundup_as_draft'].value='1';" class="lb-btn lb-btn-secondary">
                                        <?php _e('Save as Draft', 'linkblog'); ?>
                                    </button>
                                </div>
                                <input type="hidden" name="roundup_as_draft" value="0">
                            </form>
                        <?php else : ?>
                            <div class="lb-empty-state">
                                <div class="lb-empty-icon">✓</div>
                                <h3 class="lb-empty-title"><?php _e('All caught up!', 'linkblog'); ?></h3>
                                <p class="lb-empty-text"><?php _e('No unpublished links at the moment.', 'linkblog'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Add Form -->
                <div class="lb-section-card">
                    <div class="lb-section-header">
                        <h2 class="lb-section-title"><?php _e('Quick Add', 'linkblog'); ?></h2>
                    </div>
                    <div class="lb-section-body">
                        <?php if ($quick_add_success) : ?>
                            <div class="lb-success-message">
                                <span class="lb-success-icon">✓</span>
                                <span><?php _e('Link added successfully!', 'linkblog'); ?></span>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="" class="lb-quick-form">
                            <?php wp_nonce_field('linkblog_quick_add_link', 'linkblog_quick_nonce'); ?>

                            <div class="lb-form-group">
                                <label for="quick_title" class="lb-form-label"><?php _e('Title', 'linkblog'); ?> *</label>
                                <input
                                    type="text"
                                    id="quick_title"
                                    name="quick_title"
                                    class="lb-form-input"
                                    placeholder="<?php _e('Enter link title', 'linkblog'); ?>"
                                    required
                                >
                            </div>

                            <div class="lb-form-group">
                                <label for="quick_url" class="lb-form-label"><?php _e('URL', 'linkblog'); ?></label>
                                <input
                                    type="url"
                                    id="quick_url"
                                    name="quick_url"
                                    class="lb-form-input"
                                    placeholder="https://example.com"
                                >
                            </div>

                            <button type="submit" name="linkblog_quick_add" class="lb-btn lb-btn-primary">
                                <?php _e('Add Link', 'linkblog'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.lb-date-time').forEach(function(element) {
            const timestamp = parseInt(element.dataset.timestamp);
            if (!timestamp) return;
            const date = new Date(timestamp * 1000);
            element.textContent = '📅 ' + date.toLocaleString(navigator.language, {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        });
    });

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.lb-delete-btn');
        if (!btn) return;
        const linkId = btn.dataset.linkId;
        if (!confirm('<?php echo esc_js(__('Delete this link? This cannot be undone.', 'linkblog')); ?>')) return;
        btn.disabled = true;
        btn.style.opacity = '0.4';
        try {
            const res = await fetch('<?php echo esc_js(rest_url('linkblog/v1/links/')); ?>' + linkId, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: { 'X-WP-Nonce': '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>' }
            });
            if (res.ok || res.status === 204) {
                btn.closest('li').remove();
            } else {
                alert('<?php echo esc_js(__('Failed to delete link.', 'linkblog')); ?>');
                btn.disabled = false;
                btn.style.opacity = '';
            }
        } catch (err) {
            alert('<?php echo esc_js(__('Failed to delete link.', 'linkblog')); ?>');
            btn.disabled = false;
            btn.style.opacity = '';
        }
    });
    </script>
    <?php
}

/**
 * Show Links page
 */
function linkblog_show_links_page() {
    $action_message = '';
    $action_error = '';

    // Handle publish action
    if (isset($_GET['action']) && $_GET['action'] === 'publish_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
        if (wp_verify_nonce($_GET['_wpnonce'], 'publish_link_' . $_GET['link_id'])) {
            $result = linkblog_create_blog_post($_GET['link_id'], false);
            if ($result['success']) {
                $action_message = $result['message'] . ' <a href="' . get_permalink($result['post_id']) . '" target="_blank">' . __('View Post', 'linkblog') . '</a>';
            } else {
                $action_error = $result['message'];
            }
        }
    }

    // Handle draft action
    if (isset($_GET['action']) && $_GET['action'] === 'draft_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
        if (wp_verify_nonce($_GET['_wpnonce'], 'draft_link_' . $_GET['link_id'])) {
            $result = linkblog_create_blog_post($_GET['link_id'], true);
            if ($result['success']) {
                $action_message = $result['message'] . ' <a href="' . get_edit_post_link($result['post_id']) . '" target="_blank">' . __('Edit Draft', 'linkblog') . '</a>';
            } else {
                $action_error = $result['message'];
            }
        }
    }

    // Handle unpublish action
    if (isset($_GET['action']) && $_GET['action'] === 'unpublish_link' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
        if (wp_verify_nonce($_GET['_wpnonce'], 'unpublish_link_' . $_GET['link_id'])) {
            $result = linkblog_unpublish_link($_GET['link_id']);
            if ($result['success']) {
                $action_message = $result['message'];
            } else {
                $action_error = $result['message'];
            }
        }
    }

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['link_id']) && isset($_GET['_wpnonce'])) {
        if (wp_verify_nonce($_GET['_wpnonce'], 'delete_link_' . $_GET['link_id'])) {
            wp_delete_post($_GET['link_id'], true);
            $action_message = __('Link deleted successfully.', 'linkblog');
        }
    }

    // Get links grouped by category
    $grouped_links = linkblog_get_links_grouped_by_category();
    $has_links = false;
    foreach ($grouped_links as $category_links) {
        if (!empty($category_links)) {
            $has_links = true;
            break;
        }
    }
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e('LinkBlog - All Links', 'linkblog'); ?></h1>
        <a href="<?php echo admin_url('admin.php?page=linkblog-add'); ?>" class="page-title-action"><?php _e('Add New', 'linkblog'); ?></a>
        <hr class="wp-header-end">

        <?php if ($action_message) : ?>
            <div class="notice notice-success is-dismissible"><p><?php echo wp_kses_post($action_message); ?></p></div>
        <?php endif; ?>

        <?php if ($action_error) : ?>
            <div class="notice notice-error is-dismissible"><p><?php echo esc_html($action_error); ?></p></div>
        <?php endif; ?>

        <?php if (!$has_links) : ?>
            <p><?php _e('No links found. Add your first link!', 'linkblog'); ?></p>
        <?php else : ?>
            <?php foreach ($grouped_links as $category_name => $category_links) : ?>
                <div class="lb-category-section">
                    <h2 class="lb-category-heading"><?php echo esc_html($category_name); ?></h2>

                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th style="width: 25%;"><?php _e('Title', 'linkblog'); ?></th>
                                <th style="width: 25%;"><?php _e('URL', 'linkblog'); ?></th>
                                <th style="width: 10%;"><?php _e('Status', 'linkblog'); ?></th>
                                <th style="width: 10%;"><?php _e('Published Date', 'linkblog'); ?></th>
                                <th style="width: 10%;"><?php _e('Date', 'linkblog'); ?></th>
                                <th style="width: 20%;"><?php _e('Actions', 'linkblog'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($category_links as $link) :
                                $url = get_post_meta($link->ID, '_linkblog_url', true);
                                $publish_status = get_post_meta($link->ID, '_linkblog_publish_status', true);
                                $published_post_id = get_post_meta($link->ID, '_linkblog_published_post_id', true);
                                $published_date = get_post_meta($link->ID, '_linkblog_published_date', true);

                                if (empty($publish_status)) {
                                    $publish_status = 'unpublished';
                                }
                            ?>
                                <tr>
                                    <td><strong><?php echo esc_html($link->post_title); ?></strong></td>
                                    <td>
                                        <?php if ($url) : ?>
                                            <a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo esc_html(substr($url, 0, 50)) . (strlen($url) > 50 ? '...' : ''); ?></a>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($publish_status === 'published') : ?>
                                            <span class="lb-status-badge lb-status-published">✓ <?php _e('Published', 'linkblog'); ?></span>
                                        <?php elseif ($publish_status === 'draft') : ?>
                                            <span class="lb-status-badge lb-status-draft">📝 <?php _e('Draft', 'linkblog'); ?></span>
                                        <?php else : ?>
                                            <span class="lb-status-badge lb-status-unpublished"><?php _e('Unpublished', 'linkblog'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($published_date) : ?>
                                            <?php echo esc_html(mysql2date('Y-m-d', $published_date)); ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo get_the_date('Y-m-d', $link->ID); ?></td>
                                    <td>
                                        <?php if ($publish_status === 'unpublished') : ?>
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID); ?>"><?php _e('Publish', 'linkblog'); ?></a> |
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=draft_link&link_id=' . $link->ID), 'draft_link_' . $link->ID); ?>"><?php _e('Save as Draft', 'linkblog'); ?></a> |
                                        <?php elseif ($publish_status === 'published') : ?>
                                            <a href="<?php echo get_permalink($published_post_id); ?>" target="_blank"><?php _e('View Post', 'linkblog'); ?></a> |
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to unpublish this link?', 'linkblog'); ?>');"><?php _e('Unpublish', 'linkblog'); ?></a> |
                                        <?php elseif ($publish_status === 'draft') : ?>
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=publish_link&link_id=' . $link->ID), 'publish_link_' . $link->ID); ?>"><?php _e('Publish', 'linkblog'); ?></a> |
                                            <a href="<?php echo get_edit_post_link($published_post_id); ?>" target="_blank"><?php _e('View Draft', 'linkblog'); ?></a> |
                                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=unpublish_link&link_id=' . $link->ID), 'unpublish_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to unpublish this link?', 'linkblog'); ?>');"><?php _e('Unpublish', 'linkblog'); ?></a> |
                                        <?php endif; ?>
                                        <a href="<?php echo get_edit_post_link($link->ID); ?>"><?php _e('Edit', 'linkblog'); ?></a> |
                                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=linkblog-admin&action=delete&link_id=' . $link->ID), 'delete_link_' . $link->ID); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this link?', 'linkblog'); ?>');"><?php _e('Delete', 'linkblog'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add Link page
 */
function linkblog_add_link_page() {
    $message = '';
    $error = '';

    // Handle form submission
    if (isset($_POST['linkblog_add_submit']) && wp_verify_nonce($_POST['linkblog_add_nonce'], 'linkblog_add_link')) {
        $title = sanitize_text_field($_POST['linkblog_title']);
        $url = esc_url_raw($_POST['linkblog_url']);
        $content = wp_kses_post($_POST['linkblog_content']);
        $categories = isset($_POST['linkblog_categories']) ? array_map('intval', $_POST['linkblog_categories']) : array();
        $tags = sanitize_text_field($_POST['linkblog_tags']);

        if (empty($title)) {
            $error = __('Title is required.', 'linkblog');
        } else {
            // Create the post
            $post_data = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_type'    => 'linkblog',
                'post_status'  => 'publish',
            );

            $post_id = wp_insert_post($post_data);

            if ($post_id) {
                // Save URL
                if (!empty($url)) {
                    update_post_meta($post_id, '_linkblog_url', $url);
                }

                // Set categories
                if (!empty($categories)) {
                    wp_set_object_terms($post_id, $categories, 'linkblog_category');
                }

                // Set tags
                if (!empty($tags)) {
                    $tag_names = array_map('trim', explode(',', $tags));
                    wp_set_object_terms($post_id, $tag_names, 'linkblog_tag');
                }

                $message = __('Link added successfully!', 'linkblog');

                // Clear form
                $_POST = array();
            } else {
                $error = __('Failed to add link.', 'linkblog');
            }
        }
    }

    // Get all categories
    $all_categories = get_terms(array(
        'taxonomy'   => 'linkblog_category',
        'hide_empty' => false,
    ));
    ?>
    <div class="wrap">
        <h1><?php _e('Add New Link', 'linkblog'); ?></h1>

        <?php if ($message) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error) : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('linkblog_add_link', 'linkblog_add_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="linkblog_title"><?php _e('Title', 'linkblog'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="linkblog_title" id="linkblog_title" class="regular-text" value="<?php echo isset($_POST['linkblog_title']) ? esc_attr($_POST['linkblog_title']) : ''; ?>" required>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_url"><?php _e('URL', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <input type="url" name="linkblog_url" id="linkblog_url" class="regular-text" value="<?php echo isset($_POST['linkblog_url']) ? esc_attr($_POST['linkblog_url']) : ''; ?>" placeholder="https://example.com">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_content"><?php _e('Text/Description', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <?php
                        $content = isset($_POST['linkblog_content']) ? $_POST['linkblog_content'] : '';
                        wp_editor($content, 'linkblog_content', array(
                            'textarea_name' => 'linkblog_content',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                        ));
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_categories"><?php _e('Categories', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <?php if (!empty($all_categories)) : ?>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                                <?php foreach ($all_categories as $category) : ?>
                                    <label style="display: block; margin-bottom: 5px;">
                                        <input type="checkbox" name="linkblog_categories[]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo isset($_POST['linkblog_categories']) && in_array($category->term_id, $_POST['linkblog_categories']) ? 'checked' : ''; ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p><?php _e('No categories available. Create categories first in LinkBlog > Categories.', 'linkblog'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="linkblog_tags"><?php _e('Tags', 'linkblog'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="linkblog_tags" id="linkblog_tags" class="regular-text" value="<?php echo isset($_POST['linkblog_tags']) ? esc_attr($_POST['linkblog_tags']) : ''; ?>" placeholder="<?php _e('Separate tags with commas', 'linkblog'); ?>">
                        <p class="description"><?php _e('Separate multiple tags with commas (e.g., tag1, tag2, tag3)', 'linkblog'); ?></p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="linkblog_add_submit" id="submit" class="button button-primary" value="<?php _e('Add Link', 'linkblog'); ?>">
                <a href="<?php echo admin_url('admin.php?page=linkblog-admin'); ?>" class="button"><?php _e('Cancel', 'linkblog'); ?></a>
            </p>
        </form>
    </div>
    <?php
}
