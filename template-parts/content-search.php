<?php
/**
 * The template part for displaying results in search pages.
 *
 * @package __Tm
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php $utility = __tm_utility()->utility; ?>

	<div class="post-list__item-content">

		<header class="entry-header">
			<?php $author_visible = __tm_is_meta_visible( 'blog_post_author', 'loop' ) ? 'true' : 'false'; ?>

			<?php $utility->meta_data->get_author( array(
					'visible' => $author_visible,
					'class'   => 'posted-by__author',
					'prefix'  => esc_html__( 'Posted by ', '__tm' ),
					'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
					'echo'    => true,
				) );
			?>
			<?php
				$title_html = ( is_single() ) ? '<h1 %1$s>%4$s</h1>' : '<h2 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h2>' ;

				$utility->attributes->get_title( array(
					'class' => 'entry-title',
					'html'  => $title_html,
					'echo'  => true,
				) );
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->

		<?php if ( 'post' === get_post_type() ) : ?>

			<div class="entry-meta">

				<?php $date_visible = __tm_is_meta_visible( 'blog_post_publish_date', 'loop' ) ? 'true' : 'false';

					$utility->meta_data->get_date( array(
						'visible' => $date_visible,
						'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a></span>',
						'class'   => 'post__date-link',
						'icon'    => '<i class="material-icons">event</i>',
						'echo'    => true,
					) );
				?>

				<?php $comment_visible = __tm_is_meta_visible( 'blog_post_comments', 'loop' ) ? 'true' : 'false';

					$utility->meta_data->get_comment_count( array(
						'visible' => $comment_visible,
						'class'   => 'post__comments-link',
						'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
						'icon'    => '<i class="material-icons">mode_comment</i>',
						'echo'    => true,
					) );
				?>

				<?php $tags_visible = __tm_is_meta_visible( 'blog_post_tags', 'loop' ) ? 'true' : 'false';

					$utility->meta_data->get_terms( array(
						'visible'   => $tags_visible,
						'type'      => 'post_tag',
						'delimiter' => ', ',
						'icon'      => '<i class="material-icons">folder_open</i>',
						'before'    => '<div class="post__tags">',
						'after'     => '</div>',
						'echo'      => true,
					) );
				?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

	</div><!-- .post-list__item-content -->

	<footer class="entry-footer">
		<?php $utility->attributes->get_button( array(
				'class' => 'btn btn-primary',
				'text'  => get_theme_mod( 'blog_read_more_text', __tm_theme()->customizer->get_default( 'blog_read_more_text' ) ),
				'icon'  => '<i class="material-icons">arrow_forward</i>',
				'html'  => '<a href="%1$s" %3$s><span class="btn__text">%4$s</span>%5$s</a>',
				'echo'  => true,
			) );
		?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
