<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
do_action( 'airi/action/before_render_main' );

$enable_related = Airi()->settings()->get('blog_related_posts', 'off');
$related_style = Airi()->settings()->get('blog_related_design', 1);
$max_related = (int) Airi()->settings()->get('blog_related_max_post', 1);
$related_by = Airi()->settings()->get('blog_related_by', 'category');

$header_layout = Airi()->layout()->get_header_layout();

$single_post_thumbnail_size = Airi_Helper::get_image_size_from_string(Airi()->settings()->get('single_post_thumbnail_size', 'full'), 'full');

$hide_breadcrumb = Airi()->settings()->get_post_meta(get_the_ID(), 'hide_breadcrumb');
$page_title_bar_layout = Airi()->layout()->get_page_title_bar_layout();

$custom_author = Airi()->settings()->get_post_meta(get_the_ID(), 'custom_author');

?>

<div id="main" class="site-main">
    <div class="container">
        <div class="row">
            <main id="site-content" class="<?php echo esc_attr(Airi()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
                <div class="site-content-inner">

                    <?php do_action( 'airi/action/before_render_main_inner' );?>

                    <div class="page-content">

                        <div class="single-post-detail clearfix">
                            <?php

                            do_action( 'airi/action/before_render_main_content' );

                            if( have_posts() ):  the_post(); ?>

                                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-content'); ?>>

                                    <?php
                                    if('above' == Airi()->settings()->get('blog_post_title')){
                                        the_title( '<header class="entry-header entry-header-above single_post_item--title"><h1 class="entry-title h3">', '</h1></header>' );
                                        if( $page_title_bar_layout == 'hide' && $hide_breadcrumb != 'yes'){
                                            echo '<div class="la-breadcrumbs text-color-secondary">';
                                            do_action('airi/action/breadcrumbs/render_html');
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                    <?php
                                        if(Airi()->settings()->get('featured_images_single') == 'on'){
                                            airi_single_post_thumbnail($single_post_thumbnail_size);
                                        }
                                    ?>

                                    <?php
                                    if($header_layout == 'default'){
                                        echo '<div class="showposts-loop"><div class="single_post_item--meta loop__item__meta entry-meta clearfix">';
                                        airi_entry_meta_item_category_list('<div class="loop__item__meta--item loop__item__termlink blog_item--category-link">', '</div>', '');
                                        airi_entry_meta_item_postdate();
                                        airi_entry_meta_item_author();
                                        echo '</div></div>';
                                    }
                                    ?>

                                    <?php
                                        if('below' == Airi()->settings()->get('blog_post_title') ){
                                            echo '<header class="entry-header entry-header-below entry-header single_post_item--title">';
                                            the_title( '<h1 class="entry-title h3">', '</h1>' );
                                            echo '</header>';
                                        }
                                        if($header_layout != 'default'){
                                            echo '<div class="showposts-loop">';
                                            echo '<div class="single_post_item--meta loop__item__meta entry-meta">';
                                            airi_entry_meta_item_postdate();
                                            airi_entry_meta_item_author();
                                            airi_entry_meta_item_category_list('<div class="loop__item__meta--item loop__item__termlink blog_item--category-link">', '</div>', '');
                                            echo '</div>';
                                            echo '<div class="psingle-right">';
                                            airi_entry_meta_item_comment_post_link_with_icon();
                                            airi_get_favorite_link();
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    ?>

                                    <div class="entry-content">
                                        <?php

                                        the_content();

                                        wp_link_pages( array(
                                            'before'      => '<div class="clearfix"></div><div class="page-links"><span class="page-links-title">' . esc_html_x( 'Pages:', 'front-view', 'airi' ) . '</span>',
                                            'after'       => '</div>',
                                            'link_before' => '<span>',
                                            'link_after'  => '</span>',
                                            'pagelink'    => '<span class="screen-reader-text">' . esc_html_x( 'Page', 'front-view', 'airi' ) . ' </span>%',
                                            'separator'   => '<span class="screen-reader-text">, </span>',
                                        ) );
                                        ?>
                                    </div><!-- .entry-content -->
                                    <div class="clearfix"></div>
                                    <footer class="entry-footer text-color-secondary"><?php
                                            the_tags('<div class="entry-meta-footer"><div class="tags-list"><span><i class="fa fa-tags"></i></span><span class="tags-list-item">' ,', ','</span></div></div>');

                                            if(!empty($custom_author)){
                                                printf('<div class="entry-meta-footer custom-post-author">%s</div>', esc_html($custom_author));
                                            }
                                            edit_post_link( null, '<span class="edit-link hidden">', '</span>' );
                                    ?></footer><!-- .entry-footer -->
                                    <?php
                                    if(Airi()->settings()->get('blog_social_sharing_box') == 'on'){
                                        echo '<div class="la-sharing-single-posts">';
                                        echo sprintf('<span>%s <i class="fa fa-share-alt"></i></span>', esc_html_x('Share this post', 'front-view', 'airi') );
                                        airi_social_sharing(get_the_permalink(), get_the_title(), (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : ''));
                                        echo '</div>';
                                    }
                                    ?>
                                </article><!-- #post-## -->

                                <div class="clearfix"></div>

                                <?php

                                if(Airi()->settings()->get('blog_pn_nav') == 'on'){
                                    the_post_navigation( array(
                                        'next_text' => '<span class="blog_pn_nav-text">'. esc_html__('Next Post', 'airi') .'</span><span class="blog_pn_nav blog_pn_nav-left">%image</span><span class="blog_pn_nav blog_pn_nav-right"><span class="blog_pn_nav-title">%title</span><span class="blog_pn_nav-meta">%date - %author</span></span>',
                                        'prev_text' => '<span class="blog_pn_nav-text">'. esc_html__('Prev Post', 'airi') .'</span><span class="blog_pn_nav blog_pn_nav-left">%image</span><span class="blog_pn_nav blog_pn_nav-right"><span class="blog_pn_nav-title">%title</span><span class="blog_pn_nav-meta">%date - %author</span></span>'
                                    ) );
                                    echo '<div class="clearfix"></div>';
                                }


                                if(Airi()->settings()->get('blog_author_info') == 'on'){
                                    get_template_part( 'author-bio' );
                                    echo '<div class="clearfix"></div>';
                                }

                                if(Airi()->settings()->get('blog_comments') == 'on' && ( comments_open() || get_comments_number() ) ){
                                    comments_template();
                                    echo '<div class="clearfix"></div>';
                                }

                                ?>

                        <?php endif; ?>

                            <?php

                            do_action( 'airi/action/after_render_main_content' );

                            wp_reset_postdata();

                            ?>

                        </div>

                    </div>

                    <?php do_action( 'airi/action/after_render_main_inner' );?>
                </div>
            </main>
            <!-- #site-content -->
            <?php get_sidebar();?>

        </div>
    </div>
    <?php
    if($enable_related == 'on') {
        wp_reset_postdata();
        $related_args = array(
            'posts_per_page' => $max_related,
            'post__not_in' => array(get_the_ID())
        );
        if ($related_by == 'random') {
            $related_args['orderby'] = 'rand';
        }
        if ($related_by == 'category') {
            $cats = wp_get_post_terms(get_the_ID(), 'category');
            if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
                $related_args['category__in'] = array($cats[0]->term_id);
            }
        }
        if ($related_by == 'tag') {
            $tags = wp_get_post_terms(get_the_ID(), 'tag');
            if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
                $related_args['tag__in'] = array($tags[0]->term_id);
            }
        }
        if ($related_by == 'both') {
            $cats = wp_get_post_terms(get_the_ID(), 'category');
            if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
                $related_args['category__in'] = array($cats[0]->term_id);
            }
            $tags = wp_get_post_terms(get_the_ID(), 'tag');
            if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
                $related_args['tag__in'] = array($tags[0]->term_id);
            }
        }

        $related_query = new WP_Query($related_args);

        if ($related_query->have_posts()) { ?>
            <div class="row-related-posts related-posts-design-<?php echo esc_attr($related_style); ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="la-related-posts">
                                <div class="row block_heading">
                                    <div class="col-xs-12">
                                        <h2 class="block_heading--title"><span><?php
                                            if($related_style == 1){
                                                echo esc_html_x('Related Post', 'front-view', 'airi');
                                            }
                                            else{
                                                esc_html_x('Next Post', 'front-view', 'airi');
                                            }
                                        ?></span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="la-related-posts">
                                <?php

                                airi_set_theme_loop_prop('loop_name', 'related_posts', true);
                                airi_set_theme_loop_prop('loop_layout', 'grid');
                                airi_set_theme_loop_prop('loop_style', 3);
                                airi_set_theme_loop_prop('excerpt_length', 18);
                                airi_set_theme_loop_prop('title_tag', 'h2');
                                airi_set_theme_loop_prop('height_mode', 'custom');
                                airi_set_theme_loop_prop('height', '76%');
                                airi_set_theme_loop_prop('item_space', 'default');

                                $responsive_column = array(
                                    'xlg' => 3,
                                    'lg' => 3,
                                    'md' => 3,
                                    'sm' => 2,
                                    'xs' => 2,
                                    'mb' => 1
                                );
                                if($related_style == 2) {
                                    $responsive_column = array(
                                        'xlg' => 1,
                                        'lg' => 1,
                                        'md' => 1,
                                        'sm' => 1,
                                        'xs' => 1,
                                        'mb' => 1
                                    );
                                }

                                $slider_configs = 'data-slider_config="'. esc_attr(Airi_Helper::get_slick_slider_config(array_merge(array('arrows' => false), $responsive_column))) .'"';

                                airi_set_theme_loop_prop('slider_configs', $slider_configs);
                                airi_set_theme_loop_prop('responsive_column', $responsive_column);
                                airi_set_theme_loop_prop('image_size', '370x280');
                                airi_set_theme_loop_prop('is_main_loop', false);

                                if( $related_style == 2 ) {
                                    airi_set_theme_loop_prop('show_thumbnail', false);
                                }

                                get_template_part('templates/posts/start');

                                while ($related_query->have_posts()) {
                                    $related_query->the_post();
                                    get_template_part('templates/posts/loop');
                                }

                                get_template_part('templates/posts/end');

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        wp_reset_postdata();
    }
    ?>
</div>
<!-- .site-main -->
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>
