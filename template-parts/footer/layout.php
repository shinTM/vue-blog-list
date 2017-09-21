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
		<div class="site-info__flex">
			<?php __tm_footer_logo(); ?>
			<div class="site-info__mid-box"><?php
				__tm_footer_menu();
				__tm_footer_copyright();
			?></div>
			<?php __tm_social_list( 'footer' ); ?>
		</div>
	</div><!-- .site-info -->
</div><!-- .container -->
