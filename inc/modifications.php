<?php
/**
 * Modifications to core WordPress functionality.
 *
 * @package dm-customisations
 */

/**
 * Remove unwanted HTTP headers
 *
 * @param  array $headers Unfiltered HTTP headers.
 * @return array          Filtered HTTP headers with pingback removed.
 */
function dm_remove_http_headers( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'dm_remove_http_headers' );

/**
 * Disable XMLRPC functionality.
 */
add_filter( 'wp_xmlrpc_server_class', '__return_false' );
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Tidy up WP Head.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );


/**
 * Remove emojis from TinyMCE
 *
 * @param  array $plugins Array of TinyMCE plugins.
 *
 * @return array          Filtered array with wpemoji removed.
 */
function dm_remove_emojis_from_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, [ 'wpemoji' ] );
	} else {
		return [];
	}
}

/**
 * Disable emojis.
 *
 * @return void
 */
function dm_remove_emojis() {

	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	add_filter( 'emoji_svg_url', '__return_false' ); // Removes dns-prefetch entry for s.w.org.

	// Also remove from TinyMCE.
	add_filter( 'tiny_mce_plugins', 'dm_remove_emojis_from_tinymce' );
}

if ( defined( 'DM_DISABLE_EMOJIS' ) && DM_DISABLE_EMOJIS ) {
	add_action( 'init', 'dm_remove_emojis' );
}

/**
 * Filter to prevent the REST API being available to unauthenticated users.
 * Individual namespaces can be white-listed by filtering dm_allowed_anonymous_restnamespaces.
 *
 * @param  null|bool|WP_Error $access Authentication result for REST API.
 *
 * @return null|bool|WP_Error         WP_Error if authentication error, null if authentication method wasn't used, true if authentication succeeded.
 */
function dm_deny_unauthenticated_rest_api_access( $access ) {
	if ( ! is_user_logged_in() ) {

		$allowed_namespaces = apply_filters( 'dm_allowed_anonymous_restnamespaces', [] );

		// If we've got any allowed namespaces then permit access to them.
		if ( ! empty( $allowed_namespaces ) ) {

			if ( isset( $_REQUEST['rest_route'] ) ) {
				$rest_path = ltrim( $_REQUEST['rest_route'], '/' );
			} elseif ( get_option( 'permalink_structure' ) ) {
				$path = '/' . trim( urldecode( $_SERVER['REQUEST_URI'] ), '/' ) . '/';
				$pos = strlen( get_rest_url() );
				$rest_path = trim( substr( get_home_url() . $path, $pos ), '/' );
			}
			$requested_namespace = substr( trailingslashit( $rest_path ), 0, strpos( trailingslashit( $rest_path ), '/' ) );
			foreach ( $allowed_namespaces as $namespace ) {
				if ( $namespace === $requested_namespace ) {
					return $access;
				}
			}
		}

		// Otherwise, nope.
		return new WP_Error(
			'rest_cannot_access',
			__( 'Authentication required.', 'dm' ),
			[
				'status' => rest_authorization_required_code(),
			]
		);
	}
	return $access;
}
if ( defined( 'DM_DISABLE_REST_ANON' ) && DM_DISABLE_REST_ANON ) {
	add_filter( 'rest_authentication_errors', 'dm_deny_unauthenticated_rest_api_access', 10 );
}

/**
 * Return a message saying the feed is not available.
 *
 * @return void.
 */
function dmnet_disable_feed() {
	wp_die( esc_html( 'Sorry, that is not available.' ) );
	exit;
}

if ( defined( 'DM_DISABLE_RSS' ) && DM_DISABLE_RSS ) {
	add_action( 'do_feed', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_rdf', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_rss', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_rss2', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_atom', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_rss2_comments', 'dmnet_disable_feed', 1 );
	add_action( 'do_feed_atom_comments', 'dmnet_disable_feed', 1 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
}

/**
 * Disable WordPress's built-in search functionality so any requests with ?s=<search term> return a 404.
 *
 * @param  object  $query WordPress query.
 * @param  boolean $error Is this request an error.
 *
 * @return void.
 */
function dm_disable_search( $query, $error = true ) {
	if ( is_admin() ) {
		return $query;
	}
	if ( is_search() ) {
		$query->is_search       = false;
		$query->query_vars['s'] = false;
		$query->query['s']      = false;
		if ( true === $error ) {
			$query->is_404 = true;
		}
	}
}
if ( defined( 'DM_DISABLE_SEARCH' ) && DM_DISABLE_SEARCH ) {
	add_action( 'parse_query', 'dm_disable_search' );
}

/**
 * Remove comment and trackback support from all post types.
 *
 * @return void
 */
function dm_remove_comment_support() {
	foreach ( get_post_types() as $post_type ) {
		remove_post_type_support( $post_type, 'comments' );
		remove_post_type_support( $post_type, 'trackbacks' );
	}
}

/**
 * Remove comments menu item
 *
 * @return void
 */
function dm_remove_comment_menu() {
	remove_menu_page( 'edit-comments.php' );
}

/**
 * Remove comments from the admin bar
 *
 * @return void
 */
function dm_remove_comments_adminbar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
}

if ( defined( 'DM_DISABLE_COMMENTS' ) && DM_DISABLE_COMMENTS ) {
	add_action( 'init', 'dm_remove_comment_support', 900 );
	add_action( 'admin_menu', 'dm_remove_comment_menu' );
	add_action( 'wp_before_admin_bar_render', 'dm_remove_comments_adminbar' );
}








