<?php
/**
 * Footer content template.
 *
 * Rendered via the wp_footer action — before </body>. The theme still owns
 * wp_footer() and the closing </body></html>. We only inject visible footer
 * content here; suppress_css hides the theme's native footer element.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_id = AHFE_Renderer::get_template_id( 'footer' );
if ( $footer_id ) {
	AHFE_Renderer::render( $footer_id );
}
