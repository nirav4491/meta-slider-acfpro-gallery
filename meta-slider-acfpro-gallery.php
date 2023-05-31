<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nirav4491
 * @since             1.0.0
 * @package           Meta_Slider_Acfpro_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Meta Slider to ACF Pro Gallery
 * Plugin URI:        https://github.com/nirav4491/meta-slider-to-acfpro-gallery
 * Description:       This plugin converts the slider built using Meta Slider plugin into the ACF Pro Gallery field.
 * Version:           1.0.0
 * Author:            Nirav Mehta
 * Author URI:        https://github.com/nirav4491
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       meta-slider-acfpro-gallery
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MSAPG_PLUGIN_VERSION', '1.0.0' );

// Currently plugin URL.
if ( ! defined( 'MSAPG_PLUGIN_URL' ) ) {
	define( 'MSAPG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Currently plugin path.
if ( ! defined( 'MSAPG_PLUGIN_PATH' ) ) {
	define( 'MSAPG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-meta-slider-acfpro-gallery-activator.php
 */
function msapg_activate_meta_slider_acfpro_gallery() {
	require_once MSAPG_PLUGIN_PATH . 'includes/class-meta-slider-acfpro-gallery-activator.php';
	Meta_Slider_Acfpro_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-meta-slider-acfpro-gallery-deactivator.php
 */
function msapg_deactivate_meta_slider_acfpro_gallery() {

	require_once MSAPG_PLUGIN_PATH . 'includes/class-meta-slider-acfpro-gallery-deactivator.php';
	Meta_Slider_Acfpro_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'msapg_activate_meta_slider_acfpro_gallery' );
register_deactivation_hook( __FILE__, 'msapg_deactivate_meta_slider_acfpro_gallery' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function msapg_run_meta_slider_acfpro_gallery() {

	// The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
	require MSAPG_PLUGIN_PATH . 'includes/class-meta-slider-acfpro-gallery.php';
	$plugin = new Meta_Slider_Acfpro_Gallery();
	$plugin->run();

}

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires WooCommerce to be installed and active
 */
function msacg_plugins_loaded_callback() {
	$active_plugins       = get_option( 'active_plugins' );
	$is_metaslider_active = in_array( 'ml-slider/ml-slider.php', $active_plugins, true );
	$is_acfpro_active     = in_array( 'advanced-custom-fields-pro/acf.php', $active_plugins, true );

	// Check if the current user has the capability to activate plugins and the required plugins are active.
	if ( current_user_can( 'activate_plugins' ) && ( false === $is_metaslider_active || false === $is_acfpro_active ) ) {
		add_action( 'admin_notices', 'msapg_admin_notices_callback' );
	} else {
		msapg_run_meta_slider_acfpro_gallery();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ccq_plugin_links' );
	}
}

add_action( 'plugins_loaded', 'msacg_plugins_loaded_callback' );

/**
 * Show admin notice in case of WooCommerce plguin is missing
 */
function msapg_admin_notices_callback() {
	$this_plugin_data       = get_plugin_data( __FILE__ );
	$this_plugin_name       = ( ! empty( $this_plugin_data['Name'] ) ) ? $this_plugin_data['Name'] : 'Meta Slider to ACF Pro Gallery';
	$metaslider_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/ml-slider/ml-slider.php' );
	$metaslider_plugin_name = ( ! empty( $metaslider_plugin_data['Name'] ) ) ? $metaslider_plugin_data['Name'] : 'MetaSlider';
	$acfpro_plugin_data     = get_plugin_data( WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php' );
	$acfpro_plugin_name     = ( ! empty( $acfpro_plugin_data['Name'] ) ) ? $acfpro_plugin_data['Name'] : 'Advanced Custom Fields PRO';

	echo wp_kses_post(
		'<div class="notice error is-dismissible"><p>'
		. sprintf( __( '%1$s%3$s%2$s is ineffective as it requires %1$s%4$s%2$s and %1$s%5$s%2$s to be installed and active.', 'meta-slider-acfpro-gallery' ), '<strong>', '</strong>', $this_plugin_name, $metaslider_plugin_name, $acfpro_plugin_name ) // phpcs:ignore
		. '</p></div>'
	);
}

/**
 * Settings link on plugin listing page
 *
 * @param array $links Plugin action links.
 * @return array
 */
function ccq_plugin_links( $links ) {

	return array_merge(
		$links,
		array(
			'<a title="' . esc_attr__( 'Settings', 'meta-slider-acfpro-gallery' ) . '" href="' . admin_url() . '">' . esc_html__( 'Settings', 'meta-slider-acfpro-gallery' ) . '</a>',
		)
	);
}

if ( ! function_exists( 'debug' ) ) {
	/**
	 * Debug function.
	 * Should be removed from production environment.
	 *
	 * @param array $params Holds parameters.
	 */
	function debug( $params ) {
		echo '<pre>';
		print_r( $params ); // phpcs:ignore
		echo '</pre>';
	}
}
