<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package __Tm
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php $utility = __tm_utility()->utility; ?>

	<header class="entry-header">
		<?php $cats_visible = __tm_is_meta_visible( 'single_post_categories', 'single' ) ? 'true' : 'false'; ?>

		<?php $utility->meta_data->get_terms( array(
				'visible' => $cats_visible,
				'type'    => 'category',
				'icon'    => '',
				'before'  => '<div class="post__cats">',
				'after'   => '</div>',
				'echo'    => true,
			) );
		?>

		<?php $utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => '<h1 %1$s>%4$s</h1>',
				'echo'  => true,
			) );
		?>

		<?php if ( 'post' === get_post_type() ) : ?>

			<div class="entry-meta">

				<?php $author_visible = __tm_is_meta_visible( 'single_post_author', 'single' ) ? 'true' : 'false';

					$utility->meta_data->get_author( array(
						'visible' => $author_visible,
						'class'   => 'posted-by__author',
						'prefix'  => esc_html__( 'Posted by ', '__tm' ),
						'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
						'echo'    => true,
					) );
				?>

				<?php $date_visible = __tm_is_meta_visible( 'single_post_publish_date', 'single' ) ? 'true' : 'false';

					$utility->meta_data->get_date( array(
						'visible' => $date_visible,
						'class'   => 'post__date-link',
						'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a></span>',
						'icon'    => '<i class="material-icons">event</i>',
						'echo'    => true,
						'before' => '*',
					) );
				?>

				<?php $comment_visible = __tm_is_meta_visible( 'single_post_comments', 'single' ) ? 'true' : 'false';

					$utility->meta_data->get_comment_count( array(
						'visible' => $comment_visible,
						'class'   => 'post__comments-link',
						'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
						'icon'    => '<i class="material-icons">mode_comment</i>',
						'echo'    => true,
					) );
				?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php __tm_ads_post_before_content(); ?>

	<figure class="post-thumbnail">
		<?php $utility->media->get_image( array(
				'size'        => '__tm-thumb-l',
				'html'        => '<img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s">',
				'placeholder' => false,
				'echo'        => true,
			) );
		?>
	</figure><!-- .post-thumbnail -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links__title">' . esc_html__( 'Pages:', '__tm' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-links__item">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', '__tm' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php $tags_visible = __tm_is_meta_visible( 'single_post_tags', 'single' ) ? 'true' : 'false'; ?>

		<?php $utility->meta_data->get_terms( array(
				'visible'   => $tags_visible,
				'type'      => 'post_tag',
				'delimiter' => ', ',
				'icon'      => '<i class="material-icons">folder_open</i>',
				'before'    => '<div class="post__tags">',
				'after'     => '</div>',
				'echo'      => true,
			) );
		?>

		<?php __tm_share_buttons( 'single' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
