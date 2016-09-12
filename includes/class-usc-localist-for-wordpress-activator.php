<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author     USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Activator' ) ) {

	/**
	 * Class: USC Localist for WordPress Activator
	 *
	 * Fired during plugin activation
	 *
	 * @since 		1.0.0
	 */
	class USC_Localist_For_Wordpress_Activator {

		/**
		 * Activation Functions
		 * ====================
		 *
		 * Functions initiated when the plugin is activated.
		 * These should be one time instantiations instead of
		 * called each time a page/shortcode/function is called.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {

			// Flush the permalink structure.
			flush_rewrite_rules();

		}

	}

}
