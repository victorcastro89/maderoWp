<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( post_password_required() ) {
	return;
}

?>

<div id="comments" class="comments-area clearfix">
	<div class="comments-container">
		<?php if ( have_comments() ) : ?>
			<div class="comments-title">
				<?php
					$number = get_comments_number();
				?>
				<h3><?php
					if($number > 1){
						echo esc_html_x('Comments', 'front-view','airi');
						echo ' (' . get_comments_number() . ') ';
					}
					else{
						echo esc_html_x('Comments', 'front-view', 'airi');
					}
				?></h3>
			</div>
			<ul class="commentlist">
				<?php
				wp_list_comments( array(
					'callback' => 'airi_comment_form_callback',
					'style'      => 'ul',
					'avatar_size'=> 70,
				) );
				?>
			</ul><!-- .comment-list -->
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<div class="pagination">';
				paginate_comments_links( array(
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
						'type'      => 'list'
				));
				echo '</div>';
			endif; ?>
		<?php else:?>
			<p class="woocommerce-noreviews"><?php echo esc_html_x( 'There are no comments', 'front-view', 'airi' ); ?></p>
		<?php endif;?>
	</div>
	<?php
	if ( comments_open() ){
		comment_form(array(
			'class_submit'	=> 'btn'
		));
	}else{
		echo '<div class="clearfix"></div><p class="no-comments">'. esc_html_x( 'Comments are closed.', 'front-view', 'airi' ) .'</p>';
	}?>

</div><!-- #comments -->