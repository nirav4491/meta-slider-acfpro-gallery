<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/nirav4491
 * @since      1.0.0
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/includes
 * @author     Nirav Mehta <info@concatstring.com>
 */
class Meta_Slider_Acfpro_Gallery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Meta_Slider_Acfpro_Gallery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = ( defined( 'MSAPG_PLUGIN_VERSION' ) ) ? MSAPG_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'meta-slider-acfpro-gallery';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Meta_Slider_Acfpro_Gallery_Loader. Orchestrates the hooks of the plugin.
	 * - Meta_Slider_Acfpro_Gallery_i18n. Defines internationalization functionality.
	 * - Meta_Slider_Acfpro_Gallery_Admin. Defines all hooks for the admin area.
	 * - Meta_Slider_Acfpro_Gallery_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once 'class-meta-slider-acfpro-gallery-loader.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once MSAPG_PLUGIN_PATH . 'admin/class-meta-slider-acfpro-gallery-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once MSAPG_PLUGIN_PATH . 'public/class-meta-slider-acfpro-gallery-public.php';

		$this->loader = new Meta_Slider_Acfpro_Gallery_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Meta_Slider_Acfpro_Gallery_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'msapg_admin_enqueue_scripts_callback' );
		$this->loader->add_action( 'cli_init', $plugin_admin, 'msapg_cli_init_callback' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Meta_Slider_Acfpro_Gallery_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'msapg_wp_enqueue_scripts_callback' );
		$this->loader->add_shortcode( 'acf_pro_gallery', $plugin_public, 'acf_pro_gallary_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Meta_Slider_Acfpro_Gallery_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
