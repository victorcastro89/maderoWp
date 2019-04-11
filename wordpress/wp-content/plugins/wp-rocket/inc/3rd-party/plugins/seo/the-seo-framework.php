<?php

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * This file is loaded at plugins_loaded, priority 10.
 * This function is available at plugins_loaded, priority 5:
 */
if ( ! function_exists( 'the_seo_framework' ) ) {
	return;
}

rocket_add_tsf_compat();
/**
 * Runs detection and adds extra compatibility for The SEO Framework plugin.
 *
 * @since TODO
 * @author Sybre Waaijer
 */
function rocket_add_tsf_compat() {

	$tsf = the_seo_framework();

	// Either TSF < 3.1, or the plugin's silenced (soft-disabled) via a drop-in.
	if ( empty( $tsf->loaded ) ) {
		return;
	}

	/**
	 * 1. Performs option & other checks.
	 * 2. Checks for conflicting sitemap plugins that might prevent loading.
	 *
	 * These methods cache their output at runtime.
	 *
	 * @link https://github.com/wp-media/wp-rocket/issues/899
	 */
	if ( $tsf->can_run_sitemap() && ! $tsf->detect_sitemap_plugin() ) {
		rocket_add_tsf_sitemap_compat();
	}
}

/**
 * Adds compatibility for the sitemap functionality in The SEO Framework plugin.
 *
 * @since TODO
 * @author Sybre Waaijer
 */
function rocket_add_tsf_sitemap_compat() {
	add_filter( 'rocket_first_install_options', 'rocket_add_tsf_seo_sitemap_option' );
	add_filter( 'rocket_inputs_sanitize', 'rocket_tsf_seo_sitemap_option_sanitize' );
	add_filter( 'rocket_sitemap_preload_list', 'rocket_add_tsf_sitemap_to_preload' );
	add_filter( 'rocket_sitemap_preload_options', 'rocket_sitemap_add_tsf_sitemap_to_preload_option' );
}

/**
 * Adds a sitemap option in WP Rocket for The SEO Framework.
 *
 * @since TODO
 * @author Sybre Waaijer
 * @source ./yoast-seo.php (Remy Perona)
 *
 * @param array $options WP Rocket options array.
 * @return array Updated WP Rocket options array
 */
function rocket_add_tsf_seo_sitemap_option( $options ) {
	$options['tsf_xml_sitemap'] = 0;

	return $options;
}

/**
 * Sanitizes the added sitemap option for The SEO Framework.
 *
 * @since TODO
 * @author Sybre Waaijer
 * @source ./yoast-seo.php (Remy Perona)
 *
 * @param array $inputs WP Rocket inputs array.
 * @return array Sanitized WP Rocket inputs array
 */
function rocket_tsf_seo_sitemap_option_sanitize( $inputs ) {
	$inputs['tsf_xml_sitemap'] = ! empty( $inputs['tsf_xml_sitemap'] ) ? 1 : 0;

	return $inputs;
}

/**
 * Adds TSF sitemap URLs to preload.
 *
 * @since TODO
 * @author Sybre Waaijer
 * @source ./yoast-seo.php (Remy Perona)
 *
 * @param array $sitemaps Sitemaps to preload.
 * @return array Updated Sitemaps to preload
 */
function rocket_add_tsf_sitemap_to_preload( $sitemaps ) {
	if ( get_rocket_option( 'tsf_xml_sitemap', false ) ) {
		$sitemaps[] = the_seo_framework()->get_sitemap_xml_url();
	}

	return $sitemaps;
}

/**
 * Add The SEO Framework SEO option to WP Rocket settings
 *
 * @since TODO
 * @author Sybre Waaijer
 * @source ./yoast-seo.php (Remy Perona)
 *
 * @param array $options WP Rocket settings array.
 * @return array Updated WP Rocket settings array
 */
function rocket_sitemap_add_tsf_sitemap_to_preload_option( $options ) {
	$options['tsf_xml_sitemap'] = [
		'type'              => 'checkbox',
		'container_class'   => [
			'wpr-field--children',
		],
		'label'             => __( 'The SEO Framework XML sitemap', 'rocket' ),
		// translators: %s = Name of the plugin.
		'description'       => sprintf( __( 'We automatically detected the sitemap generated by the %s plugin. You can check the option to preload it.', 'rocket' ), 'The SEO Framework' ),
		'parent'            => 'sitemap_preload',
		'section'           => 'preload_section',
		'page'              => 'preload',
		'default'           => 0,
		'sanitize_callback' => 'sanitize_checkbox',
	];

	return $options;
}
