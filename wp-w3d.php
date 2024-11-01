<?php
/**
 * W3D Wordpress Plugin
 * Based upon The WordPress Plugin Boilerplate from Tom Mc Farlin.
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 *
 * @wordpress-plugin
 * Plugin Name:       WP W3D
 * Plugin URI:        http://wordpress.mba-multimedia.com/plugins/wp-w3d/
 * Description:       WP W3D aims to help WP users or developers to add easily several UI elements to their website, including 3D components and complex animated layouts.
 * Version:           0.1
 * Author:            MBA Multimedia
 * Author URI:        http://wordpress.mba-multimedia.com/
 * Text Domain:       wp-w3d-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('W3D_ROOT_FILE', __FILE__);
define('W3D_ROOT_PATH', dirname(__FILE__));
define('W3D_ROOT_URL', plugins_url('', __FILE__));

define('CURRENT_THEME_URL', get_stylesheet_directory() );

define('PLUGIN_PREMIUM_URL', "http://wordpress.mba-multimedia.com/en/our-plugins/wp-w3d-plugin/");
define('PLUGIN_PREMIUM_LINK', "<a href=\"".PLUGIN_PREMIUM_URL."\" target=\"_blank\">W3D Premium plugin</a>");

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( W3D_ROOT_PATH . '/public/class-wp-w3d.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WP_W3D', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_W3D', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WP_W3D', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
//if ( is_admin() ) {

	require_once( W3D_ROOT_PATH . '/admin/class-wp-w3d-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_W3D_Admin', 'get_instance' ) );

}
