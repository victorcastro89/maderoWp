<div class="searchform-fly-overlay">
    <a href="javascript:;" class="btn-close-search"><i class="dl-icon-close"></i></a>
    <div class="searchform-fly">
        <p><?php echo esc_html_x('Start typing and press Enter to search', 'front-view', 'airi')?></p>
        <?php
        if(function_exists('get_product_search_form')){
            get_product_search_form();
        }
        else{
            get_search_form();
        }
        ?>
        <div class="search-results">
            <div class="loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
            <div class="results-container"></div>
            <div class="view-more-results text-center">
                <a href="#" class="button search-results-button"><?php echo esc_html_x('View more', 'front-end', 'airi') ?></a>
            </div>
        </div>
    </div>
</div>
<!-- .search-form -->

<div class="cart-flyout">
    <div class="cart-flyout--inner">
        <a href="javascript:;" class="btn-close-cart"><i class="dl-icon-close"></i></a>
        <div class="cart-flyout__content">
            <div class="cart-flyout__heading"><?php echo esc_html_x('Shopping Cart', 'front-view', 'airi') ?></div>
            <div class="cart-flyout__loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
            <div class="widget_shopping_cart_content"><?php
                if(function_exists('woocommerce_mini_cart')){
                    woocommerce_mini_cart();
                }
            ?></div>
            <?php
            $aside_cart_widget = apply_filters('airi/filter/aside_cart_widget', 'aside-cart-widget');
            if(is_active_sidebar($aside_cart_widget)){
                echo '<div class="aside_cart_widget">';
                dynamic_sidebar($aside_cart_widget);
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="la-overlay-global"></div>

<?php
$show_popup = Airi()->settings()->get('enable_newsletter_popup');
$only_home_page = Airi()->settings()->get('only_show_newsletter_popup_on_home_page');
$delay = Airi()->settings()->get('newsletter_popup_delay', 2000);
$popup_content = Airi()->settings()->get('newsletter_popup_content');
$show_checkbox = Airi()->settings()->get('show_checkbox_hide_newsletter_popup', false);
$back_display_time = Airi()->settings()->get('newsletter_popup_show_again', '1');
if($show_popup){
    if($only_home_page && !is_front_page()){
        $show_popup = false;
    }
}
if($show_popup && !empty($popup_content)):
    ?>
    <div data-la_component="NewsletterPopup" class="js-el la-newsletter-popup" data-waitfortrigger="0" data-back-time="<?php echo esc_attr( floatval($back_display_time) ); ?>" data-show-mobile="<?php echo Airi()->settings()->get('disable_popup_on_mobile') ? 1 : 0 ?>" id="la_newsletter_popup" data-delay="<?php echo esc_attr( absint($delay) ); ?>">
        <a href="#" class="btn-close-newsletter-popup"><i class="dl-icon-close"></i></a>
        <div class="newsletter-popup-content"><?php echo Airi_Helper::remove_js_autop($popup_content); ?></div>
        <?php if($show_checkbox): ?>
            <label class="lbl-dont-show-popup"><input type="checkbox" class="cbo-dont-show-popup" id="dont_show_popup"/><?php echo esc_html(Airi()->settings()->get('popup_dont_show_text')) ?></label>
        <?php endif;?>
    </div>
<?php endif; ?>