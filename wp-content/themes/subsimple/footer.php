<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package 
 */
?>

	</div><!-- #content -->

	<?php get_sidebar('footer'); ?>

	<footer id="colophon" class="site-footer title-font" role="contentinfo">
		<div class="site-info container">
			<?php printf( __( 'Theme Designed by %1$s.', 'subsimple' ), '<a href="'.esc_url("http://inkhive.com/").'" rel="nofollow">InkHive</a>' ); ?>
			<span class="sep"></span>
			<?php echo ( get_theme_mod('subsimple_footer_text') == 'subsimple' ) ? ('&copy; '.date_i18n( __( 'Y', 'subsimple' ) ).' '.get_bloginfo('name').__('. All Rights Reserved. ','subsimple')) : esc_html(get_theme_mod('subsimple_footer_text')); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>
