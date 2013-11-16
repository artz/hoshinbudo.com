<?php
/**
 * Hoshin functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, hoshin_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'hoshin_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Hoshin 1.0
 */

/*

# This didn't work.
# http://codex.wordpress.org/Function_Reference/the_excerpt

function new_excerpt_more($more) {
       global $post;
	return '<a href="'. get_permalink($post->ID) . '">Read the Rest...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');
*/

function limit_text($text, $limit) {

	if (strlen($text) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = trim( substr($text, 0, $pos[$limit]) ) . '&hellip;';
	}

	return $text;
}

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

//*
add_action( 'init', 'create_promo_post_type' );

if ( ! function_exists( 'create_promo_post_type' ) ):
	function create_promo_post_type() {
		// http://codex.wordpress.org/Function_Reference/register_post_type
		register_post_type( 'artz-promo',

			array(
				'labels' => array(
					'name' => __( 'Promos' ),
					'singular_name' => __( 'Promo' ),
					'add_new_item' => __( 'Add New Promo' ),
					'edit_item' => __( 'Edit Promo' ),
					'new_item' => __( 'New Promo' ),
					'view_item' => __( 'View Promo' ),
					'search_items' => __( 'Search Promos' ),
					'not_found' => __( 'No promos found' ),
					'not_found_in_trash' => __( 'No promos found in Trash' ),
					'parent_item_colon' => __( 'Parent Promo' )
				),
				'publicly_queryable' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 30,
//				'menu_icon' => "url",
				'hierarchical' => true,
				'map_meta_cap' => true,
				'rewrite' => array(
					array( 'slug' => 'promo' )
				),
				'supports' => array(
					'title',
					'excerpt',
					'thumbnail',
					'revisions',
					'page-attributes',
					'custom-fields'
				),
				'show_in_nav_menus' => false
			)
		);

		register_post_type( 'artz-testimonial',

			array(
				'labels' => array(
					'name' => __( 'Testimonials' ),
					'singular_name' => __( 'Testimonial' ),
					'add_new_item' => __( 'Add New Testimonial' ),
					'edit_item' => __( 'Edit Testimonial' ),
					'new_item' => __( 'New Testimonial' ),
					'view_item' => __( 'View Testimonial' ),
					'search_items' => __( 'Search Testimonials' ),
					'not_found' => __( 'No testimonials found' ),
					'not_found_in_trash' => __( 'No testimonials found in Trash' ),
					'parent_item_colon' => __( 'Parent Testimonial' )
				),
				'publicly_queryable' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 30,
//				'menu_icon' => "url",
				'hierarchical' => true,
				'map_meta_cap' => true,
				'rewrite' => array(
					array( 'slug' => 'promo' )
				),
				'supports' => array(
					'title',
					'excerpt',
					'author'
				),
				'show_in_nav_menus' => false
			)
		);

	}

endif;

if ( ! function_exists( 'get_artz_promos' ) ):
	function get_artz_promos( $promo_id ) {

		$args = array(
			'post_type' => 'artz-promo',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => $promo_id ? $promo_id : ""
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ):

			$meta = get_post_custom( $post->ID );
			$post->URL = $meta['URL'][0];
			if ( isset ( $meta['_thumbnail_id'] ) ) {
				$thumbnail_id = $meta['_thumbnail_id'][0];
			}

//			$post->image_id = $thumbnail_id;
//			$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
//			$post->image_src = $image_src[0];
// 			$image_thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
//			$post->image_thumbnail_src = $image_thumbnail_src[0];
			$post->image_promo_src = wp_get_attachment_image( $thumbnail_id, 'homepage-promo' );
//print_r( wp_get_attachment_metadata( $thumbnail_id ) );
			// Aliases
			$post->title = $post->post_title;
			$post->subtitle = $post->post_excerpt;

		endforeach;

		return $posts;
	}
endif;
//*/

/** Tell WordPress to run hoshin_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'hoshin_setup' );

if ( ! function_exists( 'hoshin_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override hoshin_setup() in a child theme, add your own hoshin_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Hoshin 1.0
 */
function hoshin_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	// add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'homepage-promo', 218, 248, true );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'hoshin', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'hoshin' ),
	) );
}

endif;

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Hoshin 1.0
 * @return int
 */

/*
function hoshin_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'hoshin_excerpt_length' );
*/

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Hoshin 1.0
 * @return string "Continue Reading" link
 */
function hoshin_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hoshin' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and hoshin_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Hoshin 1.0
 * @return string An ellipsis
 */
function hoshin_auto_excerpt_more( $more ) {
	return ' &hellip;' . hoshin_continue_reading_link();
}
add_filter( 'excerpt_more', 'hoshin_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Hoshin 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function hoshin_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= hoshin_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'hoshin_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Hoshin's style.css.
 *
 * @since Hoshin 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function hoshin_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'hoshin_remove_gallery_css' );

if ( ! function_exists( 'hoshin_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own hoshin_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Hoshin 1.0
 */
function hoshin_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s<span class="says">, <a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . sprintf( __( '%1$s at %2$s', 'hoshin' ), get_comment_date(),  get_comment_time() ) . '</a>' . edit_comment_link( __( '(Edit)', 'hoshin' ), ' ' ) . '</span>', 'hoshin' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'hoshin' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'hoshin' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'hoshin'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override hoshin_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Hoshin 1.0
 * @uses register_sidebar
 */
function hoshin_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Articles Sidebar', 'hoshin' ),
		'id' => 'articles-sidebar',
		'description' => __( 'The primary widget area', 'hoshin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
    ) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Video Bar', 'hoshin' ),
		'id' => 'homepage-video-bar',
		'description' => __( 'Homepage section for Hoshin Youtube videos.', 'hoshin' ),
		'before_widget' => '<div class="hoshin-video-bar">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
/*
	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'hoshin' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'hoshin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'hoshin' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'hoshin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'hoshin' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'hoshin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'hoshin' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'hoshin' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
*/
}
/** Register sidebars by running hoshin_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'hoshin_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Hoshin 1.0
 */
function hoshin_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'hoshin_remove_recent_comments_style' );

if ( ! function_exists( 'hoshin_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Hoshin 1.0
 */
function hoshin_posted_on() {
	printf( __( '%1$s %2$s', 'hoshin' ),
		sprintf( '<span class="entry-date">%1$s</span></a>',
			date( "h.d.y", strtotime( get_the_date() ) )
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'hoshin' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'hoshin_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Hoshin 1.0
 */
function hoshin_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s.', 'hoshin' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s.', 'hoshin' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;
