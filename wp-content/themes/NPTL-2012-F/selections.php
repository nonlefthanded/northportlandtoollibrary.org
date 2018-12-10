<?php get_header('org'); rewind_posts(); ?>

<div>

		<?php 
		global $wpdb;
		global $post;

		// custom query to get posts of typer org order by trimming the leading "The " in a title
		$orgposts = $wpdb->get_results("SELECT wposts.* 
    FROM $wpdb->posts as wposts
    WHERE wposts.post_type = 'selection' and wposts.post_status='publish' 
    ORDER BY TRIM(LEADING 'The ' FROM wposts.post_title)", OBJECT);
    
    
		$default_thumb = get_bloginfo('template_url') . "/images/default-thumb.jpg";
?>



		<?php if ($orgposts): ?>
 	
 	 
		<h1>PHOTOGRAPHY SELECTIONS</h1>
		<p><a href="#">more about featured itemsooo...</a></p>
		
	<div class="content">
	
		<?php foreach ($orgposts as $post): ?>
		<?php setup_postdata($post); ?>

<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="400"><?php get_the_image(); ?></td>
    <td width="400"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s','gpp_i18n'),the_title_attribute('echo=0')); ?>"><?php the_title() ?></a></h2>
<?php get_the_image(); ?>
<?php the_excerpt(); ?><br /><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s','gpp_i18n'),the_title_attribute('echo=0')); ?>"><img src="http://www.webstore.akomplice-clothing.com/webstore-images-akomplice/webstore-akomplice-detail-purchase.jpg" border="0"/></a></td>
  </tr>
</table>

<hr />
 <?php endforeach; ?>


<div class="clear"></div>

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found','gpp_i18n'); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>
</div>
		
<?php get_sidebar('feature'); ?>

<!-- Begin Footer -->
<?php get_footer(); ?>