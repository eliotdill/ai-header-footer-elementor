<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Renderer {

	public static function init(): void {
		// Enqueue Elementor CSS + Font Awesome before wp_head fires.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_template_styles' ] );

		// Add body classes for active content types.
		add_filter( 'body_class', [ __CLASS__, 'body_class' ] );

		// Register theme-page and canvas hooks on 'wp' (after the query is set up).
		add_action( 'wp', [ __CLASS__, 'register_hooks' ] );

		// Auto-migrate imported templates from 'section' to 'page' type so the
		// Elementor editor opens them in full-width canvas mode (not a constrained section preview).
		add_action( 'wp', [ __CLASS__, 'maybe_upgrade_template_types' ] );
	}

	/**
	 * Ensure active templates use _elementor_template_type = 'page' so the editor
	 * opens them full-width. Runs once per request; update_post_meta skips the write
	 * if the value is already correct.
	 */
	public static function maybe_upgrade_template_types(): void {
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( ! $template_id ) {
				continue;
			}
			$current = get_post_meta( $template_id, '_elementor_template_type', true );
			if ( 'page' !== $current ) {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}
		}
	}

	/**
	 * Register hooks for each active content type.
	 * Called on 'wp' so the current page context is available.
	 */
	public static function register_hooks(): void {
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( ! $template_id ) {
				continue;
			}

			// Strategy A: intercept get_header / get_footer on standard theme pages.
			// We output our full replacement template and silently discard the theme's file.
			add_action( $type['hook'], static function () use ( $type ) {
				AHFE_Renderer::override_theme_template( $type );
			} );

			// Strategy B: Elementor canvas pages (editor + canvas-template frontend pages).
			// On these pages the theme's header/footer is never called — Elementor fires its
			// own hooks around the page content.
			if ( ! empty( $type['elementor_canvas_hook'] ) ) {
				$priority = isset( $type['elementor_canvas_priority'] ) ? (int) $type['elementor_canvas_priority'] : 10;
				add_action( $type['elementor_canvas_hook'], static function () use ( $type ) {
					AHFE_Renderer::inject_canvas( $type );
				}, $priority );
			}
		}
	}

	/**
	 * Strategy A: Replace theme's header.php / footer.php.
	 *
	 * 1. Require our full replacement template (which calls wp_head() / wp_footer() and renders content).
	 * 2. Remove all wp_head / wp_footer actions so they can't fire a second time.
	 * 3. Load the theme's original file via locate_template() but discard its output.
	 */
	public static function override_theme_template( array $type ): void {
		$template_file = AHFE_DIR . 'templates/' . $type['template'];
		if ( ! file_exists( $template_file ) ) {
			return;
		}

		// Output our replacement (contains full HTML structure + wp_head or wp_footer).
		require $template_file;

		// Prevent wp_head / wp_footer from firing again when the theme's file is loaded below.
		if ( 'get_header' === $type['hook'] ) {
			remove_all_actions( 'wp_head' );
			$theme_file = 'header.php';
		} else {
			remove_all_actions( 'wp_footer' );
			$theme_file = 'footer.php';
		}

		// Silently run and discard the theme's original file.
		ob_start();
		locate_template( [ $theme_file ], true );
		ob_get_clean();
	}

	/**
	 * Strategy B: Elementor canvas pages.
	 *
	 * On canvas pages (including inside the Elementor editor) the theme's header/footer
	 * is not loaded. Elementor fires elementor/page_templates/canvas/before_content and
	 * after_content. We output the Elementor template content directly here.
	 *
	 * IMPORTANT: We bail if the current post IS the template being injected. Without this
	 * guard, opening the header template in the Elementor editor would cause the header to
	 * be rendered twice — once by this hook and once as the post's own content area.
	 */
	public static function inject_canvas( array $type ): void {
		$template_id = (int) get_option( $type['option_key'], 0 );
		if ( ! $template_id ) {
			return;
		}

		// Don't inject the template into its own Elementor editor session.
		if ( (int) get_the_ID() === $template_id ) {
			return;
		}

		self::render( $template_id );
	}

	/**
	 * Enqueue Elementor-generated CSS for all active templates, plus Font Awesome
	 * so icon-based widgets (e.g. hamburger menu buttons using fa-bars) render correctly.
	 * Must run in wp_enqueue_scripts (before wp_head) so styles land in <head>.
	 */
	public static function enqueue_template_styles(): void {
		if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			return;
		}

		$has_active_template = false;

		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( $template_id ) {
				( new \Elementor\Core\Files\CSS\Post( $template_id ) )->enqueue();
				$has_active_template = true;
			}
		}

		// Load Font Awesome when at least one template is active.
		// PressMeGPT templates use FA icons (e.g. fa-bars for mobile menu toggle).
		// Elementor only auto-loads FA when a widget on the page explicitly uses icons;
		// raw HTML widgets containing <i class="fas ..."> are invisible without this.
		if ( $has_active_template ) {
			self::enqueue_font_awesome();
		}
	}

	/**
	 * Enqueue Font Awesome using Elementor's bundled copy when available,
	 * falling back to the registered elementor-icons stylesheet.
	 */
	private static function enqueue_font_awesome(): void {
		// Try Elementor's bundled Font Awesome 5 (all.min.css covers solid/regular/brands).
		if ( defined( 'ELEMENTOR_ASSETS_PATH' ) && defined( 'ELEMENTOR_ASSETS_URL' ) ) {
			$fa_path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/all.min.css';
			if ( file_exists( $fa_path ) ) {
				wp_enqueue_style(
					'ahfe-font-awesome',
					ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
					[],
					AHFE_VERSION
				);
				return;
			}
		}

		// Fallback: use the Elementor icons stylesheet that's always registered.
		if ( wp_style_is( 'elementor-icons', 'registered' ) ) {
			wp_enqueue_style( 'elementor-icons' );
		}
	}

	/**
	 * Add body classes for active content types.
	 */
	public static function body_class( array $classes ): array {
		foreach ( AHFE_Content_Types::get_all() as $type ) {
			$template_id = (int) get_option( $type['option_key'], 0 );
			if ( $template_id ) {
				$classes[] = 'ahfe-' . $type['id'];
			}
		}
		return $classes;
	}

	/**
	 * Render Elementor template content by post ID.
	 * CSS is already enqueued in wp_enqueue_scripts.
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
