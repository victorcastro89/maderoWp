<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_airi_get_demo_array($dir_url, $dir_path){

    $demo_items = array(
        array(
            'image'     => 'images/demo-01.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-01/',
            'title'     => 'Main Demo 01',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-02.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-02/',
            'title'     => 'Main Demo 02',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-03.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-03/',
            'title'     => 'Main Demo 03',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-04.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-04/',
            'title'     => 'Main Demo 04',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-05.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-05/',
            'title'     => 'Main Demo 05',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-06.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-06/',
            'title'     => 'Main Demo 06',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-07.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-07/',
            'title'     => 'Main Demo 07',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-08.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-08/',
            'title'     => 'Main Demo 08',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-09.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-09/',
            'title'     => 'Main Demo 09',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-10.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-10/',
            'title'     => 'Main Demo 10',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-11.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-11/',
            'title'     => 'Main Demo 11',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-12.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-12/',
            'title'     => 'Main Demo 12',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-13.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-13/',
            'title'     => 'Main Demo 13',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-14.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-14/',
            'title'     => 'Main Demo 14',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-15.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-15/',
            'title'     => 'Main Demo 15',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-16.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-16/',
            'title'     => 'Main Demo 16',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-17.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-17/',
            'title'     => 'Main Demo 17',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-18.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-18/',
            'title'     => 'Main Demo 18',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-19.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-19/',
            'title'     => 'Main Demo 19',
            'category'  => 'Shop'
        ),
        array(
            'image'     => 'images/demo-20.jpg',
            'link'      => 'https://airi.la-studioweb.com/home-20/',
            'title'     => 'Main Demo 20',
            'category'  => 'Shop'
        )
    );

    $default_image_setting = array(
        'woocommerce_single_image_width' => 1000,
        'woocommerce_thumbnail_image_width' => 500,
        'woocommerce_thumbnail_cropping' => 'custom',
        'woocommerce_thumbnail_cropping_custom_width' => 8,
        'woocommerce_thumbnail_cropping_custom_height' => 10,
        'thumbnail_size_w' => 0,
        'thumbnail_size_h' => 0,
        'medium_size_w' => 0,
        'medium_size_h' => 0,
        'medium_large_size_w' => 0,
        'medium_large_size_h' => 0,
        'large_size_w' => 0,
        'large_size_h' => 0
    );

    $default_menu = array(
        'main-nav'              => 'Primary Navigation',
        'mobile-nav'            => 'Primary Navigation',
        'aside-nav'             => 'Primary Navigation'
    );

    $default_page = array(
        'page_for_posts' 	            => 'Blog',
        'woocommerce_shop_page_id'      => 'Shop Pages',
        'woocommerce_cart_page_id'      => 'Cart',
        'woocommerce_checkout_page_id'  => 'Checkout',
        'woocommerce_myaccount_page_id' => 'My Account'
    );

    $slider = $dir_path . 'Slider/';
    $content = $dir_path . 'Content/';
    $widget = $dir_path . 'Widget/';
    $setting = $dir_path . 'Setting/';
    $preview = $dir_url;


    $demo_data = array();

    for( $i = 1; $i <= 20; $i ++ ){
        $id = $i;
        if( $i < 10 ) {
            $id = '0'. $i;
        }

        $demo_item_name = !empty($demo_items[$i - 1]['title']) ? $demo_items[$i - 1]['title'] : 'Demo ' . $id;

        $value = array();
        $value['title']             = $demo_item_name;
        $value['category']          = 'demo';
        $value['demo_preset']       = 'home-' . $id;
        $value['demo_url']          = 'https://airi.la-studioweb.com/home-' . $id . '/';
        $value['preview']           = $preview  .   'demo-' . $id . '.jpg';
        $value['option']            = $setting  .   'home-' . $id . '.json';
        $value['content']           = $content  .   'data-sample.xml';
        $value['widget']            = $widget   .   'widget-' . $id . '.json';

        $value['pages']             = array_merge(
            $default_page,
            array(
                'page_on_front' => 'Home ' . $id
            )
        );

        $value['menu-locations']    = array_merge(
            $default_menu,
            array(

            )
        );
        $value['other_setting']    = array_merge(
            $default_image_setting,
            array(

            )
        );

        if(!in_array($i, array(8,10,16,18))){
            $value['slider']            = $slider   .   'home-'. $id .'.zip';
        }

        $demo_data['home-'. $id] = $value;
    }

    return $demo_data;
}