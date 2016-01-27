<?php

/**
 * Class: USC Localist for WordPress
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
 */

if ( ! class_exists('USC_Localist_for_WordPress') ) {
	
	class USC_Localist_for_WordPress {

		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $plugin_tag = 'usc-localist-for-wordpress';

		/**
		 * Shortcode identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $plugin_shortcode = 'localist-calendar';

		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		protected $plugin_name = 'USC Localist for WordPress';

		/**
		 * Current version of the plugin.  Set in plugin root @ usc-localist-for-wordpress.php
		 * @var string
		 */
		protected $plugin_version = USC_LFWP__VERSION;

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
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// retrun the API configurations
			$this->config = USC_Localist_for_WordPress_Config::$config;

			// add the shortcode function
			add_shortcode( $this->plugin_shortcode, array( &$this, 'events_shortcode' ) );

			// add url parameter support
			add_filter( 'query_vars', array( $this, 'add_query_variables_filter') );

			// add custom post type registration support
			add_action( 'init', array( $this, 'events_template_post_type' ), 0 );
			
		}

		/**
		 * Register Events Template Post Type
		 * ==================================
		 *
		 * Registers the Post Type of 'Event Templates' for 
		 * custom template development with Localist events.
		 *
		 * @since 	1.0.0
		 */
		public function events_template_post_type() {

			$labels = array(
				'name'                => _x( 'Event Templates', 'Post Type General Name', '' ),
				'singular_name'       => _x( 'Event Template', 'Post Type Singular Name', '' ),
				'menu_name'           => __( 'Event Templates', '' ),
				'all_items'           => __( 'All Event Templates', '' ),
				'view_item'           => __( 'View Event Template', '' ),
				'add_new_item'        => __( 'Add New Event Template', '' ),
				'add_new'             => __( 'Add New', '' ),
				'edit_item'           => __( 'Edit Event Template', '' ),
				'update_item'         => __( 'Update Event Template', '' ),
				'search_items'        => __( 'Search Event Templates', '' ),
				'not_found'           => __( 'Not found', '' ),
				'not_found_in_trash'  => __( 'Not found in Trash', '' ),
			);
			$args = array(
				'label'               => 'event-template',
				'description'         => __( 'Template for displaying Localist events', '' ),
				'labels'              => $labels,
				'supports' 			  => array('title','editor','page-attributes','revisions'),
				'hierarchical'        => false,
				'public'              => true,
				'query_var' 		  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 40,
				'menu_icon'           => 'dashicons-calendar-alt',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'rewrite' => array(	'slug' 			=> 'event-template',	
									'with_front'	=> false,
									'hierarchical' 	=> false
								 )
			);

			register_post_type( 'event-template', $args );
		}

		/**
		 * Add Query Variables Filter
		 * ==========================
		 *
		 * Set custom query variables.  Safer methods for 
		 * using $_GET.
		 *
		 * @since 1.0.0
		 */
		public function add_query_variables_filter( $vars ){
			
			$parameters = $this->config['url']['parameters'];

			// loop throught the available parameters from the config and add them
			foreach ( $parameters as $value ) {
				
				// add the 'name' value to the allowed url parameter types
				$vars[] = $value['name'];

			}

			// return the $vars added
			return $vars;

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
		 * Get JSON
		 * ========
		 * 
		 * Get the JSON content
		 * 
		 * @since 1.0.0
		 * 
		 * @param 	array 	params 	the options for the function [url, type, options, page, timeout]
		 * @param 	string 	type 	the type of data to get [events]
		 * @param 	string 	options the options to attach to narrow results
		 * @param 	number 	timeout the timeout (in seconds) for waiting for the return
		 * @return 	json 	array 	the json data 	
		 */
		function get_json( $params ) {

			global $wp_version;

			// get the default config file
			$config = $this->config;
			
			// default variables
			$output = $error_message = array();

			// default parameters
			$api_base_url 		= isset ( $params['url'] ) ? $params['url'] : $config['url']['base'];
			$api_type 			= isset ( $params['type'] ) ? $params['type'] : '';
			$api_event_id		= isset ( $params['event_id'] ) ? $params['event_id'] : '';
			$api_cache 			= isset ( $params['cache'] ) ? $params['cache'] : HOUR_IN_SECONDS; // default cache to 1 hour
			$api_options 		= isset ( $params['options'] ) ? $params['options'] : '';
			$api_page_number	= isset ( $params['page'] ) ? $params['page'] : '';
			$timeout			= isset ( $params['timeout'] ) ? $params['timeout'] : 5;
			
			// set the default arguments
			$args = array(
			    'timeout'		=> $timeout,
			    'redirection'	=> 5,
			    'httpversion'	=> '1.0',
			    'user-agent'	=> 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
			    'blocking'		=> true,
			    'headers'		=> array(),
			    'cookies'		=> array(),
			    'body'			=> null,
			    'compress'		=> false,
			    'decompress'	=> true,
			    'sslverify'		=> true, // set to false if site is trusted
			    'stream'		=> false,
			    'filename'		=> null
		    );

			// set var for constructed api url
			$api_url = $api_base_url;

			// set api type customizations
			if ( $api_type == 'event' ) {
				
				// set the type to events for api structure
				$api_url .= 'events';

				if ( '' != $api_event_id ) {
					$api_url .= '/' . $api_event_id;
				}

			}

			// default api type
			else {
				$api_url .= $api_type;
			}

			// add query string initiator for api options or page number
			if ( '' != $api_options || '' != $api_page_number ) {

				$api_url .= '?';

			}

			// add api options
			if ( '' != $api_options ) {

				$api_url .= $api_options;

			}

			// add page number
			if ( '' != $api_page_number ) {

				// if we have api options, add ampersand joiner
				if ( '' != $api_options ) {
					$api_url .= '&';
				}

				$api_url .= 'page=' . $api_page_number;

			}

			// REMOVE: local testing only
			// $api_url = plugins_url( '/sample/events.json', dirname(__FILE__) );
			
			
			// First let's check if we have a transient for this API call
			
			// transient name using constructed api url
			$transient_name = 'localist_' . urlencode($api_url);

			// get the transient by name
			$transient = get_transient( $transient_name );

			if ( ! empty( $transient ) ) {

				// We have a transient, no need to make an API call
				$output['data'] = $transient;
				
			} else {

				// We do not have a transient stored - let's get the API

				// get the remote data
				$response = wp_safe_remote_get( $api_url, $args );

				// check if there is a wordpress error
				if ( is_wp_error( $response ) ) {

					// return WP error messages
					$error_message[] = __('WP Error: ', 'textdomain') . $response->get_error_message();

				}

				// check if there is an HTTP error 400 and above
				else if ( $response['response']['code'] >= 400 ) {

					// return the error response code and message
					$error_message[] = __('Calendar API Error. The shortcode parameters used have returned: ', 'textdomain') . $response['response']['code'] . ' - ' . $response['response']['message'];
					
					// if we have a response from localist, let's provide if for better troubleshooting
					if ( '' != $response['body'] ) {
						
						// convert response message to json data as an array
						$localist_error = json_decode( $response['body'], true );

						// return localist error response
						if ( isset( $localist_error['error'] ) ) {
							
							$error_message[] = __('Localist Error: ' . $localist_error['error'] );

						} else {

							$error_message[] = __('Localist Error: ' . $response['body'] );

						}

					}

					// add a link to the API URL called to help troubleshoot any issues
					$error_message[] = '<a target="_blank" href="' . $api_url . '">' . __('API URL') . '</a>';

				} 

				// let's assume no wp errors and no 400+ errors so we must have data
				else {
					
					// but just in case, let's make sure we have actual data
					if ( '' != $response['body'] ) {

						// encode the json data and set to TRUE for array
						$output['data'] = json_decode( $response['body'], TRUE );

						// let's store the data as a transient using the cache attribute
						if ( '' != $api_cache ) {

							// let's set a transient for the API call
							set_transient( $transient_name, $output['data'], $api_cache );
							
						}
						
					}

					// we still don't have valid data so let's let the user know
					
					else {

						$error_message[] = __('Hmm... The API Call was successful but no data was returned.  Here is the API call for verification: <a href="' . $api_url . '">' . $api_url . '</a>');

					}

				}

				// combine any errors and set a message value
				$output['errors'] = join( '<br>', $error_message );

			}

			// return the output data
			return $output;

		}



		/**
		 * Validate Date
		 * =============
		 * 
		 * Check if the date passed matches the intended format.
		 * 
		 * @param 	string 	$date 		date to pass for checking
		 * @param 	string 	$format 	format to check against date
		 * @return 	boolean	
		 */
		public function validate_date( $date, $format = 'Y-m-d' ) {
		    
		    // returns new DateTime object formatted according to the specified format
		    $d = DateTime::createFromFormat( $format, $date );

		    // return boolean
		    return $d && $d->format( $format ) == $date;
		}


		/**
		 * Fix Date
		 * ========
		 * 
		 * Change a valid date format to a specified format.
		 * 
		 * @param 	string 	$date 		valid date
		 * @param 	string 	$format 	format which to change the date
		 * @return 	string 				date in specified $format
		 */
		public function fix_date( $date, $format = 'Y-m-d' ) {
			
			// change the $date to $format and return
			$d = date( $format, strtotime( $date ) );
			return $d;

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

			$error_message = array();

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

					// check if we have a valide date
					if ( $this->validate_date( $value ) ) {
						
						// good date format, so return it
						return $value;
					
					} else {
						
						// fix the date format
						return $this->fix_date( $value );
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

								// check that we have an integer and not something else
								if ( is_numeric( $number ) ) {

									// convert any non-whole integer values
									$number_string[] = intval( $number );

								} else {

									return false;
								}


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

			// set the default output, message and string constructor
			$output = $string = $error_message = array();

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
						if ( ! $value ) {

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
									$error_message[] = 'Multiple values not allowed for "'. $key . '" with get "' . $api_type . '".';

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
				$output['errors'] = join( '<br>', $error_message );

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
					return __( $parameters_string['errors'], $plugin_tag );

				} else {

					// no errors
					$json_url['options'] = $parameters_string['parameters'];

				}

			// get the json data if no errors are present
				
				if ( ! $errors ) {

					// perform the api call
					$json_data = $this->get_json( $json_url );

					// check if we have no errors in returned json data
					if ( ! isset( $json_data['errors'] ) || null == $json_data['errors'] ) {
						
						// check if we have data
						if ( $json_data['data'] ) {
							
							// we have json array data

							// TODO: function for looping through json data
							
							return 'API Data Successful: JSON Data';  // replace this with loop


						} 

					} else {

						return $json_data['errors'];

					}

				}

		}

	}
}