<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 30/12/2016
 * Time: 22:04
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 * Registers the settings for the puddinq-dashboard settingspage.
 *
 * This function holds 99% of the info for the settingsform. Settings
 * can easily be added using the $args.
 * - boldtext - bold text above selectbox
 * - text - italic text under bold text
 * - multi - array with key is unique identifier and value is label
 */


function puddinq_dash_register_settings()
{
    add_settings_section(
        'puddinq_dash_settings',
        __('Main settings', 'puddinq-dash'),
        'puddinq_dashboard_intro', // 3.1
        'puddinq-dashboard'
    );

    $widg_ser_array = get_option('puddinq_dashboard_widg_array');
    $widg_array = unserialize($widg_ser_array);

    add_settings_field(
        'puddinq_dashboard_widgets',
        __('Hide basic widgets', 'puddinq-dash'),
        'puddinq_dashboard_multi_block_on_off', // 1.1
        'puddinq-dashboard',
        'puddinq_dash_settings',
        array(
            'class' => 'pudash-widgets',
            'boldtext' => __('Show/Hide widgets', 'puddinq-dash'),
            'text' => __('Select to hide', 'puddinq-dash'),
            'multi' => $widg_array
        )
    );

    register_setting( 'puddinq_dashboard', 'puddinq_dashboard_widg_block', 'puddinq_dashboard_test' );


    $pudd_widg_array = array(
        'dashboard_picture' => __('Dasboard picture', 'puddinq-dash')
    );

    add_settings_field(
        'puddinq_dashboard_puddinq_widgets',
        __('Show puddinq widgets', 'puddinq-dash'),
        'puddinq_dashboard_multi_on_off', // 1.1
        'puddinq-dashboard',
        'puddinq_dash_settings',
        array(
            'boldtext' => __('Show puddinq widgets', 'puddinq-dash'),
            'text' => __('Select to activate', 'puddinq-dash'),
            'class' => 'pudash-pudd',
            'multi' => $pudd_widg_array
        )
    );


    add_settings_field(
        'puddinq_dashboard_misc',
        __('Optical misc', 'puddinq-dash'),
        'puddinq_dashboard_multi_on_off', // 1.2
        'puddinq-dashboard',
        'puddinq_dash_settings',
        array(
            'boldtext' => __('Change logo, colors and more', 'puddinq-dash'),
            'text' => __('Select to activate', 'puddinq-dash'),
            'class' => 'pudash-misc',
            'multi' => array(
                'extras_logo' => __('Change logo', 'puddinq-dash'),
                'extras_color' => __('Change colors', 'puddinq-dash'),
            )
        )
    );


    add_settings_field(
        'puddinq_dashboard_images',
        __('Set images', 'puddinq-dash'),
        'puddinq_dashboard_select_image', // 1.2
        'puddinq-dashboard',
        'puddinq_dash_settings',
        array(
            'class' => 'pudash-images',
            'boldtext' => __('Select an image', 'puddinq-dash'),
            'text' => __('Works if action is activated', 'puddinq-dash'),
            'multi' => array(
                'images_logo' => array(
                    'text' => __('Change logo', 'puddinq-dash'),
                    'size' => '30px x 30px',
                    'default' => PUDASHPLUGINDIRURL . 'img/smile.png'
                ),
                'images_dash' => array(
                    'text' => __('Change Dashboard Image', 'puddinq-dash'),
                    'size' => '780px x 780px',
                    'default' => PUDASHPLUGINDIRURL . 'img/sunset.jpg'
                )
            )
        )
    );

    add_settings_field(
        'puddinq_dashboard_colors',
        __('Change colors', 'puddinq-dash'),
        'puddinq_dashboard_change_colors', // 1.2
        'puddinq-dashboard',
        'puddinq_dash_settings',
        array(
            'class' => 'pudash-colors',
            'boldtext' => __('Select a color', 'puddinq-dash'),
            'text' => __('Works if change colors is activated', 'puddinq-dash'),
            'multi' => array(
                'color_background' => array(
                    'text' => __('Background color', 'puddinq-dash'),
                    'size' => '30px x 30px',
                    'default' => '#FFA500'
                ),
                'color_heads' => array(
                    'text' => __('Block heads', 'puddinq-dash'),
                    'size' => '780px x 780px',
                    'default' => '#ffd700'
                ),
                'color_block' => array(
                    'text' => __('Block background', 'puddinq-dash'),
                    'size' => '780px x 780px',
                    'default' => '#fff'
                ),
                'color_text' => array(
                    'text' => __('Text', 'puddinq-dash'),
                    'size' => '780px x 780px',
                    'default' => '#000'
                )
            )
        )
    );


    register_setting( 'puddinq_dashboard', 'puddinq_dashboard', 'puddinq_dashboard_test' );

}

add_action('admin_init', 'puddinq_dash_register_settings');

