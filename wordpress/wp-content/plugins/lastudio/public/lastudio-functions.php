<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if(!function_exists('la_get_base_shop_url')){
    function la_get_base_shop_url( $with_post_type_archive = true ){
        $link = '';
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        }
        elseif( is_tax( get_object_taxonomies( 'product' ) ) ) {
            if( is_product_tag() && $with_post_type_archive ){
                $link = get_post_type_archive_link( 'product' );
            }
            else{
                if( is_product_category() ) {
                    $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
                }
                elseif ( is_product_tag() ) {
                    $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
                }
                else{
                    $queried_object = get_queried_object();
                    $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
                }
            }
        }
        else{
            if($with_post_type_archive){
                $link = get_post_type_archive_link( 'product' );
            }
            else{
                if(function_exists('dokan')){
                    $current_url = add_query_arg(null, null);
                    $current_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $current_url);
                    $link = preg_replace('/\/page\/\d+/', '', $current_url);
                    $tmp = explode('?', $link);
                    if(isset($tmp[0])){
                        $link = $tmp[0];
                    }
                }
            }
        }
        return $link;
    }
}

if (!function_exists('la_log')) {
    function la_log($log) {
        if (true === WP_DEBUG) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

function la_locate_template($template_names, $load = false, $require_once = true){
    static $template_cache;

    $located = '';
    foreach ((array)$template_names as $template_name) {
        if (!$template_name){
            continue;
        }
        if (!empty($template_cache[$template_name])) {
            $located = $template_cache[$template_name];
            break;
        }
        if (isset($template_cache[$template_name]) && !$template_cache[$template_name]){
            continue;
        }

        if (file_exists(STYLESHEETPATH . '/' . $template_name)) {
            $located = STYLESHEETPATH . '/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        elseif (file_exists(TEMPLATEPATH . '/' . $template_name)) {
            $located = TEMPLATEPATH . '/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        elseif (file_exists(ABSPATH . WPINC . '/theme-compat/' . $template_name)) {
            $located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
            $template_cache[$template_name] = $located;
            break;
        }
        $template_cache[$template_name] = false;
    }

    if ($load && '' != $located){
        load_template($located, $require_once);
    }

    return $located;
}

function la_string_to_bool($string){
    return is_bool($string) ? $string : ('yes' === $string || 1 === $string || 'true' === $string || '1' === $string);
}

/**
 * Define a constant if it is not already defined.
 *
 * @since 1.0.0
 * @param string $name Constant name.
 * @param string $value Value.
 */

function la_maybe_define_constant($name, $value){
    if (!defined($name)) {
        define($name, $value);
    }
}

/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_fw_add_element($field = array(), $value = '', $unique = ''){
    $output = '';
    $depend = '';
    $sub = (isset($field['sub'])) ? 'sub-' : '';
    $unique = (isset($unique)) ? $unique : '';
    $class = 'LaStudio_Theme_Options_Field_' . strtolower($field['type']);
    $wrap_class = (isset($field['wrap_class'])) ? ' ' . $field['wrap_class'] : '';
    $el_class = (isset($field['title'])) ? sanitize_title($field['title']) : 'no-title';
    $hidden = '';
    $is_pseudo = (isset($field['pseudo'])) ? ' la-pseudo-field' : '';

    if (isset($field['dependency']) && !empty($field['dependency'])) {
        $hidden = ' hidden';
        $depend .= ' data-' . $sub . 'controller="' . $field['dependency'][0] . '"';
        $depend .= ' data-' . $sub . 'condition="' . $field['dependency'][1] . '"';
        $depend .= ' data-' . $sub . 'value="' . $field['dependency'][2] . '"';
    }

    $output .= '<div class="la-element la-element-' . $el_class . ' la-field-' . $field['type'] . $is_pseudo . $wrap_class . $hidden . '"' . $depend . '>';

    if (isset($field['title'])) {
        $field_desc = (isset($field['desc'])) ? '<p class="la-text-desc">' . $field['desc'] . '</p>' : '';
        $output .= '<div class="la-title"><h4>' . $field['title'] . '</h4>' . $field_desc . '</div>';
    }

    $output .= (isset($field['title'])) ? '<div class="la-fieldset">' : '';

    $value = (!isset($value) && isset($field['default'])) ? $field['default'] : $value;
    $value = (isset($field['value'])) ? $field['value'] : $value;

    if (class_exists($class)) {
        ob_start();
        $element = new $class($field, $value, $unique);
        $element->output();
        $output .= ob_get_clean();
    }
    else {
        $output .= '<p>' . __('This field class is not available!', 'lastudio') . '</p>';
    }

    $output .= (isset($field['title'])) ? '</div>' : '';
    $output .= '<div class="clear"></div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_array_search($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $sub_array) {
            $results = array_merge($results, la_array_search($sub_array, $key, $value));
        }
    }
    return $results;
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_google_fonts(){
    $transient_name = 'la_get_google_fonts_' . LaStudio_Cache_Helper::get_transient_version('google_fonts');
    $transient_value = get_transient($transient_name);
    if( false === $transient_value ) {
        $file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/google-fonts.json';
        if (file_exists($file)) {
            $tmp = @file_get_contents($file);
            if (!is_wp_error($tmp)){
                $results = json_decode($tmp, false);
                if( is_object( $results ) ) {
                    $new_items = array();
                    foreach($results->items as $k => $v){
                        $font_obj = new stdClass();
                        $font_obj->family = $v->family;
                        $font_obj->category = $v->category;
                        $font_obj->variants = $v->variants;
                        $font_obj->subsets = $v->subsets;
                        $new_items[] = $font_obj;
                    }
                    $obj_tmp = new stdClass();
                    $obj_tmp->items = $new_items;

                    set_transient( $transient_name, $obj_tmp, DAY_IN_SECONDS * 30 );
                    return $obj_tmp;
                }
            }
        }
    }
    return !empty($transient_value) ? $transient_value : array();
}


/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_var($var, $default = ''){
    if (isset($_POST[$var])) {
        return $_POST[$var];
    }
    if (isset($_GET[$var])) {
        return $_GET[$var];
    }
    return $default;
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function la_get_vars($var, $depth, $default = ''){
    if (isset($_POST[$var][$depth])) {
        return $_POST[$var][$depth];
    }
    if (isset($_GET[$var][$depth])) {
        return $_GET[$var][$depth];
    }
    return $default;
}

function la_convert_option_to_customize($options){
    $panels = array();
    foreach ($options as $section) {
        if (empty($section['sections']) && empty($section['fields'])) {
            continue;
        }

        $panel = array(
            'name' => (isset($section['name']) ? $section['name'] : uniqid()),
            'title' => $section['title'],
            'description' => (isset($section['description']) ? $section['description'] : '')
        );

        if (!empty($section['sections'])) {
            $sub_panel = array();
            foreach ($section['sections'] as $sub_section) {
                if (!empty($sub_section['fields'])) {
                    $sub_panel2 = array(
                        'name' => (isset($sub_section['name']) ? $sub_section['name'] : uniqid()),
                        'title' => $sub_section['title'],
                        'description' => (isset($sub_section['description']) ? $sub_section['description'] : '')
                    );
                    $fields = array();
                    foreach ($sub_section['fields'] as $field) {
                        $fields[] = la_convert_field_option_to_customize($field);
                    }
                    $sub_panel2['settings'] = $fields;
                    $sub_panel[] = $sub_panel2;
                }
            }
            $panel['sections'] = $sub_panel;
            $panels[] = $panel;
        } elseif (!empty($section['fields'])) {
            $fields = array();

            foreach ($section['fields'] as $field) {
                $fields[] = la_convert_field_option_to_customize($field);
            }
            $panel['settings'] = $fields;
            $panels[] = $panel;
        }
    }
    return $panels;
}

function la_convert_field_option_to_customize($field){
    $backup_field = $field;
    if (isset($backup_field['id'])) {
        $field_id = $backup_field['id'];
        unset($backup_field['id']);
    } else {
        $field_id = uniqid();
    }
    if (isset($backup_field['type']) && 'wp_editor' === $backup_field['type']) {
        $backup_field['type'] = 'textarea';
    }
    $tmp = array(
        'name' => $field_id,
        'control' => array(
            'type' => 'la_field',
            'options' => $backup_field
        )
    );
    if (isset($backup_field['default'])) {
        $tmp['default'] = $backup_field['default'];
        unset($backup_field['default']);
    }
    return $tmp;
}


function la_fw_get_child_shortcode_nested($content, $atts = null){
    $res = array();
    $reg = get_shortcode_regex();
    preg_match_all('~' . $reg . '~', $content, $matches);
    if (isset($matches[2]) && !empty($matches[2])) {
        foreach ($matches[2] as $key => $name) {
            $res[$name] = $name;
        }
    }
    return $res;
}

function la_fw_override_shortcodes($content = null){
    if (!empty($content)) {
        global $shortcode_tags, $backup_shortcode_tags;
        $backup_shortcode_tags = $shortcode_tags;
        $child_exists = la_fw_get_child_shortcode_nested($content);
        if (!empty($child_exists)) {
            foreach ($child_exists as $tag) {
                $shortcode_tags[$tag] = 'la_fw_wrap_shortcode_in_div';
            }
        }
    }
}

function la_fw_wrap_shortcode_in_div($attr, $content = null, $tag){
    global $backup_shortcode_tags;
    return '<div class="la-item-wrap">' . call_user_func($backup_shortcode_tags[$tag], $attr, $content, $tag) . '</div>';
}

function la_fw_restore_shortcodes(){
    global $shortcode_tags, $backup_shortcode_tags;
    // Restore the original callbacks
    if (isset($backup_shortcode_tags)) {
        $shortcode_tags = $backup_shortcode_tags;
    }
}

function la_pagespeed_detected(){
    return (
        isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/GTmetrix|Page Speed/i', $_SERVER['HTTP_USER_AGENT'])
    );
}

function la_shortcode_custom_css_class($param_value, $prefix = ''){
    $css_class = preg_match('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value) ? $prefix . preg_replace('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value) : '';
    return $css_class;
}

function la_build_link_from_atts($value){
    $result = array('url' => '', 'title' => '', 'target' => '', 'rel' => '');
    $params_pairs = explode('|', $value);
    if (!empty($params_pairs)) {
        foreach ($params_pairs as $pair) {
            $param = preg_split('/\:/', $pair);
            if (!empty($param[0]) && isset($param[1])) {
                $result[$param[0]] = rawurldecode($param[1]);
            }
        }
    }
    return $result;
}


function la_get_blank_image_src(){
    return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
}

function la_get_shortcode_loop_transient_name( $shortcode_name, $shortcode_atts = array(), $cache_name = '' ){
    $transient_name = 'la_shortcode_loop' . substr( md5( wp_json_encode( $shortcode_atts ) . $shortcode_name ), 28 );
    if (isset($shortcode_atts['orderby']) &&  'rand' === $shortcode_atts['orderby'] ) {
        // When using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load.
        $rand_index      = rand( 0, max( 1, absint( apply_filters( 'lastudio_shortcode_query_max_rand_cache_count', 5 ) ) ) );
        $transient_name .= $rand_index;
    }
    $cache_name = sanitize_key($cache_name);
    if(empty($cache_name)){
        $cache_name = 'shortcode_query';
    }
    $transient_name .= LaStudio_Cache_Helper::get_transient_version( $cache_name );
    return $transient_name;
}

function la_get_shortcode_loop_query_results( $shortcode_name, $shortcode_atts = array(), $query_args = array(), $cache_name = ''){
    $transient_name = la_get_shortcode_loop_transient_name( $shortcode_name, $shortcode_atts, $cache_name);
    $tmp_cache_atts = isset($shortcode_atts['cache']) ? $shortcode_atts['cache'] : '';
    $cache          = la_string_to_bool( $tmp_cache_atts ) === true;
    $results        = $cache ? get_transient( $transient_name ) : false;
    if ( false === $results ) {
        $query = new WP_Query( $query_args );
        $paginated = ! $query->get( 'no_found_rows' );

        $results = (object) array(
            'ids'          => wp_list_pluck( $query->posts, 'ID' ),
            'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
            'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
            'per_page'     => (int) $query->get( 'posts_per_page' ),
            'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
        );
        if ( $cache ) {
            set_transient( $transient_name, $results, DAY_IN_SECONDS * 30 );
        }
    }
    return $results;
}

/**
 * @param $atts_string
 *
 * @since 1.0
 * @return array|mixed
 */
function la_param_group_parse_atts( $atts_string ) {
    $array = json_decode( urldecode( $atts_string ), true );

    return $array;
}

/**
 * Convert string to a valid css class name.
 *
 * @since 1.0
 *
 * @param string $class
 *
 * @return string
 */
function la_build_safe_css_class( $class ) {
    return preg_replace( '/\W+/', '', strtolower( str_replace( ' ', '_', strip_tags( $class ) ) ) );
}

function la_parse_multi_attribute( $value, $default = array() ) {
    $result = $default;
    $params_pairs = explode( '|', $value );
    if ( ! empty( $params_pairs ) ) {
        foreach ( $params_pairs as $pair ) {
            $param = preg_split( '/\:/', $pair );
            if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
                $result[ $param[0] ] = rawurldecode( $param[1] );
            }
        }
    }

    return $result;
}


function la_get_product_grid_style()
{
    return array(
        __('Design 01', 'lastudio') => '1',
        __('Design 02', 'lastudio') => '2',
        __('Design 03', 'lastudio') => '3',
    );
}

function la_get_product_list_style()
{
    return array(
        __('Default', 'lastudio') => 'default',
        __('Mini', 'lastudio') => 'mini'
    );
}

function la_export_options()
{
    $unique = isset($_REQUEST['unique']) ? $_REQUEST['unique'] : 'la_options';
    header('Content-Type: plain/text');
    header('Content-disposition: attachment; filename=backup-' . esc_attr($unique) . '-' . gmdate('d-m-Y') . '.txt');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo wp_json_encode(get_option($unique));
    die();
}

add_action('wp_ajax_la-export-options', 'la_export_options');

function la_add_script_to_compare()
{
    echo '<script type="text/javascript">var redirect_to_cart=true;</script>';
}

add_action('yith_woocompare_after_main_table', 'la_add_script_to_compare');

function la_add_script_to_quickview_product()
{
    global $product;
    if (function_exists('is_product') && isset($_GET['product_quickview']) && is_product()) {
        if ($product->get_type() == 'variable') {
            wp_print_scripts('underscore');
            wc_get_template('single-product/add-to-cart/variation.php');
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var _wpUtilSettings = <?php echo wp_json_encode(array(
                    'ajax' => array('url' => admin_url('admin-ajax.php', 'relative'))
                ));?>;
                var wc_add_to_cart_variation_params = <?php echo wp_json_encode(array(
                    'i18n_no_matching_variations_text' => esc_attr__('Sorry, no products matched your selection. Please choose a different combination.', 'lastudio'),
                    'i18n_make_a_selection_text' => esc_attr__('Select product options before adding this product to your cart.', 'lastudio'),
                    'i18n_unavailable_text' => esc_attr__('Sorry, this product is unavailable. Please choose a different combination.', 'lastudio')
                )); ?>;
                /* ]]> */
            </script>
            <script type="text/javascript" src="<?php echo esc_url(includes_url('js/wp-util.min.js')) ?>"></script>
            <script type="text/javascript"
                    src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/add-to-cart-variation.min.js' ?>"></script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var wc_single_product_params = <?php echo wp_json_encode(array(
                    'i18n_required_rating_text' => esc_attr__('Please select a rating', 'lastudio'),
                    'review_rating_required' => get_option('woocommerce_review_rating_required'),
                    'flexslider' => apply_filters('woocommerce_single_product_carousel_options', array(
                        'rtl' => is_rtl(),
                        'animation' => 'slide',
                        'smoothHeight' => false,
                        'directionNav' => false,
                        'controlNav' => 'thumbnails',
                        'slideshow' => false,
                        'animationSpeed' => 500,
                        'animationLoop' => false, // Breaks photoswipe pagination if true.
                    )),
                    'zoom_enabled' => 0,
                    'photoswipe_enabled' => 0,
                    'flexslider_enabled' => 1,
                ));?>;
                /* ]]> */
            </script>
            <?php
        }
    }
}

add_action('woocommerce_after_single_product', 'la_add_script_to_quickview_product');

function la_theme_fix_wc_track_product_view()
{
    if (!is_singular('product')) {
        return;
    }
    if (!function_exists('wc_setcookie')) {
        return;
    }
    global $post;
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    }
    else {
        $viewed_products = (array)explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }
    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }
    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'la_theme_fix_wc_track_product_view', 30);