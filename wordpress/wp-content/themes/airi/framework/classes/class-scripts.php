<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Handle enqueueing scrips.
 */
class Airi_Scripts
{

    /**
     * The class construction
     */
    public function __construct()
    {

        add_filter('lastudio/theme/defer_scripts', array( $this, 'override_defer_scripts' ) );
        if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 20);
        }

        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_enqueue_styles', array($this, 'remove_woo_scripts'));
        }

        add_action('wp_head', array( $this, 'add_meta_into_head'), 100 );
        add_action('airi/action/head', array( $this, 'get_custom_css_from_setting'));
        add_action('airi/action/head', array( $this, 'add_custom_header_js' ), 100 );
        add_action('wp_footer', array( $this, 'add_custom_footer_js' ), 100 );
    }

    /**
     * Takes care of enqueueing all our scripts.
     */
    public function enqueue_scripts()
    {

        if(function_exists('vc_is_inline') && function_exists('vc_is_frontend_ajax') && vc_is_inline() && vc_is_frontend_ajax()){
            return;
        }

		$theme_version = null;
        $script_min_path = apply_filters('airi/filter/js_load_min_file', 'min/');

        $styleNeedRemove = array(
            'yith-woocompare-widget',
            'jquery-selectBox',
            'yith-wcwl-font-awesome',
            'woocomposer-front-slick',
            'jquery-colorbox',
            'dokan-fontawesome'
        );
        $scriptNeedRemove = array(
            'woocomposer-slick'
        );

        foreach ($styleNeedRemove as $style) {
            if (wp_style_is($style, 'registered')) {
                wp_deregister_style($style);
            }
        }
        foreach ($scriptNeedRemove as $script) {
            if (wp_script_is($script, 'registered')) {
                wp_dequeue_script($script);
            }
        }

        wp_enqueue_style('font-awesome', Airi::$template_dir_url . '/assets/css/font-awesome.min.css', array(), $theme_version);
        wp_enqueue_style('animate-css', Airi::$template_dir_url . '/assets/css/animate.min.css', array(), $theme_version);
        wp_enqueue_style('airi-theme', get_template_directory_uri() . '/style.css', array('font-awesome'), $theme_version);


        /*
         * Scripts
         */

        $font_source = Airi()->settings()->get('font_source', 1);
        switch ($font_source) {
            case '1':
                wp_enqueue_style('airi-google_fonts', $this->get_google_font_url(), array(), null);
                break;
            case '2':
                wp_enqueue_style('airi-font_google_code', $this->get_google_font_code_url(), array(), null);
                break;
            case '3':
                wp_enqueue_script('airi-font_typekit', $this->get_google_font_typekit_url(), array(), null);
                wp_add_inline_script( 'airi-font_typekit', 'try{ Typekit.load({ async: true }) }catch(e){}' );
                break;
        }

        wp_enqueue_script( 'respond', Airi::$template_dir_url . '/assets/js/enqueue/min/respond.js');
        wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

        if (wp_script_is('waypoints', 'registered')) {
            $inline_waypoints = "
            try{
                function vc_waypoints(){
                    if (typeof jQuery.fn.waypoint !== 'undefined') {
                        jQuery('.wpb_animate_when_almost_visible:not(.wpb_start_animation)').waypoint(function(){
                            jQuery(this).addClass('wpb_start_animation animated');
                        },{ offset: '95%' });
                    }
                }
            }catch(e){ console.log(e) }
            ";
            wp_add_inline_script( 'waypoints', $inline_waypoints );
        }

        wp_register_script( 'airi-modernizr-custom', Airi::$template_dir_url . '/assets/js/enqueue/min/modernizr-custom.js', array('jquery'), $theme_version, true);

        $fullpage_config = array();
        $js_require = array('jquery','airi-modernizr-custom');

        if (in_array('is_page', Airi()->get_current_context())) {
            $fp_metadata = Airi()->settings()->get_post_meta(get_the_ID());

            if (Airi()->layout()->get_site_layout() == 'col-1c' && (!empty($fp_metadata['enable_fp']) && $fp_metadata['enable_fp'] == 'yes')) {

                wp_enqueue_style('airi-fullpage', Airi::$template_dir_url . '/assets/css/fullpage.min.css', array('airi-theme'), $theme_version);

                $section_nav_file = !empty($fp_metadata['fp_sectionnavigationstyle']) ? $fp_metadata['fp_sectionnavigationstyle'] : 'default';
                $slide_nav_file = !empty($fp_metadata['fp_slidenavigationstyle']) ? $fp_metadata['fp_slidenavigationstyle'] : 'section_nav';

                if(!empty($fp_metadata['fp_navigation']) && $fp_metadata['fp_navigation'] != 'off'){
                    if($section_nav_file != 'number'){
                        wp_enqueue_style('airi-fullpage-nav', Airi::$template_dir_url . '/assets/css/fullpage/nav/section/'.$section_nav_file.'.css', array('airi-theme'), $theme_version);
                    }
                }
                if(!empty($fp_metadata['fp_slidenavigation']) && $fp_metadata['fp_slidenavigation'] != 'off'){
                    if ($slide_nav_file == 'section_nav') {
                        $slide_nav_file = $section_nav_file;
                    }
                    if ($slide_nav_file == 'crazy-text-effect') {
                        $slide_nav_file = 'default';
                    }
                    if($slide_nav_file != 'number'){
                        wp_enqueue_style('airi-fullpage-nav', Airi::$template_dir_url . '/assets/css/fullpage/nav/slide/'.$slide_nav_file.'.css', array('airi-theme'), $theme_version);
                    }
                }

                $fp_easing = !empty($fp_metadata['fp_easing']) ? $fp_metadata['fp_easing'] : 'css3_ease';
                $fp_scrolloverflow = !empty($fp_metadata['fp_scrolloverflow']) ? $fp_metadata['fp_scrolloverflow'] : 'no';
                $fullpage_js_require = array('jquery');
                if (substr($fp_easing, 0, 3) == 'js_') {
                    wp_register_script('airi-easings', Airi::$template_dir_url . '/assets/js/enqueue/min/jquery.easings.js', array('jquery'), $theme_version, true);
                    $fullpage_js_require[] = 'airi-easings';
                }
                if ($fp_scrolloverflow == 'yes') {
                    wp_register_script('airi-scrolloverflow', Airi::$template_dir_url . '/assets/js/enqueue/min/jquery.scrolloverflow.js', array('jquery'), $theme_version, true);
                    $fullpage_js_require[] = 'airi-scrolloverflow';
                }
                wp_register_script('airi-fullpage-parallax', Airi::$template_dir_url . '/assets/js/enqueue/min/jquery.fullpage.parallax.js', array('jquery'), $theme_version, true);
                $fullpage_js_require[] = 'airi-fullpage-parallax';

                wp_register_script('airi-fullpage', Airi::$template_dir_url . '/assets/js/enqueue/min/jquery.fullpage.extensions.js', $fullpage_js_require, $theme_version, true);
                $js_require[] = 'airi-fullpage';

                $fullpage_config = $this->get_fullpage_config();
            }
        }

        if(apply_filters('airi/filter/force_enqueue_js_external', true)){
            wp_register_script('airi-plugins', Airi::$template_dir_url . '/assets/js/plugins/min/plugins-full.js', array('jquery'), $theme_version, true);
            $js_require[] = 'airi-plugins';
        }

        wp_enqueue_script('airi-theme', Airi::$template_dir_url . '/assets/js/'.$script_min_path.'app.js', $js_require, $theme_version, true);

        wp_localize_script('airi-theme', 'la_theme_config', apply_filters('airi/filter/global_message_js', array(
            'security' => array(
                'favorite_posts' => wp_create_nonce('favorite_posts'),
                'wishlist_nonce' => wp_create_nonce('wishlist_nonce'),
                'compare_nonce' => wp_create_nonce('compare_nonce'),
                'instagram_token' => esc_attr(Airi()->settings()->get('instagram_token'))
            ),
            'fullpage' => $fullpage_config,
            'product_single_design' => esc_attr(Airi()->settings()->get('woocommerce_product_page_design', 1)),
            'product_gallery_column' => esc_attr(json_encode(Airi()->settings()->get('product_gallery_column', array(
                'xlg'	=> 3,
                'lg' 	=> 3,
                'md' 	=> 3,
                'sm' 	=> 5,
                'xs' 	=> 4,
                'mb' 	=> 3
            )))),
            'single_ajax_add_cart' => esc_attr(Airi()->settings()->get('single_ajax_add_cart', 'off')),
            'i18n' => array(
                'backtext' => esc_attr_x('Back', 'front-view', 'airi'),
                'compare' => array(
                    'view' => esc_attr_x('View List Compare', 'front-view', 'airi'),
                    'success' => esc_attr_x('has been added to comparison list.', 'front-view', 'airi'),
                    'error' => esc_attr_x('An error occurred ,Please try again !', 'front-view', 'airi')
                ),
                'wishlist' => array(
                    'view' => esc_attr_x('View List Wishlist', 'front-view', 'airi'),
                    'success' => esc_attr_x('has been added to your wishlist.', 'front-view', 'airi'),
                    'error' => esc_attr_x('An error occurred, Please try again !', 'front-view', 'airi')
                ),
                'addcart' => array(
                    'view' => esc_attr_x('View Cart', 'front-view', 'airi'),
                    'success' => esc_attr_x('has been added to your cart', 'front-view', 'airi'),
                    'error' => esc_attr_x('An error occurred, Please try again !', 'front-view', 'airi')
                ),
                'global' => array(
                    'error' => esc_attr_x('An error occurred ,Please try again !', 'front-view', 'airi'),
                    'comment_author' => esc_attr_x('Please enter Name !', 'front-view', 'airi'),
                    'comment_email' => esc_attr_x('Please enter Email Address !', 'front-view', 'airi'),
                    'comment_rating' => esc_attr_x('Please select a rating !', 'front-view', 'airi'),
                    'comment_content' => esc_attr_x('Please enter Comment !', 'front-view', 'airi'),
                    'continue_shopping' => esc_attr_x('Continue Shopping', 'front-view', 'airi'),
                    'cookie_disabled' => esc_attr_x('We are sorry, but this feature is available only if cookies are enabled on your browser', 'front-view', 'airi')
                )
            ),
            'popup' => array(
                'max_width' => esc_attr(Airi()->settings()->get('popup_max_width', 790)),
                'max_height' => esc_attr(Airi()->settings()->get('popup_max_height', 430))
            ),
            'js_path'       => esc_attr(Airi::$template_dir_url . '/assets/js/plugins/' . $script_min_path),
            'theme_path'    => esc_attr(Airi::$template_dir_url . '/'),
            'ajax_url'      => esc_attr(admin_url('admin-ajax.php')),
            'mm_mb_effect' => esc_attr(Airi()->settings()->get('mm_mb_effect', 1)),
            'header_height' => array(
                'desktop' => array(
                    'normal' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_height', 100))),
                    'sticky' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_sticky_height', 80)))
                ),
                'tablet' => array(
                    'normal' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_sm_height', 100))),
                    'sticky' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_sm_sticky_height', 80)))
                ),
                'mobile' => array(
                    'normal' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_mb_height', 100))),
                    'sticky' => esc_attr(str_replace('px', '', Airi()->settings()->get('header_mb_sticky_height', 80)))
                )
            ),
            'la_extension_available' => get_option('la_extension_available', array(
                'swatches' => true,
                '360' => true,
                'content_type' => true
            )),
            'mobile_bar' => esc_attr(Airi()->settings()->get('enable_header_mb_footer_bar_sticky', 'always'))
        )));

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        wp_add_inline_style('airi-theme', Airi_Helper::compress_text($this->dynamic_css(), true));

        $asset_font_without_domain = apply_filters('airi/filter/assets_font_url', airi_get_relative_url(untrailingslashit(get_template_directory_uri())));

        wp_add_inline_style(
            "font-awesome",
            "@font-face{
                font-family: 'FontAwesome';
                src: url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.eot');
                src: url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.eot') format('embedded-opentype'),
                     url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.woff2') format('woff2'),
                     url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.woff') format('woff'),
                     url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.ttf') format('truetype'),
                     url('{$asset_font_without_domain}/assets/fonts/fontawesome-webfont.svg') format('svg');
                font-weight:normal;
                font-style:normal
            }"
        );
        wp_add_inline_style(
            "airi-theme",
            "@font-face{
                font-family: 'dl-icon';
                src: url('{$asset_font_without_domain}/assets/fonts/dl-icon.eot');
                src: url('{$asset_font_without_domain}/assets/fonts/dl-icon.eot') format('embedded-opentype'),
                     url('{$asset_font_without_domain}/assets/fonts/dl-icon.woff') format('woff'),
                     url('{$asset_font_without_domain}/assets/fonts/dl-icon.ttf') format('truetype'),
                     url('{$asset_font_without_domain}/assets/fonts/dl-icon.svg') format('svg');
                font-weight:normal;
                font-style:normal
            }"
        );

    }

    /**
     * Removes WooCommerce scripts.
     *
     * @access public
     * @since 1.0
     * @param array $scripts The WooCommerce scripts.
     * @return array
     */
    public function remove_woo_scripts($scripts)
    {

        if (isset($scripts['woocommerce-layout'])) {
            unset($scripts['woocommerce-layout']);
        }
        if (isset($scripts['woocommerce-smallscreen'])) {
            unset($scripts['woocommerce-smallscreen']);
        }
        if (isset($scripts['woocommerce-general'])) {
            unset($scripts['woocommerce-general']);
        }
        return $scripts;

    }

    private function dynamic_css()
    {
        ob_start();
        include Airi::$template_dir_path . '/framework/functions/additional_css.php';
        include Airi::$template_dir_path . '/framework/functions/dynamic_css.php';
        return ob_get_clean();
    }

    public function get_custom_css_from_setting(){
        if( $la_custom_css = Airi()->settings()->get('la_custom_css') ){
            printf( '<%1$s id="airi-extra-custom-css">%2$s</%1$s>', 'style', $la_custom_css);
        }
    }

    /**
    * Add async to theme javascript file for performance
    * allow override on `lastudio/theme/defer_scripts` filter
    * @param string $handlers The script tag.
    */

    public function override_defer_scripts( $handlers )
    {
        $handlers[] = 'airi-modernizr-custom';
        $handlers[] = 'airi-plugins';
        $handlers[] = 'airi-theme';
        return $handlers;
    }

    protected function get_fullpage_config()
    {
        $config = array();
        $metadata = Airi()->settings()->get_post_meta(get_the_ID());
        if (!empty($metadata['fp_navigation']) && $metadata['fp_navigation'] != 'off') {
            $config['navigation'] = true;
            $config['navigationPosition'] = esc_attr($metadata['fp_navigation']);
            $config['showActiveTooltip'] = (!empty($metadata['fp_showactivetooltip']) && $metadata['fp_showactivetooltip'] == 'yes') ? true : false;
        }
        if (!empty($metadata['fp_slidenavigation']) && $metadata['fp_slidenavigation'] != 'off') {
            $config['slidesNavigation'] = true;
            $config['slidesNavPosition'] = esc_attr($metadata['fp_slidenavigation']);
        }
        $config['controlArrows'] = (!empty($metadata['fp_controlarrows']) && $metadata['fp_controlarrows'] == 'yes') ? true : false;
        $config['lockAnchors'] = (!empty($metadata['fp_lockanchors']) && $metadata['fp_lockanchors'] == 'yes') ? true : false;
        $config['animateAnchor'] = (!empty($metadata['fp_animateanchor']) && $metadata['fp_animateanchor'] == 'yes') ? true : false;
        $config['keyboardScrolling'] = (!empty($metadata['fp_keyboardscrolling']) && $metadata['fp_keyboardscrolling'] == 'yes') ? true : false;
        $config['recordHistory'] = (!empty($metadata['fp_recordhistory']) && $metadata['fp_recordhistory'] == 'yes') ? true : false;

        $config['autoScrolling'] = (!empty($metadata['fp_autoscrolling']) && $metadata['fp_autoscrolling'] == 'yes') ? true : false;
        $config['fitToSection'] = (!empty($metadata['fp_fittosection']) && $metadata['fp_fittosection'] == 'yes') ? true : false;
        $config['fitToSectionDelay'] = (!empty($metadata['fp_fittosectiondelay'])) ? absint($metadata['fp_fittosectiondelay']) : 1000;

        $config['scrollBar'] = (!empty($metadata['fp_scrollbar']) && $metadata['fp_scrollbar'] == 'yes') ? true : false;
        $config['scrollOverflow'] = (!empty($metadata['fp_scrolloverflow']) && $metadata['fp_scrolloverflow'] == 'yes') ? true : false;
        if ($config['scrollOverflow']) {
            $config['scrollOverflowOptions'] = array(
                'scrollbars' => (!empty($metadata['fp_hidescrollbars']) && $metadata['fp_hidescrollbars'] == 'yes') ? false : true,
                'fadeScrollbars' => (!empty($metadata['fp_fadescrollbars']) && $metadata['fp_fadescrollbars'] == 'yes') ? true : false,
                'interactiveScrollbars' => (!empty($metadata['fp_interactivescrollbars']) && $metadata['fp_interactivescrollbars'] == 'yes') ? true : false
            );
        }
        if (!empty($metadata['fp_bigsectionsdestination']) && $metadata['fp_bigsectionsdestination'] != 'default') {
            $config['bigSectionsDestination'] = esc_attr($metadata['fp_bigsectionsdestination']);
        }

        if (!empty($metadata['fp_contvertical']) && $metadata['fp_contvertical'] == 'yes') {
            $config['continuousVertical'] = true;
            $config['loopBottom'] = false;
            $config['loopTop'] = false;
        } else {
            $config['continuousVertical'] = false;
            $config['loopBottom'] = (!empty($metadata['fp_loopbottom']) && $metadata['fp_loopbottom'] == 'yes') ? true : false;
            $config['loopTop'] = (!empty($metadata['fp_looptop']) && $metadata['fp_looptop'] == 'yes') ? true : false;
        }

        $config['loopHorizontal'] = (!empty($metadata['fp_loophorizontal']) && $metadata['fp_loophorizontal'] == 'yes') ? true : false;
        $config['scrollingSpeed'] = (!empty($metadata['fp_scrollingspeed'])) ? absint($metadata['fp_scrollingspeed']) : 700;

        $fp_easing = !empty($metadata['fp_easing']) ? $metadata['fp_easing'] : 'css3_ease';
        if (substr($fp_easing, 0, 5) == 'css3_') {
            $config['css3'] = true;
            $config['easing'] = "easeInOutCubic";
            $config['easingcss3'] = substr($fp_easing, 5, strlen($fp_easing));
        } else if (substr($fp_easing, 0, 3) == 'js_') {
            $config['css3'] = false;
            $config['easingcss3'] = "ease";
            $config['easing'] = substr($fp_easing, 3, strlen($fp_easing));
        }

        $config['verticalCentered'] = (!empty($metadata['fp_verticalcentered']) && $metadata['fp_verticalcentered'] == 'yes') ? true : false;
        $config['responsiveWidth'] = (!empty($metadata['fp_respwidth'])) ? absint($metadata['fp_respwidth']) : 0;
        $config['responsiveHeight'] = (!empty($metadata['fp_respheight'])) ? absint($metadata['fp_respheight']) : 0;

        $config['paddingTop'] = (!empty($metadata['fp_padding']['top'])) ? absint($metadata['fp_padding']['top']) . 'px' : '0px';
        $config['paddingBottom'] = (!empty($metadata['fp_padding']['bottom'])) ? absint($metadata['fp_padding']['bottom']) . 'px' : '0px';

        $fixedElements = (!empty($metadata['fp_fixedelements'])) ? esc_attr($metadata['fp_fixedelements']) : "";
        $fixedElements = array_filter(explode(',', $fixedElements));
        $fixedElements = array_merge(array('.la_fp_fixed_top', '.la_fp_fixed_bottom'), $fixedElements);

        $config['fixedElements'] = implode(',', $fixedElements);

        $parallax = false;
        if(!empty($metadata['fp_section_effect']) && $metadata['fp_section_effect'] == 'default'){
            $parallax = true;
        }

        $config['parallax'] = $parallax;
        $config['parallaxKey'] = "QU5ZXzlNZGNHRnlZV3hzWVhnPTFyRQ==";
        $config['parallaxOptions'] =  array(
            'percentage' => 50,
            'property' => 'translate',
            'type' => 'reveal'
        );
        return $config;
    }

    public function get_gfont_from_setting(){
        $array = array();
        $main_font = Airi()->settings()->get('main_font');
        $secondary_font = Airi()->settings()->get('secondary_font');
        $highlight_font = Airi()->settings()->get('highlight_font');

        if(!empty($main_font['family'])){
            $array['body'] = $main_font['family'];
        }
        if(!empty($secondary_font['family'])){
            $array['heading'] = $secondary_font['family'];
        }
        if(!empty($highlight_font['family'])){
            $array['highlight'] = $highlight_font['family'];
        }
        return $array;
    }

    public function get_google_font_url(){

        $_tmp_fonts = array();

        $main_font = (array) Airi()->settings()->get('main_font');
        $secondary_font = (array) Airi()->settings()->get('secondary_font');
        $highlight_font = (array) Airi()->settings()->get('highlight_font');

        if(!empty($main_font['family']) && (!empty($main_font['font']) && $main_font['font'] == 'google') ){
            $variant = !empty($main_font['variant']) ? (array) $main_font['variant'] : array();
            $f_name = $main_font['family'];
            if(isset($_tmp_fonts[$f_name])){
                $old_variant = $_tmp_fonts[$f_name];
                $_tmp_fonts[$f_name] = array_unique(array_merge($old_variant, $variant));
            }
            else{
                $_tmp_fonts[$f_name] = $variant;
            }
        }

        if(!empty($secondary_font['family']) && (!empty($secondary_font['font']) && $secondary_font['font'] == 'google')){
            $variant = !empty($secondary_font['variant']) ? (array) $secondary_font['variant'] : array();
            $f_name = $secondary_font['family'];
            if(isset($_tmp_fonts[$f_name])){
                $old_variant = $_tmp_fonts[$f_name];
                $_tmp_fonts[$f_name] = array_unique(array_merge($old_variant, $variant));
            }
            else{
                $_tmp_fonts[$f_name] = $variant;
            }
        }

        if(!empty($highlight_font['family']) && (!empty($highlight_font['font']) && $highlight_font['font'] == 'google')){
            $variant = !empty($highlight_font['variant']) ? (array) $highlight_font['variant'] : array();
            $f_name = $highlight_font['family'];
            if(isset($_tmp_fonts[$f_name])){
                $old_variant = $_tmp_fonts[$f_name];
                $_tmp_fonts[$f_name] = array_unique(array_merge($old_variant, $variant));
            }
            else{
                $_tmp_fonts[$f_name] = $variant;
            }
        }

        if(empty($_tmp_fonts)){
            return '';
        }

        $_tmp_fonts2 = array();

        foreach ( $_tmp_fonts as $k => $v ) {
            if( !empty( $v ) ) {
                $_tmp_fonts2[] = preg_replace('/\s+/', '+', $k) . ':' . implode(',', $v);
            }
            else{
                $_tmp_fonts2[] = preg_replace('/\s+/', '+', $k);
            }
        }
        return esc_url( add_query_arg('family', implode( '%7C', $_tmp_fonts2 ),'//fonts.googleapis.com/css') );
    }

    public function get_google_font_code_url() {
        $fonts_url = '';
        $_font_code = Airi()->settings()->get('font_google_code', '');
        if(!empty($_font_code)){
            $fonts_url = $_font_code;
        }
        return esc_url($fonts_url);
    }

    public function get_google_font_typekit_url(){
        $fonts_url = '';
        $_api_key = Airi()->settings()->get('font_typekit_kit_id', '');
        if(!empty($_api_key)){
            $fonts_url =  '//use.typekit.net/' . preg_replace('/\s+/', '', $_api_key) . '.js';
        }
        return esc_url($fonts_url);
    }

    public function add_custom_header_js(){
        printf( '<%1$s>try{ %2$s }catch (ex){}</%1$s>', 'script', Airi()->settings()->get('header_js') );
    }

    public function add_custom_footer_js(){
        printf( '<%1$s>try{ %2$s }catch (ex){}</%1$s>', 'script', Airi()->settings()->get('footer_js') );
    }

    public function add_meta_into_head(){
        do_action('airi/action/head');
    }
}