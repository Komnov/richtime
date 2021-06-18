<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mercury
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="container">
			<div class="row">
				<?php woocommerce_content(); ?>
			</div>
		</div>
		<?php do_action( 'mercury_woocommerce_after_content' ); ?>
	</main><!-- #main -->

<?php
get_footer();
