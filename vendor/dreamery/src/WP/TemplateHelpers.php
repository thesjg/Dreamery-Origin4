<?php

namespace Dreamery\WP;

class TemplateHelpers {
    public static function admin_url($ctx) {
        $opt = $ctx['hash'];

        /* Defaults */
        $path = '';
        $scheme = 'admin';

        if (!empty($opt['path']))
            $path = $opt['path'];
        if (!empty($opt['scheme']))
            $scheme = $opt['scheme'];

        return admin_url($path, $scheme);
    }

    public static function do_settings_sections($ctx) {
        $opt = $ctx['hash'];

        $page = '';

        if (!empty($opt['page']))
            $page = $opt['page'];

        ob_start();
        do_settings_sections($page);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public static function settings_fields($ctx) {
        $opt = $ctx['hash'];

        $option_group = '';

        if (!empty($opt['option_group']))
            $option_group = $opt['option_group'];

        ob_start();
        settings_fields($option_group);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public static function submit_button($ctx) {
        $opt = $ctx['hash'];

        /* Defaults */
        $text = 'Save Changes';
        $type = 'primary';
        $name = 'submit';
        $wrap = true;

        if (!empty($opt['text']))
            $text = $opt['text'];
        if (!empty($opt['type']))
            $type = $opt['type'];
        if (!empty($opt['name']))
            $name = $opt['name'];
        if (!empty($opt['wrap']))
            $wrap = ($opt['wrap']) ? true : false;

        ob_start();
        submit_button($text, $type, $name, $wrap);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }
}


// posts_nav_link() or paginate_links() or the_posts_pagination() or the_posts_navigation() or next_posts_link() and previous_posts_link()
//  paginate_comments_links() or the_comments_navigation or next_comments_link() and previous_comments_link()
//  get_avatar or wp_list_comments
// wp_link_pages
// post_class
// comments_template
//  comment_form