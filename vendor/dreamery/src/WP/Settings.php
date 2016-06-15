<?php

namespace Dreamery\WP;

/*
 * Namespace the settings under a dreamery* prefix, so they will be applicable to any
 * Dreamery theme, all of which may share the same settings infrastructure.
 */
class Settings {

    private static $instance = null;

    private $settings = array();
    private $defaults = array();
    private $shortcode_tag = '';
    private $prefix = 'origin_';

    final private function __construct() {}
    final private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();

            $settings = get_option('origin_theme_settings');
            if ($settings !== false && is_array($settings)) {
                foreach ($settings as $skey => $sval) {
                    self::$instance->$skey = $sval;
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
    public function __set($name, $value) {
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

    public function __get($name) {
        $key = $this->prefix . $name;
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        } else if (array_key_exists($key, $this->defaults)) {
            return $this->defaults[$key];
        }

        /*
         * YYY:
         * Add debug output
         */

        return null;
    }

    public function setDefaults(array $defaults, $override = false) {
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

    public function getDefault($name) {
        $key = $this->prefix . $name;
        if (array_key_exists($key, $this->defaults))
            return $this->defaults[$key];

        return false;
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