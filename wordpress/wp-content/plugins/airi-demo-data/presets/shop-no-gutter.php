<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_shop_no_gutter()
{
    return array(

        array(
            'key' => 'layout_archive_product',
            'value' => 'col-1c'
        ),

        array(
            'key' => 'main_full_width_archive_product',
            'value' => 'yes'
        ),

        array(
            'key' => 'active_shop_filter',
            'value' => 'on'
        ),

        array(
            'key' => 'woocommerce_toggle_grid_list',
            'value' => 'off'
        ),

        array(
            'key' => 'product_per_page_allow',
            'value' => ''
        ),

        array(
            'key' => 'product_per_page_default',
            'value' => 6
        ),
        array(
            'key' => 'shop_item_space',
            'value' => 0
        ),

        array(
            'key' => 'woocommerce_shop_page_columns',
            'value' => array(
                'xlg' => 4,
                'lg' => 4,
                'md' => 3,
                'sm' => 2,
                'xs' => 1,
                'mb' => 1
            )
        ),

        array(
            'filter_name' => 'airi/filter/page_title',
            'value' => '<header><h1 class="page-title">Shop No Gutter</h1></header>'
        ),

        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
.products-grid .product_item--info {
    padding-bottom: 50px;
    padding-left: 30px;
    padding-right: 30px;
}
.enable-main-fullwidth .site-main > .container{
    padding: 0;
}
@media(min-width: 1300px){
    .wc-toolbar-container .wc-toolbar-top {
        padding-left: 30px;
        padding-right: 30px;
    }
}
@media(min-width: 1500px){
    .wc-toolbar-container .wc-toolbar-top {
        padding-left: 80px;
        padding-right: 80px;
    }
}
';
                }
                return $value;
            },
            'filter_priority'  => 10,
            'filter_args'  => 2
        ),
    );
}