<?php
function subsimple_customize_regster_menu_alignement($wp_customize) {
    $menu_alignment = array (
        'default' => __('Default', 'subsimple'),
        'left' => __('Left', 'subsimple'),
        'right' =>  __('Right', 'subsimple')
    );

    $wp_customize->add_section('subsimple_menu_alignment_section', array(
            'title' => __('Menu Alignment', 'subsimple'),
            'priority' => 10,
            'panel' => 'nav_menus',
        )
    );

    $wp_customize->add_setting('subsimple_menu_alignment_set', array(
            'default' => 'default',
            'sanitize_callback' => 'subsimple_sanitize_menu_alignment',
        )
    );

    $wp_customize->add_control('subsimple_menu_alignment_set', array(
            'setting' => 'subsimple_menu_alignment_set',
            'section' => 'subsimple_menu_alignment_section',
            'label' => __('Select An Option', 'subsimple'),
            'description' => __('Default Aligned: Center', 'subsimple'),
            'type' => 'select',
            'choices' => $menu_alignment,
        )
    );

    function subsimple_sanitize_menu_alignment($input) {
        $menu_alignment = array(
            'default',
            'left',
            'right',
        );
        if ( in_array($input, $menu_alignment))
            return $input;
        else
            return '';
    }
}
add_action('customize_register', 'subsimple_customize_regster_menu_alignement');