<?php

namespace Dreamery\WP;

use WP_Customize_Control;

/*
 *
 */
class CustomizeGridRowControl extends WP_Customize_Control
{
    public $type = 'grid-row';

    private $_options = array(
        '6-6',
        '4-4-4',
        '4-8',
        '8-4',
        '3-3-3-3',
        '2-10',
        '10-2',
        '3-9',
        '9-3',
    );

    public function render_content()
    {
        ?>
        <label for="">
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>

            <select name="" data-customize-setting-link="<?php echo $this->id; ?>" autocomplete="off">
                <?php
                foreach ($this->_options as $o_key) {
                    if ($this->value() == $o_key) {
                        echo '<option value="' . $o_key . '" selected="selected">' . $o_key . '</option>';
                    } else {
                        echo '<option value="' . $o_key . '">' . $o_key . '</option>';
                    }
                }
                ?>
            </select>
        </label>
        <?php
    }
}