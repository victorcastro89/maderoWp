<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_id            = airi_get_theme_loop_prop('loop_id', uniqid('la_members_'));
$loop_style         = airi_get_theme_loop_prop('loop_style', 1);
$responsive_column  = airi_get_theme_loop_prop('responsive_column', array());
$excerpt_length     = airi_get_theme_loop_prop('excerpt_length', 15);
$slider_configs     = airi_get_theme_loop_prop('slider_configs', '');
$item_space         = airi_get_theme_loop_prop('item_space', '30');
$slider_css_class   = airi_get_theme_loop_prop('slider_css_class', '');


$loopCssClass = array('la-loop','team__members');
$loopCssClass[] = 'member--style-' . $loop_style;

$loopCssClass[] = 'grid-items';
$loopCssClass[] = 'grid-space-'. $item_space;

if(!empty($slider_configs)){
    $loopCssClass[] = 'js-el la-slick-slider' . $slider_css_class;
}
else{
    $loopCssClass[] = airi_render_grid_css_class_from_columns($responsive_column);
}
printf(
    '<div class="%1$s"%2$s>',
    esc_attr(implode(' ', $loopCssClass)),
    (!empty($slider_configs) ? ' data-la_component="AutoCarousel" ' . $slider_configs : '')
);