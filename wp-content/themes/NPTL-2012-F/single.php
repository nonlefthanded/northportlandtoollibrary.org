<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="wrapper">
	
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h1 class="section-heading"><?php _e( 'Post navigation', 'toolbox' ); ?></h1>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '&larr; Previous') ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next &rarr;') ); ?></span>
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content', 'single' ); ?>

					<?php twentyeleven_content_nav( 'nav-below' ); ?>

					<!--comments_template( '', true ); -->

				<?php endwhile; // end of the loop. ?>

	

<?php get_footer(); ?>