<?php

namespace Dreamery\WP;

use WP_Customize_Control;


/*
 * Pixels, em's, points?
 * min, max?
 */
class CustomizeSizeControl extends WP_Customize_Control
{
    public $type = 'boolean';
    public $control;

    public function __construct($manager, $id, $args = array(), $control = array())
    {
        parent::__construct($manager, $id, $args);

// lets make it so we can pass in a list of options + units + default as well (instead of min/max/step)

        if (!isset($control['units'])) {
            $control['units'] = 'px';
        }
        if (!isset($control['min'])) {
            $control['min'] = 1;
        }
        if (!isset($control['max'])) {
            $control['max'] = 100;
        }
        if (!isset($control['step'])) {
            $control['step'] = 1;
        }
        if (!isset($control['default'])) {
            $originSettings = \Dreamery\WP\Settings::getInstance();
            $default = $originSettings->getDefault($id); // this ID will be in an arry? no worky?
            if (!$default) {
                throw new \Exception('default for control must be specified');
            }
        }

        // XXX
        // Verify min is less than max, etc.
        // verify min, max, step are all numeric

        $this->control = $control;
    }

    public function render_content()
    {
        ?>
        <label for="">
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <select name="" data-customize-setting-link="<?php echo $this->id; ?>" autocomplete="off">
                <?php
                for ($i = $this->control['min']; $i <= $this->control['max']; $i += $this->control['step']) {
                    $option = $i . $this->control['units'];
                    $default = '';
                    $selected = '';
                    if ($option == $this->control['default'])
                        $default = ' (Default)';
                    if ($option == $this->value())
                        $selected = ' selected="selected"';
                    echo '<option value="' . $option . '"' . $selected . '>' . $option . $default . '</option>';
                }
                ?>
            </select>
        </label>
        <?php
    }
}