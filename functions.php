<?php

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Default: 1140px is the default Bootstrap container width.
 */
if ( !isset( $content_width ) ){
	$content_width = 1140;
}

/**
 * Sets up theme defaults and registers various features supported
 */
function dev7_theme_setup() {
	load_theme_textdomain( 'dev7', get_template_directory() . '/lang' );

	register_nav_menus( array(
		'primary-navigation' => __( 'Primary Navigation', 'dev7' )
	) );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	/* Uncomment to enable post formats */
	// add_theme_support( 'post-formats', array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'video' ) );

	add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'dev7_theme_setup' );

/**
 * Register sidebars
 */
function dev7_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'dev7' ),
		'id'            => 'primary-sidebar',
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'dev7_widgets_init' );

/**
 * Enqueues scripts and styles for front end
 */
function dev7_enqueue_scripts() {
	// An array of scripts ( $handle, $src, $deps, $in_footer )
	$scripts = array(
		array( 'modernizr', '/assets/vendor/modernizr/modernizr.js' ),
		array( 'bootstrap', '/assets/vendor/bootstrap/dist/js/bootstrap.min.js', array(), true ),
		array( 'dev7', '/assets/js/theme.min.js', array( 'jquery' ), true )
	);
	// An array of styles ( $handle, $src, $deps )
	$styles = array(
		array( 'bootstrap', '/assets/vendor/bootstrap/dist/css/bootstrap.min.css' ),
		array( 'dev7', 		'/assets/css/style.min.css', array( 'bootstrap' ) )
	);

	// Register our scripts and styles and use cache-busting versions
	foreach( $scripts as $script ){
		if( isset( $script[0] ) && isset( $script[1] ) && file_exists( get_template_directory() . $script[1] ) ){
			wp_register_script( $script[0], get_template_directory_uri() . $script[1], ( isset( $script[2] ) ? $script[2] : array() ), filemtime( get_template_directory() . $script[1] ), ( isset( $script[3] ) ? $script[3] : false ) );
		}
	}
	foreach( $styles as $style ){
		if( isset( $style[0] ) && isset( $style[1] ) && file_exists( get_template_directory() . $style[1] ) ){
			wp_register_style( $style[0], get_template_directory_uri() . $style[1], ( isset( $style[2] ) ? $style[2] : array() ), filemtime( get_template_directory() . $style[1] ) );
		}
	}

	// Enqueue our scripts
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'bootstrap' );
	wp_enqueue_script( 'dev7' );

	// Loads the javascript required for threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments') ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue our styles
	wp_enqueue_style( 'dev7' );
}
add_action( 'wp_enqueue_scripts', 'dev7_enqueue_scripts' );

/**
 * Use Bootstrap's media object for listing comments
 *
 * @link http://getbootstrap.com/components/#media
 */
class Dev7_Walker_Comment extends Walker_Comment {
	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ul <?php comment_class( 'media list-unstyled comment-'. get_comment_ID() ); ?>>
		<?php
	}

	function end_lvl( &$output, $depth = 0, $args = array() )
	{
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '</ul>';
	}

	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 )
	{
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			call_user_func( $args['callback'], $comment, $args, $depth );
			return;
		}

		extract($args, EXTR_SKIP); ?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media comment-' . get_comment_ID() ); ?>>
			<?php include( locate_template( 'parts/comment.php' ) ); ?>
		<?php
	}

	function end_el( &$output, $comment, $depth = 0, $args = array() )
	{
		if ( !empty( $args['end-callback'] ) ) {
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			return;
		}
		// Close ".media-body" <div> located in parts/comment.php, and then the comment's <li>
		echo "</div></li>\n";
	}
}

function dev7_get_avatar( $avatar, $type ) {
	if ( !is_object( $type ) ) return $avatar;

	$avatar = str_replace( 'class="avatar', 'class="avatar pull-left media-object', $avatar );
	return $avatar;
}
add_filter( 'get_avatar', 'dev7_get_avatar', 10, 2 );