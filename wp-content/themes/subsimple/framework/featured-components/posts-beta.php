<?php if(get_theme_mod('subsimple_featposts_enable') && is_front_page()): ?>
<div id="featured-beta" class="featured-section-area">
	<div class="container">
		    <div class="featured-news-container">
                <?php if(get_theme_mod('subsimple_featposts_title') != '' ): ?>
                    <div class="section-title">
                        <span>
                            <?php echo esc_html(get_theme_mod('subsimple_beta_title', __('Featured Area','subsimple'))); ?>
                        </span>
                    </div>


                <?php endif; ?>
	        <div class="fg-wrapper">
	            <?php
		            	$count = 1;
				        $args = array( 
				        	'post_type' => 'post',
				        	'posts_per_page' => 3, 
				        	'cat'  => esc_html( get_theme_mod('subsimple_beta_cat',0) ),
				        	'ignore_sticky_posts' => 1,
			        	);
			        	
				        $loop = new WP_Query( $args );
				        
				        while ( $loop->have_posts() ) : 
				        	$loop->the_post(); 
				        ?>
				        <?php
						  $perma_cat = get_post_meta($post->ID , '_category_permalink', true);
						  if ( $perma_cat != null ) {
						    $cat_id = $perma_cat['category'];
						    $category = get_category($cat_id);
						  } else {
						    $categories = get_the_category();
						    $category = $categories[0];
						  }
						  $category_link = get_category_link($category);
						  $category_name = $category->name;  
						?>                                   

						<div class="fg-item-container col-md-12">
							<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
							<div class="fg-item">
                                    <div class="featured-thumb col-md-6">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('subsimple-pop-thumb'); ?></a>
                                        <?php else: ?>
                                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/placeholder2.jpg"; ?>"></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="out-thumb col-md-6">
                                        <h3><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php echo wp_trim_words( get_the_title(), 12 ); ?></a></h3>
                                    </div>
                            </div>
							</a>	
						</div>
										
						 <?php 
							 $count++;
							 endwhile; ?>
						 <?php wp_reset_postdata(); ?>
						
		        </div>	        
	    </div>
	</div>
</div>
<?php endif; ?>