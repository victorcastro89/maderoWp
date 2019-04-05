<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$el_class = $output = '';

$atts = extract(shortcode_atts(array(
    'el_class' => ''
), $atts ));
?>
<div class="la-breadcrumbs <?php echo esc_attr($el_class) ?>">
    <?php do_action('airi/action/breadcrumbs/render_html'); ?>
</div>