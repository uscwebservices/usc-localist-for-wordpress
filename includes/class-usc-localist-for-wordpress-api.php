<?php

/**
 * Class: USC Localist for WordPress API
 *
 * Get JSON from API
 *
 * since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_API' ) ) {
	
	class USC_Localist_For_Wordpress_API {

		/**
		 * Configuration variable
		 * @var string
		 */
		private $cofig;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct() {

			// get the version of wordpress
			global $wp_version;

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// require the date class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-dates.php';

			// require the error messaging class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-errors.php';

			// retrun the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Get API
		 * =======
		 * 
		 * Compile options from passed parameters and get the JSON object from the Localist API.  
		 * Options need to be sanitized prior to being passed.  
		 * Use the functions available in:
		 * 	- USC_Localist_For_Wordpress_Validation
		 * 	- USC_Localist_For_Wordpress_Date
		 * 
		 * @since 1.0.0
		 * 
		 * @param 	array 	params 	the options for the function [url, type, options, page, timeout]
		 * @param 	string 	type 	the type of data to get [events]
		 * @param 	string 	options the options to attach to narrow results
		 * @param 	number 	timeout the timeout (in seconds) for waiting for the return
		 * @return 	array 		 	[data],[api_type],[api_options],[event_id],[page_current],[url]
		 */
		function get_api( $params ) {

			global $wp_version;

			// get the default config file
			$config = $this->config;
			
			// default variables
			$output = array();

			// set error message object
			$error_messages = new USC_Localist_For_Wordpress_Errors;

			// default parameters
			$api_base_url 			= isset ( $params['url'] ) ? $params['url'] : $config['url']['base'];
			$api_type 				= isset ( $params['api']['type'] ) ? $params['api']['type'] : '';
			$api_events_page		= isset ( $params['is_events_page'] ) ? $params['is_events_page'] : false;
			$api_event_id			= isset ( $params['event_id'] ) ? $params['event_id'] : '';
			$api_cache 				= isset ( $params['cache'] ) ? $params['cache'] : $config['default']['cache'];
			$api_options 			= isset ( $params['options'] ) ? $params['options'] : '';
			$api_page_number		= isset ( $params['page'] ) ? $params['page'] : '';
			$api_timeout			= isset ( $params['timeout'] ) ? $params['timeout'] : $config['default']['api_timeout'];

			
			// set the default arguments
			$args = array(
			    'timeout'		=> $api_timeout,
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

			// check if we have a single event or if it is an events page with event id
			if ( $api_type == 'event' || ( $api_events_page && '' != $api_event_id ) ) {
				
				// set the type to events for api structure
				$api_url .= 'events';

				if ( '' != $api_event_id ) {
					
					// add the event id but convert any integers to strings
					$api_url .= '/' . strval( $api_event_id );

					// we are setting the api url by inclusion of the api_event_id so we assume we have a sigle event - let's manually set the api type to single event for output
					$api_type = 'event';

				}

			}

			// api type and add options and page number
			else {
				
				$api_url .= $api_type;
			

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

					// add the page number parameter
					$api_url .= 'page=' . $api_page_number;

				}

			}

			/**
			 * REMOVE: Local Testing
			 *
			 * Use the sample json data in the plugin.
			 */
			if ( $config['testing'] ) {
				
				if ( $api_type == 'event' ) {
					$api_url = plugins_url( '/sample/event.json', dirname(__FILE__) );
				}

				else {
					$api_url = plugins_url( '/sample/events.json', dirname(__FILE__) );
				}
				
			}

			/**
			 * Data Options Output
			 *
			 * Add output data options for future calls (pagination)
			 */
			
			// add the api type to the output
			$output['api']['type'] = $api_type;

			// add the api options to the output
			$output['api_options'] = $api_options;

			// add the event id to the output
			$output['event_id'] = $api_event_id;

			// add the current page number to the output
			$output['page_current'] = $api_page_number;

			// add the api url used to the output
			$output['url'] = $api_url;
			
			
			/**
			 * Transient Check
			 */
			
			// transient name using constructed api url
			$transient_name = 'localist_' . urlencode($api_url);

			// get the transient by name
			$transient = get_transient( $transient_name );

			// First let's check if we have a transient for this API call
			if ( ! empty( $transient ) ) {

				// We have a transient, no need to make an API call
				$output['api']['data'] = $transient;
				
			} else {

				// We do not have a transient stored - let's get the API

				// get the remote data
				$response = wp_safe_remote_get( $api_url, $args );

				// check if there is a wordpress error
				if ( is_wp_error( $response ) ) {

					// return WP error messages
					$error_messages->add_message( 'WP Error: ' . $response->get_error_message() );

				}

				// check if there is an HTTP error 400 and above
				else if ( $response['response']['code'] >= 400 ) {

					// return the error response code and message
					$error_messages->add_message( 'Calendar API Error. The shortcode parameters used have returned: ' . $response['response']['code'] . ' - ' . $response['response']['message'] );
					
					// if we have a response from localist, let's provide if for better troubleshooting
					if ( '' != $response['body'] ) {
						
						// convert response message to json data as an array
						$localist_response = json_decode( $response['body'], true );

						// return localist error response
						if ( isset( $localist_response['error'] ) ) {
							
							$error_messages->add_message( 'Localist Error: ' . $localist_response['error'] );

						} else {

							$error_messages->add_message( 'Localist Error: ' . $response['body'] );

						}

					}

					// add a link to the API URL called to help troubleshoot any issues
					$error_messages->add_message( '<a target="_blank" href="' . $api_url . '">API URL</a>');

				} 

				// let's assume no wp errors and no 400+ errors so we must have data
				else {
					
					// but just in case, let's make sure we have actual data
					if ( '' != $response['body'] ) {

						// encode the json data and set to TRUE for array
						$output['api']['data'] = json_decode( $response['body'], true );
						
					}

					// we still don't have valid data so let's let the user know
					
					else {

						$error_messages->add_message( 'Hmm... The API Call was successful but no data was returned.  Here is the API call for verification: <a href="' . $api_url . '">' . $api_url . '</a>');

					}

				}

				// combine any errors and set a message value
				$output['errors'] = join( '<br>', $error_messages->get_messages() );

				// if we don't have any errors, set the transient
				if ( ! isset( $output['errors'] ) || '' != $output['errors'] ) {

					// let's store the data as a transient using the cache attribute
					if ( '' != $api_cache && isset( $output['api']['data'] ) ) {

						// let's set a transient for the API call
						set_transient( $transient_name, $output['api']['data'], $api_cache );
						
					}

				}

			}

			// return the output data
			return $output;

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
			$api_data = $this;

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
						$parameter_value = $api_data->validate_key_value( $key['relationship'], $parameter_value );

						// add the value as an associative array item
						$values[$key['relationship']] = $parameter_value;

					}

				}

			}
			
			return $values;

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

			// set json api for function helpers
			$api_data = $this;

			// if we do not have an array, end the process
			if ( ! is_array ( $params ) ) {
				
				return false;

			} else {
				
				// loop through the parameters
				foreach ( $params as $key => $value ) {

					// check that we have a valid value that isn't null, blank, or empty array
					if ( null !== $value && '' !== $value &! empty( $value ) ) {

						// get valid value for the key value
						$value = $api_data->validate_key_value( $key, $value );

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
		 * Convert To Bool
		 * ===============
		 *
		 * Converts bool or strings to valid bool value.
		 *
		 * @since 	1.0.0
		 * @param 	string 	$var 	string
		 * @return 	bool
		 */
		function convert_to_bool( $var ) {
			
			// if we have a valid bool already, return it
			if ( !is_string( $var ) ) {
				return (bool) $var;
			}
			
			// switch cases for types of strings
			switch (strtolower($var)) {
				
				// true strings
				case '1':
				case 'true':
				case 'on':
				case 'yes':
				case 'y':
					return true;
				
				default:
					return false;

			}

		}

		/**
		 * Validate Key Value
		 * ==================
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
		public function validate_key_value( $key, $value ) {

			// get the default config file
			$config = $this->config;

			$date_array = $config['api_options']['all']['validation']['dates'];
			$number_array = $config['api_options']['all']['validation']['numbers'];
			$boolean_array = $config['api_options']['all']['validation']['boolean'];

			// check that we don't have an empty value
			if ( !empty( $value ) ) {

				// check if the value of the key supposed to be a boolean
				if ( in_array( $key, $boolean_array ) ) {

					// convert the value to a valid bool
					$value = $this->convert_to_bool( $value );

					return $value;

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

					// we dont have a valid number, so let's not return bad options
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

		

	}

}