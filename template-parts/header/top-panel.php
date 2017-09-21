<?php
/**
 * Template part for top panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package __Tm
 */

// Don't show top panel if all elements are disabled.
if ( ! __tm_is_top_panel_visible() ) {
	return;
} ?>

<div class="top-panel">
	<div <?php echo __tm_get_container_classes( array( 'top-panel__wrap' ), 'header' ); ?>><?php
		__tm_top_message( '<div class="top-panel__message">%s</div>' );
		__tm_top_search( '<div class="top-panel__search">%s</div>' );
		__tm_top_menu();
	?></div>
</div><!-- .top-panel -->