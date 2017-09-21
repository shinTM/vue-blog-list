<div class="custom-posts__item post <?php echo $grid_class; ?>">
	<div class="post-inner">
		<div class="post-thumbnail">
			<?php echo $image; ?>
		</div>
		<div class="entry-header">
			<?php echo $author; ?>
			<?php echo $title; ?>
			<?php echo $category; ?>
		</div>
		<div class="entry-content">
			<?php echo $excerpt; ?>
			<div class="entry-meta">
				<?php echo $date; ?>
				<?php echo $count; ?>
				<?php echo $tag; ?>
			</div>
		</div>
		<div class="entry-footer">
			<?php echo $button; ?>
		</div>
	</div>
</div>