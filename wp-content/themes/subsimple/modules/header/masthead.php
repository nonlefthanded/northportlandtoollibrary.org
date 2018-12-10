<header id="masthead" class="site-header" role="banner" data-parallax="scroll" data-speed="0.15" data-image-src="<?php echo get_header_image() ?>">
    <div id="primary-menu">
        <div class="container">
            <?php get_template_part('modules/navigation/primary','menu'); ?>
        </div>
    </div>

    <div class="container">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <div id="site-logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            <div id="text-title-desc">
                <h1 class="site-title title-font"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
            </div>
        </div>
    </div>

    <div class="conntainer">
        <div class="search-submit">
            <span id="searchicon" class="fa fa-search"></span>
        </div>
    </div>

    <div class="container">
        <div id="social-icons">
            <?php get_template_part('modules/social/social', 'fa'); ?>
        </div>
    </div>
</header><!-- #masthead -->