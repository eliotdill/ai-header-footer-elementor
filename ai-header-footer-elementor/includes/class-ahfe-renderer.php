<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Renderer {

	public static function init(): void {
		// Enqueue each active template's Elementor CSS before wp_head fires.
		// Must happen in wp_enqueue_scripts, not at render time, because render
		// happens in wp_body_open / wp_footer — both after wp_head has already fired.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_template_styles' ] );

		// Inject CSS that hides the theme's native header/footer elements
		// so they don't stack on top of our Elementor templates.
		add_action( 'wp_head', [ __CLASS__, 'inject_suppress_css' ], PHP_INT_MAX );

		// Register each content type's injection hook from the registry.
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$hook     = $type['hook'];
			$priority = isset( $type['hook_priority'] ) ? (int) $type['hook_priority'] : 5;
			$type_id  = $type['id'];

			add_action( $hook, static function () use ( $type_id ) {
				self::inject( $type_id );
			}, $priority );
		}
	}

	/**
	 * Enqueue Elementor-generated CSS for all active templates.
	 * Called on wp_enqueue_scripts (before wp_head) so styles land in <head>.
	 */
	public static function enqueue_template_styles(): void {
		if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			return;
		}
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( $template_id ) {
				( new \Elementor\Core\Files\CSS\Post( $template_id ) )->enqueue();
			}
		}
	}

	/**
	 * Output a <style> block that hides the theme's native header/footer elements.
	 * Only emitted when our templates are active for that slot.
	 */
	public static function inject_suppress_css(): void {
		$css = '';
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( $template_id && ! empty( $type['suppress_css'] ) ) {
				$css .= $type['suppress_css'] . '{display:none!important;}';
			}
		}
		if ( $css ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<style id="ahfe-suppress">' . $css . '</style>';
		}
	}

	/**
	 * Inject the Elementor template for a content type.
	 * Hooked into wp_body_open (header) or wp_footer (footer).
	 */
	public static function inject( string $type_id ): void {
		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			return;
		}

		$template_id = (int) get_option( $type['option_key'], 0 );
		if ( ! $template_id ) {
			return;
		}

		echo '<div class="ahfe-' . esc_attr( $type_id ) . '-wrap">';
		self::render( $template_id );
		echo '</div>';
	}

	/**
	 * Render Elementor template content by post ID.
	 * CSS is already enqueued via enqueue_template_styles(); skip re-enqueue here.
	 */
	public static function render( int $post_id ): void {
		if ( ! $post_id ) {
			return;
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
