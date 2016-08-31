<?php

namespace Dreamery\WP\Admin;

use WP_Customize_Color_Control;

class Customizations
{
    private $priority_panel = 10;
    private $priority_section = 10;


    public function __construct() {
        add_action('customize_register', array($this, 'registerCustomizations'));

        /*
         * Load scripts for the customizer
         */
        //add_action('customize_preview_init', array($this, 'enqueueScripts'));
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    public function enqueueScripts() {
        wp_enqueue_script(
            'origin-theme-customize',
            get_template_directory_uri() . '/assets/js/admin_customizer.js',
            array('jquery', 'customize-preview'),
            '1.9.9.6',
            true
        );
    }

    public function registerCustomizations($wp_customize) {

        $originSettings = \Dreamery\WP\Settings::getInstance();
        $settings = $originSettings->getAvailableSettings();

        /*
         * Typography Settings
         */
        $typeSettings = $settings['font'];
        $wp_customize->add_section('origin_typography', array(
            'title'         => $typeSettings['name'],
            'description'   => $typeSettings['desc'],
        ));
        foreach ($typeSettings['keys'] as $cust_id => $cust) {
            $setting_name = 'origin_theme_settings[' . $cust_id . ']';
            if ($cust['type'] == 'boolean') {
                $wp_customize->add_setting($setting_name, array(
                    'type' => 'option',
                    'default' => ($originSettings->getDefault($cust_id)) ? 'true' : 'false',
                ));
            } else {
                $wp_customize->add_setting($setting_name, array(
                    'type' => 'option',
                    'default' => $originSettings->getDefault($cust_id),
                ));
            }
            switch ($cust['type']) {
                case 'boolean':
                    $wp_customize->add_control(
                        new \Dreamery\WP\CustomizeBooleanControl(
                            $wp_customize,
                            $setting_name,
                            array(
                                'section'   => 'origin_typography',
                                'label'     => $cust['name'],
                                'settings'  => $setting_name,
                            )
                        )
                    );
                    break;
                case 'text':
                    $wp_customize->add_control(
                        new \Dreamery\WP\CustomizeTextControl(
                            $wp_customize,
                            $setting_name,
                            array(
                                'section'   => 'origin_typography',
                                'label'     => $cust['name'],
                                'settings'  => $setting_name,
                            )
                        )
                    );
                    break;
                case 'number-units':
                    /* Defaults */
                    $units = 'px';
                    $min = 9;
                    $max = 60;
                    $step = 1;

                    if (!empty($cust['units']))
                        $units = $cust['units'];

                    if (!empty($cust['min']))
                        $min = $cust['min'];
                    if (!empty($cust['max']))
                        $max = $cust['max'];
                    if (!empty($cust['step']))
                        $step = $cust['step'];

                    $wp_customize->add_control(
                        new \Dreamery\WP\CustomizeSizeControl(
                            $wp_customize,
                            $setting_name,
                            array(
                                'section'   => 'origin_typography',
                                'label'     => $cust['name'],
                                'settings'  => $setting_name,
                            ),
                            array(
                                'units' => $units,
                                'min' => $min,
                                'max' => $max,
                                'step' => $step,
                                'default' => $originSettings->getDefault($cust_id),
                            )
                        )
                    );
                    break;
                case 'number':
                    /* Defaults */
                    $min = 0;
                    $max = 100;
                    $step = 1;

                    if (!empty($cust['min']))
                        $min = $cust['min'];
                    if (!empty($cust['max']))
                        $max = $cust['max'];
                    if (!empty($cust['step']))
                        $step = $cust['step'];

                    $wp_customize->add_control(
                        new \Dreamery\WP\CustomizeNumberControl(
                            $wp_customize,
                            $setting_name,
                            array(
                                'section'   => 'origin_typography',
                                'label'     => $cust['name'],
                                'settings'  => $setting_name,
                            ),
                            array(
                                'min' => $min,
                                'max' => $max,
                                'step' => $step,
                                'default' => $originSettings->getDefault($cust_id),
                            )
                        )
                    );
                    break;
                case 'select':
                    $wp_customize->add_control(
                        new \WP_Customize_Control(
                            $wp_customize,
                            $setting_name,
                            array(
                                'section'   => 'origin_typography',
                                'label'     => $cust['name'],
                                'settings'  => $setting_name,
                                'type'      => 'select',
                                'choices'   => $cust['options'],
                            )
                        )
                    );
                    break;
            }
        }

        /*
         * Style Settings
         */
        $styleSettings = $settings['style'];
        $wp_customize->add_section('origin_style', array(
            'title'         => $styleSettings['name'],
            'description'   => $styleSettings['desc'],
        ));
        foreach ($styleSettings['keys'] as $cust_id => $cust) {
            //$setting_name = $cust_id;
            $setting_name = 'origin_theme_settings[' . $cust_id . ']';
            $wp_customize->add_setting($setting_name, array(
                'type' => 'option',
                'default' => ($originSettings->getDefault($cust_id)) ? 'true' : 'false',
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
         * Color Settings
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
}