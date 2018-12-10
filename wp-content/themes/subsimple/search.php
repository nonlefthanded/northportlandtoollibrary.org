<?php
/**
 * The template for displaying search results pages.
 *
 * @package 
 */

get_header(); ?>

	<section id="primary" class="<?php do_action('subsimple_primary-width') ?> content-area">
		<header class="page-header">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'subsimple' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header><!-- .page-header -->
			
		<main id="main" class="site-main <?php do_action('subsimple_masonry_class') ?>" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
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
		
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
