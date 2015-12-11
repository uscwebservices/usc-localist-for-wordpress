<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bitbucket.org/uscwebservices/usc-localist-for-wordpress
 * @since      1.0.0
 *
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
 * @author     Your Name <email@example.com>
 */
class USC_Localist_for_WordPress {


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access 	 public
	 */
	public function __construct() {
		
		// requrire the config class for API variables
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.usc-lfwp-config.php';

		// retrun the API configurations
		$this->config = USC_Localist_for_WordPress_Config::$config;
		
	}

	/**
	 * Run the code
	 * @return [type]
	 */
	public function run() {
		
		echo 'hi';

		var_dump($this->config);
		
	}

}
