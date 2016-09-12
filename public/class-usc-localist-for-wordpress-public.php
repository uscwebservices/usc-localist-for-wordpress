<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/public
 * @author     USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Public' ) ) {

	/**
	 * The public-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and initiates any public facing functions.
	 *
	 * @since 1.0.0
	 */
	class USC_Localist_For_Wordpress_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		public $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param    string $plugin_name 		The name of this plugin.
		 * @param    string $plugin_version		The version of this plugin.
		 */
		public function __construct( $plugin_name, $plugin_version ) {

			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;

			$this->load_dependencies();

		}

		/**
		 * Load Dependencies
		 *
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Require the events shortcode Class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-shortcode.php';

				// Require the api class.
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}


		/**
		 * Activate
		 *
		 * Activate any functions that should run during the admin setup.
		 *
		 * @since 	1.0.0
		 */
		public function activate() {

			// Register the custom post types.
			$this->custom_post_types();

		}

		/**
		 * Add Query Variables Filter
		 *
		 * Add the allowed URL query variables as an object from the allowed varibles stored in the config settings.
		 *
		 * @since 1.0.0
		 * @param object $vars Existing URL variables object.
		 */
		public function add_query_variables_filter( $vars ) {

			$parameters = $this->config['url']['parameters'];

			// Loop through the available parameters from the config and add them.
			foreach ( $parameters as $value ) {

				// Add the 'name' value to the allowed url parameter types.
				$vars[] = $value['name'];

			}

			// Return the $vars added.
			return $vars;

		}

	}

}
