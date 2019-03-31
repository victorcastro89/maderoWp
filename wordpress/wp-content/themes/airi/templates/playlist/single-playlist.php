<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

the_post();

do_action( 'airi/action/before_render_main' ); ?>
	<div id="main" class="site-main">
		<div class="container">
			<div class="row">
				<main id="site-content" class="<?php echo esc_attr(Airi()->layout()->get_main_content_css_class('col-xs-12 site-content'))?>">
					<div class="site-content-inner">

						<?php do_action( 'airi/action/before_render_main_inner' );?>

						<div class="page-content">
							<div class="single-post-content single-portfolio-content clearfix">
								<?php

								do_action( 'airi/action/before_render_main_content' );

								if(function_exists('lpm_playlist')){
									lpm_playlist( get_the_ID() );
								}

								echo '<div class="playlist-single-page">';
								the_content();
								echo '</div>';

								do_action( 'airi/action/after_render_main_content' );

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
	<div class="clearfix"></div>
	<!-- .site-main -->
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>