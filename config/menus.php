<?php
/**
 * Menus configuration.
 *
 * @package __Tm
 */

add_action( 'after_setup_theme', '__tm_register_menus', 5 );
function __tm_register_menus() {

	register_nav_menus( array(
		'top'    => esc_html__( 'Top', '__tm' ),
		'main'   => esc_html__( 'Main', '__tm' ),
		'footer' => esc_html__( 'Footer', '__tm' ),
		'social' => esc_html__( 'Social', '__tm' ),
	) );
}
