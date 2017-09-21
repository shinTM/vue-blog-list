<?php
/**
 * The template for displaying related posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package __Tm
 * @subpackage single-post
 */
?>
<div class="related-post page-content<?php echo esc_attr( $grid_class ); ?>">
	<figure class="post-thumbnail">
		<?php echo $image; ?>
		<?php echo $category; ?>
	</figure>
	<header class="entry-header">
		<?php echo $author; ?>
		<?php echo $title; ?>
	</header>
	<div class="entry-content">
		<?php echo $excerpt; ?>
	</div>
	<div class="entry-meta">
		<?php echo $date; ?>
		<?php echo $comment_count; ?>
		<?php echo $tag; ?>
	</div>
</div>
