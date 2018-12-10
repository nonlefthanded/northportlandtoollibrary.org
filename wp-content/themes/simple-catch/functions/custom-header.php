<?php
/**
 *
 * @package Catch Themes
 * @subpackage Simple Catch
 * @since Simple Catch 3.0
 */

/**
 * Setup the WordPress core custom header feature
 *
 * @package Simple Catch
 */
function simplecatch_custom_header_setup() {
	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => '444',

		// Set height and width, with a maximum value for the width.
		'height'                 => 200,
		'width'                  => 978,

		// Support flexible height and width.
		'flex-height'            => true,
		'flex-width'             => true,

		// Random image rotation off by default.
		'random-default'         => false,

		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'       => 'simplecatch_header_style',
	);

	$args = apply_filters( 'simplecatch_custom_header_args', $args );

	/*
	 * This theme supports custom header for logo
	 *
	 */
	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'simplecatch_custom_header_setup' );


if ( ! function_exists( 'simplecatch_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Simple Catch 2.7
 */
function simplecatch_header_style() {

	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $text_color )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		.site-branding-text {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
			padding: 0;
		}
	<?php

		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // simplecatch_header_style


if ( ! function_exists( 'simplecatch_custom_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @since Simple Catch 2.7
 */
function simplecatch_custom_header_image() {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : ?>
        <div id="headimg">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                <img src="<?php echo esc_url( $header_image ); ?>" alt="" />
            </a>
        </div><!-- #headimg -->
	<?php endif;

}
endif; // simplecatch_custom_header_image

add_action( 'simplecatch_after_headercontent', 'simplecatch_custom_header_image', 10 );