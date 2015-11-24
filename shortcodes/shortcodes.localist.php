<?php
/**
 * USC Localist for WordPress: Shortcodes
 * @package usc-usc-localist-for-wordpress
 */


/**
 * Add the shortcode for Localist Widget
 * 
 * @since 1.0.0
 * @param string name pass the name
 * @return html 	events list
 * 
 * usage: [locaclist src="widget value"]
 */
function usc_localist_fwp_shortcodes( $params ) {
	// get the attributes from the shortcode
	$p = shortcode_atts( array(
		'name' => 'default value'
	), $params );

	// return values as $p['name']

	// function to get the json data from the server - store as transient

	// return the output using the parameters
}

// add the shortcode function
add_shortcode( 'localist', 'usc_localist_fwp_shortcodes' );

?>