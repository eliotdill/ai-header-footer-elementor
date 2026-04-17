<?php
/**
 * Header content template.
 *
 * Rendered via the wp_body_open action — after <body> opens but before the
 * theme's visual header element. The theme still owns <html>, <head>, and
 * wp_head() (scripts/styles). We only inject the visible header content here.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$header_id = AHFE_Renderer::get_template_id( 'header' );
if ( $header_id ) {
	AHFE_Renderer::render( $header_id );
}
