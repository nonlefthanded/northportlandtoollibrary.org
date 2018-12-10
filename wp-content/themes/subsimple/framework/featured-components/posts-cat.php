<?php if(get_theme_mod('subsimple_featposts_cat_enable') && is_front_page()): ?>
    <div id="featured-cat" class="featured-section-area">

            <div class="featured-cat-container">
                <?php if(get_theme_mod('subsimple_featcat_title') != '' ): ?>
                    <div class="section-title">
                        <span>
                            <?php echo esc_html(get_theme_mod('subsimple_featposts_title', __('Featured Category Area','subsimple'))); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php for( $i=1; $i<=3; $i++ ) : ?>
                <div class="f-cat-wrapper">
                    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"></a>
                    <div class="f-cat col-md-4 col-sm-4">
                        <?php $catname = get_cat_name(get_theme_mod('subsimple_featposts_category_'.$i));
                        if ($catname !='' ) :
                            $catname = get_cat_name(get_theme_mod('subsimple_featposts_category_'.$i));
                            $catid = array(get_theme_mod('subsimple_featposts_category_'.$i));
                            $category_link = get_category_link(get_theme_mod('subsimple_featposts_category_'.$i));
                            $showposts = 1;
                            $args=array(
                                'category__in' => $catid,
                                'showposts' => $showposts,
                                'orderby' => 'post_date',
                                'post_status' => 'publish',
                            );
                            $the_query = new WP_Query ( $args );
                            if($the_query->have_posts()) :
                                while ($the_query -> have_posts()) :
                                    $the_query -> the_post(); ?>
                                    <div class="featured-thumb">
                                        <a href="<?php echo esc_url( $category_link ); ?>"><?php the_post_thumbnail();?></a>
                                    </div>
                                    <div class="out-thumb">
                                        <a href="<?php the_permalink(); ?>"><?php echo $catname; ?></a>
                                    </div>
                                <?php endwhile;
                            endif;
                            /* Restore original Post Data */
                            wp_reset_postdata();
                        endif; ?>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
    </div>
<?php endif; ?>