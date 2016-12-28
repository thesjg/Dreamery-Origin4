<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 */

get_header();

?>
<div class="container">
    <?php

    while (have_posts()) {
        the_post();
        the_content();
    }

    ?>
</div>
<?php

get_footer();