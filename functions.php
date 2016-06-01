<?php

if (!defined('WPINC'))
    die();

use Dreamery\WP\Settings;
// use ...

require get_template_directory() . '/vendor/autoload.php';
require get_template_directory() . '/vendor/dreamery/autoload.php';


function origin_theme_features()  {

    $custom_header_args = array(
        'default-image'          => get_template_directory_uri() . '/assets/img/origin4-logo.png',
        'width'                  => 300,
        'height'                 => 200,
        'flex-width'             => true,
        'flex-height'            => false,
        'uploads'                => true,
        'random-default'         => false,
        'header-text'            => false,
        'default-text-color'     => '',
        'wp-head-callback'       => '',
        'admin-head-callback'    => '',
        'admin-preview-callback' => '',
    );
    add_theme_support('custom-header', $custom_header_args);

    /*
     * Add support for HTML5 Semantic Markup
     *
     * XXX
     * Do we need extra CSS for this? <figure> ? <figcaption> ?
     */
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'origin_theme_features');

//require(get_template_directory() . '/vendor/leafo/scssphp/scss.inc.php');
function origin_style_loader_filter($src) {
    static $i = 0;

    list($path) = explode('?', $src);
    if (substr($path, -4, 4) == 'scss') {

        $cache_dir = ABSPATH . '/wp-content/cache/';
        if (!is_dir($cache_dir))
            mkdir($cache_dir);

        $scss_dirs = array();
        if (get_stylesheet_directory() != get_template_directory()) {
            $scss_dirs[] = get_stylesheet_directory() . '/vendor/twbs/bootstrap/scss';
            $scss_dirs[] = get_template_directory() . '/vendor/twbs/bootstrap/scss';
            $scss_dirs[] = get_stylesheet_directory() . '/assets/scss';
            $scss_dirs[] = get_template_directory() . '/assets/scss';
            $scss_dirs[] = get_stylesheet_directory() . '/';
            $scss_dirs[] = get_template_directory() . '/';
        } else {
            $scss_dirs[] = get_stylesheet_directory() . '/vendor/twbs/bootstrap/scss';
            $scss_dirs[] = get_stylesheet_directory() . '/assets/scss';
            $scss_dirs[] = get_stylesheet_directory() . '/';
        }

        $compiler = new Leafo\ScssPhp\Compiler();
        $compiler->setImportPaths($scss_dirs);

        $compiler->registerFunction('dreamerysetting', function($args) {
            //throw new Exception();
            return Dreamery\WP\Settings::getInstance()->$args[0][2][0];
        });

        $source = file_get_contents($path);
        $scss = $compiler->compile($source);

        $fileid = 'dreamery-sass-' . $i;
        file_put_contents($cache_dir . '/' . $fileid . '.css', $scss);
        wp_enqueue_style($fileid, site_url('/wp-content/cache/' . $fileid . '.css'));
        $i++;

        return null;
    }

    return $src;
}
add_filter('style_loader_src', 'origin_style_loader_filter');

if (!function_exists('origin_enqueue_assets')) {
    function origin_enqueue_assets() {
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/style.scss');
//        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/vendor/twbs/bootstrap/scss/bootstrap.scss');
//        wp_enqueue_style('bootstrap4', '//v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css');
        wp_enqueue_style('dreamery-origin', get_template_directory_uri() . '/style.css', array('bootstrap4'));

        wp_enqueue_script('tether', '//raw.githubusercontent.com/HubSpot/tether/master/dist/js/tether.min.js');
        wp_enqueue_script('bootstrap4', '//v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js', array('jquery', 'tether'));
    }

    add_action('wp_enqueue_scripts', 'origin_enqueue_assets');
}

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
 * Setup settings
 */
$originSettingsDefaults = array(
    'origin_theme_layout' =>                  'boxed',                    // [boxed, fluid]
    'origin_theme_excerpt_length' =>          200,
    'origin_theme_title_separator' =>         '|',
    'origin_theme_analytics_gacode' =>        '',
    'origin_theme_injection_header' =>        '',
    'origin_theme_injection_bodyclose' =>     '',
    'origin_theme_compile_scss' =>            true,

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
// ------------------------
$originSettings = Settings::getInstance();
$originSettings->setDefaults($originSettingsDefaults);
$adminSettings = new Dreamery\WP\Admin\Settings;
function origin_get_setting($setting) {
    $settings = Settings::getInstance();
    return $settings->$setting;
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