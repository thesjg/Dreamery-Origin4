<?php

namespace Dreamery\WP;

use WP_Customize_Control;

/*
 * true/false
 */
class CustomizeTextControl extends WP_Customize_Control
{
    public $type = 'boolean';
    /*
        public function __construct()
        {
            parent::__construct();
        }
    */

    /*
     * The same as default in WP_Customize_Control
     * + placeholder
     */
    public function render_content()
    {
        ?>
        <label>
        <?php if ( ! empty( $this->label ) ) : ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        	<input type="<?php echo esc_attr( $this->type ); ?>" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" placeholder="yyy" <?php $this->link(); ?> />
        </label>
        <?php
    }
}