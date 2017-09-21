<?php
/**
 * Template part for widgets area in footer.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package __Tm
 */

// Don't show if option are unchecked.
if ( ! get_theme_mod( 'footer_widget_area_visibility', __tm_theme()->customizer->get_default( 'footer_widget_area_visibility' ) ) ) {
	return;
}

// Don't show if area are empty.
if ( ! is_active_sidebar( 'footer-area' ) ) {
	return;
}
?>

<div class="footer-area-wrap invert footer-widgets-container">
	<div <?php echo __tm_get_container_classes( array( 'footer-area-wrap__inner' ), 'footer' ); ?>>
		<?php do_action( '__tm_render_widget_area', 'footer-area' ); ?>
	</div>
</div>