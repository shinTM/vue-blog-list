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

	//Add get_author_data
	register_rest_field( 'post',
		'author_data',
		array(
			'get_callback'    => 'get_author_data',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Add custom_format_date
	register_rest_field( 'post',
		'custom_format_date',
		array(
			'get_callback'    => 'get_custom_format_date',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Add custom_format_date
	register_rest_field( 'post',
		'is_visible',
		array(
			'get_callback'    => 'get_is_visible',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Add custom_format_date
	register_rest_field( 'post',
		'comments_amount',
		array(
			'get_callback'    => 'get_comments_amount',
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

function get_author_data( $object, $field_name, $request ) {

	$author_data['display_name'] = get_the_author_meta( 'display_name', $object['author'] );
	$author_data['author_link'] = get_author_posts_url( $object['author'] );

	return $author_data;
}

function get_custom_format_date( $object, $field_name, $request ) {

	$date_post_format = apply_filters( 'cherry_react_custom_format_date', '' );
	$date_post_format = ( ! empty( $date_post_format ) ) ? $date_post_format : get_option( 'date_format' );

	return get_the_date( $date_post_format );
}

function get_is_visible( $object, $field_name, $request ) {

	return false;
}

function get_comments_amount( $object, $field_name, $request ) {

	$count_comments = wp_count_comments( $object['id'] );

	return $count_comments->total_comments;
}

