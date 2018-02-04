<?php
/**
 * Functionality which is only applicable in wp-admin
 *
 * @package  dm-customisations
 */


/**
 * CMB2 specificity functionality.
 */
if ( defined( 'CMB2_LOADED' ) ) {

	/**
	 * Allow CMB2's show_on to use slugs.
	 *
	 * @param  bool  $display  True for show metabox, false for don't.
	 * @param  array $meta_box Metabox data.
	 *
	 * @link   https://github.com/CMB2/CMB2/wiki/Adding-your-own-show_on-filters#example-page-slug-show_on-filter
	 *
	 * @return bool
	 */
	function dm_metabox_show_on_slug( $display, $meta_box ) {
		if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
			return $display;
		}

		if ( 'slug' !== $meta_box['show_on']['key'] ) {
			return $display;
		}

		$post_id = 0;

		if ( isset( $_GET['post'] ) ) {
			$post_id = absint( $_GET['post'] );
		} elseif ( isset( $_POST['post_ID'] ) ) { // WPCS: CSRF ok.
			$post_id = absint( $_POST['post_ID'] );
		}

		if ( ! $post_id ) {
			return $display;
		}

		$slug = get_post( $post_id )->post_name;

		return in_array( $slug, (array) $meta_box['show_on']['value'] );
	}

	add_filter( 'cmb2_show_on', 'dm_metabox_show_on_slug', 10, 2 );
}
