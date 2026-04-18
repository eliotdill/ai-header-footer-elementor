<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Core {

	public static function init(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ __CLASS__, 'notice_elementor_missing' ] );
			return;
		}

		// Register built-in content types.
		//
		// 'hook'                   — WordPress action that fires when the theme loads header/footer.
		// 'template'               — File in /templates/ that replaces the theme's output.
		// 'elementor_canvas_hook'  — Elementor's hook that fires on canvas-template pages (and in the editor).
		// 'elementor_canvas_priority' — Priority on the canvas hook.

		AHFE_Content_Types::register( [
			'id'                       => 'header',
			'label'                    => 'Header',
			'description'              => 'Replaces your theme\'s default header sitewide.',
			'option_key'               => 'ahfe_header_id',
			'hook'                     => 'get_header',
			'template'                 => 'hfe-header.php',
			'elementor_canvas_hook'    => 'elementor/page_templates/canvas/before_content',
			'elementor_canvas_priority' => 10,
		] );

		AHFE_Content_Types::register( [
			'id'                       => 'footer',
			'label'                    => 'Footer',
			'description'              => 'Replaces your theme\'s default footer sitewide.',
			'option_key'               => 'ahfe_footer_id',
			'hook'                     => 'get_footer',
			'template'                 => 'hfe-footer.php',
			'elementor_canvas_hook'    => 'elementor/page_templates/canvas/after_content',
			'elementor_canvas_priority' => 10,
		] );

		// Boot subsystems.
		AHFE_Renderer::init();
		AHFE_Admin::init();
	}

	public static function notice_elementor_missing(): void {
		?>
		<div class="notice notice-error">
			<p>
				<strong><?php esc_html_e( 'AI Header & Footer for Elementor', 'ai-hfe' ); ?></strong>
				<?php esc_html_e( 'requires Elementor to be installed and active.', 'ai-hfe' ); ?>
			</p>
		</div>
		<?php
	}
}
