<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_shop_detail_sidebar()
{
    return array(

        array(
            'key' => 'layout_single_product',
            'value' => 'col-2cl'
        ),
        array(
            'key' => 'woocommerce_product_page_design',
            'value' => '1'
        )

    );
}