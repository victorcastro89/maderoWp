<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $airi_loop;

$tmp = $airi_loop;
$airi_loop = array();

$loop_layout = Airi()->settings()->get('portfolio_display_type', 'grid');
$loop_style = Airi()->settings()->get('portfolio_display_style', '1');

$height_mode        = airi_get_theme_loop_prop('height_mode', 'original');
$thumb_custom_height= airi_get_theme_loop_prop('height', '');

airi_set_theme_loop_prop('is_main_loop', true, true);
airi_set_theme_loop_prop('loop_layout', $loop_layout);
airi_set_theme_loop_prop('loop_style', $loop_style);
airi_set_theme_loop_prop('responsive_column', Airi()->settings()->get('portfolio_column', array('xlg'=> 1, 'lg'=> 1,'md'=> 1,'sm'=> 1,'xs'=> 1)));
airi_set_theme_loop_prop('image_size', Airi_Helper::get_image_size_from_string(Airi()->settings()->get('portfolio_thumbnail_size', 'full'),'full'));
airi_set_theme_loop_prop('title_tag', 'h4');
airi_set_theme_loop_prop('excerpt_length', '15');
airi_set_theme_loop_prop('item_space', Airi()->settings()->get('portfolio_item_space', 'default'));
airi_set_theme_loop_prop('height_mode', Airi()->settings()->get('portfolio_thumbnail_height_mode', 'original'));
airi_set_theme_loop_prop('height', Airi()->settings()->get('portfolio_thumbnail_height_custom', ''));

echo '<div id="archive_portfolio_listing" class="la-portfolio-listing">';

if( have_posts() ){

    get_template_part("templates/portfolios/start", $loop_style);

    while( have_posts() ){

        the_post();

        get_template_part("templates/portfolios/loop", $loop_style);

    }

    get_template_part("templates/portfolios/end", $loop_style);

}

echo '</div>';
/**
 * Display pagination and reset loop
 */

airi_the_pagination();

wp_reset_postdata();

$airi_loop = $tmp;