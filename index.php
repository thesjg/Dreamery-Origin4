<?php

get_header();
get_template_part('partials/navigation-primary');

while (have_posts()) {
    the_post();
    the_content();
}

get_footer();