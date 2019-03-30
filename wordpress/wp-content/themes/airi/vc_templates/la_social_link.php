<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$style = $el_class = $enable_custom_items = $social_items = '';

extract(shortcode_atts(array(
    'style' => 'default',
    'enable_custom_items' => '',
    'social_items' => '',
    'el_class' => ''
), $atts ));



$css_class = implode(' ', array(
    'social-media-link',
    'style-' . $style
))  . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );

if( airi_string_to_bool( $enable_custom_items ) && function_exists('vc_icon_element_fonts_enqueue')) {
    vc_icon_element_fonts_enqueue( 'icon_fontawesome' );
}


if(airi_string_to_bool( $enable_custom_items )){
    $decode_items = urldecode($social_items);
    $social_items = json_decode($decode_items,true);
    if($decode_items == '[{}]'){
        $social_items = array();
    }
    if(!empty($social_items)){
        $social_links = array();
        foreach($social_items as $item){
            $social_links[] = array(
                'title' => $item['title'],
                'icon' => $item['icon_fontawesome'],
                'link' => $item['link']
            );
        }
    }
    else{
        $social_links = Airi()->settings()->get('social_links', array());
    }
}
else{
    $social_links = Airi()->settings()->get('social_links', array());
}


if(!empty($social_links)){
    echo '<div class="'.esc_attr($css_class).'">';
    foreach($social_links as $item){
        if(!empty($item['link']) && !empty($item['icon'])){
            $title = isset($item['title']) ? $item['title'] : '';
            printf(
                '<a href="%1$s" class="%2$s" title="%3$s" target="_blank" rel="nofollow"><i class="%4$s"></i></a>',
                esc_url($item['link']),
                esc_attr(sanitize_title($title)),
                esc_attr($title),
                esc_attr($item['icon'])
            );
        }
    }
    echo '</div>';
}