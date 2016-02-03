<?php

/**
 * Class: USC Localist for WordPress
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    USC_Localist_For_Wordpress
 * @subpackage USC_Localist_For_Wordpress/includes
 */

if ( ! class_exists('USC_Localist_For_Wordpress') ) {
	
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
		 * @var string
		 */
		protected $plugin_name;

		/**
		 * Current version of the plugin.  Set in plugin root @ usc-localist-for-wordpress.php
		 * @var string
		 */
		protected $plugin_version;

		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $plugin_tag;

		/**
		 * Shortcode identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $plugin_shortcode;

		/**
		 * Construct
		 * =========
		 *
		 * Pass a list of arguments to the class being called.
		 *
		 * @since    1.0.0
		 * @access 	 public
		 */
		public function __construct() {
			
			$this->plugin_name = 'USC Localist for Wordpress';
			$this->plugin_version = USC_LFWP__VERSION;
			$this->plugin_tag = 'usc-localist-for-wordpress';
			$this->plugin_shortcode = 'localist-calendar';

			// load dependencies for this class
			$this->load_dependencies();
			$this->define_admin_hooks();
			$this->define_public_hooks();
			
		}

		/**
		 * Load Dependencies
		 * =================
		 * 
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

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// require the json class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// add the shortcode function
			add_shortcode( $this->plugin_shortcode, array( &$this, 'events_shortcode' ) );

			$this->config = USC_Localist_For_Wordpress_Config::$config;
			$this->loader = new USC_Localist_For_Wordpress_Loader();

		}

		/**
		 * Run
		 * ===
		 *
		 * Functions to perform when running the plugin.
		 *
		 * @since 	1.0.0
		 */
		public function run() {

			// run the loader
			$this->loader->run();
			
		}

		/**
		 * Get Loader
		 * ==========
		 * 
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    USC_Localist_For_Wordpress_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Define Admin Hooks
		 * ==================
		 * 
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_admin_hooks() {

			$plugin_admin = new USC_Localist_For_Wordpress_Admin( $this->plugin_name, $this->plugin_version );

			$this->loader->add_action( 'init', $plugin_admin, 'activate' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_public_hooks() {

			$plugin_public = new USC_Localist_For_Wordpress_Public( $this->plugin_name, $this->plugin_version );

			// add url parameter support
			$this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_variables_filter' );

		}

		/**
		 * Get Query Variables
		 * ===================
		 *
		 * Get the custom query parameters and return as an array.
		 *
		 * @since 1.0.0
		 * 
		 * @return array 			associative array of keys and values
		 */
		public function get_custom_query_variables( $api_type = 'events' ) {

			// set a default value to capture url values
			$values = array();

			// set json api for function helpers
			$json_api = new USC_Localist_For_Wordpress_API;

			// get the allowed values for the api type
			$allowed_array_keys = $this->config['api_options'][$api_type]['allowed'];

			// get the default config file
			$parameters = $this->config['url']['parameters'];

			// loop through the available parameters from the config file
			foreach ( $parameters as $key ) {

				// check that the key is allowed per api type
				if ( array_key_exists( $key['relationship'], $allowed_array_keys ) ) {

					// get the value of the paramter
					$parameter_value = get_query_var( $key['name'], false );

					// check if we have a value
					if ( $parameter_value ) {

						// validate the value
						$parameter_value = $json_api->validate_key_value( $key['relationship'], $parameter_value );

						// add the value as an associative array item
						$values[$key['relationship']] = $parameter_value;

					}

				}

			}
			
			return $values;

		}

		
		/**
		 * Events Shortcode
		 * ================
		 * 
		 * Add the shortcode for Localist Widget
		 * 
		 * @since 1.0.0
		 * @access public
		 * 
		 * @require $this-config array
		 * @param 	string 	params 	shortcode api options
		 * @return 	html 			events list
		 * 
		 * usage: [localist-events {option=value}]
		 */
		public function events_shortcode( $params ) {

			// get the default config file
			$config = $this->config;

			// default setting for error checking
			$errors = $json_data = false;

			// default for json url build
			$json_url = array();

			$json_api = new USC_Localist_For_Wordpress_API;

			// get all api options
			$attr_all = shortcode_atts( $config['api_options']['all']['allowed'], $params, $this->plugin_shortcode );

			// store the api type as a variable
			$api_type = $attr_all['get'];

			// check that we have a valid 'get' type
			if ( '' == $api_type || null == $api_type ) {

				// let's default to events
				$api_type = 'events';

			}

			// set the api type
			$json_url['type'] = $api_type;

			// set transient cache expiration (in seconds)
			$api_cache = $attr_all['cache'];

			// check that we have a valid 'cache' value
			if ( '' != $api_cache ) {

				// validate the cache value
				$api_cache = $json_api->validate_key_value( 'cache', $api_cache );

				// store the cache number as part of the url array
				$json_url['cache'] = $api_cache;

			}

			// get url parameters and attach to the api query
				
				$url_parameters = $this->get_custom_query_variables( $api_type );

				// loop through the url parameters and attach to the $json_url associative array
				foreach ( $url_parameters as $key => $value ) {
					$json_url[$key] = $value;
				}
					
				
			// get allowed api attributes

				// get the available api options (based on type) from the shortcode
				$api_attr = shortcode_atts( $config['api_options'][$api_type]['allowed'], $params, 'localist-calendar' );

			// build the api url string for any options

				// get the matching api options by get type
				$parameters_string = $json_api->parameters_as_string( $api_attr, $api_type );
				
				// if we have any error messages
				if ( empty( $parameters_string ) ) {
					
					return __('Something went wrong.', $this->plugin_tag);

				}

				if ( ! empty( $parameters_string['errors'] ) ) {
					
					// there are errors
					$errors = true;
					return __( $parameters_string['errors'], $this->plugin_tag );

				} else {

					// no errors
					$json_url['options'] = $parameters_string['parameters'];

				}

			// get the json data if no errors are present
				
				if ( ! $errors ) {

					// perform the api call
					$json_data = $json_api->get_json( $json_url );

					// check if we have no errors in returned json data
					if ( ! isset( $json_data['errors'] ) || null == $json_data['errors'] ) {
						
						// check if we have data
						if ( $json_data['data'] ) {
							
							// we have json array data

							// TODO: function for looping through json data
							
							return 'API Data Successful: ' . $json_data['url'];  // replace this with loop


						} 

					} else {

						return $json_data['errors'];

					}

				}

		}

	}
}