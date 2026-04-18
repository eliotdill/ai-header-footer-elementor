<?php
/**
 * Footer replacement template.
 *
 * Loaded via the get_footer hook — replaces the theme's footer.php entirely.
 * This file closes the page wrapper opened in hfe-header.php, renders the footer
 * content, fires wp_footer(), and closes </body></html>.
 *
 * The theme's own footer.php is silently discarded by
 * AHFE_Renderer::override_theme_template() after this file outputs.
 *
 * @package ai-header-footer-elementor
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_id = AHFE_Renderer::get_template_id( 'footer' );
if ( $footer_id ) {
	AHFE_Renderer::render( $footer_id );
}
?>
</div><!-- #ahfe-page -->
<?php wp_footer(); ?>
</body>
</html>
