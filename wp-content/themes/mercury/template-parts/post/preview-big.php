<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mercury
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'preview-big' ); ?>>

	<?php mercury_post_thumbnail( 'medium-large' ); ?>

	<div class="post-info">

		<header class="entry-header">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>

		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
			the_excerpt();
			?>
		</div><!-- .entry-content -->

		<div class="row">
			<div class="col-md-12">
				<div class="mercury-post-meta">
					<?php
					mercury_post_views();
					mercury_post_comments_count();
					mercury_posted_on();
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><?php mercury_post_tags( 3 ); ?></div>
		</div>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
