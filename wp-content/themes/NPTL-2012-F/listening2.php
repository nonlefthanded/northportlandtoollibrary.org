<?php get_header(); rewind_posts(); ?>

<div>

		<?php 
		global $wpdb;
		global $post;

		// custom query to get posts of typer org order by trimming the leading "The " in a title
		$orgposts = $wpdb->get_results("SELECT wposts.* 
    FROM $wpdb->posts as wposts
    WHERE wposts.post_type = 'listening2' and wposts.post_status='publish' 
    ORDER BY TRIM(LEADING 'The ' FROM wposts.post_title)", OBJECT);
    
    
		$default_thumb = get_bloginfo('template_url') . "/images/default-thumb.jpg";
?>



		<?php if ($orgposts): ?>
		
	<div class="content">
	
		<?php foreach ($orgposts as $post): ?>
		<?php setup_postdata($post); ?>

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s'),the_title_attribute('echo=0')); ?>"><?php the_title() ?></a></h2>
<?php get_the_image(); ?>
<?php the_excerpt(); ?><br /><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s'),the_title_attribute('echo=0')); ?>">View <?php the_title() ?>...</a>

<hr />
 <?php endforeach; ?>


<div class="clear"></div>

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found'); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>
</div>
		
<?php get_sidebar('listening2'); ?>

<!-- Begin Footer -->
<?php get_footer(); ?>