<?php if ( ! defined( 'ABSPATH' ) ) { die; }

add_filter('LaStudio/global_loop_variable', 'airi_set_loop_variable');
if(!function_exists('airi_set_loop_variable')){
    function airi_set_loop_variable( $var = ''){
        return 'airi_loop';
    }
}

add_filter('LaStudio/core/google_map_api', 'airi_add_googlemap_api');
if(!function_exists('airi_add_googlemap_api')){
    function airi_add_googlemap_api( $key = '' ){
        return Airi()->settings()->get('google_key', $key);
    }
}

add_filter('airi/filter/page_title', 'airi_override_page_title_bar_title', 10, 2);
if(!function_exists('airi_override_page_title_bar_title')){
    function airi_override_page_title_bar_title( $title, $args ){

        $context = (array) Airi()->get_current_context();

        if(in_array('is_singular', $context)){
            $custom_title = Airi()->settings()->get_post_meta( get_queried_object_id(), 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        if(in_array('is_tax', $context) || in_array('is_category', $context) || in_array('is_tag', $context)){
            $custom_title = Airi()->settings()->get_term_meta( get_queried_object_id(), 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        if(in_array('is_shop', $context) && function_exists('wc_get_page_id') && ($shop_page_id = wc_get_page_id('shop')) && $shop_page_id){
            $custom_title = Airi()->settings()->get_post_meta( $shop_page_id, 'page_title_custom');
            if(!empty( $custom_title) ){
                return sprintf($args['page_title_format'], $custom_title);
            }
        }

        return $title;
    }
}

add_action( 'pre_get_posts', 'airi_set_posts_per_page_for_portfolio_cpt' );
if(!function_exists('airi_set_posts_per_page_for_portfolio_cpt')){
    function airi_set_posts_per_page_for_portfolio_cpt( $query ) {
        if ( !is_admin() && $query->is_main_query() ) {
            if( is_post_type_archive( 'la_portfolio' ) || is_tax(get_object_taxonomies( 'la_portfolio' ))){
                $pf_per_page = (int) Airi()->settings()->get('portfolio_per_page', 9);
                $query->set( 'posts_per_page', $pf_per_page );
            }
        }
    }
}

add_filter('yith_wc_social_login_icon', 'airi_override_yith_wc_social_login_icon', 10, 3);
if(!function_exists('airi_override_yith_wc_social_login_icon')){
    function airi_override_yith_wc_social_login_icon($social, $key, $args){
        if(!is_admin()){
            $social = sprintf(
                '<a class="%s" href="%s">%s</a>',
                'social_login ywsl-' . esc_attr($key) . ' social_login-' . esc_attr($key),
                $args['url'],
                isset( $args['value']['label'] ) ? $args['value']['label'] : $args['value']
            );
        }
        return $social;
    }
}

add_action('wp', 'airi_hook_maintenance');
if(!function_exists('airi_hook_maintenance')){
    function airi_hook_maintenance(){
        wp_reset_postdata();
        $enable_private = Airi()->settings()->get('enable_maintenance', 'no');
        if($enable_private == 'yes'){
            if(!is_user_logged_in()){
                $page_id = Airi()->settings()->get('maintenance_page');
                if(empty($page_id)){
                    wp_redirect(wp_login_url());
                    exit;
                }
                else{
                    $page_id = absint($page_id);
                    if(!is_page($page_id)){
                        wp_redirect(get_permalink($page_id));
                        exit;
                    }
                }
            }
        }
    }
}

add_filter('widget_archives_args', 'airi_modify_widget_archives_args');
if(!function_exists('airi_modify_widget_archives_args')){
    function airi_modify_widget_archives_args( $args ){
        if(isset($args['show_post_count'])){
            unset($args['show_post_count']);
        }
        return $args;
    }
}
if(isset($_GET['la_doing_ajax'])){
    remove_action('template_redirect', 'redirect_canonical');
}
add_filter('woocommerce_redirect_single_search_result', '__return_false');


add_filter('airi/filter/breadcrumbs/items', 'airi_theme_setup_breadcrumbs_for_dokan', 10, 2);
if(!function_exists('airi_theme_setup_breadcrumbs_for_dokan')){
    function airi_theme_setup_breadcrumbs_for_dokan( $items, $args ){
        if (  function_exists('dokan_is_store_page') && dokan_is_store_page() ) {
            $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
            if( count($items) > 1 ){
                unset($items[(count($items) - 1)]);
            }
            $items[] = sprintf(
                '<div class="la-breadcrumb-item"><span class="%2$s">%1$s</span></div>',
                esc_attr($store_user->get_shop_name()),
                'la-breadcrumb-item-link'
            );
        }

        return $items;
    }
}


add_filter('airi/filter/show_page_title', 'airi_filter_show_page_title', 10, 1 );
add_filter('airi/filter/show_breadcrumbs', 'airi_filter_show_breadcrumbs', 10, 1 );

if(!function_exists('airi_filter_show_page_title')){
    function airi_filter_show_page_title( $show ){
        $context = Airi()->get_current_context();
        if( in_array( 'is_product', $context ) && Airi()->settings()->get('product_single_hide_page_title', 'no') == 'yes' ){
            return false;
        }
        return $show;
    }
}

if(!function_exists('airi_filter_show_breadcrumbs')){
    function airi_filter_show_breadcrumbs( $show ){
        $context = Airi()->get_current_context();
        if( in_array( 'is_product', $context ) && Airi()->settings()->get('product_single_hide_breadcrumb', 'no') == 'yes'){
            return false;
        }
        return $show;
    }
}


add_filter('LaStudio/swatches/args/show_option_none', 'airi_allow_translate_woo_text_in_swatches', 10, 1);
if(!function_exists('airi_allow_translate_woo_text_in_swatches')){
    function airi_allow_translate_woo_text_in_swatches( $text ){
        return esc_html_x( 'Choose an option', 'front-view', 'airi' );
    }
}

add_filter('LaStudio/swatches/get_attribute_thumbnail_src', 'airi_allow_resize_image_url_in_swatches', 10, 4);

if(!function_exists('airi_allow_resize_image_url_in_swatches')){
    function airi_allow_resize_image_url_in_swatches( $image_url, $image_id, $size_name, $instance ) {
        if($size_name == 'la_swatches_image_size'){
            $width = $instance->get_width();
            $height = $instance->get_height();
            $image_url = Airi()->images()->get_attachment_image_url($image_id, array( $width, $height ));
            return $image_url;
        }
        return $image_url;
    }
}

add_filter('LaStudio/swatches/get_product_variation_image_url_by_attribute', 'airi_allow_resize_variation_image_url_by_attribute_in_swatches', 10, 2);
if(!function_exists('airi_allow_resize_variation_image_url_by_attribute_in_swatches')){
    function airi_allow_resize_variation_image_url_by_attribute_in_swatches( $image_url, $image_id ) {
        global $precise_loop;
        if(isset($precise_loop['image_size'])){
            return Airi()->images()->get_attachment_image_url($image_id, $precise_loop['image_size'] );
        }
        return $image_url;
    }
}

if(!function_exists('airi_get_relative_url')){
    function airi_get_relative_url( $url ) {
        return airi_is_external_resource( $url ) ? $url : str_replace( array( 'http://', 'https://' ), '//', $url );
    }
}
if(!function_exists('airi_is_external_resource')){
    function airi_is_external_resource( $url ) {
        $wp_base = str_replace( array( 'http://', 'https://' ), '//', get_home_url( null, '/', 'http' ) );
        return strstr( $url, '://' ) && strstr( $wp_base, $url );
    }
}

if (!function_exists('airi_wpml_object_id')) {
    function airi_wpml_object_id( $element_id, $element_type = 'post', $return_original_if_missing = false, $ulanguage_code = null ) {
        if ( function_exists( 'wpml_object_id_filter' ) ) {
            return wpml_object_id_filter( $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        } elseif ( function_exists( 'icl_object_id' ) ) {
            return icl_object_id( $element_id, $element_type, $return_original_if_missing, $ulanguage_code );
        } else {
            return $element_id;
        }
    }
}

/**
 * Override page title bar from global settings
 * What we need to do now is
 * 1. checking in single content types
 *  1.1) post
 *  1.2) product
 *  1.3) portfolio
 * 2. checking in archives
 *  2.1) shop
 *  2.2) portfolio
 *
 * TIPS: List functions will be use to check
 * `is_product`, `is_single_la_portfolio`, `is_shop`, `is_woocommerce`, `is_product_taxonomy`, `is_archive_la_portfolio`, `is_tax_la_portfolio`
 */

if(!function_exists('airi_override_page_title_bar_from_context')){
    function airi_override_page_title_bar_from_context( $value, $key, $context ){

        $array_key_allow = array(
            'page_title_bar_style',
            'page_title_bar_layout',
            'page_title_font_size',
            'page_title_bar_background',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color',
            'page_title_bar_spacing',
            'page_title_bar_spacing_desktop_small',
            'page_title_bar_spacing_tablet',
            'page_title_bar_spacing_mobile'
        );

        $array_key_alternative = array(
            'page_title_font_size',
            'page_title_bar_background',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color',
            'page_title_bar_spacing',
            'page_title_bar_spacing_desktop_small',
            'page_title_bar_spacing_tablet',
            'page_title_bar_spacing_mobile'
        );

        /**
         * Firstly, we need to check the `$key` input
         */
        if( !in_array($key, $array_key_allow) ){
            return $value;
        }

        /**
         * Secondary, we need to check the `$context` input
         */
        if( !in_array('is_singular', $context) && !in_array('is_woocommerce', $context) && !in_array('is_archive_la_portfolio', $context) && !in_array('is_tax_la_portfolio', $context)){
            return $value;
        }

        if( !is_singular(array('product', 'post', 'la_portfolio')) && !in_array('is_product_taxonomy', $context) && !in_array('is_shop', $context) ) {
            return $value;
        }


        $func_name = 'get_post_meta';
        $queried_object_id = get_queried_object_id();

        if( in_array('is_product_taxonomy', $context) || in_array('is_tax_la_portfolio', $context) ){
            $func_name = 'get_term_meta';
        }

        if(in_array('is_shop', $context)){
            $queried_object_id = Airi_WooCommerce::$shop_page_id;
        }

        if ( 'page_title_bar_layout' == $key ) {
            $page_title_bar_layout = Airi()->settings()->$func_name($queried_object_id, $key);
            if($page_title_bar_layout && $page_title_bar_layout != 'inherit'){
                return $page_title_bar_layout;
            }
        }

        if( 'yes' == Airi()->settings()->$func_name($queried_object_id, 'page_title_bar_style') && in_array($key, $array_key_alternative) ){
            return $value;
        }

        $key_override = $new_key = false;

        if( in_array('is_product', $context) ){
            $key_override = 'single_product_override_page_title_bar';
            $new_key = 'single_product_' . $key;
        }
        elseif( in_array('is_single_la_portfolio', $context) ) {
            $key_override = 'single_portfolio_override_page_title_bar';
            $new_key = 'single_portfolio_' . $key;
        }
        elseif( is_singular('post') ) {
            $key_override = 'single_post_override_page_title_bar';
            $new_key = 'single_post_' . $key;
        }
        elseif( in_array('is_single_la_portfolio', $context) ) {
            $key_override = 'single_portfolio_override_page_title_bar';
            $new_key = 'single_portfolio_' . $key;
        }
        elseif ( in_array('is_shop', $context) || in_array('is_product_taxonomy', $context) ) {
            $key_override = 'woo_override_page_title_bar';
            $new_key = 'woo_' . $key;
        }
        elseif ( in_array('is_archive_la_portfolio', $context) || in_array('is_tax_la_portfolio', $context) ) {
            $key_override = 'archive_portfolio_override_page_title_bar';
            $new_key = 'archive_portfolio_' . $key;
        }

        if(false != $key_override){
            if( 'on' == Airi()->settings()->get($key_override, 'off') ){
                return Airi()->settings()->get($new_key, $value);
            }
        }

        return $value;
    }

    add_filter('airi/setting/get_setting_by_context', 'airi_override_page_title_bar_from_context', 10, 3);
}

/**
 * This function allow get property of `woocommerce_loop` inside the loop
 * @since 1.0.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */

if(!function_exists('airi_get_wc_loop_prop')){
    function airi_get_wc_loop_prop( $prop, $default = ''){
        return isset( $GLOBALS['woocommerce_loop'], $GLOBALS['woocommerce_loop'][ $prop ] ) ? $GLOBALS['woocommerce_loop'][ $prop ] : $default;
    }
}

/**
 * This function allow set property of `woocommerce_loop`
 * @since 1.0.0
 * @param string $prop Prop to set.
 * @param string $value Value to set.
 */

if(!function_exists('airi_set_wc_loop_prop')){
    function airi_set_wc_loop_prop( $prop, $value = ''){
        if(isset($GLOBALS['woocommerce_loop'])){
            $GLOBALS['woocommerce_loop'][ $prop ] = $value;
        }
    }
}

/**
 * This function allow get property of `airi_loop` inside the loop
 * @since 1.0.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */

if(!function_exists('airi_get_theme_loop_prop')){
    function airi_get_theme_loop_prop( $prop, $default = ''){
        return isset( $GLOBALS['airi_loop'], $GLOBALS['airi_loop'][ $prop ] ) ? $GLOBALS['airi_loop'][ $prop ] : $default;
    }
}

if(!function_exists('airi_set_theme_loop_prop')){
    function airi_set_theme_loop_prop( $prop, $value = '', $force = false){
        if($force && !isset($GLOBALS['airi_loop'])){
            $GLOBALS['airi_loop'] = array();
        }
        if(isset($GLOBALS['airi_loop'])){
            $GLOBALS['airi_loop'][ $prop ] = $value;
        }
    }
}

if(!function_exists('airi_convert_legacy_responsive_column')){
    function airi_convert_legacy_responsive_column( $columns = array() ) {
        $legacy = array(
            'xlg'	=> '',
            'lg' 	=> '',
            'md' 	=> '',
            'sm' 	=> '',
            'xs' 	=> '',
            'mb' 	=> 1
        );
        $new_key = array(
            'mb'    =>  'xs',
            'xs'    =>  'sm',
            'sm'    =>  'md',
            'md'    =>  'lg',
            'lg'    =>  'xl',
            'xlg'   =>  'xxl'
        );
        if(empty($columns)){
            $columns = $legacy;
        }
        $new_columns = array();
        foreach($columns as $k => $v){
            if(isset($new_key[$k])){
                $new_columns[$new_key[$k]] = $v;
            }
        }
        if(empty($new_columns['xs'])){
            $new_columns['xs'] = 1;
        }
        return $new_columns;
    }
}

if(!function_exists('airi_render_grid_css_class_from_columns')){
    function airi_render_grid_css_class_from_columns( $columns, $merge = true ) {
        if($merge){
            $columns = airi_convert_legacy_responsive_column( $columns );
        }
        $classes = array();
        foreach($columns as $k => $v){
            if(empty($v)){
                continue;
            }
            if($k == 'xs'){
                $classes[] = 'block-grid-' . $v;
            }
            else{
                $classes[] = $k . '-block-grid-' . $v;
            }
        }
        return join(' ', $classes);
    }
}

if(!function_exists('airi_add_ajax_cart_btn_into_single_product')){
    function airi_add_ajax_cart_btn_into_single_product(){
        global $product;
        if($product->is_type('simple')){
            echo '<input type="hidden" name="add-to-cart" value="'.$product->get_id().'"/>';
        }
    }
    add_action('woocommerce_after_add_to_cart_button', 'airi_add_ajax_cart_btn_into_single_product');
}

if(!function_exists('airi_get_the_excerpt')){
    function airi_get_the_excerpt($length = null){
        ob_start();

        $length = absint($length);

        if(!empty($length)){
            airi_deactive_filter('get_the_excerpt', 'wp_trim_excerpt');
            add_filter('excerpt_length', function() use ($length) {
                return $length;
            }, 1012);
        }

        the_excerpt();

        if(!empty($length)) {
            remove_all_filters('excerpt_length', 1012);
        }
        $output = ob_get_clean();

        $output = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $output);

        $output = strip_tags( $output );

        if(!empty($output)){
            $output = sprintf('<p>%s</p>', $output);
        }

        return $output;
    }
}


if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
    function woocommerce_template_loop_product_title() {
        the_title( sprintf( '<h3 class="product_item--title"><a href="%s">', esc_url( get_the_permalink() ) ), '</a></h3>' );
    }
}

if( !function_exists('airi_allow_shortcode_text_in_component_text') ) {
    function airi_allow_shortcode_text_in_component_text( $text ){
        return do_shortcode($text);
    }
    add_filter('airi/filter/component/text', 'airi_allow_shortcode_text_in_component_text');
}

if(!function_exists('airi_override_woothumbnail_size_name')){
    function airi_override_woothumbnail_size_name( ) {
        return 'shop_thumbnail';
    }
    add_filter('woocommerce_gallery_thumbnail_size', 'airi_override_woothumbnail_size_name', 0);
}

if(!function_exists('airi_override_woothumbnail_size')){
    function airi_override_woothumbnail_size( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 180 ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_gallery_thumbnail', 'airi_override_woothumbnail_size');
}

if(!function_exists('airi_override_woothumbnail_single')){
    function airi_override_woothumbnail_single( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_single', 'airi_override_woothumbnail_single', 0);
}


if(!function_exists('airi_override_filter_woocommerce_format_content')){
    function airi_override_filter_woocommerce_format_content( $format, $raw_string ){
        $format = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $raw_string);
        return apply_filters( 'woocommerce_short_description', $format );
    }
}

add_action('woocommerce_checkout_terms_and_conditions', 'airi_override_wc_format_content_in_terms', 1);
add_action('woocommerce_checkout_terms_and_conditions', 'airi_remove_override_wc_format_content_in_terms', 999);
if(!function_exists('airi_override_wc_format_content_in_terms')){
    function airi_override_wc_format_content_in_terms(){
        add_filter('woocommerce_format_content', 'airi_override_filter_woocommerce_format_content', 99, 2);
    }
}
if(!function_exists('airi_remove_override_wc_format_content_in_terms')){
    function airi_remove_override_wc_format_content_in_terms(){
        airi_deactive_filter('woocommerce_format_content', 'airi_override_filter_woocommerce_format_content', 99);
    }
}


if(!function_exists('airi_wc_product_loop')){
    function airi_wc_product_loop(){
        if(!function_exists('WC')){
            return false;
        }
        return have_posts() || 'products' !== woocommerce_get_loop_display_mode();
    }
}