<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$header_layout = Airi()->layout()->get_header_layout();

$header_access_icon = Airi()->settings()->get('header_access_icon_1');

$show_header_top        = Airi()->settings()->get('enable_header_top');
$header_top_elements    = Airi()->settings()->get('header_top_elements');
$custom_header_top_html = Airi()->settings()->get('use_custom_header_top');

$aside_sidebar_name = apply_filters('airi/filter/aside_widget_bottom', 'aside-widget');
$header_aside_sidebar_name = apply_filters('airi/filter/header_sidebar_widget_bottom', 'header-sidebar-bottom');

$enable_header_aside = false;

$aside_compoment = array();

if(!empty($header_access_icon)){
    foreach($header_access_icon as $component){
        if(isset($component['type']) && $component['type'] == 'aside_header'){
            $enable_header_aside = true;
            $aside_compoment = $component;
            break;
        }
    }
}

?>

<?php if($show_header_top == 'custom' && !empty($custom_header_top_html) ): ?>
<header id="masthead" class="site-header">
    <div class="site-header-top use-custom-html">
        <div class="container">
            <?php echo Airi_Helper::remove_js_autop($custom_header_top_html); ?>
        </div>
    </div>
</header>
<?php endif; ?>
<?php if($show_header_top == 'yes' && !empty($header_top_elements) ): ?>
<header id="masthead" class="site-header">
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
</header>
<?php endif; ?>
<header id="masthead_aside" class="header--aside">
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
                    <?php if(!empty($aside_compoment)): ?>
                        <div class="header-component-inner">
                            <?php echo Airi_Helper::render_access_component($aside_compoment['type'], $aside_compoment, 'header_component'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($header_access_icon)): ?>
                    <div class="header-component-outer header-middle">
                        <div class="header-component-inner"><?php
                                foreach($header_access_icon as $component){
                                    if(isset($component['type'])){
                                        if($component['type'] == 'aside_header'){
                                            continue;
                                        }
                                        echo Airi_Helper::render_access_component($component['type'], $component, 'header_component');
                                    }
                                }
                            ?></div>
                    </div>
                <?php endif; ?>
                <div class="header-component-outer header-right">
                    <nav class="header-aside-nav menu--vertical menu--vertical-<?php echo is_rtl() ? 'right' : 'left' ?> clearfix">
                        <div class="nav-inner" data-container="#masthead_aside">
                            <?php Airi()->layout()->render_main_nav(array(
                                'menu_class'    => 'main-menu mega-menu isVerticalMenu'
                            ));?>
                        </div>
                    </nav>
                </div>
                <?php if(is_active_sidebar($header_aside_sidebar_name)): ?>
                <div class="clearfix"></div>
                <div class="header-component-outer header-bottom">
                    <div class="header-widget-bottom">
                        <?php
                        dynamic_sidebar($header_aside_sidebar_name);
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
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
