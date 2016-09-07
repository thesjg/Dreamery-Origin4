<?php

namespace Dreamery\WP;

use WP_Customize_Control;


/*
 *
 */
class CustomizeNumberControl extends WP_Customize_Control
{
    public $type = 'boolean';
    public $control;

    public function __construct($manager, $id, $args = array(), $control = array())
    {
        parent::__construct($manager, $id, $args);

        if (!isset($control['default'])) {
            $originSettings = \Dreamery\WP\Settings::getInstance();
            $default = $originSettings->getDefault($id); // this ID will be in an arry? no worky?
            if (!$default) {
//                $default = 10; // XXX
                throw new \Exception('default for control must be specified');
            }
        }

        // XXX
        // Verify min is less than max, etc. -- if they are specified
        // verify min, max, step are all numeric -- if they are specified

        $this->control = $control;
    }

    public function render_content()
    {
        ?>
        <label for="">
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>

            <input type="number" name="" data-customize-setting-link="<?php echo $this->id; ?>" />

        </label>
        <?php
    }
}