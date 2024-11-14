<?php
/**
 * Plugin Name: Spotlight - Admin Search Command Tool
 * Plugin URI:  https://wpspotlight.pro
 * Description: It's a very powerful toold for doing daily tasks without leaving on your screen. Extremely time saver and productivity tool WordPress Power User.
 * Version:     1.0.1
 * Author:      Jewel Theme
 * Author URI:  https://jeweltheme.com
 * Text Domain: spotlight
 * Domain Path: languages/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package spotlight
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$jltwp_spotlight_plugin_data = get_file_data(
	__FILE__,
	array(
		'Version'     => 'Version',
		'Plugin Name' => 'Plugin Name',
		'Author'      => 'Author',
		'Description' => 'Description',
		'Plugin URI'  => 'Plugin URI',
	),
	false
);

// Define Constants.
if ( ! defined( 'WPSPOTLIGHT' ) ) {
	define( 'WPSPOTLIGHT', $jltwp_spotlight_plugin_data['Plugin Name'] );
}

if ( ! defined( 'WPSPOTLIGHT_VER' ) ) {
	define( 'WPSPOTLIGHT_VER', $jltwp_spotlight_plugin_data['Version'] );
}

if ( ! defined( 'WPSPOTLIGHT_AUTHOR' ) ) {
	define( 'WPSPOTLIGHT_AUTHOR', $jltwp_spotlight_plugin_data['Author'] );
}

if ( ! defined( 'WPSPOTLIGHT_DESC' ) ) {
	define( 'WPSPOTLIGHT_DESC', $jltwp_spotlight_plugin_data['Author'] );
}

if ( ! defined( 'WPSPOTLIGHT_URI' ) ) {
	define( 'WPSPOTLIGHT_URI', $jltwp_spotlight_plugin_data['Plugin URI'] );
}

if ( ! defined( 'WPSPOTLIGHT_DIR' ) ) {
	define( 'WPSPOTLIGHT_DIR', __DIR__ );
}

if ( ! defined( 'WPSPOTLIGHT_FILE' ) ) {
	define( 'WPSPOTLIGHT_FILE', __FILE__ );
}

if ( ! defined( 'WPSPOTLIGHT_SLUG' ) ) {
	define( 'WPSPOTLIGHT_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'WPSPOTLIGHT_BASE' ) ) {
	define( 'WPSPOTLIGHT_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WPSPOTLIGHT_PATH' ) ) {
	define( 'WPSPOTLIGHT_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'WPSPOTLIGHT_URL' ) ) {
	define( 'WPSPOTLIGHT_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
}

if ( ! defined( 'WPSPOTLIGHT_INC' ) ) {
	define( 'WPSPOTLIGHT_INC', WPSPOTLIGHT_PATH . '/Inc/' );
}

if ( ! defined( 'WPSPOTLIGHT_LIBS' ) ) {
	define( 'WPSPOTLIGHT_LIBS', WPSPOTLIGHT_PATH . 'Libs' );
}

if ( ! defined( 'WPSPOTLIGHT_ASSETS' ) ) {
	define( 'WPSPOTLIGHT_ASSETS', WPSPOTLIGHT_URL . 'assets/' );
}

if ( ! defined( 'WPSPOTLIGHT_IMAGES' ) ) {
	define( 'WPSPOTLIGHT_IMAGES', WPSPOTLIGHT_ASSETS . 'images/' );
}

if ( ! class_exists( '\\WPSPOTLIGHT\\JLTWP_Spotlight' ) ) {
	// Autoload Files.
	include_once WPSPOTLIGHT_DIR . '/vendor/autoload.php';
	// Instantiate JLTWP_Spotlight Class.
	include_once WPSPOTLIGHT_DIR . '/class-spotlight.php';
}
