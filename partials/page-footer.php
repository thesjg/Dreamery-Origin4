<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
                Widget
            </div>
            <div class="col-md-4">
                Widget
            </div>
        </div>
    </div>
</footer>