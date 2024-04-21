<?php
Class Comparator_ajax {

    function __construct() {

        // Send filter form
        add_action( 'wp_ajax_comparator_send_form', array($this, 'comparator_send_form_func') );
        add_action( 'wp_ajax_nopriv_comparator_send_form', array($this, 'comparator_send_form_func') );

        // Send filter results
        add_action( 'wp_ajax_comparator_send_form_filter', array($this, 'comparator_send_results_filter_func') );
        add_action( 'wp_ajax_nopriv_comparator_send_form_filter', array($this, 'comparator_send_results_filter_func') );

    }

    // First form
    function comparator_send_form_func() {

        /*
        error_reporting( E_ALL );
        ini_set( 'display_errors', 1 );
        ini_set( 'log_errors', 1 );
        */
        
        $search = new Comparator_search( $_POST );
        $result['result'] = $search->submit();
        $result['request'] = $_POST;

        echo do_shortcode('[comparator_search_results]'.json_encode($result).'[/comparator_search_results]');
        wp_die();
    }

    // Results filter form
    function comparator_send_results_filter_func() {

        error_reporting( E_ALL );
        ini_set( 'display_errors', 1 );
        ini_set( 'log_errors', 1 );

        $search = new Comparator_search( $_POST['filter'], $_POST['sorting'] );

        global $result;
        $result = $search->submit();
        
        do_shortcode('[comparator_search_results_list]'.json_encode($result['items']).'[/comparator_search_results_list]');
        wp_die();

    }

}

new Comparator_ajax;



add_action('wp_ajax_save_form_data', 'save_form_data');
add_action('wp_ajax_nopriv_save_form_data', 'save_form_data');

function save_form_data() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_form_data';

    // Sanitize and validate form data
    $data = array(
        'first_name' => sanitize_text_field($_POST['firstName']),
        'last_name' => sanitize_text_field($_POST['lastName']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'address' => sanitize_text_field($_POST['address']),
        'operator' => sanitize_text_field($_POST['operator']),
        'preferences' => isset($_POST['installationPreferences']) ? implode(', ', $_POST['installationPreferences']) : ''
    );

    // Validate phone and email again
    $phone_regex = '/^\d{10}$/'; // Adjust the regex according to your phone number format
    $email_regex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

    if (!preg_match($phone_regex, $data['phone']) || !preg_match($email_regex, $data['email'])) {
        wp_send_json_error('Invalid phone number or email address.');
    }

    // Insert data into custom table
    $wpdb->insert($table_name, $data);

    // Create a WordPress post of type 'comparator-request'
    $post_data = array(
        'post_title' => $data['first_name'] . ' ' . $data['last_name'],
        'post_type' => 'comparator-request',
        'post_status' => 'publish'
        // Add more post fields as needed
    );

    $post_id = wp_insert_post($post_data);

    if (!$post_id) {
        wp_send_json_error('Error creating WordPress post.');
    }

    // Update post meta for each field
    foreach ($data as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

    // Generate a token for the post
    $token = md5($post_id . time()); // You can use a more secure method for generating tokens

    // Update the post meta with the generated token
    update_post_meta($post_id, '_token', $token);

    // Return the token to the client side
    wp_send_json_success(array('token' => $token));
}