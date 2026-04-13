<?php
/**
 * Replacement footer template.
 * Loaded in place of the theme's footer.php when a footer template is set.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_id = AHFE_Renderer::get_template_id( 'footer' );
?>

<?php if ( $footer_id ) : ?>
	<div class="ahfe-footer-wrap">
		<?php AHFE_Renderer::render( $footer_id ); ?>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
