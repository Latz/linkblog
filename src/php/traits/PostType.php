<?php

declare(strict_types=1);

trait LinkBlog_PostType {

    public function registerPostType(): void {
        $labels = array(
            'name'                  => _x('Links', 'Post Type General Name', 'LinkBlog'),
            'singular_name'         => _x('Link', 'Post Type Singular Name', 'LinkBlog'),
            'menu_name'             => __('LinkBlog', 'LinkBlog'),
            'name_admin_bar'        => __('Link', 'LinkBlog'),
            'archives'              => __('Link Archives', 'LinkBlog'),
            'attributes'            => __('Link Attributes', 'LinkBlog'),
            'parent_item_colon'     => __('Parent Link:', 'LinkBlog'),
            'all_items'             => __('All Links', 'LinkBlog'),
            'add_new_item'          => __('Add New Link', 'LinkBlog'),
            'add_new'               => __('Add New', 'LinkBlog'),
            'new_item'              => __('New Link', 'LinkBlog'),
            'edit_item'             => __('Edit Link', 'LinkBlog'),
            'update_item'           => __('Update Link', 'LinkBlog'),
            'view_item'             => __('View Link', 'LinkBlog'),
            'view_items'            => __('View Links', 'LinkBlog'),
            'search_items'          => __('Search Link', 'LinkBlog'),
            'not_found'             => __('Not found', 'LinkBlog'),
            'not_found_in_trash'    => __('Not found in Trash', 'LinkBlog'),
            'featured_image'        => __('Featured Image', 'LinkBlog'),
            'set_featured_image'    => __('Set featured image', 'LinkBlog'),
            'remove_featured_image' => __('Remove featured image', 'LinkBlog'),
            'use_featured_image'    => __('Use as featured image', 'LinkBlog'),
            'insert_into_item'      => __('Insert into link', 'LinkBlog'),
            'uploaded_to_this_item' => __('Uploaded to this link', 'LinkBlog'),
            'items_list'            => __('Links list', 'LinkBlog'),
            'items_list_navigation' => __('Links list navigation', 'LinkBlog'),
            'filter_items_list'     => __('Filter links list', 'LinkBlog'),
        );

        $args = array(
            'label'                 => __('Link', 'LinkBlog'),
            'description'           => __('Links to publish on blog', 'LinkBlog'),
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
            'name'                       => _x('Link Categories', 'Taxonomy General Name', 'LinkBlog'),
            'singular_name'              => _x('Link Category', 'Taxonomy Singular Name', 'LinkBlog'),
            'menu_name'                  => __('Categories', 'LinkBlog'),
            'all_items'                  => __('All Categories', 'LinkBlog'),
            'parent_item'                => __('Parent Category', 'LinkBlog'),
            'parent_item_colon'          => __('Parent Category:', 'LinkBlog'),
            'new_item_name'              => __('New Category Name', 'LinkBlog'),
            'add_new_item'               => __('Add New Category', 'LinkBlog'),
            'edit_item'                  => __('Edit Category', 'LinkBlog'),
            'update_item'                => __('Update Category', 'LinkBlog'),
            'view_item'                  => __('View Category', 'LinkBlog'),
            'separate_items_with_commas' => __('Separate categories with commas', 'LinkBlog'),
            'add_or_remove_items'        => __('Add or remove categories', 'LinkBlog'),
            'choose_from_most_used'      => __('Choose from the most used', 'LinkBlog'),
            'popular_items'              => __('Popular Categories', 'LinkBlog'),
            'search_items'               => __('Search Categories', 'LinkBlog'),
            'not_found'                  => __('Not Found', 'LinkBlog'),
            'no_terms'                   => __('No categories', 'LinkBlog'),
            'items_list'                 => __('Categories list', 'LinkBlog'),
            'items_list_navigation'      => __('Categories list navigation', 'LinkBlog'),
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
            'name'                       => _x('Link Tags', 'Taxonomy General Name', 'LinkBlog'),
            'singular_name'              => _x('Link Tag', 'Taxonomy Singular Name', 'LinkBlog'),
            'menu_name'                  => __('Tags', 'LinkBlog'),
            'all_items'                  => __('All Tags', 'LinkBlog'),
            'parent_item'                => __('Parent Tag', 'LinkBlog'),
            'parent_item_colon'          => __('Parent Tag:', 'LinkBlog'),
            'new_item_name'              => __('New Tag Name', 'LinkBlog'),
            'add_new_item'               => __('Add New Tag', 'LinkBlog'),
            'edit_item'                  => __('Edit Tag', 'LinkBlog'),
            'update_item'                => __('Update Tag', 'LinkBlog'),
            'view_item'                  => __('View Tag', 'LinkBlog'),
            'separate_items_with_commas' => __('Separate tags with commas', 'LinkBlog'),
            'add_or_remove_items'        => __('Add or remove tags', 'LinkBlog'),
            'choose_from_most_used'      => __('Choose from the most used', 'LinkBlog'),
            'popular_items'              => __('Popular Tags', 'LinkBlog'),
            'search_items'               => __('Search Tags', 'LinkBlog'),
            'not_found'                  => __('Not Found', 'LinkBlog'),
            'no_terms'                   => __('No tags', 'LinkBlog'),
            'items_list'                 => __('Tags list', 'LinkBlog'),
            'items_list_navigation'      => __('Tags list navigation', 'LinkBlog'),
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
