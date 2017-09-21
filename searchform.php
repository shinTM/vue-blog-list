<?php
/**
 * The template for displaying search form.
 *
 * @package __Tm
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', '__tm' ) ?></span>
		<input type="search" class="search-form__field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', '__tm' ) ?>" value="<?php echo get_search_query() ?>" name="s">
	</label>
	<button type="submit" class="search-form__submit btn btn-primary"><i class="material-icons">search</i></button>
</form>
