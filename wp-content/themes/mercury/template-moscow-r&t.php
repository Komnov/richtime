<?php

/**
 * Template Name: Moscow by R&T
 */
get_header('null', [
		'theme' => 'dark'
]);
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
	<div class="additional-info">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php
					$gallery = get_field( 'gallery' );
					$columns = get_field( 'additional_info' );
					if ( ! empty( $gallery ) ) :
						?>
						<div class="additional-info__images">
							<?php foreach ( $gallery as $key => $item ) : ?>
								<div id="<?php echo 'img-' . $key ?>" class="image-wrapper">
									<figure>
										<img src="<?php echo $item['url'] ?>" alt="">
									</figure>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
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
<?php endwhile; ?>
<?php
get_footer();