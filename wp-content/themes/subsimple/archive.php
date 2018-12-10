<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package 
 */

get_header(); ?>

	<div id="primary" class="content-area <?php do_action('subsimple_primary-width') ?>">
		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
			
		<main id="main" class="site-main <?php do_action('subsimple_masonry_class') ?>" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 */
					do_action('subsimple_blog_layout'); 
				?>

			<?php endwhile; ?>

			<?php //the_posts_pagination( array( 'mid_size' => 2 ));; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
		
		<?php if ( have_posts() ) { the_posts_pagination( array( 'mid_size' => 2 ));; } ?>
		
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
