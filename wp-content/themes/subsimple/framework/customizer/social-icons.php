<?php
function subsimple_customize_register_social( $wp_customize ) {
		// Social Icons
	$wp_customize->add_section('subsimple_social_section', array(
			'title' => __('Social Icons','subsimple'),
			'priority' => 44 ,
			'panel' => 'subsimple_header_panel'
	));
	
	$social_networks = array( //Redefinied in Sanitization Function.
					'none' => __('-','subsimple'),
					'facebook' => __('Facebook','subsimple'),
					'twitter' => __('Twitter','subsimple'),
					'google-plus' => __('Google Plus','subsimple'),
					'instagram' => __('Instagram','subsimple'),
					'rss' => __('RSS Feeds','subsimple'),
					'vine' => __('Vine','subsimple'),
					'vimeo-square' => __('Vimeo','subsimple'),
					'youtube' => __('Youtube','subsimple'),
					'flickr' => __('Flickr','subsimple'),
				);


    $social_count = count($social_networks);
				
	for ($x = 1 ; $x <= ($social_count - 3) ; $x++) :
			
		$wp_customize->add_setting(
			'subsimple_social_'.$x, array(
				'sanitize_callback' => 'subsimple_sanitize_social',
				'default' => 'none'
			));

		$wp_customize->add_control( 'subsimple_social_'.$x, array(
					'settings' => 'subsimple_social_'.$x,
					'label' => __('Icon ','subsimple').$x,
					'section' => 'subsimple_social_section',
					'type' => 'select',
					'choices' => $social_networks,			
		));
		
		$wp_customize->add_setting(
			'subsimple_social_url'.$x, array(
				'sanitize_callback' => 'esc_url_raw'
			));

		$wp_customize->add_control( 'subsimple_social_url'.$x, array(
					'settings' => 'subsimple_social_url'.$x,
					'description' => __('Icon ','subsimple').$x.__(' Url','subsimple'),
					'section' => 'subsimple_social_section',
					'type' => 'url',
					'choices' => $social_networks,			
		));
		
	endfor;
	
	function subsimple_sanitize_social( $input ) {
		$social_networks = array(
					'none' ,
					'facebook',
					'twitter',
					'google-plus',
					'instagram',
					'rss',
					'vine',
					'vimeo-square',
					'youtube',
					'flickr'
				);
		if ( in_array($input, $social_networks) )
			return $input;
		else
			return 'subsimple';	
	}
}
add_action( 'customize_register', 'subsimple_customize_register_social' );