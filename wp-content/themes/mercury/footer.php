<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mercury
 */

?>

<footer id="colophon" class="site-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="blog-title">
					<?php echo get_bloginfo( 'name' ) ?>
				</div>
				<?php $phones = get_field( 'phones', 'option' ) ?>
				<div class="phones">
					<?php if( is_array( $phones ) ) : ?>
					<ul>
						<?php foreach ( $phones as $phone ) : ?>
							<li><a href="tel:<?php echo $phone['phone'] ?>"><?php echo $phone['phone'] ?></a></li>
						<?php endforeach; ?>
					</ul>
					<?php endif ?>
				</div>
			</div>
			<div class="col-md-6">
				<nav id="footer-navigation" class="footer-navigation">
					<?php
					wp_nav_menu(
						[
							'theme_location' => 'menu-2',
							'menu_id'        => 'footer-menu',
							'depth' => 1,
						]
					);
					?>
				</nav>
				<p class="copyright">© «RICH TIME GROUP» 2014-<?php echo date('Y') ?></p>
			</div>
			<div class="col-md-3">
				<div class="social-links-text"><?php _e('We are in social', 'mercury') ?></div>
				<?php $social_links = get_field('social_links', 'option') ?>
				<div class="social-links">
					<?php if( is_array( $social_links ) ) : ?>
					<ul>
						<?php foreach ( $social_links as $link ) : ?>
							<a href="<?php echo $link['link'] ?>" target="_blank"><?php echo $link['image'] ?></a>
						<?php endforeach; ?>
					</ul>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
