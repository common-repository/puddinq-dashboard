<?php
/**
 * Seperate functions that are not directly
 * hooked in wordpress.
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}



/**
 * Outputs the html and content for the admin page.
 *
 * Called bij pu_dash_settings_page() in init.php it outputs
 * the settings page.
 */


function pu_dash_settings_page_template()
{
    echo '<div class="wrap">';

    echo '<h1>';
    echo esc_html(get_admin_page_title());
    echo '</h1>';

    echo '<form action="options.php" method="post">';

    settings_fields('puddinq_dashboard');
    puddinq_do_settings_sections('puddinq-dashboard');

    submit_button();

    echo '</form>';
    echo '</div>';
}



/**
 * Outputs the checkbox fields dynamically.
 *
 * Prints a formfield for every key pair in the multi array
 *
 * @param $args
 */


function puddinq_dashboard_multi_on_off($args) {
    $compare = get_option( 'puddinq_dashboard' );

    echo '<div class="puddinq-left">';
    echo '<b>' . $args['boldtext'] . '</b><br>';
    echo '<i>' . $args['text'] . '</i><br>';
    echo '</div>';

    $set = strtok(key($args['multi']), "_");
    $allset  = (isset($compare[$set])) ? 'checked' : '';
    echo '<div class="puddinq-right">';
    echo '<input type="checkbox" name="puddinq_dashboard[' . $set . ']" id="select-all-' . $set . '" ' . $allset . '><span class="select-all" id="'.$set.'">';
    echo __('(de)select all','puddinq-dash') . '<br>';

    foreach($args['multi'] as $singleoption => $name) {

        $activate = (isset($compare[$singleoption])) ? 'checked' : '';
        echo '<input type="checkbox" name="puddinq_dashboard[' . $singleoption . ']" id="' . $singleoption . '" ' . $activate . '> ';
        echo '<b>' . $name . '</b><br>';

    }
    echo '</div>';
}



/**
 * Outputs the image uploader fields dynamically.
 *
 * Prints a form field for every key the multi array additional
 * settings are set in the array (look at register settings)
 *
 * @param $args
 */


function puddinq_dashboard_select_image($args) {
    $compare = get_option( 'puddinq_dashboard' );

    echo '<div class="puddinq-left">';
    echo '<b>' . $args['boldtext'] . '</b><br>';
    echo '<i>' . $args['text'] . '</i><br>';
    echo '</div>';

    echo '<div class="puddinq-right">';

    wp_enqueue_media();

    foreach($args['multi'] as $image => $info) {
        $imgurl = isset($compare[$image]) && $compare[$image] != '' ? $compare[$image] : $info['default'];
        echo '<div id="upload-' . $image . '" style="display: none;"><b>' . $info['text'] . '</b><i>(' . $info['size'] . ')</i><br>';
        echo '<div class="image-preview-wrapper" id="show_upload_' . $image . '_button" style="display:inline-block;vertical-align:bottom;;">';
		echo '<img id="image-preview" src="' . $imgurl . '">';
		$sort = explode(' ', $info['text']);
		$sort = $sort[count($sort) -1];
	    echo '</div>';
        echo '<input type="text" name="puddinq_dashboard[' . $image . ']" id="puddinq_dashboard[' . $image . ']" value="'. $imgurl . '">';
	    echo '<input id="upload_' . $image . '_button"  name="upload_' . $image . '_button" type="button" class="image_button" value="' . __('Choose ', 'puddinq-dash') . $sort . '" /></div><br>';
    }

    echo '</div>';
}



/**
 * Outputs the change color fields dynamically.
 *
 * Prints a form field for every key the multi array additional
 * settings are set in the array (look at register settings)
 *
 * @param $args
 */


function puddinq_dashboard_change_colors($args) {
    $compare = get_option( 'puddinq_dashboard' );

    echo '<div class="puddinq-left">';
    echo '<b>' . $args['boldtext'] . '</b><br>';
    echo '<i>' . $args['text'] . '</i><br>';
    echo '</div>';

    echo '<div class="puddinq-right">';
    echo '<div id="puddinq-color-picker-reset">reset</div>';
    wp_enqueue_media();

    foreach($args['multi'] as $color => $info) {
        $cval = isset($compare[$color]) ? $compare[$color] : $info['default'];
        echo '<div class="puddinq-change-colors" id="puddinq_' . $color . '" style="display: none;">'
            . '<input type="text" class="puddinq-color-picker" name="puddinq_dashboard[' . $color . ']" id="' . $color . '" '
            . 'value="' . $cval . '" data-default-color="' . $info['default'] . '">'
            . '<b>' . $info['text'] . '</b><i>(' . $info['size'] . ')</i><br></div>';

    }

    echo '</div>';
}



/**
 * Outputs the select block widgets dynamically.
 *
 * Prints a formfield for every key pair in the widget array
 *
 * @param $args
 */

function puddinq_dashboard_multi_block_on_off($args) {
    $compare_ser = get_option( 'puddinq_dashboard_widg_block' );
    $compare = maybe_unserialize($compare_ser);

    echo '<div class="puddinq-left">';
    echo '<b>' . $args['boldtext'] . '</b><br>';
    echo '<i>' . $args['text'] . '</i><br>';
    echo '</div>';

    $set = 'dashboard';
    $allset  = (isset($compare[$set])) ? 'checked' : '';
    echo '<div class="puddinq-right">';
    echo '<input type="checkbox" name="puddinq_dashboard_widg_block[' . $set . ']" id="select-all-' . $set . '" ' . $allset . '><span class="select-all" id="'.$set.'">';
    echo __('(de)select all','puddinq-dash') . '<br>';

    if ($args['multi']) {
        foreach ($args['multi'] as $singleoption => $name) {
            $activate = (isset($compare[$singleoption])) ? 'checked' : '';
            echo '<input type="checkbox" name="puddinq_dashboard_widg_block[' . $singleoption . ']" id="' . $singleoption . '" ' . $activate . '> ';
            echo '<b>' . $name['title'] . '</b><br>';
        }
    }
    echo '</div>';
}



/**
 * Ouputs the text for the section.
 *
 * @param $args
 */


function puddinq_dashboard_intro($args)
{
    do_action('puddinq_admin_notices');
    echo '<p>' . __( 'General settings, widgets, themes, colors and more.', 'puddinq-dash'  ) . '</p>';
}



/**
 * Throws an error (notice) if POST data is wrong.
 *
 * The checkboxes can only send 'on' as a value, if it is something
 * else the values will not be saved and a notice will fire.
 *
 * @param $out
 * @return mixed
 */



function puddinq_dashboard_test($out) {

    if ($out) {
        foreach ($out as $slug => $value) {

            if ($value != 'on' && esc_url_raw($value) == '' && $value != '') {
                add_settings_error(
                    $slug,
                    esc_attr('settings_updated'),
                    $slug . ': ' . esc_url($value) . __(' could not be passed as creditable', 'puddinq-dash'),
                    'error'
                );
            }
        }
    }
    return $out;
}



/**
 * custom do_settings_sections.
 *
 * Put settings in a div instead of table
 *
 * @param $page
 */


function puddinq_do_settings_sections( $page ) {
    global $wp_settings_sections, $wp_settings_fields;

    if ( ! isset( $wp_settings_sections[$page] ) )
        return;

    foreach ( (array) $wp_settings_sections[$page] as $section ) {
        if ( $section['title'] )
            echo "<h2>{$section['title']}</h2>\n";

        if ( $section['callback'] )
            call_user_func( $section['callback'], $section );

        if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
            continue;
        echo '<div class="puddinq-form-settings">';
        puddinq_do_settings_fields( $page, $section['id'] );
        echo '</div>';
    }
}



/**
 * custom do_settings_fields.
 *
 * Puts the settings in divs instead of a tr td
 *
 * @param $page
 * @param $section
 */


function puddinq_do_settings_fields($page, $section) {
    global $wp_settings_fields;

    if ( ! isset( $wp_settings_fields[$page][$section] ) )
        return;

    foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {

        $class = '';

        if ( ! empty( $field['args']['class'] ) ) {
            $class = ' class="pudash-block ' . esc_attr( $field['args']['class'] ) . '"';
        }

        echo "<div{$class}>";

        if ( ! empty( $field['args']['label_for'] ) ) {
            echo '<div class="puddinq-label" id="' . $field['id'] . '"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></div>';
        } else {
            echo '<div class="puddinq-label">' . $field['title'] . '</div>';
        }

        echo '<div class="puddinq-inputs">';
        call_user_func($field['callback'], $field['args']);
        echo '</div>';
        echo '</div>';
    }
}


