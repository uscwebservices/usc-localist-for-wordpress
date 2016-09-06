<?php
/**
 *	USC Localist for WordPress Plugin Class.
 *
 * @package usc-localist-for-wordpress
 */

/**
 * Class: USC Localist for WordPress API
 *
 * Get JSON from API
 *
 * since 		1.0.0
 *
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_API' ) ) {

	class USC_Localist_For_Wordpress_API {

		/**
		 * Configuration variable
		 *
		 * @var string
		 */
		private $config;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 *
		 * Constructor to run when the class is called.
		 */
		public function __construct() {

			// Get the version of wordpress.
			global $wp_version;

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Require the date class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-dates.php';

			// Require the error messaging class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-errors.php';

			// Return the API configurations.
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
		 * @param 	array $params 	the options for the function [url, type, options, page, timeout].
		 * @return 	array 		 	[data],[api_type],[api_options],[event_id],[page_current],[url].
		 */
		function get_api( $params ) {

			global $wp_version;

			// Get the default config file.
			$config = $this->config;

			// Default variables.
			$output = array();

			// Set error message object.
			$error_messages = new USC_Localist_For_Wordpress_Errors;

			// Default parameters.
			$api_base_url 			= isset( $params['url'] ) ? $params['url'] : $config['url']['base'];
			$api_type 				= isset( $params['api']['type'] ) ? $params['api']['type'] : '';
			$api_events_page		= isset( $params['is_events_page'] ) ? $params['is_events_page'] : false;
			$api_event_id			= isset( $params['event_id'] ) ? $params['event_id'] : '';
			$api_cache 				= isset( $params['cache'] ) ? $params['cache'] : $config['default']['cache'];
			$api_options 			= isset( $params['options'] ) ? $params['options'] : '';
			$api_page_number		= isset( $params['page'] ) ? $params['page'] : '1'; // default to first page of results.
			$api_timeout			= isset( $params['timeout'] ) ? $params['timeout'] : $config['default']['api_timeout'];

			// Set the default arguments.
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
			    'sslverify'		=> true, // set to false if site is trusted.
			    'stream'		=> false,
			    'filename'		=> null,
		    );

			// set var for constructed api url.
			$api_url = $api_base_url;

			// check if we have a single event or if it is an events page with event id.
			if ( 'event' === $api_type || ( $api_events_page && '' !== $api_event_id ) ) {

				// Set the type to events for api structure.
				$api_url .= 'events';

				if ( '' !== $api_event_id ) {

					// Add the event id but convert any integers to strings.
					$api_url .= '/' . strval( $api_event_id );

					// We are setting the api url by inclusion of the api_event_id so we assume we have a single event - let's manually set the api type to single event for output.
					$api_type = 'event';

				}
			} else {

				// API type and add options and page number.
				$api_url .= $api_type;

				// Add query string initiator for api options or page number.
				if ( '' !== $api_options || '' !== $api_page_number ) {

					$api_url .= '?';

				}

				// Add API options.
				if ( '' !== $api_options ) {

					$api_url .= $api_options;

				}

				// Add page number.
				if ( '' !== $api_page_number ) {

					// If we have api options, add ampersand joiner.
					if ( '' !== $api_options ) {
						$api_url .= '&';
					}

					// Add the page number parameter.
					$api_url .= 'page=' . $api_page_number;

				}
			}

			/**
			 * REMOVE: Local Testing
			 *
			 * Use the sample json data in the plugin.
			 */
			if ( $config['testing']['enabled'] ) {

				if ( 'event' === $api_type ) {
					$api_url = plugins_url( $config['testing']['json']['single'], dirname( __FILE__ ) );
				}

				if ( 'event' !== $api_type ) {
					$api_url = plugins_url( $config['testing']['json']['multiple'], dirname( __FILE__ ) );
				}
			}

			/**
			 * Data Options Output
			 *
			 * Add output data options for future calls (pagination).
			 */

			// Add the api type to the output.
			$output['api']['type'] = $api_type;

			// Add the api options to the output.
			$output['api']['options'] = $api_options;

			// Add the event id to the output.
			$output['api']['event_id'] = $api_event_id;

			// Add the current page number to the output.
			$output['api']['page_current'] = $api_page_number;

			// Add the api url used to the output.
			$output['api']['url'] = $api_url;

			/**
			 * Transient Check
			 */

			// Transient name using parameter values.
			$transient_name = '';
			foreach ( $params['parameters'] as $key => $value ) {

				if ( is_array( $value ) ) {
					/**
					 * We have an array for the value.
					 * Loop through and add the value to the transient_name.
					 *
					 * @var  string
					 */
					foreach ( $value as $key => $value ) {

						$transient_name .= $value;

					}
				} else {

					// Not an arrar - add single value to the transient_name.
					$transient_name .= $value;

				}
			}

			// Get the transient by name.
			$transient = get_transient( $transient_name );

			// First let's check if we have a transient for this API call.
			if ( ! empty( $transient ) ) {

				// We have a transient, no need to make an API call.
				$output['api']['data'] = $transient;

			}

			if ( empty( $transient ) ) {

				/**
				 * We do not have a transient stored - let's get the API.
				 */

				// Get the remote data.
				$response = wp_safe_remote_get( $api_url, $args );

				// Check if there is a wordpress error.
				if ( is_wp_error( $response ) ) {

					// Return WP error messages.
					$error_messages->add_message( 'WP Error: ' . $response->get_error_message() );

				} elseif ( $response['response']['code'] >= 400 ) {
					/**
					 * Check if there is an HTTP error 400 and above.
					 */

					// Return the error response code and message.
					$error_messages->add_message( 'Calendar API Error. The shortcode parameters used have returned: ' . $response['response']['code'] . ' - ' . $response['response']['message'] );

					// If we have a response from localist, let's provide if for better troubleshooting.
					if ( '' !== $response['body'] ) {

						// Convert response message to json data as an array.
						$localist_response = json_decode( $response['body'], true );

						// Return localist error response.
						if ( isset( $localist_response['error'] ) ) {

							$error_messages->add_message( 'Localist Error: ' . $localist_response['error'] );

						}

						// Return localist success response but with error message in body.
						if ( ! isset( $localist_response['error'] ) ) {

							$error_messages->add_message( 'Localist Error: ' . $response['body'] );

						}
					}

					// Add a link to the API URL called to help troubleshoot any issues.
					$error_messages->add_message( '<a target="_blank" href="' . $api_url . '">API URL</a>' );

				}

				// Let's assume no wp errors and no 400+ errors so we must have data.
				if ( ! is_wp_error( $response ) && $response['response']['code'] < 400 ) {

					// But just in case, let's make sure we have actual data.
					if ( '' !== $response['body'] ) {

						// Encode the json data and set to TRUE for array.
						$output['api']['data'] = json_decode( $response['body'], true );

					}

					if ( '' === $response['body'] ) {

						// We still don't have valid data so let's let the user know.
						$error_messages->add_message( 'Hmm... The API Call was successful but no data was returned.  Here is the API call for verification: <a href="' . $api_url . '">' . $api_url . '</a>' );

					}
				}

				// Combine any errors and set a message value.
				$output['errors'] = join( '<br>', $error_messages->get_messages() );

				// If we don't have any errors, set the transient.
				if ( ! isset( $output['errors'] ) || '' === $output['errors'] ) {

					// Let's store the data as a transient using the cache attribute.
					if ( '' !== $api_cache  && 0 !== $api_cache && '0' !== $api_cache && isset( $output['api']['data'] ) ) {

						// Let's set a transient for the API call.
						set_transient( $transient_name, $output['api']['data'], $api_cache );

					}
				}
			}

			// Return the output data.
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
		 * @param  string $api_type		The API type to call - default 'events'.
		 * @return array 				Associative array of keys and values.
		 */
		public function get_custom_query_variables( $api_type = 'events' ) {

			// Set a default value to capture url values.
			$values = array();

			// Set json api for function helpers.
			$api_data = $this;

			// Get the allowed values for the api type.
			$allowed_array_keys = $this->config['api_options'][ $api_type ]['allowed'];

			// Get the default config file.
			$parameters = $this->config['url']['parameters'];

			// Loop through the available parameters from the config file.
			foreach ( $parameters as $key ) {

				// Check that the key is allowed per api type.
				if ( array_key_exists( $key['relationship'], $allowed_array_keys ) ) {

					// Get the value of the parameter.
					$parameter_value = get_query_var( $key['name'], false );

					// Check if we have a value.
					if ( $parameter_value ) {

						// Validate the value.
						$parameter_value = $api_data->validate_key_value( $key['relationship'], $parameter_value );

						// Add the value as an associative array item.
						$values[ $key['relationship'] ] = $parameter_value;

					}
				}
			}

			return $values;

		}

		/**
		 * Parameters as String
		 * ====================
		 *
		 * Convert Parameters to URL string for passing to Localist API.
		 *
		 * @since 	1.0.0
		 *
		 * @param 	array  $params		An array of values to process into a string.
		 * @param	string $api_type	The allowed array set from the config settings ['api_optons'].
		 * @return 	string 				String value of $key=$value concatenated from $params.
		 */
		public function parameters_as_string( $params, $api_type = 'all' ) {

			// Get the default config file.
			$config = $this->config;

			// Get the allowed array values for the api type.
			$allowed_array = $config['api_options'][ $api_type ]['allowed_array'];

			// Set the default output and string constructor.
			$output = $string = $parameter = array();

			// Set error message object.
			$error_messages = new USC_Localist_For_Wordpress_Errors;

			// Set json api for function helpers.
			$api_data = $this;

			// If we do not have an array, end the process.
			if ( ! is_array( $params ) ) {

				return false;

			}

			if ( is_array( $params ) ) {

				// Loop through the parameters.
				foreach ( $params as $key => $value ) {

					// Check that we have a valid value that isn't null, blank, or empty array.
					if ( null !== $value && '' !== $value && ! empty( $value ) ) {

						// Get valid value for the key value.
						$value = $api_data->validate_key_value( $key, $value );

						// Check that we don't have a boolean.
						if ( is_bool( $value ) ) {

							// Add single key boolean values as 'key=bool_value'.
							$string[] .= urlencode( $key ) . '=' . var_export( $value, true );

							// Add single key boolean as parameter.
							$parameter[ urlencode( $key ) ] = var_export( $value, true );

						}

						if ( ! is_bool( $value ) ) {

							// Convert any comma delimited $value to an array.
							$value = explode( ',', $value );

							// If the $value is an array.
							if ( count( $value ) > 1 ) {

								// Check that the $value is allowed as an array.
								if ( ! in_array( $key, $allowed_array, true ) ) {

									// Let the user know they are attempting an array where one is not allowed.
									$error_messages->add_message( 'Multiple values not allowed for "' . $key . '" with get "' . $api_type . '".' );

								} else {

									// Loop through sub values.
									foreach ( $value as $sub_value ) {

										// Add multiple values as 'key[]=sub_value'.
										$string[] .= urlencode( $key ) . '[]=' . urlencode( $sub_value );

										// Add multiple values as key = sub_value to paremters.
										$parameter[ urlencode( $key ) ][] = urlencode( $sub_value );

									}
								}
							} else {

								// Add single key values as 'key=value'.
								$string[] .= urlencode( $key ) . '=' . urlencode( $value[0] );

								// Add single key values as 'key=value' to parameter.
								$parameter[ urlencode( $key ) ] = urlencode( $value[0] );

							}
						}
					}
				}

				// Combine any errors and set a message value.
				$output['errors'] = join( '<br>', $error_messages->get_messages() );

				// Return the full list of parameters.
				$output['parameters'] = $parameter;

				// Combine any strings and set a url string value.
				$output['string'] = join( '&', $string );

				// Return the output.
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
		 * @param 	string $var 	String/Bool value to convert to bool value.
		 * @return 	bool
		 */
		function convert_to_bool( $var ) {

			// If we have a valid bool already, return it.
			if ( ! is_string( $var ) ) {
				return (bool) $var;
			}

			// Switch cases for types of strings.
			switch ( strtolower( $var ) ) {

				// True strings passed to conver to 'true'.
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
		 * @param 	string $key 		key to check against date/number options.
		 * @param 	string $value 		value to check if date or number.
		 * @return 	string 		 		returns validated value if date/number or
		 * 								original value
		 */
		public function validate_key_value( $key, $value ) {

			// Get the default config file.
			$config = $this->config;

			$date_array = $config['api_options']['all']['validation']['dates'];
			$number_array = $config['api_options']['all']['validation']['numbers'];
			$boolean_array = $config['api_options']['all']['validation']['boolean'];

			// Check that we don't have an empty value.
			if ( ! is_null( $value ) ) {

				// Check if the value of the key supposed to be a boolean.
				if ( in_array( $key, $boolean_array, true ) ) {

					// Convert the value to a valid bool.
					$value = $this->convert_to_bool( $value );

					return $value;

				} elseif ( in_array( $key, $date_array, true ) ) {

					/**
					 *  CheckS if the value of the key is supposed to be in a date format.
					 */

					// Set a new date object for this $key.
					$date = new USC_Localist_For_Wordpress_Dates;

					// Check if we have a valid date (bool).
					if ( $date->valid_date( $value ) ) {

						// Good date format, so return it.
						return $value;

					}

					// Else fix the date format.
					return $date->fix_date( $value );

				} elseif ( in_array( $key, $number_array, true ) ) {

					// Check if the key is supposed to be in a number format.

					// If we have a number.
					if ( is_numeric( $value ) ) {

						// Convert any non-whole integer values.
						return intval( $value );

					} elseif ( is_string( $value ) ) {

						// Else do we have a string.

						// Set default to re-attach valid numbers.
						$number_string = array();

						// If we have a string of numbers (array), check each one.
						$value_string = explode( ',', $value );

						// If we have more than one in the exploded array.
						if ( count( $value_string ) > 1 ) {

							// Loop through the values.
							foreach ( $value_string as $number ) {

								// Convert any non-whole integer or string values.
								$number_string[] = intval( $number );

							}

							// Combine $number_string array back to a string format.
							return join( ',', $number_string );

						}

						// Else, default 'false'.
						return false;
					}

					// Else  we do not have a valid number, so let's not return bad options.
					return false;
				} else {

					// If the value doesn't need validation, just return the value.
					return $value;

				}
			}
		}
	}
}
