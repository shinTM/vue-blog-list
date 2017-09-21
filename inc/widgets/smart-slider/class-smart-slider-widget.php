<?php
/*
Widget Name: Smart Slider widget
Description: This widget is used to display a smart slider
Settings:
 Title - Widget's text title
 Choose taxonomy type - Choose the posts source type
 Select cateogory / tag - Choose the posts source
 Posts Count - Limit the posts
 Display title - Choose whether to display post title
 Display content - Choose whether to display post content
 Display more button - Choose whether to display a more button
 Content words trimmed count - Limit the post content
 Mode - Choose the slider mode
 Slider width - Choose the width of the smart slider
 Slider height - Choose the height of the smart slider
 Slider orientation - Choose an orientation of the smart slider
 Slide distance - Customize the slide distance
 Slide duration - Customize the duration of the slide
 Use fade effect - Choose whether to use the fade effect animation
 Use navigation - Choose whether to show the navigation
 Slider Arrows Fade - Choose whether the arrows will fade in only on mouse over
 Use pagination - Choose whether to show the pagination
 Use autoplay - Toggle the autoplay
 Autoplay delay - Customize the delay of the autoplay
 Display fullscreen button - Choose whether to display the fullscreen button
 Shuffle - Toggle for slides to be shuffled
 Use infinite scrolling - Toggle the infinite scrolling
 Display thumbnails - Choose whether to display the thumbnails
*/

/**
 * @package __Tm
 */

if ( ! class_exists( '__Tm_Smart_Slider_Widget' ) ) {

	class __Tm_Smart_Slider_Widget extends Cherry_Abstract_Widget {

		/**
		 * Contain utility module from Cherry Framework.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $utility = null;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-smart-slider smart-slider';
			$this->widget_description = esc_html__( 'Display smart slider on your site.', '__tm' );
			$this->widget_id          = '__tm_widget_smart_slider';
			$this->widget_name        = esc_html__( 'Smart Slider', '__tm' );
			$this->utility            = __tm_utility()->utility;
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'value' => esc_html__( 'Smart Slider', '__tm' ),
					'label' => esc_html__( 'Title', '__tm' ),
				),
				'terms_type' => array(
					'type'    => 'radio',
					'value'   => 'category',
					'options' => array(
						'category' => array(
							'label' => esc_html__( 'Category', '__tm' ),
							'slave' => 'terms_type_category',
						),
						'post_tag' => array(
							'label' => esc_html__( 'Tag', '__tm' ),
							'slave' => 'terms_type_post_tag',
						),
					),
					'label' => esc_html__( 'Choose taxonomy type', '__tm' ),
				),
				'categories' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array(
						$this->utility->satellite,
						'get_terms_array',
						array( 'category', 'slug' ),
					),
					'options'     => false,
					'label'       => esc_html__( 'Select category', '__tm' ),
					'multiple'    => true,
					'placeholder' => esc_html__( 'Select category', '__tm' ),
					'master'      => 'terms_type_category',
				),
				'tags' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array(
						$this->utility->satellite,
						'get_terms_array',
						array( 'post_tag', 'slug' ),
					),
					'options'     => false,
					'label'       => esc_html__( 'Select tags', '__tm' ),
					'multiple'    => true,
					'placeholder' => esc_html__( 'Select tags', '__tm' ),
					'master'      => 'terms_type_post_tag',
				),
				'posts_per_page' => array(
					'type'      => 'stepper',
					'value'     => 5,
					'max_value' => 50,
					'min_value' => 1,
					'label'     => esc_html__( 'Posts count', '__tm' ),
				),
				'post_title' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Display title', '__tm' ),
				),
				'content' => array(
					'type'  => 'switcher',
					'value' => 'false',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Display content', '__tm' ),
				),
				'more_button' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Display more button', '__tm' ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'On', '__tm' ),
						'false_toggle' => esc_html__( 'Off', '__tm' ),
						'true_slave'   => 'more_button_attr',
						'false_slave'  => '',
					),
				),
				'more_button_text' => array(
					'type'   => 'text',
					'value'  => esc_html__( 'Read more', '__tm' ),
					'label'  => esc_html__( 'More button text', '__tm' ),
					'master' => 'more_button_attr',
				),
				'trim_words' => array(
					'type'       => 'slider',
					'value'      => 15,
					'max_value'  => 55,
					'min_value'  => 1,
					'step_value' => 1,
					'label'      => esc_html__( 'Content words trimmed count', '__tm' ),
				),
				'mode' => array(
					'type'    => 'select',
					'value'   => 'slider',
					'options' => array(
						'slider'  => esc_html__( 'Slider', '__tm' ),
						'gallery' => esc_html__( 'Gallery', '__tm' ),
					),
					'label' => esc_html__( 'Mode', '__tm' ),
				),
				'width' => array(
					'type'  => 'text',
					'value' => '100%',
					'label' => esc_html__( 'Slider width (less than 100% for `Gallery` mode)', '__tm' ),
				),
				'height' => array(
					'type'  => 'text',
					'value' => '800',
					'label' => esc_html__( 'Slider height', '__tm' ),
				),
				'orientation' => array(
					'type'    => 'select',
					'size'    => 1,
					'value'   => 'horizontal',
					'options' => array(
						'horizontal' => esc_html__( 'Horizontal', '__tm' ),
						'vertical'   => esc_html__( 'Vertical', '__tm' ),
					),
					'label' => esc_html__( 'Slider orientation', '__tm' ),
				),
				'slide_distance' => array(
					'type'      => 'slider',
					'value'     => 0,
					'max_value' => 100,
					'min_value' => 0,
					'label'     => esc_html__( 'Slide distance(px)', '__tm' ),
				),
				'slide_duration' => array(
					'type'       => 'slider',
					'value'      => 500,
					'max_value'  => 3000,
					'min_value'  => 100,
					'step_value' => 100,
					'label'      => esc_html__( 'Slide duration(ms)', '__tm' ),
				),
				'slide_fade' => array(
					'type'  => 'switcher',
					'value' => 'false',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Use fade effect?  (only for `Slider` mode)', '__tm' ),
				),
				'navigation' => array(
					'type'   => 'switcher',
					'value'  => 'true',
					'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label'  => esc_html__( 'Use navigation?', '__tm' ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'On', '__tm' ),
						'false_toggle' => esc_html__( 'Off', '__tm' ),
						'true_slave'   => 'navigation_button',
						'false_slave'  => '',
					),
				),
				'fade_navigation' => array(
					'type'   => 'switcher',
					'value'  => 'true',
					'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label'  => esc_html__( 'Indicates whether the arrows will fade in only on hover', '__tm' ),
					'master' => 'navigation_button',
				),
				'pagination' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Use pagination?', '__tm' ),
				),
				'autoplay' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Use autoplay?', '__tm' ),
				),
				'autoplay_delay' => array(
					'type'       => 'slider',
					'value'      => 5000,
					'max_value'  => 10000,
					'min_value'  => 2000,
					'step_value' => 500,
					'label'      => esc_html__( 'Autoplay delay(ms)', '__tm' ),
				),
				'fullScreen' => array(
					'type'  => 'switcher',
					'value' => 'false',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Display fullScreen button?', '__tm' ),
				),
				'shuffle' => array(
					'type'  => 'switcher',
					'value' => 'false',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Indicates if the slides will be shuffled', '__tm' ),
				),
				'loop' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Use infinite scrolling?', '__tm' ),
				),
				'thumbnails' => array(
					'type'   => 'switcher',
					'value'  => 'false',
					'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label'  => esc_html__( 'Display thumbnails?', '__tm' ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'On', '__tm' ),
						'false_toggle' => esc_html__( 'Off', '__tm' ),
						'true_slave'   => 'thumbnails_attr',
						'false_slave'  => '',
					),
				),
				'thumbnails_position' => array(
					'type'    => 'select',
					'size'    => 1,
					'value'   => 'bottom',
					'options' => array(
						'top'    => esc_html__( 'Top', '__tm' ),
						'bottom' => esc_html__( 'Bottom', '__tm' ),
						'right'  => esc_html__( 'Right', '__tm' ),
						'left'   => esc_html__( 'Left', '__tm' ),
					),
					'label'  => esc_html__( 'Sets the position of the thumbnail scroller', '__tm' ),
					'master' => 'thumbnails_attr',
				),
				'thumbnails_arrows' => array(
					'type'   => 'switcher',
					'value'  => 'true',
					'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label'  => esc_html__( 'Display thumbnails arrows?', '__tm' ),
					'master' => 'thumbnails_attr',
				),
			);

			parent::__construct();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 9 );
		}

		/**
		 * Widget function.
		 *
		 * @see WP_Widget
		 *
		 * @since 1.0.0
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$categories_array = ( isset( $this->instance['categories'] ) ) ? $this->instance['categories'] : array();
			$tags_array       = ( isset( $this->instance['tags'] ) ) ? $this->instance['tags'] : array();

			$tax_query = array();

			if ( 'category' == $this->instance['terms_type'] ) {
				if ( ( is_array( $categories_array ) && ! empty( $categories_array ) ) ) {
					array_push( $tax_query, array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories_array,
					));
				}
			} else {
				if ( ( is_array( $tags_array ) && ! empty( $tags_array ) ) ) {
					array_push( $tax_query, array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $tags_array,
					));
				}
			}

			// The Query.
			$posts_query = $this->get_query_slider_items( array(
				'posts_per_page' => $this->instance['posts_per_page'],
				'tax_query'      => $tax_query,
			) );

			if ( $posts_query ) {
				$html = $this->render_slider( $posts_query );
			} else {
				$html = '<h4>' . esc_html__( 'Posts for slider not found', '__tm' ) . '</h4>';
			}

			echo $html;

			$this->widget_end( $args );
			$this->reset_widget_data();
			wp_reset_postdata();
		}

		/**
		 * Smart Slider rendering.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function render_slider( $posts_query ) {
			$this->instance['shuffle'] = ( count( $posts_query->posts ) > 2 ) ? $this->instance['shuffle'] : 'false';

			$uniq_id = 'slider-pro-' . uniqid();
			$slider_html_attr  = 'data-id="' . $uniq_id . '"';

			/* FIX for `Gallery` mode */
			if ( 'gallery' == $this->instance['mode'] ) {
				$this->instance['slide_fade']  = 'false';
				$this->instance['orientation'] = 'horizontal';

				$slider_html_attr .= ' data-visible-size="100%"';
			} else {
				$slider_html_attr .= ' data-visible-size="auto"';
			}

			$slider_html_attr .= ' data-width="' . esc_attr( $this->instance['width'] ) . '"';
			$slider_html_attr .= ' data-height="' . esc_attr( $this->instance['height'] ) . '"';
			$slider_html_attr .= ' data-orientation="' . esc_attr( $this->instance['orientation'] ) . '"';
			$slider_html_attr .= ' data-slide-distance="' . esc_attr( $this->instance['slide_distance'] ) . '"';
			$slider_html_attr .= ' data-slide-duration="' . esc_attr( $this->instance['slide_duration'] ) . '"';
			$slider_html_attr .= ' data-slide-fade="' . esc_attr( $this->instance['slide_fade'] ) . '"';
			$slider_html_attr .= ' data-navigation="' . esc_attr( $this->instance['navigation'] ) . '"';
			$slider_html_attr .= ' data-fade-navigation="' . esc_attr( $this->instance['fade_navigation'] ) . '"';
			$slider_html_attr .= ' data-pagination="' . esc_attr( $this->instance['pagination'] ) . '"';
			$slider_html_attr .= ' data-autoplay="' . esc_attr( $this->instance['autoplay'] ) . '"';
			$slider_html_attr .= ' data-autoplay-delay="' . esc_attr( $this->instance['autoplay_delay'] ) . '"';
			$slider_html_attr .= ' data-fullScreen="' . esc_attr( $this->instance['fullScreen'] ) . '"';
			$slider_html_attr .= ' data-shuffle="' . esc_attr( $this->instance['shuffle'] ) . '"';
			$slider_html_attr .= ' data-loop="' . esc_attr( $this->instance['loop'] ) . '"';
			$slider_html_attr .= ' data-thumbnails="' . esc_attr( $this->instance['thumbnails'] ) . '"';
			$slider_html_attr .= ' data-thumbnails-arrows="' . esc_attr( $this->instance['thumbnails_arrows'] ) . '"';
			$slider_html_attr .= ' data-thumbnails-position="' . esc_attr( $this->instance['thumbnails_position'] ) . '"';
			$slider_html_attr .= ' data-thumbnails-width="100"';
			$slider_html_attr .= ' data-thumbnails-height="100"';

			$html = '<div class="__tm-smartslider smart-slider__instance invert" ' . $slider_html_attr . '>';
				$html .= '<div id="' . $uniq_id . '" class="slider-pro">';
					$html .= '<div class="smart-slider__items sp-slides">';
						$html .= $this->get_slider_loop( $posts_query );
					$html .= '</div>';

					if ( 'true' == $this->instance['thumbnails'] ) {
						$html .= '<div class="smart-slider__thumbnails sp-thumbnails">';
							$html .= $this->get_slider_thumbnails( $posts_query );
						$html .= '</div>';
					}
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		/**
		 * Get slider items.
		 *
		 * @since  1.0.0
		 * @param  array|string $query_args Arguments to be passed to the query.
		 * @return array|bool               Array if true, boolean if false.
		 */
		public function get_query_slider_items( $query_args = array() ) {

			$defaults_query_args = apply_filters( '__tm_smart_slider_default_query_args', array(
				'post_type'           => 'post',
				'orderby'             => 'date',
				'order'               => 'DESC',
				'posts_per_page'      => -1,
				'offset'              => 0,
				'tax_query'           => array(),
				'ignore_sticky_posts' => 1,
			) );

			$query_args = wp_parse_args( $query_args, $defaults_query_args );
			$query_args = array_intersect_key( $query_args, $defaults_query_args );

			// The Query.
			$posts_query = new WP_Query( $query_args );

			if ( ! is_wp_error( $posts_query ) && $posts_query->have_posts() ) {
				$posts_query->set( 'posts_per_page', $query_args[ 'posts_per_page' ] );

				return $posts_query;
			} else {
				return false;
			}
		}

		/**
		 * Get slider loop.
		 *
		 * @since  1.0.0
		 * @param  array  $posts_query List of WP_Post objects.
		 * @return string
		 */
		public function get_slider_loop( $posts_query ) {
			/**
			 * Title layer settings.
			 *
			 * @link https://github.com/bqworks/slider-pro/blob/master/docs/modules.md
			 * @var  array
			 */
			$title_settings = apply_filters( '__tm_smart_slider_title_settings', array(
				'width'           => "100%",
				'horizontal'      => '0%',
				'vertical'        => '25%',
				'show_transition' => 'left',
				'show_duration'   => 500,
				'show_delay'      => 500,
				'hide_transition' => 'left',
				'hide_duration'   => 500,
				'hide_delay'      => 700,
			), $this->instance );

			$title_attr = $this->generate_layer_attrline( $title_settings );

			/**
			 * Content layer settings.
			 *
			 * @link https://github.com/bqworks/slider-pro/blob/master/docs/modules.md
			 * @var  array
			 */
			$content_settings = apply_filters( '__tm_smart_slider_content_settings', array(
				'width'           => '90%',
				'horizontal'      => '5%',
				'vertical'        => '51%',
				'show_transition' => 'left',
				'show_duration'   => 500,
				'show_delay'      => 800,
				'hide_transition' => 'left',
				'hide_duration'   => 500,
				'hide_delay'      => 400,
			), $this->instance );

			$content_attr = $this->generate_layer_attrline( $content_settings );

			/**
			 * More button layer settings.
			 *
			 * @link https://github.com/bqworks/slider-pro/blob/master/docs/modules.md
			 * @var  array
			 */
			$more_settings = apply_filters( '__tm_smart_slider_more_button_settings', array(
				'width'           => '90%',
				'horizontal'      => '5%',
				'vertical'        => '58%',
				'show_transition' => 'left',
				'show_duration'   => 500,
				'show_delay'      => 1100,
				'hide_transition' => 'left',
				'hide_duration'   => 500,
				'hide_delay'      => 100,
			), $this->instance );

			$more_attr = $this->generate_layer_attrline( $more_settings );

			$html = '';

			while ( $posts_query->have_posts() ) : $posts_query->the_post();
				$html .= '<div class="smart-slider__item sp-slide">';
					$title = $this->utility->attributes->get_title( array(
						'visible' => $this->instance['post_title'],
						'html'    => '<a href="%2$s" %3$s>%4$s</a>',
					) );

					$content = $this->utility->attributes->get_content( array(
						'visible' => $this->instance['content'],
						'length'  => (int) $this->instance['trim_words'],
						'html'    => '%2$s',
					) );

					$button = $this->utility->attributes->get_button( array(
						'visible' => $this->instance['more_button'],
						'text'    => $this->use_wpml_translate( 'more_button_text' ),
						'icon'    => '',
						'html'    => '<a href="%1$s" %2$s %3$s >%4$s%5$s</a>',
					) );

					$html .= $this->utility->media->get_image( array(
						'size'        => '__tm-thumb-xl',
						'mobile_size' => '__tm-thumb-l',
						'class'       => 'sp-image',
						'html'        => '<img %2$s src="%3$s" alt="%4$s" %5$s>',
					) );
					$html .= '<h2 class="sp-title sp-layer"' . $title_attr . '>' . $title . '</h2>';
					$html .= '<div class="sp-content sp-layer"' . $content_attr . '>' . $content . '</div>';
					$html .= '<div class="sp-more sp-layer"' . $more_attr . '>' . $button . '</div>';

				$html .= '</div>';

			endwhile;

			return $html;
		}

		/**
		 * Get thumbnails images list.
		 *
		 * @since  1.0.0
		 * @param  object $posts_query Result post query.
		 * @return string $html
		 */
		public function get_slider_thumbnails( $posts_query ) {
			$html = '';

			while ( $posts_query->have_posts() ) : $posts_query->the_post();
				$html .= '<div class="sp-thumbnail">';
					$html .= $this->utility->media->get_image( array(
						'size' => '__tm-thumb-s',
						'html' => '<img src="%3$s" alt="%4$s" %5$s>',
					) );
				$html .= '</div>';

			endwhile;

			return $html;
		}

		/**
		 * Slider layer attributes line generator.
		 *
		 * @since  1.0.0
		 * @param  array  $settings Attributes line settings.
		 * @return string
		 */
		public function generate_layer_attrline( $settings ) {
			$attr_line = '';

			if ( ! empty( $settings ) && is_array( $settings ) ) {
				$attr_line .= ' data-width="' . esc_attr( $settings['width'] ) . '"';
				$attr_line .= ' data-horizontal="' . esc_attr( $settings['horizontal'] ) . '"';
				$attr_line .= ' data-vertical="' . esc_attr( $settings['vertical'] ) . '"';
				$attr_line .= ' data-show-transition="' . esc_attr( $settings['show_transition'] ) . '"';
				$attr_line .= ' data-show-duration="' . esc_attr( $settings['show_duration'] ) . '"';
				$attr_line .= ' data-show-delay="' . esc_attr( $settings['show_delay'] ) . '"';
				$attr_line .= ' data-hide-transition="' . esc_attr( $settings['hide_transition'] ) . '"';
				$attr_line .= ' data-hide-duration="' . esc_attr( $settings['hide_duration'] ) . '"';
				$attr_line .= ' data-hide-delay="' . esc_attr( $settings['hide_delay'] ) . '"';
			}

			return $attr_line;
		}

		/**
		 * Enqueue javascript and stylesheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {
			if ( is_active_widget( false, false, $this->id_base, true ) ) {
				wp_enqueue_script( 'jquery-slider-pro' );
				wp_enqueue_style( 'jquery-slider-pro' );
			}
		}
	}

	add_action( 'widgets_init', '__tm_register_smart_slider_widgets' );
	function __tm_register_smart_slider_widgets() {
		register_widget( '__Tm_Smart_Slider_Widget' );
	}
}
