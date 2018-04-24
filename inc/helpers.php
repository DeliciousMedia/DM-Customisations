<?php
/**
 * Helpers & utility functions
 *
 * @package dm-customisations
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Are we running under a Delicious Media development environment?
 *
 * @return bool
 */
function dm_is_dev() {
	if ( defined( 'DM_ENVIRONMENT' ) && 'DEV' == DM_ENVIRONMENT ) {
		return true;
	}
	return false;
}

/**
 * Are we on a blog page?
 *
 * @return bool
 */
function dm_is_blog_page() {
	global $post;
	$posttype = get_post_type( $post );
	return ( ( ( is_archive() ) || ( is_author() ) || ( is_category() ) || ( is_home() ) || ( is_single() ) || ( is_tag() ) ) && ( 'post' == $posttype ) ) ? true : false;
}

