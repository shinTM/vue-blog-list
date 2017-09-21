<?php
if ( ! class_exists( '__Tm_Theme_Setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 1.0.0
	 */
	class __Tm_Theme_Setup {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $core = null;

		/**
		 * Holder for CSS layout scheme.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $layout = array();

		/**
		 * Holder for current customizer module instance.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		public $customizer = null;

		/**
		 * Holder for current dynamic_css module instance.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		public $dynamic_css = null;

		/**
		 * Sets up needed actions/filters for the theme to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Set the constants needed by the theme.
			add_action( 'after_setup_theme', array( $this, 'constants' ), -1 );

			// Load the installer core.
			add_action( 'after_setup_theme', require( trailingslashit( get_template_directory() ) . 'cherry-framework/setup.php' ), 0 );

			// Load the core functions/classes required by the rest of the theme.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );

			// Language functions and translations setup.
			add_action( 'after_setup_theme', array( $this, 'l10n' ), 2 );

			// Handle theme supported features.
			add_action( 'after_setup_theme', array( $this, 'theme_support' ), 3 );

			// Load the theme includes.
			add_action( 'after_setup_theme', array( $this, 'includes' ), 4 );

			// Initialization of modules.
			add_action( 'after_setup_theme', array( $this, 'init' ), 10 );

			// Load admin files.
			add_action( 'wp_loaded', array( $this, 'admin' ), 1 );

			// Enqueue admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

			// Register public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 9 );

			// Enqueue public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 10 );

			// Overrides the load textdomain function for the 'cherry-framework' domain.
			add_filter( 'override_load_textdomain', array( $this, 'override_load_textdomain' ), 5, 3 );
		}

		/**
		 * Defines the constant paths for use within the core and theme.
		 *
		 * @since 1.0.0
		 */
		public function constants() {
			global $content_width;

			/**
			 * Fires before definitions the constants.
			 *
			 * @since 1.0.0
			 */
			do_action( '__tm_constants_before' );

			$template  = get_template();
			$theme_obj = wp_get_theme( $template );

			/** Sets the theme version number. */
			define( '__TM_THEME_VERSION', $theme_obj->get( 'Version' ) );

			/** Sets the theme directory path. */
			define( '__TM_THEME_DIR', get_template_directory() );

			/** Sets the theme directory URI. */
			define( '__TM_THEME_URI', get_template_directory_uri() );

			/** Sets the path to the core framework directory. */
			defined( 'CHERRY_DIR' ) or define( 'CHERRY_DIR', trailingslashit( __TM_THEME_DIR ) . 'cherry-framework' );

			/** Sets the path to the core framework directory URI. */
			defined( 'CHERRY_URI' ) or define( 'CHERRY_URI', trailingslashit( __TM_THEME_URI ) . 'cherry-framework' );

			/** Sets the theme includes paths. */
			define( '__TM_THEME_CLASSES', trailingslashit( __TM_THEME_DIR ) . 'inc/classes' );
			define( '__TM_THEME_WIDGETS', trailingslashit( __TM_THEME_DIR ) . 'inc/widgets' );
			define( '__TM_THEME_EXT', trailingslashit( __TM_THEME_DIR ) . 'inc/extensions' );

			/** Sets the theme assets URIs. */
			define( '__TM_THEME_CSS', trailingslashit( __TM_THEME_URI ) . 'assets/css' );
			define( '__TM_THEME_JS', trailingslashit( __TM_THEME_URI ) . 'assets/js' );

			// Sets the content width in pixels, based on the theme's design and stylesheet.
			if ( ! isset( $content_width ) ) {
				$content_width = 710;
			}
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * theme because they have required functions for use.
		 *
		 * @since  1.0.0
		 */
		public function get_core() {
			/**
			 * Fires before loads the core theme functions.
			 *
			 * @since 1.0.0
			 */
			do_action( '__tm_core_before' );

			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );

				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => CHERRY_DIR,
				'base_url' => CHERRY_URI,
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-interface-builder' => array(
						'autoload' => false,
					),
					'cherry-utility' => array(
						'autoload' => true,
						'args'     => array(
							'meta_key' => array(
								'term_thumb' => 'cherry_terms_thumbnails'
							),
						)
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
					'cherry-post-formats-api' => array(
						'autoload' => true,
						'args'     => array(
							'rewrite_default_gallery' => true,
							'gallery_args'            => array(
								'size'          => '__tm-thumb-l',
								'base_class'    => 'post-gallery',
								'container'     => '<div class="%2$s swiper-container" id="%4$s" %3$s><div class="swiper-wrapper">%1$s</div><div class="swiper-button-prev %2$s__button-prev"><i class="material-icons">navigate_before</i></div><div class="swiper-button-next %2$s__button-next"><i class="material-icons">navigate_next</i></div></div>',
								'slide'         => '<figure class="%2$s swiper-slide">%1$s</figure>',
								'img_class'     => 'swiper-image',
								'slider_handle' => 'jquery-swiper',
								'slider'        => 'sliderPro',
								'slider_init'   => array(
									'buttons' => false,
									'arrows'  => true,
								),
								'popup'        => 'magnificPopup',
								'popup_handle' => 'magnific-popup',
								'popup_init'   => array(
									'type' => 'image',
								),
							),
							'image_args' => array(
								'size'         => '__tm-thumb-l',
								'popup'        => 'magnificPopup',
								'popup_handle' => 'magnific-popup',
								'popup_init'   => array(
									'type' => 'image',
								),
							),
						),
					),
					'cherry-customizer' => array(
						'autoload' => false,
					),
					'cherry-dynamic-css' => array(
						'autoload' => false,
					),
					'cherry-google-fonts-loader' => array(
						'autoload' => false,
					),
					'cherry-term-meta' => array(
						'autoload' => false,
					),
					'cherry-post-meta' => array(
						'autoload' => false,
					),
					'cherry-breadcrumbs' => array(
						'autoload' => false,
					),
				),
			) );

			return $this->core;
		}

		/**
		 * Loads the theme translation file.
		 *
		 * @since 1.0.0
		 */
		public function l10n() {
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 */
			load_theme_textdomain( '__tm', trailingslashit( __TM_THEME_DIR ) . 'languages' );
		}

		/**
		 * Adds theme supported features.
		 *
		 * @since 1.0.0
		 */
		public function theme_support() {

			// Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );

			// Enable HTML5 markup structure.
			add_theme_support( 'html5', array(
				'comment-list', 'comment-form', 'search-form', 'gallery', 'caption',
			) );

			// Enable default title tag.
			add_theme_support( 'title-tag' );

			// Enable post formats.
			add_theme_support( 'post-formats', array(
				'aside', 'gallery', 'image', 'link', 'quote', 'video', 'audio', 'status',
			) );

			// Enable custom background.
			add_theme_support( 'custom-background', array( 'default-color' => 'ffffff', ) );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			// Add support for mobile menu
			add_theme_support( 'tm-custom-mobile-menu' );
		}

		/**
		 * Loads the theme files supported by themes and template-related functions/classes.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			/**
			 * Configurations.
			 */
			require_once trailingslashit( __TM_THEME_DIR ) . 'config/layout.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'config/menus.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'config/sidebars.php';
			require_if_theme_supports( 'post-thumbnails', trailingslashit( __TM_THEME_DIR ) . 'config/thumbnails.php' );

			/**
			 * Functions.
			 */
			if ( ! is_admin() ) {
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/template-tags.php';
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/template-menu.php';
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/template-meta.php';
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/template-comment.php';
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/template-related-posts.php';
				require_once trailingslashit( __TM_THEME_DIR ) . 'inc/extras.php';
			}

			require_once trailingslashit( __TM_THEME_DIR ) . 'inc/context.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'inc/customizer.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'inc/hooks.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'inc/wp-rest-api-end-point.php';
			require_once trailingslashit( __TM_THEME_DIR ) . 'inc/register-plugins.php';

			/**
			 * Widgets.
			 */
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'about/class-about-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'about-author/class-about-author-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'banner/class-banner-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'carousel/class-carousel-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'custom-posts/class-custom-posts-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'image-grid/class-image-grid-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'smart-slider/class-smart-slider-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'subscribe-follow/class-subscribe-follow-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'taxonomy-tiles/class-taxonomy-tiles-widget.php';
			require_once trailingslashit( __TM_THEME_WIDGETS ) . 'contact-information/class-contact-information-widget.php';

			/**
			 * Classes.
			 */
			if ( ! is_admin() ) {
				require_once trailingslashit( __TM_THEME_CLASSES ) . 'class-wrapping.php';
			}

			require_once trailingslashit( __TM_THEME_CLASSES ) . 'class-widget-area.php';
			require_once trailingslashit( __TM_THEME_CLASSES ) . 'class-tgm-plugin-activation.php';

			/**
			 * Extensions.
			 */
			require_once trailingslashit( __TM_THEME_EXT ) . 'woocommerce.php';
			require_once trailingslashit( __TM_THEME_EXT ) . 'tm-mega-menu.php';
		}

		/**
		 * Run initialization of modules.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			$this->customizer  = $this->get_core()->init_module( 'cherry-customizer', __tm_get_customizer_options() );
			$this->dynamic_css = $this->get_core()->init_module( 'cherry-dynamic-css', __tm_get_dynamic_css_options() );
			$this->get_core()->init_module( 'cherry-google-fonts-loader', __tm_get_fonts_options() );
			$this->get_core()->init_module( 'cherry-term-meta', array(
				'tax'      => 'category',
				'priority' => 10,
				'fields'   => array(
					'cherry_terms_thumbnails' => array(
						'type'               => 'media',
						'value'              => '',
						'multi_upload'       => false,
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Set thumbnail', '__tm' ),
						'label'              => esc_html__( 'Category thumbnail', '__tm' ),
					),
				),
			) );
			$this->get_core()->init_module( 'cherry-term-meta', array(
				'tax'      => 'post_tag',
				'priority' => 10,
				'fields'   => array(
					'cherry_terms_thumbnails' => array(
						'type'               => 'media',
						'value'              => '',
						'multi_upload'       => false,
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Set thumbnail', '__tm' ),
						'label'              => esc_html__( 'Tag thumbnail', '__tm' ),
					),
				),
			) );
			$this->get_core()->init_module( 'cherry-post-meta', array(
				'id'            => 'post-layout',
				'title'         => esc_html__( 'Page Options', '__tm' ),
				'page'          => array( 'post', 'page' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'fields'        => array(
					'__tm_sidebar_position' => array(
						'type'          => 'radio',
						'title'         => esc_html__( 'Layout', '__tm' ),
						'description'   => esc_html__( 'Sidebar position global settings redefining. If you select inherit option, global setting will be applyed for this layout', '__tm' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label'   => esc_html__( 'Inherit', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'one-left-sidebar' => array(
								'label'   => esc_html__( 'Sidebar on left side', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/page-layout-left-sidebar.svg',
							),
							'one-right-sidebar' => array(
								'label'   => esc_html__( 'Sidebar on right side', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/page-layout-right-sidebar.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'No sidebar', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/page-layout-fullwidth.svg',
							),
						)
					),
					'__tm_header_container_type' => array(
						'type'          => 'radio',
						'title'         => esc_html__( 'Header layout', '__tm' ),
						'description'   => esc_html__( 'Header layout global settings redefining. If you select inherit option, global setting will be applyed for this layout', '__tm' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label'   => esc_html__( 'Header Inherit Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed' => array(
								'label'   => esc_html__( 'Header Boxed Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Header Fullwidth Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						)
					),
					'__tm_content_container_type' => array(
						'type'          => 'radio',
						'title'         => esc_html__( 'Content layout', '__tm' ),
						'description'   => esc_html__( 'Content layout global settings redefining. If you select inherit option, global setting will be applyed for this layout', '__tm' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label'   => esc_html__( 'Content Inherit Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed' => array(
								'label'   => esc_html__( 'Content Boxed Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Content Fullwidth Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						)
					),
					'__tm_footer_container_type' => array(
						'type'          => 'radio',
						'title'         => esc_html__( 'Footer layout', '__tm' ),
						'description'   => esc_html__( 'Footer layout global settings redefining. If you select inherit option, global setting will be applyed for this layout', '__tm' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label'   => esc_html__( 'Footer Inherit Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed' => array(
								'label'   => esc_html__( 'Footer Boxed Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Footer Fullwidth Layout', '__tm' ),
								'img_src' => trailingslashit( __TM_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						)
					),
					'__tm_content_paddings' => array(
						'type'          => 'switcher',
						'title'         => esc_html__( 'Use inherit content paddings?', '__tm' ),
						'description'   => esc_html__( 'If you want using inherit content paddings select Yes option', '__tm' ),
						'value'         => true,
						'toggle'        => array(
							'true_toggle'  => esc_html__( 'Yes', '__tm' ),
							'false_toggle' => esc_html__( 'No', '__tm' ),
							'true_slave'   => '',
							'false_slave'  => '__tm_content_paddings_false_toggle',
						),
					),
					'__tm_content_padding_top' => array(
						'type'          => 'slider',
						'title'         => esc_html__( 'Content top padding', '__tm' ),
						'description'   => esc_html__( 'Content top padding global settings redefining.', '__tm' ),
						'display_input' => false,
						'max_value'     => __tm_get_content_padding_max_value(),
						'min_value'     => 0,
						'value'         => 0,
						'step_value'    => 1,
						'master'        => '__tm_content_paddings_false_toggle',
					),
					'__tm_content_padding_bottom' => array(
						'type'          => 'slider',
						'title'         => esc_html__( 'Content bottom padding', '__tm' ),
						'description'   => esc_html__( 'Content bottom padding global settings redefining.', '__tm' ),
						'display_input' => false,
						'max_value'     => __tm_get_content_padding_max_value(),
						'min_value'     => 0,
						'value'         => 0,
						'step_value'    => 1,
						'master'        => '__tm_content_paddings_false_toggle',
					),
				),
			) );
			$this->get_core()->init_module( 'cherry-post-meta', array(
				'id'            => 'post-header-layout-type',
				'title'         => esc_html__( 'Header Layout Type', '__tm' ),
				'page'          => array( 'post', 'page' ),
				'context'       => 'normal',
				'priority'      => 'low',
				'callback_args' => false,
				'fields'        => array(
					'__tm_header_layout_type' => array(
						'type'          => 'radio',
						'title'         => esc_html__( 'Layout', '__tm' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => __tm_get_header_layout_pm_options(),
					)
				),
			) );
		}

		/**
		 * Load admin files for the theme.
		 *
		 * @since 1.0.0
		 */
		public function admin() {

			// Check if in the WordPress admin.
			if ( ! is_admin() ) {
				return;
			}
		}

		/**
		 * Enqueue admin-specific assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_admin_assets( $hook ) {

			$available_pages = array(
				'widgets.php',
			);

			if ( ! in_array( $hook, $available_pages ) ) {
				return;
			}

			wp_enqueue_style( '__tm-admin-style', __TM_THEME_CSS . '/admin.min.css', array(), __TM_THEME_VERSION );
		}

		/**
		 * Register assets.
		 *
		 * @since 1.0.0
		 */
		public function register_assets() {
			wp_register_script( 'jquery-slider-pro', __TM_THEME_JS . '/min/jquery.slider-pro.min.js', array( 'jquery' ), '1.2.4', true );
			wp_register_script( 'jquery-swiper', __TM_THEME_JS . '/min/swiper.jquery.min.js', array( 'jquery' ), '3.4.0', true );
			wp_register_script( 'magnific-popup', __TM_THEME_JS . '/min/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
			wp_register_script( 'jquery-stickup', __TM_THEME_JS . '/min/jquery.stickup.min.js', array( 'jquery' ), '1.0.0', true );
			wp_register_script( 'jquery-totop', __TM_THEME_JS . '/min/jquery.ui.totop.min.js', array( 'jquery' ), '1.2.0', true );
			wp_register_script( 'jquery-cherry-responsive-menu', __TM_THEME_JS . '/cherry-responsive-menu.js', array( 'jquery' ), '1.0.0', true );


			wp_register_script( 'vue-js', 'https://unpkg.com/vue/dist/vue.js', array(), '1.0.0', true );
			wp_register_script( 'vue-resource', 'https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js', array(), '1.0.0', true );
			wp_register_script( 'vue-posts-list', __TM_THEME_JS . '/vue-posts-list.js', array( 'vue-js', 'vue-resource' ), '1.0.0', true );

			wp_register_style( 'jquery-slider-pro', __TM_THEME_CSS . '/slider-pro.min.css', array(), '1.2.4' );
			wp_register_style( 'jquery-swiper', __TM_THEME_CSS . '/swiper.min.css', array(), '3.4.0' );
			wp_register_style( 'magnific-popup', __TM_THEME_CSS . '/magnific-popup.min.css', array(), '1.1.0' );
			wp_register_style( 'font-awesome', __TM_THEME_CSS . '/font-awesome.min.css', array(), '4.7.0' );
			wp_register_style( 'material-icons', __TM_THEME_CSS . '/material-icons.min.css', array(), '2.2.0' );
		}

		/**
		 * Enqueue assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {
			wp_enqueue_style( '__tm-theme-style', get_stylesheet_uri(), array(
					'font-awesome',
					'material-icons',
					'magnific-popup',
				),
				__TM_THEME_VERSION
			);

			/**
			 * Filter the depends on main theme script.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$depends = apply_filters( '__tm_theme_script_depends', array(
				'cherry-js-core',
				'hoverIntent',
				'jquery-cherry-responsive-menu',
			) );

			wp_enqueue_script( 'vue-posts-list' );
			wp_enqueue_script( '__tm-theme-script', __TM_THEME_JS . '/theme-script.js', $depends, __TM_THEME_VERSION, true );

			/**
			 * Filter the strings that send to scripts.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$labels = apply_filters( '__tm_theme_localize_labels', array(
				'totop_button' => esc_html__( 'Top', '__tm' ),
			) );

			$more_button_options = apply_filters( '__tm_theme_more_button_options', array(
				'more_button_type'             => get_theme_mod( 'more_button_type', __tm_theme()->customizer->get_default( 'more_button_type' ) ),
				'more_button_text'             => get_theme_mod( 'more_button_text', __tm_theme()->customizer->get_default( 'more_button_text' ) ),
				'more_button_icon'             => get_theme_mod( 'more_button_icon', __tm_theme()->customizer->get_default( 'more_button_icon' ) ),
				'more_button_image_url'        => get_theme_mod( 'more_button_image_url', __tm_theme()->customizer->get_default( 'more_button_image_url' ) ),
				'retina_more_button_image_url' => get_theme_mod( 'retina_more_button_image_url', __tm_theme()->customizer->get_default( 'retina_more_button_image_url' ) ),
				'menu_clotting'                => get_theme_mod( 'more_button_clotting', __tm_theme()->customizer->get_default( 'more_button_clotting' ) ),
			) );

			wp_localize_script( '__tm-theme-script', '__tm', apply_filters(
				'__tm_theme_script_variables',
				array(
					'ajaxurl'             => esc_url( admin_url( 'admin-ajax.php' ) ),
					'labels'              => $labels,
					'more_button_options' => $more_button_options,
			) ) );

			wp_localize_script( 'vue-posts-list', 'vuePostsList', apply_filters(
				'__tm_theme_vue_posts_list',
				array(
					'siteUrl' => esc_url( home_url( '/' ) ),
					'root'    => esc_url_raw( rest_url() ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
				)
			) );

			// Threaded Comments.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}

		/**
		 * Overrides the load textdomain functionality when 'cherry-framework' is the domain in use.
		 *
		 * @since  1.0.0
		 * @link   https://gist.github.com/justintadlock/7a605c29ae26c80878d0
		 * @param  bool   $override
		 * @param  string $domain
		 * @param  string $mofile
		 * @return bool
		 */
		public function override_load_textdomain( $override, $domain, $mofile ) {

			// Check if the domain is our framework domain.
			if ( 'cherry-framework' === $domain ) {

				global $l10n;

				// If the theme's textdomain is loaded, assign the theme's translations
				// to the framework's textdomain.
				if ( isset( $l10n['__tm'] ) ) {
					$l10n[ $domain ] = $l10n['__tm'];
				}

				// Always override.  We only want the theme to handle translations.
				$override = true;
			}

			return $override;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

/**
 * Returns instanse of main theme configuration class.
 *
 * @since  1.0.0
 * @return object
 */
function __tm_theme() {
	return __Tm_Theme_Setup::get_instance();
}

__tm_theme();
