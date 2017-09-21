(function($){
	"use strict";

	var vuePostsList = {

		init: function() {

			new Vue({
				el: '#vue-posts-list',
				data: {
					posts: [],
					totalPosts: 0,
					totalPages: 0,
					postPerPage: 9,
					page: 1
				},
				methods: {
					fetchPhotos: function(page) {
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
					this.fetchPhotos( this.page );
				}
			}); // End Vue Instance

		}
	};

	vuePostsList.init();

}(jQuery));
