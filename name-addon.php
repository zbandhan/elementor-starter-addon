<?php
/**
 * Plugin Name: textdomains Addon
 * Description: A textdomains elementor addon for textdomains theme development
 * Plugin URI: https://textdomains.dev/
 * Author: Zulfikar
 * Version: 1.0.0
 * Author URI: https://textdomains.dev/
 *
 * Text Domain: textdomains
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'TEXTDOMAINA_VERSION', '1.0.0' );

define( 'TEXTDOMAINA__FILE__', __FILE__ );
define( 'TEXTDOMAINA_PLUGIN_BASE', plugin_basename( TEXTDOMAINA__FILE__ ) );
define( 'TEXTDOMAINA_PATH', plugin_dir_path( TEXTDOMAINA__FILE__ ) );
define( 'TEXTDOMAINA_MODULES_PATH', TEXTDOMAINA_PATH . 'modules/' );
define( 'TEXTDOMAINA_URL', plugins_url( '/', TEXTDOMAINA__FILE__ ) );
define( 'TEXTDOMAINA_ASSETS_URL', TEXTDOMAINA_URL . 'assets/' );
define( 'TEXTDOMAINA_MODULES_URL', TEXTDOMAINA_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function textdomains_load_plugin() {
	load_plugin_textdomain( 'textdomains' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'textdomains_fail_load' );
		return;
	}

	$elementor_version_required = '1.0.6';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'textdomains_fail_load_out_of_date' );
		return;
	}

	require( TEXTDOMAINA_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'textdomains_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function textdomains_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'textdomainc is not working because you need to activate the Elementor plugin.', 'textdomains' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'textdomains' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'textdomainc is not working because you need to install the Elemenor plugin', 'textdomains' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'textdomains' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function textdomains_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'textdomainc is not working because you are using an old version of Elementor.', 'textdomains' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'textdomains' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
