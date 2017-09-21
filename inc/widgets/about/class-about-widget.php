<?php
/*
Widget Name: About widget
Description: This widget is used to display information about your site.
Settings:
 Title - Widget's text title
 Logo - You can select a logo for the widget
 Enable Social Buttons - Enable/disable social buttons
 Enable Tagline - Enable/disable tagline
 Content - Add content to this field
*/

/**
 * @package __Tm
 */

if ( ! class_exists( '__Tm_About_Widget' ) ) {

	class __Tm_About_Widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-about';
			$this->widget_description = esc_html__( 'Display an information about your site.', '__tm' );
			$this->widget_id          = '__tm_widget_about';
			$this->widget_name        = esc_html__( 'About __Tm', '__tm' );
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
					'label'              => esc_html__( 'Logo:', '__tm' ),
				),
				'enable_social' => array(
					'type'  => 'checkbox',
					'value' => array(
						'enable_social' => 'true',
					),
					'options' => array(
						'enable_social' => esc_html__( 'Enable Social Buttons', '__tm' ),
					),
				),
				'enable_tagline' => array(
					'type'  => 'checkbox',
					'value' => array(
						'enable_tagline' => 'true',
					),
					'options' => array(
						'enable_tagline' => esc_html__( 'Enable Tagline', '__tm' ),
					),
				),
				'content'  => array(
					'type'              => 'textarea',
					'placeholder'       => esc_html__( 'Text or HTML', '__tm' ),
					'value'             => '',
					'label'             => esc_html__( 'Content:', '__tm' ),
					'sanitize_callback' => 'wp_kses_post',
				),
			);

			parent::__construct();
		}

		/**
		 * Get social navigation menu
		 *
		 * @param  string $wrapper Formated wrapper string.
		 * @return string
		 */
		public function get_social_nav( $wrapper ) {
			$content        = '';
			$social_enabled = ( ! empty( $this->instance['enable_social'] ) ) ? $this->instance['enable_social'] : false;

			if ( is_array( $social_enabled ) && 'true' === $social_enabled['enable_social'] ) {
				$content = sprintf( $wrapper, __tm_get_social_list( 'widget' ) );
			}

			return $content;
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

			$template = locate_template( 'inc/widgets/about/views/about.php', false, false );

			if ( empty( $template ) ) {
				return;
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$title     = ! empty( $instance['title'] ) ? $instance['title'] : $this->settings['title']['value'];
			$media_id  = absint( $instance['media_id'] );
			$src       = wp_get_attachment_image_src( $media_id, 'medium' );
			$site_name = esc_attr( get_bloginfo( 'name' ) );
			$home_url  = esc_url( home_url( '/' ) );
			$logo_url  = $logo_width = $logo_height = '';

			if ( false !== $src ) {
				$logo_url = esc_url( $src[0] );
			}

			$content = $this->use_wpml_translate( 'content' );

			/**
			 * Filters the content of the widget.
			 *
			 * @param string            $content  The widget content.
			 * @param array             $instance Array of settings for the current widget.
			 * @param __Tm_About_Widget $this     Current widget instance.
			 */
			$content = apply_filters( 'widget_text', $content, $instance, $this );

			$tagline = '';
			$tagline_enabled = ( ! empty( $instance['enable_tagline'] ) ) ? $instance['enable_tagline'] : false;

			if ( is_array( $tagline_enabled ) && 'true' === $tagline_enabled['enable_tagline'] ) {
				$tagline_enabled = true;
			} else {
				$tagline_enabled = false;
			}

			if ( $tagline_enabled ) {
				$format   = apply_filters( '__tm_about_widget_tagline_format', '<p>%s</p>', $this->settings, $this->args );
				$_tagline = get_bloginfo( 'description', 'display' );
				$tagline  = ( ! empty( $_tagline ) ) ? sprintf( $format, $_tagline ) : '';
			}

			include $template;

			$this->widget_end( $args );
			$this->reset_widget_data();
		}
	}
}

add_action( 'widgets_init', '__tm_register_about_widget' );
function __tm_register_about_widget() {
	register_widget( '__Tm_About_Widget' );
}
