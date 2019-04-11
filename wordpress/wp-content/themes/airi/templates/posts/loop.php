<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_index         = absint(airi_get_theme_loop_prop('loop_index', 1));

$is_main_loop       = airi_get_theme_loop_prop('is_main_loop', false);
$loop_name          = airi_get_theme_loop_prop('loop_name', false);
$show_thumbnail     = airi_get_theme_loop_prop('show_thumbnail', true);
$layout             = airi_get_theme_loop_prop('loop_layout', 'grid');
$style              = airi_get_theme_loop_prop('loop_style', 1);
$thumbnail_size     = airi_get_theme_loop_prop('image_size', 'thumbnail');
$title_tag          = airi_get_theme_loop_prop('title_tag', 'h3');
$excerpt_length     = airi_get_theme_loop_prop('excerpt_length', 0);
$show_excerpt       = absint($excerpt_length) > 0 ? true : false;
$responsive_column  = airi_get_theme_loop_prop('responsive_column', array());

$height_mode        = airi_get_theme_loop_prop('height_mode', 'original');
$thumb_custom_height= airi_get_theme_loop_prop('height', '');

if($is_main_loop){
    $height_mode = Airi()->settings()->get('blog_thumbnail_height_mode', $height_mode);
    $thumb_custom_height = Airi()->settings()->get('blog_thumbnail_height_custom', $thumb_custom_height);
}

$post_class = array('blog__item', 'grid-item');

if($layout != 'list' || ( $layout == 'list' && $style != 1 ) ){
    $post_class[] = 'loop__item';
}

$post_class[] = ($show_excerpt ? 'show' : 'hide') . '-excerpt';

$thumb_css_style = '';

if ( 'original' !== $height_mode ) {
    $thumb_css_class = ' gitem-zone-height-mode-' . $height_mode;
}
else{
    $thumb_css_class = ' gitem-zone-height-mode-original2';
}

$thumb_src = '';
$thumb_width = $thumb_height = 0;
if (!has_post_thumbnail() || ($is_main_loop && !$show_thumbnail)) {
    $post_class[] = 'no-featured-image';
}
else{
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
        $thumb_css_style = 'padding-bottom: ' . $thumb_custom_height . ';';
        $thumb_css_class .= ' gitem-hide-img';
    }
}
elseif ( 'original' !== $height_mode ) {
	$thumb_css_style = '';
    $thumb_css_class .= ' gitem-hide-img gitem-zone-height-mode-auto' . ( strlen( $height_mode ) > 0 ? ' gitem-zone-height-mode-auto-' . $height_mode : '' );
}

$allow_featured_image = true;
if($is_main_loop && !$show_thumbnail){
    $allow_featured_image = false;
    $post_class[] = 'no-featured-image';
}

if($style == 'echeck' && !has_post_thumbnail()){
    $allow_featured_image = false;
    $post_class[] = 'no-featured-image';
}

$use_lazy_load = Airi_Helper::is_enable_image_lazy();
if($use_lazy_load){
    $thumb_css_class .= ' la-lazyload-image';
}

?>
<div <?php post_class($post_class); ?>>
    <div class="loop__item__inner">
        <div class="loop__item__inner2">
            <?php if( $allow_featured_image ) : ?>
            <div class="loop__item__thumbnail">
                <?php
                if('gallery' == get_post_format()){
                    $galleries = airi_get_image_for_post_type_gallery(get_the_ID(), $thumbnail_size);
                    $gallery_html = '';

                    $_thumb_css_style = $thumb_css_style;

                    foreach($galleries as $gallery){
                        if(!$use_lazy_load){
                            $_thumb_css_style = $thumb_css_style . sprintf('background-image: url(%s);', $gallery);
                        }
                        $gallery_html .= sprintf(
                            '<div class="g-item"><div class="loop__item__thumbnail--bkg %1$s" data-background-image="%2$s" style="%3$s"></div></div>',
                            esc_attr($thumb_css_class),
                            $gallery,
                            esc_attr($_thumb_css_style)
                        );
                    }
                    echo sprintf(
                        '<div data-la_component="AutoCarousel" class="js-el la-slick-slider" data-slider_config="%1$s">%2$s</div>',
                        esc_attr(json_encode(array(
                            'slidesToShow' => 1,
                            'slidesToScroll' => 1,
                            'dots' => false,
                            'arrows' => true,
                            'speed' => 300,
                            'autoplay' => false,
                            'infinite' => false,
                            'prevArrow'=> '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                            'nextArrow'=> '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>'
                        ))),
                        $gallery_html
                    );
                }
                ?>
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>"
                     data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>"
                     style="<?php
                        if(!$use_lazy_load && !empty($thumb_src)){
                            $thumb_css_style .= sprintf('background-image: url(%s);', $thumb_src);
                        }
                        echo esc_attr($thumb_css_style);
                     ?>"
                ><?php
                    if('gallery' != get_post_format() && has_post_thumbnail()) {
                        if ( has_post_thumbnail() ) {
                            echo Airi()->images()->render_image( $thumb_src, array(
                                'width' => $thumb_width,
                                'height' => $thumb_height,
                                'alt' => get_the_title()
                            ) );
                            echo sprintf( '<a href="%s" title="%s" class="loop__item__thumbnail--linkoverlay" rel="nofollow"><span class="pf-icon pf-icon-link"></span><span class="item--overlay"></span></a>', esc_url( get_the_permalink() ), the_title_attribute( array( 'echo' => false ) ) );
                        }
                    }
                ?></div>
                <?php

                if('quote' == get_post_format()){
                    airi_get_image_for_post_type_quote(get_the_ID());
                }

                if('gallery' == get_post_format()){
                    if(has_post_thumbnail()){
                        echo sprintf(
                            '<a href="%s" title="%s" class="loop__item__thumbnail--linkoverlay" rel="nofollow"><span class="pf-icon pf-icon-link"></span><span class="item--overlay"></span></a>',
                            esc_url(get_the_permalink()),
                            the_title_attribute(array('echo'=>false))
                        );
                    }
                }
                else{
                    if(has_post_thumbnail()){
                        echo sprintf(
                            '<a href="%s" title="%s" class="loop__item__thumbnail--linkoverlay2" rel="nofollow"><span class="pf-icon pf-icon-link"></span><span class="item--overlay"></span></a>',
                            esc_url(get_the_permalink()),
                            the_title_attribute(array('echo'=>false))
                        );
                    }
                }
                ?>
            </div>
            <?php endif; ?>
            <div class="loop__item__info">
                <div class="loop__item__info2">
                    <div class="loop__item__meta loop__item__meta__top"><?php

                        if($style == 'echeck'){
                            airi_entry_meta_item_category_list('<div class="loop__item__meta--item loop__item__termlink blog_item--category-link">', '</div>', '');
                        }
                        else{
                            if( ($layout == 'grid' && ($style != 2 && $style != 4 && $style != 5)) || ( $layout == 'list' && $style == 1 )){
                                airi_entry_meta_item_postdate();
                                airi_entry_meta_item_author();
                                airi_entry_meta_item_category_list('<div class="loop__item__meta--item loop__item__termlink blog_item--category-link">', '</div>', '');
                            }
                        }
                        if(($layout == 'grid' && $style == 5) || ($layout == 'list' && $style == 2)){
                            airi_entry_meta_item_category_list('<div class="only__term_links loop__item__meta--item loop__item__termlink blog_item--category-link">', '</div>', '');
                        }

                        if($layout == 'list' && $style == 'mini'){
                            airi_entry_meta_item_postdate();
                            airi_entry_meta_item_author();
                        }

                    ?></div>
                    <div class="loop__item__title">
                        <?php
                        $title_css_class = 'entry-title';
                        if($title_tag == 'h2' || $title_tag == 'h1'){
                            if($layout != 'list' && !empty($responsive_column['lg']) && $responsive_column['lg'] > 2){
                                $title_css_class .= ' h5';
                            }
                            else{
                                $title_css_class .= ' h3';
                            }
                        }
                        else if($title_tag == 'h3'){
                            $title_css_class .= ' h3';
                        }
                        else if($title_tag == 'h4'){
                            //$title_css_class .= ' h3';
                        }
                        echo sprintf(
                            '<%1$s class="%2$s"><a href="%3$s" title="%4$s">%5$s</a></%1$s>',
                            esc_attr($title_tag),
                            esc_attr($title_css_class),
                            esc_url(get_the_permalink()),
                            esc_attr(get_the_title()),
                            esc_html(get_the_title())
                        );
                        ?>
                    </div><?php
                    if($style == 'echeck'){
                        echo '<div class="loop__item__meta">';
                        airi_entry_meta_item_author(false);
                        airi_entry_meta_item_postdate();
                        echo '</div>';
                    }
                    else{
                        if( ($layout == 'grid' && ($style == 2 || $style == 4 || $style == 5)) || ( $layout == 'list' && ($style == 3 || $style == 2)) ){
                            echo '<div class="loop__item__meta loop__item__meta__middle">';
                                airi_entry_meta_item_postdate();
                                airi_entry_meta_item_author();
                            echo '</div>';
                        }
                    }
                    ?>
                    <?php if ($show_excerpt): ?>
                    <div class="loop__item__desc">
                        <?php
                            echo airi_get_the_excerpt( $loop_name == 'related_posts' ? $excerpt_length : null);
                        ?>
                    </div>
                    <?php endif; ?>
                    <?php
                    $readmore_class = 'btn-readmore';
                    printf(
                        '<div class="loop__item__meta--footer"><a class="%3$s" href="%1$s">%2$s</a></div>',
                        get_the_permalink(),
                        esc_html_x('Read more', 'front-view', 'airi'),
                        esc_attr($readmore_class)
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

if($layout == 'list' && $style == 1){
    if($loop_index == 1){
        echo '</div><div class="loop__item__two loop__item">';
    }
    else{
        if($loop_index == 3){
            echo '</div><div class="loop__item__one loop__item">';
            $loop_index = 0;
        }
    }
    $loop_index++;
    airi_set_theme_loop_prop('loop_index', $loop_index);
}