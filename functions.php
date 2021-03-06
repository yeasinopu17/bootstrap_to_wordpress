<?php

/**
 * Bootstrap to WordPress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootstrap_to_WordPress
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

if (!function_exists('bootstrap2wordpress_setup')) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bootstrap2wordpress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Bootstrap to WordPress, use a find and replace
		 * to change 'bootstrap2wordpress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('bootstrap2wordpress', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__('Primary', 'bootstrap2wordpress'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'bootstrap2wordpress_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
}
add_action('after_setup_theme', 'bootstrap2wordpress_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bootstrap2wordpress_content_width() {
	$GLOBALS['content_width'] = apply_filters('bootstrap2wordpress_content_width', 640);
}
add_action('after_setup_theme', 'bootstrap2wordpress_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bootstrap2wordpress_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'bootstrap2wordpress'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'bootstrap2wordpress'),
			'before_widget' => '<section id="%1$s" class="widget %2$s bg-white mb-3 rounded-3 p-3">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title text-center text-decoration-underline">',
			'after_title'   => '</h4>',
		)
	);
}
add_action('widgets_init', 'bootstrap2wordpress_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function bootstrap2wordpress_scripts() {
	wp_enqueue_style('bootstrap2wordpress-style', get_stylesheet_uri(), array(), time());
	wp_style_add_data('bootstrap2wordpress-style', 'rtl', 'replace');

	wp_enqueue_script('bootstrap2wordpress-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'bootstrap2wordpress_scripts');

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
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}


// add menu li class
function add_additional_class_on_li($classes, $item, $args) {
	if (isset($args->add_li_class)) {
		$classes[] = $args->add_li_class;
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 10, 4);



//add <a> class in li of nav menu
function add_additional_class_on_a($classes, $item, $args) {
	if (isset($args->add_a_class)) {
		$classes['class'] = $args->add_a_class;
	}
	if (isset($args->a_tag_aria_current)) {
		$classes['aria-current'] = $args->a_tag_aria_current;
	}
	return $classes;
}

add_filter('nav_menu_link_attributes', 'add_additional_class_on_a', 10, 4);


// edit excerpt [..] symbol
function excerpt_more_symbol($more) {
	if (!is_single()) {
		$more = sprintf(
			'<a class="read-more" href="%1$s">%2$s</a>',
			get_permalink(get_the_ID()),
			__('...Continue Reading. <i class="fas fa-forward"></i>', 'bootstrap2wordpress')
		);
	}

	return $more;
}
add_filter('excerpt_more', 'excerpt_more_symbol');

