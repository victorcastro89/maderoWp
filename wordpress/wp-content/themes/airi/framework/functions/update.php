<?php

if(!function_exists('airi_override_yikes_mailchimp_page_data')){
    function airi_override_yikes_mailchimp_page_data($page_data, $form_id){
        $new_data = new stdClass();
        if(isset($page_data->ID)){
            $new_data->ID = $page_data->ID;
        }
        return $new_data;
    }
    add_filter('yikes-mailchimp-page-data', 'airi_override_yikes_mailchimp_page_data', 10, 2);
}

if(!function_exists('airi_override_theme_default')){
    function airi_override_theme_default(){
        $header_layout = Airi()->layout()->get_header_layout();
        $title_layout = Airi()->layout()->get_page_title_bar_layout();
        if($header_layout == 'default' && (empty($title_layout) || $title_layout == 'hide') && !is_404()) :
            ?>
            <div class="page-title-section">
                <?php
                echo Airi()->breadcrumbs()->get_title();
                do_action('airi/action/breadcrumbs/render_html');
                ?>
            </div>
            <?php
        endif;
    }
    add_action('airi/action/before_render_main_inner', 'airi_override_theme_default');
}

if(!function_exists('airi_override_dokan_main_query')){
    function airi_override_dokan_main_query( $query ) {
        if(function_exists('dokan_is_store_page') && dokan_is_store_page() && isset($query->query['term_section'])){
            $query->set('posts_per_page', 0);
            WC()->query->product_query( $query );
        }
    }
    add_action('pre_get_posts', 'airi_override_dokan_main_query', 11);
}


if(!function_exists('airi_dokan_dashboard_wrap_before')){
    function airi_dokan_dashboard_wrap_before(){
        echo '<div id="main" class="site-main"><div class="container"><div class="row"><main id="site-content" class="col-md-12 col-xs-12 site-content">';
    }
    add_filter('dokan_dashboard_wrap_before', 'airi_dokan_dashboard_wrap_before');
}

if(!function_exists('airi_dokan_dashboard_wrap_after')){
    function airi_dokan_dashboard_wrap_after(){
        echo '</main></div></div></div>';
    }
    add_filter('dokan_dashboard_wrap_after', 'airi_dokan_dashboard_wrap_after');
}


/**
 * @desc: adding the custom badges to product
 * @since: 1.0.3
 */

if(!function_exists('airi_add_custom_badge_for_product')){
    function airi_add_custom_badge_for_product(){
        global $product;
        $product_badges = Airi()->settings()->get_post_meta( $product->get_id(), 'product_badges' );
        if(empty($product_badges)){
            return;
        }
        $_tmp_badges = array();
        foreach($product_badges as $badge){
            if(!empty($badge['text'])){
                $_tmp_badges[] = $badge;
            }
        }
        if(empty($_tmp_badges)){
            return;
        }
        foreach($_tmp_badges as $i => $badge){
            $attribute = array();
            if(!empty($badge['bg'])){
                $attribute[] = 'background-color:' . esc_attr($badge['bg']);
            }
            if(!empty($badge['color'])){
                $attribute[] = 'color:' . esc_attr($badge['color']);
            }
            $el_class = ($i%2==0) ? 'odd' : 'even';
            if(!empty($badge['el_class'])){
                $el_class .= ' ';
                $el_class .= $badge['el_class'];
            }

            echo sprintf(
                '<span class="la-custom-badge %1$s" style="%3$s"><span>%2$s</span></span>',
                esc_attr($el_class),
                esc_html($badge['text']),
                (!empty($attribute) ? esc_attr(implode(';', $attribute)) : '')
            );
        }
    }
    add_action( 'woocommerce_before_shop_loop_item_title', 'airi_add_custom_badge_for_product', 9 );
    add_action( 'woocommerce_before_single_product_summary', 'airi_add_custom_badge_for_product', 9 );
}

/**
 * @desc: kick-off the function when theme has new version
 * @since: 1.0.0
 */
if(!function_exists('airi_hook_update_the_theme')){
    function airi_hook_update_the_theme(){
        $current_version = get_option('airi_opts_db_version', false);
        if( class_exists('LaStudio_Cache_Helper') && version_compare( '1.0.0', $current_version) > 0 ) {
            LaStudio_Cache_Helper::get_transient_version('icon_library', true);
            $current_version = '1.0.0';
            update_option('airi_opts_db_version', $current_version);
        }
    }
    add_action( 'after_setup_theme', 'airi_hook_update_the_theme', 0 );
}

/*
 * @desc: custom block after add-to-cart on single product page
 * @since: 1.0.0
 */
// if(!function_exists('airi_custom_block_after_add_cart_form_on_single_product')){
//     function airi_custom_block_after_add_cart_form_on_single_product(){
//         if(is_active_sidebar('s-p-after-add-cart')){
//             echo '<div class="extradiv-after-frm-cart">';
//             dynamic_sidebar('s-p-after-add-cart');
//             echo '</div>';
//             echo '<div class="clearfix"></div>';
//         }
//     }
//     add_action('woocommerce_single_product_summary', 'airi_custom_block_after_add_cart_form_on_single_product', 52);
// }

if(!function_exists('airi_override_portfolio_content_type_args')){
    function airi_override_portfolio_content_type_args( $args, $post_type_name ) {
        if($post_type_name == 'la_portfolio'){
            $label = esc_html(Airi()->settings()->get('portfolio_custom_name'));
            $label2 = esc_html(Airi()->settings()->get('portfolio_custom_name2'));
            $slug = sanitize_title(Airi()->settings()->get('portfolio_custom_slug'));
            if(!empty($label)){
                $args['label'] = $label;
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
    add_filter('register_post_type_args', 'airi_override_portfolio_content_type_args', 99, 2);
}

if(!function_exists('airi_override_portfolio_tax_type_args')){
    function airi_override_portfolio_tax_type_args( $args, $tax_name ) {

        if( $tax_name == 'la_portfolio_category' ) {
            $label = esc_html(Airi()->settings()->get('portfolio_cat_custom_name'));
            $label2 = esc_html(Airi()->settings()->get('portfolio_cat_custom_name2'));
            $slug = sanitize_title(Airi()->settings()->get('portfolio_cat_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }
        else if( $tax_name == 'la_portfolio_skill' ) {
            $label = esc_html(Airi()->settings()->get('portfolio_skill_custom_name'));
            $label2 = esc_html(Airi()->settings()->get('portfolio_skill_custom_name2'));
            $slug = sanitize_title(Airi()->settings()->get('portfolio_skill_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
    add_filter('register_taxonomy_args', 'airi_override_portfolio_tax_type_args', 99, 2);
}



/*
 * @desc: Ensure that a specific theme is never updated. This works by removing the theme from the list of available updates.
 * @since: 1.0.1
 */

add_filter('http_request_args', 'airi_hidden_theme_update_from_repository', 10, 2);
if(!function_exists('airi_hidden_theme_update_from_repository')){
    function airi_hidden_theme_update_from_repository( $response, $url ){
        $pos = strpos($url, '//api.wordpress.org/themes/update-check');
        if($pos === 5 || $pos === 6){
            $themes = json_decode( $response['body']['themes'], true );
            if(isset($themes['themes']['airi'])){
                unset($themes['themes']['airi']);
            }
            if(isset($themes['themes']['airi-child'])){
                unset($themes['themes']['airi-child']);
            }
            $response['body']['themes'] = json_encode( $themes );
        }
        return $response;
    }
}

