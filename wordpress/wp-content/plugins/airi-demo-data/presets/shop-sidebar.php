<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_shop_sidebar()
{
    return array(
        array(
            'filter_name' => 'airi/filter/page_title',
            'value' => '<header><h1 class="page-title">Shop Sidebar</h1></header>'
        ),
    );
}