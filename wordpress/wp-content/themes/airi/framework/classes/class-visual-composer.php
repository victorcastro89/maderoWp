<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Airi_Visual_Composer{

    protected $category;

    public function __construct(){
        if(!class_exists('Vc_Manager')) return false;
        $this->load_hooks();
    }

    private function load_hooks(){
        $this->category = esc_html_x( 'La Studio', 'admin-view', 'airi');
        add_action( 'vc_before_init', array( $this, 'vc_before_init') );
        add_action( 'vc_after_init', array( $this, 'vc_after_init') );
        add_filter( 'vc_tta_container_classes', array( $this, 'vc_tta_container_classes'), 12, 2 );
        add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG , array( $this, 'modify_shortcode_css_class_output' ), 12, 3 );
    }

    public function vc_before_init(){
        //vc_automapper()->setDisabled( true );
        //vc_manager()->disableUpdater( true );
        vc_manager()->setIsAsTheme( true );
        if(class_exists( 'WooCommerce' )){
            remove_action( 'wp_enqueue_scripts', 'vc_woocommerce_add_to_cart_script' );
        }
        add_filter('vc_map_get_param_defaults', array( $this, 'change_css_animation_value' ), 10, 2);
    }

    public function vc_after_init(){
        $this->overrideVcSection();
        $this->overrideProgressBar();
        $this->overridePieChart();
        $this->overrideTtaAccordion();
        $this->overrideTtaTabs();
        $this->overrideTtaTour();
        $this->overrideTtaSection();
        $this->overrideVcColumn();
        $this->overrideVcColumnInner();

        if( function_exists('vc_set_default_editor_post_types') ){
            $list = array(
                'page',
                'post',
                'la_block',
                'la_portfolio',
                'la_team_member',
                'lpm_playlist',
                'ld_release',
                'la_video'
            );
            vc_set_default_editor_post_types( $list );
        }
    }

    public function change_css_animation_value($value, $param){
        if( 'css_animation' ==  $param['param_name'] && 'none' == $value){
            $value = '';
        }
        return $value;
    }

    public function modify_shortcode_css_class_output($css_classes, $shortcode_name, $atts){
        if ( $shortcode_name == 'vc_progress_bar' ){
            if( isset($atts['display_type']) ){
                $css_classes .= ' vc_progress_bar_' . esc_attr($atts['display_type']);
            }
        }
        if ( $shortcode_name == 'vc_tta_tabs' || $shortcode_name == 'vc_tta_accordion' || $shortcode_name == 'vc_tta_tour' ){
            if( isset($atts['style']) && strpos($atts['style'], 'la-') !== false ){
                $css_classes = preg_replace('/ vc_tta-(o|shape|spacing|gap|color)[0-9a-zA-Z\_\-]+/','',$css_classes);
                if($shortcode_name == 'vc_tta_tabs'){
                    $css_classes .= ' vc_tta-o-no-fill';
                    $css_classes = str_replace('vc_tta-style-la-','tab--',$css_classes);
                    $css_classes = str_replace('vc_general ','',$css_classes);
                }
                if($shortcode_name == 'vc_tta_tour'){
                    $css_classes = str_replace('vc_tta-style-la-','tour--',$css_classes);
                    $css_classes = str_replace('vc_general ','',$css_classes);
                }
                if($shortcode_name == 'vc_tta_accordion'){
                    $css_classes = str_replace('vc_tta-style-la-','accordion--',$css_classes);
                }
            }
        }
        if($shortcode_name == 'vc_btn'){
            if(!empty($atts['style']) && in_array($atts['style'], array('modern', 'outline', 'custom', 'outline-custom'))){
                if( false !== strpos( $css_classes, 'vc_btn3-container')){
                    $css_classes .= ' la-vc-btn';
                }
            }
        }

        if ( $shortcode_name == 'vc_row' ) {
            $css_classes .= ' la_fp_slide la_fp_child_section';
        }

        return $css_classes;
    }

    public function vc_tta_container_classes($classes, $atts){
        if(isset($atts['style']) && strpos($atts['style'],'la-') !== false){
            if(isset($atts['alignment'])){
                $classes[] = 'tta--align-' . $atts['alignment'];
                $classes[] = 'la__tta';
            }
            else{
                $classes[] = 'la__ttaac';
            }
            $classes[] = 'tta--' . str_replace('la-', '', $atts['style']);
        }
        return $classes;
    }

    private function overrideProgressBar(){
        vc_map_update( 'vc_progress_bar', array(
            'category' => $this->category,
            'params' => array(
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Values', 'airi' ),
                    'param_name' => 'values',
                    'description' => __( 'Enter values for graph - value, title and color.', 'airi' ),
                    'value' => urlencode( json_encode( array(
                        array(
                            'label' => __( 'Development', 'airi' ),
                            'value' => '90',
                        ),
                        array(
                            'label' => __( 'Design', 'airi' ),
                            'value' => '80',
                        ),
                        array(
                            'label' => __( 'Marketing', 'airi' ),
                            'value' => '70',
                        ),
                    ) ) ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Label', 'airi' ),
                            'param_name' => 'label',
                            'description' => __( 'Enter text used as title of bar.', 'airi' ),
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Value', 'airi' ),
                            'param_name' => 'value',
                            'description' => __( 'Enter value of bar.', 'airi' ),
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Color', 'airi' ),
                            'param_name' => 'color',
                            'value' => array(
                                    __( 'Default', 'airi' ) => '',
                                ) + array(
                                    __( 'Classic Grey', 'airi' ) => 'bar_grey',
                                    __( 'Classic Blue', 'airi' ) => 'bar_blue',
                                    __( 'Classic Turquoise', 'airi' ) => 'bar_turquoise',
                                    __( 'Classic Green', 'airi' ) => 'bar_green',
                                    __( 'Classic Orange', 'airi' ) => 'bar_orange',
                                    __( 'Classic Red', 'airi' ) => 'bar_red',
                                    __( 'Classic Black', 'airi' ) => 'bar_black',
                                ) + getVcShared( 'colors-dashed' ) + array(
                                    __( 'Gradient', 'airi' ) => 'gradient',
                                    __( 'Custom Color', 'airi' ) => 'custom'
                                ),
                            'description' => __( 'Select single bar background color.', 'airi' ),
                            'admin_label' => true,
                            'param_holder_class' => 'vc_colored-dropdown',
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => __( 'Custom color', 'airi' ),
                            'param_name' => 'customcolor',
                            'description' => __( 'Select custom single bar background color.', 'airi' ),
                            'dependency' => array(
                                'element' => 'color',
                                'value' => array( 'custom' ),
                            ),
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => __( 'Custom text color', 'airi' ),
                            'param_name' => 'customtxtcolor',
                            'description' => __( 'Select custom single bar text color.', 'airi' ),
                            'dependency' => array(
                                'element' => 'color',
                                'value' => array( 'custom' ),
                            )
                        ),
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Units', 'airi' ),
                    'param_name' => 'units',
                    'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Color', 'airi' ),
                    'param_name' => 'bgcolor',
                    'value' => array(
                            __( 'Classic Grey', 'airi' ) => 'bar_grey',
                            __( 'Classic Blue', 'airi' ) => 'bar_blue',
                            __( 'Classic Turquoise', 'airi' ) => 'bar_turquoise',
                            __( 'Classic Green', 'airi' ) => 'bar_green',
                            __( 'Classic Orange', 'airi' ) => 'bar_orange',
                            __( 'Classic Red', 'airi' ) => 'bar_red',
                            __( 'Classic Black', 'airi' ) => 'bar_black',
                        ) + getVcShared( 'colors-dashed' ) + array(
                            __( 'Gradient', 'airi' ) => 'gradient',
                            __( 'Custom Color', 'airi' ) => 'custom'
                        ),
                    'description' => __( 'Select bar background color.', 'airi' ),
                    'admin_label' => true,
                    'param_holder_class' => 'vc_colored-dropdown',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Bar custom background color', 'airi' ),
                    'param_name' => 'custombgcolor',
                    'description' => __( 'Select custom background color for bars.', 'airi' ),
                    'dependency' => array(
                        'element' => 'bgcolor',
                        'value' => array( 'custom' ),
                    ),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Bar custom text color', 'airi' ),
                    'param_name' => 'customtxtcolor',
                    'description' => __( 'Select custom text color for bars.', 'airi' ),
                    'dependency' => array(
                        'element' => 'bgcolor',
                        'value' => array( 'custom' ),
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Options', 'airi' ),
                    'param_name' => 'options',
                    'value' => array(
                        __( 'Add stripes', 'airi' ) => 'striped',
                        __( 'Add animation (Note: visible only with striped bar).', 'airi' ) => 'animated',
                    ),
                ),
                vc_map_add_css_animation(),
                array(
                    'type' => 'el_id',
                    'heading' => __( 'Element ID', 'airi' ),
                    'param_name' => 'el_id'
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'airi' ),
                    'param_name' => 'el_class',
                    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'airi' ),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'CSS box', 'airi' ),
                    'param_name' => 'css',
                    'group' => __( 'Design Options', 'airi' ),
                ),
            )
        ));
    }

    private function overridePieChart(){
        $shortcode_tag = 'vc_pie';
        $shortcode_object = vc_get_shortcode($shortcode_tag);
        $shortcode_params = $shortcode_object['params'];

        $shortcode_params = array(
            array(
                'type' => 'dropdown',
                'param_name' => 'style',
                'value' => array(
                    esc_html_x( 'Style 01', 'admin-view', 'airi' ) => '1',
                    esc_html_x( 'Style 02', 'admin-view', 'airi' ) => '2',
                ),
                'default'   => '1',
                'heading' => esc_html_x( 'Style', 'admin-view', 'airi' ),
                'description' => esc_html_x( 'Select display style.', 'admin-view', 'airi' )
            )
        ) + $shortcode_params ;

        vc_map_update( $shortcode_tag , array(
            'category' => $this->category,
            'params'   => $shortcode_params
        ));
    }

    private function overrideTtaAccordion(){
        vc_map_update('vc_tta_accordion' , array(
            'category' => $this->category,
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'param_name' => 'style',
                    'value' => array(
                        esc_html_x( 'Airi 01', 'admin-view', 'airi' ) => 'la-1',
                        esc_html_x( 'Airi 02', 'admin-view', 'airi' ) => 'la-2',
                        esc_html_x( 'Airi 03', 'admin-view', 'airi' ) => 'la-3',
                        esc_html_x( 'Airi 04', 'admin-view', 'airi' ) => 'la-4',
                        esc_html_x( 'Classic', 'admin-view', 'airi' ) => 'classic',
                        esc_html_x( 'Modern', 'admin-view', 'airi' ) => 'modern',
                        esc_html_x( 'Flat', 'admin-view', 'airi' ) => 'flat',
                        esc_html_x( 'Outline', 'admin-view', 'airi' ) => 'outline',
                    ),
                    'heading' => esc_html_x( 'Style', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion display style.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'shape',
                    'value' => array(
                        esc_html_x( 'Rounded', 'admin-view', 'airi' ) => 'rounded',
                        esc_html_x( 'Square', 'admin-view', 'airi' ) => 'square',
                        esc_html_x( 'Round', 'admin-view', 'airi' ) => 'round',
                    ),
                    'heading' => esc_html_x( 'Shape', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion shape.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'color',
                    'value' => getVcShared( 'colors-dashed' ),
                    'std' => 'grey',
                    'heading' => esc_html_x( 'Color', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion color.', 'admin-view', 'airi' ),
                    'param_holder_class' => 'vc_colored-dropdown',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'param_name' => 'no_fill',
                    'heading' => esc_html_x( 'Do not fill content area?', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Do not fill content area with color.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'spacing',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        '1px' => '1',
                        '2px' => '2',
                        '3px' => '3',
                        '4px' => '4',
                        '5px' => '5',
                        '10px' => '10',
                        '15px' => '15',
                        '20px' => '20',
                        '25px' => '25',
                        '30px' => '30',
                        '35px' => '35',
                    ),
                    'heading' => esc_html_x( 'Spacing', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion spacing.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'gap',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        '1px' => '1',
                        '2px' => '2',
                        '3px' => '3',
                        '4px' => '4',
                        '5px' => '5',
                        '10px' => '10',
                        '15px' => '15',
                        '20px' => '20',
                        '25px' => '25',
                        '30px' => '30',
                        '35px' => '35',
                    ),
                    'heading' => esc_html_x( 'Gap', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion gap.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'c_align',
                    'value' => array(
                        esc_html_x( 'Left', 'admin-view', 'airi' ) => 'left',
                        esc_html_x( 'Right', 'admin-view', 'airi' ) => 'right',
                        esc_html_x( 'Center', 'admin-view', 'airi' ) => 'center',
                    ),
                    'heading' => esc_html_x( 'Alignment', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion section title alignment.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'autoplay',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => 'none',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '10' => '10',
                        '20' => '20',
                        '30' => '30',
                        '40' => '40',
                        '50' => '50',
                        '60' => '60',
                    ),
                    'std' => 'none',
                    'heading' => esc_html_x( 'Autoplay', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select auto rotate for accordion in seconds (Note: disabled by default).', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'param_name' => 'collapsible_all',
                    'heading' => esc_html_x( 'Allow collapse all?', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Allow collapse all accordion sections.', 'admin-view', 'airi' ),
                ),
                // Control Icons
                array(
                    'type' => 'dropdown',
                    'param_name' => 'c_icon',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        esc_html_x( 'Chevron', 'admin-view', 'airi' ) => 'chevron',
                        esc_html_x( 'Plus', 'admin-view', 'airi' ) => 'plus',
                        esc_html_x( 'Triangle', 'admin-view', 'airi' ) => 'triangle',
                    ),
                    'std' => 'plus',
                    'heading' => esc_html_x( 'Icon', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion navigation icon.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'c_position',
                    'value' => array(
                        esc_html_x( 'Left', 'admin-view', 'airi' ) => 'left',
                        esc_html_x( 'Right', 'admin-view', 'airi' ) => 'right',
                    ),
                    'dependency' => array(
                        'element' => 'c_icon',
                        'not_empty' => true,
                    ),
                    'heading' => esc_html_x( 'Position', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select accordion navigation icon position.', 'admin-view', 'airi' ),
                ),
                // Control Icons END
                array(
                    'type' => 'textfield',
                    'param_name' => 'active_section',
                    'heading' => esc_html_x( 'Active section', 'admin-view', 'airi' ),
                    'value' => 1,
                    'description' => esc_html_x( 'Enter active section number (Note: to have all sections closed on initial load enter non-existing number).', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html_x( 'Extra class name', 'admin-view', 'airi' ),
                    'param_name' => 'el_class',
                    'description' => esc_html_x( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'admin-view', 'airi' ),
                ),
            )
        ));
    }

    private function overrideTtaTabs(){
        vc_map_update( 'vc_tta_tabs', array(
            'category' => $this->category,
            'params' => array(
                array(
                    'type' => 'textfield',
                    'param_name' => 'title',
                    'heading' => _x( 'Widget title', 'admin-view', 'airi' ),
                    'description' => _x( 'Enter text used as widget title (Note: located above content element).', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'style',
                    'value' => array(
                        esc_html_x( 'Airi 01', 'admin-view', 'airi' ) => 'la-1',
                        esc_html_x( 'Airi 02', 'admin-view', 'airi' ) => 'la-2',
                        esc_html_x( 'Airi 03', 'admin-view', 'airi' ) => 'la-3',
                        esc_html_x( 'Airi 04', 'admin-view', 'airi' ) => 'la-4',
                        esc_html_x( 'Airi 05', 'admin-view', 'airi' ) => 'la-5',
                        esc_html_x( 'Airi 06', 'admin-view', 'airi' ) => 'la-6',
                        esc_html_x( 'Classic', 'admin-view', 'airi' ) => 'classic',
                        esc_html_x( 'Modern', 'admin-view', 'airi' ) => 'modern',
                        esc_html_x( 'Flat', 'admin-view', 'airi' ) => 'flat',
                        esc_html_x( 'Outline', 'admin-view', 'airi' ) => 'outline',
                    ),
                    'heading' => esc_html_x( 'Style', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs display style.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'shape',
                    'value' => array(
                        esc_html_x( 'Rounded', 'admin-view', 'airi' ) => 'rounded',
                        esc_html_x( 'Square', 'admin-view', 'airi' ) => 'square',
                        esc_html_x( 'Round', 'admin-view', 'airi' ) => 'round',
                    ),
                    'heading' => esc_html_x( 'Shape', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs shape.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'color',
                    'heading' => esc_html_x( 'Color', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs color.', 'admin-view', 'airi' ),
                    'value' => getVcShared( 'colors-dashed' ),
                    'std' => 'grey',
                    'param_holder_class' => 'vc_colored-dropdown',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),

                array(
                    'type' => 'checkbox',
                    'param_name' => 'no_fill_content_area',
                    'heading' => esc_html_x( 'Do not fill content area?', 'admin-view', 'airi' ),
                    'std' => 'true',
                    'description' => esc_html_x( 'Do not fill content area with color.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'spacing',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        '1px' => '1',
                        '2px' => '2',
                        '3px' => '3',
                        '4px' => '4',
                        '5px' => '5',
                        '10px' => '10',
                        '15px' => '15',
                        '20px' => '20',
                        '25px' => '25',
                        '30px' => '30',
                        '35px' => '35',
                    ),
                    'heading' => esc_html_x( 'Spacing', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs spacing.', 'admin-view', 'airi' ),
                    'std' => '',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'gap',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        '1px' => '1',
                        '2px' => '2',
                        '3px' => '3',
                        '4px' => '4',
                        '5px' => '5',
                        '10px' => '10',
                        '15px' => '15',
                        '20px' => '20',
                        '25px' => '25',
                        '30px' => '30',
                        '35px' => '35',
                    ),
                    'heading' => esc_html_x( 'Gap', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs gap.', 'admin-view', 'airi' ),
                    'std' => '',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'tab_position',
                    'value' => array(
                        esc_html_x( 'Top', 'admin-view', 'airi' ) => 'top',
                        esc_html_x( 'Bottom', 'admin-view', 'airi' ) => 'bottom',
                    ),
                    'heading' => esc_html_x( 'Position', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs navigation position.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'alignment',
                    'value' => array(
                        esc_html_x( 'Left', 'admin-view', 'airi' ) => 'left',
                        esc_html_x( 'Right', 'admin-view', 'airi' ) => 'right',
                        esc_html_x( 'Center', 'admin-view', 'airi' ) => 'center',
                    ),
                    'heading' => esc_html_x( 'Alignment', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs section title alignment.', 'admin-view', 'airi' ),
                    'std' => 'center',
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'autoplay',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => 'none',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '10' => '10',
                        '20' => '20',
                        '30' => '30',
                        '40' => '40',
                        '50' => '50',
                        '60' => '60',
                    ),
                    'std' => 'none',
                    'heading' => esc_html_x( 'Autoplay', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select auto rotate for tabs in seconds (Note: disabled by default).', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'param_name' => 'active_section',
                    'heading' => esc_html_x( 'Active section', 'admin-view', 'airi' ),
                    'value' => 1,
                    'description' => esc_html_x( 'Enter active section number (Note: to have all sections closed on initial load enter non-existing number).', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'pagination_style',
                    'value' => array(
                        esc_html_x( 'None', 'admin-view', 'airi' ) => '',
                        esc_html_x( 'Square Dots', 'admin-view', 'airi' ) => 'outline-square',
                        esc_html_x( 'Radio Dots', 'admin-view', 'airi' ) => 'outline-round',
                        esc_html_x( 'Point Dots', 'admin-view', 'airi' ) => 'flat-round',
                        esc_html_x( 'Fill Square Dots', 'admin-view', 'airi' ) => 'flat-square',
                        esc_html_x( 'Rounded Fill Square Dots', 'admin-view', 'airi' ) => 'flat-rounded',
                    ),
                    'heading' => esc_html_x( 'Pagination style', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select pagination style.', 'admin-view', 'airi' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('classic','modern','flat','outline')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'pagination_color',
                    'value' => getVcShared( 'colors-dashed' ),
                    'heading' => esc_html_x( 'Pagination color', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select pagination color.', 'admin-view', 'airi' ),
                    'param_holder_class' => 'vc_colored-dropdown',
                    'std' => 'grey',
                    'dependency' => array(
                        'element' => 'pagination_style',
                        'not_empty' => true,
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html_x( 'Extra class name', 'admin-view', 'airi' ),
                    'param_name' => 'el_class',
                    'description' => esc_html_x( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => esc_html_x( 'CSS box', 'admin-view', 'airi' ),
                    'param_name' => 'css',
                    'group' => esc_html_x( 'Design Options', 'admin-view', 'airi' ),
                ),
            )
        ));
    }

    private function overrideTtaSection(){
        $shortcode_tag = 'vc_tta_section';
        $shortcode_object = vc_get_shortcode($shortcode_tag);
        $shortcode_params = $shortcode_object['params'];
        $i_type_idx = self::getParamIndex($shortcode_params,'i_type');
        $el_class_idx = self::getParamIndex($shortcode_params,'el_class');
        if($i_type_idx !== -1 && $el_class_idx !== -1){
            $el_class = $shortcode_params[$el_class_idx];
            $shortcode_params[$i_type_idx]['value'][esc_html_x('LaStudio Icon Outline', 'admin-view', 'airi')] = 'la_icon_outline';
            $shortcode_params[$el_class_idx] = array (
                'type' => 'iconpicker',
                'heading' => _x( 'Icon', 'admin-view', 'airi' ),
                'param_name' => 'i_icon_la_icon_outline',
                'value' => 'la-icon design-2_image',
                'settings' => array(
                    'emptyIcon' => false,
                    'type' => 'la_icon_outline',
                    'iconsPerPage' => 50,
                ),
                'dependency' => array(
                    'element' => 'i_type',
                    'value' => 'la_icon_outline',
                )
            );
            $shortcode_params[] = $el_class;
            vc_map_update($shortcode_tag , array(
                'params' => $shortcode_params
            ));
        }
    }

    private function overrideTtaTour(){
        vc_map_update( 'vc_tta_tour', array(
            'category' => $this->category,
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'param_name' => 'style',
                    'value' => array(
                        esc_html_x( 'Airi 01', 'admin-view', 'airi' ) => 'la-1',
                    ),
                    'heading' => esc_html_x( 'Style', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs display style.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'tab_position',
                    'value' => array(
                        esc_html_x( 'Left', 'admin-view', 'airi' ) => 'left',
                        esc_html_x( 'Right', 'admin-view', 'airi' ) => 'right',
                    ),
                    'heading' => esc_html_x( 'Position', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tour navigation position.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'alignment',
                    'value' => array(
                        esc_html_x( 'Left', 'admin-view', 'airi' ) => 'left',
                        esc_html_x( 'Right', 'admin-view', 'airi' ) => 'right',
                        esc_html_x( 'Center', 'admin-view', 'airi' ) => 'center',
                    ),
                    'heading' => esc_html_x( 'Alignment', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tabs section title alignment.', 'admin-view', 'airi' ),
                    'std' => 'center',
                ),
                array(
                    'type' => 'hidden',
                    'param_name' => 'autoplay',
                    'std' => 'none',
                ),
                array(
                    'type' => 'dropdown',
                    'param_name' => 'controls_size',
                    'value' => array(
                        esc_html_x( 'Auto', 'admin-view', 'airi' ) => '',
                        esc_html_x( 'Extra large', 'admin-view', 'airi' ) => 'xl',
                        esc_html_x( 'Large', 'admin-view', 'airi' ) => 'lg',
                        esc_html_x( 'Medium', 'admin-view', 'airi' ) => 'md',
                        esc_html_x( 'Small', 'admin-view', 'airi' ) => 'sm',
                        esc_html_x( 'Extra small', 'admin-view', 'airi' ) => 'xs',
                    ),
                    'heading' => esc_html_x( 'Navigation width', 'admin-view', 'airi' ),
                    'description' => esc_html_x( 'Select tour navigation width.', 'admin-view', 'airi' ),
                ),

                array(
                    'type' => 'textfield',
                    'param_name' => 'active_section',
                    'heading' => esc_html_x( 'Active section', 'admin-view', 'airi' ),
                    'value' => 1,
                    'description' => esc_html_x( 'Enter active section number (Note: to have all sections closed on initial load enter non-existing number).', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html_x( 'Extra class name', 'admin-view', 'airi' ),
                    'param_name' => 'el_class',
                    'description' => esc_html_x( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'admin-view', 'airi' ),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => esc_html_x( 'CSS box', 'admin-view', 'airi' ),
                    'param_name' => 'css',
                    'group' => esc_html_x( 'Design Options', 'admin-view', 'airi' ),
                ),
            )
        ));
    }

    private function overrideVcSection(){
        vc_add_params('vc_section', array(
            array(
                'type' => 'dropdown',
                'heading' => _x('Section Behaviour', 'admin-view', 'airi'),
                'param_name' => 'fp_auto_height',
                'admin_label' => true,
                'value' => array(
                    _x('Full Height', 'admin-view', 'airi') => 'off',
                    _x('Auto Height', 'admin-view', 'airi') => 'on',
                    _x('Responsive Auto Height', 'admin-view', 'airi') => 'responsive',
                    _x('Top Fixed Header', 'admin-view', 'airi') => 'fixed_top',
                    _x('Bottom Fixed Footer', 'admin-view', 'airi') => 'fixed_bottom',
                ),
                'description' => _x('Select section row behaviour.', 'admin-view', 'airi'),
                'group' => esc_html_x('One Page Settings', 'admin-view', 'airi'),
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => _x('Anchor', 'admin-view', 'airi'),
                'param_name' => 'fp_anchor',
                'admin_label'   => true,
                'value' => '',
                'description' => _x('Enter an anchor (ID).', 'admin-view', 'airi'),
                'dependency' => array('element' => 'fp_auto_height', 'value' => array('off', 'on', 'responsive')),
                'group' => esc_html_x('One Page Settings', 'admin-view', 'airi'),
            ),
            array(
                'type' => 'textfield',
                'class' => '',
                'heading' => _x('Tooltip', 'admin-view', 'airi'),
                'param_name' => 'fp_tooltip',
                'dependency' => array('element' => 'fp_auto_height', 'value' => array('off', 'on', 'responsive')),
                'value' => '',
                'group' => esc_html_x('One Page Settings', 'admin-view', 'airi'),
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'heading' => _x('Rows as Slides', 'admin-view', 'airi'),
                'param_name' => 'fp_column_slide',
                'dependency' => array('element' => 'fp_auto_height', 'value' => array('off', 'on', 'responsive')),
                'value' => '',
                'group' => esc_html_x('One Page Settings', 'admin-view', 'airi'),
                'description' => _x('Enable if you want to show each row in this section as slides.', 'admin-view', 'airi'),
            ),
            array(
                'type' => 'checkbox',
                'class' => '',
                'heading' => _x('No Scrollbars', 'admin-view', 'airi'),
                'param_name' => 'fp_no_scrollbar',
                'dependency' => array('element' => 'fp_auto_height', 'value' => array('off', 'on', 'responsive')),
                'value' => '',
                'group' => esc_html_x('One Page Settings', 'admin-view', 'airi'),
                'description' => _x('Enable if scrolloverflow is enabled but you do not want to show scrollbars for this section.', 'admin-view', 'airi'),
            )
        ));
    }


    private function overrideVcColumn(){
        $shortcode_name = 'vc_column';
        $shortcode_objects = vc_get_shortcode($shortcode_name);
        $shortcode_params = $shortcode_objects['params'];

        $f_css_animation_idx = self::getParamIndex($shortcode_params,'css_animation');
        if($f_css_animation_idx){

            unset($shortcode_params[$f_css_animation_idx]);

            $shortcode_params[] = array(
                'type' => 'animation_style',
                'heading' => __( 'CSS Animation', 'airi' ),
                'param_name' => 'la_animation_name',
                'value' => 'none',
                'settings' => array(
                    'type' => array(
                        'in',
                        'other',
                        'infinite'
                    ),
                ),
                'admin_label' => true,
                'description'   => __( 'Select initial loading animation for element.', 'airi' ),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Duration', 'airi'),
                'param_name' => 'la_animation_duration',
                'value' => 1,
                'min' => 0.1,
                'max' => 100,
                'suffix' => 's',
                'description' => __('How long the animation effect should last. Decides the speed of effect.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Delay', 'airi'),
                'param_name' => 'la_animation_delay',
                'value' => 0,
                'min' => 0.1,
                'max' => 100,
                'suffix' => 's',
                'description' => __('Delays the animation effect for seconds you enter above.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Repeat Count', 'airi'),
                'param_name' => 'la_animation_iteration_count',
                'value' => 1,
                'min' => 0,
                'max' => 100,
                'suffix' => '',
                'description' => __('The animation effect will repeat to the count you enter above. Enter 0 if you want to repeat it infinitely.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'dropdown',
                'heading' => __('Hide elements until delay','airi'),
                'description' => __('If set to yes, the elements inside block will stay hidden until animation starts (depends on delay settings above).', 'airi'),
                'param_name' => 'la_opacity',
                'value' => array(
                    __('Yes','airi') => 'yes',
                    __('No','airi') => 'no',
                ),
                'std' => 'yes',
                'admin_label' => true,
                'group'         => __( 'Animation', 'airi' )
            );

            vc_map_update($shortcode_name , array(
                'params' => $shortcode_params
            ));
        }

    }

    private function overrideVcColumnInner(){
        $shortcode_name = 'vc_column_inner';
        $shortcode_objects = vc_get_shortcode($shortcode_name);
        $shortcode_params = $shortcode_objects['params'];

        $f_css_animation_idx = self::getParamIndex($shortcode_params,'css_animation');
        if($f_css_animation_idx){

            unset($shortcode_params[$f_css_animation_idx]);

            $shortcode_params[] = array(
                'type' => 'animation_style',
                'heading' => __( 'CSS Animation', 'airi' ),
                'param_name' => 'la_animation_name',
                'value' => 'none',
                'settings' => array(
                    'type' => array(
                        'in',
                        'other',
                        'infinite'
                    ),
                ),
                'admin_label' => true,
                'description'   => __( 'Select initial loading animation for element.', 'airi' ),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Duration', 'airi'),
                'param_name' => 'la_animation_duration',
                'value' => 1,
                'min' => 0.1,
                'max' => 100,
                'suffix' => 's',
                'description' => __('How long the animation effect should last. Decides the speed of effect.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Delay', 'airi'),
                'param_name' => 'la_animation_delay',
                'value' => 0,
                'min' => 0.1,
                'max' => 100,
                'suffix' => 's',
                'description' => __('Delays the animation effect for seconds you enter above.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'la_number',
                'heading' => __('Animation Repeat Count', 'airi'),
                'param_name' => 'la_animation_iteration_count',
                'value' => 1,
                'min' => 0,
                'max' => 100,
                'suffix' => '',
                'description' => __('The animation effect will repeat to the count you enter above. Enter 0 if you want to repeat it infinitely.', 'airi'),
                'group'         => __( 'Animation', 'airi' )
            );

            $shortcode_params[] = array(
                'type' => 'dropdown',
                'heading' => __('Hide elements until delay','airi'),
                'description' => __('If set to yes, the elements inside block will stay hidden until animation starts (depends on delay settings above).', 'airi'),
                'param_name' => 'la_opacity',
                'value' => array(
                    __('Yes','airi') => 'yes',
                    __('No','airi') => 'no',
                ),
                'std' => 'yes',
                'admin_label' => true,
                'group'         => __( 'Animation', 'airi' )
            );

            vc_map_update($shortcode_name , array(
                'params' => $shortcode_params
            ));
        }
    }

    public static function getParamIndex($array, $attr){
        foreach ($array as $index => $entry) {
            if ($entry['param_name'] == $attr) {
                return $index;
            }
        }
        return -1;
    }

}