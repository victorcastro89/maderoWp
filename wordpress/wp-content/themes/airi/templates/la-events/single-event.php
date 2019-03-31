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

								<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-content'); ?> itemtype="http://schema.org/Event" itemscope>

									<?php

									if(function_exists('le_get_event_meta')){
										extract( le_get_event_meta() );
										?>
										<meta itemprop="name" content="<?php echo esc_attr( $name ); ?>">
										<meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>">
										<?php if ( $thumbnail_url ) : ?>
											<meta itemprop="image" content="<?php echo esc_url( $thumbnail_url ); ?>">
										<?php endif; ?>
										<meta itemprop="description" content="<?php echo esc_attr( $description ); ?>">
										<div class="row">
											<div class="col-xs-12 col-sm-4 col-md-3">
												<div class="event-thumbnail">
													<a class="lightbox" href="<?php echo get_the_post_thumbnail_url( '', '%SLUG-XL%' ); ?>">
														<?php the_post_thumbnail(); ?>
													</a>
												</div><!-- .event-thumbnail -->
												<?php if ( $artist ) : ?>
													<div class="event-artist">
														<strong><?php echo wp_kses_post( $artist ); ?></strong>
													</div><!-- .event-artist -->
												<?php endif; ?>
												<div class="event-date">
													<?php if ( $raw_start_date ) : ?>
														<strong class="start-date" itemprop="startDate" content="<?php echo esc_attr( $raw_start_date ); ?>">
															<?php echo le_nice_date( $raw_start_date ); ?>
														</strong>
													<?php endif; ?>
													<?php if ( $raw_end_date ) : ?>
														<span>&mdash;</span>
														<strong class="end-date" itemprop="endDate" content="<?php echo esc_attr( $raw_end_date ); ?>">
															<?php echo le_nice_date( $raw_end_date ); ?>
														</strong>
													<?php endif; ?>
												</div><!-- .event-date -->
												<?php if ( $display_location ) : ?>
													<div class="event-location">
														<strong><?php echo sanitize_text_field( $display_location ); ?></strong>
													</div><!-- .event-location -->
												<?php endif; ?>
												<div class="event-buttons">
													<?php if ( $cancelled ) : ?>
														<strong class="event-status"><?php esc_html_e( 'Cancelled', 'airi' ); ?></strong>
													<?php elseif ( $soldout ) : ?>
														<strong class="event-status"><?php esc_html_e( 'Sold Out', 'airi' ); ?></strong>
													<?php elseif ( $free ) : ?>
														<strong class="event-status"><?php esc_html_e( 'Free', 'airi' ); ?></strong>
													<?php elseif ( $ticket_url ) : ?>
														<a target="_blank" class="<?php echo apply_filters( 'airi_single_event_buy_ticket_button_class', 'btn' ); ?>" href="<?php echo esc_url( $ticket_url ); ?>"><span class="fa fa-shopping-cart"></span><?php esc_html_e( 'Buy Ticket', 'airi' ); ?></a>
													<?php endif; ?>
													<?php if ( $facebook_url ) : ?>
														<a target="_blank" class="<?php echo apply_filters( 'airi_single_event_fb_button_class', 'btn fb-button' ); ?>" href="<?php echo esc_url( $facebook_url ); ?>"><span class="fa fa-facebook"></span><?php esc_html_e( 'facebook event', 'airi' ); ?></a>
													<?php endif; ?>
												</div>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-9 event-container">
												<?php if ( $map ) : ?>
													<div class="event-map">
														<?php echo le_get_iframe( $map ); ?>
													</div><!-- .event-map -->
												<?php endif; ?>
												<div class="event-details">
													<?php if ( $time && '00:00' !== $time ) : ?>
														<div class="event-time">
															<strong><?php esc_html_e( 'Time', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $time ); ?>
														</div><!-- .event-time -->
													<?php endif; ?>
													<?php if ( $venue ) : ?>
														<div class="event-venue">
															<strong><?php esc_html_e( 'Venue', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $venue ); ?>
														</div><!-- .event-venue -->
													<?php endif; ?>
													<?php if ( $address ) : ?>
														<div class="event-address">
															<strong><?php esc_html_e( 'Address', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $address ); ?>
														</div><!-- .event-address -->
													<?php endif; ?>
													<?php if ( $zipcode ) : ?>
														<div class="event-zipcode">
															<strong><?php esc_html_e( 'Zipcode', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $zipcode ); ?>
														</div><!-- .event-zipcode -->
													<?php endif; ?>
													<?php if ( $state ) : ?>
														<div class="event-state">
															<strong><?php esc_html_e( 'State', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $state ); ?>
														</div><!-- .event-state -->
													<?php endif; ?>
													<?php if ( $country ) : ?>
														<div class="event-country">
															<strong><?php esc_html_e( 'Country', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $country ); ?>
														</div><!-- .event-country -->
													<?php endif; ?>
													<?php if ( $phone ) : ?>
														<div class="event-phone">
															<strong><?php esc_html_e( 'Phone', 'airi' ); ?></strong>: <?php echo sanitize_text_field( $phone ); ?>
														</div><!-- .event-phone -->
													<?php endif; ?>
													<?php if ( $email ) : ?>
														<div class="event-email">
															<strong><?php esc_html_e( 'Email', 'airi' ); ?></strong>: <a href="mailto:<?php echo sanitize_email( $email ); ?>"><?php echo sanitize_email( $email ); ?></a>
														</div><!-- .event-email -->
													<?php endif; ?>
													<?php if ( $website ) : ?>
														<div class="event-website">
															<strong><?php esc_html_e( 'Website', 'airi' ); ?></strong>: <a href="<?php echo esc_url( $website ); ?>" target="_blank"><?php echo esc_url( $website ); ?></a>
														</div><!-- .event-website -->
													<?php endif; ?>
												</div><!-- .event-details -->
												<div class="event-content">
													<?php the_content(); ?>
												</div><!-- .event-content -->
												
											</div>
										</div>
									<?php
									}
									?>

								</article><!-- #post-## -->

								<div class="clearfix"></div>

								<?php


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
</div>
<div class="clearfix"></div>
<!-- .site-main -->
<?php do_action( 'airi/action/after_render_main' ); ?>
<?php get_footer();?>
