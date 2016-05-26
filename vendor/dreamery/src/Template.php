<?php

namespace Dreamery;

use LightnCandy\LightnCandy;

class Template {

    private $compiler = null;
    private $baseDirs = array();

    protected $LightNCandyHelpers = array();
    protected $LightNCandyBlockHelpers = array();

    public function __construct() {
        $this->registerBuiltinHelpers();
    }

    public function render($template, $options) {
        $lc_opt = $this->getLightNCandyOptions();
        $c = LightnCandy::compile('{{> ' . $template . '}}', $lc_opt);
        $r = LightnCandy::prepare($c);
        return $r($options);
    }

    protected function getLightNCandyOptions() {
        if (is_admin() && current_user_can('manage_options')) {
            if (get_stylesheet_directory() != get_template_directory()) {
                $this->baseDirs[] = get_stylesheet_directory() . '/partials/admin';
                $this->baseDirs[] = get_template_directory() . '/partials/admin';
            } else {
                $this->baseDirs[] = get_stylesheet_directory() . '/partials/admin';
            }
        } else {
            if (get_stylesheet_directory() != get_template_directory()) {
                $this->baseDirs[] = get_stylesheet_directory() . '/partials/content';
                $this->baseDirs[] = get_template_directory() . '/partials/content';
            } else {
                $this->baseDirs[] = get_stylesheet_directory() . '/partials/content';
            }
        }
        $options = array(
            'flags' => LightnCandy::FLAG_STANDALONEPHP | LightnCandy::FLAG_ERROR_EXCEPTION
                | LightnCandy::FLAG_SLASH
                | LightnCandy::FLAG_HANDLEBARSJS,
//                | LightnCandy::FLAG_INSTANCE
//                | LightnCandy::FLAG_RUNTIMEPARTIAL,
            'basedir' => $this->baseDirs,
            'fileext' => array(
                '.html',
            ),
            'helpers' => $this->LightNCandyHelpers,
            'blockhelpers' => $this->LightNCandyBlockHelpers,
            'partialresolver' => function($ctx, $name) {
                foreach ($this->baseDirs as $basedir) {
                    $file = $basedir . '/' . $name . '.html';
                    if (file_exists($file)) {
                        return file_get_contents($file);
                    }
                }
            },
        );
        return $options;
    }

    public function registerHelper($name, $cb) {
        $this->LightNCandyHelpers[$name] = $cb;
    }

    protected function registerBuiltinHelpers() {
        $this->registerHelper('wp_admin_url', 'Dreamery\WP\TemplateHelpers::admin_url');
        $this->registerHelper('wp_do_settings_sections', 'Dreamery\WP\TemplateHelpers::do_settings_sections');
        $this->registerHelper('wp_settings_fields', 'Dreamery\WP\TemplateHelpers::settings_fields');
        $this->registerHelper('wp_submit_button', 'Dreamery\WP\TemplateHelpers::submit_button');
    }
}