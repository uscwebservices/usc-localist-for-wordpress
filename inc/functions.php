<?php

/**
 * USC Localist for WordPress: Functions
 * @package usc-usc-localist-for-wordpress
 */


/**
 * Get JSON
 * ========
 * 
 * Get the JSON content
 * 
 * @since 1.0.0
 * 
 * @param 	array 	params 	the options for the function [url, type, options, page_number, timeout]
 * @param 	string 	type 	the type of data to get [events]
 * @param 	string 	options the options to attach to narrow results
 * @param 	timeout number 	the timeout (in seconds) for waiting for the return
 * @return 	json 	array 	the json results 	
 */
function usc_lfwp_get_json( $params ) {

	global $wp_version;

	$config = usc_lfwp_config();
	
	// default variables
	$output = $error_message = array();

	// default parameters
	$api_base_url 		= isset ( $params['url'] ) ? $params['url'] : $config['url']['base'];
	$api_type 			= isset ( $params['type'] ) ? $params['type'] : '';
	$api_options 		= isset ( $params['options'] ) ? $params['options'] : '';
	$api_page_number	= isset ( $params['page_number'] ) ? $params['page_number'] : '';
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
	$api_url = $api_base_url . $api_type . $api_options . $api_page_number;

	// local testing only
	$api_url = plugins_url( '/sample/events.json', dirname(__FILE__) );

	// get the remote data
	$response = wp_safe_remote_get( $api_url, $args );

	// check if there is a wordpress error
	if ( is_wp_error( $response ) ) {

		// return WP error messages
		$error_message[] = __('WP Error: ', 'textdomain') . $response->get_error_message();

	}

	// check if there is an HTTP error 400 and above
	if ( $response['response']['code'] >= 400 ) {

		// return the error response code and message
		$error_message[] = __('Calendar API Error. The shortcode parameters used have returned: ', 'textdomain') . $response['response']['code'] . ' - ' . $response['response']['message'];

	} else {

		// no errors so let's return the data!
		
		// encode the json data and set to TRUE for array
		$output['results'] = json_decode( $response['body'], TRUE );

		// TODO: function to get the json data from the server - store as transient

	}

	// combine any errors and set a message value
	$output['errors'] = join( '<br>', $error_message );

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
function usc_lfwp_validate_date( $date, $format = 'Y-m-d' ) {
    
    // returns new DateTime object formatted according to the specified format
    $d = DateTime::createFromFormat($format, $date);

    // return boolean
    return $d && $d->format($format) == $date;
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
function usc_lfwp_fix_date( $date, $format = 'Y-m-d' ) {
	
	// change the $date to $format and return
	$d = date($format,strtotime($date));
	return $d;

}



/**
 * Validate Value
 * ==============
 * 
 * Validate keys and associative values against specified dates and 
 * numbers keys from usc_lfwp_config() settings.  The $key is matched
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
function usc_lfwp_validate_key( $key, $value ) {

	$config = usc_lfwp_config();

	$error_message = array();

	$date_array = $config['api_options']['all']['validation']['dates'];
	$number_array = $config['api_options']['all']['validation']['numbers'];

	// check that we don't have an empty value
	if ( !empty( $value ) ) {

		// check if the value of the key is supposed to be in a date format
		if ( in_array( $key, $date_array ) ) {

			// check if we have a valide date
			if ( usc_lfwp_validate_date( $value ) ) {
				
				// good date format, so return it
				return $value;
			
			} else {
				
				// fix the date format
				return usc_lfwp_fix_date( $value );
			}

		} 

		// check if the key is supposed to be in a number format
		else if ( in_array( $key, $number_array ) ) {

			// if we have a number
			if ( is_numeric( $value ) ) {
				
				return $value;
			
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
						if ( is_int( intval( $number ) ) ) {

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
function usc_lfwp_parameters_as_string( $params, $api_type = 'all' ) {

	// get the global config settings
	$config = usc_lfwp_config();

	// get the allowed array values for the api type
	$allowed_array = $config['api_options'][$api_type]['allowed_array'];

	// set the default output, message and string constructor
	$output = $string = $error_message = array();

	// if we do not have an array, end the process
	if ( !is_array ( $params ) ) {
		
		return false;

	} else {
		
		// loop through the parameters
		foreach ( $params as $key => $value ) {
			
			// check 
			$valid_value = usc_lfwp_validate_key($key,$value);


			echo '<br>key: [' . $key . '] value: [' . $value . '] valid: [' . $valid_value . ']<br>';

			// check for validation
			$value = $valid_value;

			// check that we have a valid value that isn't null, blank, or empty array
			if ( $value !== null && $value !== '' &! empty( $value ) ) {

				// convert any comma delimited $value to an array
				$value = explode( ',', $value );

				// if the $value is an array
				if ( count( $value ) > 1 ) {
					
					// check that the $value is allowed as an array
					if ( !in_array( $key, $allowed_array ) ) {
						
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
		
		// combine any errors and set a message value
		$output['errors'] = join( '<br>', $error_message );

		// combine any strings and set a url string value
		$output['parameters'] = join( '&', $string );
		

		// return the output
		var_dump($output);

	}

}
?>