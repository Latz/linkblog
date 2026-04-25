<?php

declare(strict_types=1);

trait LinkDigest_PostType {

    public function registerPostType(): void {
        $labels = array(
            'name'                  => _x('Links', 'Post Type General Name', 'linkdigest'),
            'singular_name'         => _x('Link', 'Post Type Singular Name', 'linkdigest'),
            'menu_name'             => __('LinkDigest', 'linkdigest'),
            'name_admin_bar'        => __('Link', 'linkdigest'),
            'archives'              => __('Link Archives', 'linkdigest'),
            'attributes'            => __('Link Attributes', 'linkdigest'),
            'parent_item_colon'     => __('Parent Link:', 'linkdigest'),
            'all_items'             => __('All Links', 'linkdigest'),
            'add_new_item'          => __('Add New Link', 'linkdigest'),
            'add_new'               => __('Add New', 'linkdigest'),
            'new_item'              => __('New Link', 'linkdigest'),
            'edit_item'             => __('Edit Link', 'linkdigest'),
            'update_item'           => __('Update Link', 'linkdigest'),
            'view_item'             => __('View Link', 'linkdigest'),
            'view_items'            => __('View Links', 'linkdigest'),
            'search_items'          => __('Search Link', 'linkdigest'),
            'not_found'             => __('Not found', 'linkdigest'),
            'not_found_in_trash'    => __('Not found in Trash', 'linkdigest'),
            'featured_image'        => __('Featured Image', 'linkdigest'),
            'set_featured_image'    => __('Set featured image', 'linkdigest'),
            'remove_featured_image' => __('Remove featured image', 'linkdigest'),
            'use_featured_image'    => __('Use as featured image', 'linkdigest'),
            'insert_into_item'      => __('Insert into link', 'linkdigest'),
            'uploaded_to_this_item' => __('Uploaded to this link', 'linkdigest'),
            'items_list'            => __('Links list', 'linkdigest'),
            'items_list_navigation' => __('Links list navigation', 'linkdigest'),
            'filter_items_list'     => __('Filter links list', 'linkdigest'),
        );

        $args = array(
            'label'                 => __('Link', 'linkdigest'),
            'description'           => __('Links to publish on blog', 'linkdigest'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'taxonomies'            => array('linkdigest_category', 'linkdigest_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => plugins_url('assets/icon-20x20.png', LINKDIGEST_PLUGIN_FILE),
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );

        register_post_type('linkdigest', $args);

        register_post_meta('linkdigest', '_linkdigest_publish_status', array(
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => '__return_true',
        ));
    }

    public function registerTaxonomies(): void {
        // Register Category taxonomy
        $category_labels = array(
            'name'                       => _x('Link Categories', 'Taxonomy General Name', 'linkdigest'),
            'singular_name'              => _x('Link Category', 'Taxonomy Singular Name', 'linkdigest'),
            'menu_name'                  => __('Categories', 'linkdigest'),
            'all_items'                  => __('All Categories', 'linkdigest'),
            'parent_item'                => __('Parent Category', 'linkdigest'),
            'parent_item_colon'          => __('Parent Category:', 'linkdigest'),
            'new_item_name'              => __('New Category Name', 'linkdigest'),
            'add_new_item'               => __('Add New Category', 'linkdigest'),
            'edit_item'                  => __('Edit Category', 'linkdigest'),
            'update_item'                => __('Update Category', 'linkdigest'),
            'view_item'                  => __('View Category', 'linkdigest'),
            'separate_items_with_commas' => __('Separate categories with commas', 'linkdigest'),
            'add_or_remove_items'        => __('Add or remove categories', 'linkdigest'),
            'choose_from_most_used'      => __('Choose from the most used', 'linkdigest'),
            'popular_items'              => __('Popular Categories', 'linkdigest'),
            'search_items'               => __('Search Categories', 'linkdigest'),
            'not_found'                  => __('Not Found', 'linkdigest'),
            'no_terms'                   => __('No categories', 'linkdigest'),
            'items_list'                 => __('Categories list', 'linkdigest'),
            'items_list_navigation'      => __('Categories list navigation', 'linkdigest'),
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

        register_taxonomy('linkdigest_category', array('linkdigest'), $category_args);

        // Register Tag taxonomy
        $tag_labels = array(
            'name'                       => _x('Link Tags', 'Taxonomy General Name', 'linkdigest'),
            'singular_name'              => _x('Link Tag', 'Taxonomy Singular Name', 'linkdigest'),
            'menu_name'                  => __('Tags', 'linkdigest'),
            'all_items'                  => __('All Tags', 'linkdigest'),
            'parent_item'                => __('Parent Tag', 'linkdigest'),
            'parent_item_colon'          => __('Parent Tag:', 'linkdigest'),
            'new_item_name'              => __('New Tag Name', 'linkdigest'),
            'add_new_item'               => __('Add New Tag', 'linkdigest'),
            'edit_item'                  => __('Edit Tag', 'linkdigest'),
            'update_item'                => __('Update Tag', 'linkdigest'),
            'view_item'                  => __('View Tag', 'linkdigest'),
            'separate_items_with_commas' => __('Separate tags with commas', 'linkdigest'),
            'add_or_remove_items'        => __('Add or remove tags', 'linkdigest'),
            'choose_from_most_used'      => __('Choose from the most used', 'linkdigest'),
            'popular_items'              => __('Popular Tags', 'linkdigest'),
            'search_items'               => __('Search Tags', 'linkdigest'),
            'not_found'                  => __('Not Found', 'linkdigest'),
            'no_terms'                   => __('No tags', 'linkdigest'),
            'items_list'                 => __('Tags list', 'linkdigest'),
            'items_list_navigation'      => __('Tags list navigation', 'linkdigest'),
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

        register_taxonomy('linkdigest_tag', array('linkdigest'), $tag_args);
    }
}
