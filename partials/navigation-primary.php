<nav class="navbar navbar-light bg-faded" role="navigation">
    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header">
        &#9776;
    </button>
    <div class="collapse navbar-toggleable-xs" id="navbar-header">
        <a class="navbar-brand" href="<?php echo home_url(); ?>">
            <?php bloginfo('name'); ?>
        </a>
        <?php
        $a = wp_nav_menu(array(
                'menu'              => 'primary',
                'theme_location'    => 'origin_navigation_menu_primary',
                'depth'             => 2,
                'container'         => '',
                'container_class'   => '',
                'menu_class'        => 'nav navbar-nav pull-sm-right',
                'menu_id'           => 'origin-primary-navigation',
                'fallback_cb'       => 'Dreamery\WP\NavMenuWalker::fallback',
                'walker'            => new Dreamery\WP\NavMenuWalker()
            )
        );
        ?>
    </div>
</nav>