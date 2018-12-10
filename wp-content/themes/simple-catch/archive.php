<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 1.0
 */
 
get_header(); 

			get_template_part( 'content' ); ?>
        
	</div><!-- #primary -->

    <?php
    /** 
     * simplecatch_below_primary hook
     */
    do_action( 'simplecatch_below_primary' ); 
    ?>    
            
	<?php get_sidebar(); ?>
            
        
<?php get_footer(); ?>