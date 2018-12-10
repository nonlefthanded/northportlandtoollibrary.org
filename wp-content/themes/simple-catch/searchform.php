<?php
/**
 * The template for displaying search forms in Simple Catch
 *
 * @package Catch Themes
 * @subpackage Simple_Catch_Pro
 * @since Simple Catch 1.0
 */
// Get theme options
$options = simplecatch_get_options();

$display_text = $options['search_display_text'];
$button_text  = $options['search_button_text'];
?>
<form method="get" class="searchform clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-box">
		<input type="text" class="search" value="<?php echo esc_attr( $display_text ); ?>" name="s" id="s" title="<?php echo esc_attr( $display_text ); ?>" />
   	</div>
    
    <button><?php echo $button_text; ?></button>
</form>