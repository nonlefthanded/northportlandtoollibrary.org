<?php
function subsimple_customize_register_fp( $wp_customize ) {
	//FEATURED POSTS
    $wp_customize->add_panel(
        'subsimple_featured_posts',
        array(
            'title' => __('Featured Posts', 'subsimple'),
            'priority' => 35,
        )
    );

	$wp_customize->add_section(
	    'subsimple_featposts',
	    array(
	        'title'     => __('Featured Posts Boxes','subsimple'),
	        'priority'  => 3,
            'panel' => 'subsimple_featured_posts'
	    )
	);
	
	$wp_customize->add_setting(
		'subsimple_featposts_enable',
		array( 'sanitize_callback' => 'subsimple_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'subsimple_featposts_enable', array(
		    'settings' => 'subsimple_featposts_enable',
		    'label'    => __( 'Enable', 'subsimple' ),
		    'section'  => 'subsimple_featposts',
		    'type'     => 'checkbox',
		)
	);

    $wp_customize->add_setting(
        'subsimple_featposts_title',
        array( 'sanitize_callback' => 'sanitize_text_field' )
    );

    $wp_customize->add_control(
        'subsimple_featposts_title', array(
            'settings' => 'subsimple_featposts_title',
            'label'    => __( 'Title', 'subsimple' ),
            'section'  => 'subsimple_featposts',
            'type'     => 'text',
        )
    );

    $wp_customize->add_setting(
		    'subsimple_featposts_cat',
		    array( 'sanitize_callback' => 'subsimple_sanitize_category' )
		);
	
		
	$wp_customize->add_control(
	    new SubSimple_WP_Customize_Category_Control(
	        $wp_customize,
	        'subsimple_beta_cat',
	        array(
	            'label'    => __('Category For Featured Posts','subsimple'),
	            'settings' => 'subsimple_featposts_cat',
	            'section'  => 'subsimple_featposts'
	        )
	    )
	);
}
add_action( 'customize_register', 'subsimple_customize_register_fp' );