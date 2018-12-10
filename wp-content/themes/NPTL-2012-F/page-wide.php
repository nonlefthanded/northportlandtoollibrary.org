<?php
/*
Template Name: Wide Page
*/
?>
<?php get_header(); ?>
<div class="span-24 first last">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php if(function_exists('post_class')) : ?><?php post_class(); ?><?php else : ?>class="post post-<?php the_ID(); ?>"<?php endif; ?>>
			<h2><?php the_title(); ?></h2>
				<?php if ( !post_password_required() ) { ?>
					
				<?php } ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div>
		<div class="clear"></div>
		<?php endwhile; endif; ?>
	<?php edit_post_link(__('Edit this entry'), '<p>', '.</p>'); ?>
</div>
<div class="clear"></div>

<?php get_footer(); ?>

