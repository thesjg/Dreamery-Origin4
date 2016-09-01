<?php

namespace Dreamery\WP;

/*
 *
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
                'theme_excerpt_length' => array(
                    'name' => 'Excerpt Length',
                    'desc' => '',
                    'type' => 'number',
                    'default' => '200'),
                'theme_title_separator' => array(
                    'name' => 'Title Separator',
                    'desc' => '',
                    'type' => 'text',
                    'default' => '|'),
                'theme_analytics_gacode' => array(
                    'name' => 'Google Analytics UA Code',
                    'desc' => 'Example: UA-#######-1',
                    'type' => 'text',
                    'default' => ''),
                'theme_injection_header' => array(
                    'name' => 'Header Extras (JS/CSS)',
                    'desc' => '',
                    'type' => 'textarea',
                    'default' => ''),
                'theme_injection_bodyclose' => array(
                    'name' => 'Before Body Close Extras',
                    'desc' => '',
                    'type' => 'textarea',
                    'default' => ''),
//                'origin_theme_compile_scss' => array(
//'name' => 'Compile SCSS',
//'type' => 'boolean'),
            )
        ),
        'font' => array(
            'name' => 'Typography Settings',
            'desc' => '',
            'keys' => array(
                'theme_font_scheme_enable' => array(
                    'name' => 'Enable Theme Fonts',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
                'theme_font_scheme' => array(
                    'name' => 'Font Scheme',
                    'desc' => '',
                    'type' => 'select',
                    'default' => 'muli-ovo',
                    'options' => array(
                        'muli-ovo'              =>  'Muli (Sans) / Ovo (Serif)',
                        'opensans-montserrat'   =>  'Open Sans (Sans) / Montserrat (Sans)',
                        'lato-fjallaone'        =>  'Lato (Sans) / Fjalla One (Sans)',
                        'cabin-quicksand'       =>  'Cabin (Sans) / Quicksand (Sans)',
                        'lora-muli'             =>  'Lora (Serif) / Muli (Sans)',
                    )
                ),
                'theme_font_size_root' => array(
                    'name' => 'Root Font Size',
                    'desc' => 'Used to responsively scale all other typography',
                    'type' => 'number-units',
                    'units' => 'px',
                    'default' => '16px',
                    'min' => 10,
                    'max' => 28,
                    'step' => 1),
                'theme_font_size_base' => array(
                    'name' => 'Base Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h1' => array(
                    'name' => 'H1 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '2.5rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h2' => array(
                    'name' => 'H2 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '2rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h3' => array(
                    'name' => 'H3 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.75rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h4' => array(
                    'name' => 'H4 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.5rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h5' => array(
                    'name' => 'H5 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.25rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h6' => array(
                    'name' => 'H6 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_line_height_base' => array(
                    'name' => 'Base Line Height',
                    'desc' => '',
                    'type' => 'number',
                    'default' => '1.5',
                    'min' => .8,
                    'max' => 2.5,
                    'step' => .1),
                'theme_line_height_heading' => array(
                    'name' => 'H1-H6 Line Height',
                    'desc' => '',
                    'type' => 'number',
                    'default' => '1.1',
                    'min' => .8,
                    'max' => 2.5,
                    'step' => .1),
            )
        ),
        'color' => array(
            'name' => 'Color Settings',
            'desc' => '',
            'keys' => array(
                'theme_color_header_background' => array(
                    'name' => 'Header Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
                'theme_color_body_background' => array(
                    'name' => 'Body Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
                'theme_color_footer_background' => array(
                    'name' => 'Footer Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
                'theme_color_text' => array(
                    'name' => 'Text Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
                'theme_color_heading' => array(
                    'name' => 'H1-H6 Text Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
                'theme_color_brand_primary' => array(
                    'name' => 'Brand Primary Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#0275d8'),
                'theme_color_brand_success' => array(
                    'name' => 'Brand Success Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#5cb85c'),
                'theme_color_brand_info' => array(
                    'name' => 'Brand Info Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#5bc0de'),
                'theme_color_brand_warning' => array(
                    'name' => 'Brand Warning Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#f0ad4e'),
                'theme_color_brand_danger' => array(
                    'name' => 'Brand Danger Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#d9543f'),
                'theme_color_brand_inverse' => array(
                    'name' => 'Brand Inverse Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => ''),
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

            /*
             * Setup built-in defaults
             */
            foreach (self::$instance->base as $setting_type => $setting_value) {
                foreach ($setting_value['keys'] as $skey => $sval) {
                    if (isset($sval['default'])) {
                        self::$instance->setDefault($skey, $sval['default']);
                    }
                }
            }

            /*
             * Bring in saved settings from WordPress database
             */
            $settings = get_option('origin_theme_settings');
            if ($settings !== false && is_array($settings)) {
                foreach ($settings as $skey => $sval) {
                    self::$instance->$skey = $sval;
                }
            }

            /*
             * Bring in saved settings from Theme Customizer
             *
             * ... just kidding! By passing type => option to
             * wp_customize->add_setting, the customizer settings are
             * turned into options retrievable via get_option() instead of
             * get_theme_mods()
             */
            /*
            $mods = get_theme_mods();
            if ($mods !== false) {
                foreach ($mods as $mod_key => $mod_val) {
                    self::$instance->$mod_key = $mod_val;
                }
            }
            */

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
        $value = $this->coerceValue($value);
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
        foreach ($defaults as $default_key => $default_value) {
            $this->setDefault($default_key, $default_value, $override);
        }
    }

    public function setDefault($name, $value, $override = false)
    {
        $value = $this->coerceValue($value);

        $default_key = $this->prefix . $name;
        if ($override === false) {
            if (!isset($this->defaults[$default_key]))
                $this->defaults[$default_key] = $value;
        } else {
            $this->defaults[$default_key] = $value;
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

        $ret = self::getInstance()->$a['name'];

        return $ret;
    }

    private function coerceValue($value) {
        if ($value == 'true')
            $value = true;
        else if ($value == 'false')
            $value = false;

        return $value;
    }
}