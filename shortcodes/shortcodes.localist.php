<?php
/**
 * USC Localist for WordPress: Shortcodes
 * @package usc-usc-localist-for-wordpress
 */


/**
 * Add the shortcode for Localist Widget
 * 
 * @since 1.0.0
 * 
 * @require $localist_config array
 * @param 	string 	params 	shortcode api options
 * @return 	html 			events list
 * 
 * usage: [localist-events {option=value}]
 */
function usc_localist_fwp_events_shortcode( $params ) {
	
	// get the global configuration
	global $localist_config;

	// default setting for error checking
	$errors = $json_data = false;

	// get api type

		// get all api options
		$attr_all = shortcode_atts( $localist_config['api_options']['all']['allowed'], $params, 'localist-calendar' );

		// store the api type as a variable
		$api_type = $attr_all['get'];

		// check that we have a valid 'get' type
		if ( $api_type == '' || $api_type == null ) {

			// let's default to events
			$api_type = 'events';

		}

	// get allowed api attributes

		// get the available api options (based on type) from the shortcode
		$api_attr = shortcode_atts( $localist_config['api_options'][$api_type]['allowed'], $params, 'localist-calendar' );

		// build the api string
		$json_url = array(
			'type' => $api_type
		);

	// build the api url string for any options

		// get the matching api options by get type_url_form_file()
		$parameters_string = usc_localist_fwp_parameters_as_string( $api_attr, $api_type );
	
	// error processing

		// if we have any error messages
		if ( !empty($parameters_string['message']) ) {
			
			$errors = true;
			return __($parameters_string['message'], 'usc-localist-for-wordpress');

		} else {

			// no errors
			$json_url['options'] = $parameters_string['parameters'];

		}

		// get the json data if no errors are present
		if ( !$errors ) {

			$json_data = usc_localist_fwp_get_json( $json_url );

		}

	// perform the api call

		// check if we have json data
		if ( !empty( $json_data ) ) {
			
			// check if we have an array
			if ( is_array( $json_data ) ) {
				
				// we have json array data

				// TODO: function for looping through json data

			} else if ( is_string( $json_data ) && '' != $json_data ) {

				// output any error messages
				return __( $json_data, 'usc-localist-for-wordpress');

			} else {

				// output message that something was untested and needs to be addressed
				return __( 'Ninja wildebeests have sprung into action. Please contact plugin development team.','textdomain' );
			}
		}
}

// add the shortcode function
add_shortcode( 'localist-calendar', 'usc_localist_fwp_events_shortcode' );

?>