<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Settings' ) ) {

	/**
	 * The Settings Page of the plugin.
	 *
	 * Creates a settings page for the plugin for any stored options and instructions for usage.
	 *
	 * @package	Usc_Localist_For_Wordpress
	 * @subpackage Usc_Localist_For_Wordpress/includes
	 * @author	 USC Web Services <webhelp@usc.edu>
	 */
	class USC_Localist_For_Wordpress_Settings {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		protected $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_version    The current version of this plugin.
		 */
		protected $plugin_version;

		/**
		 * The tag of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_tag    The current version of this plugin.
		 */
		protected $plugin_tag;

		/**
		 * Holds the values to be used in the fields callbacks.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      array    $options    The options values used in the fields callback.
		 */
		private $options;

		/**
		 * Constructor to initialize the class and its properties.
		 *
		 * @since  1.0.0
		 * @param  string $plugin_name     The plugin name.
		 * @param  string $plugin_version  The plugin version.
		 * @param  string $plugin_tag      The plugin tag.
		 */
		public function __construct( $plugin_name, $plugin_version, $plugin_tag ) {

			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_tag = $plugin_tag;

			$this->load_dependencies();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		public function load_dependencies() {

			/**
			 * Add any Class dependencies on construct.
			 */

		}

		/**
		 * Add the plugin options page under 'Settings' menu.
		 *
		 * @since 	1.0.0
		 */
		public function add_plugin_options_page() {

			add_options_page(
				'Setting Admin',
				'USC Localist for WordPress',
				'manage_options',
				'usc-localist-for-wordpress-admin',
				array( $this, 'create_admin_page' )
			);

		}

		/**
		 * Create the Admin Page for the plugin options.
		 *
		 * @since   1.0.0
		 */
		public function create_admin_page() {

			// Set class property.
			$this->options = get_option( 'usc_lfwp_name' );

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-main.php';

		}

		/**
		 * Removes the Rich text editor based on post_type.
		 *
		 * @since 1.1.7
		 *
		 * @return bool  Output of whether post type is 'event-template'.
		 */
		public function remove_richedit_option() {

			global $post;

			$output = true;

			if ( 'event-template' === $post->post_type ) {

				$output = false;

			}

			return $output;

		}



	}

}
