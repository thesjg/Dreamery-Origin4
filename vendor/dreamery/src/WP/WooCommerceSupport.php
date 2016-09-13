<?php

namespace Dreamery\WP;

class WooCommerceSupport {
    public function __construct() {
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        add_action('woocommerce_before_main_content', array($this, 'my_theme_wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'my_theme_wrapper_end'), 10);
        add_action('after_setup_theme', array($this, 'woocommerce_support'));
    }

    /*
     * XXX
     */
    public function wrapper_start() {
        echo '';
    }

    /*
     * XXX
     */
    public function wrapper_end() {
        echo '';
    }

    function declare_support() {
        add_theme_support('woocommerce');
    }
}