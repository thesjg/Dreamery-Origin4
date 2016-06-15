<?php

namespace Dreamery\WP\Admin;

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
            'description'   => '',

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
            'settings' => array(),
        ));





        $wp_customize->add_section('abc_section', array(
            'panel' => 'abc_panel',
            'title'         => 'ABC Section',
            'description'   => '',
//            'priority'      => $this->priority_section,
            'active_callback' => function() { return true; }
        ));

/*
        $wp_customize->add_setting('themename_theme_option', array(
            'default'        => 'Arse!',
            'type'           => 'text',

        ));
*/

        $wp_customize->add_control('themename_text_test', array(
            'label'      => 'Testing 123',
            'section'    => 'abc_section',
            //'settings'   => 'themename_theme_option',
            'settings' => array(),
            'type' => 'button',
            'input_attrs' => array('value' => 'FOOOO'),
            'active_callback' => function() { return true; }
        ));




        $wp_customize->add_control('def', array(
            'section'   => 'colors',
            'label'     => 'DEF: ',
            'type'      => 'text',
            'settings' => array(),
        ));



    }



    public function abccallback($control) {
        echo 'abc';
    }

}