<?php
/**
 * Template part for default Header layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package __Tm
 */
?>
<?php __tm_social_list( 'header' ); ?>

<div class="site-branding">
	<?php __tm_header_logo() ?>
	<?php __tm_site_description(); ?>
</div>

<?php __tm_main_menu(); ?>
