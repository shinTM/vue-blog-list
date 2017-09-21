<?php
/*
Widget Name: Carousel widget
Description: This widget is used to display a list of your posts in a carousel layout
Settings:
 Title - Widget's text title
 Choose taxonomy type - Choose posts source type
 Select category / Select tag - Choose tags or categories as your posts source
 Posts count - Limit the posts
 Display title - Choose whether to display post title
 Display content - Choose whether to display post content
 Display more button - Choose whether to display a more button
 Content words trimmed count - Limit the post content
 Number of slides per view - Choose a number of slides per view
 Number of slides per group - Choose a number of slides per group
 Multirow Slides Layout - Choose a number of rows
 Width of the space between slides (px) - Choose a distance between slides
 Duration of transition between slides (ms) - Choose the slides animation speed
 Slider navigation - Toggle the slider navigation
 Slider pagination - Toggle the slider pagination
*/

/**
 * @package __Tm
 */
if ( ! class_exists( '__Tm_Carousel_Widget' ) ) {

	class __Tm_Carousel_Widget extends Cherry_Abstract_Widget {

		/**
		 * Contain utility module from Cherry Framework.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $utility = null;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-carousel';
			$this->widget_description = esc_html__( 'Display a list of your posts on your site.', '__tm' );
			$this->widget_id          = '__tm_widget_carousel';
			$this->widget_name        = esc_html__( 'Carousel', '__tm' );
			$this->utility            = __tm_utility()->utility;
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title', '__tm' ),
				),
				'terms_type' => array(
					'type'  => 'radio',
					'value' => 'category',
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
					'value'     => 10,
					'max_value' => 20,
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
					'type'   => 'switcher',
					'value'  => 'false',
					'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label'  => esc_html__( 'Display more button', '__tm' ),
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
				'slides_per_view' => array(
					'type'      => 'slider',
					'max_value' => 25,
					'min_value' => 1,
					'value'     => 5,
					'label'     => esc_html__( 'Number of slides per view', '__tm' ),
				),
				'slides_per_group' => array(
					'type'      => 'slider',
					'max_value' => 25,
					'min_value' => 1,
					'value'     => 1,
					'label'     => esc_html__( 'Number slides per group', '__tm' ),
				),
				'slides_per_column' => array(
					'type'      => 'slider',
					'max_value' => 5,
					'min_value' => 1,
					'value'     => 1,
					'label'     => esc_html__( 'Multi Row Slides Layout', '__tm' ),
				),
				'space_between_slides' => array(
					'type'      => 'slider',
					'max_value' => 100,
					'min_value' => 0,
					'value'     => 30,
					'label'     => esc_html__( 'Width of the space between slides(px)', '__tm' ),
				),
				'duration_speed' => array(
					'type'      => 'slider',
					'max_value' => 5000,
					'min_value' => 100,
					'value'     => 500,
					'label'     => esc_html__( 'Duration of transition between slides (ms)', '__tm' ),
				),
				'navigation' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Slider navigation', '__tm' ),
				),
				'pagination' => array(
					'type'  => 'switcher',
					'value' => 'true',
					'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
					'label' => esc_html__( 'Slider pagination', '__tm' ),
				),
			);

			parent::__construct();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 9 );
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 * @since 1.0.0
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$template_container = locate_template( 'inc/widgets/carousel/views/container.php', false, false );
			$template_item      = locate_template( 'inc/widgets/carousel/views/item.php', false, false );

			if ( empty( $template_container ) || empty( $template_item ) ) {
				return '<h5>' . esc_html__( 'View file not found', '__tm' ) . '</h5>';
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );
			$this->setup_instance( $args );

			$instance = uniqid();

			$data_attr_line = 'class="__tm-carousel swiper-container"';
			$data_attr_line .= ' data-uniq-id="swiper-carousel-' . $instance . '"';
			$data_attr_line .= ' data-slides-per-view="' . esc_attr( $this->instance['slides_per_view'] ) . '"';
			$data_attr_line .= ' data-slides-per-group="' . esc_attr( $this->instance['slides_per_group'] ) . '"';
			$data_attr_line .= ' data-slides-per-column="' . esc_attr( $this->instance['slides_per_column'] ) . '"';
			$data_attr_line .= ' data-space-between-slides="' . esc_attr( $this->instance['space_between_slides'] ) . '"';
			$data_attr_line .= ' data-duration-speed="' . esc_attr( $this->instance['duration_speed'] ) . '"';
			$data_attr_line .= ' data-swiper-loop="false"';
			$data_attr_line .= ' data-free-mode="false"';
			$data_attr_line .= ' data-grab-cursor="true"';
			$data_attr_line .= ' data-mouse-wheel="false"';

			$pagination_html = ( 'true' == $this->instance['pagination'] ) ? '<div id="swiper-carousel-'. $instance . '-pagination" class="swiper-pagination"></div>' : '';
			$navigation_html = ( 'true' == $this->instance['navigation'] ) ? '<div id="swiper-carousel-'. $instance . '-next" class="swiper-button-next widget-carousel__button-next"><i class="material-icons">navigate_next</i></div><div id="swiper-carousel-'. $instance . '-prev" class="swiper-button-prev widget-carousel__button-prev"><i class="material-icons">navigate_before</i></div>' : '';

			$categories_array = ( isset( $this->instance['categories'] ) ) ? $this->instance['categories'] : array();
			$tags_array       = ( isset( $this->instance['tags'] ) ) ? $this->instance['tags'] : array();

			$tax_query = array();

			if ( 'category' == $this->instance['terms_type'] ) {
				if ( ( is_array( $categories_array ) && ! empty( $categories_array ) ) ) {
					array_push( $tax_query, array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories_array,
					) );
				}
			} else {
				if ( ( is_array( $tags_array ) && ! empty( $tags_array ) ) ) {
					array_push( $tax_query, array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $tags_array,
					) );
				}
			}

			// The Query.
			$posts_query = $this->get_query_items( apply_filters( '__tm_carousel_query_args', array(
				'posts_per_page' => $this->instance['posts_per_page'],
				'tax_query'      => $tax_query,
			), $this ) );

			if ( $posts_query ) {
				include $template_container;

			} else {
				echo '<h5>' . esc_html__( 'Posts not found', '__tm' ) . '</h5>';
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			wp_reset_postdata();
		}

		/**
		 * Get carousel items.
		 *
		 * @since  1.0.0
		 * @param  array|string $args Arguments to be passed to the query.
		 * @return array|bool         Array if true, boolean if false.
		 */
		public function get_query_items( $args = array() ) {
			$defaults = apply_filters( '__tm_carousel_query_defaults', array(
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => -1,
				'tax_query'      => array(),
			), $this );

			$args = wp_parse_args( $args, $defaults );
			$args = array_intersect_key( $args, $defaults );

			// The Query.
			$posts_query = new WP_Query( $args );

			if ( ! is_wp_error( $posts_query ) && $posts_query->have_posts() ) {
				return $posts_query;

			} else {
				return false;
			}
		}

		/**
		 * Get carousel items.
		 *
		 * @since  1.0.0
		 * @param  array  $posts_query List of WP_Post objects.
		 * @param  string $template    Path to a template file.
		 * @return string
		 */
		public function get_carousel_loop( $posts_query, $template ) {

			while ( $posts_query->have_posts() ) : $posts_query->the_post();

				$permalink = $this->utility->media->get_post_permalink();

				$date = $this->utility->meta_data->get_date( array(
					'class' => 'post__date',
					'icon'  => '<i class="material-icons">event</i>',
				) );

				$author = $this->utility->meta_data->get_author( array(
					'class' => 'posted-by__author',
					'icon'  => '',
				) );

				$title = $this->utility->attributes->get_title( array(
					'visible' => $this->instance['post_title'],
					'html'    => '<h5 %1$s><a href="%2$s" %3$s>%4$s</a></h5>',
				) );

				$image = $this->utility->media->get_image( array(
					'size'              => '__tm-thumb-560-350',
					'mobile_size'       => '__tm-thumb-s',
					'class'             => 'post-thumbnail__link',
					'html'              => '<a href="%1$s" %2$s ><img src="%3$s" alt="%4$s" %5$s></a>',
					'placeholder_title' => strip_tags( $title ),
				) );

				$content = $this->utility->attributes->get_content( array(
					'visible' => $this->instance['content'],
					'length'  => (int) $this->instance['trim_words'],
					'class'   => 'post__excerpt',
				) );

				$more_button = $this->utility->attributes->get_button( array(
					'visible' => $this->instance['more_button'],
					'text'    => $this->use_wpml_translate( 'more_button_text' ),
					'class'   => 'btn btn-secondary',
					'icon'    => '',
				) );

				$comments = $this->utility->meta_data->get_comment_count( array(
					'class' => 'post__comments',
				) );

				$terms_line = $this->utility->meta_data->get_terms( array(
					'type'   => $this->instance['terms_type'],
					'before' => '<div class="post__cats">',
					'after'  => '</div>',
					'icon'   => '',
				) );

				include $template;

			endwhile;
		}

		/**
		 * Enqueue javascript and stylesheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {
			wp_enqueue_style( 'jquery-swiper' );
			wp_enqueue_script( 'jquery-swiper' );
		}

		/**
		 * Setup widget instance.
		 *
		 * @since  1.0.0
		 * @param  array $args
		 * @return void
		 */
		public function setup_instance( $args ) {

			if ( ! in_array( $args['id'], array( 'sidebar', 'footer-area' ) ) ) {
				return;
			}

			if ( 'sidebar' == $args['id'] ) {
				$this->_prepare_slide_params();
				return;
			}

			$footer_widget_columns = get_theme_mod(
				'footer_widget_columns',
				__tm_theme()->customizer->get_default( 'footer_widget_columns' )
			);

			if ( in_array( $footer_widget_columns, array( '2', '3', '4' ) ) ) {
				$this->_prepare_slide_params();
			}
		}

		/**
		 * Prepare slider parameters.
		 *
		 * @since 1.0.0
		 */
		public function _prepare_slide_params() {
			$this->instance['slides_per_view']   = 1;
			$this->instance['slides_per_group']  = 1;
			$this->instance['slides_per_column'] = 1;
		}
	}

	add_action( 'widgets_init', '__tm_register_carosel_widgets' );
	function __tm_register_carosel_widgets() {
		register_widget( '__Tm_Carousel_Widget' );
	}
}
