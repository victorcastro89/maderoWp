<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_home_20()
{
    return array(

        array(
            'key' => 'enable_header_top',
            'value' => 'yes'
        ),
        array(
            'key' => 'header_top_elements',
            'value' => array(
                array (
                    'type' => 'text',
                    'icon' => '',
                    'text' => 'Welcome to AIRI Mutipurpose Woocommerce Theme',
                    'el_class' => 'm7_header_top_text hidden-xs'
                ),

                array (
                    'type' => 'dropdown_menu',
                    'text' => 'Currency: <span>USD</span>',
                    'icon' => '',
                    'menu_id' => 86,
                    'el_class' => 'la_com_dropdown_show_arrow la_com_dropdown_currency'
                ),
                array (
                    'type' => 'dropdown_menu',
                    'text' => 'Language: <span>English</span>',
                    'menu_id' => 85,
                    'icon' => '',
                    'el_class' => 'la_com_dropdown_show_arrow la_com_dropdown_language'
                ),
            )
        ),

        array(
            'key' => 'header_top_background_color',
            'value' => '#F9F9F9'
        ),
        array(
            'key' => 'header_layout',
            'value' => '9'
        ),

        array(
            'key' => 'header_access_icon_2',
            'value' => array(
                array(
                    'type' => 'text',
                    'text' => '[la_social_link]',
                    'el_class' => 'header_demo7_1'
                ),
                array(
                    'type' => 'text',
                    'text' => '<span>24/7 HOTLINE</span><span>(+85) 246 888 9889</span>',
                    'el_class' => 'm7_header_com_text m7_header_com_text_1'
                ),
                array(
                    'type' => 'text',
                    'text' => '<span>LOCATION</span><span>United Kingdom</span>',
                    'el_class' => 'm7_header_com_text m7_header_com_text_2'
                )
            )
        ),

        array(
            'key' => 'enable_searchbox_header',
            'value' => 'yes'
        ),

        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
.site-header .header-left .header-component-inner {
    margin-top: -10px;
}
.m7_header_com_text_1{
    clear: both;
}
.m7_header_com_text .component-target-text{
    position: relative;
    padding-left: 16px;
}
.m7_header_com_text .component-target-text:before{
    content : "";
    border-left: 1px solid #D0D0D0;
    height: 30px;
    position: absolute;
    left: -1px;
    top: 2px;
}
.m7_header_com_text .component-target-text span{
    display: block;
    color: #282828;
    font-size: 12px;
    line-height: 16px;
}
.m7_header_com_text .component-target-text span:first-child{
    color: #8A8A8A;
}
.site-header-top {
    padding-top: 3px;
    padding-bottom: 3px;
}
.la_compt_iem.la_com_action--searchbox.searchbox__01 {
    display: none;
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