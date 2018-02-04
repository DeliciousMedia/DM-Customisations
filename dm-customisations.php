<?php
/**
 * Plugin Name: DM Customisations
 * Plugin URI: https://github.com/DeliciousMedia/DM-EI
 * Description: Tweaks and modifications to WordPress along with some helpers and utility functions.
 * Version: 2.0.0
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

// Utility functions & helpers.
require_once( dirname( __FILE__ ) . '/inc/helpers.php' );

// Modifications to WP core behaviour.
require_once( dirname( __FILE__ ) . '/inc/modifications.php' );

// Customisations / helpers which are only required in /wp-admin/.
if ( is_admin() ) {
	require_once( dirname( __FILE__ ) . '/inc/admin.php' );
}
