<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/nirav4491
 * @since      1.0.0
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/admin
 * @author     Nirav Mehta <info@concatstring.com>
 */
class Meta_Slider_Acfpro_Gallery_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Admin notice to display the error if WP CLI is undefined.
	 *
	 * @since 1.0.0
	 */
	public function msapg_admin_notices_cli_missing_callback() {
		$wpcli_supported  = ( defined( 'WP_CLI' ) && WP_CLI );
		$this_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/meta-slider-acfpro-gallery/meta-slider-acfpro-gallery.php' );
		$this_plugin_name = ( ! empty( $this_plugin_data['Name'] ) ) ? $this_plugin_data['Name'] : 'Meta Slider to ACF Pro Gallery';

		if ( ! $wpcli_supported ) {
			echo wp_kses_post(
				'<div class="notice error is-dismissible"><p>'
				. sprintf( __( '%1$s%3$s%2$s is ineffective as WP CLI is not defined.', 'meta-slider-acfpro-gallery' ), '<strong>', '</strong>', $this_plugin_name )
				. '</p></div>'
			);
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function msapg_admin_enqueue_scripts_callback() {
		// Enqueue the plugin style.
		wp_enqueue_style(
			$this->plugin_name,
			MSAPG_PLUGIN_URL . 'admin/css/meta-slider-acfpro-gallery-admin.css',
			array(),
			filemtime( MSAPG_PLUGIN_PATH . 'admin/css/meta-slider-acfpro-gallery-admin.css' ),
			'all'
		);

		// Enqueue the plugin script.
		wp_enqueue_script(
			$this->plugin_name,
			MSAPG_PLUGIN_URL . 'admin/js/meta-slider-acfpro-gallery-admin.js',
			array( 'jquery' ),
			filemtime( MSAPG_PLUGIN_PATH . 'admin/js/meta-slider-acfpro-gallery-admin.js' ),
			true
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function msapg_cli_init_callback() {
		require_once MSAPG_PLUGIN_PATH . 'includes/cli/class-msapg-build-slideshow.php';
		WP_CLI::add_command( 'build_slideshow', 'Msapg_Build_Slideshow' );
	}
}
