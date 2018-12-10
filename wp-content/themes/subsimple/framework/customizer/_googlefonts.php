<?php
function subsimple_customize_register_fonts( $wp_customize ) {
		$wp_customize->add_section(
	    'subsimple_typo_options',
	    array(
	        'title'     => __('Google Web Fonts','subsimple'),
	        'panel' => 'subsimple_design_panel',
	        'priority'  => 41,
	    )
	);
	
	$font_array = array('HIND','Khula','Open Sans','Droid Sans','Droid Serif','Roboto','Roboto Condensed','Lato','Bree Serif','Oswald','Slabo','Lora','Source Sans Pro','Arimo','Bitter','Noto Sans','Squada One','Nunito Sans');
	$fonts = array_combine($font_array, $font_array);
	
	$wp_customize->add_setting(
		'subsimple_title_font',
		array(
			'default'=> 'HIND',
			'sanitize_callback' => 'subsimple_sanitize_gfont' 
			)
	);
	
	function subsimple_sanitize_gfont( $input ) {
		if ( in_array($input, array('HIND','Khula','Open Sans','Droid Sans','Droid Serif','Roboto','Roboto Condensed','Lato','Bree Serif','Oswald','Slabo','Lora','Source Sans Pro','Arimo','Bitter','Noto Sans','Squada One','Nunito Sans') ) )
			return $input;
		else
			return 'subsimple';	
	}
	
	$wp_customize->add_control(
		'subsimple_title_font',array(
				'label' => __('Title','subsimple'),
				'settings' => 'subsimple_title_font',
				'section'  => 'subsimple_typo_options',
				'type' => 'select',
				'choices' => $fonts,
			)
	);
	
	$wp_customize->add_setting(
		'subsimple_body_font',
			array(	'default'=> 'Open Sans',
					'sanitize_callback' => 'subsimple_sanitize_gfont' )
	);
	
	$wp_customize->add_control(
		'subsimple_body_font',array(
				'label' => __('Body','subsimple'),
				'settings' => 'subsimple_body_font',
				'section'  => 'subsimple_typo_options',
				'type' => 'select',
				'choices' => $fonts
			)
	);

}
add_action( 'customize_register', 'subsimple_customize_register_fonts' );