<?php
/**
 * The template for displaying posts in the Status Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 * 
 * @package WordPress
 * @subpackage Twenty_Eleven
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<hgroup>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<h2 class="entry-format"><?php _e( 'Status'); ?></h2>
			</hgroup>

			<?php if ( 'post' == $post->post_type ) : ?>
			<?php endif; ?>
			
			<?php if ( comments_open() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( __( '<span class="leave-reply">Reply</span>'), __( '1'), __( '%') ); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<div class="avatar"><?php echo get_avatar( $post->post_author, apply_filters( 'twentyeleven_status_avatar', '65' ) ); ?></div>
			
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>') ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:'), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php
				printf( __( '<span class="sep">Posted on </span><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%4$s" title="%5$s">%6$s</a></span>'),
					get_permalink(),
					get_the_date( 'c' ),
					get_the_date(),
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					sprintf( esc_attr__( 'View all posts by %s'), get_the_author() ),
					get_the_author()
				);
			?>
			<?php if ( comments_open() ) : ?>
			<span class="sep"> | </span>
			<span class="comments-link"><?php comments_popup_link( __( '<span class="leave-reply">Leave a reply</span>'), __( '<b>1</b> Reply'), __( '<b>%</b> Replies') ); ?></span>
			<?php endif; ?>
			<?php edit_post_link( __( 'Edit'), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->
