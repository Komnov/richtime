<?php

$terms = get_terms(
	[
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'child_of'   => 38,
	]
);
if ( ! empty( $terms ) ) :
	?>
	<div class="menu-terms">
		<ul class="menu-terms__list">
			<?php foreach ( $terms as $term ) : ?>
				<li><a href="<?php echo get_term_link( $term->slug, $term->taxonomy ) ?>"><?php echo $term->name ?></a>
					<img class="menu-terms__term-image" src="<?php echo get_term_image( $term->term_id, 'full' ) ?>" alt="">
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="image-block"></div>
	</div>
<?php endif;