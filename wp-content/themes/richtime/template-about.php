<?php

/**
 * Template Name: About
 */
get_header( 'null', [
	'theme' => 'dark'
] ); ?>
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
							<?php if ( ! empty( $tiles ) ) : ?>
								<?php foreach ( $tiles as $key => $tile ) : ?>
                                    <div class="col-md-<?php echo 12 / count( $tiles ); ?> tiles-column-<?php echo $key ?>">
                                        <div class="image-wrapper">
                                            <p class="tiles-caption"><?php echo $tile['caption'] ?></p>
                                        </div>
                                        <div>
                                        <?php if ( ! empty( $tile['under_numbers'] ) ) : ?>
                                            <p class="text-center"><?php echo $tile['under_numbers'] ?></p>
                                        <?php endif; ?>
                                        </div>
                                    </div>
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </div>
        <div class="additional-info__columns">
            <div class="container">
                <div class="row">
					<?php if ( ! empty( $columns ) ) : ?>
						<?php foreach ( $columns as $column ) : ?>
                            <div class="col-md-<?php echo 12 / count( $columns ); ?>">
                                <p class="title"><?php echo $column['title'] ?></p>
                                <p class="column-description"><?php echo $column['description'] ?></p>
                            </div>
						<?php endforeach; ?>
					<?php endif ?>
                </div>
            </div>
        </div>
    </article>
<?php endwhile; ?>
<?php get_footer();
