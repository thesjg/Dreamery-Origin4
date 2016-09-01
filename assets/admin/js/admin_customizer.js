/*
 * Runs outside the customizer preview iframe, alongside the controls
 */
(function($) {
        onElementInserted('body', '#customize-control-origin_theme_settings-theme_font_scheme_enable select', function(e){
            $(document).on('change', '#customize-control-origin_theme_settings-theme_font_scheme_enable select', function(){
                activeFontScheme();
            });
            activeFontScheme();
        })
})(jQuery);

function activeFontScheme() {
    var $choose = jQuery('#customize-control-origin_theme_settings-theme_font_scheme_enable select');
    var $scheme = jQuery('#customize-control-origin_theme_settings-theme_font_scheme select');
    if ($choose.val() == 'true') {
        $scheme.prop('disabled', false)
    }  else {
        $scheme.prop('disabled', 'disabled')
    }
}

function onElementInserted(containerSelector, elementSelector, callback) {

    var onMutationsObserved = function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes) {
                var elements = jQuery(mutation.addedNodes).find(elementSelector);
                for (var i = 0, len = elements.length; i < len; i++) {
                    callback(elements[i]);
                }
            }
        });
    };

    var target = jQuery(containerSelector)[0];
    var config = { childList: true, subtree: true };
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
    var observer = new MutationObserver(onMutationsObserved);
    observer.observe(target, config);
}