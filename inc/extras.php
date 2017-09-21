<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package __Tm
 */

/**
 * Sidebar position
 */
add_filter( 'theme_mod_sidebar_position', '__tm_set_post_meta_value' );

/**
 * Header container type
 */
add_filter( 'theme_mod_header_container_type', '__tm_set_post_meta_value' );

/**
 * Content container type
 */
add_filter( 'theme_mod_content_container_type', '__tm_set_post_meta_value' );

/**
 * Footer container type
 */
add_filter( 'theme_mod_footer_container_type', '__tm_set_post_meta_value' );

/**
 * Header layout type
 */
add_filter( 'theme_mod_header_layout_type', '__tm_set_header_layout_value' );

/**
 * Container top padding
 */
add_filter( 'theme_mod_content_padding_top', '__tm_set_container_paddings_value' );

/**
 * Container bottom padding
 */
add_filter( 'theme_mod_content_padding_bottom', '__tm_set_container_paddings_value' );

/**
 * Set post specific meta value.
 *
 * @param  string $value Default meta-value.
 * @return string
 */
function __tm_set_post_meta_value( $value ) {
	$queried_obj = __tm_get_queried_obj();

	if ( ! $queried_obj ) {
		return $value;
	}

	$meta_key   = '__tm_' . str_replace( 'theme_mod_', '', current_filter() );
	$meta_value = get_post_meta( $queried_obj, $meta_key, true );

	if ( ! $meta_value || 'inherit' === $meta_value ) {
		return $value;
	}

	return $meta_value;
}

/**
 * Set header layout meta value.
 *
 * @param  string $value Default meta-value.
 * @return string
 */
function __tm_set_header_layout_value( $value ) {

	if ( wp_is_mobile() ) {
		return 'mobile';
	}

	return __tm_set_post_meta_value( $value );
}

/**
 * Redefined container paddings meta value.
 *
 * @param  string $value Default meta-value.
 * @return string
 */
function __tm_set_container_paddings_value( $value ) {
	$queried_obj = __tm_get_queried_obj();

	if ( ! $queried_obj ) {
		return $value;
	}

	$use_inherit_paddings = get_post_meta( $queried_obj, '__tm_content_paddings', true );

	if ( ! $use_inherit_paddings ) {
		return $value;
	}

	if ( filter_var( $use_inherit_paddings, FILTER_VALIDATE_BOOLEAN ) ) {
		return $value;
	}

	$meta_key   = '__tm_' . str_replace( 'theme_mod_', '', current_filter() );
	$meta_value = get_post_meta( $queried_obj, $meta_key, true );

	return $meta_value;
}

/**
 * Get queried object.
 *
 * @return string|boolean
 */
function __tm_get_queried_obj() {
	$queried_obj = apply_filters( '__tm_queried_object_id', false );

	if ( ! $queried_obj && ! __tm_maybe_need_rewrite_mod() ) {
		return false;
	}

	$queried_obj = is_home() ? get_option( 'page_for_posts' ) : false;
	$queried_obj = ! $queried_obj ? get_the_id() : $queried_obj;

	return $queried_obj;
}

/**
 * Check if we need to try rewrite theme mod or not
 *
 * @return boolean
 */
function __tm_maybe_need_rewrite_mod() {

	if ( is_front_page() && 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}

	if ( is_home() && 'page' == get_option( 'show_on_front' ) ) {
		return true;
	}

	if ( ! is_singular() ) {
		return false;
	}

	return true;
}

/**
 * Render existing macros in passed string.
 *
 * @since  1.0.0
 * @param  string $string String to parse.
 * @return string
 */
function __tm_render_macros( $string ) {

	$macros = apply_filters( '__tm_data_macros', array(
		'/%%year%%/' => date( 'Y' ),
		'/%%date%%/' => date( get_option( 'date_format' ) ),
	) );

	return preg_replace( array_keys( $macros ), array_values( $macros ), $string );
}

/**
 * Render font icons in content
 *
 * @param  string $content content to render
 * @return string
 */
function __tm_render_icons( $content ) {
	$icons     = __tm_get_render_icons_set();
	$icons_set = implode( '|', array_keys( $icons ) );

	$regex = '/icon:(' . $icons_set . ')?:?([a-zA-Z0-9-_]+)/';

	return preg_replace_callback( $regex, '__tm_render_icons_callback', $content );
}

/**
 * Callback for icons render.
 *
 * @param  array $matches Search matches array.
 * @return string
 */
function __tm_render_icons_callback( $matches ) {

	if ( empty( $matches[1] ) && empty( $matches[2] ) ) {
		return $matches[0];
	}

	if ( empty( $matches[1] ) ) {
		return sprintf( '<i class="fa fa-%s"></i>', $matches[2] );
	}

	$icons = __tm_get_render_icons_set();

	if ( ! isset( $icons[ $matches[1] ] ) ) {
		return $matches[0];
	}

	return sprintf( $icons[ $matches[1] ], $matches[2] );
}

/**
 * Get list of icons to render.
 *
 * @return array
 */
function __tm_get_render_icons_set() {
	return apply_filters( '__tm_render_icons_set', array(
		'fa'       => '<i class="fa fa-%s"></i>',
		'material' => '<i class="material-icons">%s</i>',
	) );
}

/**
 * Replace %s with theme URL.
 *
 * @param  string $url Formatted URL to parse.
 * @return string
 */
function __tm_render_theme_url( $url ) {
	return sprintf( $url, get_template_directory_uri() );
}

/**
 * Get image ID by URL.
 *
 * @param  string $image_src Image URL to search it in database.
 * @return int|bool false
 */
function __tm_get_image_id_by_url( $image_src ) {
	global $wpdb;

	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid = %s";
	$id    = $wpdb->get_var( $wpdb->prepare( $query, esc_url( $image_src ) ) );

	return $id;
}

/**
 * Print different galleries for masonry and non-masonry layout
 */
function __tm_post_formats_gallery() {
	$size = __tm_post_thumbnail_size();

	if ( ! in_array( get_theme_mod( 'blog_layout_type' ), array( 'masonry-2-cols', 'masonry-3-cols' ) ) ) {
		return do_action( 'cherry_post_format_gallery', array(
			'size' => $size[ 'size' ],
		) );
	}

	$images = __tm_theme()->get_core()->modules['cherry-post-formats-api']->get_gallery_images( false );

	if ( is_string( $images ) && ! empty( $images ) ) {
		return $images;
	}

	$items             = array();
	$first_item        = null;
	$size              = $size[ 'size' ];
	$format            = '<div class="mini-gallery post-thumbnail--fullwidth">%1$s<div class="post-gallery__slides" style="display: none;">%2$s</div></div>';
	$first_item_format = '<a href="%1$s" class="post-thumbnail__link">%2$s</a>';
	$item_format       = '<a href="%1$s">%2$s</a>';

	foreach( $images as $img ) {
		$image = wp_get_attachment_image( $img, $size );
		$url   = wp_get_attachment_url( $img );

		if ( sizeof( $items ) === 0 ) {
			$first_item = sprintf( $first_item_format, $url, $image );
		}

		$items[] = sprintf( $item_format, $url, $image );
	}

	printf( $format, $first_item, join( "\r\n", $items ) );
}

/**
 * Check if passed meta data is visible in current context.
 *
 * @since  1.0.0
 * @param  string $meta    Meta setting to check.
 * @param  string $context Current post context - 'single' or 'loop'.
 * @return bool
 */
function __tm_is_meta_visible( $meta, $context = 'loop' ) {

	if ( ! $meta ) {
		return false;
	}

	$meta_enabled = get_theme_mod( $meta, __tm_theme()->customizer->get_default( $meta ) );

	switch ( $context ) {

		case 'loop':

			if ( ! is_single() && $meta_enabled ) {
				return true;
			} else {
				return false;
			}

		case 'single':

			if ( is_single() && $meta_enabled ) {
				return true;
			} else {
				return false;
			}

	}

	return false;
}

/**
 * Get post thumbnail size.
 *
 * @return array
 */
function __tm_post_thumbnail_size( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'small'        => 'post-thumbnail',
		'fullwidth'    => '__tm-thumb-l',
		'masonry'      => '__tm-thumb-masonry',
		'class_prefix' => '',
	) );

	$layout      = get_theme_mod( 'blog_layout_type', __tm_theme()->customizer->get_default( 'blog_layout_type' ) );
	$format      = get_post_format();
	$size_option = get_theme_mod( 'blog_featured_image', __tm_theme()->customizer->get_default( 'blog_featured_image' ) );
	$size        = $args[ $size_option ];
	$link_class  = sanitize_html_class( $args['class_prefix'] . $size_option );

	if ( 'default' !== $layout
		|| is_single()
		|| is_sticky()
		|| in_array( $format , array( 'image', 'gallery', 'link' ) )
	) {
		$size       = $args['fullwidth'];
		$link_class = $args['class_prefix'] . 'fullwidth';
	}

	if ( in_array( $layout, array( 'masonry-2-cols', 'masonry-3-cols' ) ) ) {
		$size       = $args['masonry'];
		$link_class = $args['class_prefix'] . 'fullwidth';
	}

	return array(
		'size'  => $size,
		'class' => $link_class,
	);
}
