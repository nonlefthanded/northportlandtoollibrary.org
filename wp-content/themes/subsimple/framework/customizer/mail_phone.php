<?php
function subsimple_customize_register_header_mail( $wp_customize ) {
	
	$wp_customize->add_section('subsimple_mail', array(
		'title' => __('Email & Phone','subsimple'),
		'panel' => 'subsimple_header_panel'	
	));
	
	$wp_customize->add_setting( 'subsimple_mailid' , array(
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_control(
	'subsimple_mailid', array(
		'label' => __('Your Email','subsimple'),
		'section' => 'subsimple_mail',
		'settings' => 'subsimple_mailid',
		'type' => 'text',
	) );
	
	$wp_customize->add_setting( 'subsimple_phone' , array(
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_control(
	'subsimple_phone', array(
		'label' => __('Your Phone No.','subsimple'),
		'section' => 'subsimple_mail',
		'settings' => 'subsimple_phone',
		'type' => 'text',
	) );
	
}
add_action( 'customize_register', 'subsimple_customize_register_header_mail' );