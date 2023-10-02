<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package richtime
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php richtime_post_thumbnail( 'thumbnail' ); ?>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>

	</header><!-- .entry-header -->
</article><!-- #post-<?php the_ID(); ?> -->
