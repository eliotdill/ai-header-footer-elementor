<?php
/**
 * Header replacement template.
 *
 * Loaded via the get_header hook — replaces the theme's header.php entirely.
 * This file owns the full HTML document opening: DOCTYPE, <html>, <head>, wp_head(),
 * <body>, and the page wrapper. The theme's own header.php is silently discarded
 * by AHFE_Renderer::override_theme_template() after this file outputs.
 *
 * NOTE: This file must NOT have a closing </div></body></html>.
 * Those are output by hfe-footer.php when the footer hook fires.
 *
 * @package ai-header-footer-elementor
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div id="ahfe-page" class="ahfe-site">
<?php
$header_id = AHFE_Renderer::get_template_id( 'header' );
if ( $header_id ) {
	AHFE_Renderer::render( $header_id );
}
?>
