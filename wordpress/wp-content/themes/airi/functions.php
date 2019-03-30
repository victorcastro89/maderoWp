<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Require plugins vendor
 */

require_once get_template_directory() . '/plugins/tgm-plugin-activation/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/plugins/plugins.php';

/**
 * Include the main class.
 */

include_once get_template_directory() . '/framework/classes/class-core.php';


Airi::$template_dir_path   = get_template_directory();
Airi::$template_dir_url    = get_template_directory_uri();
Airi::$stylesheet_dir_path = get_stylesheet_directory();
Airi::$stylesheet_dir_url  = get_stylesheet_directory_uri();

/**
 * Include the autoloader.
 */
include_once Airi::$template_dir_path . '/framework/classes/class-autoload.php';

new Airi_Autoload();

/**
 * load functions for later usage
 */

require_once Airi::$template_dir_path . '/framework/functions/functions.php';

new Airi_Multilingual();

if(!function_exists('Airi')){
    function Airi() {
        return Airi::get_instance();
    }
}

new Airi_Scripts();

new Airi_Admin();

new Airi_WooCommerce();

new Airi_WooCommerce_Wishlist();

new Airi_WooCommerce_Compare();

new Airi_Visual_Composer();

/**
 * Set the $content_width global.
 */
global $content_width;
if ( ! is_admin() ) {
    if ( ! isset( $content_width ) || empty( $content_width ) ) {
        $content_width = (int) Airi()->layout()->get_content_width();
    }
}

require_once Airi::$template_dir_path . '/framework/functions/extra-functions.php';

require_once Airi::$template_dir_path . '/framework/functions/update.php';
