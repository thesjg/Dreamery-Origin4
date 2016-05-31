<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<?php
    wp_footer();
    echo origin_get_setting('origin_theme_injection_bodyclose');

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
?>
</body>
</html>