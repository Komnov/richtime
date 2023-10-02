<?php

/**
 * Template Name: Список желаний
 */
get_header();
while ( have_posts() ) : the_post(); ?>
	<div class="products-wrapper">
		<div class="container">
			<div class="row">
				<h1 class="title"><?php echo the_title() ?></h1>
			</div>
			<?php
			global $favorites;
			if ( ! empty( $favorites ) ) :
				$wishlist = new WP_Query(
					[
						'posts_per_page' => 8,
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'post__in'       => $favorites,
					]
				);

				if ( $wishlist->have_posts() ) : ?>
					<div class="row">
						<div class="col-md-3">
							<?php
							$catalog_orderby_options = [
								'menu_order' => __( 'Default sorting', 'woocommerce' ),
								'popularity' => __( 'Sort by popularity', 'woocommerce' ),
								'rating'     => __( 'Sort by average rating', 'woocommerce' ),
								'date'       => __( 'Sort by latest', 'woocommerce' ),
								'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
								'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
							];
							$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : '';
							wc_get_template(
								'loop/orderby.php',
								[
									'catalog_orderby_options' => $catalog_orderby_options,
									'orderby'                 => $orderby,
								]
							);
							?>
						</div>
						<div class="col-md-7"></div>
						<div class="col-md-2">
							<button id="clear-wishlist"><i class="bi bi-trash"></i> <?php _e( 'Clear all' ) ?></button>
						</div>
					</div>
					<div class="row">
						<?php
						while ( $wishlist->have_posts() ) : $wishlist->the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
						?>
					</div>
				<?php
				endif;
			else :
				_e( 'You have not added the product to your wishlist yet.', 'richtime' );
			endif;
			?>
		</div>
	</div>
<?php
endwhile;
get_footer();