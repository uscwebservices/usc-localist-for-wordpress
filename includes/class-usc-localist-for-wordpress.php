<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author     USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress' ) ) {

	/**
	 * Class: USC Localist for WordPress
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @since 		1.0.0
	 * @package 	Usc_Localist_For_Wordpress
	 * @subpackage 	Usc_Localist_For_Wordpress/includes
	 * @author 		USC Web Services <webhelp@usc.edu>
	 */
	class USC_Localist_For_Wordpress {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @var      Usc_Localist_For_Wordpress_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * User friendly name used to identify the plugin.
		 *
		 * @var string
		 */
		protected $plugin_name;

		/**
		 * Current version of the plugin.  Set in plugin root @ usc-localist-for-wordpress.php
		 *
		 * @var string
		 */
		protected $plugin_version;

		/**
		 * Tag identifier used by file includes and selector attributes.
		 *
		 * @var string
		 */
		protected $plugin_tag;

		/**
		 * Tag identifier used by shortcode generator for the calendar.
		 *
		 * @var string
		 */
		protected $plugin_shortcode_cal;

		/**
		 * Tag identifier used by the settings page.
		 *
		 * @var string
		 */
		protected $plugin_settings;

		/**
		 * Construct: Pass a list of arguments to the class being called.
		 *
		 * @since    1.0.0
		 * @access 	 public
		 */
		public function __construct() {

			// Load dependencies for this class.
			$this->load_dependencies();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-loader.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-usc-localist-for-wordpress-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-usc-localist-for-wordpress-public.php';

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Require the json class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// Require the shortcode class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-shortcode.php';

			/**
			 * The class responsible for the settings page of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-settings.php';

			$this->config = USC_Localist_For_Wordpress_Config::$config;
			$this->loader = new USC_Localist_For_Wordpress_Loader();

			$this->plugin_name = $this->config['plugin']['name'];
			$this->plugin_version = $this->config['plugin']['version'];
			$this->plugin_tag = $this->config['plugin']['tag'];
			$this->plugin_shortcode_calendar = $this->config['plugin']['shortcode']['calendar'];

		}

		/**
		 * Functions to perform when running the plugin.
		 *
		 * @since 	1.0.0
		 */
		public function run() {

			// Run the loading functions for actions and filters.
			$this->loader->run();

		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    USC_Localist_For_Wordpress_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_admin_hooks() {

			$plugin_admin = new USC_Localist_For_Wordpress_Admin( $this->plugin_name, $this->plugin_version, $this->plugin_tag );

			// Run admin activations on init.
			$this->loader->add_action( 'init', $plugin_admin, 'activate_init' );

			// Add customizer registration.
			$this->loader->add_action( 'customize_register', $plugin_admin, 'activate_customize_register' );

			if ( is_admin() ) {

				$plugin_settings = new USC_Localist_For_Wordpress_Settings( $this->plugin_name, $this->plugin_version, $this->plugin_tag );

				// Add admin menu for settings.
				$this->loader->add_action( 'admin_menu', $plugin_settings, 'add_plugin_options_page' );

				// Add action to remove visual editor from event templates.
				$this->loader->add_action( 'user_can_richedit', $plugin_settings, 'remove_richedit_option' );

			}

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_public_hooks() {

			// Get the public.
			$plugin_public = new USC_Localist_For_Wordpress_Public( $this->plugin_name, $this->plugin_version );

			// Get the shortcode class.
			$plugin_shortcode = new USC_Localist_For_Wordpress_Shortcode( $this->plugin_name, $this->plugin_version, $this->plugin_tag );

			// Add url parameter support.
			$this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_variables_filter' );

			// Add shortcodes.
			$this->loader->add_shortcode( $this->plugin_shortcode_calendar, $plugin_shortcode, 'events_shortcode' );

			// Add widget.
			$this->loader->add_action( 'widgets_init', $plugin_public, 'add_widget' );

		}


	}
} // End if().
