<?php

function theme_init() {
    /*
     * Add a "Powered-By" page
     */
}
add_action("after_switch_theme", "theme_init");

function theme_uninit() {

}
add_action('switch_theme', 'theme_uninit');

