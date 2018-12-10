<?php
/**
 * Simple Catch functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, simplecatch_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 1.0
 */

// Load up theme options defaults
require( get_template_directory() . '/functions/panel/simplecatch_themeoptions_defaults.php' );

/**
 * Get Theme Options
 *
 * @return array
 */
function simplecatch_get_options() {
	$defaults = simplecatch_defaults_options();

	$options = (array) get_option( 'simplecatch_options' );

	return array_merge( $defaults , $options ) ;
}

if ( ! function_exists( 'simplecatch_content_width' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function simplecatch_content_width() {
		$layout = simplecatch_get_theme_layout();

		$content_width = 978;

		if ( 'no-sidebar-full-width' != $layout ) {
			$content_width = 642;
		}

		$GLOBALS['content_width'] = apply_filters( 'simplecatch_content_width', $content_width );
	}
endif; // simplecatch_content_width
add_action( 'after_setup_theme', 'simplecatch_content_width', 0 );

/**
 * Tell WordPress to run simplecatch_setup() when the 'after_setup_theme' hook is run.
 */

if ( ! function_exists( 'simplecatch_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * @uses load_theme_textdomain() For localization support.
	 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
	 * @uses register_nav_menu() To add support for navigation menu.
	 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
	 *
	 * @since Simple Catch 1.0
	 */
	function simplecatch_setup() {

		/* Simple Catch is now available for translation.
		 * Add your files into /languages/ directory.
		 * @see http://codex.wordpress.org/Function_Reference/load_theme_textdomain
		 */
		load_theme_textdomain( 'simple-catch', get_template_directory() . '/languages' );

		/**
	     * This feature enables Jetpack plugin Infinite Scroll
	     */
	    add_theme_support( 'infinite-scroll', array(
			'type'          => 'click',
	        'container'     => 'primary',
			'render'    	=> 'simplecatch_infinite_scroll_render',
	        'footer'        => 'page'
	    ) );

		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
		add_theme_support( 'post-thumbnails' );

		/* We'll be using post thumbnails for custom features images on posts under blog category.
		 * Larger images will be auto-cropped to fit.
		 */
		set_post_thumbnail_size( 210, 210 );

		// Add Simple Catch's custom image sizes
		add_image_size( 'featured', 210, 210, true); // uses on homepage featured image
		add_image_size( 'slider', 976, 313, true); // uses on Featured Slider on Homepage Header

		/**
		 * Setup title support for theme
		 * Supported from WordPress version 4.1 onwards
		 * More Info: https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
		 */
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'simple-catch' ) );

		// Setup the WordPress core custom background feature.
		add_theme_support( 'custom-background' );

		/**
		 * Add support for the Aside Post Formats
		 */
		add_theme_support( 'post-formats', array( 'video', 'audio' ) );

		//@remove Remove check when WordPress 4.8 is released
		if ( function_exists( 'has_custom_logo' ) ) {
			/**
			* Setup Custom Logo Support for theme
			* Supported from WordPress version 4.5 onwards
			* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
			*/
			add_theme_support( 'custom-logo' );
		}
	} // simplecatch_setup
endif;
add_action( 'after_setup_theme', 'simplecatch_setup' );


if ( ! function_exists( 'simplecatch_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_options
	 *
	 * @action wp_head
	 *
	 * @since Simple Catch 3.6
	 */
	function simplecatch_get_theme_layout() {
		$id = '';

		global $post, $wp_query;

	    // Front page displays in Reading Settings
		$page_on_front  = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page or Front Page setting in Reading Settings
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $id = $page_id;
	    }
	    else if ( is_singular() ) {
	 		if ( is_attachment() ) {
				$id = $post->post_parent;
			}
			else {
				$id = $post->ID;
			}
		}

		//Get appropriate metabox value of layout
		if ( '' != $id ) {
			$layout = get_post_meta( $id, 'simplecatch-sidebarlayout', true );
		}
		else {
			$layout = 'default';
		}

		// Get theme options
		$options = simplecatch_get_options();

   		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['sidebar_layout'];
		}

		return $layout;
	}
endif; //simplecatch_get_theme_layout


/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function simplecatch_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '3.6' ) >= 0 ) {
		return;
	}

	// Get theme options
	$options = simplecatch_get_options();

   	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'the_custom_logo' ) ) {
		if ( ! has_custom_logo() ) {
			if( isset( $options['featured_logo_header'] ) && '' != $options['featured_logo_header'] ) {
				// Since previous logo was stored a URL, convert it to an attachment ID
				$logo = attachment_url_to_postid( $options['featured_logo_header'] );

				if ( is_int( $logo ) ) {
					set_theme_mod( 'custom_logo', $logo );
				}
			}
		}
  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'simplecatch_logo_migrate' );


/**
 * Migrate Custom Favicon to WordPress core Site Icon
 *
 * Runs if version number saved in theme_mod "site_icon_version" doesn't match current theme version.
 */
function simplecatch_site_icon_migrate() {
	$ver = get_theme_mod( 'site_icon_version', false );

	//Return if update has already been run
	if ( version_compare( $ver, '3.6' ) >= 0 ) {
		return;
	}
	
	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'has_site_icon' ) ) {
		// Get theme options
		$options = simplecatch_get_options();

		if( isset( $options['fav_icon'] ) && '' != $options['fav_icon'] ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$site_icon = attachment_url_to_postid( $options['fav_icon'] );

			if ( is_int( $site_icon ) ) {
				update_option( 'site_icon', $site_icon );
			}
		}

	  	// Update to match site_icon_version so that script is not executed continously
		set_theme_mod( 'site_icon_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'simplecatch_site_icon_migrate' );


/**
 * Migrate Custom CSS to WordPress core Custom CSS
 *
 * Runs if version number saved in theme_mod "custom_css_version" doesn't match current theme version.
 */
function simplecatch_custom_css_migrate(){
	$ver = get_theme_mod( 'custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
	    // Migrate any existing theme CSS to the core option added in WordPress 4.7.

	    // Get theme options
		$options = simplecatch_get_options();

	    if ( '' != $options['custom_css'] ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $options['custom_css'] );

	        if ( ! is_wp_error( $return ) ) {
	            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
	            unset( $options['custom_css'] );
	            update_option( 'simplecatch_options', $options );

	            // Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'custom_css_version', '4.7' );
	        }
	    }
	}
}
add_action( 'after_setup_theme', 'simplecatch_custom_css_migrate' );




// Implement the Custom Header feature
require trailingslashit( get_template_directory() ) . 'functions/custom-header.php';

// Grab Simple Catch's Custom widgets.
require trailingslashit( get_template_directory() ) . 'functions/widgets.php';

// Load up our Simple Catch's core Functions
require trailingslashit( get_template_directory() ) . 'functions/core.php';

// Load Sliders
require trailingslashit( get_template_directory() ) . 'functions/sliders.php';

// Load metabox options
require trailingslashit( get_template_directory() ) . 'functions/metabox.php';

// Load customizer
require trailingslashit( get_template_directory() ) . 'functions/panel/customizer/customizer.php';