<?php

/**
 * USC Localist for WordPress: Get
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
 * @param 	url 	string 	the url of the json data to retrieve
 * @param 	timeout number 	the timeout (in seconds) for waiting for the return
 * @return 	json 	object 	the json results 	
 */
function get_json($params) {

	// check the $params for attributes and fallback to default values if not set
	$url		= isset($params['url']) ? $params['url'] : 'https://calendar.usc.edu/ap/2/events';
	$timeout	= isset($params['timeout']) ? $params['timeout'] : 5;
	
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

	$response = wp_remote_get( $url, $args );

	if ( is_array( $response ) ) {
		return $response['body'];
	}
}
?>