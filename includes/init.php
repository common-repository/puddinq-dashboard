<?php

// if called direct die
defined( 'ABSPATH' ) or die( 'Go home! ;)' );



/**
 * Redirect to dashboard after activation.
 *
 * For the settings the dashboard needs to be loaded once.
 * That's why after plugin activation the dashboard is loaded.
 * All loaded widgets are then registered and can be blocked
 * in the settings page.
 *
 * @param $plugin
 *
 */


function puddinq_dash_redirect( $plugin ) {
    if( $plugin == plugin_basename( PUDASHPLUGIN ) ) {
        exit( wp_redirect( admin_url( 'index.php' ) ) );
    }
}
add_action( 'activated_plugin', 'puddinq_dash_redirect' );



/**
 * Load styles and scripts.
 *
 * - Load on dashboard of 'Change colors' settings are on.
 * - Always load on settings page
 */

function pu_dash_style() {

    $screen = get_current_screen();
    $options = get_option('puddinq_dashboard');
    // load only on settingsscreen (and dashboard if active)

    if (($screen->id == 'settings_page_puddinq-dashboard') || (isset($options['extras_color']) && $screen->id == 'dashboard'))  {


        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script('puddinq-script', PUDASHPLUGINDIRURL . 'js/script.js', array( 'thickbox', 'media-upload', 'wp-color-picker' ), '0.0.7', true);

        wp_enqueue_style('puddinq-dash', PUDASHPLUGINDIRURL . 'css/style.css', '', '0.0.7');

    }
}
add_action( 'admin_enqueue_scripts', 'pu_dash_style' );



/**
 * Load html putput styles.
 *
 * - Load on dashboard of 'Change colors' settings are on.
 * - Always load on settingspage
 */


function pu_dash_basic_colors() {

    $screen = get_current_screen();
    $options = get_option('puddinq_dashboard');

    if (($screen->id == 'settings_page_puddinq-dashboard') || (isset($options['extras_color']) && $screen->id == 'dashboard'))  {
        $style = <<<STYLE_OUT
        <style type="text/css">

            #wpwrap, body {
                background-color: {$options['color_background']}!important;
                color: {$options['color_text']};
            }

            body.puddinq-dashboard div.postbox > h2,
            .puddinq-label {
                background-color: {$options['color_heads']}!important;
            }
            body.puddinq-dashboard div.postbox,
            .puddinq-inputs {
                background-color: {$options['color_block']}!important;
            }
STYLE_OUT;

        echo $style;
    }

}


add_action('admin_enqueue_scripts', 'pu_dash_basic_colors');
/**
 * Run at the dashboard to collect all widgets.
 *
 * Collects every widget in an option to be selected in the
 * settingsscreen.
 *
 */

function pu_dash_get_widgets()
{
    global $wp_meta_boxes;

    $screen = get_current_screen();

        if ($screen->id == 'dashboard') {

            $dash_adm_ser_array = get_option('puddinq_dashboard_widg_array');
            $dash_adm_array = maybe_unserialize($dash_adm_ser_array);
            $dash_adm_array = is_array($dash_adm_array) ? $dash_adm_array : array();

            foreach ($wp_meta_boxes['dashboard'] as $place => $meta_boxes) {
                foreach ($meta_boxes as $meta) {
                    foreach ($meta as $meta_box => $value) {
                        if (isset($value['id'])) {
                            $dash_adm_array[$value['id']] = ['title' => $value['title'], 'id' => $value['id'], 'place' => $place];
                        }
                    }
                }
            }

            update_option('puddinq_dashboard_widg_array', serialize($dash_adm_array));
        }
}

add_action('wp_dashboard_setup', 'pu_dash_get_widgets', 200);



/**
 * Run at the dashboard to block widgets.
 *
 * Collects every widget from the settingsscreen and blocks it
 *
 */


function pu_dash_block_widgets()
{
    $options = get_option('puddinq_dashboard_widg_block');
    $dash_adm_ser_array = get_option('puddinq_dashboard_widg_array');
    $dash_adm_array = maybe_unserialize($dash_adm_ser_array);

    if ($options && $dash_adm_array) {
        foreach ($options as $option => $name) {
            if ('control_switch' != $option && array_key_exists($option, $dash_adm_array)) {
                remove_meta_box($option, 'dashboard', $dash_adm_array[$option]['place']);
            }
        }
    }
}

add_action('admin_init', 'pu_dash_block_widgets');



/**
 * Adds settingslink to plugin on WordPress plugins overview
 *
 * @param $links
 * @param $file
 * @return array
 */


function pu_dash_PluginRowMeta($links, $file)
{
    if (strpos($file, 'puddinq-dashboard.php') !== false) {
        $newLinks = array(
            '<a href="' . admin_url('admin.php?page=puddinq-dashboard') . '">Settings</a>'
        );

        $links = array_merge($links, $newLinks);
    }

    return $links;
}

add_filter('plugin_row_meta', 'pu_dash_PluginRowMeta', 10, 2);



/**
 * Adds an extra class to the admin body.
 *
 * If activated in settings this function adds an extra class to the body.
 * The extra class triggers the puddinq-dashboard styling on the dashboard.
 *
 * @param $classes
 * @return string
 */

function puddinq_add_admin_body_class( $classes ) {

    $options = get_option('puddinq_dashboard');

    if (isset($options['extras_color'])) {
        $classes .= " puddinq-dashboard";
    }
    return $classes;
}

add_filter( 'admin_body_class', 'puddinq_add_admin_body_class' );



/**
 * Add puddinq admin dashboard settings page.
 *
 * Registers the page and appoints the function.
 */


function pu_dash_settings_page()
{
    add_options_page(
        __('Puddinq Dashboard Settings', 'nf-dashboard'),
        __('Puddinq Dashboard', 'nf-dashboard'),
        'manage_options',
        'puddinq-dashboard',
        'pu_dash_settings_page_template');

}

add_action('admin_menu', 'pu_dash_settings_page');



/**
 * Removes the wordpress logo and menu from admin bar.
 *
 * - Only in the admin bar
 * - The logo is replaced
 * - The submenu is removed
 */

function puddinq_remove_logo()
{
    $options = get_option('puddinq_dashboard');
    
    if (isset($options['extras_logo'])) {

        if (isset($options['images_logo'])) {
            $image = $options['images_logo'];
        } else {
            $image = PUDASHPLUGINDIRURL . 'img/smile.png';
        }

        echo '
                <style type="text/css">
    
                
                #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                background-image: url(' . $image . ') !important;
                background-size: 20px 20px;
                background-position: 0 0;
                color:rgba(0, 0, 0, 0);
                }
                #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
                background-position: 0 0;
                }
                @media screen and (max-width: 782PX) {
                    #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                        background-size: 30px 30px;
                    }
                }
                </style>
            ';

        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('documentation');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');

        $args = array(
            'id' => 'wp-logo',
            'href' => 'https://www.newfountain.nl">'
        );
        $wp_admin_bar->add_node($args);

    }
}

add_action('wp_before_admin_bar_render', 'puddinq_remove_logo');


/**
 * Add filter to media uploaderscreen
 *
 * Change the button text
 */


function puddinq_media_options_setup() {
    global $pagenow;
    
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
        // Now we'll replace the 'Insert into Post Button' inside Thickbox
        add_filter( 'gettext', 'puddinq_thickbox_text'  , 1, 3 );
    }
}
add_action( 'admin_init', 'puddinq_media_options_setup' );

function puddinq_thickbox_text($translated_text, $text, $domain) {
    if ('Insert into Post' == $text) {
        $referer = strpos( wp_get_referer(), 'puddinq-dashboard' );
        if ( $referer != '' ) {
            return __('Set as image!', 'puddinq-dash' );
        }
    }
    return $translated_text;
}


/**
 * Get global messages ($puddinq_message) and return them.
 *
 * Hooked near the footer all errors that have been set will be added to the filter used
 * in the print message function: function pu_dash_massage($notices)
 *
 * @param $text
 * @return mixed
 */

function puddinq_push_messages($text)
{
    global $puddinq_message;

    $text = $puddinq_message;

    return $text;
}

add_filter('pu_dash_message', 'puddinq_push_messages', 10);



/**
 * Adds an error message to the global ($puddinq_message)
 *
 * Makes a sub array for every error in the global $puddinq_message.
 *
 * @param null $type
 * @param null $text
 */

function puddinq_add_messages($type = null, $text = null)
{
    if (!is_null($type) && !is_null($text)) {

        global $puddinq_message;
        $puddinq_message[] = array('type' => $type, 'text' => $text);

    }
}

/**
 * Print error messages on puddinq-dashboard settingspage.
 *
 * If arrays with messages are set in the global $puddinq_messages when the
 * footer runs, Print those messages, they will appear at the top.
 *
 * @param $notices
 */
function pu_dash_massage($notices) {

    if(has_filter('pu_dash_message')) {
        $notices = apply_filters('pu_dash_message', '');
    }

    if (is_array($notices)) {
        foreach($notices as $notice) {
            echo '<div id="pu-dash-message" class="updated settings-' . $notice['type'] . ' ' . $notice['type']. ' is-dismissible">';
            echo "<p id='wp-admin-motivation'>{$notice['text']}</p>";
            echo "</div>";
        }
    }
}
add_action( 'admin_footer', 'pu_dash_massage' );

/**
 * activation.
 *
 */

function puddinq_dashboard_activation()
{

}

register_activation_hook(PUDASHPLUGIN, 'puddinq_dashboard_activation');

/**
 * Remove options on deactivation.
 *
 * Check if options exist and delete them if so
 */

function puddinq_dashboard_deactivation()
{
    $value = get_option('puddinq_dashboard');
    $notoptions = wp_cache_get( 'notoptions', 'options' );
    if ( !isset( $notoptions['puddinq_dashboard'] ) ) {
        delete_option('puddinq_dashboard');
    }
    if ( !isset( $notoptions['puddinq_dashboard_widg_block'] ) ) {
        delete_option('puddinq_dashboard_widg_block');
    }
    if ( !isset( $notoptions['puddinq_dashboard_widg_array'] ) ) {
        delete_option('puddinq_dashboard_widg_array');
    }
}

register_deactivation_hook(PUDASHPLUGIN, 'puddinq_dashboard_deactivation');




