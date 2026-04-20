<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Importer {

	/**
	 * Import an Elementor JSON file and save it as an elementor_library post.
	 *
	 * @param string $file_path Absolute path to the uploaded .json file.
	 * @param string $type_id   Content type ID (e.g. 'header', 'footer').
	 * @return int|\WP_Error    New post ID on success, WP_Error on failure.
	 */
	public static function import_json( string $file_path, string $type_id ) {
		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			return new \WP_Error( 'invalid_type', __( 'Unknown content type.', 'ai-hfe' ) );
		}

		// Read and decode.
		$raw = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( false === $raw ) {
			return new \WP_Error( 'read_error', __( 'Could not read the uploaded file.', 'ai-hfe' ) );
		}

		$data = json_decode( $raw, true );
		if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
			return new \WP_Error( 'invalid_json', __( 'The file does not contain valid JSON.', 'ai-hfe' ) );
		}

		if ( empty( $data['content'] ) || ! is_array( $data['content'] ) ) {
			return new \WP_Error( 'missing_content', __( 'The JSON file is missing an Elementor "content" array.', 'ai-hfe' ) );
		}

		$title          = ! empty( $data['title'] ) ? sanitize_text_field( $data['title'] ) : ucfirst( $type_id ) . ' — Imported';
		$elementor_data = wp_json_encode( $data['content'] );

		// Create the post.
		$post_id = wp_insert_post( [
			'post_type'   => 'elementor_library',
			'post_title'  => $title,
			'post_status' => 'publish',
		], true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, '_elementor_data', $elementor_data );
		// Use 'page' (not 'section') so Elementor opens this template in full-width
		// canvas mode rather than a constrained section preview.
		update_post_meta( $post_id, '_elementor_template_type', 'page' );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_ahfe_content_type', $type_id );

		// Regenerate Elementor CSS for this post.
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
			$css_file->update();
		}

		return $post_id;
	}
}
