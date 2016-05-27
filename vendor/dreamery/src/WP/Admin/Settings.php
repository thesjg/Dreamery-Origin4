<?php


namespace Dreamery\WP\Admin;


class Settings
{
    private $defaults;
    private static $settings = array(
        'general' => array(
            'name' => 'General Settings',
            'keys' => array(
                'origin_theme_layout' => array('name' => 'Layout', 'type' => 'select', 'options' => array('boxed', 'fluid')),
                'origin_theme_excerpt_length' => array('name' => 'Excerpt Length', 'type' => 'number'),
                'origin_theme_title_separator' => array('name' => 'Title Separator', 'type' => 'text'),
                'origin_theme_analytics_gacode' => array('name' => 'Google Analytics UA Code', 'type' => 'text'),
                'origin_theme_injection_header' => array('name' => 'Header Extras (JS/CSS)', 'type' => 'textarea'),
                'origin_theme_injection_bodyclose' => array('name' => 'Before Body Close Extras', 'type' => 'textarea'),
//                'origin_theme_compile_scss' => array('name' => 'Compile SCSS', 'type' => 'boolean'),
            )
        ),
        'font' => array(
            'name' => 'Font Settings',
            'keys' => array(
                'origin_theme_font_family_base' => array('name' => 'Base Font Family', 'type' => 'text'),
                'origin_theme_font_size_base' => array('name' => 'Base Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h1' => array('name' => 'H1 Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h2' => array('name' => 'H2 Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h3' => array('name' => 'H3 Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h4' => array('name' => 'H4 Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h5' => array('name' => 'H5 Font Size', 'type' => 'number-units'),
                'origin_theme_font_size_h6' => array('name' => 'H6 Font Size', 'type' => 'number-units'),
                'origin_theme_line_height_base' => array('name' => 'Base Line Height', 'type' => 'number-units'),
                'origin_theme_line_height_heading' => array('name' => 'H1-H6 Line Height', 'type' => 'number-units'),
            )
        ),
        'color' => array(
            'name' => 'Color Settings',
            'keys' => array(
                'origin_theme_color_header_background' => array('name' => 'Header Background Color', 'type' => 'color'),
                'origin_theme_color_body_background' => array('name' => 'Body Background Color', 'type' => 'color'),
                'origin_theme_color_footer_background' => array('name' => 'Footer Background Color', 'type' => 'color'),
                'origin_theme_color_text' => array('name' => 'Text Color', 'type' => 'color'),
                'origin_theme_color_heading' => array('name' => 'H1-H6 Text Color', 'type' => 'color'),
                'origin_theme_color_brand_primary' => array('name' => 'Brand Primary Color', 'type' => 'color'),
                'origin_theme_color_brand_success' => array('name' => 'Brand Success Color', 'type' => 'color'),
                'origin_theme_color_brand_info' => array('name' => 'Brand Info Color', 'type' => 'color'),
                'origin_theme_color_brand_warning' => array('name' => 'Brand Warning Color', 'type' => 'color'),
                'origin_theme_color_brand_danger' => array('name' => 'Brand Danger Color', 'type' => 'color'),
                'origin_theme_color_brand_inverse' => array('name' => 'Brand Inverse Color', 'type' => 'color'),
            )
        ),
    );

    public function __construct()
    {
        add_action('admin_menu', __CLASS__ . '::registerMenus');
        add_action('admin_init', __CLASS__ . '::registerSettings');
    }


    public static function registerMenus() {

        add_theme_page('Origin4 Options', 'Origin4 Options', 'edit_theme_options', 'origin-theme-settings', __CLASS__ . '::renderOptions');
    }

    public static function registerSettings() {

        register_setting('origin_theme_settings', 'origin_theme_settings', __CLASS__ . '::validate');

        foreach (self::$settings as $section_name => $section_data) {
            add_settings_section('origin_settings_theme_' . $section_name, $section_data['name'], __CLASS__ . '::renderSection', 'origin');
            foreach ($section_data['keys'] as $setting_key => $setting_data) {
                add_settings_field($setting_key, $setting_data['name'], function($args) use ($setting_data, $setting_key){
                    self::renderField($setting_data, $setting_key, $args);
                }, 'origin', 'origin_settings_theme_' . $section_name);
            }
        }
    }

    public static function renderOptions() {
        $options = array(
            'has_tabs' => false,
            'tabs' => array(
            ),

        );

        $t = new \Dreamery\Template();
        echo $t->render('settings', $options);
    }

    public static function renderSection() {
        $options = array();

        $t = new \Dreamery\Template();
        echo $t->render('settings-section', $options);
    }

    public static function renderField($data, $field, $args) {
        $settings = \Dreamery\WP\Settings::getInstance();
        $options = array(
            'value'     =>      $settings->$field,
            'default'   =>      $settings->getDefault($field),
            'name'      =>      'origin_theme_settings[' . $field . ']',
        );
        if ($data['type'] == 'select') {
            $options['options'] = array();
            foreach ($data['options'] as $option) {
                $t_option['name'] = $option;
                $t_option['is_selected'] = false;
                if ($option == $options['default']) {
                    $t_option['is_selected'] = true;
                }
                $options['options'][] = $t_option;
            }
        } else {
            if ($options['value'] == $options['default']) {
                $options['placeholder'] = $options['value'];
                unset($options['value']);
            }
        }

        $t = new \Dreamery\Template();
        echo $t->render('settings-field-' . $data['type'], $options);
    }

    public static function validate($input) {
        $valid = array();
        foreach ($input as $skey => $sval) {
            $sval = trim($sval);
            if (empty($sval)) {
                continue;
            }

            $valid[$skey] = $sval;
        }
        return $valid;
    }
}
