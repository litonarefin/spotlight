<?php
namespace WPSPOTLIGHT\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Assets Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 * @version     1.0.0
	 */
	class Assets {

		/**
		 * Constructor method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'jltwp_spotlight_enqueue_scripts' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'jltwp_spotlight_enqueue_scripts' ), 100 );
		}


		/**
		 * Get environment mode
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function get_mode() {
			return defined( 'WP_DEBUG' ) && WP_DEBUG ? 'development' : 'production';
		}

		/**
		 * Enqueue Scripts
		 *
		 * @method wp_enqueue_scripts()
		 */
		public function jltwp_spotlight_enqueue_scripts() {

			// CSS Files .
			wp_enqueue_style('spotlight', WPSPOTLIGHT_ASSETS . 'css/spotlight.css', array('dashicons'), WPSPOTLIGHT_VER, 'all');

			wp_enqueue_style('spotlight-form', WPSPOTLIGHT_ASSETS . 'css/spotlight-form.css', array('dashicons'), WPSPOTLIGHT_VER, 'all');

			// JS Files .
			wp_enqueue_script('spotlight', WPSPOTLIGHT_ASSETS . 'js/spotlight.js', array('jquery',), WPSPOTLIGHT_VER, true);
			wp_localize_script(
				'spotlight',
				'WPSPOTLIGHT_CORE',
				array(
					'is_frontend'       => (is_admin()) ? true : false,
					'admin_ajax'        => admin_url('admin-ajax.php'),
					'recommended_nonce' => wp_create_nonce('jltwp_spotlight_recommended_nonce'),
					'admin_url'			=> admin_url(),
					'logout_url'		=> wp_logout_url(),
				)
			);

		}

	}
}
