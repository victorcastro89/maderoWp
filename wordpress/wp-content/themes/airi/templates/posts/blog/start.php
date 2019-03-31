<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wp_query, $wp_rewrite;

$blog_item_space = Airi()->settings()->get('blog_item_space', 'default');

if($blog_item_space == 'zero'){
    $blog_item_space = 0;
}

$blog_design = Airi()->settings()->get('blog_design', 'grid_4');
$blog_columns = wp_parse_args( (array) Airi()->settings()->get('blog_post_column'), array('lg'=> 1,'md'=> 1,'sm'=> 1,'xs'=> 1, 'mb' => 1) );
$blog_masonry = ( Airi()->settings()->get('blog_masonry') == 'on' ) ? true : false;
$blog_pagination_type = Airi()->settings()->get('blog_pagination_type', 'pagination');
$css_classes = array( 'la-loop', 'showposts-loop', 'blog-main-loop' );
$css_classes[] = 'blog-pagination-type-' . $blog_pagination_type;
$css_classes[] = 'blog-' . $blog_design;

$layout = $blog_design;
$style  = str_replace(array('grid_', 'list_'), '', $layout);
$layout = str_replace($style, '', $layout);
$layout = str_replace('_', '', $layout);

$css_classes[] = "$layout-$style";
$css_classes[] = 'showposts-' . $layout;

if($layout == 'grid'){
    $css_classes[] = 'grid-items';
    $css_classes[] = 'grid-space-' . $blog_item_space;
    $css_classes[] = airi_render_grid_css_class_from_columns($blog_columns);
}

$data_js_component = array();

if($blog_masonry && $layout == 'grid'){
    $css_classes[] = 'js-el la-isotope-container';
    $data_js_component[] = 'DefaultMasonry';
}

if($blog_pagination_type == 'infinite_scroll'){
    $css_classes[] = 'js-el la-infinite-container';
    $data_js_component[] = 'InfiniteScroll';
}
if($blog_pagination_type == 'load_more'){
    $css_classes[] = 'js-el la-infinite-container infinite-show-loadmore';
    $data_js_component[] = 'InfiniteScroll';
}

$thumbnail_size     = Airi_Helper::get_image_size_from_string(Airi()->settings()->get('blog_thumbnail_size', 'full'), 'full');
$excerpt_length     = Airi()->settings()->get('blog_excerpt_length');
$content_type       = (Airi()->settings()->get('blog_content_display', 'excerpt') == 'excerpt') ? 'excerpt' : 'full';
$show_thumbnail     = (Airi()->settings()->get('featured_images_blog') == 'on') ? true : false;
$height_mode        = 'original';
$thumb_custom_height= '';

airi_set_theme_loop_prop('is_main_loop', true, true);
airi_set_theme_loop_prop('loop_layout', $layout);
airi_set_theme_loop_prop('loop_style', $style);
airi_set_theme_loop_prop('title_tag', 'h2');
airi_set_theme_loop_prop('image_size', $thumbnail_size);
airi_set_theme_loop_prop('excerpt_length', $excerpt_length);
airi_set_theme_loop_prop('content_type', $content_type);
airi_set_theme_loop_prop('show_thumbnail', $show_thumbnail);
airi_set_theme_loop_prop('height_mode', $height_mode);
airi_set_theme_loop_prop('height', $thumb_custom_height);
airi_set_theme_loop_prop('responsive_column', $blog_columns);

?>
<div
    class="<?php echo esc_attr(implode(' ', $css_classes)); ?>"
    <?php if(!empty($data_js_component)) echo 'data-la_component="'. esc_attr(json_encode($data_js_component)) .'"'; ?>
    data-item_selector=".loop__item"
    data-page_num="<?php echo esc_attr( get_query_var('paged') ? get_query_var('paged') : 1 ) ?>"
    data-page_num_max="<?php echo esc_attr( $wp_query->max_num_pages ? $wp_query->max_num_pages : 1 ) ?>"
    ><?php

if( $layout == 'list' && $style == 1){
    echo '<div class="loop__item__one loop__item">';
}