<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

do_action( 'airi/action/before_render_main' );
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

                                <div id="post-<?php the_ID(); ?>" <?php post_class('single-post-content'); ?>>
                                    <div class="entry-content">
                                        <?php
                                        the_content();
                                        ?>
                                    </div><!-- .entry-content -->

                                </div><!-- #post-## -->

                            <?php

                            endif;

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
</div>

<!-- .site-main -->
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>
