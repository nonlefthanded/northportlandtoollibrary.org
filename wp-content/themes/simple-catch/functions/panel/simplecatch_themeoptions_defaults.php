<?php
/**
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 1.0
 */

if ( ! function_exists( 'simplecatch_available_fonts' ) ) :
	/**
	 * Returns an array of fonts available to the theme.
	 *
	 * @since Simple Catch 1.0
	 */
	function simplecatch_available_fonts() {
		return array(
			'arial-black'			=> '"Arial Black", Gadget, sans-serif',
			'allan'					=> 'Allan, sans-serif',
			'allerta'				=> 'Allerta, sans-serif',
			'amaranth'				=> 'Amaranth, sans-serif',
			'arial'					=> 'Arial, Helvetica, sans-serif',
			'bitter'				=> 'Bitter, sans-serif',
			'cabin'					=> 'Cabin, sans-serif',
			'cantarell'				=> 'Cantarell, sans-serif',
			'century-gothic'		=> '"Century Gothic", sans-serif',
			'courier-new'			=> '"Courier New", Courier, monospace',
			'crimson-text'			=> '"Crimson Text", sans-serif',
			'dancing-script'		=> '"Dancing Script", sans-serif',
			'droid-sans'			=> '"Droid Sans", sans-serif',
			'droid-serif'			=> '"Droid Serif", sans-serif',
			'georgia'				=> 'Georgia, "Times New Roman", Times, serif',
			'helvetica'				=> 'Helvetica, "Helvetica Neue", Arial, sans-serif',
			'helvetica-neue'		=> '"Helvetica Neue",Helvetica,Arial,sans-serif',
			'istok-web'				=> '"Istok Web", sans-serif',
			'impact'				=> 'Impact, Charcoal, sans-serif',
			'lato'					=> '"Lato", sans-serif',
			'lucida-sans-unicode'	=> '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'lucida-grande'			=> '"Lucida Grande", "Lucida Sans Unicode", sans-serif',
			'lobster'				=> 'Lobster, sans-serif',
			'lora'					=> '"Lora", serif',
			'monaco'				=> 'Monaco, Consolas, "Lucida Console", monospace, sans-serif',
			'nobile'				=> 'Nobile, sans-serif',
			'open-sans'				=> '"Open Sans", sans-serif',
			'oswald'				=> '"Oswald", sans-serif',
			'palatino'				=> 'Palatino, "Palatino Linotype", "Book Antiqua", serif',
			'patua-one'				=> '"Patua One", sans-serif',
			'playfair-display'		=> '"Playfair Display", sans-serif',
			'pt-sans'				=> '"PT Sans", sans-serif',
			'pt-serif'				=> '"PT Serif", serif',
			'quattrocento-sans' 	=> '"Quattrocento Sans", sans-serif',
			'tahoma'				=> 'Tahoma, Geneva, sans-serif',
			'trebuchet-ms'			=> '"Trebuchet MS", "Helvetica", sans-serif',
			'times-new-roman'		=> '"Times New Roman", Times, serif',
			'ubuntu'				=> 'Ubuntu, sans-serif',
			'verdana'				=> 'Verdana, Geneva, sans-serif',
			'yanone-kaffeesatz' 	=> '"Yanone Kaffeesatz", sans-serif'
		);
	}
endif;


if ( ! function_exists( 'simplecatch_defaults_options' ) ) :
	/**
	 * Returns an array of default options
	 *
	 * @since Simple Catch 3.8
	 */
	function simplecatch_defaults_options() {
		$defaults = array(
			'disable_responsive'                => '0',
			'remove_header_logo'                => '0',
			'featured_logo_header'              => get_template_directory_uri().'/images/logo.png',
			'remove_site_title'                 => '0',
			'remove_site_description'           => '0',
			'featured_logo_footer'              => get_template_directory_uri().'/images/logo-foot.png',
			'remove_footer_logo'                => '0',
			'disable_header_right_sidebar'      => '0',
			'color_scheme'                      => 'default',
			'header_top_background'             => 'transparent',
			'header_background'                 => 'transparent',
			'footer_background'                 => 'transparent',
			'footer_sidebar_background'         => 'transparent',
			'header_footer_border'              => '#ccc',
			'title_color'                       => '#444',
			'tagline_color'                     => '#666',
			'heading_color'                     => '#444',
			'meta_color'                        => '#999',
			'text_color'                        => '#555',
			'link_color'                        => '#000',
			'widget_heading_color'              => '#666',
			'widget_text_color'                 => '#666',
			'widget_link_color'                 => '#666',
			'menu_bg_color'                     => '#fff',
			'menu_text_color'                   => '#444',
			'border_color'                      => '#ccc',
			'hover_active_color'                => '#444',
			'hover_active_text_color'           => '#fff',
			'sub_menu_bg_color'                 => '#444',
			'sub_menu_text_color'               => '#999',
			'sub_menu_hover_bg_color'           => '#333',
			'sub_menu_hover_text_color'         => '#fff',
			'scrollup_bg_color'                 => '#000',
			'scrollup_text_color'               => '#fff',
			'reset_color'                       => '2',
			'reset_typography'                  => '2',
			'body_font'                         => 'arial',
			'title_font'                        => 'lobster',
			'tagline_font'                      => 'arial',
			'headings_font'                     => 'arial',
			'content_font'                      => 'arial',
			'reset_typography_font_size'        => '2',
			'body_font_size'                    => '14',
			'body_font_size_unit'               => 'px',
			'body_line_height'                  => '1.62',
			'body_line_height_unit'             => 'em',
			'site_title_font_size'              => '45',
			'site_title_font_size_unit'         => 'px',
			'site_title_line_height'            => '45',
			'site_title_line_height_unit'       => 'px',
			'site_description_font_size'        => '14',
			'site_description_font_size_unit'   => 'px',
			'site_description_line_height'      => '16',
			'site_description_line_height_unit' => 'px',
			'content_title_font_size'           => '34',
			'content_title_font_size_unit'      => 'px',
			'content_title_line_height'         => '45',
			'content_title_line_height_unit'    => 'px',
			'h1_font_size'                      => '34',
			'h1_font_size_unit'                 => 'px',
			'h2_font_size'                      => '28',
			'h2_font_size_unit'                 => 'px',
			'h3_font_size'                      => '21',
			'h3_font_size_unit'                 => 'px',
			'h4_font_size'                      => '18',
			'h4_font_size_unit'                 => 'px',
			'headings_line_height'              => '1.62',
			'headings_line_height_unit'         => 'em',
			'content_font_size'                 => '14',
			'content_font_size_unit'            => 'px',
			'content_line_height'               => '1.62',
			'content_line_height_unit'          => 'em',
			'front_page_category'               => '0',
			'exclude_slider_post'               => '0',
			'sidebar_layout'                    => 'right-sidebar',
			'content_layout'                    => 'excerpt',
			'reset_layout'                      => '2',
			'slider_qty'                        => 4,
			'select_slider_type'                => 'post-slider',
			'enable_slider'                     => 'enable-slider-homepage',
			'slider_category'                   => '0',
			'featured_slider'                   => array(),
			'featured_slider_page'              => array(),
			'featured_image_slider_image'       => array(),
			'featured_image_slider_link'        => array(),
			'featured_image_slider_base'        => array(),
			'featured_image_slider_title'       => array(),
			'featured_image_slider_content'     => array(),
			'remove_noise_effect'               => '0',
			'transition_effect'                 => 'fade',
			'transition_delay'                  => 4,
			'transition_duration'               => 1,
			'social_facebook'                   => '',
			'social_twitter'                    => '',
			'social_googleplus'                 => '',
			'social_pinterest'                  => '',
			'social_youtube'                    => '',
			'social_vimeo'                      => '',
			'social_linkedin'                   => '',
			'social_slideshare'                 => '',
			'social_foursquare'                 => '',
			'social_flickr'                     => '',
			'social_tumblr'                     => '',
			'social_deviantart'                 => '',
			'social_dribbble'                   => '',
			'social_myspace'                    => '',
			'social_wordpress'                  => '',
			'social_rss'                        => '',
			'social_delicious'                  => '',
			'social_lastfm'                     => '',
			'social_instagram'                  => '',
			'social_github'                     => '',
			'social_vkontakte'                  => '',
			'social_myworld'                    => '',
			'social_odnoklassniki'              => '',
			'social_goodreads'                  => '',
			'social_skype'                      => '',
			'social_soundcloud'                 => '',
			'social_email'                      => '',
			'custom_css'                        => '',
			'disable_scrollup'                  => '0',
			'sidebar_layout'                    => 'right-sidebar',
			'more_tag_text'                     => esc_html__( 'Continue Reading', 'simple-catch' ) . ' &rarr;' ,
			'search_display_text'               => esc_html__( 'Type Keyword', 'simple-catch' ),
			'search_button_text'                => esc_html__( 'Search', 'simple-catch' ),
			'excerpt_length'                    => 30,
			'footer_code'                       => '<div class="copyright">[footer-image] Copyright &copy; [the-year] [site-link]. All Rights Reserved.</div><div class="powered-by">Powered by: [wp-link] | Theme: [theme-link]</div>',
			'reset_footer'                      => '2'
		);
		
		return $defaults;
	}
endif;


/**
 * Shortcode to display Footer Image.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function simplecatch_footer_image() {
	if( function_exists( 'simplecatch_footerlogo' ) ) :
    	return simplecatch_footerlogo();
    endif;
}


/**
 * Shortcode to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function simplecatch_the_year() {
	return esc_attr( date_i18n( __( 'Y', 'simple-catch' ) ) );
}

/**
 * Shortcode to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function simplecatch_site_link() {
	return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}


/**
 * Shortcode to display a link to WordPress.org.
 *
 * @return string
 */
function simplecatch_wp_link() {
	return '<a href="http://wordpress.org" target="_blank" title="' . esc_attr__( 'WordPress', 'simple-catch' ) . '"><span>' . __( 'WordPress', 'simple-catch' ) . '</span></a>';
}

/**
 * Shortcode to display a link to Theme Link.
 *
 * @return string
 */
function simplecatch_theme_link() {
	return '<a href="' . esc_url( 'https://catchthemes.com/themes/simple-catch' ) . '" target="_blank" title="' . esc_attr__( 'Simple Catch', 'simple-catch' ) . '"><span>' . esc_html__( 'Simple Catch', 'simple-catch' ) . '</span></a>';
}

/**
 * Returns a link to Theme Shop.
 *
 * @return string
 */
function simplecatch_shop_link() {
    return '<a href="'. esc_url( __( 'https://catchthemes.com', 'simple-catch' ) ) . '" target="_blank" title="' . esc_attr__( 'Catch Themes', 'simple-catch' ) . '"><span>' . esc_html__( 'Catch Themes', 'simple-catch' ) . '</span></a>';
}

/**
 * Returns an array of color schemes registered
 *
 * @since Simple Catch 3.0
 */
function simplecatch_color_schemes() {
	$options = array(
		'default' 		=> esc_html__( 'Default (Light)', 'simple-catch' ),
		'dark'			=> esc_html__( 'Dark', 'simple-catch' ),
		'brown'			=> esc_html__( 'Brown', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_color_schemes', $options );
}

/**
 * Returns an array of sidebar layout options
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sidebar_layout_options() {
	$options = array(
		'right-sidebar' 		=> __( 'Content on Left', 'simple-catch' ),
		'left-sidebar' 			=> __( 'Content on Right', 'simple-catch' ),
		'no-sidebar'			=> __( 'No Sidebar', 'simple-catch' ),
		'no-sidebar-full-width' => __( 'No Sidebar, Full Width', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_sidebar_layout_options', $options );
}

/**
 * Returns an array of slider transition effects
 *
 * @since Simple Catch 3.0
 */
function simplecatch_transition_effects() {
	$options = array(
		'fade'			=> __( 'fade', 'simple-catch' ),
		'wipe' 			=> __( 'wipe', 'simple-catch' ),
		'scrollUp' 		=> __( 'scrollUp', 'simple-catch' ),
		'scrollDown'	=> __( 'scrollDown', 'simple-catch' ),
		'scrollUp' 		=> __( 'scrollUp', 'simple-catch' ),
		'scrollLeft'	=> __( 'scrollLeft', 'simple-catch' ),
		'scrollRight'	=> __( 'scrollRight', 'simple-catch' ),
		'blindX' 		=> __( 'blindX', 'simple-catch' ),
		'blindY' 		=> __( 'blindY', 'simple-catch' ),
		'blindZ' 		=> __( 'blindZ', 'simple-catch' ),
		'cover' 		=> __( 'cover', 'simple-catch' ),
		'shuffle' 		=> __( 'shuffle', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_transition_effects', $options );
}

/**
 * Returns an array of content layout options
 *
 * @since Simple Catch 3.4
 */
function simplecatch_content_layout_options() {
	$options = array(
		'excerpt' 		=> __( 'Excerpt/Blog Display', 'simple-catch' ),
		'full' 			=> __( 'Full Content Display', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_content_layout_options', $options );
}

/**
 * Returns an array of slider enable options
 *
 * @since Simple Catch 3.4
 */
function simplecatch_enable_slider_options() {
	$options = array(
		'enable-slider-homepage'=> __( 'Homepage / Frontpage', 'simple-catch' ),
		'enable-slider-allpage' => __( 'Entire Site', 'simple-catch' ),
		'disable-slider' 		=> __( 'Disable', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_enable_slider_options', $options );
}

/**
 * Returns an array of slider types
 *
 * @since Simple Catch 3.4
 */
function simplecatch_slider_types() {
	$options = array(
		'image-slider' 		=> __( 'Featured Image Slider', 'simple-catch' ),
		'post-slider' 		=> __( 'Featured Post Slider', 'simple-catch' ),
		'page-slider' 		=> __( 'Featured Page Slider', 'simple-catch' ),
		'category-slider' 	=> __( 'Featured Category Slider', 'simple-catch' ),
	);

	return apply_filters( 'simplecatch_slider_types', $options );
}