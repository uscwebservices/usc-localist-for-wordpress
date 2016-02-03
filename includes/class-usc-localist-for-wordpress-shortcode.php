<?php

/**
 * Class: USC Localist for WordPress Shortcode
 * 
 * Add shortcode(s) for the site to get calendar data from Localist API.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Shortcode' ) ) {
	
	class USC_Localist_For_Wordpress_Shortcode {

		/**
		 * Configuration variable
		 * @var string
		 */
		private $config;

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
		 * Tag identifier used by shortcode generator for the calendar.
		 * @var string
		 */
		protected $plugin_shortcode_calendar;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $plugin_name, $plugin_version, $plugin_tag ) {

			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_tag= $plugin_tag;

			// load dependencies for this class
			$this->load_dependencies();

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

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// require the api class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// retrun the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

			$this->plugin_shortcode_calendar = $this->config['plugin']['shortcode']['calendar'];

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
			$attr_all = shortcode_atts( $config['api_options']['all']['allowed'], $params, $this->plugin_shortcode_calendar );

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
				
				$url_parameters = $json_api->get_custom_query_variables( $api_type );

				// loop through the url parameters and attach to the $json_url associative array
				foreach ( $url_parameters as $key => $value ) {
					$json_url[$key] = $value;
				}
					
				
			// get allowed api attributes

				// get the available api options (based on type) from the shortcode
				$api_attr = shortcode_atts( $config['api_options'][$api_type]['allowed'], $params, $this->plugin_shortcode_calendar );

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