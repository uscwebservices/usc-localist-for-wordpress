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
 * @param 	string 	type 	the type of data to get [events]
 * @param 	string 	options the options to attach to narrow results
 * @param 	timeout number 	the timeout (in seconds) for waiting for the return
 * @return 	json 	array 	the json results 	
 */
function usc_localist_fwp_get_json( $params ) {

	global $wp_version, $localist_config;
	
	// default parameters
	$api_base_url 		= isset($params['url']) ? $params['url'] : $localist_config['url']['base'];
	$api_type 			= isset($params['type']) ? $params['type'] : '';
	$api_options 		= isset($params['options']) ? '?' . $params['options'] : '';
	$api_page_number	= isset($params['page_number']) ? '?' . $params['page_number'] : '';
	$timeout			= isset($params['timeout']) ? $params['timeout'] : 5;
	
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
	$api_url = $api_base_url . $api_type . $api_options;

	// get the remote data
	$response = wp_safe_remote_get( $api_url, $args );

	// check if there is a wordpress error
	if ( is_wp_error( $response ) ) {

		// return WP error messages
		return '<div class="error-code">' . __('WP Error: ', 'textdomain') . $response->get_error_message() . '</div>';

	}

	// check if there is an HTTP error 400 and above
	if ( $response['response']['code'] >= 400 ) {

		// return the error response code and message
		return '<div class="error-code"><p>' . __('Calendar API Error. The shortcode parameters used have returned: ', 'textdomain') . $response['response']['code'] . ' - ' . $response['response']['message'] . '</p></div>';

	} else {

		// no errors so let's return the data!
		
		// encode the json data and set to TRUE for array
		$json_data = json_decode($response['body'], TRUE);

		// function to get the json data from the server - store as transient

		return $json_data;

	}

}



/**
 * API Get Type
 * ============
 * 
 * /organizations
 * /organizations/ORGANIZATION_ID
 * /organizations/ORGANIZATION_ID/communities
 * /organizations/ORGANIZATION_ID/communities/COMMUNITY_ID
 * /events
 * /events/search
 * /events/EVENT_ID
 * /events/EVENT_ID/activity
 * /events/EVENT_ID/attendees
 * /events/EVENT_ID/attendance
 * /events/filters
 * /events/labels
 * /places
 * /places/search
 * /places/PLACE_ID
 * /places/filters
 * /places/labels
 * /groups
 * /groups/GROUP_ID
 * /groups/filters
 * /groups/labels
 * /departments
 * /departments/DEPARTMENT_ID
 * /departments/filters
 * /departments/labels
 * /photos
 * /photo/PHOTO_ID
 * 
 * Returns they url parameters for the GET type.
 * 
 * @param 	string 	type 		Type of API data to retrieve [events,search,organization,communities]
 * @param 	int 	id 			Pass the ID of the specific item
 * @param 	int 	parent_id 	Pass the ID of the parent item. Valid only for [organization,communities]
 */


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
 * @return 	string 				Parameters constructed in 
 */
function usc_localist_fwp_parameters_as_string( $params, $api_type = 'all' ) {

	// get the global config settings
	global $localist_config;

	// get the base url of the api
	$base = $localist_config['url']['base'];

	// get the allowed array types
	$allowed = $localist_config['api_options'][$api_type]['allowed_array'];

	// set the default string constructor
	$string = array();


	// if we do not have an array, end the process
	if ( !is_array($params) ) {

		return false;

	} else {
		
		// loop through the parameters
		foreach ( $params as $key => $value ) {
				
			// check that we have a valid value that isn't null, blank, or empty array
			if ( $value !== null && $value !== '' &! empty($value) ) {
				
				// convert comma delimited values to array
				$value = explode(',',$value);

				// if the $value is an array
				if( is_array( $value ) ) {
					
					// loop through sub values
					foreach($value as $sub_value) {
						
						// add the key values as multiple array items
						$string[] .= urlencode($key) . '[]=' . urlencode($sub_value);

					}

				} else {
					
					// add single key values
					$string[] .= urlencode($key) . '=' . urlencode($value);

				}

			}
		}

		// check if the $string array is not empty
		if ( !empty( $string ) ) {

			// if not, build the query string
			return join('&', $string);

		} else {
			
			// else just return the $base
			return false;

		}

	}

}
?>