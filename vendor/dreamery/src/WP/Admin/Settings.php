<?php


namespace Dreamery\WP\Admin;


class Settings
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'registerMenus'));
            add_action('admin_init', array($this, 'registerSettings'));
        }
    }

    public static function registerMenus() {

        add_theme_page('Origin4 Options', 'Origin4 Options', 'edit_theme_options', 'origin-theme-settings', __CLASS__ . '::renderOptions');
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
