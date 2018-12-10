<?php
function subsimple_customize_register_fp_cat( $wp_customize ) {
    //FEATURED POSTS
    $wp_customize->add_section(
        'subsimple_featposts_cat',
        array(
            'title'     => __('Featured Posts Categories','subsimple'),
            'priority'  => 5,
            'panel' => 'subsimple_featured_posts'
        )
    );

    $wp_customize->add_setting(
        'subsimple_featposts_cat_enable',
        array( 'sanitize_callback' => 'subsimple_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'subsimple_featposts_cat_enable', array(
            'settings' => 'subsimple_featposts_cat_enable',
            'label'    => __( 'Enable', 'subsimple' ),
            'section'  => 'subsimple_featposts_cat',
            'type'     => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'subsimple_featcat_title',
        array( 'sanitize_callback' => 'sanitize_text_field' )
    );

    $wp_customize->add_control(
        'subsimple_featcat_title', array(
            'settings' => 'subsimple_featcat_title',
            'label'    => __( 'Title', 'subsimple' ),
            'section'  => 'subsimple_featposts_cat',
            'type'     => 'text',
        )
    );

    for( $x = 1; $x <= 3; $x++ ):

    $wp_customize->add_setting(
        'subsimple_featposts_category_'.$x,
        array( 'sanitize_callback' => 'subsimple_sanitize_category' )
    );

    $wp_customize->add_control(
        new SubSimple_WP_Customize_Category_Control(
            $wp_customize,
            'subsimple_featposts_category_'.$x,
            array(
                'label'    => __('Select Featured Category ','subsimple').$x,
                'settings' => 'subsimple_featposts_category_'.$x,
                'section'  => 'subsimple_featposts_cat'
            )
        )
    );

    endfor;
}
add_action( 'customize_register', 'subsimple_customize_register_fp_cat' );