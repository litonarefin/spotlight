<?php
namespace WPSPOTLIGHT\Inc\Classes;

use WPSPOTLIGHT\Libs\RowLinks;

if ( ! class_exists( 'Row_Links' ) ) {
	/**
	 * Row Links Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Row_Links extends RowLinks {

		public $is_active;
		public $is_free;

		/**
		 * Construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			parent::__construct();

			$this->is_active = false;
			$this->is_free   = true;
		}


		/**
		 * Plugin action links
		 *
		 * @param [type] $links .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function plugin_action_links( $links ) {

			$docs_url = 'https://jeweltheme.com/docs/spotlight';
			$links['settings'] = sprintf(
				'<a class="spotlight-settings" id="spotlight-settings" href="#">%1$s</a>',
				__('Settings', 'spotlight')
			);

			$links['docs'] = sprintf(
				'<a class="spotlight-docs" href="%1$s" target="_blank">%2$s</a>',
				esc_url($docs_url),
				__('Docs', 'spotlight')
			);
			return $links;
		}

	}
}
