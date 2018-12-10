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
<?php $pinfo = midea_get_project_info(get_the_ID());?>
		<div id="container">
        <hr class="page-hr" />
			<div id="welcome" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<!-- comments_template( '', true ); -->

	<div id="featured-dailyphoto-sidebar">
		  <p><a href="http://www.johnjohnchao.com/daily">Daily Photos</a></p>
		
		<?php $pinfo = midea_get_project_info(get_the_ID());?>
		
		<?php
		// show info for MIDEA member
		
		// set up query to get org name
		$my_query = "post_type=daily&orderby=ID&p=" . $pinfo['daily'] . "&posts_per_page=4";
		$showOrg = new WP_Query();
		$showOrg->query($my_query);
	
		?>
		
		
		<?php
		while ($showOrg->have_posts()) : $showOrg->the_post(); 
			get_the_image( array( 'link_to_post' => true, 'class' => 'thumbnail', 'width' => '175', 'height' => '175' ) );
			
			?>
            
	
    <!-- $ourl = get_post_meta($post->ID, 'midea-daily-web', true);
 echo '<p>Location: ' . $ourl . '</p>' -->
			
		<?php endwhile;
		?>
		<br />
		</div> 
        
          <div class="featured-websites-sidebar">
        
        <p><a href="http://www.johnjohnchao.com/portfolio/websites">Websites</a></p>
		<?php
		// show info for MIDEA member
		
		// set up query to get org name
		$my_query = "post_type=project&orderby=rand&p=" . $pinfo['project'] . "&posts_per_page=3";
		$showOrg = new WP_Query();
		$showOrg->query($my_query);
	
		?>
		
		
		<?php
		while ($showOrg->have_posts()) : $showOrg->the_post(); 
			get_the_image( array( 'link_to_post' => true, 'class' => 'thumbnail', 'width' => '200', 'height' => '113' ) );
		endwhile;
		?>
        
        </div>



			</div><!-- #content -->
		</div><!-- #primary -->
        <?php get_sidebar('') ?>


<?php get_footer(); ?>