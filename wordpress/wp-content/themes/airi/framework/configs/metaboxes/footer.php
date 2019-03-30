<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


/**
 * MetaBox
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function airi_metaboxes_section_footer( $sections )
{
    $sections['footer'] = array(
        'name'      => 'footer',
        'title'     => esc_html_x('Footer', 'admin-view', 'airi'),
        'icon'      => 'laicon-footer',
        'fields'    => array(
            array(
                'id'            => 'hide_footer',
                'type'          => 'radio',
                'default'       => 'no',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('Hide Footer', 'admin-view', 'airi'),
                'options'       => Airi_Options::get_config_radio_opts(false)
            ),

            array(
                'id'            => 'footer_layout',
                'type'          => 'select',
                'class'         => 'chosen',
                'title'         => esc_html_x('Footer Layout', 'admin-view', 'airi'),
                'desc'          => esc_html_x('Controls the layout of the footer.', 'admin-view', 'airi'),
                'default'       => 'inherit',
                'options'       => Airi_Options::get_config_footer_layout_opts(false, true),
                'dependency'    => array( 'hide_footer_no', '==', 'true' )
            ),
            array(
                'id'            => 'footer_full_width',
                'type'          => 'radio',
                'default'       => 'inherit',
                'class'         => 'la-radio-style',
                'title'         => esc_html_x('100% Footer Width', 'admin-view', 'airi'),
                'desc'          => esc_html_x('Turn on to have the footer area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'airi'),
                'options'       => Airi_Options::get_config_radio_opts(),
                'dependency'    => array( 'hide_footer_no', '==', 'true' )
            )
        )
    );
    return $sections;
}