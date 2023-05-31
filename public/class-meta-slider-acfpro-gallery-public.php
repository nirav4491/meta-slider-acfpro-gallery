<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/nirav4491
 * @since      1.0.0
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/public
 * @author     Nirav Mehta <info@concatstring.com>
 */
class Meta_Slider_Acfpro_Gallery_Public {

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
	 * @since 1.0.0
	 * @param string $plugin_name  name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function msapg_wp_enqueue_scripts_callback() {
		// Register the slick theme style.
		wp_enqueue_style(
			$this->plugin_name . '-slick-css',
			MSAPG_PLUGIN_URL . 'public/css/slick_slider/slick.min.css',
			array(),
			filemtime( MSAPG_PLUGIN_PATH . 'public/css/slick_slider/slick.min.css' )
		);

		// Register the slick theme style.
		wp_enqueue_style(
			$this->plugin_name . '-slick-theme-css',
			MSAPG_PLUGIN_URL . 'public/css/slick_slider/slick-theme.min.css',
			array(),
			filemtime( MSAPG_PLUGIN_PATH . 'public/css/slick_slider/slick-theme.min.css' )
		);
		wp_enqueue_style(
			$this->plugin_name,
			MSAPG_PLUGIN_URL . 'public/css/meta-slider-acfpro-gallery-public.css',
			array(),
			filemtime( MSAPG_PLUGIN_PATH . 'public/css/meta-slider-acfpro-gallery-public.css' ),
			'all'
		);

		wp_enqueue_script(
			$this->plugin_name . '-slick-js',
			MSAPG_PLUGIN_URL . 'public/js/slick.min.js',
			array( 'jquery' ),
			filemtime( MSAPG_PLUGIN_PATH . 'public/js/slick.min.js' ),
			true
		);

		wp_enqueue_script(
			$this->plugin_name . '-core-public-js',
			MSAPG_PLUGIN_URL . 'public/js/meta-slider-acfpro-gallery-public.js',
			array( 'jquery' ),
			filemtime( MSAPG_PLUGIN_PATH . 'public/js/meta-slider-acfpro-gallery-public.js' ),
			true
		);
	}

	/**
	 * Shortcode callback function for display gallary slider.
	 *
	 * @param array $atts Holds shortcode attributes.
	 */
	public function acf_pro_gallary_callback( $atts ) {
		global $post;

		// Return, if admin dashboard.
		if ( is_admin() ) {
			return;
		}

		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts
		);

		$page_id = ( ! empty( $atts['id'] ) ) ? $atts['id'] : $post->ID;
		$images  = get_field( 'build_slideshow', $page_id );

		ob_start(); // Start building the HTML.
		if ( $images ) {
			?>
			<div id="slider" class="gallary-slider">
				<div class="slides">
					<?php
					foreach ( $images as $image ) {
						?>
						<div class="item">
							<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
							<p><?php echo esc_html( $image['caption'] ); ?></p>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		return ob_get_clean();
	}

}
