<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_style     = airi_get_theme_loop_prop('loop_style', 1);
$thumbnail_size = airi_get_theme_loop_prop('image_size', 'full');
$title_tag      = airi_get_theme_loop_prop('title_tag', 'h5');
$post_class     = array('loop__item','grid-item','release__item');

$release_itunes = Airi()->settings()->get_post_meta( get_the_ID(), '', '_lastudio_release_itunes' );
$release_gplay  = Airi()->settings()->get_post_meta( get_the_ID(), '', '_lastudio_release_google_play' );
$release_amazon = Airi()->settings()->get_post_meta( get_the_ID(), '', '_lastudio_release_amazon' );
$release_buy    = Airi()->settings()->get_post_meta( get_the_ID(), '', '_lastudio_release_buy' );
$release_free   = Airi()->settings()->get_post_meta( get_the_ID(), '', '_lastudio_release_free' );

$height_mode        = airi_get_theme_loop_prop('height_mode', 'original');
$thumb_custom_height= airi_get_theme_loop_prop('height', '');
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
        $thumb_css_style .= 'height: ' . $thumb_custom_height . ';';
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
<article itemscope itemtype="http://schema.org/MusicAlbum" <?php post_class($post_class)?>>
    <div class="loop__item__inner">
        <div class="loop__item__inner2">
            <?php do_action('lastudio_release_start'); ?>
            <div class="loop__item__thumbnail">
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>"
                     data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>"
                     style="<?php echo esc_attr($thumb_css_style); ?>">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="loop__item__thumbnail--linkoverlay"><span class="hidden"><?php the_title(); ?></span></a>
                    <?php echo Airi()->images()->render_image($thumb_src, array('width' => $thumb_width, 'height' => $thumb_height, 'alt' => get_the_title())); ?>
                </div>
                <div class="release__item__applink">
                    <?php
                    if(!empty($release_itunes)){
                        echo sprintf(
                            '<a class="release__item__applink-item release__item__applink--itunes" href="%1$s" title="%2$s" target="_blank"><span>%2$s</span></a>',
                            esc_url($release_itunes),
                            esc_attr_x('Buy on iTunes', 'front-end', 'airi')
                        );
                    }
                    if(!empty($release_gplay)){
                        echo sprintf(
                            '<a class="release__item__applink-item release__item__applink--gplay" href="%1$s" title="%2$s" target="_blank"><span>%2$s</span></a>',
                            esc_url($release_gplay),
                            esc_attr_x('Buy on Google Play', 'front-end', 'airi')
                        );
                    }
                    ?>
                </div>
            </div>
            <div class="loop__item__info">
                <div class="loop__item__info2">
                    <div class="loop__item__title">
                        <?php the_title(sprintf('<%s class="entry-title"><a href="%s" title="%s">', $title_tag, esc_url(get_the_permalink()), the_title_attribute(array('echo'=>false))), sprintf('</a></%s>', $title_tag)); ?>
                    </div>
                    <?php if(function_exists('ld_get_artist')){
                        echo sprintf('<div class="loop__item__meta">%s</div>', ld_get_artist());
                    }?>
                </div>
            </div>
        </div>
    </div>
</article>