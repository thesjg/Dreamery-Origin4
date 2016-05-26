<?php

if (!defined('WPINC'))
    die();

use Dreamery\WP\Settings;
// use ...

if (1) {
    require get_template_directory() . '/vendor/autoload.php';
    require get_template_directory() . '/vendor/dreamery/autoload.php';
}


/*
 * Setup settings
 */
$originSettingsDefaults = array(
    'origin_theme_layout' =>                  'boxed',                    // [boxed, fluid]
    'origin_theme_excerpt_length' =>          200,
    'origin_theme_title_separator' =>         '|',
    'origin_theme_analytics_gacode' =>        '',
    'origin_theme_injection_header' =>        '',
    'origin_theme_injection_bodyclose' =>     '',

    'origin_theme_font_scheme' =>             'bootstrap',                // [bootstrap, other1, other2]
    'origin_theme_font_family_base' =>        '-apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif',
    'origin_theme_font_size_base' =>          '16px',
    'origin_theme_font_size_h1' =>            '2.5rem',
    'origin_theme_font_size_h2' =>            '2rem',
    'origin_theme_font_size_h3' =>            '1.75rem',
    'origin_theme_font_size_h4' =>            '1.5rem',
    'origin_theme_font_size_h5' =>            '1.25rem',
    'origin_theme_font_size_h6' =>            '1rem',
    'origin_theme_line_height_base' =>        '1.5',
    'origin_theme_line_height_heading' =>     '1.1',

    'origin_theme_color_scheme' =>            'bootstrap',                // [bootstrap, light, dark]
    'origin_theme_color_header_background' => '#fff',
    'origin_theme_color_body_background' => '#fff',
    'origin_theme_color_footer_background' => '#fff',
    'origin_theme_color_text' => '#373a3c',
    'origin_theme_color_heading' => '#373a3c',
    'origin_theme_color_brand_primary' => '#0275d8',
    'origin_theme_color_brand_success' => '#5cb85c',
    'origin_theme_color_brand_info' => '#5bc0de',
    'origin_theme_color_brand_warning' => '#f0ad4e',
    'origin_theme_color_brand_danger' => '#d9534f',
    'origin_theme_color_brand_inverse' => '#373a3c',
);

$originSettingsSchemeFonts = array();
$originSettingsSchemeFonts['other1'] = array();

$originSettingsSchemeColors = array();
$originSettingsSchemeColors['origin4'] = array();

// ------------------------
$originSettings = Settings::getInstance();
$originSettings->setDefaults($originSettingsDefaults);



// ---------------------
function origin_settings_section_render() {
    $options = array();

    $t = new \Dreamery\Template();
    echo $t->render('settings-section', $options);
}
function origin_admin_options_render() {
    $options = array(
        'has_tabs' => false,
        'tabs' => array(
        ),
        
    );

    $t = new \Dreamery\Template();
    echo $t->render('settings', $options);
}
add_action('admin_menu', 'origin_admin_menu');
function origin_admin_menu() {
    add_theme_page('Origin4 Options', 'Origin4 Options', 'edit_theme_options', 'origin-theme-settings', 'origin_admin_options_render');
}
add_action('admin_init', 'origin_register_settings');
function origin_register_settings() {

    register_setting('origin_theme_settings', 'origin_theme_settings', 'origin_theme_options_validate');

    add_settings_section('origin_settings_theme', 'General Settings', 'origin_settings_section_render', 'origin');
    add_settings_section('origin_settings_theme_font', 'Font Settings', 'origin_settings_section_render', 'origin');
    add_settings_section('origin_settings_theme_color', 'Color Settings', 'origin_settings_section_render', 'origin');

    add_settings_field('origin_theme_excerpt_length', 'Excerpt Length', function($args){
        $options = array();
        $t = new \Dreamery\Template();
        echo $t->render('settings-field-input', $options);

        return Settings::getInstance()->origin_theme_excerpt_length;
    }, 'origin', 'origin_settings_theme');
}







function origin_enqueue_styles() {
    //wp_enqueue_style('bootstrap', get_template_directory_uri() . '/vendor/twbs/bootstrap/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap4', '//v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css');
    wp_enqueue_style('dreamery-origin', get_template_directory_uri() . '/style.css', array('bootstrap4'));

    wp_enqueue_script('tether', '//raw.githubusercontent.com/HubSpot/tether/master/dist/js/tether.min.js');
    wp_enqueue_script('bootstrap4', '//v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js', array('jquery', 'tether'));
}
add_action('wp_enqueue_scripts', 'origin_enqueue_styles');

if (!function_exists('origin_register_navigation')) {
    function origin_register_navigation() {
        $locations = array(
            'origin_navigation_menu_primary' =>     __('Primary Menu', 'origin4'),
            'origin_navigation_menu_secondary' =>   __('Secondary Menu', 'origin4'),
            'origin_navigation_menu_tertiary' =>    __('Tertiary Menu', 'origin4'),
            'origin_navigation_menu_footer1' =>     __('Footer Menu #1', 'origin4'),
            'origin_navigation_menu_footer2' =>     __('Footer Menu #2', 'origin4'),
        );
        register_nav_menus($locations);
    }
    add_action('init', 'origin_register_navigation');
}

/*
 * Turn various WordPress knobs based on theme settings
 */
function origin_filter_excerpt_length() {
    $settings = Settings::getInstance();
    return $settings->origin_theme_excerpt_length;
}
add_filter('excerpt_length', 'origin_filter_excerpt_length');

function origin_filter_document_title_separator($sep) {
    $settings = Settings::getInstance();
    $o_sep = $settings->origin_theme_title_separator;

    if (is_singular('post')) {
        $sep = $o_sep;
    }
    return $sep;
}
add_filter('document_title_separator', 'origin_filter_document_title_separator', 10, 1);