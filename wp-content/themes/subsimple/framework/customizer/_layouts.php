<?php
function subsimple_customize_register_layouts( $wp_customize ) {
	// Layout and Design
	$wp_customize->add_panel( 'subsimple_design_panel', array(
	    'priority'       => 40,
	    'capability'     => 'edit_theme_options',
	    'title'          => __('Design & Layout','subsimple'),
	) );
	
	$wp_customize->add_section(
	    'subsimple_design_options',
	    array(
	        'title'     => __('Blog Layout','subsimple'),
	        'priority'  => 0,
	        'panel'     => 'subsimple_design_panel'
	    )
	);
	
	
	$wp_customize->add_setting(
		'subsimple_blog_layout',
		array( 'sanitize_callback' => 'subsimple_sanitize_blog_layout', 'default' => 'subsimple' )
	);
	
	function subsimple_sanitize_blog_layout( $input ) {
		if ( in_array($input, array('grid','grid_2_column','grid_3_column','subsimple') ) )
			return $input;
		else 
			return 'subsimple';	
	}
	
	$wp_customize->add_control(
		'subsimple_blog_layout',array(
				'label' => __('Select Layout','subsimple'),
				'description' => __('This is the Layout for your Recent Posts Page, Categories & Archives Pages. The Front Page of your site would use a Separate Layout.','subsimple'),
				'settings' => 'subsimple_blog_layout',
				'section'  => 'subsimple_design_options',
				'type' => 'select',
				'choices' => array(
						'subsimple' => __('Default Theme Layout','subsimple'),
						'grid' => __('Basic Blog Layout','subsimple'),
						'grid_2_column' => __('Grid - 2 Column','subsimple'),
						'grid_3_column' => __('Grid - 3 Column','subsimple'),
						
					)
			)
	);
	
	$wp_customize->add_section(
	    'subsimple_sidebar_options',
	    array(
	        'title'     => __('Sidebar Layout','subsimple'),
	        'priority'  => 0,
	        'panel'     => 'subsimple_design_panel'
	    )
	);
	
	$wp_customize->add_setting(
		'subsimple_disable_sidebar',
		array( 'sanitize_callback' => 'subsimple_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'subsimple_disable_sidebar', array(
		    'settings' => 'subsimple_disable_sidebar',
		    'label'    => __( 'Disable Sidebar Everywhere.','subsimple' ),
		    'section'  => 'subsimple_sidebar_options',
		    'type'     => 'checkbox',
		    'default'  => false
		)
	);
	
	$wp_customize->add_setting(
		'subsimple_disable_sidebar_home',
		array( 'sanitize_callback' => 'subsimple_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'subsimple_disable_sidebar_home', array(
		    'settings' => 'subsimple_disable_sidebar_home',
		    'label'    => __( 'Disable Sidebar on Home/Blog.','subsimple' ),
		    'section'  => 'subsimple_sidebar_options',
		    'type'     => 'checkbox',
		    'active_callback' => 'subsimple_show_sidebar_options',
		    'default'  => false
		)
	);
	
	$wp_customize->add_setting(
		'subsimple_disable_sidebar_front',
		array( 'sanitize_callback' => 'subsimple_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'subsimple_disable_sidebar_front', array(
		    'settings' => 'subsimple_disable_sidebar_front',
		    'label'    => __( 'Disable Sidebar on Front Page.','subsimple' ),
		    'section'  => 'subsimple_sidebar_options',
		    'type'     => 'checkbox',
		    'active_callback' => 'subsimple_show_sidebar_options',
		    'default'  => false
		)
	);
	
	
	$wp_customize->add_setting(
		'subsimple_sidebar_width',
		array(
			'default' => 4,
		    'sanitize_callback' => 'subsimple_sanitize_positive_number' )
	);
	
	$wp_customize->add_control(
			'subsimple_sidebar_width', array(
		    'settings' => 'subsimple_sidebar_width',
		    'label'    => __( 'Sidebar Width','subsimple' ),
		    'description' => __('Min: 25%, Default: 33%, Max: 40%','subsimple'),
		    'section'  => 'subsimple_sidebar_options',
		    'type'     => 'range',
		    'active_callback' => 'subsimple_show_sidebar_options',
		    'input_attrs' => array(
		        'min'   => 3,
		        'max'   => 5,
		        'step'  => 1,
		        'class' => 'sidebar-width-range',
		        'style' => 'color: #0a0',
		    ),
		)
	);
	
	/* Active Callback Function */
	function subsimple_show_sidebar_options($control) {
	   
	    $option = $control->manager->get_setting('subsimple_disable_sidebar');
	    return $option->value() == false ;
	    
	}
	
	function subsimple_sanitize_text( $input ) {
	    return wp_kses_post( force_balance_tags( $input ) );
	}
	
	$wp_customize-> add_section(
    'subsimple_custom_footer',
    array(
    	'title'			=> __('Custom Footer Text','subsimple'),
    	'description'	=> __('Enter your Own Copyright Text.','subsimple'),
    	'priority'		=> 80,
    	'panel'			=> 'subsimple_design_panel'
    	)
    );
    
	$wp_customize->add_setting(
	'subsimple_footer_text',
	array(
		'default'		=> 'subsimple',
		'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	
	$wp_customize->add_control(	 
	       'subsimple_footer_text',
	        array(
	            'section' => 'subsimple_custom_footer',
	            'settings' => 'subsimple_footer_text',
	            'type' => 'text'
	        )
	);
}
add_action( 'customize_register', 'subsimple_customize_register_layouts' );