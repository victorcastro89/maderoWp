<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LaStudio
 * @subpackage LaStudio/admin
 * @author     Duy Pham <dpv.0990@gmail.com>
 */
class LaStudio_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_style('jquery-chosen');

		if(wp_style_is('font-awesome', 'registered')) {
			wp_deregister_style('font-awesome');
		}

		// wp core styles
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lastudio-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'font-awesome', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-awesome.min.css', array(), null);
		wp_enqueue_style( 'la-icon-outline', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-la-icon-outline.min.css', array(), null);
		wp_enqueue_style( 'font-nucleo-glyph', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-nucleo-glyph.min.css', array(), null);
		wp_enqueue_style( 'la-svg-icon', plugin_dir_url( dirname(__FILE__) ) . 'public/css/la-svg.css', array(), null);

		if ( is_rtl() ) {
			wp_enqueue_style( $this->plugin_name . '-rtl', plugin_dir_url(__FILE__) . 'css/lastudio-admin-rtl.css', array(), $this->version, 'all');
		}

		$asset_font_without_domain = apply_filters('LaStudio/filter/assets_font_url', untrailingslashit(plugin_dir_url( dirname(__FILE__) )));

		wp_add_inline_style(
			$this->plugin_name,
			"@font-face {
				font-family: 'icomoon';
				src:url('{$asset_font_without_domain}/public/fonts/icomoon.ttf');
				font-weight: normal;
				font-style: normal;
			}"
		);

		wp_add_inline_style(
			'font-awesome',
			"@font-face{
				font-family: 'FontAwesome';
				src: url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.eot');
				src: url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.svg') format('svg');
				font-weight:normal;
				font-style:normal
			}"
		);
		wp_add_inline_style(
			'la-icon-outline',
			"@font-face {
				font-family: 'LaStudio Outline';
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot');
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-outline.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-outline.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);
		wp_add_inline_style(
			'font-nucleo-glyph',
			"@font-face {
				font-family: 'Nucleo Glyph';
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot');
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_script('jquery-chosen');
		// admin utilities
		wp_enqueue_media();

		$script_dependencies = array(
			'jquery',
			'wp-color-picker',
			'jquery-ui-dialog',
			'jquery-ui-sortable',
			'jquery-ui-accordion'
		);

		wp_register_script( 'lastudio-plugins', plugin_dir_url( __FILE__ ) . 'js/lastudio-admin-plugin.js', $script_dependencies, $this->version, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lastudio-admin.js', array( 'lastudio-plugins' ), $this->version, true );

		$vars = array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'swatches_nonce' => wp_create_nonce( 'swatches_nonce' )
		);
		wp_localize_script( $this->plugin_name , 'la_swatches_vars', $vars );

	}

	public function admin_customize_enqueue(){
		wp_enqueue_script( 'lastudio-admin-customize', plugin_dir_url( __FILE__ ) .'/js/lastudio-admin-customize.js', array( 'jquery','customize-preview' ), $this->version, true );
	}

	/**
	 * Register Text Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_text( $value ) {
		return wp_filter_nohtml_kses( $value );
	}

	/**
	 * Register Textarea Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_textarea( $value ) {
		global $allowedposttags;
		return wp_kses( $value, $allowedposttags );
	}

	/**
	 * Register Checkbox Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_checkbox( $value ) {
		if( ! empty( $value ) && $value == 1 ) {
			$value = true;
		}
		if( empty( $value ) ) {
			$value = false;
		}
		return $value;
	}

	/**
	 * Register Image Select Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_image_select( $value ) {
		if( isset( $value ) && is_array( $value ) ) {
			if( count( $value ) ) {
				$value = $value;
			}
			else {
				$value = $value[0];
			}
		}
		else if ( empty( $value ) ) {
			$value = '';
		}

		return $value;
	}

	/**
	 * Register Group Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_group( $value ) {
		return ( empty( $value ) ) ? '' : $value;
	}

	/**
	 * Register Title Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_title( $value ) {
		return sanitize_title( $value );
	}

	/**
	 * Register Text Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_clean( $value ) {
		return $value;
	}

	/**
	 * Register Email Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_email( $value ) {
		if ( ! sanitize_email( $value ) ) {
			return __( 'Please write a valid email address!', 'lastudio' );
		}
	}

	/**
	 * Register Numeric Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_numeric( $value ) {
		if ( ! is_numeric( $value ) ) {
			return __( 'Please write a numeric data!', 'lastudio' );
		}
	}

	/**
	 * Register Required Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_required( $value ) {
		if ( empty( $value ) ) {
			return __( 'Fatal Error! This field is required!', 'lastudio' );
		}
	}

	private function get_icon_library(){

		$transient_name = 'la_get_icon_library_all_' . LaStudio_Cache_Helper::get_transient_version('icon_library');
		$cache = get_transient($transient_name);
		if (empty($cache)) {
			$jsons = apply_filters('lastudio/filter/framework/field/icon/json', array(
				plugin_dir_path( dirname(__FILE__) ) . 'public/fonts/font-awesome.json'
			));
			if (!empty($jsons)) {
				$cache_tmp = array();
				foreach ($jsons as $path) {
					$file_data = @file_get_contents($path);
					if (!is_wp_error($file_data)) {
						$cache_tmp[] = json_decode($file_data, false);
					}
				}
				if(!empty($cache_tmp)){
					set_transient( $transient_name, $cache_tmp, DAY_IN_SECONDS * 30 );
					return $cache_tmp;
				}
			}
		}
		return !empty($cache) ? $cache : array();
	}

	/**
	 * Get icons from admin ajax
	 *
	 * @since 1.0.0
	 */
	public function ajax_get_icons(){
		$icons = $this->get_icon_library();
		if( ! empty( $icons ) ) {
			foreach ( $icons as $icon_object ) {
				if( is_object( $icon_object ) ) {
					echo ( count( $icons ) >= 2 ) ? '<h4 class="la-icon-title">'. $icon_object->name .'</h4>' : '';
					foreach ( $icon_object->icons as $icon ) {
						echo '<a class="la-icon-tooltip" data-la-icon="'. $icon .'" data-title="'. $icon .'"><span class="la-icon--selector la-selector"><i class="'. $icon .'"></i></span></a>';
					}
				} else {
					echo '<h4 class="la-icon-title">'. __( 'Error! Can not load json file.', 'lastudio' ) .'</h4>';
				}
			}
		}
		die();
	}

	/**
	 * Render icon on admin footer
	 *
	 * @since 1.0.0
	 */
	public function render_admin_footer(){
		include_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/admin-footer.php';
	}

	/**
	 * Get value form admin field autocomplete
	 *
	 * @since 1.0.0
	 */
	public function ajax_autocomplete(){
		if ( empty( $_GET['query_args'] ) || empty( $_GET['s'] ) ) {
			echo '<b>' . __('Query is empty ...', 'lastudio' ) . '</b>';
			die();
		}
		ob_start();

		$args = array(
			's' => $_GET['s']
		);

		$query = new WP_Query( wp_parse_args( $_GET['query_args'], $args ) );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<div data-id="' . get_the_ID() . '">' . get_the_title() . '</div>';
			}
		} else {
			echo '<b>' . __('Not found', 'lastudio' ) . '</b>';
		}

		wp_reset_postdata();
		echo ob_get_clean();
		die();
	}

	/**
	 * Get theme options form export field
	 *
	 * @since 1.0.0
	 */
	public function ajax_export_options(){
		$unique = isset($_REQUEST['unique']) ? $_REQUEST['unique'] : 'la_options';
		header('Content-Type: plain/text');
		header('Content-disposition: attachment; filename=backup-'.esc_attr($unique).'-'. gmdate( 'd-m-Y' ) .'.txt');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo wp_json_encode(get_option($unique));
		die();
	}


	/**
	 * Override Easy Mailchimp Shortcode
	 */
	public function add_el_class_field_to_easy_mc_shortcode(){
		vc_add_params('yikes-mailchimp', array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'latudio' ),
				'param_name' => 'el_class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'latudio' ),
			)
		));
	}
	public function add_global_var_for_easy_mc_shortcode($out, $pairs, $atts){
		$out['el_class'] = '';
		if(!empty($atts['el_class'])){
			$GLOBALS['easy_mc_parem_el_class'] = esc_attr($atts['el_class']);
		}
		return $out;
	}
	public function unset_global_var_when_call_success_esy_mc_shortcode(){
		if(isset($GLOBALS['easy_mc_parem_el_class'])){
			unset($GLOBALS['easy_mc_parem_el_class']);
		}
	}
	public function add_el_class_to_ouput_easy_mc_shortcode($class){
		if(isset($GLOBALS['easy_mc_parem_el_class'])){
			if(!empty($class)){
				$class .= ' ';
			}
			$class .= $GLOBALS['easy_mc_parem_el_class'];
		}
		return $class;
	}
}
