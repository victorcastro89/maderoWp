<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

add_action('admin_menu', 'la_admin_init_menu_import' );

function la_admin_init_menu_import(){
    add_submenu_page(
        'tools.php',
        esc_html__('Demo Importer', 'la-studio'),
        esc_html__('Demo Importer', 'la-studio'),
        'manage_options',
        'la_importer',
        'la_admin_import_panel'
    );
}

function la_admin_import_panel(){
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Demo Importer', 'la-studio') ?></h1>
        <div class="la_demo_importer_panel">
            <?php
                echo la_fw_add_element(
                    array(
                        'id' => 'demo_importer',
                        'type' => 'la_demo_importer',
                        'theme_name' => 'airi',
                        'demo' => apply_filters('airi/filter/demo_data', array())
                    )
                );
            ?>
        </div>
        <style type="text/css">
            .la_demo_importer_panel .cs-field-la_demo_importer {
                padding: 0;
                border: none;
                background: none;
            }
        </style>
    </div>
<?php
}