<?php

namespace Dreamery\WP;

use WP_Customize_Control;

/*
 * true/false
 */
class CustomizeBooleanControl extends WP_Customize_Control
{
    public $type = 'boolean';

    public function render_content()
    {
?>
<label for="">
    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>

    <select name="" data-customize-setting-link="<?php echo $this->id; ?>" autocomplete="off">
    <?php
    if ($this->value() === true) {
        echo '<option value="true" selected="selected">True</option>';
        echo '<option value="false">False</option>';
    } else {
        echo '<option value="true">True</option>';
        echo '<option value="false" selected="selected">False</option>';
    }

    ?>
</select>
</label>
<?php
    }
}