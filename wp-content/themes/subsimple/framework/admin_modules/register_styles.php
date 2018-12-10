<?php
/**
 * Enqueue scripts and styles.
 */
function subsimple_scripts() {
	
	wp_enqueue_style( '-style', get_stylesheet_uri() );
	
	wp_enqueue_style('-title-font', '//fonts.googleapis.com/css?family='.str_replace(" ", "+", get_theme_mod('subsimple_title_font', 'Nunito') ).':100,300,400,700' );
	
	wp_enqueue_style('-body-font', '//fonts.googleapis.com/css?family='.str_replace(" ", "+", get_theme_mod('subsimple_body_font', 'Alegreya') ).':100,300,400,700' );
	
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css' );
		
	wp_enqueue_style( 'nivo-slider', get_template_directory_uri() . '/assets/css/nivo-slider.css' );
	
	wp_enqueue_style( 'nivo-skin', get_template_directory_uri() . '/assets/css/nivo-default/default.css' );
	
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
	
	wp_enqueue_style( 'hover-style', get_template_directory_uri() . '/assets/css/hover.min.css' );

    wp_enqueue_style( '-main-theme-style', get_template_directory_uri() . '/assets/theme-styles/css/'.get_theme_mod('subsimple_skin', 'default').'.css', array(), null );
	
	wp_enqueue_script( '-external', get_template_directory_uri() . '/assets/js/external.js', array('jquery'), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_enqueue_script( '-custom-js', get_template_directory_uri() . '/assets/js/custom.js', array('jquery-masonry'), false, true );
	
}
add_action( 'wp_enqueue_scripts', 'subsimple_scripts' );