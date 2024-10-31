<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

function get_nf_widgets()
{
    global $pu_widgets;

    $widgetFolder = PUDASHDIR . 'widgets/';
    $files = array_diff(scandir($widgetFolder), array('.', '..'));
    $filesOut = array();

    foreach ($files as $file) {
        $name = str_replace('.php', '', $file);
        $filesOut[$name] = ucfirst($name);
    }

    $pu_widgets = $filesOut;

    return $filesOut;
}

function load_widgets()
{
    $widgets = get_nf_widgets();

    $use = get_option('puddinq_dashboard');

    if ($use) {
        foreach ($use as $widget => $setting) {
            if (strpos($widget, 'dashboard_')  !== false) {
                include_once(PUDASHDIR . 'widgets/' . $widget . '.php');
            }
        }
    }
}

if (is_admin()) {
    load_widgets();
}

