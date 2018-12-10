<?php
/**
 * The template for displaying the footer.
 *
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 1.0
 */
?>
	</div> <!-- .wrapper -->
</div> <!-- .site-content -->
<?php 
/** 
 * catchthemes_after_main hook
 */
do_action( 'simplecatch_after_main' ); 
?>

<footer id="colophon" class="site-footer" role="contentinfo">
 
       <?php 
       /** 
         * simplecatch_footer hook
         *
         * @hooked simplecatch_footercontent - 15
         */
       do_action( 'simplecatch_footer' ); ?> 

</footer><!-- #colophon -->  

<?php 
/** 
 * simplecatch_after hook
 *
 * @hooked simplecatch_scrollup - 15
 */
do_action( 'simplecatch_after' );
?>
</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>