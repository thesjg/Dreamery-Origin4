<?php

namespace Dreamery\WP;

/**
 * Customize gallery output
 *
 * This is based on the core WordPress function
 */
class FilterGallery
{
    public function __construct() {
        add_filter('post_gallery', array($this, 'render'), 10, 2);
    }

    public function render($string, $attr) {
        $post = get_post();

        if (!empty($attr['ids'])) {
            // 'ids' is explicitly ordered, unless you specify otherwise.
            if (empty($attr['orderby'])) {
                $attr['orderby'] = 'post__in';
            }
            $attr['include'] = $attr['ids'];
        }

        $defaults = array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'itemtag'    => 'figure',
            'icontag'    => 'div',
            'captiontag' => 'figcaption',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => '',
            'link'       => ''
        );
        $atts = shortcode_atts($defaults, $attr, 'gallery');

        $column_class = 'col-xs-4';
        switch ($atts['columns']) {
            /* Standard column widths */
            case 1:
                $column_class = 'col-xs-12';
                break;
            case 2:
                $column_class = 'col-xs-6';
                break;
            case 3:
                $column_class = 'col-xs-4';
                break;
            case 4:
                $column_class = 'col-xs-3';
                break;
            case 6:
                $column_class = 'col-xs-2';
                break;

            /* Non-standard widths */
            case 5:
                $column_class = 'origin-col-one-fifth';
                break;
            case 7:
                $column_class = 'origin-col-one-seventh';
                break;
            case 8:
                $column_class = 'origin-col-one-eighth';
                break;
            case 9:
                $column_class = 'origin-col-one-ninth';
                break;

            default:
                break;
        }

        $id = intval($atts['id']);

        if (!empty($atts['include'])) {
            $_attachments = get_posts(array('include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ( ! empty( $atts['exclude'] ) ) {
            $attachments = get_children(array('post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
        } else {
            $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
        }

        if (empty($attachments)) {
            return '';
        }

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment) {
                $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
            }
            return $output;
        }

        $itemtag = tag_escape($atts['itemtag']);
        $captiontag = tag_escape($atts['captiontag']);
        $icontag = tag_escape($atts['icontag']);
        $valid_tags = wp_kses_allowed_html('post');
        if (!isset($valid_tags[$itemtag])) { $itemtag = $defaults['itemtag']; }
        if (!isset($valid_tags[$captiontag])) { $captiontag = $defaults['captiontag']; }
        if (!isset($valid_tags[$icontag])) { $icontag = $defaults['icontag']; }

        $columns = intval($atts['columns']);

        $size_class = sanitize_html_class($atts['size']);
        $gallery_div = "<div class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

        /**
         * Filters the default gallery shortcode CSS styles.
         *
         * @since 2.5.0
         *
         * @param string $gallery_style Default CSS styles and opening HTML div container
         *                              for the gallery shortcode output.
         */
        $output = apply_filters('gallery_style', $gallery_div);

        foreach ($attachments as $id => $attachment) {
            $attr = array();
            $attr['class'] = 'img-fluid attachment-' . $atts['size'] . ' size-' . $atts['size'];
            if (!empty($atts['link']) && 'file' === $atts['link']) {
                $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
            } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
                $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
            } else {
                $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
            }
            $image_meta  = wp_get_attachment_metadata($id);

            $orientation = '';
            if (isset($image_meta['height'], $image_meta['width'])) {
                $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
            }
            $output .= '<' . $itemtag . ' class="figure gallery-item ' . $column_class . '">';
            $output .= "
			<{$icontag} class='figure-img gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
            if ($captiontag && trim($attachment->post_excerpt)) {
                $output .= "
				<{$captiontag} class='figure-caption wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
            }
            $output .= "</{$itemtag}>";
        }

        $output .= "
		</div>\n";

        return $output;
    }
}