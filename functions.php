<?php
/**
 * EAMann functions and definitions
 *
 * @package EAMann
 * @since EAMann 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since EAMann 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'eamann_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since EAMann 1.0
 */
function eamann_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on EAMann, use a find and replace
	 * to change 'eamann' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'eamann', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'eamann' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'status' ) );
}
endif; // eamann_setup
add_action( 'after_setup_theme', 'eamann_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since EAMann 1.0
 */
function eamann_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'eamann' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Front Sidebar', 'eamann' ),
		'id' => 'sidebar-front',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Single Post Sidebar', 'eamann' ),
		'id' => 'sidebar-single',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'eamann_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function eamann_scripts() {
	global $post;

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_style( 'dancing-font', 'http://fonts.googleapis.com/css?family=Dancing+Script:700' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'eamann_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Fetch the featured image URL for front-page posts.
 */
function eamann_featured_image() {
	global $post;

	if ( has_post_thumbnail( $post->ID ) ) {
		echo get_the_post_thumbnail( $post->ID, 'eamann_featured' );
	} else {
		$id = (int) $post->ID;

		echo get_stylesheet_directory_uri() . '/images/default-banner-' . ( $id % 8 ) . '.png';
	}
}

add_image_size( 'eamann_featured', 340, 160 );

function custom_excerpt_length( $length ) {
	global $post;
	if ( is_tag() || is_category() || is_date() ) {
		$length = 20;
	} else {
		switch ( get_post_format( $post->ID ) ) {
			case "aside":
				$length = 50;
				break;
			default:
				$length = 20;
		}
	}

	return $length;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	global $post;

	return ' <a class="more" href="' . get_permalink( $post->ID ) . '">&hellip;</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function remove_status_aside_from_feed( $wp_query ) {
	if ( ! $wp_query->is_feed() )
		return;

	$formats_to_exclude = array(
		'post-format-status',
		'post-format-aside'
	);

	$tax_query = $wp_query->get( 'tax_query' );

	$tax_query[] = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => $formats_to_exclude,
		'operator' => 'NOT IN'
	);

	$wp_query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'remove_status_aside_from_feed' );

register_widget( 'Journal_Entry' );
class Journal_Entry extends  WP_Widget {
	public function __construct() {
		parent::__construct(
			'journal_entry', // Base ID
			'Journal_Entry', // Name
			array( 'description' => __( 'Latest Journal Entry', 'eamann' ), ) // Args
		);
	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

	public function widget( $args, $instance ) {
		extract( $args );

		$query_args = array(
			'posts_per_page' => 10,
			'tax_query'      => array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array( 'post-format-aside' )
				)
			)
		);

		$journals = new WP_Query( $query_args );
		$first = true;

		echo $before_widget;
		echo '<h3>From the Journal</h3>';

		while ( $journals->have_posts() ) : $journals->the_post();

		if ( $first ) {
			$first = false;
			echo '<h4><a href="' . get_permalink() . '">' . get_the_date() . '</a></h4>';
			echo '<div class="journal-entry">';
			the_excerpt();
			echo '</div>';
			echo '<ul>';
		} else {
			echo '<li><a href="' . get_permalink() . '">' . get_the_date() . '</a></li>';
		}

		endwhile;

		echo '</ul>';

		echo $after_widget;
	}
}