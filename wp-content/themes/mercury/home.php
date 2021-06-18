<?php

get_header(); ?>
<?php $tags = get_tags(); ?>
	<section class="news">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1 class="title"><?php _e( 'News', 'mercury' ) ?></h1>
				</div>
			</div>
			<?php
			$news = new WP_Query(
				[
					'posts_per_page' => 1,
					'post_status'    => 'publish',
					'post_type'      => 'post',
				]
			);
			if ( $news->have_posts() ) : ?>
				<div class="row">
					<div class="col-md-12">
						<?php
						$i = 0;
						while ( $news->have_posts() ) : $news->the_post();
							?>
							<?php if ( 0 === $i ) : ?>
								<?php get_template_part( 'template-parts/post/preview', 'big' ) ?>
							<?php else : ?>
								<?php get_template_part( 'template-parts/post/preview', 'sub' ) ?>

							<?php endif ?>
							<?php
							$i ++;
						endwhile; ?>
					</div>
				</div>
			<?php endif;
			wp_reset_postdata();
			?>
			<?php if ( ! empty( $tags ) ) : ?>
				<div class="row">
					<div class="col-md-12">
						<div class="news-tags">
							<?php for ( $i = 0; $i < count( $tags ); $i ++ ) : ?>
								<a class="tag"
										href="<?php echo get_tag_link( $tags[ $i ]->term_id ) ?>"><?php echo $tags[ $i ]->name ?></a>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			<?php endif ?>
			<?php if ( have_posts() ) : ?>
				<div class="row">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="col-md-4">
							<?php get_template_part( 'template-parts/content' ) ?>
						</div>
						<?php $i ++ ?>
					<?php endwhile; ?>
				</div>
			<?php endif ?>
		</div>
	</section>

<?php get_footer();