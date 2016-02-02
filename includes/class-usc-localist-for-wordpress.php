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


			// require the error messaging class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-errors.php';

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// require the json class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// require the date class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-dates.php';

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
						$parameter_value = $this->validate_key( $key['relationship'], $parameter_value );

						// add the value as an associative array item
						$values[$key['relationship']] = $parameter_value;

					}

				}

			}
			
			return $values;

		}

		/**
		 * Validate Value
		 * ==============
		 * 
		 * Validate keys and associative values against specified dates and 
		 * numbers keys from $config settings.  The $key is matched
		 * against supported keys in api_options.all.validation.dates and 
		 * api_options.all.validation.numbers - if they key matches, it will
		 * check the value to be a date or integer and return a validated value.
		 * If the value does not match one of the associated keys, it just 
		 * returns the original value.
		 * 
		 * @param 	string 	$key 		key to check against date/number options
		 * @param 	string 	$value 		value to check if date or number
		 * @return 	string 		 		returns validated value if date/number or
		 * 								original value
		 */
		public function validate_key( $key, $value ) {

			// get the default config file
			$config = $this->config;

			$date_array = $config['api_options']['all']['validation']['dates'];
			$number_array = $config['api_options']['all']['validation']['numbers'];
			$boolean_array = $config['api_options']['all']['validation']['boolean'];

			// check that we don't have an empty value
			if ( !empty( $value ) ) {

				// check if the value of the key supposed to be a boolean
				if ( in_array( $key, $boolean_array ) ) {

					// check that we have a boolean
					if ( is_bool( $value ) ) {

						// we have a boolean value - let's return it
						return $value;

					} else {

						// we don't have one - let's return 'false' by default
						return false;

					}

				}

				// check if the value of the key is supposed to be in a date format
				else if ( in_array( $key, $date_array ) ) {

					// set a new date object for this $key
					$date = new USC_Localist_For_Wordpress_Dates;

					// check if we have a valide date (bool)
					if ( $date->valid_date( $value ) ) {
						
						// good date format, so return it
						return $value;
					
					} else {
						
						// fix the date format
						return $date->fix_date( $value );
					}

				} 

				// check if the key is supposed to be in a number format
				else if ( in_array( $key, $number_array ) ) {

					// if we have a number
					if ( is_numeric( $value ) ) {

						// convert any non-whole integer values
						return intval( $value );
					
					} 

					// else do we have a string
					else if ( is_string( $value ) ) {

						// set default to re-attach valid numbers
						$number_string = array();

						// if we have a string of numbers (array), check each one
						$value_string = explode( ',', $value );

						// if we have more than one in the exploded array
						if ( count( $value_string ) > 1 ) {

							// loop through the values
							foreach ( $value_string as $number ) {

								// convert any non-whole integer or string values
								$number_string[] = intval( $number );

							}

							// combine $number_string array back to a string format
							return join( ',', $number_string );

						} else {

							return false;

						}

					} 

					// we dont have a valide number, so let's not return bad options
					else {
						
						return false;
					}

				}

				// if the value doesn't need valiation, just return the value
				else {
					
					return $value;
				
				}

			}
		}

		/**
		 * Parameters as String
		 * ====================
		 * 
		 * Convert Paramaters to URL string for passing to Localist API.
		 * 
		 * @since 1.0.0
		 * 
		 * @param 	string 	api_type 	The type of API call to get 
		 * 								[organizations, communities, events, places, departments, photos] 
		 * 								[default: events]
		 * @param 	array 	params 		The array of parameters to return
		 * @return 	array 				
		 */
		public function parameters_as_string( $params, $api_type = 'all' ) {

			//get the default config file
			$config = $this->config;
			
			// get the allowed array values for the api type
			$allowed_array = $config['api_options'][$api_type]['allowed_array'];

			// set the default output and string constructor
			$output = $string = array();

			// set error message object
			$error_messages = new USC_Localist_For_Wordpress_Errors;

			// if we do not have an array, end the process
			if ( ! is_array ( $params ) ) {
				
				return false;

			} else {
				
				// loop through the parameters
				foreach ( $params as $key => $value ) {

					// check that we have a valid value that isn't null, blank, or empty array
					if ( null !== $value && '' !== $value &! empty( $value ) ) {

						// get valid value for the key value
						$value = $this->validate_key( $key, $value );

						// check that we don't have a boolean
						if ( is_bool( $value ) ) {

							// add single key boolean values as 'key=bool_value'
							$string[] .= urlencode( $key ) . '=' . var_export($value, true);

						} else {

							// convert any comma delimited $value to an array
							$value = explode( ',', $value );

							// if the $value is an array
							if ( count( $value ) > 1 ) {
							
								// check that the $value is allowed as an array
								if ( ! in_array( $key, $allowed_array ) ) {
									
									// let the user know they are attempting an array where one is not allowed
									$error_messages->add_message('Multiple values not allowed for "'. $key . '" with get "' . $api_type . '".');

								} else {

									// loop through sub values
									foreach ( $value as $sub_value ) {
										
										// add multiple values as 'key[]=sub_value'
										$string[] .= urlencode( $key ) . '[]=' . urlencode( $sub_value );

									}
								}

							} else {

								// add single key values as 'key=value'
								$string[] .= urlencode( $key ) . '=' . urlencode( $value[0] );

							}

						}

					}

				}

				// combine any errors and set a message value
				$output['errors'] = join( '<br>', $error_messages->get_messages() );

				// combine any strings and set a url string value
				$output['parameters'] = join( '&', $string );
				

				// return the output
				return $output;

			}

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
				$api_cache = $this->validate_key( 'cache', $api_cache );

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
				$parameters_string = $this->parameters_as_string( $api_attr, $api_type );
				
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