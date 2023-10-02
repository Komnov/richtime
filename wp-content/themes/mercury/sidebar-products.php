<section class="news-products">
	<div class="container-fluid">
		<div class="row">
			<?php
			$new_products = new WP_Query(
				[
					'posts_per_page' => 8,
					'post_type'      => 'product',
					'post_status'    => 'publish',
				]
			);
			?>
			<div class="col-md-12">
				<?php if ( $new_products->have_posts() ) : ?>
					<div id="new-products">
						<?php while ( $new_products->have_posts() ) : $new_products->the_post(); ?>
							<?php wc_get_template_part( 'content', 'product' ); ?>
						<?php endwhile; ?>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</section>