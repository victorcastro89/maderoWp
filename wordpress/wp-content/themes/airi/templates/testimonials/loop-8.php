<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$loop_style     = airi_get_theme_loop_prop('loop_style', 1);
$title_tag      = airi_get_theme_loop_prop('title_tag', 'h3');

$role           = Airi()->settings()->get_post_meta(get_the_ID(),'role');
$content        = Airi()->settings()->get_post_meta(get_the_ID(),'content');
$avatar         = Airi()->settings()->get_post_meta(get_the_ID(),'avatar');
$rating         = Airi()->settings()->get_post_meta(get_the_ID(),'rating');
$post_class     = array('loop__item', 'grid-item', 'testimonial_item');

?>
<div <?php post_class($post_class)?>>
    <div class="loop__item__inner">
        <div class="loop__item__inner2">
            <div class="loop__item__info">
                <div class="loop__item__info2">
                    <div class="loop__item__desc"><?php echo esc_html($content);?></div>
                    <div class="loop__item__meta">
                        <?php
                        if(!empty($rating)){
                            echo sprintf(
                                '<p class="item--rating"><span class="star-rating"><span style="width: %s"></span></span></p>',
                                esc_attr(absint($rating) * 10) . '%'
                            );
                        }
                        ?>
                    </div>
                    <div class="loop__item__title">
                        <?php
                        echo sprintf(
                            '<%1$s class="%4$s">%3$s</%1$s>',
                            esc_attr($title_tag),
                            'javascript:;',
                            get_the_title(),
                            'entry-title'
                        );
                        if(!empty($role)){
                            echo sprintf(
                                '<p class="testimonial_item--role">%s</p>',
                                esc_html($role)
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>