<?php if ( ! defined( 'ABSPATH' ) ) { die; }

if(!class_exists('Airi_MegaMenu_Walker')){

    class Airi_MegaMenu_Walker extends Walker_Nav_Menu {

        public $custom_block = null;

        // add popup class to ul sub-menus
        public function start_lvl( &$output, $depth = 0, $args = array() ) {

            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
            $submenu_custom_style = '';
            $out_div = '';
            if($depth == 0){
                $popup_custom_style = isset( $args->popup_custom_style ) ? ' style="' . esc_attr( $args->popup_custom_style ) . '"' : '';
                $out_div = '<div class="popup"><div class="inner" ' . $popup_custom_style . '>';
                $args->popup_custom_style = '';
            }
            elseif($depth == 1){
                $submenu_custom_style = isset( $args->popup_custom_style ) ? ' style="' . esc_attr( $args->popup_custom_style ) . '"' : '';
                $args->popup_custom_style = '';
            }
            $output .= "{$n}{$indent}{$out_div}<ul class=\"sub-menu\"{$submenu_custom_style}>{$n}";
        }

        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );

            if($depth == 0){
                $out_div = '</div></div>';
            }
            else{
                $out_div = '';
            }
            if( $depth == 1 && !empty( $this->custom_block )){
                $out_div .= '<div class="mm-menu-block menu-block-after">'.do_shortcode('[la_block id="'.esc_attr($this->custom_block).'"]').'</div>';
                $this->custom_block = null;
            }
            $output .= "{$indent}</ul>{$out_div}{$n}";
        }

        // add main/sub classes to li's and links
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            $classes = empty( $item->classes ) ? array() : (array)$item->classes;
            $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

            $classes[] = 'mm-item';

            if($this->has_children){
                $classes[] = 'mm-item-has-sub';
            }
            if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
                $classes[] = 'active';
            }

            if ($depth == 0) {
                $popup_custom_styles = '';
                if ($item->menu_type == "wide") {
                    if ($item->popup_column == ""){
                        $item->popup_column = 4;
                    }
                    $classes[] = "mm-popup-wide";
                    $classes[] = "mm-popup-column-{$item->popup_column}";

                    if(isset($item->popup_background)){
                        $popup_background = shortcode_atts(array(
                            'image' => '',
                            'repeat' => 'repeat',
                            'position' => 'left top',
                            'attachment' => 'scroll',
                            'size' => '',
                            'color' => ''
                        ),$item->popup_background);
                        $popup_custom_styles .= Airi_Helper::render_background_atts($popup_background, false);
                    }
                    if(isset($item->popup_max_width) && !empty($item->popup_max_width)){
                        $popup_custom_styles .= 'max-width:' . absint($item->popup_max_width) . 'px;';
                        $classes[] = "mm-popup-max-width";
                    }
                    if( $item->force_full_width ){
                        $classes[] = "mm-popup-force-fullwidth";
                    }
                }
                else {
                    $classes[] = "mm-popup-narrow";
                }
                if(isset($item->custom_style) && !empty($item->custom_style)){
                    $popup_custom_styles .= $item->custom_style;
                }
                $popup_custom_styles = str_replace('"', '\'', $popup_custom_styles);

                $args->popup_custom_style = $this->compress_text($popup_custom_styles);
            }
            if ($depth == 1) {
                $popup_custom_styles = '';
                if ( isset( $item->popup_background ) ) {
                    if(isset($item->popup_background)){
                        $popup_background = shortcode_atts(array(
                            'image' => '',
                            'repeat' => 'repeat',
                            'position' => 'left top',
                            'attachment' => 'scroll',
                            'size' => '',
                            'color' => ''
                        ),$item->popup_background);
                        $popup_custom_styles .= Airi_Helper::render_background_atts($popup_background, false);
                    }
                }
                if ( isset( $item->custom_style ) && !empty( $item->custom_style ) ) {
                    $popup_custom_styles .= $item->custom_style;
                }
                $popup_custom_styles = str_replace( '"', '\'', $popup_custom_styles );
                /** waiting for options behind */
                $args->popup_custom_style = $this->compress_text($popup_custom_styles);

                if ( $item->block || $item->block2) {
                    $classes[] = 'mm-menu-custom-block';
                }
                if($item->block){
                    $classes[] = 'mm-menu-custom-block-before';
                }
                if ( $item->block2 ) {
                    $classes[] = 'mm-menu-custom-block-after';
                    $this->custom_block = $item->block2;
                }
            }
            $classes[] = "mm-item-level-{$depth}";
            if ( $item->hide ) {
                $classes[] = "mm-item-hide";
            }
            if ( $item->nolink ) {
                $classes[] = "mm-item-nolink";
                $item->url = 'javascript:;';
                $item->target = '_self';
            }
            if($depth > 0 && $this->has_children){
                $classes[] = 'submenu-position-' . ( isset($item->submenu_position) && !empty($item->submenu_position) ? $item->submenu_position : 'left' );
            }
            $classes[] = 'menu-item-' .$item->ID;

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

            if($depth == 1) {
                $output .= $indent . '<li class="' . esc_attr( $class_names ) . '" data-column="'.esc_attr( $item->item_column ? $item->item_column : 1).'">';
            }
            else{
                $output .= $indent . '<li  class="' . esc_attr( $class_names ) . '">';
            }

            // link attributes
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before . ($item->icon ? '<i class="mm-icon '.esc_attr($item->icon).'"></i>' : '') ;
            if($depth != 0 || ( $depth == 0 && !$item->only_icon )){
                $title = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
                $item_output .= $title;
            }
            $item_output .= $args->link_after;
            if($depth == 0 && !empty($args->show_menu_item_description) && $args->show_menu_item_description && !empty($item->description)){
                $item_output .= '<span class="mm-desc">'.esc_html($item->description).'</span>';
            }
            if ($item->tip_label) {
                $tip_style = '';
                $tip_arrow_style = '';
                if ($item->tip_color) {
                    $tip_style .= 'color:'.$item->tip_color.';';
                }
                if ($item->tip_background_color) {
                    $tip_style .= 'background:'.$item->tip_background_color.';';
                    $tip_arrow_style .= 'color:'.$item->tip_background_color.';';
                }
                $item_output .= '<span class="tip" style="'.esc_attr( $tip_style ).'"><span class="tip-arrow" style="'.esc_attr( $tip_arrow_style ).'"></span>'. esc_html( $item->tip_label ) .'</span>';
            }
            $item_output .= '</a>';

            $item_output .= $args->after;
            if ($item->block){
                $item_output .= '<div class="mm-menu-block menu-block-before">'.do_shortcode('[la_block id="'.esc_attr($item->block).'"]').'</div>';
            }
            // build html
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        protected function compress_text($content){
            return $content;
        }

        public static function fallback( $args ){
            ?><ul class="main-menu mega-menu default-menu"><?php
            echo str_replace( array("page_item","<ul class='children'>"), array("page_item menu-item","<ul class='sub-menu'>"), wp_list_pages('echo=0&title_li=') );
            ?></ul><?php
        }
    }
}