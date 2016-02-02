<?php

/**
 * Class: USC Localist for WordPress API
 *
 * Get JSON from API
 *
 * @since      1.0.0
 *
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
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

			// retrun the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Get JSON
		 * ========
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
		 * @return 	json 	array 	the json data 	
		 */
		function get_json( $params ) {

			global $wp_version;

			// get the default config file
			$config = $this->config;
			
			// default variables
			$output = array();

			// set error message object
			$error_messages = new USC_Localist_For_Wordpress_Errors;

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

			// set the api url to the output data for any debugging
			$output['url'] = $api_url;

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
					$error_messages->add_message( 'WP Error: ' . $response->get_error_message() );

				}

				// check if there is an HTTP error 400 and above
				else if ( $response['response']['code'] >= 400 ) {

					// return the error response code and message
					$error_messages->add_message( 'Calendar API Error. The shortcode parameters used have returned: ' . $response['response']['code'] . ' - ' . $response['response']['message'] );
					
					// if we have a response from localist, let's provide if for better troubleshooting
					if ( '' != $response['body'] ) {
						
						// convert response message to json data as an array
						$localist_error = json_decode( $response['body'], true );

						// return localist error response
						if ( isset( $localist_error['error'] ) ) {
							
							$error_messages->add_message( 'Localist Error: ' . $localist_error['error'] );

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
						$output['data'] = json_decode( $response['body'], TRUE );

						// let's store the data as a transient using the cache attribute
						if ( '' != $api_cache ) {

							// let's set a transient for the API call
							set_transient( $transient_name, $output['data'], $api_cache );
							
						}
						
					}

					// we still don't have valid data so let's let the user know
					
					else {

						$error_messages->add_message( 'Hmm... The API Call was successful but no data was returned.  Here is the API call for verification: <a href="' . $api_url . '">' . $api_url . '</a>');

					}

				}

				// combine any errors and set a message value
				$output['errors'] = join( '<br>', $error_messages->get_messages() );

			}

			// return the output data
			return $output;

		}

	}

}