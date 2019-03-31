<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$shortcode_params = array(
    array(
        'type' => 'attach_image',
        'heading' => __('Upload the banner image', 'airi'),
        'param_name' => 'banner_id'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Height Mode','airi'),
        'param_name' => 'height_mode',
        'value' => array(
            __('1:1','airi') => '1-1',
            __('Original','airi') => 'original',
            __('4:3','airi') => '4-3',
            __('3:4','airi') => '3-4',
            __('16:9','airi') => '16-9',
            __('9:16','airi') => '9-16',
            __('Custom','airi') => 'custom',
        ),
        'std' => 'original',
        'description' => __('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'airi')
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Height', 'airi'),
        'param_name' 	=> 'height',
        'value'			=> '',
        'description' 	=> __('Enter custom height.', 'airi'),
        'dependency' => array(
            'element'   => 'height_mode',
            'value'     => array('custom')
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Design','airi'),
        'param_name' => 'style',
        'value' => array(
            __('Style 1','airi') => '1',
            __('Style 2','airi') => '2',
            __('Style 3','airi') => '3',
            __('Style 4','airi') => '4',
            __('Style 5','airi') => '5',
            __('Style 6','airi') => '6',
            __('Style 7','airi') => '7',
            __('Style 8','airi') => '8',
            __('Style 9','airi') => '9',
            __('Style 10','airi') => '10',
            __('Style 11','airi') => '11',
        ),
        'std' => '1'
    ),

    array(
        'type' => 'vc_link',
        'heading' => __('Banner Link', 'airi'),
        'param_name' => 'banner_link',
        'description' => __('Add link / select existing page to link to this banner', 'airi')
    ),


    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 1', 'airi' ),
        'param_name' => 'title_1',
        'admin_label' => true
    ),

    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 2', 'airi' ),
        'param_name' => 'title_2',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('6','7', '10', '11')
        ),
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 3', 'airi' ),
        'param_name' => 'title_3',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('7', '10')
        ),
    ),

    LaStudio_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),

    LaStudio_Shortcodes_Helper::fieldExtraClass(),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 1', 'airi'),
        'param_name' 	=> 'el_class1',
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 2', 'airi'),
        'param_name' 	=> 'el_class2',
        'dependency' => array(
            'element' => 'style',
            'value' => array('6','7', '11')
        )
    )),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 3', 'airi'),
        'param_name' 	=> 'el_class3',
        'dependency' => array(
            'element' => 'style',
            'value' => array('7', '10')
        )
    )),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'overlay_bg_color',
        'heading' 		=> __('Overlay background color', 'airi'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'overlay_hover_bg_color',
        'heading' 		=> __('Overlay hover background color', 'airi'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'btn_color',
        'heading' 		=> __('Button Color', 'airi'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'btn_bg_color',
        'heading' 		=> __('Button Background Color', 'airi'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'btn_hover_color',
        'heading' 		=> __('Button Hover Color', 'airi'),
        'group' 		=> 'Design'
    ),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'btn_hover_bg_color',
        'heading' 		=> __('Button Hover Background Color', 'airi'),
        'group' 		=> 'Design'
    ),
);

$param_fonts_title1 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_1', __('Title 1', 'airi'));
$param_fonts_title2 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_2', __('Title 2', 'airi'), array(
    'element' => 'style',
    'value' => array('6','7', '10', '11')
));
$param_fonts_title3 = LaStudio_Shortcodes_Helper::fieldTitleGFont('title_3', __('Title 3', 'airi'), array(
    'element' => 'style',
    'value' => array('7', '10')
));


$shortcode_params = array_merge( $shortcode_params, $param_fonts_title1, $param_fonts_title2, $param_fonts_title3);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Banner Box', 'airi'),
        'base'			=> 'la_banner',
        'icon'          => 'la-wpb-icon la_banner',
        'category'  	=> __('La Studio', 'airi'),
        'description'   => __('Displays the banner image with Information', 'airi'),
        'params' 		=> $shortcode_params
    ),
    'la_banner'
);