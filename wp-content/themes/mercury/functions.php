<?php
/**
 * mercury functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mercury
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.7' );
}

if ( ! function_exists( 'mercury_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mercury_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on mercury, use a find and replace
		 * to change 'mercury' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mercury', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			[
				'menu-1' => esc_html__( 'Primary', 'mercury' ),
				'menu-2' => esc_html__( 'Footer', 'mercury' ),
				'menu-3' => esc_html__( 'Side', 'mercury' ),
			]
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			]
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'mercury_custom_background_args',
				[
					'default-color' => 'ffffff',
					'default-image' => '',
				]
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);

		add_theme_support( 'woocommerce' );

		add_image_size( 'post-thumbnail', 350, 213 );
//		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

	}
endif;
add_action( 'after_setup_theme', 'mercury_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mercury_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mercury_content_width', 640 );
}

add_action( 'after_setup_theme', 'mercury_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mercury_widgets_init() {
	register_sidebar(
		[
			'name'          => esc_html__( 'Сайдар для часов', 'mercury' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mercury' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		]
	);

	register_sidebar(
		[
			'name'          => esc_html__( 'Сайдаб для украшений', 'mercury' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add widgets here.', 'mercury' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		]
	);
}

add_action( 'widgets_init', 'mercury_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mercury_scripts() {
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond&family=Jost:wght@300;400;500&display=swap', [], null );
	wp_enqueue_style( 'mercury-style', get_stylesheet_uri(), [], _S_VERSION );
	wp_style_add_data( 'mercury-style', 'rtl', 'replace' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'mercury-main', get_template_directory_uri() . '/build/index.js', [], _S_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'mercury_scripts' );

function mercury_language_switcher() {
	$langs        = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str&active=0' );
	$current_lang = [];
	foreach ( $langs as $lang ) {
		if ( $lang['active'] === 0 ) {
			$current_lang = $lang;
			break;
		}
	}
	$svg = '<svg width="28" height="16" viewBox="0 0 28 16" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path opacity="0.7" d="M7.53846 12C8.28394 12 9.01269 11.7654 9.63253 11.3259C10.2524 10.8864 10.7355 10.2616 11.0208 9.53073C11.3061 8.79983 11.3807 7.99556 11.2353 7.21964C11.0898 6.44372 10.7308 5.73098 10.2037 5.17157C9.67657 4.61216 9.00496 4.2312 8.2738 4.07686C7.54264 3.92252 6.78478 4.00173 6.09604 4.30448C5.4073 4.60723 4.81863 5.11992 4.40446 5.77772C3.99029 6.43552 3.76923 7.20887 3.76923 8C3.76923 9.06087 4.16634 10.0783 4.87321 10.8284C5.58008 11.5786 6.5388 12 7.53846 12ZM7.53846 6.28572C7.85795 6.28572 8.17027 6.38626 8.43592 6.57462C8.70157 6.76299 8.90862 7.03073 9.03088 7.34397C9.15315 7.65722 9.18514 8.0019 9.12281 8.33444C9.06048 8.66698 8.90663 8.97244 8.68071 9.21218C8.4548 9.45193 8.16696 9.6152 7.85361 9.68135C7.54026 9.74749 7.21545 9.71354 6.92028 9.58379C6.62511 9.45404 6.37282 9.23432 6.19532 8.95241C6.01782 8.67049 5.92308 8.33905 5.92308 8C5.92308 7.54534 6.09327 7.10931 6.39621 6.78782C6.69916 6.46633 7.11003 6.28572 7.53846 6.28572ZM7.53846 16L20.4615 16C22.4609 16 24.3783 15.1571 25.792 13.6569C27.2058 12.1566 28 10.1217 28 8C28 5.87827 27.2058 3.84344 25.792 2.34315C24.3783 0.842858 22.4609 2.37678e-06 20.4615 2.20199e-06L7.53846 2.02589e-06C5.53914 2.80478e-06 3.6217 0.842857 2.20797 2.34315C0.794229 3.84344 -1.02248e-06 5.87827 -1.20797e-06 8C-1.39345e-06 10.1217 0.794228 12.1566 2.20796 13.6569C3.6217 15.1571 5.53914 16 7.53846 16ZM7.53846 2.28572L20.4615 2.28572C21.8896 2.28572 23.2592 2.88775 24.269 3.95939C25.2788 5.03103 25.8462 6.48448 25.8462 8C25.8462 9.51552 25.2788 10.969 24.269 12.0406C23.2592 13.1122 21.8896 13.7143 20.4615 13.7143L7.53846 13.7143C6.11037 13.7143 4.74077 13.1122 3.73096 12.0406C2.72115 10.969 2.15385 9.51552 2.15385 8C2.15385 6.48448 2.72115 5.03103 3.73096 3.95939C4.74078 2.88776 6.11037 2.28572 7.53846 2.28572Z" fill="white"/>
	</svg>';
	if ( ! empty( $current_lang ) ) {
		echo sprintf( '<a class="mercury-language" href="%s">%s %s</a>', $current_lang['url'], $svg, $current_lang['tag'] );
	}

}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Walkers
 */
require get_template_directory() . '/inc/walkers/class-main-menu-walker.php';

/**
 * Mercury widgets
 */
require get_template_directory() . '/inc/widgets/class-mercury-attribute-filter.php';

/**
 * Load Woocommerce hooks
 */
require get_template_directory() . '/inc/woocommerce/hooks.php';
require get_template_directory() . '/inc/woocommerce/functions.php';

/**
 * Custom Post Types
 */
require get_template_directory() . '/inc/post-types/post-types.php';

/**
 * Taxonomies
 */
require get_template_directory() . '/inc/taxonomies/taxonomies.php';

/**
 * ACF settings
 */
require get_template_directory() . '/inc/acf/acf-settings.php';

/**
 * Filters
 */
require get_template_directory() . '/inc/filters/class-mercury-filter.php';