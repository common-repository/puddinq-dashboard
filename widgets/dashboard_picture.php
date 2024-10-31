<?php

/**
 * Add a Picture widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function example_add_dashboard_widgets() {
    add_meta_box(
        'puddinq_picture_widget',
        'Puddinq picture widget',
        'puddinq_picture_widget_function',
        'dashboard',
        'side',
        'high');

}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets', 510 );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function puddinq_picture_widget_function() {

    $options = get_option('puddinq_dashboard');
    
    if (isset($options['images_dash']) && $options['images_dash'] != '') {
        $image = $options['images_dash'];

    } else {
        $image = PUDASHPLUGINDIRURL . 'img/sunset.jpg';
    }

    echo '<img src="' . $image .'" style="max-width:100%;max-height:100%">';
    // Display whatever it is you want to show.
}