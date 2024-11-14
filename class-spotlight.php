<?php
namespace WPSPOTLIGHT;

use WPSPOTLIGHT\Libs\Assets;
use WPSPOTLIGHT\Libs\Helper;
use WPSPOTLIGHT\Libs\Featured;
use WPSPOTLIGHT\Inc\Classes\Upgrade_Plugin;
use WPSPOTLIGHT\Inc\Classes\Feedback;

/**
 * Main Class
 *
 * @WP Spotlight
 * Jewel Theme <support@jeweltheme.com>
 * @version     1.0.0
 */

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JLTWP_Spotlight Class
 */
if ( ! class_exists( '\WPSPOTLIGHT\JLTWP_Spotlight' ) ) {

	/**
	 * Class: JLTWP_Spotlight
	 */
	final class JLTWP_Spotlight {

		const VERSION            = WPSPOTLIGHT_VER;
		private static $instance = null;

		/**
		 * what we collect construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			$this->includes();
			add_action( 'plugins_loaded', array( $this, 'jltwp_spotlight_plugins_loaded' ), 999 );
			// Body Class.
			add_filter( 'admin_body_class', array( $this, 'jltwp_spotlight_body_class' ) );
			// This should run earlier .
			// add_action( 'plugins_loaded', [ $this, 'jltwp_spotlight_maybe_run_upgrades' ], -100 ); .
		}

		/**
		 * plugins_loaded method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwp_spotlight_plugins_loaded() {
			$this->jltwp_spotlight_activate();
		}

		/**
		 * Version Key
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function plugin_version_key() {
			return Helper::jltwp_spotlight_slug_cleanup() . '_version';
		}

		/**
		 * Activation Hook
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function jltwp_spotlight_activate() {
			$current_jltwp_spotlight_version = get_option( self::plugin_version_key(), null );

			if ( get_option( 'jltwp_spotlight_activation_time' ) === false ) {
				update_option( 'jltwp_spotlight_activation_time', strtotime( 'now' ) );
			}

			if ( is_null( $current_jltwp_spotlight_version ) ) {
				update_option( self::plugin_version_key(), self::VERSION );
			}

			$allowed = get_option( Helper::jltwp_spotlight_slug_cleanup() . '_allow_tracking', 'no' );

			// if it wasn't allowed before, do nothing .
			if ( 'yes' !== $allowed ) {
				return;
			}
			// re-schedule and delete the last sent time so we could force send again .
			$hook_name = Helper::jltwp_spotlight_slug_cleanup() . '_tracker_send_event';
			if ( ! wp_next_scheduled( $hook_name ) ) {
				wp_schedule_event( time(), 'weekly', $hook_name );
			}
		}


		/**
		 * Add Body Class
		 *
		 * @param [type] $classes .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwp_spotlight_body_class( $classes ) {
			$classes .= ' spotlight ';
			return $classes;
		}

		/**
		 * Run Upgrader Class
		 *
		 * @return void
		 */
		public function jltwp_spotlight_maybe_run_upgrades() {
			if ( ! is_admin() && ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Run Upgrader .
			$upgrade = new Upgrade_Plugin();

			// Need to work on Upgrade Class .
			if ( $upgrade->if_updates_available() ) {
				$upgrade->run_updates();
			}
		}

		/**
		 * Include methods
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function includes() {
			\WPSPOTLIGHT\Inc\Classes\REST_API::instance();
			new Assets();
			new \WPSPOTLIGHT\Inc\Classes\Row_Links();
		}


		/**
		 * Initialization
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwp_spotlight_init() {
			$this->jltwp_spotlight_load_textdomain();
		}


		/**
		 * Text Domain
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwp_spotlight_load_textdomain() {
			$domain = 'spotlight';
			$locale = apply_filters( 'jltwp_spotlight_plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( WPSPOTLIGHT_BASE ) . '/languages/' );
		}


		/**
		 * Returns the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof JLTWP_Spotlight ) ) {
				self::$instance = new JLTWP_Spotlight();
				self::$instance->jltwp_spotlight_init();
			}

			return self::$instance;
		}
	}

	// Get Instant of JLTWP_Spotlight Class .
	JLTWP_Spotlight::get_instance();
}
