<?php

if (!defined('WPINC'))
    die();

use Dreamery\WP\Settings;
// use ...

require get_template_directory() . '/vendor/autoload.php';
require get_template_directory() . '/vendor/dreamery/autoload.php';

/**
 * Max content width (max image width)
 * Rationale: based on the width of a .col-lg-8
 */
if (!isset($content_width)) {
    $content_width = 760;
}

if (!function_exists('origin_theme_support_custom_header')) {
    function origin_theme_support_custom_header() {
        $args = array(
            'default-image' => get_template_directory_uri() . '/assets/img/origin4-logo.png',
            'width' => 300,
            'height' => 200,
            'flex-width' => true,
            'flex-height' => false,
            'uploads' => true,
            'random-default' => false,
            'header-text' => false,
            'default-text-color' => '',
            'wp-head-callback' => '',
            'admin-head-callback' => '',
            'admin-preview-callback' => '',
        );
        add_theme_support('custom-header', $args);
    }
    add_action('after_setup_theme', 'origin_theme_support_custom_header');
}

if (!function_exists('origin_theme_support_html5')) {
    function origin_theme_support_html5() {
        /*
         * Add support for HTML5 Semantic Markup
         *
         * XXX
         * Do we need extra CSS for this? <figure> ? <figcaption> ?
         */
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    }
    add_action('after_setup_theme', 'origin_theme_support_html5');
}

if (!function_exists('origin_theme_support_title_tag')) {
    function origin_theme_support_title_tag() {
        add_theme_support('title-tag');
    }
    add_action('after_setup_theme', 'origin_theme_support_title_tag');
}

if (!function_exists('origin_theme_support_custom_background')) {
    function origin_theme_support_custom_background()
    {
        $settings = Settings::getInstance();
        $args = array(
            'default-color' => $settings->getDefault('color_body_background'),
            'wp-head-callback' => function () {
            },
            'admin-head-callback' => function () {
            },
            'admin-preview-callback' => function () {
            },
        );
        add_theme_support('custom-background', $args);

        /*
         * Push custom theme background into Settings, if applicable
         *
         * XXX: Research the order in which this is done, after_setup_theme
         * probably isn't right (doesn't work in the Customizer)
         */
        $background = set_url_scheme(get_background_image());
        if (is_string($background)) {
            $settings->theme_background_image = $background;
        }
        $repeat = get_theme_mod('background_repeat');
        if (is_string($repeat)) {
            $settings->theme_background_repeat = $repeat;
        }
        $position = get_theme_mod('background_position');
        if (is_string($position)) {
            $settings->theme_background_position = $position;
        }
        $attachment = get_theme_mod('background_attachment');
        if (is_string($attachment)) {
            $settings->theme_background_attachment = $attachment;
        }
    }

    add_action('after_setup_theme', 'origin_theme_support_custom_background');
}

/*
 * Conditionally compile our own SCSS and the dependent bootstrap
 *
 * XXX: need to conditionally compile based on the file mtime's
 * XXX: need to conditionally compile based on whether or not a
 *      "compile scss" setting is enabled or disabled in the admin
 *      (to support other compilation plugins)
 * XXX: Y mod n, clear out the cache of previously compiled ..
 */
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
        $compiler->registerFunction('get_theme_mod', function($args) {
            return get_theme_mod($args[0][2][0]);
        });
        $compiler->registerFunction('get_template_directory_uri', function($args) {
            return get_template_directory_uri();
        });
        $compiler->registerFunction('get_stylesheet_uri', function($args) {
            return get_stylesheet_uri();
        });

        $source = file_get_contents($path);
        $scss = $compiler->compile($source);

        $fileid = 'dreamery-sass-' . $i;
        file_put_contents($cache_dir . '/' . $fileid . '.css', $scss);
//        wp_enqueue_style($fileid, site_url('/wp-content/cache/' . $fileid . '.css?t=' . time()));
        $i++;

        return site_url('/wp-content/cache/' . $fileid . '.css');
    }

    return $src;
}
add_filter('style_loader_src', 'origin_style_loader_filter');

/**
 * Customize gallery output
 *
 * This is based on the core WordPress function
 */
function dreamery_post_gallery($string, $attr){
    $post = get_post();

    if (!empty($attr['ids'])) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if (empty($attr['orderby'])) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    $defaults = array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'figure',
        'icontag'    => 'div',
        'captiontag' => 'figcaption',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => ''
    );
    $atts = shortcode_atts($defaults, $attr, 'gallery');

    $column_class = 'col-xs-4';
    switch ($atts['columns']) {
        /* Standard column widths */
        case 1:
            $column_class = 'col-xs-12';
            break;
        case 2:
            $column_class = 'col-xs-6';
            break;
        case 3:
            $column_class = 'col-xs-4';
            break;
        case 4:
            $column_class = 'col-xs-3';
            break;
        case 6:
            $column_class = 'col-xs-2';
            break;

        /* Non-standard widths */
        case 5:
            $column_class = 'origin-col-one-fifth';
            break;
        case 7:
            $column_class = 'origin-col-one-seventh';
            break;
        case 8:
            $column_class = 'origin-col-one-eighth';
            break;
        case 9:
            $column_class = 'origin-col-one-ninth';
            break;

        default:
            break;
    }

    $id = intval($atts['id']);

    if (!empty($atts['include'])) {
        $_attachments = get_posts(array('include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children(array('post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
    } else {
        $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
    }

    if (empty($attachments)) {
        return '';
    }

    if (is_feed()) {
        $output = "\n";
        foreach ($attachments as $att_id => $attachment) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $itemtag = tag_escape($atts['itemtag']);
    $captiontag = tag_escape($atts['captiontag']);
    $icontag = tag_escape($atts['icontag']);
    $valid_tags = wp_kses_allowed_html('post');
    if (!isset($valid_tags[$itemtag])) { $itemtag = $defaults['itemtag']; }
    if (!isset($valid_tags[$captiontag])) { $captiontag = $defaults['captiontag']; }
    if (!isset($valid_tags[$icontag])) { $icontag = $defaults['icontag']; }

    $columns = intval($atts['columns']);

    $size_class = sanitize_html_class($atts['size']);
    $gallery_div = "<div class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

    /**
     * Filters the default gallery shortcode CSS styles.
     *
     * @since 2.5.0
     *
     * @param string $gallery_style Default CSS styles and opening HTML div container
     *                              for the gallery shortcode output.
     */
    $output = apply_filters('gallery_style', $gallery_div);

    foreach ($attachments as $id => $attachment) {
        $attr = array();
        $attr['class'] = 'img-fluid attachment-' . $atts['size'] . ' size-' . $atts['size'];
        if (!empty($atts['link']) && 'file' === $atts['link']) {
            $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
        } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
            $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
        } else {
            $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
        }
        $image_meta  = wp_get_attachment_metadata($id);

        $orientation = '';
        if (isset($image_meta['height'], $image_meta['width'])) {
            $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
        }
        $output .= '<' . $itemtag . ' class="figure gallery-item ' . $column_class . '">';
        $output .= "
			<{$icontag} class='figure-img gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
        if ($captiontag && trim($attachment->post_excerpt)) {
            $output .= "
				<{$captiontag} class='figure-caption wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
    }

    $output .= "
		</div>\n";

    return $output;




}
add_filter('post_gallery', 'dreamery_post_gallery', 10, 2);

/*
 * Filter out non-Page templates on WP versions before 4.7
 */
if (!function_exists('origin_theme_filter_templates')) {
    function origin_theme_filter_templates($post_templates) {
        if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
            array_filter($post_templates, function($template_path){
                $match = 'templates/page';
                if (strncmp($template_path, $match, strlen($match))) {
                    return true;
                }
                return false;
            });
        }
    }
    add_filter('theme_page_templates', 'origin_theme_filter_templates');
}

/*
 * Enqueue our css/js assets
 */
if (!function_exists('origin_enqueue_assets')) {
    function origin_enqueue_assets() {
        wp_enqueue_style('bootstrap4', get_template_directory_uri() . '/style.scss');
        wp_enqueue_style('dreamery-origin4', get_template_directory_uri() . '/style.css', array('bootstrap4'));

        wp_enqueue_script('bootstrap4', get_template_directory_uri() . '/vendor/twbs/bootstrap/dist/js/bootstrap.min.js');
        wp_enqueue_script('tether', '//raw.githubusercontent.com/HubSpot/tether/master/dist/js/tether.min.js');
    }

    add_action('wp_enqueue_scripts', 'origin_enqueue_assets');
}

/*
 * Add img-fluid to all WP-managed images
 */
function origin_image_tag_class($class){
    return $class . ' img-fluid';
}
add_filter('get_image_tag_class','origin_image_tag_class');

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
    'compile_scss' =>            true,
);

if (get_stylesheet_directory() != get_template_directory()) {
    $origin_settings_override_filename = get_stylesheet_directory() . '/settings.php';
    if (file_exists($origin_settings_override_filename)) {
        require($origin_settings_override_filename);
    }
}

// ------------------------
$originSettings = Settings::getInstance();
$originSettings->setDefaults($originSettingsDefaults, false);
function origin_get_setting($setting) {
    $settings = Settings::getInstance();
    return $settings->$setting;
}

new Dreamery\WP\Admin\Settings;
new Dreamery\WP\Admin\Customizations;
new Dreamery\WP\WooCommerceSupport;

/*
 * Turn various WordPress knobs based on theme settings
 */
function origin_filter_excerpt_length() {
    $settings = Settings::getInstance();
    return $settings->theme_excerpt_length;
}
add_filter('excerpt_length', 'origin_filter_excerpt_length');

function origin_filter_document_title_separator($sep) {
    $settings = Settings::getInstance();
    $o_sep = $settings->theme_title_separator;

    if (is_singular('post')) {
        $sep = $o_sep;
    }
    return $sep;
}
add_filter('document_title_separator', 'origin_filter_document_title_separator', 10, 1);