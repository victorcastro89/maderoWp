<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * General settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function airi_options_section_general( $sections ) {
    $sections['general'] = array(
        'name'          => 'general_panel',
        'title'         => esc_html_x('General', 'admin-view', 'airi'),
        'icon'          => 'fa fa-tachometer',
        'sections' => array(
            array(
                'name'      => 'general_sections',
                'title'     => esc_html_x('General', 'admin-view', 'airi'),
                'icon'      => 'fa fa-check',
                'fields'    => array(
                    array(
                        'id'        => 'layout',
                        'title'     => esc_html_x('Global Layout', 'admin-view', 'airi'),
                        'type'      => 'image_select',
                        'radio'     => true,
                        'default'   => 'col-1c',
                        'desc'      => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'airi'),
                        'options'   => Airi_Options::get_config_main_layout_opts(true, false)
                    ),
                    array(
                        'id'        => 'body_boxed',
                        'type'      => 'radio',
                        'default'   => 'no',
                        'class'     => 'la-radio-style',
                        'title'     => esc_html_x('Enable Layout Boxed', 'admin-view', 'airi'),
                        'options'   => Airi_Options::get_config_radio_opts(false)
                    ),
                    array(
                        'id'        => 'body_max_width',
                        'type'      => 'slider',
                        'default'    => 1230,
                        'title'     => esc_html_x( 'Body Max Width', 'admin-view', 'airi' ),
                        'dependency' => array('body_boxed_yes', '==', 'true'),
                        'options'   => array(
                            'step'    => 5,
                            'min'     => 900,
                            'max'     => 2000,
                            'unit'    => 'px'
                        )
                    ),
                    array(
                        'id'        => 'main_full_width',
                        'type'      => 'radio',
                        'default'   => 'no',
                        'class'     => 'la-radio-style',
                        'title'     => esc_html_x('100% Main Width', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'airi'),
                        'options'   => Airi_Options::get_config_radio_opts(false)
                    ),
                    array(
                        'id'            => 'main_space',
                        'type'          => 'spacing',
                        'title'         => esc_html_x('Custom Main Space', 'admin-view', 'airi'),
                        'desc'          => esc_html_x('Leave empty if you not need', 'admin-view', 'airi'),
                        'unit' 	        => 'px',
                        'default'       => array(
                            'top'       => '70',
                            'bottom'    => '30'
                        )
                    ),

                    array(
                        'id'        => 'backtotop_btn',
                        'type'      => 'radio',
                        'default'   => 'no',
                        'class'     => 'la-radio-style',
                        'title'     => esc_html_x('"Back to top" Button', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Turn on to show "Back to top" button', 'admin-view', 'airi'),
                        'options'   => Airi_Options::get_config_radio_opts(false)
                    )
                )
            ),
            array(
                'name'      => 'favicon_sections',
                'title'     => esc_html_x('Custom Favicon', 'admin-view', 'airi'),
                'icon'      => 'fa fa-check',
                'fields'    => array(
                    array(
                        'id'        => 'favicon',
                        'type'      => 'image',
                        'title'     => esc_html_x('Favicon', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Favicon for your website at 16px x 16px.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'favicon_iphone',
                        'type'      => 'image',
                        'title'     => esc_html_x('Apple iPhone Icon Upload', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Favicon for Apple iPhone at 57px x 57px.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'favicon_ipad',
                        'type'      => 'image',
                        'title'     => esc_html_x('Apple iPad Icon Upload', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Favicon for Apple iPad at 72px x 72px.', 'admin-view', 'airi')
                    )
                )
            ),
            array(
                'name'      => 'logo_sections',
                'title'     => esc_html_x('Logo', 'admin-view', 'airi'),
                'icon'      => 'fa fa-check',
                'fields'    => array(
                    array(
                        'id'        => 'logo',
                        'type'      => 'image',
                        'title'     => esc_html_x('Default Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for your logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_2x',
                        'type'      => 'image',
                        'title'     => esc_html_x('Retina Default Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for the retina version of the logo. It should be exactly 2x the size of the main logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_transparency',
                        'type'      => 'image',
                        'title'     => esc_html_x('Transparency Header Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for your transparency header logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_transparency_2x',
                        'type'      => 'image',
                        'title'     => esc_html_x('Retina Transparency Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for the retina version of the logo. It should be exactly 2x the size of the transparency header logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_mobile',
                        'type'      => 'image',
                        'title'     => esc_html_x('Mobile Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for your mobile logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_mobile_2x',
                        'type'      => 'image',
                        'title'     => esc_html_x('Retina Mobile Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for the retina version of the logo. It should be exactly 2x the size of the mobile logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_mobile_transparency',
                        'type'      => 'image',
                        'title'     => esc_html_x('Mobile Transparency Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for your mobile logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_mobile_transparency_2x',
                        'type'      => 'image',
                        'title'     => esc_html_x('Retina Mobile Transparency Logo', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Select an image file for the retina version of the logo. It should be exactly 2x the size of the mobile logo.', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'logo_height',
                        'type'      => 'slider',
                        'default'    => '',
                        'title'     => esc_html_x( '[Desktop] Logo width', 'admin-view', 'airi' ),
                        'options'   => array(
                            'step'    => 1,
                            'min'     => 50,
                            'max'     => 1000,
                            'unit'    => 'px'
                        )
                    ),
                    array(
                        'id'        => 'logo_mobile_height',
                        'type'      => 'slider',
                        'default'    => '',
                        'title'     => esc_html_x( '[Mobile] Logo width', 'admin-view', 'airi' ),
                        'options'   => array(
                            'step'    => 1,
                            'min'     => 50,
                            'max'     => 1000,
                            'unit'    => 'px'
                        )
                    ),
                )
            ),
            array(
                'name'      => 'color_sections',
                'title'     => esc_html_x('Colors', 'admin-view', 'airi'),
                'icon'      => 'fa fa-paint-brush',
                'fields'    => array(
                    array(
                        'id'        => 'body_background',
                        'type'      => 'background',
                        'title'     => esc_html_x('Body Background', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'body_boxed_background',
                        'type'      => 'background',
                        'title'     => esc_html_x('Body Boxed Background', 'admin-view', 'airi'),
                        'dependency' => array('body_boxed_yes', '==', 'true'),
                    ),
                    array(
                        'id'        => 'text_color',
                        'default'   => Airi_Options::get_color_default('text_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Text Color', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'heading_color',
                        'default'   => Airi_Options::get_color_default('heading_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Heading Color', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'primary_color',
                        'default'   => Airi_Options::get_color_default('primary_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Primary Color', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'secondary_color',
                        'default'   => Airi_Options::get_color_default('secondary_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Secondary Color', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'three_color',
                        'default'   => Airi_Options::get_color_default('three_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Three Color', 'admin-view', 'airi')
                    ),
                    array(
                        'id'        => 'border_color',
                        'default'   => Airi_Options::get_color_default('border_color'),
                        'type'      => 'color_picker',
                        'title'     => esc_html_x('Border Color', 'admin-view', 'airi')
                    )
                )
            ),
            array(
                'name'      => 'loading_sections',
                'title'     => esc_html_x('Page Preload', 'admin-view', 'airi'),
                'icon'      => 'fa fa-refresh fa-spin',
                'fields'    => array(
                    array(
                        'id'        => 'page_loading_animation',
                        'type'      => 'radio',
                        'class'     => 'la-radio-style',
                        'default'   => 'off',
                        'title'     => esc_html_x('Page Preload Animation', 'admin-view', 'airi'),
                        'desc'      => esc_html_x('Turn on to show the icon/images loading animation before view site', 'admin-view', 'airi'),
                        'options'   => Airi_Options::get_config_radio_onoff(false)
                    ),
                    array(
                        'id'        => 'page_loading_style',
                        'type'      => 'select',
                        'default'   => '1',
                        'title'     => esc_html_x('Select Preload Style', 'admin-view', 'airi'),
                        'options'   => array(
                            '1'         => esc_html_x('Style 1', 'admin-view', 'airi'),
                            '2'         => esc_html_x('Style 2', 'admin-view', 'airi'),
                            '3'         => esc_html_x('Style 3', 'admin-view', 'airi'),
                            '4'         => esc_html_x('Style 4', 'admin-view', 'airi'),
                            '5'         => esc_html_x('Style 5', 'admin-view', 'airi'),
                            'custom'    => esc_html_x('Custom image', 'admin-view', 'airi')
                        ),
                        'dependency' => array('page_loading_animation_on', '==', 'true'),
                    ),
                    array(
                        'id'        => 'page_loading_custom',
                        'type'      => 'image',
                        'title'     => esc_html_x('Custom Page Loading Image', 'admin-view', 'airi'),
                        'add_title' => esc_html_x('Add Image', 'admin-view', 'airi'),
                        'dependency'=> array('page_loading_animation_on|page_loading_style', '==|==', 'true|custom'),
                    )
                )
            )
        )
    );
    return $sections;
}