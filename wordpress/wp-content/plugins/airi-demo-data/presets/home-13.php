<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_preset_home_13()
{
    return array(

        array(
            'key' => 'header_layout',
            'value' => '2'
        ),

        array(
            'key' => 'header_transparency',
            'value' => 'no'
        ),

        array(
            'key' => 'header_height',
            'value' => '120px'
        ),


        array(
            'key' => 'footer_layout',
            'value' => '5col32223'
        ),
        array(
            'key' => 'enable_footer_top',
            'value' => 'no'
        ),

        array(
            'key' => 'footer_copyright_background_color',
            'value' => '#000000'
        ),
        array(
            'key' => 'footer_text_color|footer_link_color|footer_copyright_text_color|footer_copyright_link_color',
            'value' => '#868686'
        ),
        array(
            'key' => 'footer_heading_color',
            'value' => '#fff'
        ),
        array(
            'key' => 'footer_background',
            'value' => array(
                'color'       => '#000000'
            )
        ),
        array(
            'key' => 'footer_space',
            'value' => array(
                'padding_top'       => '55px',
                'padding_bottom'    => '0'
            )
        ),

        array(
            'filter_name' => 'airi/filter/footer_column_1',
            'value' => 'footer-layout-2-column-1'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_2',
            'value' => 'f-col-1'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_3',
            'value' => 'f-col-2'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_4',
            'value' => 'f-col-3'
        ),
        array(
            'filter_name' => 'airi/filter/footer_column_5',
            'value' => 'footer-layout-2-column-5'
        ),

        array(
            'filter_name' => 'airi/setting/option/get_single',
            'filter_func' => function( $value, $key ){
                if( $key == 'la_custom_css'){
                    $value .= '
.site-footer ul li {
    margin-bottom: 15px;
}
.footer-top .widget .widget-title {
    text-transform: uppercase;
    font-weight: 500;
    letter-spacing: 2px;
}
.footer-top .widget .widget-title:after {
    content: "";
    border-bottom: 1px solid #CF987E;
    display: block;
    width: 30px;
    padding-top: 20px;
}

.site-footer .la-contact-info .la-contact-item {
    margin-bottom: 15px;
}
.site-footer .la-contact-info .la-contact-address {
    line-height: normal;
}
.isLaWebRoot .la-footer-5col32223 .footer-column-5 .footer-column-inner {
    width: 100%;
    float: none;
}
.la-footer-5col32223 .footer-column-1 .footer-column-inner {
    width: 300px;
}
.footer-bottom .footer-bottom-inner .la-headings {
    position: relative;
}
.footer-bottom .footer-bottom-inner .la-headings:before {
    content: "";
    height: 48px;
    width: 1px;
    background: #3E3E3E;
    position: absolute;
    left: -50px;
    top: 5px;
    opacity: 0.5;
}
.footer-bottom .footer-bottom-inner .col-md-3:first-child .la-headings:before{
    display: none;
}
.footer-bottom .footer-bottom-inner {
    padding-top: 25px;
}
@media(min-width: 1200px){
    .la-footer-5col32223 .footer-column {
        width: 16%;
    }
    .la-footer-5col32223 .footer-column-1 {
        width: 30%;
    }
    .la-footer-5col32223 .footer-column-5 {
        width: 22%;
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
            'key' => 'footer_copyright',
            'value' => '
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-3">
[la_heading tag="div" alignment="left" title="FREESHIPPING WORLD WIDE" title_class="letter-spacing-1 font-weight-500 padding-bottom-5" title_fz="lg:12px;" title_color="#d0d0d0" subtitle_fz="lg:12px;" subtitle_color="#949494"]Freeship over oder $100[/la_heading]
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
[la_heading tag="div" alignment="left" title="30 DAYS MONEY BACK" title_class="letter-spacing-1 font-weight-500 padding-bottom-5" title_fz="lg:12px;" title_color="#d0d0d0" subtitle_fz="lg:12px;" subtitle_color="#949494"]You can back money any times[/la_heading]
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
[la_heading tag="div" alignment="left" title="PROFESSIONAL SUPPORT 24/7" title_class="letter-spacing-1 font-weight-500 padding-bottom-5" title_fz="lg:12px;" title_color="#d0d0d0" subtitle_fz="lg:12px;" subtitle_color="#949494"]info@la-studioweb.com[/la_heading]
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
[la_heading tag="div" alignment="left" title="100% SECURE CHECKOUT" title_class="letter-spacing-1 font-weight-500 padding-bottom-5" title_fz="lg:12px;" title_color="#d0d0d0" subtitle_fz="lg:12px;" subtitle_color="#949494"]Protect buyer & clients[/la_heading]
    </div>
</div>
<div class="row font-size-11 padding-top-20 padding-bottom-5">
	<div class="col-xs-12 text-center">
		© 2018 AIRI All rights reserved. Designed by LA-STUDIO
	</div>
</div>
'
        ),
    );
}