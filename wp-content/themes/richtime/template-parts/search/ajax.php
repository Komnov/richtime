<?php

$search_products = new WP_Query(
	[
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 20,
	]
);

if ( $search_products->have_posts() ) : ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<p><strong><?php _e( 'Search result' ) ?></strong><button type="button" class="search-close"><i class="bi bi-x-lg"></i></button></p>
				<p><?php echo sprintf( '%d position', $search_products->found_posts ) ?></p>
			</div>
		</div>
		<div class="row">
			<?php
			while ( $search_products->have_posts() ) : $search_products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile; ?>
		</div>
	</div>
<?php
endif;
wp_reset_postdata();