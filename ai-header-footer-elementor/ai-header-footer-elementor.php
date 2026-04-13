<?php
/**
 * Plugin Name:       AI Header & Footer for Elementor by PressMeGPT
 * Plugin URI:        https://pressmegpt.com
 * Description:       Set any Elementor template as your site's global header or footer. Upload an Elementor JSON file or choose from your existing templates.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            PressMeGPT
 * Author URI:        https://pressmegpt.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-hfe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AHFE_VERSION', '1.0.0' );
define( 'AHFE_FILE', __FILE__ );
define( 'AHFE_DIR', plugin_dir_path( __FILE__ ) );
define( 'AHFE_URL', plugin_dir_url( __FILE__ ) );

require_once AHFE_DIR . 'includes/class-ahfe-content-types.php';
require_once AHFE_DIR . 'includes/class-ahfe-importer.php';
require_once AHFE_DIR . 'includes/class-ahfe-renderer.php';
require_once AHFE_DIR . 'includes/class-ahfe-admin.php';
require_once AHFE_DIR . 'includes/class-ahfe-core.php';

add_action( 'plugins_loaded', [ 'AHFE_Core', 'init' ] );
