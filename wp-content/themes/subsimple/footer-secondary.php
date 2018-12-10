<?php if(get_theme_mod('subsimple_secondary_footer_enable', true) && is_home()   ): ?>
<div id="footer-secondary">
    <div class="container">
        <?php for ($i = 1; $i <= 3; $i++) : ?>
            <div class="features col-md-4 col-sm-4 col-xs-12">


                    <?php
                    $args = array(
                        'post_type' => 'page',
                        'posts_per_page' => 1,
                        'post__in' => array(get_theme_mod('subsimple_select_page_'.$i)),
                    );

                    $loop = new WP_Query( $args );
                    while( $loop -> have_posts() ):
                        $loop->the_post(); ?>

                        <div class="col-md-4 col-sm-4 col-xs-4 f-icons">
                            <?php $featured_icons = esc_html(get_theme_mod('subsimple_featured_icon_'.$i));
                            if ( ($featured_icons != 'none') && ($featured_icons != '') ) : ?>
                               <i class="fa fa-fw fa-<?php echo $featured_icons; ?>"></i>
                            <?php endif; ?>
                        </div>

                        <div class="h-content col-md-8 col-sm-8 col-xs-8">
                            <h1 class="title">
                                <?php the_title(); ?>
                            </h1>
                            <div class="excerpt">
                                <?php echo substr(get_the_content(), 0, 100)."..."; ?>
                            </div>

                            <?php if(get_theme_mod('subsimple_featured_button') != ''): ?>
                                <a href="" class="more-button">
                                    <?php echo get_theme_mod('plum_hero2_button'); ?>
                                </a>
                            <?php endif;?>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>


            </div>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>