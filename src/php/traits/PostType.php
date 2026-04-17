<?php

declare(strict_types=1);

trait LinkBlog_PostType {

    public function registerPostType(): void {
        $labels = array(
            'name'                  => _x('Links', 'Post Type General Name', 'linkblog'),
            'singular_name'         => _x('Link', 'Post Type Singular Name', 'linkblog'),
            'menu_name'             => __('linkblog', 'linkblog'),
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

        register_post_meta('linkblog', '_linkblog_publish_status', array(
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => '__return_true',
        ));
    }

    public function registerTaxonomies(): void {
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
}
