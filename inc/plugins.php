<?php
/**
 * Modifications to plugin functionality.
 *
 * @package dm-customisations
 */

/**
 * Add our tab to the list of tabs on the plugins screen.
 *
 * @param  array $tabs Existing array of tabs.
 *
 * @return array
 */
function dm_plugins_add_tab_link( $tabs ) {

	$tabs = array_merge(
		[
			'deliciousmedia' => esc_html__( 'Delicious Media Recommendations', 'dm-customisations' ),
		],
		$tabs
	);

	return $tabs;
}
add_filter( 'install_plugins_tabs', 'dm_plugins_add_tab_link' );

/**
 * Filter the arguments passed to plugins_api() for DM suggested tab
 *
 * @param array $args Plugin arguments passed to api.
 * @return array
 */
function dm_plugins_filter_args( $args ) {
	$args = [
		'page'     => 1,
		'per_page' => 100,
		'fields'   => [
			'last_updated'    => true,
			'active_installs' => true,
			'icons'           => true,
		],
		'locale'   => get_user_locale(),
		'user'     => 'deliciousmedia',
	];

	return $args;
}
add_filter( 'install_plugins_table_api_args_deliciousmedia', 'dm_plugins_filter_args' );

/**
 * Setup DM suggested plugin display table
 */
add_action( 'install_plugins_deliciousmedia', 'display_plugins_table' );

/**
 * On tabs other than our recommended plugins, display a friendly warning about installing plugins.
 *
 * @return void
 */
function dm_plugins_admin_notice() {
	?>
	<div class="notice notice-warning">
		<p>
			<?php
				printf(
					// translators: %s is a link to the DM Suggested plugins screen.
					__( "Some plugins may affect the speed and reliability of your website, or change the way it appears. If you are unsure, please feel free to contact <a href='https://www.deliciousmedia.co.uk/'>Delicious Media</a> for advice or take a look at the <a href='%s'>plugins Delicious Media recommendeds</a>.", 'dm-customisations' ),
					esc_url( network_admin_url( 'plugin-install.php?tab=deliciousmedia' ) )
				);
			?>
		</p>
	</div>
	<?php
}

/**
 * Add our admin notice to both the plugin admin and network plugin admin screens.
 *
 * @return void
 */
function dm_plugins_add_admin_notice() {
	add_action( 'admin_notices', 'dm_plugins_admin_notice' );
	add_action( 'network_admin_notices', 'dm_plugins_admin_notice' );
}

add_action( 'install_plugins_pre_featured', 'dm_plugins_add_admin_notice' );
add_action( 'install_plugins_pre_popular', 'dm_plugins_add_admin_notice' );
add_action( 'install_plugins_pre_favorites', 'dm_plugins_add_admin_notice' );
add_action( 'install_plugins_pre_beta', 'dm_plugins_add_admin_notice' );
add_action( 'install_plugins_pre_search', 'dm_plugins_add_admin_notice' );
add_action( 'install_plugins_pre_dashboard', 'dm_plugins_add_admin_notice' );
