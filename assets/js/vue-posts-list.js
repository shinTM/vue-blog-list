(function($){
	"use strict";

	var vuePostsList = {

		init: function() {

			Vue.component( 'posts-list', {
				template: '#posts-list-template',
				props: [ 'posts' ]
			} );

			Vue.component( 'more-button', {
				template: '#more-button-template',
				props: [ 'page', 'totalPages' ],
				data: function () {
					return {
						active: true
					}
				},
				methods: {
					loadMore: function() {
						var currentPage = this.page;

						currentPage++;

						if ( this.page >= this.totalPages ) {
							this.active = false;
							return false;
						}

						this.$emit( 'load-more', currentPage );
					}
				}
			} );

			var app = new Vue({
				el: '#vue-posts-list',
				data: {
					posts: [],
					totalPosts: 0,
					totalPages: 0,
					postPerPage: 4,
					page: 1
				},
				methods: {
					fetchPosts: function( page ) {
						var restApiUrl = `${ window.vuePostsList.siteUrl }/wp-json/wp/v2/posts`,
							options = {
								params: {
									page: page,
									per_page: this.postPerPage
								}
							};

						this.$http.get( restApiUrl, options ).then( function( response ) {

							this.posts = response.body;
							console.log( this.posts );

							this.totalPosts = parseInt( response.headers.get( 'X-WP-Total' ) );

							this.totalPages = parseInt( response.headers.get( 'X-WP-TotalPages' ) );

							this.page = page;

						}, console.log );
					}
				},
				created: function() {
					this.fetchPosts( this.page );
				}
			}); // End Vue Instance

		}
	};

	vuePostsList.init();

}(jQuery));
