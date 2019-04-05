<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_home_10()
{
    return array(
        array(
            'key' => 'header_layout',
            'value' => '6'
        ),
        array(
            'filter_name' => 'airi/filter/header_sidebar_widget_bottom',
            'filter_func' => function( $value ){
                return 'home-07-header-aside-bottom';
            },
            'filter_priority'  => 10,
            'filter_args'  => 1
        ),
        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
.header-v6 #masthead_aside .header-left .header-component-inner {
    display: none;
}
.header-v6 #masthead_aside .mega-menu > li > a {
    text-align: center;
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