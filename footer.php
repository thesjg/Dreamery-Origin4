<?php
    wp_footer();
    echo origin_get_setting('origin_theme_injection_bodyclose');

    if (!current_user_can('manage_options')) {
        $gacode = origin_get_setting('origin_theme_analytics_gacode');
        if (!empty($gacode)) {
            ?>
            <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo $gacode; ?>', 'auto');
            ga('send', 'pageview');
            </script>
            <?php
        }
    }
?>
</body>
</html>