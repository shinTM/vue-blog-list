<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package __Tm
 */
?>

<?php get_template_part( 'template-parts/footer/widgets-area' ); ?>

<div class="footer-container">
	<div <?php echo __tm_get_container_classes( array( 'site-info' ), 'footer' ); ?>>
		<?php
			__tm_footer_logo();
			__tm_social_list( 'footer' );
			__tm_footer_copyright();
			__tm_footer_menu();
		?>
	</div><!-- .site-info -->
</div><!-- .container -->
