<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

        <div id="col1">
			<!-- Column one start -->
			<nav id="access" role="navigation">
            
              <div id="masthead2">
              
                <?php include ('nav.php'); ?>
                
              </div>
              
			</nav>
			<!-- Column one end -->
		</div>
        
			<div id="contents">
          
	
		<div id="col2">
			<!-- Column two start -->
		<?php the_post(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php get_template_part( 'content', 'page' ); ?>

				<!-- comments_template( '', true ); -->
                
                
                           <?php get_footer(); ?>
		</div>
	</div>
</div>
            
            
				
                
			</div><!-- #content -->
 
		</div><!-- #container1 -->

		</div><!-- #container2 -->

