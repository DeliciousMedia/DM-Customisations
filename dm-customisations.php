<?php
/**
 * Plugin Name: DM Customisations
 * Plugin URI: https://github.com/DeliciousMedia/DM-Customisations
 * Description: Tweaks and modifications to WordPress along with some helpers and utility functions.
 * Version: 2.2.0
 * Author: Delicious Media Limited
 * Author URI: https://www.deliciousmedia.co.uk/
 * License: GPLv3 or later
 * Text Domain: dm-customisations
 * Contributors: davepullig
 *
 * @package dm-customisations
 **/

// Set our defaults.
defined( 'DM_DISABLE_COMMENTS' ) || define( 'DM_DISABLE_COMMENTS', true );
defined( 'DM_DISABLE_SEARCH' ) || define( 'DM_DISABLE_SEARCH', false );
defined( 'DM_DISABLE_EMOJIS' ) || define( 'DM_DISABLE_EMOJIS', true );
defined( 'DM_DISABLE_REST_ANON' ) || define( 'DM_DISABLE_REST_ANON', true );
defined( 'DM_DISABLE_RSS' ) || define( 'DM_DISABLE_RSS', true );
defined( 'DM_MODIFY_PLUGINS_SCREEN' ) || define( 'DM_MODIFY_PLUGINS_SCREEN', true );
defined( 'DM_GFORM_DELETE' ) || define( 'DM_GFORM_DELETE', true );
defined( 'DM_LASTLOGIN' ) || define( 'DM_LASTLOGIN', true );


// Array of user roles to give the ability to flush the object cache.
defined( 'DM_CACHEFLUSH_PERMS' ) || define( 'DM_CACHEFLUSH_PERMS', [ 'administrator', 'editor' ] );

// Utility functions & helpers.
require_once( dirname( __FILE__ ) . '/inc/helpers.php' );

// Modifications to WP core behaviour.
require_once( dirname( __FILE__ ) . '/inc/modifications.php' );

// Modifications to third party plugins.
require_once( dirname( __FILE__ ) . '/inc/thirdparty.php' );

// Modifications to WordPress plugins section.
if ( DM_MODIFY_PLUGINS_SCREEN ) {
	require_once( dirname( __FILE__ ) . '/inc/plugins.php' );
}
// Track last login times for accounts.
if ( DM_LASTLOGIN ) {
	require_once( dirname( __FILE__ ) . '/inc/last-login.php' );
}

// Customisations / helpers which are only required in /wp-admin/.
if ( is_admin() ) {
	require_once( dirname( __FILE__ ) . '/inc/admin.php' );
}
