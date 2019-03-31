<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_home_03()
{
    return array(
        array(
            'key' => 'header_transparency',
            'value' => 'yes'
        ),

        array(
            'key' => 'header_layout',
            'value' => '6'
        ),

        array(
            'key' => 'enable_header_top',
            'value' => 'yes'
        ),

        array(
            'key' => 'header_access_icon_1',
            'value' => array(
                array(
                    'type' => 'aside_header',
                    'icon' => 'dl-icon-menu2'
                )
            )
        ),

        array(
            'key' => 'header_top_elements',
            'value' => array(
                array(
                    'type' => 'dropdown_menu',
                    'icon' => 'fa fa-user-circle-o',
                    'menu_id' => 17
                ),
                array(
                    'type' => 'cart',
                    'icon' => 'dl-icon-cart4'
                ),
                array(
                    'type' => 'search_1'
                )
            )
        ),

        array(
            'key' => 'transparency_header_text_color|transparency_header_link_color|transparency_mm_lv_1_color|transparency_header_top_text_color|transparency_header_top_link_color',
            'value' => '#282828'
        ),

        array(
            'key' => 'transparency_mm_lv_1_hover_color',
            'value' => '#cf987e'
        ),


        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
.header-v6 #masthead_aside {
    background-color: transparent;
}
@media(max-width: 992px){
    .header-v6 .site-header .site-header-top {
        display: none;
    }
}
#masthead_aside .site-branding {
    float: left;
}
.header-v6 #masthead_aside .header-left .header-component-inner a.component-target {
    font-size: 24px;
    margin-left: 20px;
    margin-top: 10px;
}
.header-v6 #masthead_aside .header-right {
    margin-top: calc( (100vh - 400px)/2 - 150px );
}
';
                }
                return $value;
            },
            'filter_priority'  => 10,
            'filter_args'  => 2
        )
    );
}