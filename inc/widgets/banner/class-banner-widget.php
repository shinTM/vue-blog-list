<?php
/*
Widget Name: Banner widget
Description: This widget is used to display a banner in your sidebar.
Settings:
 Title - Widget's text title
 Source - You can choose an image
 Link - Specify a banner link
 Opens in - Choose where the link will be opened in
*/

/**
 * @package __Tm
 */

if ( ! class_exists( '__Tm_Banner_Widget' ) ) {

	class __Tm_Banner_Widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-banner';
			$this->widget_description = esc_html__( 'Display a banner in your sidebar.', '__tm' );
			$this->widget_id          = '__tm_widget_banner';
			$this->widget_name        = esc_html__( 'Banner', '__tm' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title:', '__tm' ),
				),
				'media_id' => array(
					'type'               => 'media',
					'multi_upload'       => false,
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Upload', '__tm' ),
					'value'              => '',
					'label'              => esc_html__( 'Source:', '__tm' ),
				),
				'link' => array(
					'type'        => 'text',
					'placeholder' => esc_html__( 'Type a banner`s link', '__tm' ),
					'value'       => esc_url( home_url( '/' ) ),
					'label'       => esc_html__( 'Link:', '__tm' ),
				),
				'target' => array(
					'type'    => 'select',
					'options' => array(
						'_blank' => esc_html__( 'A new window or tab', '__tm' ),
						'_self'  => esc_html__( 'The same frame as it was clicked', '__tm' ),
					),
					'value' => '_blank',
					'label' => esc_html__( 'Opens in:', '__tm' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 * @since 1.0.1
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( empty( $instance['media_id'] ) ) {
				return;
			}

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$template = locate_template( 'inc/widgets/banner/views/banner.php', false, false );

			if ( empty( $template ) ) {
				return;
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$title    = ! empty( $instance['title'] ) ? $instance['title'] : $this->settings['title']['value'];
			$link     = $this->use_wpml_translate( 'link' );
			$media_id = absint( $instance['media_id'] );
			$src      = wp_get_attachment_image_src( $media_id, 'medium' );
			$target   = ! empty( $instance['target'] ) && in_array( $instance['target'], array( '_blank', '_self' ) ) ? $instance['target'] : $this->settings['target']['value'];

			include $template;

			$this->widget_end( $args );
			$this->reset_widget_data();
		}
	}
}

add_action( 'widgets_init', '__tm_register_banner_widget' );
function __tm_register_banner_widget() {
	register_widget( '__Tm_Banner_Widget' );
}
