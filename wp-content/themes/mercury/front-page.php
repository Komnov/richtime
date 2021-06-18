<?php

get_header( null, [
	'theme' => 'dark',
] ); ?>
<?php
$banners          = get_field( 'banners_list' );
$front_categories = get_field( 'product_tax_front' );
$term             = ! empty( $front_categories ) ? $front_categories[0] : null;

if ( ! empty( $banners ) ) : ?>
	<section id="main-carousel">
		<div id="banners" class="main-carousel">
			<?php foreach ( $banners as $key => $banner ) : ?>
				<div class="carousel-item">
					<img src="<?php echo $banner['image']['url'] ?>" alt="">
					<div class="carousel-item__read-more">
						<div class="title">Christophe<br>Claret</div>
						<a href="<?php echo $banner['link'] ?>"
								target="_blank"
						><?php echo __( 'Read more', 'mercury' ); ?></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif ?>
<?php if ( is_object( $term ) ) : ?>
	<div class="products-wrapper">
		<div class="container">
			<div class="row">
				<h2 class="title"><?php echo $term->name ?></h2>
			</div>
			<div class="row">
				<?php
				$products = new WP_Query(
					[
						'posts_per_page' => 8,
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'tax_query'      => [
							[
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => [ $term->term_id ],
							],
						],
					]
				);

				if ( $products->have_posts() ) :
					while ( $products->have_posts() ) : $products->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
				else:
					echo 'No products';
				endif;
				?>
			</div>
		</div>
	</div>
<?php endif ?>
	<section class="news-products">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title">Новые поступления</h2>
				</div>
			</div>
			<div class="row">
				<?php
				$new_products = new WP_Query(
					[
						'posts_per_page' => 8,
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'tax_query'      => [
							[
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => [ $term->term_id ],
							],
						],
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

<?php get_footer();