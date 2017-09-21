<?php
/**
 * Extends basic functionality for better TM Mega Menu compatibility
 *
 * @package __Tm
 */

/**
 * Check if Mega Menu plugin is activated.
 *
 * @return bool
 */
function __tm_is_mega_menu_active() {
	return class_exists( 'tm_mega_menu' );
}

add_filter( '__tm_theme_script_variables', '__tm_pass_mega_menu_vars' );

/**
 * Pass Mega Menu variables.
 *
 * @param  array  $vars Variables array.
 * @return array
 */
function __tm_pass_mega_menu_vars( $vars = array() ) {

	if ( ! __tm_is_mega_menu_active() ) {
		return $vars;
	}

	if ( get_option( 'tm-mega-menu-location' ) ) {

		$vars['megaMenu'] = array(
			'isActive' => true,
			'location' => get_option( 'tm-mega-menu-location' ),
		);
	}

	return $vars;

}
