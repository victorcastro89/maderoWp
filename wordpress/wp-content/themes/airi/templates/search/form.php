<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
    <div class="sf-fields">
        <div class="sf-field sf-field-input">
            <input autocomplete="off" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here&hellip;', 'front-view', 'airi' ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'front-view', 'airi' ); ?>" />
            <?php if(function_exists('WC')): ?>
                <input type="hidden" name="post_type" value="product"/>
            <?php endif; ?>
        </div>
        <button class="search-button" type="submit"><i class="dl-icon-search1"></i></button>
    </div>
</form>
<!-- .search-form -->