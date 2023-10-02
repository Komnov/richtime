<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package richtime
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

<body <?php body_class( current_user_can( 'manage_options' ) ? 'is-admin' : '' ); ?>>
<?php wp_body_open(); ?>
<?php
$langs        = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
$template_url = get_template_directory_uri();
$theme        = isset( $args['theme'] ) ? 'theme-' . $args['theme'] : '';
$phones       = get_field( 'phones', 'option' );
?>
<div id="page" class="site <?php echo ! is_front_page() ? $theme : '' ?>">
    <header id="masthead" class="site-header <?php echo $theme ?>">
        <div class="actions-row">
            <div class="container-fluid">
                <div class="row align-items-center h-100">
                    <div class="col-8 col-lg-2">
                        <div class="header-actions">
                            <button class="hamburger hamburger--slider" type="button">
                              <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                              </span>
                            </button>
                            <div class="site-logo">
								<?php if ( ! is_front_page() ) : ?>
                                <a href="<?php echo get_home_url() ?>">
									<?php endif; ?>
									<?php if ( ! empty( $theme ) ) : ?>
										<?php $alt_logo = get_field( 'alt_logo', 'option' ) ?>
                                        <img src="<?php echo $alt_logo['sizes']['medium'] ?>" alt="">
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
                    </div>
                    <div class="d-none d-lg-block col-lg-8">
        <div class="navigation-row" style="">
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
                    </div>
                    <div class="col-4 col-lg-2">
                        <div class="header-actions">
                            <div class="language-wrapper">
								<?php richtime_language_switcher() ?>
                            </div>
                            <div class="icons-list">
								<?php echo do_shortcode('[fibosearch]'); ?>
                            </div>
                        </div>
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
                                <div class="language-wrapper">
									<?php richtime_language_switcher() ?>
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
					$brands = get_terms(
						[
							'taxonomy'   => 'product_cat',
							'hide_empty' => false,
							'parent'     => 38,
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
                    <div class="phones">
						<?php if ( is_array( $phones ) ) : ?>
                            <ul>
								<?php foreach ( $phones as $phone ) : ?>
									<?php if ( ! empty( $phone['whatsapp'] ) ) : ?>
										<?php $clearphone = preg_replace( '/[^0-9.]/', '', $phone['phone'] ); ?>
                                        <li>
                                            <a href="https://api.whatsapp.com/send?phone=<?php echo intval( $clearphone ) ?>"
                                               target="_blank"><?php echo $phone['phone'] ?></a></li>
									<?php else : ?>
                                        <li>
                                            <a href="tel:<?php echo $phone['phone'] ?>"><?php echo $phone['phone'] ?></a>
                                        </li>
									<?php endif ?>
								<?php endforeach; ?>
                            </ul>
						<?php endif ?>
                    </div>
                    <div class="phones">
                        <ul>
                            <li>Москва, Петровка 18</li>
                        </ul>
                    </div>
                    <div class="phones">
                        <ul>
                            <li>Пн-пт 10:00-22:00</li>
                            <li>Сб 11:00-21:00</li>
                            <li>Вс 11:00-20:00</li>
                        </ul>
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
    $filters = new Richtime_Filter( $object );
	if ( $object->slug === 'chasy' ) {
		$filters->get_filters(
            [ 'Все часы', 'Стиль', 'Бренд', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'brands', 'individual' ],
         );
		 	} else if($object->slug === 'cabestan') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'carl-f-bucherer') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'clerc') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'corum') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'cvstos') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'encelade') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'franc-vila') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'hautlence') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'h-moser-cie') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'hysek') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'hyt') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'kerbedanz') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'louis-moinet') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'manufacture-royale') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'mct') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'montblanc') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
				 	} else if($object->slug === 'qlocktwo') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
						 	} else if($object->slug === 'romain-jerome') {
        $filters->get_filters(
            [ 'Все часы', 'Стиль', 'Индивидуальный подбор' ],
            [ 'pa_dlya-kogo', 'pa_stil', 'individual' ],
        );
	} else if($object->slug === 'aksessuary') {
        $filters->get_filters(
            [ 'Вид', 'Материал', 'Покрытие' ],
            [ 'pa_vid', 'pa_material', 'pa_pokrytie' ]
        );
			} else if($object->slug === 'hysek-2') {
        $filters->get_filters(
            [ 'Вид', 'Материал', 'Покрытие' ],
            [ 'pa_vid', 'pa_material', 'pa_pokrytie' ]
        );
			} else if($object->slug === 'encelade-2') {
        $filters->get_filters(
            [ 'Вид', 'Материал', 'Покрытие' ],
            [ 'pa_vid', 'pa_material', 'pa_pokrytie' ]
        );
			} else if($object->slug === 'louis-moinet-2') {
        $filters->get_filters(
            [ 'Вид', 'Материал', 'Покрытие' ],
            [ 'pa_vid', 'pa_material', 'pa_pokrytie' ]
        );
			} else if($object->slug === 'rj') {
        $filters->get_filters(
            [ 'Вид', 'Материал', 'Покрытие' ],
            [ 'pa_vid', 'pa_material', 'pa_pokrytie' ]
        );
    } else if( $object->slug === 'yuvelirnye-izdeliya' ) {
        $filters->get_filters(
            [ 'Изделия', 'Металл', 'Камни', 'Коллекции' ],
            [ 'pa_izdeliya', 'pa_metall', 'pa_kamni', 'collection_name' ]
        );
		    } else if( $object->slug === 'crivelli' ) {
        $filters->get_filters(
            [ 'Изделия', 'Металл', 'Камни', 'Коллекции' ],
            [ 'pa_izdeliya', 'pa_metall', 'pa_kamni', 'collection_name' ]
        );
		    } else if( $object->slug === 'girona-prive' ) {
        $filters->get_filters(
            [ 'Изделия', 'Металл', 'Камни', 'Коллекции' ],
            [ 'pa_izdeliya', 'pa_metall', 'pa_kamni', 'collection_name' ]
        );
		    } else if( $object->slug === 'korloff' ) {
        $filters->get_filters(
            [ 'Изделия', 'Металл', 'Камни', 'Коллекции' ],
            [ 'pa_izdeliya', 'pa_metall', 'pa_kamni', 'collection_name' ]
        );
    }
} ?>