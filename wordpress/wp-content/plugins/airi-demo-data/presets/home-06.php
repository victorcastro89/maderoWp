<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_home_06()
{
    return array(

        array(
            'key' => 'header_height',
            'value' => '120px'
        ),


        array(
            'key' => 'footer_layout',
            'value' => '3col363'
        ),

        array(
            'key' => 'enable_footer_top',
            'value' => 'no'
        ),
        array(
            'key' => 'footer_full_width',
            'value' => 'yes'
        ),

        array(
            'filter_name' => 'airi/filter/footer_column_1',
            'value' => 'footer-layout-3-column-1'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_2',
            'value' => 'footer-layout-3-column-2'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_3',
            'value' => 'footer-layout-3-column-3'
        ),

        array(
            'key' => 'footer_copyright',
            'value' => '
<div class="row">
	<div class="col-xs-12 text-center">
		© 2018 AIRI All rights reserved. Designed by LA-STUDIO
	</div>
</div>
'
        ),
        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
@media(min-width: 800px){
.site-footer .widget{
    margin-bottom: 0;
}
.site-footer .footer-column-1 .widget {
    margin-top: -15px;
}
}

';
                }
                return $value;
            },
            'filter_priority'  => 10,
            'filter_args'  => 2
        ),

        array(
            'key' => 'footer_text_color|footer_link_color',
            'value' => '#282828'
        ),
        array(
            'key' => 'footer_copyright_text_color|footer_copyright_link_color',
            'value' => '#8A8A8A'
        )
    );
}