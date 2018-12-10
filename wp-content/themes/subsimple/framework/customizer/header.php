<?php
function subsimple_customize_register_header( $wp_customize ) {
	//Settings for Header Image
	$wp_customize->add_panel('subsimple_header_panel', array(
		'priority' => 20,
		'title' => __('Header Settings','subsimple')	
	));
	
	$wp_customize->get_section('header_image')->panel = 'subsimple_header_panel';
	$wp_customize->get_section('title_tagline')->panel = 'subsimple_header_panel';
	
	$wp_customize->add_setting( 'subsimple_himg_style' , array(
	    'default'     => 'cover',
	    'sanitize_callback' => 'subsimple_sanitize_himg_style'
	) );
	
	/* Sanitization Function */
	function subsimple_sanitize_himg_style( $input ) {
		if (in_array( $input, array('contain','cover') ) )
			return $input;
		else
			return 'subsimple';	
	}
	
	$wp_customize->add_control(
	'subsimple_himg_style', array(
		'label' => __('Header Image Arrangement','subsimple'),
		'section' => 'header_image',
		'settings' => 'subsimple_himg_style',
		'type' => 'select',
		'choices' => array(
				'contain' => __('Contain','subsimple'),
				'cover' => __('Cover Completely (Recommended)','subsimple'),
				)
	) );
	
	$wp_customize->add_setting( 'subsimple_himg_align' , array(
	    'default'     => 'center',
	    'sanitize_callback' => 'subsimple_sanitize_himg_align'
	) );
	
	/* Sanitization Function */
	function subsimple_sanitize_himg_align( $input ) {
		if (in_array( $input, array('center','left','right') ) )
			return $input;
		else
			return 'subsimple';	
	}
	
	$wp_customize->add_control(
	'subsimple_himg_align', array(
		'label' => __('Header Image Alignment','subsimple'),
		'section' => 'header_image',
		'settings' => 'subsimple_himg_align',
		'type' => 'select',
		'choices' => array(
				'center' => __('Center','subsimple'),
				'left' => __('Left','subsimple'),
				'right' => __('Right','subsimple'),
			)
	) );
	
	$wp_customize->add_setting( 'subsimple_himg_repeat' , array(
	    'default'     => true,
	    'sanitize_callback' => 'subsimple_sanitize_checkbox'
	) );
	
	$wp_customize->add_control(
	'subsimple_himg_repeat', array(
		'label' => __('Repeat Header Image','subsimple'),
		'section' => 'header_image',
		'settings' => 'subsimple_himg_repeat',
		'type' => 'checkbox',
	) );
}
add_action( 'customize_register', 'subsimple_customize_register_header' );