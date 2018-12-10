<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package 
 */

get_header(); ?>

	<div id="primary" class="content-areas <?php do_action('subsimple_primary-width') ?>">
		<?php if ( is_home() ) : ?>
            <div class="section-title"><span><?php _e("RECENT ARTICLES", 'subsimple'); ?></span></div>
        <?php endif; ?>
		
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

			<?php the_posts_pagination( array( 'mid_size' => 2 )); ?>

		<?php else : ?>

			<?php get_template_part( '/modules/content/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
