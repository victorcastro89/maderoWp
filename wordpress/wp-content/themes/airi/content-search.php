<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$title_tag = 'h2';

$post_class = array('loop__item', 'blog__item', 'grid-item', 'hide-excerpt');

?>
<div <?php post_class($post_class); ?>>
    <div class="loop__item__inner">
        <div class="loop__item__inner2">
            <div class="loop__item__info">
                <div class="loop__item__info2">
                    <div class="loop__item__title">
                        <?php the_title(sprintf('<%s class="entry-title h3"><a href="%s" title="%s">', $title_tag, esc_url(get_the_permalink()), the_title_attribute(array('echo'=>false))), sprintf('</a></%s>', $title_tag)); ?>
                    </div>

                    <div class="loop__item__desc">
                        <?php
                        echo airi_get_the_excerpt();
                        ?>
                    </div>

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