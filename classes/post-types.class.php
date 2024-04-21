<?php
// Add Comparator request type
function custom_post_type() {
    $labels = array(
        'name'               => _x('Comparator requests', 'post type general name'),
        'singular_name'      => _x('Comparator request', 'post type singular name'),
        'add_new'            => _x('Add New', 'book'),
        'add_new_item'       => __('Add New Comparator request'),
        'edit_item'          => __('Edit Comparator request'),
        'new_item'           => __('New Comparator request'),
        'all_items'          => __('All Comparator requests'),
        'view_item'          => __('View Comparator request'),
        'search_items'       => __('Search Comparator requests'),
        'not_found'          => __('No Comparator requests found'),
        'not_found_in_trash' => __('No Comparator requests found in the Trash'),
        'parent_item_colon'  => '',
        'menu_name'          => 'Comparator requests'
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'   => true,
        'rewrite'       => array('slug' => 'custom_post'),
    );

    register_post_type('comparator_request', $args);
}
add_action('init', 'custom_post_type');