<?php
/**
 * End point hooks.
 *
 * @package __Tm
 */


add_action( 'rest_api_init', 'update_post_end_point' );

function update_post_end_point() {
	//Add featured image
	register_rest_field( 'post',
		'featured_image_src',
		array(
			'get_callback'    => 'get_featured_image_src',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Add trimed_content
	register_rest_field( 'post',
		'trimed_content',
		array(
			'get_callback'    => 'get_trimed_content',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Add trimed_content
	register_rest_field( 'post',
		'excerpt',
		array(
			'get_callback'    => 'get_excerpt',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}

function get_featured_image_src( $object, $field_name, $request ) {

	$size = 'thumb-xl'; // Change this to the size you want | 'medium' / 'large'
	$feat_img_array = wp_get_attachment_image_src( $object['featured_media'], $size, true );

	return $feat_img_array[ 0 ];
}

function get_trimed_content( $object, $field_name, $request ) {

	if ( $object['content']['rendered'] ) {
		$text = strip_shortcodes( $object['content']['rendered'] );
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );
	}

	$text = wp_trim_words( $text, 20, '...' );

	return $text;
}

function get_excerpt( $object, $field_name, $request ) {

	$excerpt = get_post_field( 'post_excerpt', $object[ 'id' ] );

	return $excerpt;
}


