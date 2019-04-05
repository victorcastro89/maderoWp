<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_Inner
 */

/**
 * Extra shortcode attributes
 * @var $la_animation_name
 * @var $la_animation_duration
 * @var $la_animation_delay
 * @var $la_animation_iteration_count
 * @var $la_opacity
 */

$la_wrapper_attributes = array();
$la_animation_name = $la_animation_duration = $la_animation_delay = $la_animation_iteration_count = $la_opacity = '';

$el_class = $width = $el_id = $css = $offset = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ),
	'wpb_column',
	'vc_column_container',
	$width,
);

if ( vc_shortcode_custom_css_has_property( $css, array(
	'border',
	'background',
) ) ) {
	$css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}


if ( '' !== $la_animation_name && 'none' !== $la_animation_name ) {
	wp_enqueue_style('la-animate-block');
	if( $la_opacity == 'yes'){
		$la_wrapper_attributes[] = 'class="wpb_wrapper js-el la-animation-block la-animate-viewport"';
		$la_wrapper_attributes[] = 'style="opacity:1;"';
		$wrapper_attributes[] = 'style="opacity:0;"';
		$wrapper_attributes[] = 'data-lacolumn_animation_viewport="true"';
	}
	else{
		$la_wrapper_attributes[] = 'class="wpb_wrapper js-el la-animation-block"';
		$wrapper_attributes[] = 'data-lacolumn_animation_viewport="false"';
	}
	$la_wrapper_attributes[] = 'data-la_component="AnimationBlock"';

	$inifinite_arr = array("InfiniteRotate", "InfiniteDangle","InfiniteSwing","InfinitePulse","InfiniteHorizontalShake","InfiniteBounce","InfiniteFlash","InfiniteTADA");

	if($la_animation_iteration_count == 0 || in_array($la_animation_name, $inifinite_arr)){
		$la_animation_iteration_count = 'infinite';
		$la_animation_name = 'infinite '. $la_animation_name;
	}

	$la_wrapper_attributes[] = 'data-animate="'.esc_attr($la_animation_name).'"';
	$la_wrapper_attributes[] = 'data-animation-delay="'.esc_attr($la_animation_delay).'"';
	$la_wrapper_attributes[] = 'data-animation-duration="'.esc_attr($la_animation_duration).'"';
	$la_wrapper_attributes[] = 'data-animation-iteration="'.esc_attr($la_animation_iteration_count).'"';

}
else{
	$la_wrapper_attributes[] = 'class="wpb_wrapper"';
}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= '<div class="vc_column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';
$output .= '<div ' . implode( ' ', $la_wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo airi_render_variable($output);
