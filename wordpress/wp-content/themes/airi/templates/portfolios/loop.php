<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_index         = absint(airi_get_theme_loop_prop('loop_index', 0));
$loop_index2        = absint(airi_get_theme_loop_prop('loop_index2', 0));
$image_size         = airi_get_theme_loop_prop('image_size', 'full');
$title_tag          = airi_get_theme_loop_prop('title_tag', 'h3');
$item_sizes         = airi_get_theme_loop_prop('item_sizes', array());
$style              = airi_get_theme_loop_prop('loop_style', 1);
$item_w             = 1;
$item_h             = 1;

if($loop_index2 == count($item_sizes)){
    $loop_index2 = 0;
}

if(!empty($item_sizes[$loop_index2]['w'])){
    $item_w = $item_sizes[$loop_index2]['w'];
}
if(!empty($item_sizes[$loop_index2]['h'])){
    $item_h = $item_sizes[$loop_index2]['h'];
}
if(!empty($item_sizes[$loop_index2]['s'])){
    $thumbnail_size = $item_sizes[$loop_index2]['s'];
}
else{
    $thumbnail_size = $image_size;
}

$height_mode        = airi_get_theme_loop_prop('height_mode', 'original');
$thumb_custom_height= airi_get_theme_loop_prop('height', '');
$post_class     = array('loop__item','grid-item','portfolio__item');
if (!has_post_thumbnail()) {
    $post_class[] = 'no-featured-image';
}

$thumb_css_style = '';
$thumb_css_class = ' gitem-zone-height-mode-' . $height_mode;
$thumb_src = '';
$thumb_width = $thumb_height = 0;
if(has_post_thumbnail()){
    if($thumbnail_obj = Airi()->images()->get_attachment_image_src( get_post_thumbnail_id(), $thumbnail_size )){
        list( $thumb_src, $thumb_width, $thumb_height ) = $thumbnail_obj;
        if( $thumb_width > 0 && $thumb_height > 0 ) {
            $thumb_css_style .= 'padding-bottom:' . round( ($thumb_height/$thumb_width) * 100, 2 ) . '%;';
            if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
                $photon_args = array(
                    'resize' => $thumb_width . ',' . $thumb_height
                );
                $thumb_src = wp_get_attachment_url( get_post_thumbnail_id() );
                $thumb_src = jetpack_photon_url( $thumb_src, $photon_args );
            }
        }
    }
}

if ( 'custom' === $height_mode ) {
    if ( strlen( $thumb_custom_height ) > 0 ) {
        if ( preg_match( '/^\d+$/', $thumb_custom_height ) ) {
            $thumb_custom_height .= 'px';
        }
        $thumb_css_style .= 'padding-bottom: ' . $thumb_custom_height . ';';
        $thumb_css_class .= ' gitem-hide-img';
    }
}
elseif ( 'original' !== $height_mode ) {
    $thumb_css_class .= ' gitem-hide-img gitem-zone-height-mode-auto' . ( strlen( $height_mode ) > 0 ? ' gitem-zone-height-mode-auto-' . $height_mode : '' );
}

$use_lazy_load = Airi_Helper::is_enable_image_lazy();
if($use_lazy_load){
    $thumb_css_class .= ' la-lazyload-image';
}
else{
    $thumb_css_style .= sprintf('background-image: url(%s);', esc_url($banner_image_src));
}

?>
<div <?php post_class($post_class); ?> data-width="<?php echo esc_attr($item_w);?>" data-height="<?php echo esc_attr($item_h);?>">
    <div class="loop__item__inner">
        <div class="loop__item__inner2">
            <div class="loop__item__thumbnail">
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>"
                     data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>"
                     style="<?php echo esc_attr($thumb_css_style); ?>">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="loop__item__thumbnail--linkoverlay"><span class="hidden"><?php the_title(); ?></span></a>
                    <?php echo Airi()->images()->render_image($thumb_src, array('width' => $thumb_width, 'height' => $thumb_height, 'alt' => get_the_title())); ?>
                </div>
            </div>
            <div class="loop__item__info">
                <div class="loop__item__info2">
                    <?php
                    if($style != 8){
                        echo airi_get_the_term_list(get_the_ID(), 'la_portfolio_skill', '<div class="loop__item__termlink">', '', '</div>', 3);
                    }?>
                    <div class="loop__item__title">
                        <?php the_title(sprintf('<%s class="entry-title"><a href="%s" title="%s">', $title_tag, esc_url(get_the_permalink()), the_title_attribute(array('echo'=>false))), sprintf('</a></%s>', $title_tag)); ?>
                    </div>
                    <?php if( $style == 8 ):?>
                    <div class="loop__item__desc">
                        <?php echo airi_get_the_excerpt(10); ?>
                    </div>
                    <div class="loop__item__meta--footer">
                        <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php echo esc_html_x('Read more', 'front-end', 'airi');  ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$loop_index2++;
$loop_index++;
airi_set_theme_loop_prop('loop_index', $loop_index);
airi_set_theme_loop_prop('loop_index2', $loop_index2);