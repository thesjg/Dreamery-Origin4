<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico"> <!-- XXX Favicons are handled by the Site Icon setting in the customizer since version 4.3. -->
    <?php
        wp_head();
        echo origin_get_setting('theme_injection_header');
    ?>
</head>
<body <?php body_class(); ?>>