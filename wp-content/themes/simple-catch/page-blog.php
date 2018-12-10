<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Blog Template
 *
   Template Name: Blog
 *
 * The template for Blog
 *
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 2.1
 */
get_header();
$options    = simplecatch_get_options();
$moretag    = $options['more_tag_text'];
$paged      = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$blog_query = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged ) );

	if ( $blog_query->have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header><!-- .page-header -->

        <?php /* Start the Loop */ ?>
		<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

        	 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            	<?php
				$format = get_post_format();
				//If category has thumbnail it displays thumbnail and excerpt of content else excerpt only
				if ( has_post_thumbnail() && ( false === $format ) ) : ?>
                    <div class="post-thumbnail post-thumb no-margin-left">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'featured' ); ?>
                        </a>
                    </div><!-- .post-thumbnail -->
					<?php
				endif; ?>

                <div class="entry-container post-article">
                    <header class="entry-header">
                        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                        <div class="entry-meta">
                            <ul class="clearfix">
                                <li class="no-padding-left author vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr(get_the_author_meta( 'display_name' ) ); ?>" rel="author"><?php _e( 'By', 'simple-catch' ); ?>&nbsp;<?php the_author_meta( 'display_name' );?></a></li>
                                <li class="entry-date updated"><?php $simplecatch_date_format = get_option( 'date_format' ); the_time( $simplecatch_date_format ); ?></li>
                                <li class="last"><?php comments_popup_link( __( 'No Comments', 'simple-catch' ), __( '1 Comment', 'simple-catch' ), __( '% Comments', 'simple-catch' ) ); ?></li>
                            </ul>
                        </div> <!-- .entry-meta -->
                    </header> <!-- .entry-header -->

                    <?php $simplecatch_excerpt = get_the_excerpt();
                    if ( !empty( $simplecatch_excerpt ) && ( false === $format ) ) :
                        echo '<div class="entry-summary">';
                                the_excerpt();
                        echo '</div><!-- .entry-summary --> ';
                    else :
                        echo '<div class="entry-content">';
                                the_content( $moretag );
                        echo '</div><!-- .entry-content --> ';
                    endif; ?>

                </div><!-- .entry-container -->

            </article><!-- #post-## -->

      	<?php endwhile;

        // Pagination start
        if ( $blog_query->max_num_pages > 1 ) { 
            if ( function_exists('wp_pagenavi' ) )  {
                wp_pagenavi();
            }
            elseif ( function_exists('wp_page_numbers' ) ) {
                wp_page_numbers();
            }
            else { ?>
                <ul class="default-wp-page clearfix">
                    <li class="previous"><?php next_posts_link( __( 'Previous', 'simple-catch' ), $blog_query->max_num_pages ); ?></li>
                    
                    <li class="next"><?php previous_posts_link( __( 'Next', 'simple-catch' ) ); ?></li>
                </ul>
            <?php
            } ?>
        <?php
        };

	else : ?>
        <article id="post-not-found" <?php post_class(); ?>>
            <div class="entry-container post">
                <header class="entry-header">
                    <h1><?php _e( 'Not found', 'simple-catch' ); ?></h1>
                </header>
                <div class="entry-content clearfix">
                    <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'simple-catch' ); ?></p>
                    <?php get_search_form(); ?>
                </div> <!-- .entry-content -->
            </div>
            <div class="clear"></div>
        </article><!-- .post -->

		<?php endif;
        wp_reset_postdata();
        ?>
	</div><!-- #primary -->

    <?php
    /**
     * simplecatch_below_primary hook
     */
    do_action( 'simplecatch_below_primary' );
    ?>

	<?php get_sidebar(); ?>


<?php get_footer(); ?>