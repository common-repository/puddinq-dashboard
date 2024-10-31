<?php
/*
Plugin Name: Puddinq Dashboard
Plugin URI:  https://wordpress.org/plugins/puddinq-dashboard/
Description: Show hide admin dashboard widgets
Version:     0.1.1
Author:      Stefan Schotvanger
Author URI:  http://www.puddinq.nl/wip/stefan-schotvanger/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: puddinq-dash
Domain path: /lang/
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

// constants
define('PUDASHPLUGIN', __FILE__);
define('PUDASHDIR', plugin_dir_path(__FILE__));
define('PUDASHPLUGINDIRURL', plugin_dir_url(__FILE__));
define('PUDASHNAME', dirname( plugin_basename(__FILE__)));



/**
 * Load puddinq-dash language files
 */

function puddinq_dash_load_textdomain() {
    load_plugin_textdomain( 'puddinq-dash', false, PUDASHNAME  . '/lang/' );

}

add_action('plugins_loaded', 'puddinq_dash_load_textdomain');



/**
 * Require the functions and settings.
 *
 * The init file holds the basic plugin actions, the settings file everything
 * needed for the settings. Functions holds all actions used in both.
 *
 */
require_once PUDASHDIR . 'includes/functions.php';
require_once PUDASHDIR . 'includes/init.php';
require_once PUDASHDIR . 'includes/settings.php';
require_once PUDASHDIR . 'includes/widgets.php';
