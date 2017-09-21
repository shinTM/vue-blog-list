<?php
/**
 * Thumbnails configuration.
 *
 * @package __Tm
 */

add_action( 'after_setup_theme', '__tm_register_image_sizes', 5 );
function __tm_register_image_sizes() {
	set_post_thumbnail_size( 370, 230, true );

	// Registers a new image sizes.
	add_image_size( '__tm-thumb-s', 150, 150, true );
	add_image_size( '__tm-thumb-m', 400, 400, true );
	add_image_size( '__tm-thumb-l', 1170, 780, true );
	add_image_size( '__tm-thumb-xl', 1920, 1080, true );
	add_image_size( '__tm-thumb-masonry', 600, 999, false );
	add_image_size( '__tm-author-avatar', 512, 512, true );
	add_image_size( '__tm-thumb-560-350', 560, 350, true );
}
