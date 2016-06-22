<?php

namespace Dreamery\WP\Admin;

use WP_Customize_Color_Control;

class Customizations
{
    private $priority_panel = 10;
    private $priority_section = 10;

    private $customizations = array(
        'color' => array(
            'name' => 'Color Settings',
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
    );

    public function __construct()
    {
        //if (is_admin()) {
            add_action('customize_register', array($this, 'registerCustomizations'));
        //}
    }

    public function registerCustomizations($wp_customize) {
/*
        $wp_customize->add_panel('abc_panel', array(
            'title' => 'ABC Panel',
            'description' => 'ABC PANEL STUFF',
            'priority' => $this->priority_panel,
            'active_callback' => function() { return true; }
        ));
*/


        $wp_customize->add_section('origin_typography', array(
            'title'         => 'Typography',
            'description'   => 'Select the typography scheme to be used throughout your website',

        ));
        $wp_customize->add_setting('origin_typography_scheme', array(
                'default' => 'muli-ovo',
//                'sanitize_callback' => '',
        ));
        $wp_customize->add_control('origin_typography_scheme', array(
            'section'   => 'origin_typography',
            'label'     => 'Font Scheme: ',
            'type'      => 'select',
            'choices' => array(
                'muli-ovo'              =>  'Muli (Sans) / Ovo (Serif)',
                'opensans-montserrat'   =>  'Open Sans (Sans) / Montserrat (Sans)',
                'lato-fjallaone'        =>  'Lato (Sans) / Fjalla One (Sans)',
                'cabin-quicksand'       =>  'Cabin (Sans) / Quicksand (Sans)',
                'lora-muli'             =>  'Lora (Serif) / Muli (Sans)',
            ),
            'settings' => 'origin_typography_scheme',
        ));





        $originSettings = \Dreamery\WP\Settings::getInstance();
        foreach ($this->customizations['color']['keys'] as $cust_id => $cust) {
            $setting_name = 'origin_theme_settings[' . $cust_id . ']';
            $wp_customize->add_setting($setting_name, array(
                'type' => 'option',
                'default' => $originSettings->getDefault($cust_id),
            ));
            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    $setting_name,
                    array(
                        'section'   => 'colors',
                        'label'     => $cust['name'],
                        'settings'  => $setting_name,
                    )
                )
            );
        }
    }

    public function abccallback($control) {
        echo 'abc';
    }

}