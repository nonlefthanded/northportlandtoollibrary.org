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
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>

<body id="home">
	<div id="container">
    
    	<div id="latest">
            
            
                 <aside class="sidebar">
              

              
        <?php get_sidebar('') ?>
        

        </aside>
                   
            
		<section class="left-col">
			
			
            <?php if ( have_posts() ) : ?>
			
            <div id="featured">

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>
			<!-- twentyeleven_content_nav( 'nav-below' ); -->
			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found'); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.'); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
                        <?php twentyeleven_content_nav( 'nav-above' ); ?>
            
			</div> <!-- END Featured -->
                  
              
               
      
			</section> <!-- END Left Column -->	
			</div> <!-- END Latest -->
            
              <aside class="sidebar">
           
        <?php get_sidebar('2') ?>
              </aside>
            
			<div class="clearfix"></div>
			<hr/>
            
			<div id="latest">
			
				
			</div> <!-- END Latest -->
            
			<div class="clearfix"></div>
			<hr/>
			<div id="about">
				<h3>About</h3>
				<p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec id elit non mi porta gravida at eget metus.<br/><br/>

				Sed posuere consectetur est at lobortis. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Curabitur blandit tempus porttitor. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed posuere consectetur est at lobortis. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
			</div>	
		</section>	
		<hr/>	
        
            
   
            
      
      <?php get_footer(); ?>
	
		            
                 
                    
	</div> <!-- END Container -->
    
	<!-- comments_template( '', true ); -->

	
	</body>


