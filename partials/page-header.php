<header class="container">
    <div class="row">
        <div class="col-sm-4">
<?php
$logo = '';
if (function_exists('the_custom_logo')) {
    $logo = the_custom_logo();
}
if (empty($logo)) {
    echo '<a href="/"><img src="' . get_template_directory_uri() . '/assets/img/logo.png"></a>';
} else {
    echo $logo;
}
?>
        </div>
        <div class="col-sm-8">
            ...
        </div>
    </div>
</header>
<?php
get_template_part('partials/navigation-primary');