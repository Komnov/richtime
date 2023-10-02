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
 * @package richtime
 */

get_header();
?>
<script src="/html2pdf.bundle.js"></script>
<script>
function generatePDF() {
    const element = document.getElementById('invoice');
    html2pdf()
        .from(element)
         .save();
}
</script>
	<main id="primary" class="site-main">
		<div class="container" id="invoice">
			<div class="row">
				<?php woocommerce_content(); ?>
			</div>
		</div>
		<?php do_action( 'richtime_woocommerce_after_content' ); ?>
	</main><!-- #main -->

<?php
get_footer();
