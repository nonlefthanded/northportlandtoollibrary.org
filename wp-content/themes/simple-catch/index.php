<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a Simple Catch theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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