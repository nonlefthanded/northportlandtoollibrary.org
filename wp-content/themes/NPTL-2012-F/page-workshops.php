<?php
/*
Template Name: Workshops
*/ get_header(); ?>

        <div id="col1">
			<!-- Column one start -->
			<nav id="access" role="navigation">
            
              <div id="masthead2">
              
                <?php include ('nav-4workshops.php'); ?>
                
              </div>
              
			</nav>
			<!-- Column one end -->
		</div>
        
			<div id="contents">
          
	
		<div id="col2">
			<!-- Column two start -->
		<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<!-- comments_template( '', true ); -->
                
                
                           <?php get_footer(); ?>
		</div>
	</div>
</div>
            
            
				
                
			</div><!-- #content -->
 
		</div><!-- #container1 -->

		</div><!-- #container2 -->

