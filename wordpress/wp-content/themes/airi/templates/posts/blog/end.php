<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/*
 * Template loop-end
 */

$layout             = airi_get_theme_loop_prop('loop_layout', 'grid');
$style              = airi_get_theme_loop_prop('loop_style', 1);

global $airi_loop;
$airi_loop = array();
$blog_pagination_type = Airi()->settings()->get('blog_pagination_type', 'pagination');

if($layout == 'list' && $style == 1){
    echo '</div>';
}

echo '</div>';
?>
<!-- ./end-main-loop -->
<?php if($blog_pagination_type == 'load_more'): ?>
    <div class="blog-main-loop__btn-loadmore">
        <a href="javascript:;">
            <span><?php echo esc_html_x('Load more posts', 'front-view', 'airi'); ?></span>
        </a>
    </div>
<?php endif; ?>