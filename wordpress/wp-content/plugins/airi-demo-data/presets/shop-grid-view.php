<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_shop_grid_view()
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
            'key' => 'main_full_width',
            'value' => 'yes'
        ),

        array(
            'key' => 'product_per_page_allow',
            'value' => ''
        ),

        array(
            'key' => 'product_per_page_default',
            'value' => 25
        ),

        array(
            'key' => 'shop_item_space',
            'value' => 20
        ),

        array(
            'key' => 'woocommerce_shop_page_columns',
            'value' => array(
                'xlg' => 5,
                'lg' => 4,
                'md' => 4,
                'sm' => 2,
                'xs' => 1,
                'mb' => 1
            )
        ),

        array(
            'filter_name' => 'airi/filter/page_title',
            'value' => '<header><h1 class="page-title">Shop Grid View</h1></header>'
        ),
    );
}