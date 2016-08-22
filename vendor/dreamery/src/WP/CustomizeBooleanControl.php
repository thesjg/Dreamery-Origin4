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
    /*
        public function __construct()
        {
            parent::__construct();
        }
    */

    public function render_content()
    {
    }
}

/*
 * XXX: This is completely non-functional
 *
 * true/false
 */
class CustomizeBooleanControl extends WP_Customize_Control
{
    public $type = 'boolean';
/*
    public function __construct()
    {
        parent::__construct();
    }
*/

    public function render_content()
    {
        echo '<select><option>True</option><option>False</option></select>';
    }
}