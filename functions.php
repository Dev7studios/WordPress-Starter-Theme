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

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Dev7_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Dev7_Nav_Walker extends Walker_Nav_Menu {
	function check_current( $classes )
	{
		return preg_match( '/(current[-_])|active|dropdown/', $classes );
	}

	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		$output .= "\n<ul class=\"dropdown-menu\">\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
	{
		$item_html = '';
		parent::start_el( $item_html, $item, $depth, $args );

		if ( $item->is_dropdown && ($depth === 0) ) {
			$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html );
			$item_html = str_replace( '</a>', ' <b class="caret"></b></a>', $item_html );
		}
		elseif ( stristr( $item_html, 'li class="divider' ) ) {
			$item_html = preg_replace( '/<a[^>]*>.*?<\/a>/iU', '', $item_html );
		}
		elseif ( stristr( $item_html, 'li class="dropdown-header' ) ) {
			$item_html = preg_replace( '/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html );
		}
		$output .= $item_html;
	}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output )
	{
		$element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

		if ( $element->is_dropdown ) {
			$element->classes[] = 'dropdown';
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function dev7_nav_menu_css_class( $classes, $item ) {
	$slug = sanitize_title($item->title);
	$classes = preg_replace( '/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes );
	$classes = preg_replace( '/^((menu|page)[-_\w+]+)+/', '', $classes );

	$classes[] = 'menu-' . $slug;

	$classes = array_unique( $classes );

	return array_filter( $classes, 'is_element_empty' );
}
add_filter( 'nav_menu_css_class', 'dev7_nav_menu_css_class', 10, 2 );
add_filter( 'nav_menu_item_id', '__return_null' );

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Dev7_Nav_Walker() by default
 */
function dev7_nav_menu_args($args = '') {
	$dev7_nav_menu_args['container'] = false;

	if ( !$args['items_wrap'] ) {
		$dev7_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	}

	if ( !$args['depth'] ) {
		$dev7_nav_menu_args['depth'] = 2;
	}

	if ( !$args['walker'] ) {
		$dev7_nav_menu_args['walker'] = new Dev7_Nav_Walker();
	}

	return array_merge( $args, $dev7_nav_menu_args );
}
add_filter( 'wp_nav_menu_args', 'dev7_nav_menu_args' );
