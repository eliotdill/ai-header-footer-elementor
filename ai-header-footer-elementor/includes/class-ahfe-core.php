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
		// hook        — WordPress action used to inject content into the page.
		// hook_priority — priority on that action (lower = earlier output).
		// suppress_css  — CSS selectors for the theme's native element to hide.
		AHFE_Content_Types::register( [
			'id'           => 'header',
			'label'        => 'Header',
			'description'  => 'Replaces your theme\'s default header sitewide.',
			'option_key'   => 'ahfe_header_id',
			// wp_body_open fires right after <body> opens, before the theme's
			// visual <header> element — so our template renders first, then
			// suppress_css hides the theme's native header below it.
			'hook'         => 'wp_body_open',
			'hook_priority' => 5,
			'template'     => 'hfe-header.php',
			'suppress_css' => implode( ',', [
				'.site-header',
				'header.site-header',
				'#masthead',
				'#site-header',
				'.header-main',
				'.main-header',
				'header[role="banner"]',
				'.header-area',
				'#top-bar',
				'.global-header',
				// Block / FSE themes
				'header.wp-block-template-part',
				// Elementor Pro location header (if present alongside ours)
				'.elementor-location-header',
			] ),
		] );

		AHFE_Content_Types::register( [
			'id'           => 'footer',
			'label'        => 'Footer',
			'description'  => 'Replaces your theme\'s default footer sitewide.',
			'option_key'   => 'ahfe_footer_id',
			// wp_footer fires before </body> so our template lands at the
			// bottom; suppress_css hides the theme's native footer above it.
			'hook'         => 'wp_footer',
			'hook_priority' => 5,
			'template'     => 'hfe-footer.php',
			'suppress_css' => implode( ',', [
				'.site-footer',
				'footer.site-footer',
				'#colophon',
				'#site-footer',
				'.footer-main',
				'.main-footer',
				'footer[role="contentinfo"]',
				'.footer-area',
				'.global-footer',
				// Block / FSE themes
				'footer.wp-block-template-part',
				// Elementor Pro location footer
				'.elementor-location-footer',
			] ),
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
