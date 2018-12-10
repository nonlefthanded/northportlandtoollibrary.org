<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
    
			<?php if ( is_sticky() ) : ?>
		
           			<hgroup>
                     <aside class="grid_4 details">
				<h1 class="entry-title">FEATURED POST</a></h1>
                <div class="alignright-postformat">
                </div>
                
                <table  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>  <td height="35" align="center">
<div class="date"><?php the_time('F') ?> </div><div class="date2"><?php the_time('j') ?> </div>
</td> </tr><tr>
    <td width="24"><div class="date3"><?php the_time('Y') ?> </div></td>
  </tr></table> </aside>
	</hgroup>

			<?php else : ?>
        			<hgroup>
                     <aside class="grid_4 details">
                <div class="alignright-postformat">
                </div>
                
                <table width="100%">
  <tr>
    <td align="center">
    
    <table class="postdate" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>  <td  align="center">
<div class="date"><?php the_time('F') ?> </div>
<table border="0">
  <tr>
    <td><div class="date2"><?php the_time('j') ?> </div></td>
    <td><div class="date-s"><sup><?php the_time('S') ?></sup></div></td>
     <td><div class="date-comma">,</div></td>
    <td><div class="date3">&nbsp;<?php the_time('Y') ?> </div></td>
  </tr>
</table>

</td> </tr></table>
    
    </td>
  </tr>
</table>
 </aside>
	</hgroup>

			<?php endif; ?>


		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
         <section class="grid_8 content">
		<div class="entry-content">
        <header class="postname">
      <table class="postname" width="100%">
  <tr>
    <td align="center"><h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1></td>
  </tr>
</table>
</header>
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>') ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( '<span>Pages:</span>'), 'after' => '</div>' ) ); ?>
           

		</div>
        
         <table width="100%" border="0">
  <tr>
    <td align="center">
    
    <table class="home-share" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="twitter-share-button"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink() ?>" data-text="RT @joaochao <?php the_title(); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> </div></td>
    <td><div id="plus-1">
    <!-- Place this tag where you want the +1 button to render -->
<g:plusone size="medium"></g:plusone></div>
</td>
<td><div id="fb-recommend">
<div class="fb-like" data-href="<?php echo urlencode(get_permalink($page->ID)); ?>" data-send="true" data-layout="button_count" data-width="70px" data-show-faces="false" data-font="verdana"></div>
    </div></td>
  </tr>
</table>
    
    </td>
  </tr>
</table>
        
        <!-- .entry-content --> </section>
        
		<?php endif; ?>

	<?php if ( 'post' == $post->post_type ) : ?>
			<div class="entry-meta">
            
				
			</div><!-- .entry-meta -->
			<?php endif; ?>

		<footer class="entry-meta">
		
			<?php if ( 'post' == $post->post_type ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ') );
				if ( $categories_list ):
			?>
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">tagged</span> %2$s'), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
			</span>

			<?php endif; // End if categories ?>
    
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ') );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			<span class="sep"> | </span>
				<?php endif; // End if $show_sep ?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s'), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == $post->post_type ?>

			<?php if ( comments_open() ) : ?>
			<?php if ( $show_sep ) : ?>
	
			<?php endif; // End if $show_sep ?>
			<span class="comments-link"><!-- comments_popup_link( __( '<span class="leave-reply">Leave a reply</span>'), __( '<b>1</b> Reply'), __( '<b>%</b> Replies') ); --></span>
			<?php endif; // End if comments_open() ?>

		</footer><!-- #entry-meta -->

	</article><!-- #post-<?php the_ID(); ?> -->

