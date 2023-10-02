<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package richtime
 */

if ( ! function_exists( 'richtime_post_tags' ) ) {
	function richtime_post_tags( $total = - 1 ) {
		$tags       = get_the_tags();
		$tags_count = count( get_the_tags() );

		$count = $tags_count;

		if ( $total !== - 1 && $total <= $tags_count ) {
			$count = $total;
		}

		if ( ! empty( $tags ) ) : ?>
			<div class="post-tags">
				<?php for ( $i = 0; $i < $count; $i ++ ) : ?>
					<a class="tag"
							href="<?php echo get_tag_link( $tags[ $i ]->term_id ) ?>"><?php echo $tags[ $i ]->name ?></a>
				<?php endfor; ?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'richtime_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function richtime_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date( 'd.m.Y' ) ),
		);

		$posted_on = '<div class="post-meta-item post-posted-date"><span class="dashicons dashicons-clock"></span><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></div>';

		echo $posted_on; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'richtime_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function richtime_posted_by() {
		$byline = sprintf(
		/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'richtime' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'richtime_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function richtime_entry_footer() {
		richtime_posted_on();
		richtime_post_views();
		richtime_post_comments_count();
		richtime_post_link();
	}
endif;

if ( ! function_exists( 'richtime_post_views' ) ) {
	function richtime_post_views() {
		if ( pvc_get_post_views() > 0 ) {
			echo '<div class="post-meta-item post-view-count">' . do_shortcode( '[post-views]' ) . '</div>';
		}
	}
}
if ( ! function_exists( 'richtime_post_comments_count' ) ) {
	function richtime_post_comments_count() {
		$comment_count = get_comments_number( get_post() );
//		if ( ! empty( $comment_count ) ) {
		echo '<div class="post-meta-item post-comment-count"><span class="dashicons dashicons-admin-comments"></span><span class="comment-count-text">' . $comment_count . '</span></div>';
//		}
	}
}

if ( ! function_exists( 'richtime_post_link' ) ) {
	function richtime_post_link() {
		echo sprintf( '<div class="post-meta-item post-arrow-link"><a href="%s"><span class="dashicons dashicons-arrow-right-alt"></span></a></div>', get_permalink() );
	}
}

if ( ! function_exists( 'richtime_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function richtime_post_thumbnail( $size = 'post-thumbnail' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		} ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				$size,
				[
					'alt' => the_title_attribute(
						[
							'echo' => false,
						]
					),
				]
			);
			?>
		</a>
		<?php
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

if ( ! function_exists( 'the_favorite_count' ) ) {
	function the_favorite_count() {
		if ( is_user_logged_in() ) {
			$fav_pr = get_user_meta( get_current_user_id(), 'favorites_products', true );
			if ( is_array( $fav_pr ) && count( $fav_pr ) > 0 ) {
				echo sprintf( '<sup>%d</sup>', count( $fav_pr ) );
			}
		}
	}
}

if ( ! function_exists( 'the_cart_count' ) ) {
	function the_cart_count() {
		$count = WC()->cart->get_cart_contents_count();
		if ( $count > 0 ) {
			echo sprintf( '<sup>%d</sup>', $count );
		}
	}
}