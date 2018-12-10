<?php
/**
 * Simple Catch Customizer/Theme Options
 *
 * @package Catch Themes
 * @subpackage Simple Catch
 * @since Simple Catch 3.0
 */

/**
 * Implements Simple Catch theme options into Theme Customizer.
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Simple Catch 3.0
 */
function simplecatch_customize_register( $wp_customize ) {
	// Get theme options
	$options  = simplecatch_get_options();
	$defaults = simplecatch_defaults_options();

	//Custom Controls
	require trailingslashit( get_template_directory() ) . 'functions/panel/customizer/customizer-custom-controls.php';

	$theme_slug = 'simplecatch_';

	$settings_page_tabs = array(
		'theme_options' => array(
			'id' 			=> 'theme_options',
			'title' 		=> esc_html__( 'Theme Options', 'simple-catch' ),
			'description' 	=> esc_html__( 'Basic theme Options', 'simple-catch' ),
			'sections' 		=> array(
				'responsive_design' => array(
					'id' 			=> 'responsive_design',
					'title' 		=> esc_html__( 'Responsive Design', 'simple-catch' ),
					'description' 	=> '',
				),
				
				'default_layout' => array(
					'id' 			=> 'default_layout',
					'title' 		=> esc_html__( 'Default Layout', 'simple-catch' ),
					'description' 	=> '',
				),
				'homepage_frontpage_settings' => array(
					'id' 			=> 'homepage_frontpage_settings',
					'title' 		=> esc_html__( 'Homepage / Frontpage Category Setting', 'simple-catch' ),
					'description' 	=> '',
				),
				'search_text_settings' => array(
					'id' 			=> 'search_text_settings',
					'title' 		=> esc_html__( 'Search Text Settings', 'simple-catch' ),
					'description' 	=> '',
				),
				'excerpt_more_tag_settings' => array(
					'id' 			=> 'excerpt_more_tag_settings',
					'title' 		=> esc_html__( 'Excerpt / More Tag Settings', 'simple-catch' ),
					'description' 	=> '',
				),
				'custom_css' => array(
					'id' 			=> 'custom_css',
					'title' 		=> esc_html__( 'Custom CSS', 'simple-catch' ),
					'description' 	=> '',
				),
				'scrollup_options' => array(
					'id' 			=> 'scrollup_options',
					'title' 		=> esc_html__( 'Scroll Up', 'simple-catch' ),
					'description' 	=> '',
				)
			),
		),

		'featured_post_slider' => array(
			'id' 			=> 'featured_post_slider',
			'title' 		=> esc_html__( 'Featured Post Slider', 'simple-catch' ),
			'description' 	=> esc_html__( 'Featured Post Slider', 'simple-catch' ),
			'sections' 		=> array(
				'add_slider_options' => array(
					'id' 			=> 'add_slider_options',
					'title' 		=> esc_html__( 'Add Slider Options', 'simple-catch' ),
					'description' 	=> '',
				),
				'slider_effect_options' => array(
					'id' 			=> 'slider_effect_options',
					'title' 		=> esc_html__( 'Slider Effect Options', 'simple-catch' ),
					'description' 	=> '',
				),
			)
		),
		'webmaster_tools' => array(
			'id' 			=> 'webmaster_tools',
			'title' 		=> esc_html__( 'Webmaster Tools', 'simple-catch' ),
			'description' 	=> '',
			'sections' 		=> array(
				'site_verification' => array(
					'id' 			=> 'site_verification',
					'title' 		=> esc_html__( 'Site Verification', 'simple-catch' ),
					'description' 	=> '',
				),
				'header_footer_codes' => array(
					'id' 			=> 'header_footer_codes',
					'title' 		=> esc_html__( 'Header and Footer Codes', 'simple-catch' ),
					'description' 	=> '',
				),
			)
		),

	);

	//Add Panels and sections
	foreach ( $settings_page_tabs as $panel ) {
		$panel_priority = 200;
		if( 'webmaster_tools' == $panel['id'] ){
			$panel_priority = 202;
		}
		$wp_customize->add_panel(
			$theme_slug . $panel['id'],
			array(
				'priority' 		=> $panel_priority,
				'capability' 	=> 'edit_theme_options',
				'title' 		=> $panel['title'],
				'description' 	=> $panel['description'],
			)
		);

		// Loop through tabs for sections
		foreach ( $panel['sections'] as $section ) {
			$params = array(
								'title'			=> $section['title'],
								'description'	=> $section['description'],
								'panel'			=> $theme_slug . $panel['id']
							);

			if ( isset( $section['active_callback'] ) ) {
				$params['active_callback'] = $section['active_callback'];
			}

			$wp_customize->add_section(
				// $id
				$theme_slug . $section['id'],
				// parameters
				$params

			);
		}
	}

	$wp_customize->add_section(
		// $id
		$theme_slug . 'social_links',
		// parameters
		array(
			'priority'	=> 201,
			'title' => esc_html__( 'Social Links', 'simple-catch' ),
		)

	);

	$settings_parameters = array(
		//Responsive Design
		'disable_responsive' => array(
			'id' 				=> 'disable_responsive',
			'title' 			=> esc_html__( 'Check to Disable Responsive Design', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'panel'				=> 'theme_options',
			'section' 			=> 'responsive_design',
			'default' 			=> $defaults['disable_responsive'],
		),
		//Header Options
		'remove_site_title' => array(
			'id' 				=> 'remove_site_title',
			'title' 			=> esc_html__( 'Check to Disable Site Title', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'section' 			=> 'title_tagline',
			'default' 			=> $defaults['remove_site_title'],
			'priority'			=> '25',
		),
		'remove_site_description' => array(
			'id' 				=> 'remove_site_description',
			'title' 			=> esc_html__( 'Check to Disable Site Description', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'section' 			=> 'title_tagline',
			'default' 			=> $defaults['remove_site_description'],
			'priority'			=> '26',
		),

		'featured_logo_footer' => array(
			'id' 				=> 'featured_logo_footer',
			'title' 			=> esc_html__( 'Footer Logo', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'image',
			'sanitize' 			=> 'simplecatch_sanitize_image',
			'section' 			=> 'title_tagline',
			'default' 			=> $defaults['featured_logo_footer'],
			'priority'			=> '27',
		),
		'remove_footer_logo' => array(
			'id' 				=> 'remove_footer_logo',
			'title' 			=> esc_html__( 'Check to Disable Footer Logo', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'section' 			=> 'title_tagline',
			'default' 			=> $defaults['remove_footer_logo'],
			'priority'			=> '28',
		),
		
		//Header Right Sidebar Options
		'disable_header_right_sidebar' => array(
			'id' 				=> 'disable_header_right_sidebar',
			'title' 			=> esc_html__( 'Check to Disable Header Right Sidebar', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'section' 			=> 'header_right_sidebar_options',
			'default' 			=> $defaults['disable_header_right_sidebar'],
		),

		//Color Scheme
		'color_scheme' => array(
			'id' 			=> 'color_scheme',
			'title' 		=> esc_html__( 'Default Color Scheme', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'simplecatch_sanitize_select',
			'section' 		=> 'colors',
			'default' 		=> $defaults['color_scheme'],
			'choices'		=> simplecatch_color_schemes(),
			'priority'		=> '10',
		),
		'heading_color' => array(
			'id' 			=> 'heading_color',
			'title' 		=> esc_html__( 'Heading Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['heading_color'],
			'priority'		=> '100',
		),
		'meta_color' => array(
			'id' 			=> 'meta_color',
			'title' 		=> esc_html__( 'Meta Description Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['meta_color'],
			'priority'		=> '110',
		),
		'text_color' => array(
			'id' 			=> 'text_color',
			'title' 		=> esc_html__( 'Text Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['text_color'],
			'priority'		=> '120',
		),
		'link_color' => array(
			'id' 			=> 'link_color',
			'title' 		=> esc_html__( 'Link Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['link_color'],
			'priority'		=> '130',
		),
		'widget_heading_color' => array(
			'id' 			=> 'widget_heading_color',
			'title' 		=> esc_html__( 'Sidebar Widget Heading Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['widget_heading_color'],
			'priority'		=> '140',
		),
		'widget_text_color' => array(
			'id' 			=> 'widget_text_color',
			'title' 		=> esc_html__( 'Sidebar Widget Text Color', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'color',
			'sanitize' 		=> 'sanitize_hex_color',
			'section' 		=> 'colors',
			'default' 		=> $defaults['widget_text_color'],
			'priority'		=> '150',
		),
		'reset_color' => array(
			'id' 			=> 'reset_color',
			'title' 		=> esc_html__( 'Check to Reset Color', 'simple-catch' ),
			'description'	=> esc_html__( 'Please refresh the customizer after saving if reset option is used', 'simple-catch' ),
			'transport'		=> 'postMessage',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'simplecatch_sanitize_checkbox',
			'section' 		=> 'colors',
			'default' 		=> $defaults['reset_color'],
			'priority'		=> '280',
		),

		//Layout Options
		'sidebar_layout' => array(
			'id' 			=> 'sidebar_layout',
			'title' 		=> esc_html__( 'Default Layout', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'simplecatch_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'default_layout',
			'default' 		=> $defaults['sidebar_layout'],
			'choices'		=> simplecatch_sidebar_layout_options(),
		),

		//Homepage/Frontpage Settings
		'front_page_category' => array(
			'id' 			=> 'front_page_category',
			'title' 		=> esc_html__( 'Front page posts categories:', 'simple-catch' ),
			'description'	=> esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'simple-catch' ),
			'field_type' 	=> 'category-multiple',
			'sanitize' 		=> 'simplecatch_sanitize_category_list',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_frontpage_settings',
			'default' 		=> $defaults['front_page_category']
		),

		//Search Settings
		'search_display_text' => array(
			'id' 			=> 'search_display_text',
			'title' 		=> esc_html__( 'Default Display Text in Search', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_text_settings',
			'default' 		=> $defaults['search_display_text']
		),
		'search_button_text' => array(
			'id' 			=> 'search_button_text',
			'title' 		=> esc_html__( 'Search Button\'s text', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_text_settings',
			'default' 		=> $defaults['search_button_text']
		),

		//Excerpt More Settings
		'more_tag_text' => array(
			'id' 			=> 'more_tag_text',
			'title' 		=> esc_html__( 'More Tag Text', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['more_tag_text']
		),
		'excerpt_length' => array(
			'id' 			=> 'excerpt_length',
			'title' 		=> esc_html__( 'Excerpt length(words)', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'number',
			'sanitize' 		=> 'simplecatch_sanitize_number_range',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['excerpt_length'],
			'input_attrs' 	=> array(
					            'style' => 'width: 45px;',
					            'min'   => 0,
					            'max'   => 999999,
					            'step'  => 1,
					        	)
		),

		//Custom Css
		'custom_css' => array(
			'id' 			=> 'custom_css',
			'title' 		=> esc_html__( 'Enter your custom CSS styles', 'simple-catch' ),
			'description' 	=> '',
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'simplecatch_sanitize_custom_css',
			'panel' 		=> 'theme_options',
			'section' 		=> 'custom_css',
			'default' 		=> $defaults['custom_css']
		),

		//Slider Options

		//Featured Post Slider
		'exclude_slider_post' => array(
			'id' 				=> 'exclude_slider_post',
			'title' 			=> esc_html__( 'Check to Exclude Slider posts from Homepage posts', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'add_slider_options',
			'default' 			=> $defaults['exclude_slider_post'],
		),

		'slider_qty' => array(
			'id' 				=> 'slider_qty',
			'title' 			=> esc_html__( 'Number of Slides', 'simple-catch' ),
			'description'		=> esc_html__( 'Customizer page needs to be refreshed after saving if number of slides is changed', 'simple-catch' ),
			'field_type' 		=> 'number',
			'sanitize' 			=> 'simplecatch_sanitize_number_range',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'add_slider_options',
			'default' 			=> $defaults['slider_qty'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 20,
						            'step'  => 1,
						        	)
		),

		'remove_noise_effect' => array(
			'id' 				=> 'remove_noise_effect',
			'title' 			=> esc_html__( 'Check to Disable Slider Background Effect', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'simplecatch_sanitize_checkbox',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['remove_noise_effect'],
		),
		'transition_effect' => array(
			'id' 				=> 'transition_effect',
			'title' 			=> esc_html__( 'Transition Effect', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'simplecatch_sanitize_select',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_effect'],
			'choices'			=> simplecatch_transition_effects(),
		),
		'transition_delay' => array(
			'id' 				=> 'transition_delay',
			'title' 			=> esc_html__( 'Transition Delay', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'simplecatch_sanitize_number_range',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_delay'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		'transition_duration' => array(
			'id' 				=> 'transition_duration',
			'title' 			=> esc_html__( 'Transition Length', 'simple-catch' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'simplecatch_sanitize_number_range',
			'panel' 			=> 'featured_post_slider',
			'section' 			=> 'slider_effect_options',
			'default' 			=> $defaults['transition_duration'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		

		//Update Notifier
		'disable_scrollup' => array(
			'id' 			=> 'disable_scrollup',
			'title' 		=> esc_html__( 'Check to Disable Scroll Up', 'simple-catch' ),
			'description' 	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'simplecatch_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'scrollup_options',
			'default' 		=> $defaults['disable_scrollup']
		),

		//Social Links
		'social_facebook' => array(
			'id' 			=> 'social_facebook',
			'title' 		=> esc_html__( 'Facebook', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_facebook']
		),
		'social_twitter' => array(
			'id' 			=> 'social_twitter',
			'title' 		=> esc_html__( 'Twitter', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_twitter']
		),
		'social_googleplus' => array(
			'id' 			=> 'social_googleplus',
			'title' 		=> esc_html__( 'Google+', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_googleplus']
		),
		'social_pinterest' => array(
			'id' 			=> 'social_pinterest',
			'title' 		=> esc_html__( 'Pinterest', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_pinterest']
		),
		'social_youtube' => array(
			'id' 			=> 'social_youtube',
			'title' 		=> esc_html__( 'Youtube', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_youtube']
		),
		'social_vimeo' => array(
			'id' 			=> 'social_vimeo',
			'title' 		=> esc_html__( 'Vimeo', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_vimeo']
		),
		'social_linkedin' => array(
			'id' 			=> 'social_linkedin',
			'title' 		=> esc_html__( 'LinkedIn', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_linkedin']
		),
		'social_slideshare' => array(
			'id' 			=> 'social_slideshare',
			'title' 		=> esc_html__( 'Slideshare', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_slideshare']
		),
		'social_foursquare' => array(
			'id' 			=> 'social_foursquare',
			'title' 		=> esc_html__( 'Foursquare', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_foursquare']
		),
		'social_flickr' => array(
			'id' 			=> 'social_flickr',
			'title' 		=> esc_html__( 'Flickr', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_flickr']
		),
		'social_tumblr' => array(
			'id' 			=> 'social_tumblr',
			'title' 		=> esc_html__( 'Tumblr', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_tumblr']
		),
		'social_deviantart' => array(
			'id' 			=> 'social_deviantart',
			'title' 		=> esc_html__( 'deviantART', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_deviantart']
		),
		'social_dribbble' => array(
			'id' 			=> 'social_dribbble',
			'title' 		=> esc_html__( 'Dribbble', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_dribbble']
		),
		'social_myspace' => array(
			'id' 			=> 'social_myspace',
			'title' 		=> esc_html__( 'MySpace', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_myspace']
		),
		'social_wordpress' => array(
			'id' 			=> 'social_wordpress',
			'title' 		=> esc_html__( 'WordPress', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_wordpress']
		),
		'social_rss' => array(
			'id' 			=> 'social_rss',
			'title' 		=> esc_html__( 'RSS', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_rss']
		),
		'social_delicious' => array(
			'id' 			=> 'social_delicious',
			'title' 		=> esc_html__( 'Delicious', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_delicious']
		),
		'social_lastfm' => array(
			'id' 			=> 'social_lastfm',
			'title' 		=> esc_html__( 'Last.fm', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_lastfm']
		),
		'social_instagram' => array(
			'id' 			=> 'social_instagram',
			'title' 		=> esc_html__( 'Instagram', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_instagram']
		),
		'social_github' => array(
			'id' 			=> 'social_github',
			'title' 		=> esc_html__( 'GitHub', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_github']
		),
		'social_vkontakte' => array(
			'id' 			=> 'social_vkontakte',
			'title' 		=> esc_html__( 'Vkontakte', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_vkontakte']
		),
		'social_myworld' => array(
			'id' 			=> 'social_myworld',
			'title' 		=> esc_html__( 'My World', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_myworld']
		),
		'social_odnoklassniki' => array(
			'id' 			=> 'social_odnoklassniki',
			'title' 		=> esc_html__( 'Odnoklassniki', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_odnoklassniki']
		),
		'social_goodreads' => array(
			'id' 			=> 'social_goodreads',
			'title' 		=> esc_html__( 'Goodreads', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_goodreads']
		),
		'social_skype' => array(
			'id' 			=> 'social_skype',
			'title' 		=> esc_html__( 'Skype', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_text_field',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_skype']
		),
		'social_soundcloud' => array(
			'id' 			=> 'social_soundcloud',
			'title' 		=> esc_html__( 'Soundcloud', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_soundcloud']
		),
		'social_email' => array(
			'id' 			=> 'social_email',
			'title' 		=> esc_html__( 'Email', 'simple-catch' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_email',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_email']
		)
	);

	//@remove Remove if block when WordPress 4.8 is released
	if( !function_exists( 'has_custom_logo' ) ) {
		$settings_logo = array(
			//Header Logo Options
			'featured_logo_header' => array(
				'id' 				=> 'featured_logo_header',
				'title' 			=> esc_html__( 'Header Logo', 'simple-catch' ),
				'description'		=> '',
				'field_type' 		=> 'image',
				'sanitize' 			=> 'simplecatch_sanitize_image',
				'section' 			=> 'title_tagline',
				'default' 			=> $defaults['featured_logo_header'],
				'priority'			=> '50',
			),
			'remove_header_logo' => array(
				'id' 				=> 'remove_header_logo',
				'title' 			=> esc_html__( 'Check to Disable Header Logo', 'simple-catch' ),
				'description'		=> '',
				'field_type' 		=> 'checkbox',
				'sanitize' 			=> 'simplecatch_sanitize_checkbox',
				'section' 			=> 'title_tagline',
				'default' 			=> $defaults['remove_header_logo'],
				'priority'			=> '70',
			),
		);

		$settings_parameters = array_merge( $settings_parameters, $settings_logo);
	}


	//@remove Remove if block and custom_css from $settings_paramater when WordPress 5.0 is released
	if( function_exists( 'wp_update_custom_css_post' ) ) {
		unset( $settings_parameters['custom_css'] );
	}

	foreach ( $settings_parameters as $option ) {
		$priority 	= isset( $option['priority'] ) ? $option['priority'] : '' ;
		$transport 	= isset( $option['transport'] ) ? $option['transport'] : 'refresh' ;
		if( 'color' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
					'transport'			=> $transport,
				)
			);

			$params = array(
						'label'			=> $option['title'],
						'settings'  	=> $theme_slug . 'options[' . $option['id'] . ']',
						'priority'		=> $priority,
					);

			if ( 'title_tagline' == $option['section'] || 'colors' == $option['section']){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else if( 'image' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
					'transport'			=> $transport,
				)
			);

			$params = array(
						'label'			=> $option['title'],
						'settings'  	=> $theme_slug . 'options[' . $option['id'] . ']',
						'priority'		=> $priority,
					);

			if ( 'title_tagline' == $option['section'] ){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else if ('checkbox' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
					'transport'			=> $transport,
					)
			);

			$params = array(
						'label'			=> $option['title'],
						'settings'  	=> $theme_slug . 'options[' . $option['id'] . ']',
						'name'  		=> $theme_slug . 'options[' . $option['id'] . ']',
						'priority'		=> $priority,
						'description'	=> $option['description'],
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( 'colors' == $option['section'] || 'title_tagline' == $option['section'] ){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				new Simple_Catch_Customize_Checkbox(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else if ('category-multiple' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
					'transport'			=> $transport,
				)
			);

			$params = array(
						'label'			=> $option['title'],
						'section'		=> $theme_slug . $option['section'],
						'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
						'description'	=> $option['description'],
						'name'	 		=> $theme_slug . 'options[' . $option['id'] . ']',
						'priority'		=> $priority,
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new Simple_Catch_Customize_Dropdown_Categories_Control (
					$wp_customize,
					$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		//For Font Size
		else if( 'multiple-input' == $option['field_type'] ){
			/* Body Font Size */
			$option1_id = $option['id'];
			$option2_id = $option['id'].'_unit';

			$wp_customize->add_setting( $theme_slug .'options['. $option1_id .']', array(
		            'default'        	=> $defaults[$option1_id],
		            'capability'     	=> 'edit_theme_options',
		            'sanitize_callback'	=> $option['sanitize'],
		            'type'				=> 'option',
		        ) );

			$wp_customize->add_setting( $theme_slug .'options['. $option2_id .']', array(
		            'default'        	=> $defaults[$option2_id],
		            'capability'     	=> 'edit_theme_options',
		            'sanitize_callback'	=> $option['sanitize'],
		            'type'				=> 'option',
		        ) );

			$control = new Simplecatch_Customize_Multiple_Input_Control(
	            $wp_customize, $theme_slug .'options['. $option1_id .']', array(
	            'label'    		=> $option['title'],
	            'section'  		=> $theme_slug . $option['section'],
	            'settings'   	=> array (
	                $theme_slug .'options['. $option1_id .']',
	                $theme_slug .'options['. $option2_id .']',
	            )
	        ) );

		    $wp_customize->add_control( $control );
		}
		else {
			//Normal Loop
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'default'			=> $option['default'],
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'transport'			=> $transport,
				)
			);

			// Add setting control
			$params = array(
					'label'			=> $option['title'],
					'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
					'type'			=> $option['field_type'],
					'description'   => $option['description'],
					'priority'		=> $priority,
				) ;

			if ( isset( $option['choices']  ) ){
				$params['choices'] = $option['choices'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( isset( $option['input_attrs']  ) ){
				$params['input_attrs'] = $option['input_attrs'];
			}

			if ( 'colors' == $option['section'] ){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				$params
			);
		}
	}

	//Add featured post elements with respect to no of featured sliders
	for ( $i = 1; $i <= $options['slider_qty']; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[featured_slider][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'simplecatch_sanitize_post_id'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[featured_slider][' . $i . ']',
			array(
				'label'		=> sprintf( esc_html__( '#%s Featured Post ID', 'simple-catch' ), $i ),
				'section'   => $theme_slug .'add_slider_options',
				'settings'  => $theme_slug . 'options[featured_slider][' . $i . ']',
				'type'		=> 'text',
					'input_attrs' => array(
	        		'style' => 'width: 100px;'
	    		),
			)
		);
	}


	// Reset all settings to default
	$wp_customize->add_section( 'simplecatch_reset_all_settings', array(
		'description'	=> esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'simple-catch' ),
		'priority' 		=> 700,
		'title'    		=> esc_html__( 'Reset all settings', 'simple-catch' ),
	) );

	$wp_customize->add_setting( 'simplecatch_options[reset_all_settings]', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback' => 'simplecatch_sanitize_checkbox',
		'transport'			=> 'postMessage',
		'type'				=> 'option'
	) );

	$wp_customize->add_control( 'simplecatch_options[reset_all_settings]', array(
		'label'    => esc_html__( 'Check to reset all settings to default', 'simple-catch' ),
		'section'  => 'simplecatch_reset_all_settings',
		'settings' => 'simplecatch_options[reset_all_settings]',
		'type'     => 'checkbox'
	) );
	// Reset all settings to default end

	//Important Links
	$wp_customize->add_section( 'important_links', array(
		'priority' 		=> 999,
		'title'   	 	=> esc_html__( 'Important Links', 'simple-catch' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'important_links', array(
		'sanitize_callback'	=> 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Simple_Catch_Important_Links( $wp_customize, 'important_links', array(
        'label'   	=> esc_html__( 'Important Links', 'simple-catch' ),
        'section'  	=> 'important_links',
        'settings' 	=> 'important_links',
        'type'     	=> 'important_links',
    ) ) );
    //Important Links End
}
add_action( 'customize_register', 'simplecatch_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously for simplecatch.
 * And flushes out all transient data on preview
 *
 * @since Simple Catch 1.6.3
 */
function simplecatch_customize_preview() {
	//Remove transients on preview
	simplecatch_flush_transients();
}
add_action( 'customize_preview_init', 'simplecatch_customize_preview' );
add_action( 'customize_save', 'simplecatch_customize_preview' );


/**
 * Custom scripts and styles on Customizer for Simple Catch
 *
 * @since Simple Catch 1.4
 */
function simplecatch_customize_scripts() {
	wp_enqueue_script( 'simplecatch_customizer_custom', get_template_directory_uri() . '/functions/panel/customizer-custom-scripts.js', array( 'jquery' ), '20140108', true );
}
add_action( 'customize_controls_enqueue_scripts', 'simplecatch_customize_scripts' );


/**
 * Function to reset date with respect to condition
 */
function simplecatch_reset_data() {
	$options = simplecatch_get_options();
    
    if ( '1' == $options['reset_all_settings'] ) {
    	remove_theme_mods();

    	delete_option( 'simplecatch_options' );

        // Flush out all transients	on reset
        simplecatch_flush_transients();

        return;
    }

	$defaults = simplecatch_defaults_options();
    
    if ( '1' == $options['reset_color'] ) {
		$new_val['color_scheme']              = $defaults['color_scheme'];
		$new_val['heading_color']             = $defaults['heading_color'];
		$new_val['title_color']               = $defaults['title_color'];
		$new_val['tagline_color']             = $defaults['tagline_color'];
		$new_val['meta_color']                = $defaults['meta_color'];
		$new_val['text_color']                = $defaults['text_color'];
		$new_val['link_color']                = $defaults['link_color'];
		$new_val['widget_heading_color']      = $defaults['widget_heading_color'];
		$new_val['widget_text_color']         = $defaults['widget_text_color'];
		$new_val['reset_color']               = "0";

		remove_theme_mod( 'header_textcolor' );

		remove_theme_mod( 'background_color' );

		update_option( 'simplecatch_options', array_merge( $options, $new_val ) );
		
		// Flush out all transients	on reset
        simplecatch_flush_transients();
    }
}
add_action( 'customize_save_after', 'simplecatch_reset_data' );

//Sanitize functions for customizer
require trailingslashit( get_template_directory() ) . 'functions/panel/customizer/customizer-sanitize-functions.php';

//Add Upgrade Button
require trailingslashit( get_template_directory() ) . 'functions/panel/customizer/upgrade-button/class-customize.php';