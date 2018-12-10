<?php
global $post_type;
$home_url = '<a href="' . home_url() . '">' . get_bloginfo( "name" ) . '</a>';
?>
<div id="r_sidebar" class="sidebar">
    <ul>

        <?php if ( is_category() ) { ?>
            <li><p><?php printf( __( 'You are currently browsing the archives for the %s category.', 'techozoic' ), single_cat_title( '', false ) ); ?> </p></li>
        <?php } elseif ( is_day() ) { ?>
            <li><p><?php printf( __( 'You are currently browsing the %1$s archives for %2$s.', 'techozoic' ), $home_url, get_the_time( "l, F jS, Y" ) ); ?></p></li>
        <?php } elseif ( is_month() ) { ?>
            <li><p><?php printf( __( 'You are currently browsing the %1$s archives for %2$s.', 'techozoic' ), $home_url, get_the_time( "F, Y" ) ); ?></p></li>
        <?php } elseif ( is_year() ) { ?>
            <li><p><?php printf( __( 'You are currently browsing the %1$s archives for %2$s.', 'techozoic' ), $home_url, get_the_time( "Y" ) ); ?></p></li>
        <?php } elseif ( is_search() ) { ?>
            <li><p><?php printf( __( 'You have searched the %1$s archives for %2$s.', 'techozoic' ), $home_url, '<strong>\'' . esc_html( $s ) . '\'</strong>' ) ?></p></li>
        <?php } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
            <li><p><?php printf( __( 'You are currently browsing the %1$s archives.', 'techozoic' ), $home_url ); ?></p></li>
            <?php
        }
        if ( is_page() && is_active_sidebar( "page_sidebar_r_$post->ID" ) ) {
            dynamic_sidebar( "page_sidebar_r_$post->ID" );
        } elseif ( ($post_type == 'forum' || $post_type == 'topic' || $post_type == 'reply') && is_active_sidebar( "page_sidebar_r_forum" ) ) {
            dynamic_sidebar( "page_sidebar_r_forum" );
        } elseif ( !dynamic_sidebar( __( 'Right Sidebar', 'techozoic' ) ) ) {
            global $user_ID;
            if ( $user_ID ) {
                if ( current_user_can( 'edit_themes' ) || current_user_can( 'edit_theme_options' ) ) {
                    ?>
                    <li><h2 class="widgettitle"><?php _e( 'Default Widgets', 'techozoic' ) ?></h2>
                        <?php printf( __( 'Widgets below are default.  These will be replaced when customizing using %s Widget Admin</a>', 'techozoic' ), '<a href="' . site_url() . '/wp-admin/widgets.php" title="' . __( 'Widgets', 'techozoic' ) . '">' ); ?>
                    </li>
                    <?php
                }
            }
            if ( is_page() ) {
                $children = wp_list_pages( 'title_li=&child_of=' . $post->ID . '&echo=0' );
                if ( $children ) {
                    ?>
                    <li><h2 class="widgettitle"><?php _e( 'Pages Below Current', 'techozoic' ) ?></h2>
                        <ul><?php echo $children; ?></ul>
                    </li>
                    <?php
                }
            }
            ?>
            <li id="rss"><h2 class="widgettitle"><?php _e( 'Syndicate', 'techozoic' ) ?></h2>
                <ul>
                    <li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS 2.0', 'techozoic' ) ?></a></li>
                    <?php if ( is_home() ) { ?>	
                        <li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>">RSS 2.0 (<?php _e( 'Comments', 'techozoic' ) ?>)</a></li>
                    <?php } ?>
                </ul>
            </li>
            <li><h2 class="widgettitle"><?php _e( 'Categories', 'techozoic' ) ?></h2>
                <form action="<?php echo home_url(); ?>" method="get">
                    <?php wp_dropdown_categories( 'show_count=1&hierarchical=1&orderby=name' ); ?>
                    <input type="submit" name="submit" value="<?php _e( 'Go', 'techozoic' ) ?>" id="catsubmit" style="margin-bottom:7px;"/>
                </form>
            </li>
            <?php if ( is_home() ) { ?>	
                <li><h2 class="widgettitle"><?php _e( 'Blogroll', 'techozoic' ) ?></h2>
                    <ul>
                        <?php wp_list_bookmarks( 'show_images=0&title_before=<h2 class="widgettitle">&categorize=0&title_li=' ); ?>
                    </ul>
                </li>
            <?php } ?>
        <?php } //End Dynamic Side Bar if  ?>
    </ul>	
</div>
