<?php
/**
 * The template for displaying all single posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mercury
 */

get_header();
?>
<?php
while ( have_posts() ) :
	the_post(); ?>

	<main id="primary" class="site-main">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1 class="title"><?php the_title() ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="row align-items-center">
						<div class="col-md-8"><?php mercury_post_tags( 3 ); ?></div>
						<div class="col-md-4">
							<div class="mercury-post-meta">
								<?php
								mercury_post_views();
								mercury_post_comments_count();
								mercury_posted_on();
								?>
							</div>
						</div>
					</div>
					<div class="post-content">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="col-md-6">
					<?php the_post_thumbnail( 'medium-large' ); ?>
				</div>
			</div>
			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) : ?>
				<div class="row">
					<div class="col-md-6">
						<?php comments_template(); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>


	</main><!-- #main -->
<?php
endwhile; // End of the loop.
?>
	?>

<?php
get_footer();
