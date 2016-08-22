<?php

namespace Dreamery\WP;

/*
 * Namespace the settings under a dreamery* prefix, so they will be applicable to any
 * Dreamery theme, all of which may share the same settings infrastructure.
 */
class Settings
{
    private static $instance = null;

    private $settings = array();
    private $defaults = array();

    private $shortcode_tag = '';
    private $prefix = 'origin_';

    private $base = array(
        'general' => array(
            'name' => 'General Settings',
            'desc' => '',
            'keys' => array(
                'theme_layout' => array('name' => 'Layout', 'type' => 'select', 'options' => array('boxed', 'fluid')),
                'theme_excerpt_length' => array('name' => 'Excerpt Length', 'type' => 'number'),
                'theme_title_separator' => array('name' => 'Title Separator', 'type' => 'text'),
                'theme_analytics_gacode' => array('name' => 'Google Analytics UA Code', 'type' => 'text'),
                'theme_injection_header' => array('name' => 'Header Extras (JS/CSS)', 'type' => 'textarea'),
                'theme_injection_bodyclose' => array('name' => 'Before Body Close Extras', 'type' => 'textarea'),
//                'origin_theme_compile_scss' => array('name' => 'Compile SCSS', 'type' => 'boolean'),
            )
        ),
        'font' => array(
            'name' => 'Font Settings',
            'desc' => '',
            'keys' => array(
                'theme_font_family_base' => array('name' => 'Base Font Family', 'type' => 'text'),
                'theme_font_size_base' => array('name' => 'Base Font Size', 'type' => 'number-units'),
                'theme_font_size_h1' => array('name' => 'H1 Font Size', 'type' => 'number-units'),
                'theme_font_size_h2' => array('name' => 'H2 Font Size', 'type' => 'number-units'),
                'theme_font_size_h3' => array('name' => 'H3 Font Size', 'type' => 'number-units'),
                'theme_font_size_h4' => array('name' => 'H4 Font Size', 'type' => 'number-units'),
                'theme_font_size_h5' => array('name' => 'H5 Font Size', 'type' => 'number-units'),
                'theme_font_size_h6' => array('name' => 'H6 Font Size', 'type' => 'number-units'),
                'theme_line_height_base' => array('name' => 'Base Line Height', 'type' => 'number-units'),
                'theme_line_height_heading' => array('name' => 'H1-H6 Line Height', 'type' => 'number-units'),
            )
        ),
        'color' => array(
            'name' => 'Color Settings',
            'desc' => '',
            'keys' => array(
                'theme_color_header_background' => array('name' => 'Header Background Color', 'type' => 'color'),
                'theme_color_body_background' => array('name' => 'Body Background Color', 'type' => 'color'),
                'theme_color_footer_background' => array('name' => 'Footer Background Color', 'type' => 'color'),
                'theme_color_text' => array('name' => 'Text Color', 'type' => 'color'),
                'theme_color_heading' => array('name' => 'H1-H6 Text Color', 'type' => 'color'),
                'theme_color_brand_primary' => array('name' => 'Brand Primary Color', 'type' => 'color'),
                'theme_color_brand_success' => array('name' => 'Brand Success Color', 'type' => 'color'),
                'theme_color_brand_info' => array('name' => 'Brand Info Color', 'type' => 'color'),
                'theme_color_brand_warning' => array('name' => 'Brand Warning Color', 'type' => 'color'),
                'theme_color_brand_danger' => array('name' => 'Brand Danger Color', 'type' => 'color'),
                'theme_color_brand_inverse' => array('name' => 'Brand Inverse Color', 'type' => 'color'),
            )
        ),
        'style' => array(
            'name' => 'Style Settings',
            'desc' => 'Global style settings that control a variety of site-wide presentation options',
            'keys' => array(
                'theme_style_enable_flex' => array(
                    'name' => 'Enable Flex Display',
                    'desc' => 'Use "display: flex" instead of "float" and "display: table"',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_rounded' => array(
                    'name' => 'Enabled Rounded Corners',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
                'theme_style_enable_shadows' => array(
                    'name' => 'Enable Shadows',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_gradients' => array(
                    'name' => 'Enable Gradients',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_transitions' => array(
                    'name' => 'Enable Transitions',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
            )
        ),
    );

    final private function __construct()
    {
    }

    final private function __clone()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();

            $settings = get_option('origin_theme_settings');
            if ($settings !== false && is_array($settings)) {
                foreach ($settings as $skey => $sval) {
                    self::$instance->$skey = $sval;
                }
            }

            $mods = get_theme_mods();
            if ($mods !== false) {
                foreach ($mods as $mod_key => $mod_val) {
                    self::$instance->$mod_key = $mod_val;
                }
            }

            add_shortcode('dreamerysetting', __CLASS__ . '::shortcode');
        }

        return self::$instance;
    }

    /*
     * Don't save our theme-supplied defaults to the database, only modified settings
     * or added settings. This will let us change the defaults by pushing a new version
     * of the theme.
     */
    public function __set($name, $value)
    {
        $key = $this->prefix . $name;
        if (array_key_exists($key, $this->defaults)) {
            if ($this->defaults[$key] != $value) {
                $this->settings[$key] = $value;
            } else if (array_key_exists($key, $this->settings))
                unset($this->settings[$key]);
        } else {
            $this->settings[$key] = $value;
        }
    }

    public function __get($name)
    {
        $ret = null;

        $key = $this->prefix . $name;
        if (array_key_exists($key, $this->settings)) {
            $ret = $this->settings[$key];
        } else if (array_key_exists($key, $this->defaults)) {
            $ret = $this->defaults[$key];
        }

        /*
         * Are we in the customizer?
         *
         * TODO: Selectively re-compile css, different output file for the admin
         * preview vs the frontend?
         *
         * Use is_customize_preview() to determine if in the customizer
         *
         * Use:
         * global $wp_customize;
         * $wp_customize->get_setting('setting-name')
         * to get setting  (->get_setting($name) should work??)
         */
        if (!empty($_POST['wp_customize']) && $_POST['wp_customize'] == 'on') {
            /*
             * Is the setting we are looking for being over-ridden for preview?
             */
            $setting_name = $setting_name = 'origin_theme_settings[' . $name . ']';
            $cust_data = json_decode(stripslashes($_POST['customized']));
            if (!empty($cust_data->$setting_name)) {
                $ret = $cust_data->$setting_name;
            }
        }

        /*
         * YYY:
         * Add debug output
         */

        return $ret;
    }

    public function setDefaults(array $defaults, $override = false)
    {
        if ($override === false) {
            foreach ($defaults as $default_key => $default_value) {
                $default_key = $this->prefix . $default_key;
                if (!isset($this->defaults[$default_key])) {
                    $this->defaults[$default_key] = $default_value;
                }
            }
        } else {
            foreach ($defaults as $default_key => $default_value) {
                $default_key = $this->prefix . $default_key;
                $this->defaults[$default_key] = $default_value;
            }
        }
    }

    public function getDefault($name)
    {
        $key = $this->prefix . $name;
        if (array_key_exists($key, $this->defaults))
            return $this->defaults[$key];

        return false;
    }

    public function getAvailableSettings() {
        return $this->base;
    }

    public function __sleep() {
        return array_keys($this->settings);
    }

    public static function shortcode($atts) {
        $a = shortcode_atts(array(
            'name' => '',
        ), $atts);

        return self::getInstance()->$a['name'];
    }
}