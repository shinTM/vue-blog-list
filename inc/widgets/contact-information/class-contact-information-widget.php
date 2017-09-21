<?php
/*
Widget Name: Contact Information widget
Description: This widget is used to display a list of your social networks
Settings:
 Title - Widget's text title
 Add Contact Information - Click to add a new contact information
 Choose icon - Choose an icon for your social network
 Value - Describe your social network contact
*/

/**
 * @package __Tm
 */

if ( ! class_exists( '__Tm_Contact_Information_Widget' ) ) {

	class __Tm_Contact_Information_Widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'contact-information-widget';
			$this->widget_description = esc_html__( 'Display an contact-information.', '__tm' );
			$this->widget_id          = '__tm_contact_information_widget';
			$this->widget_name        = esc_html__( 'Contact Information', '__tm' );

			$this->settings = array(
				'title'  => array(
					'type'  => 'text',
					'value' => 'Contact Information',
					'label' => esc_html__( 'Title:', '__tm' ),
				),
				'contact_information'  => array(
					'type'         => 'repeater',
					'add_label'    => esc_html__( 'Add Contact Information', '__tm' ),
					'title_field'  => 'value',
					'hidden_input' => true,
					'fields'       => array(
						'icon' => array(
							'type'        => 'iconpicker',
							'id'          => 'icon',
							'name'        => 'icon',
							'label'       => esc_html__( 'Choose icon', '__tm' ),
							'width'       => 'full',
							'icon_data'   => array(
								'icon_set'    => 'cherryWidgetFontAwesome',
								'icon_css'    => __TM_THEME_CSS . '/font-awesome.min.css',
								'icon_base'   => 'fa',
								'icon_prefix' => 'fa-',
								'icons'       => $this->get_icons_set(),
							),
						),
						'value' => array(
							'type'        => 'textarea',
							'id'          => 'value',
							'name'        => 'value',
							'placeholder' => esc_html__( 'Value', '__tm' ),
							'label'       => esc_html__( 'Value', '__tm' ),
						),
					),
				),
			);

			add_action( 'cherry_widget_after_update', array( $this, 'register_string' ) );

			parent::__construct();
		}

		/**
		 * Returns social icons set
		 *
		 * @return array
		 */
		public function get_icons_set() {

			ob_start();

			include __TM_THEME_DIR . '/assets/js/icons.json' ;

			$json   = ob_get_clean();
			$result = array();
			$icons  = json_decode( $json, true );

			foreach ( $icons['icons'] as $icon ) {

				if ( ! in_array( 'Brand Icons', $icon['categories'] ) ) {
					continue;
				}

				$result[] = $icon['id'];
			}

			return $result;
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

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$template = locate_template( 'inc/widgets/contact-information/views/contact-information-view.php', false, false );

			if ( empty( $template ) ) {
				return;
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			echo '<ul class="contact-information-widget__inner">';

			if( ! empty( $instance['contact_information'] ) ){

				foreach ( $instance['contact_information'] as $key => $value ) {
					$icon_class = $value[ 'icon' ];
					$text       = apply_filters( 'wpml_translate_single_string', $value['value'], 'Widgets', "{$this->widget_name} - value {$key}" );

					include $template;
				}
			}

			echo '</ul>';

			$this->widget_end( $args );
			$this->reset_widget_data();
		}

		/**
		 * Registers a text string for translation via WPML-plugin.
		 *
		 * @param array $instance
		 */
		public function register_string( $instance ) {

			if ( empty( $instance['contact_information'] ) ) {
				return;
			}

			foreach ( $instance['contact_information'] as $key => $value ) {
				do_action( 'wpml_register_single_string', 'Widgets', "{$this->widget_name} - value {$key}", $value['value'] );
			}
		}
	}
}

add_action( 'widgets_init', '__tm_register_contact_information_widget' );
function __tm_register_contact_information_widget() {
	register_widget( '__Tm_Contact_Information_Widget' );
}
