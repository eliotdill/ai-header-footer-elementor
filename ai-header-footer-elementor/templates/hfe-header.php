<?php
/**
 * Replacement header template.
 * Loaded in place of the theme's header.php when a header template is set.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$header_id = AHFE_Renderer::get_template_id( 'header' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( $header_id ) : ?>
	<div class="ahfe-header-wrap">
		<?php AHFE_Renderer::render( $header_id ); ?>
	</div>
<?php endif; ?>
