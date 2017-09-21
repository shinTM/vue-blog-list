<?php
/**
 * Template part for Carousel's widget container.
 *
 * @package __Tm
 * @subpackage widgets
 */
?>

<div class="swiper-carousel-container">
	<div id="swiper-carousel-<?php echo $instance; ?>" <?php echo $data_attr_line; ?>>
		<div class="swiper-wrapper">
			<?php $this->get_carousel_loop( $posts_query, $template_item ); ?>
		</div>
		<?php echo $pagination_html; ?>
	</div>
	<?php echo $navigation_html; ?>
</div>
