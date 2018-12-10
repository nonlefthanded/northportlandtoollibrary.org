<?php
function subsimple_customize_register_footer_secondary($wp_customize) {
    //Footer Secondary Feature


        $wp_customize->add_section('subsimple_footer_secondary_sec',
            array(
                'title' => __('Secondary Footer', 'subsimple'),
                'priority' => 78,
                'panel' => 'subsimple_design_panel'
            )
        );

    $wp_customize->add_setting('subsimple_secondary_footer_enable',
        array(
            'sanitize_callback' => 'subsimple_sanitize_checkbox'
        )
    );

    $wp_customize->add_control('subsimple_secondary_footer_enable',
        array(
            'setting' => 'subsimple_secondary_footer_enable',
            'section' => 'subsimple_footer_secondary_sec',
            'label' => __('Enable', 'subsimple'),
            'type' => 'checkbox',
        )
    );

    for($x = 1; $x <= 3; $x++ ) :
        $wp_customize->add_setting('subsimple_featured_icon_'.$x,
            array(
                'default' => 'refresh',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control('subsimple_featured_icon_'.$x,
            array(
                'setting' => 'subsimple_featured_icon_'.$x,
                'section' => 'subsimple_footer_secondary_sec',
                'label' => __('Featured Icon ', 'subsimple').$x,
                'description' => __('You need to add fontawesome code to add a Featured Icon to display. Example: refresh', 'subsimple'),
                'type' => 'text',
            )
        );

        $wp_customize->add_setting('subsimple_select_page_'.$x,
            array(
                'sanitize_callback' => 'absint'
            )
        );

        $wp_customize->add_control('subsimple_select_page_'.$x,
            array(
                'setting' => 'subsimple_select_page_'.$x,
                'section' => 'subsimple_footer_secondary_sec',
                'label' => __('Page ', 'subsimple').$x,
                'description' => __('Select a Page to display Details', 'subsimple').$x,
                'type' => 'dropdown-pages',
            )
        );
    endfor;
}
add_action('customize_register', 'subsimple_customize_register_footer_secondary');