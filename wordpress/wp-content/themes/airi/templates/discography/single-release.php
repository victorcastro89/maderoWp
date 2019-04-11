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
						<div class="single-post-content single-release-content clearfix">
							<article itemscope itemtype="http://schema.org/MusicAlbum" data-post-id="<?php the_ID(); ?>" id="post-<?php the_ID(); ?>"  <?php post_class(); ?>>

								<?php do_action( 'airi/action/before_render_main_content' ); ?>

								<?php
								/**
								 * lastudio_release_start_hook
								 */
								do_action( 'lastudio_release_start' );
								?>

								<div class="release-content">

									<?php
									/**
									 * Tracklists
									 */
									ld_release_tracklist();
									?>

									<div class="entry-content">
										<?php the_content(); ?>
									</div><!-- .entry-content -->

									<?php
									/**
									 * Buy Buttons
									 */
									ld_release_buttons();
									?>

								</div>

								<div class="release-info-container">
									<div class="release-thumbnail">
										<?php
										/**
										 * Cover
										 */
										ld_release_thumbnail();
										?>
									</div>
									<h1 class="entry-title h5"><?php the_title(); ?></h1>
									<div class="release-meta-container">
										<?php ld_release_meta(); ?>
									</div>
								</div>

								<?php do_action( 'airi/action/after_render_main_content' );?>

							</article>
						</div>
					</div>

					<?php do_action( 'airi/action/after_render_main_inner' );?>
				</div>
			</main>
			<!-- #site-content -->
			<?php get_sidebar();?>
		</div>
		<div class="row portfolio-nav">
			<div class="col-xs-4">
				<?php
				$prev = get_previous_post();
				if(!empty($prev) && isset($prev->ID)){
					printf(
						'<a href="%s"><i class="dl-icon-left"></i><span>%s</span></a>',
						get_the_permalink($prev->ID),
						esc_html_x('Preview', 'front-end', 'airi')
					);
				}
				?>
			</div>
			<div class="col-xs-4">
				<?php

				$discography_get_page_id = lastudio_discography_get_page_id();
				if($discography_get_page_id > 0){
					echo '<div class="nav-parents">';
					echo sprintf('<a href="%s"><i class="dl-icon-menu5"></i></a>',
						esc_url(get_the_permalink($discography_get_page_id))
					);
					echo '</div>';
				}

				?>
			</div>
			<div class="col-xs-4">
				<?php
				$next = get_next_post();
				if(!empty($next) && isset($next->ID)){
					printf(
						'<a href="%s"><span>%s</span><i class="dl-icon-right"></i></a>',
						get_the_permalink($next->ID),
						esc_html_x('Next', 'front-end', 'airi')
					);
				}
				?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<!-- .site-main -->
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>
