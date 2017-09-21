(function($){
	"use strict";

	CherryJsCore.utilites.namespace('theme_script');
	CherryJsCore.theme_script = {
		init: function () {
			// Document ready event check
			if( CherryJsCore.status.is_ready ){
				this.document_ready_render();
			}else{
				CherryJsCore.variable.$document.on( 'ready', this.document_ready_render.bind( this ) );
			}

			// Windows load event check
			if( CherryJsCore.status.on_load ){
				this.window_load_render();
			}else{
				CherryJsCore.variable.$window.on( 'load', this.window_load_render.bind( this ) );
			}
		},

		document_ready_render: function () {
			this.responsiveMenuInit( this );
			this.smart_slider_init( this );
			this.swiper_carousel_init( this );
			this.post_formats_custom_init( this );
			this.navbar_init( this );
			this.subscribe_init( this );
			this.to_top_init( this );
		},

		window_load_render: function () {
			this.page_preloader_init( this );
		},

		responsiveMenuInit: function( self ) {
			var moreMenuContent = '&middot;&middot;&middot;',
				imgurl,
				srcset,
				clotting = true;

			if ( window.__tm && window.__tm.more_button_options && window.__tm.more_button_options.more_button_type ) {
				switch ( window.__tm.more_button_options.more_button_type ) {
					case 'image':
						imgurl = window.__tm.more_button_options.more_button_image_url;

						if ( window.__tm.more_button_options.retina_more_button_image_url ) {
							srcset = ' srcset="' + window.__tm.more_button_options.retina_more_button_image_url + ' 2x"';
						}
						moreMenuContent = '<img src="' + imgurl + '"' + srcset + ' alt="' + moreMenuContent + '">';
					break;
					case 'icon':
						moreMenuContent = '<i class="fa ' + window.__tm.more_button_options.more_button_icon + '"></i>';
					break;
					case 'text':
					default:
						moreMenuContent = window.__tm.more_button_options.more_button_text || moreMenuContent;
					break;
				}

				clotting = window.__tm.more_button_options.menu_clotting;
			}

			$( '.main-navigation' ).cherryResponsiveMenu({
				moreMenuContent: moreMenuContent,
				clotting:        clotting
			});

		},

		smart_slider_init: function( self ) {
			$( '.__tm-smartslider' ).each( function() {
				var slider = $(this),
					sliderId = slider.data('id'),
					sliderWidth = slider.data('width'),
					sliderHeight = slider.data('height'),
					sliderOrientation = slider.data('orientation'),
					slideDistance = slider.data('slide-distance'),
					slideDuration = slider.data('slide-duration'),
					sliderFade = slider.data('slide-fade'),
					sliderNavigation = slider.data('navigation'),
					sliderFadeNavigation = slider.data('fade-navigation'),
					sliderPagination = slider.data('pagination'),
					sliderAutoplay = slider.data('autoplay'),
					sliderAutoplayDelay = slider.data('autoplay-delay'),
					sliderFullScreen = slider.data('fullscreen'),
					sliderShuffle = slider.data('shuffle'),
					sliderLoop = slider.data('loop'),
					sliderThumbnailsArrows = slider.data('thumbnails-arrows'),
					sliderThumbnailsPosition = slider.data('thumbnails-position'),
					sliderThumbnailsWidth = slider.data('thumbnails-width'),
					sliderThumbnailsHeight = slider.data('thumbnails-height'),
					sliderVisibleSize = slider.data('visible-size'),
					sliderForceSize = slider.data('force-size');

				if ( $('.smart-slider__items', '#' + sliderId ).length > 0 ) {
					$( '#' + sliderId ).sliderPro( {
						width: sliderWidth,
						height: sliderHeight,
						visibleSize: sliderVisibleSize,
						forceSize: sliderForceSize,
						orientation: sliderOrientation,
						slideDistance: slideDistance,
						slideAnimationDuration: slideDuration,
						fade: sliderFade,
						arrows: sliderNavigation,
						fadeArrows: sliderFadeNavigation,
						buttons: sliderPagination,
						autoplay: sliderAutoplay,
						autoplayDelay: sliderAutoplayDelay,
						fullScreen: sliderFullScreen,
						shuffle: sliderShuffle,
						loop: sliderLoop,
						waitForLayers: false,
						thumbnailArrows: sliderThumbnailsArrows,
						thumbnailsPosition: sliderThumbnailsPosition,
						thumbnailWidth: sliderThumbnailsWidth,
						thumbnailHeight: sliderThumbnailsHeight,
						init: function() {
							$( this ).resize();
						},
						sliderResize: function( event ) {

							var thisSlider = $( '#' + sliderId ),
								slides = $( '.sp-slide', thisSlider );

								slides.each( function() {

									if ( $( '.sp-title', this ).width() > $( this ).width() ) {
										$( this ).addClass( 'text-wrapped' );
									}

								} );
						},
						breakpoints: {
							1200: {
								height: parseFloat( sliderHeight ) * 0.75
							},
							900: {
								height: parseFloat( sliderHeight ) * 0.5
							}
						}
					} );
				}
			});//each end
		},

		swiper_carousel_init: function ( self ) {

			// Enable swiper carousels
			jQuery('.__tm-carousel').each( function() {
				var swiper = null,
					uniqId = jQuery(this).data('uniq-id'),
					slidesPerView = parseFloat( jQuery(this).data('slides-per-view') ),
					slidesPerGroup = parseFloat( jQuery(this).data('slides-per-group') ),
					slidesPerColumn = parseFloat( jQuery(this).data('slides-per-column') ),
					spaceBetweenSlides = parseFloat( jQuery(this).data('space-between-slides') ),
					durationSpeed = parseFloat( jQuery(this).data('duration-speed') ),
					swiperLoop = jQuery(this).data('swiper-loop'),
					freeMode = jQuery(this).data('free-mode'),
					grabCursor = jQuery(this).data('grab-cursor'),
					mouseWheel = jQuery(this).data('mouse-wheel'),
					breakpointsSettings = {
						1600: {
							slidesPerView: Math.floor( slidesPerView * 0.75 ),
							spaceBetween: Math.floor( spaceBetweenSlides * 0.75 )
						},
						1200: {
							slidesPerView: Math.floor( slidesPerView * 0.5 ),
							spaceBetween: Math.floor( spaceBetweenSlides * 0.5 )
						},
						900: {
							slidesPerView: ( 0 !== Math.floor( slidesPerView * 0.25 ) ) ? Math.floor( slidesPerView * 0.25 ) : 1
						}
					};

					if ( 1 == slidesPerView ) {
						breakpointsSettings = {}
					}

				var swiper = new Swiper( '#' + uniqId, {
						slidesPerView: slidesPerView,
						slidesPerGroup: slidesPerGroup,
						slidesPerColumn: slidesPerColumn,
						spaceBetween: spaceBetweenSlides,
						speed: durationSpeed,
						loop: swiperLoop,
						freeMode: freeMode,
						grabCursor: grabCursor,
						mousewheelControl: mouseWheel,
						paginationClickable: true,
						nextButton: '#' + uniqId + '-next',
						prevButton: '#' + uniqId + '-prev',
						pagination: '#' + uniqId + '-pagination',
						onInit: function(){
							$( '#' + uniqId + '-next' ).css({ 'display': 'block' });
							$( '#' + uniqId + '-prev' ).css({ 'display': 'block' });
						},
						breakpoints: breakpointsSettings
					}
				);
			});
		},

		post_formats_custom_init: function ( self ) {
			CherryJsCore.variable.$document.on( 'cherry-post-formats-custom-init', function( event ) {

				if ( 'slider' !== event.object ) {
					return;
				}

				var uniqId = '#' + event.item.attr( 'id' ),
					swiper = new Swiper( uniqId, {
						pagination: uniqId + ' .swiper-pagination',
						paginationClickable: true,
						nextButton: uniqId + ' .swiper-button-next',
						prevButton: uniqId + ' .swiper-button-prev',
						spaceBetween: 30,
						onInit: function(){
							$( uniqId + ' .swiper-button-next' ).css( { 'display': 'block' } );
							$( uniqId + ' .swiper-button-prev' ).css( { 'display': 'block' } );
						}
					} );

				event.item.data( 'initalized', true );
			});

			var items = [];

			$('.mini-gallery .post-thumbnail__link').on('click', function(event) {
				event.preventDefault();

				$(this).parents('.mini-gallery').find('.post-gallery__slides > a[href]').each(function() {
					items.push({
						src: $(this).attr('href'),
						type: 'image'
					});
				});

				$.magnificPopup.open({
					items: items,
					gallery: {
						enabled: true
					}
				});
			});
		},

		navbar_init: function ( self ) {

			$( window ).load( function() {

				var $navbar = $('.header-container');

				if ( ! $.isFunction( jQuery.fn.stickUp ) || ! $navbar.length ) {
					return !1;
				}

				$navbar.stickUp({
					correctionSelector: '#wpadminbar',
					listenSelector: '.listenSelector',
					pseudo: true,
					active: true
				});
				CherryJsCore.variable.$document.trigger( 'scroll.stickUp' );

			});
		},

		subscribe_init: function( self ) {

			CherryJsCore.variable.$document.on( 'click', '.subscribe-block__submit', function( event ){

				event.preventDefault();

				var $this       = $(this),
					form       = $this.parents( 'form' ),
					nonce      = form.find( 'input[name="nonce"]' ).val(),
					mail_input = form.find( 'input[name="subscribe-mail"]' ),
					mail       = mail_input.val(),
					error      = form.find( '.subscribe-block__error' ),
					success    = form.find( '.subscribe-block__success' ),
					hidden     = 'hidden';

				if ( '' === mail ) {
					mail_input.addClass( 'error' );
					error.removeClass( hidden );
					return !1;
				}

				if ( $this.hasClass( 'processing' ) ) {
					return !1;
				}

				$this.addClass( 'processing' );

				error.addClass( hidden );
				success.addClass( hidden );
				mail_input.removeClass( 'error' );

				$.ajax({
					url: __tm.ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: '__tm_subscribe',
						mail: mail,
						nonce: nonce
					},
					error: function() {
						$this.removeClass( 'processing' );
					}
				}).done( function( response ) {

					$this.removeClass( 'processing' );

					if ( true === response.success ) {
						success.removeClass( hidden );
						mail_input.val('');
						return 1;
					}

					mail_input.addClass( 'error' );
					error.removeClass( hidden );
					return !1;

				});

			});

		},

		page_preloader_init: function ( self ) {

			if ( $( '.page-preloader-cover' )[0] ) {
				$( '.page-preloader-cover' ).delay( 500 ).fadeTo( 500, 0, function() {
					$( this ).remove();
				});
			}
		},

		to_top_init: function ( self ) {
			if ( $.isFunction( jQuery.fn.UItoTop ) ) {
				$().UItoTop({
					text: __tm.labels.totop_button,
					scrollSpeed: 600
				});
			}
		}
	}
	CherryJsCore.theme_script.init();
}(jQuery));
