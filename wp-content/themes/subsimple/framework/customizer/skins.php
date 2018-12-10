<?php
function subsimple_customize_register_skins( $wp_customize ) {
    $wp_customize->get_section('colors')->title = __('Theme Skin & Colors','subsimple');
    $wp_customize->get_section('colors')->panel = "subsimple_design_panel";
	$wp_customize->get_section('background_image')->panel = "subsimple_design_panel";
	$wp_customize->get_section('custom_css')->panel = "subsimple_design_panel";
	//Replace Header Text Color with, separate colors for Title and Description
	$wp_customize->get_control('header_textcolor')->label = __('Site Title Color','subsimple');
	$wp_customize->add_setting('subsimple_header_desccolor', array(
	    'default'     => '#000',
	    'sanitize_callback' => 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(new WP_Customize_Color_Control( 
		$wp_customize, 
		'subsimple_header_desccolor', array(
			'label' => __('Site Tagline Color','subsimple'),
			'section' => 'colors',
			'settings' => 'subsimple_header_desccolor',
			'type' => 'color'
		) ) 
	);

    $wp_customize->add_setting(
        'subsimple_skin',
        array(
            'default'=> 'default',
            'sanitize_callback' => 'subsimple_sanitize_skin'
        )
    );

    $skins = array( 'default' => __('Default(subsimple)','subsimple'),
        'orange' =>  __('Orange','subsimple'),
        'green' => __('Green','subsimple'),
        'brown' => __('Brown', 'subsimple'),
    );

    $wp_customize->add_control(
        'subsimple_skin',array(
            'settings' => 'subsimple_skin',
            'section'  => 'colors',
            'label' => __('Choose Skin','subsimple'),
            'description' => __('Free Version of subsimple Supports 3 Different Skin Colors.','subsimple'),
            'type' => 'select',
            'choices' => $skins,
        )
    );

    function subsimple_sanitize_skin( $input ) {
        if ( in_array($input, array('default','orange','green', 'brown') ) )
            return $input;
        else
            return '';
    }

}
add_action( 'customize_register', 'subsimple_customize_register_skins' );