<?php

/**
 * Template Name: About
 */
get_header('null', [
		'theme' => 'dark'
]); ?>
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

		<div class="additional-info">
			<?php
			$tiles   = get_field( 'tiles' );
			$columns = get_field( 'columns' );
			if ( ! empty( $tiles ) ) :
				?>
				<div class="additional-info__images">
					<div class="container">
						<div class="row">
							<?php foreach ( $tiles as $tile ) : ?>
								<div class="col-md-<?php echo 12 / count( $tiles ); ?>">
									<div class="image-wrapper">
										<figure>
											<img src="<?php echo $tile['image']['url'] ?>" alt="">
											<figcaption
													class="tiles-caption"><?php echo $tile['caption'] ?></figcaption>
										</figure>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="additional-info__columns">
			<div class="container">
				<div class="row">
					<?php foreach ( $columns as $column ) : ?>
						<div class="col-md-<?php echo 12 / count( $columns ); ?>">
							<p class="title"><?php echo $column['title'] ?></p>
							<p class="column-description"><?php echo $column['description'] ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer();
