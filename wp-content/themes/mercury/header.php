<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mercury
 */

?>
	<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">

		<?php wp_head(); ?>
	</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$langs        = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
$template_url = get_template_directory_uri();
$theme        = isset( $args['theme'] ) ? 'theme-' . $args['theme'] : '';
?>
<div id="page" class="site <?php echo ! is_front_page() ? $theme : '' ?>">
	<header id="masthead" class="site-header <?php echo $theme ?>">
		<div class="actions-row">
			<div class="container-fluid">
				<div class="row align-items-center h-100">
					<div class="col-2 col-md-3">
						<button class="hamburger hamburger--slider" type="button">
					  <span class="hamburger-box">
					    <span class="hamburger-inner"></span>
					  </span>
						</button>
					</div>
					<div class="col-7 col-md-6">
						<div class="site-logo"><?php if ( ! is_front_page() ) : ?>
							<a href="<?php echo get_home_url() ?>">
								<?php endif; ?>
								<?php if ( ! empty( $theme ) ) : ?>
									<?php $alt_logo = get_field( 'alt_logo', 'option' ) ?>
									<img src="<?php echo $alt_logo['url'] ?>" alt="">
								<?php else : ?>
									<img
											src="<?php echo wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'medium' ); ?>"
											alt="">
								<?php endif ?>
								<?php if ( ! is_front_page() ) : ?>
							</a>
						<?php endif ?>
						</div>
					</div>
					<div class="col-3 col-md-3">
						<div class="header-actions">
							<div class="language-wrapper">
								<?php mercury_language_switcher() ?>
							</div>
							<div class="icons-list">
								<div class="icon search-icon">
									<a id="search-modal" href="#">
										<i class="bi bi-search"></i>
									</a>
								</div>
								<div class="icon start-icon">
									<a id="star-modal" href="<?php echo get_permalink( 301 ) ?>">
										<i class="bi bi-star"></i>
										<?php the_favorite_count(); ?>
									</a>
								</div>
								<div class="icon account-icon">
									<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
										<i class="bi bi-person"></i>
									</a>
								</div>
								<div class="icon basket-icon">
									<a href="<?php echo wc_get_cart_url() ?>">
										<i class="bi bi-cart"></i>
										<?php the_cart_count(); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="navigation-row">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<nav id="site-navigation" class="main-navigation">
							<?php
							wp_nav_menu(
								[
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
									'walker'         => new Main_Menu_Walker(),
								]
							);
							?>
						</nav><!-- #site-navigation -->
					</div>
				</div>
			</div>
		</div>
		<div class="side-menu">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="side-menu__buttons">
							<div class="icons-list">
								<div class="icon start-icon">
									<a id="star-modal" href="<?php echo get_permalink( 301 ) ?>">
										<i class="bi bi-star"></i>
										<?php the_favorite_count(); ?>
									</a>
								</div>
								<div class="icon account-icon">
									<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
										<i class="bi bi-person"></i>
									</a>
								</div>
								<div class="language-wrapper">
									<?php mercury_language_switcher() ?>
								</div>
							</div>
							<div class="side-menu__close-wrapper">
								<button type="button"><i class="bi bi-x-lg"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<?php
					wp_nav_menu(
						[
							'theme_location' => 'menu-3',
							'menu_id'        => 'side-menu',
							'menu_class'     => 'side-navigation',
						]
					);
					?>
				</div>
				<div class="row">
					<?php $social_links = get_field( 'social_links', 'option' ) ?>
					<div class="social-links">
						<?php if ( is_array( $social_links ) ) : ?>
							<ul>
								<li>
									<?php foreach ( $social_links as $link ) : ?>
										<a href="<?php echo $link['link'] ?>"
												target="_blank"><?php echo $link['image'] ?></a>
									<?php endforeach; ?>
								</li>
							</ul>
						<?php endif ?>

					</div>
				</div>
				<div class="row">
					<?php $phones = get_field( 'phones', 'option' ) ?>
					<div class="phones">
						<?php if ( is_array( $phones ) ) : ?>
							<ul>
								<?php foreach ( $phones as $phone ) : ?>
									<li>
										<a href="tel:<?php echo $phone['phone'] ?>"><?php echo $phone['phone'] ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif ?>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p class="copyright">© «RICH TIME GROUP» 2014-<?php echo date( 'Y' ) ?></p>
					</div>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
	<div class="search-wrapper">
		<div class="search-input-wrapper <?php echo $theme ?>">
			<form id="ajax-search-form" action="">
				<button type="button" class="search-close"><i class="bi bi-x-lg"></i></button>
				<label for="search"><input id="search" placeholder="Поиск" type="text" name="search" value=""></label>
				<button class="search-submit" type="submit"><i class="bi bi-search"></i></button>
			</form>
		</div>
		<div id="search-results" class="search-result-wrapper"></div>
	</div>
<?php if ( ! is_front_page() ) : ?>
	<div class="breadcrumbs-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
<?php
if ( is_product_taxonomy() ) {
	$object = get_queried_object();
	if ( $object->slug === 'chasy' ) {
		$filters = new Mercury_Filter( $object );
		$filters->get_filters( [ 'Все часы', 'Стиль', 'Бренд', 'Индивидуальный подбор' ], [
			'pa_dlya-kogo',
			'pa_stil',
			'brands',
			'individual',
		] );
	}
} ?>