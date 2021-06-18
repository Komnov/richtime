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
	define( '_S_VERSION', '1.0.1' );
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
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond&family=Roboto:wght@300;400;500&display=swap', [], null );
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
	if ( ! empty( $current_lang ) ) {
		echo sprintf( '<a class="mercury-language" href="%s">%s</a>', $current_lang['url'], $current_lang['tag'] );
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