<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package 
 */
 
get_template_part('modules/header/head'); ?>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

    <?php get_template_part('modules/header/jumbosearch'); ?>
    <?php get_template_part('modules/header/top-bar'); ?>
	<?php get_template_part('modules/header/masthead'); ?>	
	<?php get_template_part('framework/featured-components/posts-beta'); ?>
	<?php get_template_part('framework/featured-components/posts-cat'); ?>

	<?php if( class_exists('rt_slider') ) {
			 rt_slider::render('slider', 'nivo' ); 
		} ?>

	<?php do_action('subsimple-after-header'); ?>
	
	<div class="mega-container">
	
		<div id="content" class="site-content container">