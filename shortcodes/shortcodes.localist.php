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

	// function variables
		$pagination_name = $localist_config['url']['variables']['pagination'];

		// get pagination from url
		$page = filter_input(INPUT_GET, $pagination_name, FILTER_SANITIZE_NUMBER_INT);

	
	// get all api options
	$attr_all = shortcode_atts($localist_config['api_options']['all']['allowed'], $params, 'localist-calendar');

	// store the api type as a variable
	$api_type = $attr_all['get'];

	// check that we have a valid 'get' type
	if ( $api_type == '' || $api_type == null ) {

		// let's default to events
		$api_type = 'events';

	}

	// get the available api options (based on type) from the shortcode
	$api_attr = shortcode_atts( $localist_config['api_options'][$api_type]['allowed'], $params, 'localist-calendar' );

	// build the api string
	$json_url = array(
		'type' => $api_type
		//'url' => plugins_url( '/sample/events.json', dirname(__FILE__) )
	);

	// get the matching api options by get type_url_form_file()
	$parameters_string = parameters_as_string($api_attr, $api_type);

	if ( !empty( $parameters_string ) ) {
		$json_url['options'] = $parameters_string;
	}

	// get the json data
	$json_data = get_json($json_url);

	// check if we have json data
	if ( $json_data ) {
		
		// check if we have an array
		if ( is_array( $json_data ) ) {

			// we have json array data

			// TODO: function for looping through json data

		} else if ( $json_data != null || $json_data != '' ) {

			// output any error messages
			echo $json_data;

		} else {

			// output message that something needs to be addressed
			_e('Ninja wildebeests have sprung into action. Please contact plugin development team.','textdomain');
		}
	}
}

// add the shortcode function
add_shortcode( 'localist-calendar', 'usc_localist_fwp_events_shortcode' );

?>