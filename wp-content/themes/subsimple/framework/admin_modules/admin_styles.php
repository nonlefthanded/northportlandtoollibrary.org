<?php
/**
 * Enqueue Scripts for Admin
 */
function subsimple_custom_wp_admin_style() {
        wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css' );
        wp_enqueue_style( '-admin_css', get_template_directory_uri() . '/assets/css/admin.css' );
}
add_action( 'customize_controls_print_styles', 'subsimple_custom_wp_admin_style' );