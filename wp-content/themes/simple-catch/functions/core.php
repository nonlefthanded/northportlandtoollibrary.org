<?php
if ( ! function_exists( 'simplecatch_fonts_url' ) ) :
	/**
	 * return google fonts url
	 *
	 * @since Simple Catch 1.0.
	 */
	function simplecatch_fonts_url() {
		// Get theme options
		$options = simplecatch_get_options();
		$fonts[] = $options['body_font'];
		$fonts[] = $options['title_font'];
		$fonts[] = $options['tagline_font'];
		$fonts[] = $options['headings_font'];
		$fonts[] = $options['content_font'];

		$web_fonts_stylesheet = '';

		$web_fonts = array(
			'allan'             => 'Allan',
			'allerta'           => 'Allerta',
			'amaranth'          => 'Amaranth',
			'bitter'            => 'Bitter',
			'cabin'             => 'Cabin',
			'cantarell'         => 'Cantarell',
			'crimson-text'      => 'Crimson+Text',
			'dancing-script'    => 'Dancing+Script',
			'droid-sans'        => 'Droid+Sans',
			'droid-serif'       => 'Droid+Serif',
			'istok-web'         => 'Istok+Web',
			'lato'              => 'Lato',
			'lobster'           => 'Lobster',
			'lora'              => 'Lora',
			'nobile'            => 'Nobile',
			'open-sans'         => 'Open+Sans',
			'oswald'            => 'Oswald',
			'patua-one'         => 'Patua+One',
			'playfair-display'  => 'Playfair+Display',
			'pt-sans'           => 'PT+Sans',
			'pt-serif'          => 'PT+Serif',
			'quattrocento-sans' => 'Quattrocento+Sans',
			'ubuntu'            => 'Ubuntu',
			'yanone-kaffeesatz' => 'Yanone+Kaffeesatz'
		);
		
		$fonts = array_unique( $fonts ); // Make the array of fonts unique so that same font is not loaded twice
		$fonts = array_intersect( $fonts, array_keys( $web_fonts ) ); // Intersect selected fonts and webfonts to only recover fonts that need loading
		
		if ( !empty( $fonts ) ) {
			$web_fonts_stylesheet = '//fonts.googleapis.com/css?family=';
			
			$i = 0;
			
			foreach( $fonts as $font ) {
				if ( $i )// only set | to $web_fonts_stylesheet from second loop onwards
					$web_fonts_stylesheet .='%7c';

				$web_fonts_stylesheet .= $web_fonts[ $font ] . ':300,300italic,regular,italic,600,600italic';

				$i++;
			}

			$web_fonts_stylesheet .= '&subset=latin';
		}

		return esc_url( $web_fonts_stylesheet );
	} // simplecatch_fonts_url
endif;


if ( ! function_exists( 'simplecatch_scripts_method' ) ) :
/**
 * Register jquery scripts
 *
 * @register jquery cycle and custom-script
 * hooks action wp_enqueue_scripts
 */
function simplecatch_scripts_method() {
	global $wp_query;

   	// Get theme options
	$options      = simplecatch_get_options();
	
	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Load google fonts
	wp_enqueue_style( 'simple-catch-fonts', simplecatch_fonts_url(), array(), null );

	/**
	 * Loads up main stylesheet.
	 */
	wp_enqueue_style( 'simple-catch-style', get_stylesheet_uri() );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons/genericons.css', array(), '3.4.1' );

	/**
	 * Loads up Color Scheme
	 */
	$color_scheme = $options['color_scheme'];
	if ( 'dark' == $color_scheme ) {
		wp_enqueue_style( 'simple-catch-dark', get_template_directory_uri() . '/css/dark.css', array(), null );
	}
	elseif ( 'brown' == $color_scheme ) {
		wp_enqueue_style( 'simple-catch-brown', get_template_directory_uri() . '/css/brown.css', array(), null );
	}

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Adds Slider JavaScript to homepage only in Free version
	 */
	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		//Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
		wp_register_script( 'jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

		wp_enqueue_script( 'simplecatch-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery-cycle' ), '1.0', true );

		$transition_effect   = $options['transition_effect'];
		$transition_delay    = $options['transition_delay'] * 1000;
		$transition_duration = $options['transition_duration'] * 1000;
		
		wp_localize_script(
			'simplecatch-slider',
			'js_value',
			array(
				'transition_effect'   => $transition_effect,
				'transition_delay'    => $transition_delay,
				'transition_duration' => $transition_duration
			)
		);
	}

	//Enqueue Search Script
	wp_enqueue_script ( 'simplecatch-search', get_template_directory_uri() . '/js/search.js', array( 'jquery' ), '1.0', true );

	//Responsive Menu and Style
	if ( '0' == $options['disable_responsive'] ) {
		wp_enqueue_style( 'simplecatch-responsive', get_template_directory_uri() . '/css/responsive.css' );
		
		wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/js/fitvids.min.js', array( 'jquery' ), '20130324', true );

		wp_enqueue_script( 'simplecatch-menu', get_template_directory_uri() . '/js/menu.min.js', array( 'jquery' ), '20151204', true );

		wp_localize_script( 'simplecatch-menu', 'screenReaderText', array(
			'expand'   => esc_html__( 'expand child menu', 'simple-catch' ),
			'collapse' => esc_html__( 'collapse child menu', 'simple-catch' ),
		) );
	}

	/**
	 * Loads up Scroll Up script
	 */
	if ( $options['disable_scrollup'] == '0' ) {
		wp_enqueue_script( 'simplecatch-scrollup', get_template_directory_uri() . '/js/scrollup.min.js', array( 'jquery' ), '20072014', true  );
	}

	// Load the html5 shiv.
	wp_enqueue_script( 'simplecatch-html5', get_template_directory_uri() . '/js/html5.min.js', array(), '3.7.3' );
	wp_script_add_data( 'simplecatch-html5', 'conditional', 'lt IE 9' );

} // simplecatch_scripts_method
endif;
add_action( 'wp_enqueue_scripts', 'simplecatch_scripts_method' );


// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
function simplecatch_add_menuclass($ulclass) {
	return preg_replace('/<ul>/', '<ul class="menu">', $ulclass, 1);
}
add_filter('wp_page_menu','simplecatch_add_menuclass');


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function simplecatch_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'simplecatch_page_menu_args' );


/**
 * Register script for admin section
 *
 * No scripts should be enqueued within this function.
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 * @uses wp_register_script
 * @action admin_enqueue_scripts
 */
function simplecatch_register_js() {
	//jQuery Cookie
	wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'simplecatch_register_js' );


/**
 * Responsive Layout
 *
 * @get the data value of responsive layout from theme options
 * @display responsive meta tag
 * @action wp_head
 */
function simplecatch_responsive() {
	// Get theme options
	$options = simplecatch_get_options();

	$output = '<!-- Disable Responsive -->';

	if ( "0" == $options['disable_responsive'] ) {
		$output = '<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">';
	}

	echo $output;
} // simplecatch_responsive
add_filter( 'wp_head', 'simplecatch_responsive', 1 );


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function simplecatch_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'simplecatch_pingback_header' );


/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 * @uses filter excerpt_length
 */
function simplecatch_excerpt_length( $length ) {
	// Get theme options
	$options = simplecatch_get_options();

	return $options['excerpt_length'];
}
add_filter( 'excerpt_length', 'simplecatch_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
function simplecatch_continue_reading() {
	// Get theme options
	$options = simplecatch_get_options();

	$more_tag_text = $options['more_tag_text'];
	return ' <a class="readmore" href="'. esc_url( get_permalink() ) . '">' . esc_attr( $more_tag_text ) . '</a>';
}


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with simplecatch_continue_reading().
 *
 */
function simplecatch_excerpt_more( $more ) {
	return ' &hellip;' . simplecatch_continue_reading();
}
add_filter( 'excerpt_more', 'simplecatch_excerpt_more' );


/**
 * Adds Continue Reading link to post excerpts.
 *
 * function tied to the get_the_excerpt filter hook.
 */
function simplecatch_custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= simplecatch_continue_reading();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'simplecatch_custom_excerpt' );


if ( ! function_exists( 'simplecatch_header_logo' ) ) :
	/**
	 * Get the header logo Image from theme options
	 *
	 * @uses header logo
	 */
	function simplecatch_header_logo() {
		// Check Logo
		if ( function_exists( 'has_custom_logo' ) ) {
			if ( has_custom_logo() ) {
				echo '<div class="site-logo">'. get_custom_logo() . '</div><!-- #site-logo -->';
			}
		}
	} // simplecatch_header_logo
endif;


if ( ! function_exists( 'simplecatch_header_title' ) ) :
	/**
	 * Get the Site Title and Tagline from Settings and theme options
	 *
	 */
	function simplecatch_header_title() {
		// Get theme options
		$options = simplecatch_get_options();

		if ( !empty( $options['remove_site_title'] ) && !empty( $options['remove_site_description'] ) ) {
			$branding_class = "site-branding-text screen-reader-text";
			$title_class = "site-title";
			$description_class = "site-description";
		}
		else {
			$branding_class = "site-branding-text";

			if( empty( $options['remove_site_title'] ) ) {
				$title_class = "site-title";
			}
			else {
				$title_class = "site-title screen-reader-text";
			}
			
			if( empty( $options['remove_site_description'] ) ) {
				$description_class = "site-description";
			}
			else {
				$description_class = "site-description screen-reader-text";
			}
		} ?>

		<div class="<?php echo $branding_class; ?>">
			<?php if ( is_front_page() ) : ?>
				<h1 class="<?php echo $title_class; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="<?php echo $title_class; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
			<?php $description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="<?php echo $description_class; ?>"><?php echo $description; ?></p>
			<?php endif; ?>
		</div><!-- .site-branding-text -->
	<?php 
	} // simplecatch_header_title
endif;


if ( ! function_exists( 'simplecatch_headerdetails' ) ) :
/**
 * Diplaying Header Logo, Site Title and Tagline
 */
function simplecatch_headerdetails() {
	// Get theme options
	$options = simplecatch_get_options();

	echo '<div class="site-branding">';

	simplecatch_header_logo();
	simplecatch_header_title();

	echo '</div>';

} // simplecatch_headerdetails
endif;
add_action( 'simplecatch_before_sidebartop', 'simplecatch_headerdetails', 10 );


if ( ! function_exists( 'simplecatch_header_right' ) ) :
/**
 * Diplaying social and search elements on header right
 */
function simplecatch_header_right() {
	?>
	<div id="sidebar-top" class="clearfix widget-area">
		<?php 
		if ( function_exists( 'simplecatch_headersocialnetworks' ) ) { ?>
            <section class="widget widget_simplecatch_social_widget">
                <?php simplecatch_headersocialnetworks(); ?>
            </section>
        <?php
        } ?>
        
        <section class="widget widget_search" id="search-header-right">	
        	<?php echo get_search_form(); ?>
		</section>
    </div><!-- #sidebar-top .widget-area -->
	<?php

} // simplecatch_header_right
endif;
add_action( 'simplecatch_before_sidebartop', 'simplecatch_header_right', 20 );


if ( ! function_exists( 'simplecatch_footerlogo' ) ) :
/**
 * Get the footer logo Image from theme options
 *
 * @uses footer logo
 * @get the data value of image from theme options
 * @display footer Image logo
 *
 * @uses default logo if logo field on theme options is empty
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_footerlogo() {
	// Get theme options
	$options = simplecatch_get_options();

	$output = '';

	if ( '0' == $options['remove_footer_logo']) {
		// if empty featured_logo_footer on theme options, display default fav icon
		$output = '<a href="'.esc_url( home_url( '/' ) ).'#masthead" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'"><img src="'. get_template_directory_uri().'/images/logo-foot.png" alt="footerlogo" /></a>';

		// if not empty featured_logo_footer on theme options
		if ( !empty( $options['featured_logo_footer'] ) ) {
			$output = '<a href="'.esc_url( home_url( '/' ) ).'#masthead" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'"><img src="'.esc_url( $options['featured_logo_footer'] ).'" alt="'.get_bloginfo( 'name' ).'" /></a>';
		}
	}

	return $output;
} // simplecatch_footerlogo
endif;


if ( ! function_exists( 'simplecatch_breadcrumb_display' ) ) :
/**
 * Display breadcrumb on header
 */
function simplecatch_breadcrumb_display() {
	global $wp_query;

	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		return false;
	}
	else {
		if ( function_exists( 'bcn_display_list' ) ) {
			echo
			'<div class="breadcrumb">
				<ul>';
					bcn_display_list();
					echo '
				</ul>
				<div class="row-end"></div>
			</div> <!-- .breadcrumb -->';
		}
	}

} // simplecatch_breadcrumb_display
endif;

add_action( 'simplecatch_after_headercontent', 'simplecatch_breadcrumb_display', 25 );


if ( ! function_exists( 'simplecatch_headersocialnetworks' ) ) :
	/**
	 * This function for social links display on header
	 *
	 * @fetch links through Theme Options
	 * @use in widget
	 * @social links, Facebook, Twitter and RSS
	  */
	function simplecatch_headersocialnetworks() {
		//delete_transient( 'simplecatch_headersocialnetworks' );

		// Get theme options
		$options = simplecatch_get_options();

	    $elements = array();

		$elements = array( 	$options['social_facebook'],
							$options['social_twitter'],
							$options['social_googleplus'],
							$options['social_linkedin'],
							$options['social_pinterest'],
							$options['social_youtube'],
							$options['social_vimeo'],
							$options['social_slideshare'],
							$options['social_foursquare'],
							$options['social_flickr'],
							$options['social_tumblr'],
							$options['social_deviantart'],
							$options['social_dribbble'],
							$options['social_myspace'],
							$options['social_wordpress'],
							$options['social_rss'],
							$options['social_delicious'],
							$options['social_lastfm'],
							$options['social_instagram'],
							$options['social_github'],
							$options['social_vkontakte'],
							$options['social_myworld'],
							$options['social_odnoklassniki'],
							$options['social_goodreads'],
							$options['social_skype'],
							$options['social_soundcloud'],
							$options['social_email']
						);
		$flag = 0;
		if( !empty( $elements ) ) {
			foreach( $elements as $option) {
				if( !empty( $option ) ) {
					$flag = 1;
				}
				else {
					$flag = 0;
				}
				if( $flag == 1 ) {
					break;
				}
			}
		}

		if ( ( !$simplecatch_headersocialnetworks = get_transient( 'simplecatch_headersocialnetworks' ) ) && ( $flag == 1 ) )  {
			echo '<!-- refreshing cache -->';

			$simplecatch_headersocialnetworks .='
			<ul class="social-profile">';

				//facebook
				if ( !empty( $options['social_facebook'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="facebook"><a href="'.esc_url( $options['social_facebook'] ).'" title="'.sprintf( esc_attr__( '%s on Facebook', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Facebook </a></li>';
				}
				//Twitter
				if ( !empty( $options['social_twitter'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="twitter"><a href="'.esc_url( $options['social_twitter'] ).'" title="'.sprintf( esc_attr__( '%s on Twitter', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}
				//Google+
				if ( !empty( $options['social_googleplus'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="google-plus"><a href="'.esc_url( $options['social_googleplus'] ).'" title="'.sprintf( esc_attr__( '%s on Google+', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Google+ </a></li>';
				}
				//Linkedin
				if ( !empty( $options['social_linkedin'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="linkedin"><a href="'.esc_url( $options['social_linkedin'] ).'" title="'.sprintf( esc_attr__( '%s on Linkedin', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Linkedin </a></li>';
				}
				//Pinterest
				if ( !empty( $options['social_pinterest'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="pinterest"><a href="'.esc_url( $options['social_pinterest'] ).'" title="'.sprintf( esc_attr__( '%s on Pinterest', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}
				//Youtube
				if ( !empty( $options['social_youtube'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="you-tube"><a href="'.esc_url( $options['social_youtube'] ).'" title="'.sprintf( esc_attr__( '%s on YouTube', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' YouTube </a></li>';
				}
				//Vimeo
				if ( !empty( $options['social_vimeo'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="viemo"><a href="'.esc_url( $options['social_vimeo'] ).'" title="'.sprintf( esc_attr__( '%s on Vimeo', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vimeo </a></li>';
				}
				//Slideshare
				if ( !empty( $options['social_slideshare'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="slideshare"><a href="'.esc_url( $options['social_slideshare'] ).'" title="'.sprintf( esc_attr__( '%s on Slideshare', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Slideshare </a></li>';
				}
				//Foursquare
				if ( !empty( $options['social_foursquare'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="foursquare"><a href="'.esc_url( $options['social_foursquare'] ).'" title="'.sprintf( esc_attr__( '%s on Foursquare', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' foursquare </a></li>';
				}
				//Flickr
				if ( !empty( $options['social_flickr'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="flickr"><a href="'.esc_url( $options['social_flickr'] ).'" title="'.sprintf( esc_attr__( '%s on Flickr', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Flickr </a></li>';
				}
				//Tumblr
				if ( !empty( $options['social_tumblr'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="tumblr"><a href="'.esc_url( $options['social_tumblr'] ).'" title="'.sprintf( esc_attr__( '%s on Tumblr', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Tumblr </a></li>';
				}
				//deviantART
				if ( !empty( $options['social_deviantart'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="deviantart"><a href="'.esc_url( $options['social_deviantart'] ).'" title="'.sprintf( esc_attr__( '%s on deviantART', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' deviantART </a></li>';
				}
				//Dribbble
				if ( !empty( $options['social_dribbble'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="dribbble"><a href="'.esc_url( $options['social_dribbble'] ).'" title="'.sprintf( esc_attr__( '%s on Dribbble', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Dribbble </a></li>';
				}
				//MySpace
				if ( !empty( $options['social_myspace'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="myspace"><a href="'.esc_url( $options['social_myspace'] ).'" title="'.sprintf( esc_attr__( '%s on MySpace', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' MySpace </a></li>';
				}
				//WordPress
				if ( !empty( $options['social_wordpress'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="wordpress"><a href="'.esc_url( $options['social_wordpress'] ).'" title="'.sprintf( esc_attr__( '%s on WordPress', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' WordPress </a></li>';
				}
				//RSS
				if ( !empty( $options['social_rss'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="rss"><a href="'.esc_url( $options['social_rss'] ).'" title="'.sprintf( esc_attr__( '%s on RSS', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' RSS </a></li>';
				}
				//Delicious
				if ( !empty( $options['social_delicious'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="delicious"><a href="'.esc_url( $options['social_delicious'] ).'" title="'.sprintf( esc_attr__( '%s on Delicious', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Delicious </a></li>';
				}
				//Last.fm
				if ( !empty( $options['social_lastfm'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="lastfm"><a href="'.esc_url( $options['social_lastfm'] ).'" title="'.sprintf( esc_attr__( '%s on Last.fm', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Last.fm </a></li>';
				}
				//Instagram
				if ( !empty( $options['social_instagram'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="instagram"><a href="'.esc_url( $options['social_instagram'] ).'" title="'.sprintf( esc_attr__( '%s on Instagram', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Instagram </a></li>';
				}
				//GitHub
				if ( !empty( $options['social_github'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="github"><a href="'.esc_url( $options['social_github'] ).'" title="'.sprintf( esc_attr__( '%s on GitHub', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' GitHub </a></li>';
				}
				//Vkontakte
				if ( !empty( $options['social_vkontakte'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="vkontakte"><a href="'.esc_url( $options['social_vkontakte'] ).'" title="'.sprintf( esc_attr__( '%s on Vkontakte', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vkontakte </a></li>';
				}
				//My World
				if ( !empty( $options['social_myworld'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="myworld"><a href="'.esc_url( $options['social_myworld'] ).'" title="'.sprintf( esc_attr__( '%s on My World', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' My World </a></li>';
				}
				//Odnoklassniki
				if ( !empty( $options['social_odnoklassniki'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="odnoklassniki"><a href="'.esc_url( $options['social_odnoklassniki'] ).'" title="'.sprintf( esc_attr__( '%s on Odnoklassniki', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Odnoklassniki </a></li>';
				}
				//Goodreads
				if ( !empty( $options['social_goodreads'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="goodreads"><a href="'.esc_url( $options['social_goodreads'] ).'" title="'.sprintf( esc_attr__( '%s on Goodreads', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Goodreads </a></li>';
				}
				//Skype
				if ( !empty( $options['social_skype'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="skype"><a href="'.esc_attr( $options['social_skype'] ).'" title="'.sprintf( esc_attr__( '%s on Skype', 'simple-catch' ),get_bloginfo( 'name' ) ).'">'.get_bloginfo( 'name' ).' Skype </a></li>';
				}
				//Soundcloud
				if ( !empty( $options['social_soundcloud'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="soundcloud"><a href="'.esc_url( $options['social_soundcloud'] ).'" title="'.sprintf( esc_attr__( '%s on Soundcloud', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Soundcloud </a></li>';
				}
				//Email
				if ( !empty( $options['social_email'] ) && is_email($options['social_email'] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="email"><a href="mailto:'.antispambot( sanitize_email( $options['social_email'] ) ).'" title="'.sprintf( esc_attr__( '%s on Email', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Email </a></li>';
				}
				$simplecatch_headersocialnetworks .='
			</ul><div class="clear"></div>';

			set_transient( 'simplecatch_headersocialnetworks', $simplecatch_headersocialnetworks, 86940 );
		}
		echo $simplecatch_headersocialnetworks;
	} // simplecatch_headersocialnetworks
endif;


if ( ! function_exists( 'simplecatch_menu' ) ) :
	/**
	 * Access / Menu
	 */
	function simplecatch_menu() { ?>
		<button id="menu-toggle" class="menu-toggle main-menu-button"><?php _e( 'Menu', 'simple-catch' ); ?><span class="genericon genericon-menu"></span></button>
		<div id="site-header-menu">
			<nav id="access" class="main-navigation menu-header-container clearfix" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'simple-catch' ); ?>">
				<h3 class="assistive-text"><?php _e( 'Primary menu', 'simple-catch' ); ?></h3>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */
				if( has_nav_menu( 'primary', 'simple-catch' ) ) {
				    wp_nav_menu( array(
				        'theme_location' 	=> 'primary',
				        'container' 		=> ''
				    ) );
				} else {
				    wp_page_menu( array(
				        'menu_class'  => 'menu-header-container'
				    ) );
				}
				?>
			</nav><!-- #access -->
		</div><!-- #site-header-menu -->
		<?php
	} // simplecatch_menu
endif;

add_action( 'simplecatch_after_headercontent', 'simplecatch_menu', 15 );


if ( ! function_exists( 'simplecatch_inline_css' ) ) :
	/**
	 * Hooks the Custom Inline CSS to head section
	 *
	 * @since Simple Catch 1.2.3
	 */
	function simplecatch_inline_css() {
		//delete_transient( 'simplecatch_inline_css' );

		if ( ( !$simplecatch_inline_css = get_transient( 'simplecatch_inline_css' ) ) ) {
			// Get theme options
			$options  = simplecatch_get_options();		
			$defaults = simplecatch_defaults_options();		
			$fonts    = simplecatch_available_fonts();

	        $simplecatch_inline_css = '';

			//Color Options
			if( $defaults[ 'heading_color' ] != $options['heading_color'] ) {
				$simplecatch_inline_css	.=  ".site-content .entry-title, .site-content .entry-title a { color: ".  $options['heading_color'] ."; }". "\n";
			}
			if( $defaults[ 'meta_color' ] != $options['meta_color'] ) {
				$simplecatch_inline_css	.=  ".site-content .entry-meta, .site-content .entry-meta a { color: ".  $options['meta_color'] ."; }". "\n";
			}
			if( $defaults[ 'text_color' ] != $options['text_color'] ) {
				$simplecatch_inline_css	.=  ".site-content { color: ".  $options['text_color'] ."; }". "\n";
			}
			if( $defaults[ 'link_color' ] != $options['link_color'] ) {
				$simplecatch_inline_css	.=  ".site-content a { color: ".  $options['link_color'] ."; }". "\n";
			}
			if( $defaults[ 'widget_heading_color' ] != $options['widget_heading_color'] ) {
				$simplecatch_inline_css	.=  "#secondary .widget-title, #secondary .widget-title a, #supplementary .widget-title, #supplementary .widget-title a { color: ".  $options['widget_heading_color'] ."; }". "\n";
			}
			if( $defaults[ 'widget_text_color' ] != $options['widget_text_color'] ) {
				$simplecatch_inline_css	.=  "#secondary .widget,  #supplementary .widget { color: ".  $options['widget_text_color'] ."; }". "\n";
			}

			//Custom CSS Option
			if( !empty( $options['custom_css'] ) ) {
				$simplecatch_inline_css .=  "\n/* Styles From Custom CSS Box */\n" . $options['custom_css'] . "\n";
			}
			//Custom CSS Option End

			//Create Style tag only if catchmustang_inline_css is not empty
			if( '' !=  $simplecatch_inline_css ){
				echo '<!-- refreshing cache -->' . "\n";

				$simplecatch_inline_css = '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n" .
					'<style type="text/css" media="screen">' . "\n" .
						$simplecatch_inline_css .
					'</style>' . "\n";
			}

			set_transient( 'simplecatch_inline_css', $simplecatch_inline_css, 86940 );

		}
		echo $simplecatch_inline_css;
	} // simplecatch_inline_css
endif;
add_action('wp_head', 'simplecatch_inline_css');


if ( ! function_exists( 'simplecatch_custom_tag_cloud' ) ) :
/*
 * Function for showing custom tag cloud
 */
function simplecatch_custom_tag_cloud() {
?>
	<div class="custom-tagcloud"><?php wp_tag_cloud('smallest=12&largest=12px&unit=px'); ?></div>
<?php
} //simplecatch_custom_tag_cloud
endif;


if ( ! function_exists( 'simplecatch_footer_sidebar_class' ) ) :
/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function simplecatch_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'widget-area one wrapper clearfix';
			break;
		case '2':
			$class = 'widget-area two wrapper clearfix';
			break;
		case '3':
			$class = 'widget-area three wrapper clearfix';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}
// simplecatch_footer_sidebar_class
endif;

if ( ! function_exists( 'simplecatch_footercontent' ) ) :
/**
 * shows footer content
 */
function simplecatch_footercontent() {
	//delete_transient( 'simplecatch_footercontent' );

	if ( !$simplecatch_footercontent = get_transient( 'simplecatch_footercontent' ) ) {
		echo '<!-- refreshing cache -->';

		$defaults = simplecatch_defaults_options();

		$footer_code = $defaults['footer_code'];

		$search = array( '[footer-image]', '[the-year]', '[site-link]', '[wp-link]', '[theme-link]' );

        $replace = array( simplecatch_footer_image(), simplecatch_the_year(), simplecatch_site_link(), simplecatch_wp_link(), simplecatch_theme_link() );

        $footer_code = str_replace( $search, $replace, $footer_code );

        $simplecatch_footercontent = '<div id="site-generator"><div class="wrapper clearfix">' . $footer_code . '</div></div><!-- .wrapper -->';

    	set_transient( 'simplecatch_footercontent', $simplecatch_footercontent, 86940 );
    }
	
	echo do_shortcode( $simplecatch_footercontent );
} // simplecatch_footercontent
endif;
add_action( 'simplecatch_footer', 'simplecatch_footercontent', 15 );


/**
 * Function to pass the slider value
 */
function simplecatch_pass_slider_value() {
	// Get theme options
	$options = simplecatch_get_options();

	$transition_effect   = $options['transition_effect'];
	$transition_delay    = $options['transition_delay'] * 1000;
	$transition_duration = $options['transition_duration'] * 1000;
	wp_localize_script(
		'simplecatch_slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}// simplecatch_pass_slider_value


if ( ! function_exists( 'simple_catch_alter_home' ) ) :
	/**
	 * Alter the query for the main loop in home page
	 * @uses pre_get_posts hook
	 */
	function simple_catch_alter_home( $query ){
		if ( $query->is_main_query() && $query->is_home() ) {
			// Get theme options
			$options = simplecatch_get_options();
			$cats    = $options['front_page_category'];

		    if ( '0' != $options['exclude_slider_post'] && !empty( $options['featured_slider'] ) ) {
				$query->query_vars['post__not_in'] = $options['featured_slider'];
			}

			if ( is_array( $cats ) && !in_array( '0', $cats ) ) {
				$query->query_vars['category__in'] = $options['front_page_category'];
			}
		}
	}
endif;
add_action( 'pre_get_posts','simple_catch_alter_home' );

if ( ! function_exists( 'simplecatch_class_names' ) ) :
/**
 * Add specific CSS class by filter
 * @uses body_class filter hook
 * @since Simple Catch 1.3.2
 */
function simplecatch_class_names($classes) {
	// Get theme options
	$options = simplecatch_get_options();

	//Content Layouts
	$content_layout = $options['content_layout'];
	if ( 'excerpt' == $content_layout  ) {
		$classes[] = 'layout-excerpt';
	}

	$classes[] = simplecatch_get_theme_layout();

	if ( '1' == $options['disable_responsive'] ) {
		$classes[] = 'fixed-layout';
	}

	return $classes;
} //simplecatch_class_names
endif;

add_filter( 'body_class', 'simplecatch_class_names' );


if ( ! function_exists( 'simplecatch_content' ) ) :
	/**
	 * Display the page/post content
	 * @since Simple Catch 1.0
	 */
	function simplecatch_content() {
		get_header();

				while ( have_posts() ):the_post();

					if ( function_exists( 'simplecatch_loop') ) simplecatch_loop();

					comments_template();

				endwhile; ?>

			</div><!-- #main -->

	    <?php
	    /**
	     * simplecatch_below_primary hook
	     */
	    do_action( 'simplecatch_below_primary' );
	    ?>

		<?php

		get_sidebar();

		get_footer();

	} // simplecatch_content
endif;


if ( ! function_exists( 'simplecatch_loop' ) ) :
	/**
	 * Display the page/post loop part
	 * @since Simple Catch 1.3.2
	 */
	function simplecatch_loop() {

		if ( is_page() ): ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
	            <div class="entry-content clearfix">
					<?php the_content();
	                // copy this <!--nextpage--> and paste at the post content where you want to break the page
	                 wp_link_pages(array(
	                        'before'			=> '<div class="pagination">Pages: ',
	                        'after' 			=> '</div>',
	                        'link_before' 		=> '<span>',
	                        'link_after'       	=> '</span>',
	                        'pagelink' 			=> '%',
	                        'echo' 				=> 1
	                ) ); ?>
	           	</div><!-- .entry-content -->
			</article><!-- #post-## -->

	    <?php elseif ( is_single() ): ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	        	<header class="entry-header">
	                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	                <div class="entry-meta">
	                    <ul class="clearfix">
	                        <li class="no-padding-left author vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr(get_the_author_meta( 'display_name' ) ); ?>" rel="author"><?php _e( 'By', 'simple-catch' ); ?>&nbsp;<?php the_author_meta( 'display_name' );?></a></li>
	                        <li class="entry-date updated"><?php echo get_the_modified_date(); ?></li>
	                        <li><?php comments_popup_link( __( 'No Comments', 'simple-catch' ), __( '1 Comment', 'simple-catch' ), __( '% Comments', 'simple-catch' ) ); ?></li>
	                    </ul>
	                </div>
	           	</header>
	            <div class="entry-content clearfix">
					<?php the_content();
	                // copy this <!--nextpage--> and paste at the post content where you want to break the page
	                 wp_link_pages(array(
	                        'before'			=> '<div class="pagination">Pages: ',
	                        'after' 			=> '</div>',
	                        'link_before' 		=> '<span>',
	                        'link_after'       	=> '</span>',
	                        'pagelink' 			=> '%',
	                        'echo' 				=> 1
	                    ) );
	                ?>
				</div>
	            <footer class="entry-meta">
	            	<?php
	                $tag = get_the_tags();
	                if (! $tag ) { ?>
	                    <div class='tags'><?php _e( 'Categories: ', 'simple-catch' ); ?> <?php the_category(', '); ?> </div>
	                <?php } else {
	                   	the_tags( '<div class="tags"> ' . __('Tags', 'simple-catch') . ': ', ', ', '</div>');
	                } ?>
	           	</footer>
			</article><!-- #post-## -->

		<?php endif;
	} // simplecatch_loop
endif;


if ( ! function_exists( 'simplecatch_comment_form_fields' ) ) :
	/**
	 * Altering Comment Form Fields
	 * @uses comment_form_default_fields filter
	 */
	function simplecatch_comment_form_fields( $fields ) {
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

	    $fields['author'] = '<label for="author">' . __('Name','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Name', 'simple-catch' ) .'&nbsp;'. ( $req ? esc_attr__( '( required )', 'simple-catch' ) : '' ) .'" name="author"'. $aria_req .' />';
		$fields['email'] = '<label for="email">' . __('Email','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Email', 'simple-catch' ) .'&nbsp;'. ( $req ? esc_attr__( '( required )', 'simple-catch' ) : '' ) .'" name="email"'. $aria_req .' />';
		$fields['url'] = '<label for="url">' . __('Website','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Website', 'simple-catch' ) .'" name="url"'. $aria_req .' />';

	    return $fields;
	} // simplecatch_comment_form_fields
	endif;
add_filter( 'comment_form_default_fields', 'simplecatch_comment_form_fields' );


if ( ! function_exists( 'simplecatch_comment_form_defaults' ) ) :
	/**
	 * Altering Comment Form Defaults
	 *
	 * @uses comment_form_defaults filter
	 */
	function simplecatch_comment_form_defaults( $defaults ) {

		$defaults['comment_notes_before'] = '';
		$defaults['comment_notes_after'] = '';
		$defaults['title_reply'] = esc_html__( 'Leave a Comment', 'simple-catch' );

		return $defaults;
	} // simplecatch_comment_form_defaults
	endif;
add_filter( 'comment_form_defaults', 'simplecatch_comment_form_defaults' );


/**
 * Adds in post and Page ID when viewing lists of posts and pages
 * This will help the admin to add the post ID in featured slider
 *
 * @param mixed $post_columns
 * @return post columns
 */
function simplecatch_post_id_column( $post_columns ) {
	$beginning           = array_slice( $post_columns, 0 ,1 );
	$beginning['postid'] = esc_html__( 'ID', 'simple-catch'  );
	$ending              = array_slice( $post_columns, 1 );
	$post_columns        = array_merge( $beginning, $ending );
	return $post_columns;
}
add_filter( 'manage_posts_columns', 'simplecatch_post_id_column' );
add_filter( 'manage_pages_columns', 'simplecatch_post_id_column' );

function simplecatch_posts_id_column( $col, $val ) {
	if( 'postid' == $col  ) echo $val;
}
add_action( 'manage_posts_custom_column', 'simplecatch_posts_id_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'simplecatch_posts_id_column', 10, 2 );

function simplecatch_posts_id_column_css() {
	echo '
	<style type="text/css">
	    #postid { width: 80px; }
	    @media screen and (max-width: 782px) {
	        .wp-list-table #postid, .wp-list-table #the-list .postid { display: none; }
	        .wp-list-table #the-list .is-expanded .postid {
	            padding-left: 30px;
	        }
	    }
    </style>';
}
add_action( 'admin_head-edit.php', 'simplecatch_posts_id_column_css' );


if ( ! function_exists( 'simplecatch_pagemenu_alter' ) ) :
/**
 * Add default navigation menu to page menu
 * Used while viewing on smaller screen
 */
function simplecatch_pagemenu_alter( $output ) {
	$output .= '<li class="default-menu"><a href="' . esc_url( home_url( '/' ) ) . '" title="Menu">'.__( 'Menu', 'simple-catch' ).'</a></li>';
	return $output;
}
endif; // simplecatch_pagemenu_alter
add_filter( 'wp_list_pages', 'simplecatch_pagemenu_alter' );


if ( ! function_exists( 'simplecatch_pagemenu_filter' ) ) :
/**
 * @uses wp_page_menu filter hook
 */
function simplecatch_pagemenu_filter( $text ) {
	$replace = array(
		'current_page_item'	=> 'current-menu-item'
	);

	$text = str_replace( array_keys( $replace ), $replace, $text );
  	return $text;

}
endif; // simplecatch_pagemenu_filter
add_filter('wp_page_menu', 'simplecatch_pagemenu_filter');


if ( !function_exists( 'simplecatch_infinite_scroll_render' ) ):
/**
 * Set the code to be rendered on for calling posts,
 * hooked to template parts when possible.
 *
 * Note: must define a loop.
 */
function simplecatch_infinite_scroll_render() {
   get_template_part( 'content' );
} // simplecatch_infinite_scroll_render
endif;


if ( ! function_exists( 'simplecatch_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Catch Everest 1.0
 */
function simplecatch_content_nav( $nav_id ) {
	/**
	 * Check Jetpack Infinite Scroll
	 * if it's active then disable pagination
	 */
	if ( class_exists( 'Jetpack', false ) ) {
		$jetpack_active_modules = get_option('jetpack_active_modules');
		
		if ( $jetpack_active_modules && in_array( 'infinite-scroll', $jetpack_active_modules ) ) {
			return false;
		}
	}

	if ( function_exists('wp_pagenavi' ) ) {
		// Checking WP-PageNaviplugin exist
		wp_pagenavi();
	} elseif ( function_exists('wp_page_numbers' ) ) {
		// Checking WP Page Numbers plugin exist
		wp_page_numbers();
	} else {
		the_posts_pagination( array(
			'prev_text'          => esc_html__( 'Previous', 'simple-catch' ),
			'next_text'          => esc_html__( 'Next', 'simple-catch' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'simple-catch' ) . ' </span>',
		) );
	}
}
endif; // simplecatch_content_nav


/**
 * This function loads Scroll Up Navigation
 *
 * @get the data value from theme options for disable
 * @uses simplecatch_after action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function simplecatch_scrollup() {
	$options = simplecatch_get_options();

	$output = '';
	
	if ( empty( $options['disable_scrollup'] ) ) {
		$output =  '<a href="#masthead" id="scrollup"></a>' ;
	}

	echo $output;
}
add_action( 'simplecatch_after', 'simplecatch_scrollup', 10 );


/*
 * Clearing the cache if any changes in Admin Theme Option
 */
function simplecatch_flush_transients(){
	delete_transient( 'simplecatch_slider_display' ); // featured slider
	delete_transient( 'simplecatch_headersocialnetworks' );  // Social links on header
	delete_transient( 'simplecatch_footercode' ); // scripts which loads on footer
	delete_transient( 'simplecatch_inline_css' ); // Custom Inline CSS
	delete_transient( 'simplecatch_footercontent' ); // Footer content
}


/*
 * Clearing the cache if any changes in post or page
 */
function simplecatch_post_invalidate_caches(){
	delete_transient( 'simplecatch_slider_display' ); // featured slider
}
//Add action hook here save post
add_action( 'save_post', 'simplecatch_post_invalidate_caches' );