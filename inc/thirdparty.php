<?php
/**
 * Modifications to third party plugins.
 *
 * @package dm-customisations
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Shift the Yoast metabox to the bottom of post screens.
 */
add_filter(
	'wpseo_metabox_prio',
	function() {
		return 'low';
	}
);

/**
 * Delete GravityForms entries upon submission to avoid retaining data we don't need.
 */
if ( defined( 'DM_GFORM_DELETE' ) && DM_GFORM_DELETE ) {
	add_action(
		'gform_after_submission',
		function( $entry, $form ) {
			GFAPI::delete_entry( $entry['id'] );
		},
		99,
		2
	);
}

/**
 * Don't show WooCommerce Extension suggestions.
 *
 * @link: https://woocommerce.wordpress.com/2019/04/03/extension-suggestions-in-3-6/
 */
add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

/**
 * Hide ACF from admin area unless we're in a dev environment.
 */
if ( true !== dm_is_dev() ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}
