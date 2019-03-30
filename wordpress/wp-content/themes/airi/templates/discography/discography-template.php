<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header(); ?>
<?php do_action( 'airi/action/before_render_main' ); ?>
<div id="main" class="site-main">
	<div class="container">
		<div class="row">
			<main id="site-content" class="<?php echo esc_attr(Airi()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
				<div class="site-content-inner">

					<?php do_action( 'airi/action/before_render_main_inner' ); ?>

					<div id="blog_content_container" class="main--loop-container"><?php

						do_action( 'airi/action/before_render_main_content' );

						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$posts_per_page = apply_filters( 'airi_ld_posts_per_page', -1 );

						$args = array(
							'post_type' => 'ld_release',
							'posts_per_page' => $posts_per_page,
						);

						if ( -1 < $posts_per_page ) {
							$args['paged'] = $paged;
						}

						$loop = new WP_Query( $args );

						global $airi_loop;

						$tmp = $airi_loop;

						$airi_loop = array();

						$loop_layout = Airi()->settings()->get('discography_display_type', 'grid');
						$loop_style = Airi()->settings()->get('discography_display_style', '1');
						airi_set_theme_loop_prop('is_main_loop', true, true);
						airi_set_theme_loop_prop('loop_layout', $loop_layout);
						airi_set_theme_loop_prop('loop_style', $loop_style);
						airi_set_theme_loop_prop('responsive_column', Airi()->settings()->get('discography_column', array('xlg'=> 4, 'lg'=> 4,'md'=> 3,'sm'=> 2,'xs'=> 1)));
						airi_set_theme_loop_prop('image_size', Airi_Helper::get_image_size_from_string(Airi()->settings()->get('discography_thumbnail_size', 'full'),'full'));
						airi_set_theme_loop_prop('title_tag', 'h5');
						airi_set_theme_loop_prop('excerpt_length', 15);
						airi_set_theme_loop_prop('item_space', 'default');

						if($loop->have_posts()):

							get_template_part('templates/discography/loop/start');

							$blog_template = 'templates/discography/loop/loop';

							while($loop->have_posts()):

								$loop->the_post();
								get_template_part($blog_template);

							endwhile;

							get_template_part('templates/discography/loop/end');

						else:

							?>
							<p class="no-release"><?php _e( 'No release yet', 'airi' ); ?></p>
							<?php

						endif;

						/**
						 * Display pagination and reset loop
						 */

						airi_the_pagination(array(), $loop);

						wp_reset_postdata();

						$tmp = $airi_loop;

						do_action( 'airi/action/after_render_main_content' ); ?>

					</div>

					<?php do_action( 'airi/action/after_render_main_inner' ); ?>
				</div>
			</main>
			<?php get_sidebar();?>
		</div>
	</div>
</div>
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>
