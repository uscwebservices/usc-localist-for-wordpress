<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Deactivator' ) ) {

	/**
	 * Class: USC Localist for WordPress Deactivator
	 *
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's deactivation.
	 *
	 * @since 		1.0.0
	 */
	class USC_Localist_For_Wordpress_Deactivator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since	1.0.0
		 */
		public static function deactivate() {

			// Our post type will be automatically removed, so no need to unregister it.
			// Clear the permalinks to remove our post type's rules.
			flush_rewrite_rules();

		}

	}

}
