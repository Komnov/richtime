<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mercury
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php mercury_post_thumbnail(); ?>

	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		mercury_post_tags(1); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_excerpt();
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php mercury_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
