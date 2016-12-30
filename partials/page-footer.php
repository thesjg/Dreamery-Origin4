<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <?php
                $logo = '';
                if (function_exists('the_custom_logo')) {
                    $logo = the_custom_logo();
                }
                if (empty($logo)) {
                    echo '<a href="/"><img class="img-fluid" src="' . get_template_directory_uri() . '/assets/img/logo.png"></a>';
                } else {
                    echo $logo;
                }
                ?>
            </div>
            <div class="col-md-4 offset-md-2">
                <?php
                if (is_active_sidebar('footer_widget_1')) {
                    dynamic_sidebar('footer_widget_1');
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php
                if (is_active_sidebar('footer_widget_2')) {
                    dynamic_sidebar('footer_widget_2');
                }
                ?>
            </div>
        </div>
    </div>
</footer>