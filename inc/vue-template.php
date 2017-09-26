<?php

function generate_vue_template(){
	?>
	<script type="text/x-template" id="posts-list-template">
		<section class="vue-posts-list">
			<div class="vue-posts-list__item" v-for="post in posts">
				<figure class="vue-posts-list__figure">
					<img :src="post.featured_image_src" alt="">
				</figure>
				<h4 class="vue-posts-list__title">{{ post.title.rendered }}</h4>
				<p class="vue-posts-list__content" v-html="post.excerpt.rendered"></p>
			</div>
		</section>
	</script>

	<script type="text/x-template" id="more-button-template">
		<div class="more-button-wrapper" v-if="active">
			<button @click.prevent="loadMore">More posts</button>
		</div>
	</script>
<?php
}

add_action( 'wp_footer', 'generate_vue_template' );
