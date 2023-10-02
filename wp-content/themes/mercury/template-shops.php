<?php

/**
 * Template Name: R&T Shops
 */
get_header( 'null', [
	'theme' => 'dark',
] );
?>
<?php while ( have_posts() ) : the_post(); ?>
	<article>
		<div class="container-wrapper">
			<div class="container page-container">
				<div class="row">
					<div class="col-md-12">
						<h1><?php the_title() ?></h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="page-description">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
	<div class="addresses">
		<div class="map-wrapper">
			<iframe src="https://snazzymaps.com/embed/325731" width="100%" height="600px" style="border:none;"></iframe>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="addresses_data">
						<p><span class="dashicons dashicons-location"></span><strong><?php _e( 'Address' ) ?></strong>
						</p>
						<p><?php the_field( 'address', get_the_id() ) ?></p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="addresses_data">
						<p><span class="dashicons dashicons-phone"></span><strong><?php _e( 'Contacts' ) ?></strong></p>
						<p><?php the_field( 'contacts',  get_the_id() ) ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $shops = get_field( 'images' ) ?>
	<?php if ( ! empty( $shops ) ) : ?>
		<div class="shops-images">
			<div class="container-fluid">
				<div class="row">
					<?php foreach ( $shops as $shop ) : ?>
						<div class="col-md-6"><img src="<?php echo $shop['sizes']['medium_large'] ?>" alt=""></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif ?>
<?php endwhile; ?>
<?php
get_footer();