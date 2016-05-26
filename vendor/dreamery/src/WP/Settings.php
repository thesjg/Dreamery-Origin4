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

    final private function __construct() {}
    final private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();

            $settings = get_option('dreamery_settings');
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
        if (array_key_exists($name, $this->defaults)) {
            if ($this->defaults[$name] != $value) {
                $this->settings[$name] = $value;
            } else if (array_key_exists($name, $this->settings))
                unset($this->settings[$name]);
        } else {
            $this->settings[$name] = $value;
        }
    }

    public function __get($name) {
        if (array_key_exists($name, $this->settings)) {
            return $this->settings[$name];
        } else if (array_key_exists($name, $this->defaults)) {
            return $this->defaults[$name];
        }

        /*
         * YYY:
         * Add debug output
         */

        return null;
    }

    public function setDefaults(array $defaults) {
        $this->defaults = $defaults;
    }

    public function getDefault($name) {
        if (array_key_exists($name, $this->defaults))
            return $this->defaults[$name];

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