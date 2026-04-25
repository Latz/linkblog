<?php

declare(strict_types=1);

trait LinkDigest_PostType {

    public function registerPostType(): void {
        $labels = array(
            'name'                  => _x('Links', 'Post Type General Name', 'LinkDigest'),
            'singular_name'         => _x('Link', 'Post Type Singular Name', 'LinkDigest'),
            'menu_name'             => __('LinkDigest', 'LinkDigest'),
            'name_admin_bar'        => __('Link', 'LinkDigest'),
            'archives'              => __('Link Archives', 'LinkDigest'),
            'attributes'            => __('Link Attributes', 'LinkDigest'),
            'parent_item_colon'     => __('Parent Link:', 'LinkDigest'),
            'all_items'             => __('All Links', 'LinkDigest'),
            'add_new_item'          => __('Add New Link', 'LinkDigest'),
            'add_new'               => __('Add New', 'LinkDigest'),
            'new_item'              => __('New Link', 'LinkDigest'),
            'edit_item'             => __('Edit Link', 'LinkDigest'),
            'update_item'           => __('Update Link', 'LinkDigest'),
            'view_item'             => __('View Link', 'LinkDigest'),
            'view_items'            => __('View Links', 'LinkDigest'),
            'search_items'          => __('Search Link', 'LinkDigest'),
            'not_found'             => __('Not found', 'LinkDigest'),
            'not_found_in_trash'    => __('Not found in Trash', 'LinkDigest'),
            'featured_image'        => __('Featured Image', 'LinkDigest'),
            'set_featured_image'    => __('Set featured image', 'LinkDigest'),
            'remove_featured_image' => __('Remove featured image', 'LinkDigest'),
            'use_featured_image'    => __('Use as featured image', 'LinkDigest'),
            'insert_into_item'      => __('Insert into link', 'LinkDigest'),
            'uploaded_to_this_item' => __('Uploaded to this link', 'LinkDigest'),
            'items_list'            => __('Links list', 'LinkDigest'),
            'items_list_navigation' => __('Links list navigation', 'LinkDigest'),
            'filter_items_list'     => __('Filter links list', 'LinkDigest'),
        );

        $args = array(
            'label'                 => __('Link', 'LinkDigest'),
            'description'           => __('Links to publish on blog', 'LinkDigest'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'taxonomies'            => array('linkdigest_category', 'linkdigest_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => plugins_url('assets/icon-20x20.png', LINKBLOG_PLUGIN_FILE),
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

        register_post_meta('linkblog', '_linkdigest_publish_status', array(
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => '__return_true',
        ));
    }

    public function registerTaxonomies(): void {
        // Register Category taxonomy
        $category_labels = array(
            'name'                       => _x('Link Categories', 'Taxonomy General Name', 'LinkDigest'),
            'singular_name'              => _x('Link Category', 'Taxonomy Singular Name', 'LinkDigest'),
            'menu_name'                  => __('Categories', 'LinkDigest'),
            'all_items'                  => __('All Categories', 'LinkDigest'),
            'parent_item'                => __('Parent Category', 'LinkDigest'),
            'parent_item_colon'          => __('Parent Category:', 'LinkDigest'),
            'new_item_name'              => __('New Category Name', 'LinkDigest'),
            'add_new_item'               => __('Add New Category', 'LinkDigest'),
            'edit_item'                  => __('Edit Category', 'LinkDigest'),
            'update_item'                => __('Update Category', 'LinkDigest'),
            'view_item'                  => __('View Category', 'LinkDigest'),
            'separate_items_with_commas' => __('Separate categories with commas', 'LinkDigest'),
            'add_or_remove_items'        => __('Add or remove categories', 'LinkDigest'),
            'choose_from_most_used'      => __('Choose from the most used', 'LinkDigest'),
            'popular_items'              => __('Popular Categories', 'LinkDigest'),
            'search_items'               => __('Search Categories', 'LinkDigest'),
            'not_found'                  => __('Not Found', 'LinkDigest'),
            'no_terms'                   => __('No categories', 'LinkDigest'),
            'items_list'                 => __('Categories list', 'LinkDigest'),
            'items_list_navigation'      => __('Categories list navigation', 'LinkDigest'),
        );

        $category_args = array(
            'labels'                     => $category_labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );

        register_taxonomy('linkdigest_category', array('linkblog'), $category_args);

        // Register Tag taxonomy
        $tag_labels = array(
            'name'                       => _x('Link Tags', 'Taxonomy General Name', 'LinkDigest'),
            'singular_name'              => _x('Link Tag', 'Taxonomy Singular Name', 'LinkDigest'),
            'menu_name'                  => __('Tags', 'LinkDigest'),
            'all_items'                  => __('All Tags', 'LinkDigest'),
            'parent_item'                => __('Parent Tag', 'LinkDigest'),
            'parent_item_colon'          => __('Parent Tag:', 'LinkDigest'),
            'new_item_name'              => __('New Tag Name', 'LinkDigest'),
            'add_new_item'               => __('Add New Tag', 'LinkDigest'),
            'edit_item'                  => __('Edit Tag', 'LinkDigest'),
            'update_item'                => __('Update Tag', 'LinkDigest'),
            'view_item'                  => __('View Tag', 'LinkDigest'),
            'separate_items_with_commas' => __('Separate tags with commas', 'LinkDigest'),
            'add_or_remove_items'        => __('Add or remove tags', 'LinkDigest'),
            'choose_from_most_used'      => __('Choose from the most used', 'LinkDigest'),
            'popular_items'              => __('Popular Tags', 'LinkDigest'),
            'search_items'               => __('Search Tags', 'LinkDigest'),
            'not_found'                  => __('Not Found', 'LinkDigest'),
            'no_terms'                   => __('No tags', 'LinkDigest'),
            'items_list'                 => __('Tags list', 'LinkDigest'),
            'items_list_navigation'      => __('Tags list navigation', 'LinkDigest'),
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

        register_taxonomy('linkdigest_tag', array('linkblog'), $tag_args);
    }
}
