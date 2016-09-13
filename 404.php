<?php

/**
 * 404 Not Found page template
 */

get_header();
get_template_part('partials/page-header');
get_template_part('partials/navigation-primary');

?>
<div class="container">
    <h1>Requested page cannot be found</h1>
    <p>Try a search:</p>
    <?php get_search_form(); ?>
</div>
<?php

get_template_part('partials/page-footer');
get_footer();