<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dynamic registry for all content types.
 *
 * To add a new content type (Sidebar, Hero, Copyright, etc.),
 * call AHFE_Content_Types::register() with the type definition.
 * The admin UI and renderer will pick it up automatically.
 */
class AHFE_Content_Types {

	/** @var array */
	private static $types = [];

	/**
	 * Register a content type.
	 *
	 * @param array $args {
	 *   @type string $id          Unique identifier, e.g. 'header'.
	 *   @type string $label       Human-readable label, e.g. 'Header'.
	 *   @type string $description Short description shown in the admin UI.
	 *   @type string $option_key  wp_options key that stores the template post ID.
	 *   @type string $hook        WordPress action hook to intercept, e.g. 'get_header'.
	 *   @type string $template    Filename inside /templates/, e.g. 'hfe-header.php'.
	 * }
	 */
	public static function register( array $args ): void {
		$required = [ 'id', 'label', 'description', 'option_key', 'hook', 'template' ];
		foreach ( $required as $key ) {
			if ( empty( $args[ $key ] ) ) {
				_doing_it_wrong( __METHOD__, esc_html( "Missing required key: {$key}" ), '1.0.0' );
				return;
			}
		}
		self::$types[ $args['id'] ] = $args;
	}

	/**
	 * Return all registered content types.
	 *
	 * @return array
	 */
	public static function get_all(): array {
		return self::$types;
	}

	/**
	 * Return a single content type by ID.
	 *
	 * @param string $id
	 * @return array|null
	 */
	public static function get( string $id ): ?array {
		return self::$types[ $id ] ?? null;
	}
}
