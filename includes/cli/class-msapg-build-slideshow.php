<?php
/**
 * The file that defines the WP CLI command.
 *
 * A class definition that includes the wp cli command that converts meta slider to acf pro gallery field.
 *
 * @link       https://github.com/nirav4491
 * @since      1.0.0
 *
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/includes/cli
 */

/**
 * The file that defines the WP CLI command.
 *
 * A class definition that includes the wp cli command that converts meta slider to acf pro gallery field.
 *
 * @since      1.0.0
 * @package    Meta_Slider_Acfpro_Gallery
 * @subpackage Meta_Slider_Acfpro_Gallery/includes
 * @author     Nirav Mehta <info@concatstring.com>
 */
class Msapg_Build_Slideshow {
	/**
	 * This function accepts argument, sets the WP CLI command to receive url argument.
	 *
	 * @param array $args Accepts the list of indexed arguments.
	 * @param array $assoc_args Accepts the list of associative arguments.
	 */
	public function convert( $args, $assoc_args ) {
		global $wpdb;
		$slider_id = (int) WP_CLI\Utils\get_flag_value( $assoc_args, 'slider' );
		$page_id   = (int) WP_CLI\Utils\get_flag_value( $assoc_args, 'page' );

		// If the slider ID is empty or 0.
		if ( empty( $slider_id ) || 0 === $slider_id ) {
			WP_CLI::error( __( 'Slider ID is either empty or invalid.', 'meta-slider-acfpro-gallery' ) );
		}

		// If the Page ID is empty or 0.
		if ( empty( $page_id ) || 0 === $page_id ) {
			WP_CLI::error( __( 'Page ID is either empty or invalid.', 'meta-slider-acfpro-gallery' ) );
		}

		/**
		 * You've got a valid slider ID.
		 * Check if a slider exists from the ID.
		 */
		$slider_data = get_post( $slider_id );

		// Check if there is a post object returned. If not, throw error.
		if ( empty( $slider_data ) ) {
			WP_CLI::error( sprintf( __( 'No post object exists with the ID: %1$d', 'meta-slider-acfpro-gallery' ), $slider_id ) ); // phpcs:ignore
		}

		// Check if the post object returned is a meta slider. If not, throw error.
		if ( ! empty( $slider_data->post_type ) && 'ml-slider' !== $slider_data->post_type ) {
			WP_CLI::error( sprintf( __( 'No meta slider exists with the ID: %1$d', 'meta-slider-acfpro-gallery' ), $slider_id ) ); // phpcs:ignore
		}

		/**
		 * You've got a valid Page ID.
		 * Check if a page exists from the ID.
		 */
		$page_data = get_post( $page_id );

		// Check if there is a post object returned. If not, throw error.
		if ( empty( $page_data ) ) {
			WP_CLI::error( sprintf( __( 'No post object exists with the ID: %1$d', 'meta-slider-acfpro-gallery' ), $page_id ) ); // phpcs:ignore
		}

		/**
		 * Now, you have a perfect meta slider ID.
		 * Get the slides information now.
		 */
		$slider_array = array();
		$images_id    = array();
		$slider_data  = get_post( $slider_id );

		// Get the slider term ID.
		$ml_slider_term_id = get_term_by( 'slug', $slider_id, 'ml-slider' )->term_id;

		// Get the slides ID from slider term id.
		$get_slider_images = $wpdb->get_results( "SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id={$ml_slider_term_id}", ARRAY_A ); // phpcs:ignore
		if ( empty( $get_slider_images ) ) {
			WP_CLI::error( __( 'Slider not have any slider image in it.', 'meta-slider-acfpro-gallery' ) );
		}

		// Fetching the slider image data.
		foreach ( $get_slider_images as $slider_image ) {
			$image_id       = $slider_image['object_id'];
			$thumbnai_id    = get_post_meta( $image_id, '_thumbnail_id', true ); // Slider image ID.
			$caption        = get_the_excerpt( $image_id ); // Slider image Caption.
			$slider_array   = array(
				'ID'           => $thumbnai_id,
				'post_excerpt' => $caption,
			);
			wp_update_post( $slider_array );
			$images_id[]    = $thumbnai_id;
		}
		$old_galary_images = get_post_meta( $page_id, 'build_slideshow', true );
		if ( ! empty( $old_galary_images ) ) {
			$images_id = array_merge( $old_galary_images, $images_id );
		}

		// Updating the ACF gallary field of page.
		update_field( 'build_slideshow', $images_id, $page_id );
		
		WP_CLI::success( 'Gallary Field Updated' );
	}
}
