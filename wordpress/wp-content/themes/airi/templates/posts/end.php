<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/*
 * Template loop-end
 */

$layout             = airi_get_theme_loop_prop('loop_layout', 'grid');
$style              = airi_get_theme_loop_prop('loop_style', 1);

if($layout == 'list' && $style == 1){
    echo '</div>';
}

echo '</div>';