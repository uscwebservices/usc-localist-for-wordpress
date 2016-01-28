<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
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
		
		// TODO: add custom post type addition here

		flush_rewrite_rules();
		
		
		// TODO: add any rewrite flushes
		
	}

}
