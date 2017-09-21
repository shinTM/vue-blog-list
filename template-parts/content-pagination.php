<?php
/**
 * Template part for posts pagination.
 *
 * @package __Tm
 */
the_posts_pagination( array(
	'prev_text' => sprintf( '<span class="screen-reader-text">%s</span>', esc_html__( 'Previous', '__tm' ) ),
	'next_text' => sprintf( '<span class="screen-reader-text">%s</span>', esc_html__( 'Next', '__tm' ) ),
) );
