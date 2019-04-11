<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'tgmpa_register', 'airi_register_required_plugins' );

if(!function_exists('airi_register_required_plugins')){

	function airi_register_required_plugins() {

		$plugins = array();

		$plugins[] = array(
			'name'					=> esc_html_x('WPBakery Visual Composer', 'admin-view', 'airi'),
			'slug'					=> 'js_composer',
			'source'				=> get_template_directory() . '/plugins/js_composer.zip',
			'required'				=> true,
			'version'				=> '5.6'
		);

		$plugins[] = array(
			'name'					=> esc_html_x('LA-Studio Core', 'admin-view', 'airi'),
			'slug'					=> 'lastudio',
			'source'				=> get_template_directory() . '/plugins/lastudio.zip',
			'required'				=> true,
			'version'				=> '1.0.5'
		);

		$plugins[] = array(
			'name'     				=> esc_html_x('WooCommerce', 'admin-view', 'airi'),
			'slug'     				=> 'woocommerce',
			'version'				=> '3.5.2',
			'required' 				=> false
		);

		$plugins[] = array(
			'name'     				=> esc_html_x('Envato Market', 'admin-view', 'airi'),
			'slug'     				=> 'envato-market',
			'source'   				=> 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required' 				=> false,
			'version' 				=> '2.0.0'
		);

		$plugins[] = array(
			'name'					=> esc_html_x('Airi Package Demo Data', 'admin-view', 'veera'),
			'slug'					=> 'airi-demo-data',
			'source'				=> 'https://github.com/la-studioweb/resource/raw/master/airi/airi-demo-data.zip',
			'required'				=> true,
			'version'				=> '1.0.0'
		);

		$plugins[] = array(
			'name' 					=> esc_html_x('Contact Form 7', 'admin-view', 'airi'),
			'slug' 					=> 'contact-form-7',
			'required' 				=> false
		);

		$plugins[] = array(
			'name' 					=> esc_html_x('Easy Forms for MailChimp', 'admin-view', 'airi'),
			'slug' 					=> 'yikes-inc-easy-mailchimp-extender',
			'required' 				=> false
		);

		$plugins[] = array(
			'name'					=> esc_html_x('Slider Revolution', 'admin-view', 'airi'),
			'slug'					=> 'revslider',
			'source'				=> get_template_directory() . '/plugins/revslider.zip',
			'required'				=> false,
			'version'				=> '5.4.8'
		);

		$config = array(
			'id'           				=> 'airi',
			'default_path' 				=> '',
			'menu'         				=> 'tgmpa-install-plugins',
			'has_notices'  				=> true,
			'dismissable'  				=> true,
			'dismiss_msg'  				=> '',
			'is_automatic' 				=> false,
			'message'      				=> ''
		);

		tgmpa( $plugins, $config );

	}

}
