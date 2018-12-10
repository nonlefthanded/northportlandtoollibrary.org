<?php
/**
 * Slider display
 *
 *
 * @package Catch Themes
 * @subpackage Simple Catch
 * @since Simple Catch 3.7
 */

if ( ! function_exists( 'simplecatch_slider_display' ) ) :
	/**
	 * Display slider
	 */
	function simplecatch_slider_display() {
		global $wp_query;

		// Get theme options
		$options      = simplecatch_get_options();

		// Front page displays in Reading Settings
		$page_for_posts = get_option( 'page_for_posts' );

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		$output = '';


		if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {

			if ( !$output = get_transient( 'simplecatch_slider_display' ) ) {		
				$output = '
				<div id="main-slider" class="post-slider">
					<div class="featured-slider">';

				$output .= simplecatch_page_post_category_slider( $options );

				$output .= '
					</div> <!-- .featured-slider -->
					
					<div id="controllers">
					</div><!-- #controllers -->
				
				</div><!-- #main-slider -->';
			}

			set_transient( 'simplecatch_slider_display', $output, 86940 );
		}

		echo $output;
	} // simplecatch_slider_display
endif;
add_action( 'simplecatch_after_headercontent', 'simplecatch_slider_display', 20 );


if ( ! function_exists( 'simplecatch_page_post_category_slider' ) ) :
	/**
	 * This function to display featured posts on homepage header
	 *
	 * @get the data value from theme options
	 * @displays on the homepage header
	 *
	 * @useage Featured Image, Title and Content of Post
	 */
	function simplecatch_page_post_category_slider( $options ) {
		$postperpage  = $options['slider_qty'];
		$slidereffect = $options['remove_noise_effect'];
		$output       = '';

		$args = array();

		$args = array(
			'posts_per_page'      => $postperpage,
			'post__in'            => $options['featured_slider'],
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => 1 // ignore sticky posts
		);

		$loop = new WP_Query( $args );

		$i=0; 

		while ( $loop->have_posts() ) {
			$loop->the_post(); 

			$i++;
			
			$title_attribute = the_title_attribute( 'echo=0' );
			
			$excerpt = get_the_excerpt();
			
			if ( $i == 1 ) { 
				$classes = "slides displayblock"; 
			} else { 
				$classes = "slides displaynone"; 
			}
			
			$output .= '
			<div class="' . $classes . '">
				<article id="post-' . esc_attr( get_the_ID() ) . '" class="type-post hentry">
					<div class="post-thumbnail featured-img">';
						if( has_post_thumbnail() ) {

							$output .= '<a href="' . esc_url( get_permalink() ) . '" title="' . $title_attribute . '">';

							if( $slidereffect == "0" ) {
								$output .= '<span class="img-effect pngfix"></span>';
							}

							$output .= get_the_post_thumbnail( get_the_ID(), 'slider', array( 'title' => $title_attribute, 'alt' => $title_attribute ) ).'</a>';
						}
						else {
							$output .= '<span class="img-effect pngfix"></span>';
						}
					$output .= '
					</div> <!-- .post-thumbnail -->
					<div class="entry-container featured-text">';
						if( $excerpt !='') {
						$output .= the_title( '<span class="slider-title">','</span>', false ).'<span class="slider-sep">: </span><span class="slider-content">'.$excerpt.'</span>';
						}
						$output .= '
					</div><!-- .entry-container -->
				</article><!-- .featured-text -->
			</div> <!-- .slides -->';
		}

		wp_reset_postdata();

		return $output;
	} // simplecatch_page_post_category_slider
endif;