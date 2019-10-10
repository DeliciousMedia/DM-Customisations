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

/**
 * Add a button to the admin bar to allow users to flush the object cache.
 *
 * @return void
 */
function dm_add_flush_object_cache_button() {
	global $wp_admin_bar;

	$user = wp_get_current_user();
	if ( array_intersect( DM_CACHEFLUSH_PERMS, (array) $user->roles ) ) {

		$wp_admin_bar->add_node(
			[
				'id' => 'dm-flush-object-cache',
				'parent' => 'top-secondary',
				'title' => __( 'Flush Cache' ),
				'href' => wp_nonce_url(
					add_query_arg(
						[
							'action' => 'dm_flush_object_cache',
							admin_url( 'index.php' ),
						]
					),
					'dm-flush-object-cache'
				),
			]
		);
	}
}
add_action( 'admin_bar_menu', 'dm_add_flush_object_cache_button' );

/**
 * Flush the object cache, and show results via an admin notice.
 *
 * @return void
 */
function dm_process_cache_flush_request() {
	if ( ! isset( $_GET['action'] ) || 'dm_flush_object_cache' != $_GET['action'] ) {
		return;
	}

	if ( ! check_admin_referer( 'dm-flush-object-cache' ) ) {
		return;
	}

	$user = wp_get_current_user();
	if ( ! array_intersect( DM_CACHEFLUSH_PERMS, (array) $user->roles ) ) {
		return;
	}

	$result = wp_cache_flush();

	if ( $result ) {
		add_action(
			'admin_notices',
			function() {
			?><div class="notice notice-success is-dismissible"><p>Object cache flushed successfully.</p></div>
			<?php
			}
		);
	} else {
		add_action(
			'admin_notices',
			function() {
			?>
			<div class="notice notice-warning is-dismissible"><p>Error flushing object cache.</p></div>
			<?php
			}
		);

	}

}
add_action( 'admin_init', 'dm_process_cache_flush_request' );

/**
 * Insert inline CSS in wp-admin
 *
 * @return void
 */
function dm_customisations_insert_admin_css() {
	if ( ! is_user_logged_in() ) {
		return;
	}
	?>
	<style type="text/css">
		#wp-admin-bar-dm-flush-object-cache {
			background-color: #444 !important;
			color: #fff !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'dm_customisations_insert_admin_css' );
