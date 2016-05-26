<?php

namespace Dreamery\WP;

/* PHP */
use Exception;

/* WP Global */
use Walker;
use Dreamery\Template;

class NavMenuWalker extends Walker {
    public $tree_type = array('post_type', 'taxonomy', 'custom');

    /**
     * Database fields to use, same as the default WordPress Walker_Nav_Menu class
     *
     * YYY:
     * is this really being used?
     */
    public $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');

    /*
     * 
     * YYY:
     * This function has exceedingly gross use of array's, might be better to simply use
     * objects, even though templates incur a runtime performance hit when object support
     * is enabled
     */
    public function walk($elements, $max_depth) {

        $elementById = array();
        $elementHierKey = null;
        $elementRootIds = array();
        $elementRoots = array();

        /* Build our ById array of menu elements */
        $counter = 0;
        foreach ($elements as $element) {
            $elementById[$element->ID] = array(
                'url' => $element->url,
                'title' => $element->title,
                'target' => $element->target,
                'has_target' => (empty($element->target)) ? false : true,
                'classes' => trim(implode(' ', $element->classes)),
                'attr_title' => $element->attr_title,
                'has_attr_title' => (empty($element->attr_title)) ? false : true,
                'children' => array(),
                'children_ids' => array(),
                'has_children' => (in_array('menu-item-has-children', $element->classes)) ? true : false,
                'is_root' => ($element->menu_item_parent == 0) ? true : false,
                'parent_id' => $element->menu_item_parent,
            );
            if ($elementById[$element->ID]['has_children'] && $elementById[$element->ID]['url'] != '#') {
                $temp_element = $elementById[$element->ID];
                $temp_element['has_children'] = false;
                $temp_element['parent_id'] = $element->ID;
                $temp_element['classes'] = trim(str_replace('menu-item-has-children', '', $temp_element['classes']));
                $temp_element['is_root'] = false;
                $elementById[$element->ID . '-' . $counter] = $temp_element;
                $counter++;
                $elementById[$element->ID]['url'] = '#';
            }
        }

        /* Wire up a hierarchy */
        foreach ($elementById as $key => $element) {
            if ($element['is_root']) {
                $elementRootIds[] = $key;
                continue;
            }

            $elementById[$element['parent_id']]['children_ids'][] = $key;
        }

        /* Assemble the hierarchy */
        function assembleChildren($elementById, $element, $max_depth, $depth = 0) {
            if ($depth >= $max_depth) {
                return $element;
            }
            $depth++;
            foreach ($element['children_ids'] as $child_id) {
                $element['children'][] = assembleChildren($elementById, $elementById[$child_id], $max_depth, $depth);
            }
            return $element;
        }
        foreach ($elementRootIds as $root_id) {
            $elementRoots[] = assembleChildren($elementById, $elementById[$root_id], $max_depth);
        }

        /* Throw away the bits we no longer need */
        unset($elementById);

        $output = '';
        $t = new Template();
        foreach ($elementRoots as $element) {
            $output .= $t->render('navigation-menu-element', $element);
        }
        return $output;
    }

    /*
     *
     * YYY:
     * Does something need to be done with before, after, link_before, link_after, or items_wrap
     * as passed to us in $args?
     */
    public static function fallback($args) {
        if (current_user_can('manage_options')) {
            $options = array_intersect_key($args, array('container' => true, 'container_id' => true,
                'container_class' => true, 'menu_id' => true, 'menu_class' => true));

            $options['has_container'] = (empty($options['container'])) ? false : true;

            $options['has_container_id_class'] = false;
            $options['has_container_id'] = false;
            $options['has_container_class'] = false;
            if (!empty($options['container_id']) && !empty($options['container_class']))
                $options['has_container_id_class'] = true;
            if (!empty($options['container_id']))
                $options['has_container_id'] = true;
            if (!empty($options['container_class']))
                $options['has_container_class'] = true;

            $options['has_menu_id_class'] = false;
            $options['has_menu_id'] = false;
            $options['has_menu_class'] = false;
            if (!empty($options['menu_id']) && !empty($options['menu_class']))
                $options['has_menu_id_class'] = true;
            if (!empty($options['menu_id']))
                $options['has_menu_id'] = true;
            if (!empty($options['menu_class']))
                $options['has_menu_class'] = true;

            $menus = get_registered_nav_menus();
            $options['menu_theme_location_ident'] = $args['theme_location'];
            $options['menu_theme_location'] = $menus[$options['menu_theme_location_ident']];

            // get compiler
            $t = new Template();
            echo $t->render('navigation-menu-nomenu', $options);
        }
    }
}