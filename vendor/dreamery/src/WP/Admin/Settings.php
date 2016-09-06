<?php

namespace Dreamery\WP\Admin;


class Settings
{
    public static $page_name = 'origin-theme-settings';

    public function __construct()
    {

        if (is_admin()) {
            add_action('admin_menu', array($this, 'registerMenus'), 1);
            add_action('admin_init', array($this, 'registerSettings'));
            add_action('current_screen', array($this, 'enqueueAssets'));
        }
    }

    public static function enqueueAssets() {
        $screen = get_current_screen();
        if ($screen->id == 'appearance_page_' . self::$page_name) {
            wp_enqueue_style('strider', get_template_directory_uri() . '/assets/admin/css/theme_settings.css');
        }
    }

    public static function registerMenus() {

        add_theme_page('Origin4 Options', 'Origin4 Options', 'edit_theme_options', self::$page_name, __CLASS__ . '::renderOptions');
    }

    public static function registerSettings() {

        register_setting('origin_theme_settings', 'origin_theme_settings', __CLASS__ . '::validate');

        /*
         * XXX: Temporarily commented out
         * Will need to pull what we want out of the WP\Settings class w/ getSettings()
         *
         * We specifically do not want (because they are handled in the Customizer):
         * Color, Style, Font/Typography
         *
         * We DO want:
         * Google Analytics code, header/footer injection, Title seperator? excerpt length?
         */
        /*
        foreach (self::$settings as $section_name => $section_data) {
            add_settings_section('origin_settings_theme_' . $section_name, $section_data['name'], __CLASS__ . '::renderSection', 'origin');
            foreach ($section_data['keys'] as $setting_key => $setting_data) {
                add_settings_field($setting_key, $setting_data['name'], function($args) use ($setting_data, $setting_key){
                    self::renderField($setting_data, $setting_key, $args);
                }, 'origin', 'origin_settings_theme_' . $section_name);
            }
        }
        */

        $originSettings = \Dreamery\WP\Settings::getInstance();
        $current_settings = $originSettings->getAvailableSettings();

        $generalSettings = $current_settings['general'];

        $section_name = 'origin_settings_theme_general';
        add_settings_section($section_name, 'General Settings', __CLASS__ . '::renderSection', 'origin');

        foreach ($generalSettings['keys'] as $cust_id => $cust) {
            if ($cust['show'] === false)
                continue;

//            $setting_name = 'origin_theme_settings[' . $cust_id . ']';

            add_settings_field($cust_id, $cust['name'], function($args) use ($cust, $cust_id){
                self::renderField($cust, $cust_id, $args);
            }, 'origin', $section_name);

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
            'value'         =>      $settings->$field,
            'placeholder'   =>      $settings->getDefault($field),
            'name'          =>      'origin_theme_settings[' . $field . ']',
            'description'   =>      $data['desc'],
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
//            $options['placeholder'] = $options['default'];
            if ($options['value'] == $options['placeholder']) {
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