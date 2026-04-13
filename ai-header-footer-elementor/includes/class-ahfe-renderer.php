<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Renderer {

	public static function init(): void {
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$hook    = $type['hook'];
			$type_id = $type['id'];

			// WordPress fires get_header / get_footer before the theme's template is included.
			// We intercept the hook, suppress the theme output, and render our own template.
			add_action( $hook, static function () use ( $type_id ) {
				self::override( $type_id );
			} );
		}
	}

	/**
	 * Suppress the theme's default output and render the Elementor template instead.
	 */
	public static function override( string $type_id ): void {
		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			return;
		}

		$template_id = (int) get_option( $type['option_key'], 0 );
		if ( ! $template_id ) {
			return;
		}

		// Suppress whatever the theme would have printed.
		ob_start();
		// Load our replacement template which calls wp_head / wp_footer and renders Elementor content.
		$template_file = AHFE_DIR . 'templates/' . $type['template'];
		if ( file_exists( $template_file ) ) {
			include $template_file;
		}
		ob_end_flush();
	}

	/**
	 * Render Elementor template content by post ID.
	 * Called from the template files.
	 */
	public static function render( int $post_id ): void {
		if ( ! $post_id ) {
			return;
		}

		// Enqueue this template's Elementor-generated CSS.
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
			$css_file->enqueue();
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $post_id, true );
	}

	/**
	 * Return the active template post ID for a given content type.
	 */
	public static function get_template_id( string $type_id ): int {
		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			return 0;
		}
		return (int) get_option( $type['option_key'], 0 );
	}
}
