<?php

namespace Dreamery\WP\Admin;

use WP_Customize_Color_Control;

class Customizations
{
    private $priority_panel = 10;
    private $priority_section = 10;


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


        $wp_customize->add_section('origin_style', array(
            'title'         => 'Display Styles',
            'description'   => 'Various customizable display options',

        ));
        $x = array(
            'flex'  => false,
            'rounded'   => true,
            'shadows'   => false,
            'gradients' =>  false,
            'transitions'   => true,
        );
        /*
        $wp_customize->add_setting('origin_style_flex', array(
            'default' => 'false',
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
*/
        $originSettings = \Dreamery\WP\Settings::getInstance();
        $settings = $originSettings->getAvailableSettings();

        /*
         *
         */
        $styleSettings = $settings['style'];
        $wp_customize->add_section('origin_style', array(
            'title'         => $styleSettings['name'],
            'description'   => $styleSettings['desc'],
        ));
        foreach ($styleSettings['keys'] as $cust_id => $cust) {
            $setting_name = 'origin_theme_settings[' . $cust_id . ']';
            $wp_customize->add_setting($setting_name, array(
                'type' => 'option',
                'default' => $originSettings->getDefault($cust_id),
            ));
            $wp_customize->add_control(
                new \Dreamery\WP\CustomizeBooleanControl(
                    $wp_customize,
                    $setting_name,
                    array(
                        'section'   => 'origin_style',
                        'label'     => $cust['name'],
                        'settings'  => $setting_name,
                    )
                )
            );
        }


        /*
         *
         */
        $colorSettings = $settings['color'];
        foreach ($colorSettings['keys'] as $cust_id => $cust) {
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