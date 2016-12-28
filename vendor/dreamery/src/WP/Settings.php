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
                    'show' => true,
                    'name' => 'Excerpt Length',
                    'desc' => '',
                    'type' => 'number',
                    'default' => '200'),
                'theme_title_separator' => array(
                    'show' => true,
                    'name' => 'Title Separator',
                    'desc' => '',
                    'type' => 'text',
                    'default' => '|'),
                'theme_analytics_gacode' => array(
                    'show' => true,
                    'name' => 'Google Analytics UA Code',
                    'desc' => 'Example: UA-#######-1',
                    'type' => 'text',
                    'default' => ''),
                'theme_injection_header' => array(
                    'show' => true,
                    'name' => 'Header Extras (JS/CSS)',
                    'desc' => '',
                    'type' => 'textarea',
                    'default' => ''),
                'theme_injection_bodyclose' => array(
                    'show' => true,
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
                    'show' => true,
                    'name' => 'Enable Theme Fonts',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
                'theme_font_scheme' => array(
                    'show' => true,
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
                    'show' => true,
                    'name' => 'Root Font Size',
                    'desc' => 'Used to responsively scale all other typography',
                    'type' => 'number-units',
                    'units' => 'px',
                    'default' => '16px',
                    'min' => 10,
                    'max' => 28,
                    'step' => 1),
                'theme_font_size_base' => array(
                    'show' => true,
                    'name' => 'Base Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h1' => array(
                    'show' => true,
                    'name' => 'H1 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '2.5rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h2' => array(
                    'show' => true,
                    'name' => 'H2 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '2rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h3' => array(
                    'show' => true,
                    'name' => 'H3 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.75rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h4' => array(
                    'show' => true,
                    'name' => 'H4 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.5rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h5' => array(
                    'show' => true,
                    'name' => 'H5 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1.25rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_font_size_h6' => array(
                    'show' => true,
                    'name' => 'H6 Font Size',
                    'desc' => '',
                    'type' => 'number-units',
                    'units' => 'rem',
                    'default' => '1rem',
                    'min' => .5,
                    'max' => 3.5,
                    'step' => .25),
                'theme_line_height_base' => array(
                    'show' => true,
                    'name' => 'Base Line Height',
                    'desc' => '',
                    'type' => 'number',
                    'default' => '1.5',
                    'min' => .8,
                    'max' => 2.5,
                    'step' => .1),
                'theme_line_height_heading' => array(
                    'show' => true,
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
                    'show' => false,
                    'name' => 'Header Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#fff'),
                'theme_color_body_background' => array(
                    'show' => false,
                    'name' => 'Body Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#fff'),
                'theme_color_footer_background' => array(
                    'show' => false,
                    'name' => 'Footer Background Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#fff'),
                'theme_color_text' => array(
                    'show' => true,
                    'name' => 'Text Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#000'),
                'theme_color_heading' => array(
                    'show' => true,
                    'name' => 'H1-H6 Text Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#000'),
                'theme_color_brand_primary' => array(
                    'show' => true,
                    'name' => 'Brand Primary Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#0275d8'),
                'theme_color_brand_success' => array(
                    'show' => true,
                    'name' => 'Brand Success Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#5cb85c'),
                'theme_color_brand_info' => array(
                    'show' => true,
                    'name' => 'Brand Info Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#5bc0de'),
                'theme_color_brand_warning' => array(
                    'show' => true,
                    'name' => 'Brand Warning Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#f0ad4e'),
                'theme_color_brand_danger' => array(
                    'show' => true,
                    'name' => 'Brand Danger Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#d9543f'),
                'theme_color_brand_inverse' => array(
                    'show' => false,
                    'name' => 'Brand Inverse Color',
                    'desc' => '',
                    'type' => 'color',
                    'default' => '#373a3c'),
            )
        ),
        /*
         * All of the background settings are opaque, WordPress Customizer
         * has this functionality built in, we are pulling our info from WP
         */
        'background' => array(
            'name' => 'Background Settings',
            'desc' => '',
            'keys' => array(
                'theme_background_image' => array(
                    'show' => false,
                    'name' => 'Background Image',
                    'desc' => '',
                    'type' => 'text',
                    'default' => ''),
                /* XXX: Should repeat, position, and attachment be specified as a 'select', since we know all the options? */
                'theme_background_repeat' => array(
                    /* 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' */
                    'show' => false,
                    'name' => 'Background Repeat',
                    'desc' => '',
                    'type' => 'text',
                    'default' => 'repeat'),
                'theme_background_position' => array(
                    /* 'center', 'right', 'left' */
                    'show' => false,
                    'name' => 'Background Image',
                    'desc' => '',
                    'type' => 'text',
                    'default' => 'left'),
                'theme_background_attachment' => array(
                    /* 'fixed', 'scroll' */
                    'show' => false,
                    'name' => 'Background Attachment',
                    'desc' => '',
                    'type' => 'text',
                    'default' => 'scroll'),
            ),
        ),
        'style' => array(
            'name' => 'Style Settings',
            'desc' => 'Global style settings that control a variety of site-wide presentation options',
            'keys' => array(
                'theme_style_enable_flex' => array(
                    'show' => true,
                    'name' => 'Enable Flex Display',
                    'desc' => 'Use "display: flex" instead of "float" and "display: table"',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_rounded' => array(
                    'show' => true,
                    'name' => 'Enabled Rounded Corners',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
                'theme_style_enable_shadows' => array(
                    'show' => true,
                    'name' => 'Enable Shadows',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_gradients' => array(
                    'show' => true,
                    'name' => 'Enable Gradients',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => false),
                'theme_style_enable_transitions' => array(
                    'show' => true,
                    'name' => 'Enable Transitions',
                    'desc' => '',
                    'type' => 'boolean',
                    'default' => true),
            )
        ),
        'footer' => array(
            'name' => 'Footer Settings',
            'desc' => '',
            'keys' => array(
                'theme_footer_rows' => array(
                    'show' => true,
                    'name' => 'Grid Rows',
                    'desc' => 'Number of Footer Grid Rows for Widgets',
                    'type' => 'number',
                    'default' => 0),
                /* Maybe this shouldn't actually be built in */
                /* Maybe we need a 'multiple' option? */
                /* Maybe we need a 'parent' option? */
                'theme_footer_row_option_columns' => array(
                    'show' => false,
                    'name' => 'Column Layout for Row',
                    'desc' => '',
                    'type' => 'select',
                    'options' => array(
                        '6-6'              =>  '6-6',
                        '4-4-4'            =>  '4-4-4',
                        '4-8'              =>  '4-8',
                        '8-4'              =>  '8-4',
                        '3-3-3-3'          =>  '3-3-3-3',
                        '2-10'             =>  '2-10',
                        '10-2'             =>  '10-2',
                        '3-9'              =>  '3-9',
                        '9-3'              =>  '9-3',
                    )
                ),
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
         * XXX TODO: Selectively re-compile css, different output file for the admin
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