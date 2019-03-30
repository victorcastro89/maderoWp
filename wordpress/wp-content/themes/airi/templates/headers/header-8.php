<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$header_layout = Airi()->layout()->get_header_layout();

$header_access_icon = Airi()->settings()->get('header_access_icon_1');

$show_header_top        = Airi()->settings()->get('enable_header_top');
$header_top_elements    = Airi()->settings()->get('header_top_elements');
$custom_header_top_html = Airi()->settings()->get('use_custom_header_top');

$enable_searchbox_header = Airi()->settings()->get('enable_searchbox_header');

$aside_sidebar_name = apply_filters('airi/filter/aside_widget_bottom', 'aside-widget');

$enable_header_aside = false;

if(!empty($header_access_icon)){
    foreach($header_access_icon as $component){
        if(isset($component['type']) && $component['type'] == 'aside_header'){
            $enable_header_aside = true;
            break;
        }
    }
}

?>

<header id="masthead" class="site-header">
    <?php if($show_header_top == 'custom' && !empty($custom_header_top_html) ): ?>
        <div class="site-header-top use-custom-html">
            <div class="container">
                <?php echo Airi_Helper::remove_js_autop($custom_header_top_html); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($show_header_top == 'yes' && !empty($header_top_elements) ): ?>
        <div class="site-header-top use-default">
            <div class="container">
                <div class="header-top-elements">
                    <?php
                    foreach($header_top_elements as $component){
                        if(isset($component['type'])){
                            echo Airi_Helper::render_access_component($component['type'], $component, 'header_component');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="site-header-outer">
        <div class="site-header-inner">
            <div class="container">
                <div class="header-main clearfix">
                    <div class="header-component-outer header-left">
                        <div class="site-branding">
                            <a href="<?php echo esc_url( home_url( '/'  ) ); ?>" rel="home">
                                <figure class="logo--normal"><?php Airi()->layout()->render_logo();?></figure>
                                <figure class="logo--transparency"><?php Airi()->layout()->render_transparency_logo();?></figure>
                            </a>
                        </div>
                    </div>
                    <?php if(airi_string_to_bool($enable_searchbox_header)):?>
                    <div class="header-component-outer header-middle">
                        <div class="header-component-inner clearfix">
                            <div class="searchform-wrapper">
                                <?php
                                    get_template_part('templates/search/form');
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="header-component-outer header-right">
                        <div class="header-component-inner clearfix">
                            <?php
                            if(!empty($header_access_icon)){
                                foreach($header_access_icon as $component){
                                    if(isset($component['type'])){
                                        echo Airi_Helper::render_access_component($component['type'], $component, 'header_component');
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-header__nav site-header__nav-primary">
                <div class="container">
                    <div class="header-main clearfix">
                        <?php if(has_nav_menu('shop-category-nav')):?>
                        <nav class="site-category-nav menu--vertical menu--vertical-<?php echo is_rtl() ? 'right' : 'left' ?>">
                            <span class="toggle-category-menu"><?php echo esc_html_x('Shop by Categories', 'front-view', 'airi'); ?></span>
                            <div class="nav-inner" data-container="#masthead .site-header__nav-primary .site-category-nav .nav-inner" data-parent-container="#masthead .site-header__nav-primary .header-main">
                            <?php
                            wp_nav_menu(array(
                                'container'     => false,
                                'menu_class'    => 'site-category-menu mega-menu isVerticalMenu',
                                'theme_location'=> 'shop-category-nav',
                                'link_before'   => '<span class="mm-text">',
                                'link_after'    => '</span>',
                                'fallback_cb'   => array( 'Airi_MegaMenu_Walker', 'fallback' ),
                                'walker'        => new Airi_MegaMenu_Walker
                            ))
                            ?>
                            </div>
                        </nav>
                        <?php endif; ?>
                        <nav class="site-main-nav clearfix<?php echo has_nav_menu('shop-category-nav') ? '': ' size-full'?>" data-container="#masthead .site-header__nav-primary .header-main">
                            <?php Airi()->layout()->render_main_nav();?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="la-header-sticky-height"></div>
    </div>
</header>
<!-- #masthead -->
<?php if($enable_header_aside): ?>
    <aside id="header_aside" class="header--aside">
        <div class="header-aside-wrapper">
            <a class="btn-aside-toggle" href="#"><i class="dl-icon-close"></i></a>
            <div class="header-aside-inner">
                <?php if(is_active_sidebar($aside_sidebar_name)): ?>
                    <div class="header-widget-bottom">
                        <?php
                        dynamic_sidebar($aside_sidebar_name);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </aside>
<?php endif;?>