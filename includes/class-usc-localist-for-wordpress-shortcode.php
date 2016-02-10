<?php

/**
 * Class: USC Localist for WordPress Shortcode
 * 
 * Add shortcode(s) for the site to get calendar data from Localist API.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Shortcode' ) ) {
	
	class USC_Localist_For_Wordpress_Shortcode {

		/**
		 * Configuration variable
		 * @var string
		 */
		private $config;

		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		protected $plugin_name;

		/**
		 * Current version of the plugin.  Set in plugin root @ usc-localist-for-wordpress.php
		 * @var string
		 */
		protected $plugin_version;

		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $plugin_tag;

		/**
		 * Tag identifier used by shortcode generator for the calendar.
		 * @var string
		 */
		protected $plugin_shortcode_calendar;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $plugin_name, $plugin_version, $plugin_tag ) {

			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_tag= $plugin_tag;

			// load dependencies for this class
			$this->load_dependencies();

		}

		/**
		 * Load Dependencies
		 * =================
		 * 
		 * Load the required dependencies for this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// require the api class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// require the api class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-events.php';

			// retrun the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

			$this->plugin_shortcode_calendar = $this->config['plugin']['shortcode']['calendar'];

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
			$errors = $api_output = false;

			// default for api url and template options build
			$api_url = array();

			$api_data = new USC_Localist_For_Wordpress_API;

			// get all api options
			$attr_all = shortcode_atts( $config['api_options']['all']['allowed'], $params, $this->plugin_shortcode_calendar );

			/**
			 * Get type
			 */
				
				// store the api type as a variable
				$api_type = $attr_all['get'];

				// check that we have a valid 'get' type
				if ( '' == $api_type || null == $api_type ) {

					// let's default to events
					$api_type = 'events';

				}

				// set the api type
				$api_url['api_type'] = $api_type;

			/**
			 * Get flag for event inline in page
			 */
				
				// set variable
				$api_events_page = $attr_all['is_events_page'];

				// check that we have a valid 'cache' value
				if ( '' != $api_events_page || null != $api_events_page ) {

					// validate the cache value
					$api_events_page = $api_data->validate_key_value( 'is_events_page', $api_events_page );

					// store the cache number as part of the url array
					$api_url['is_events_page'] = $api_events_page;

				}

			/**
			 * Get cache
			 */

				// set transient cache expiration (in seconds)
				$api_cache = $attr_all['cache'];

				// check that we have a valid 'cache' value
				if ( '' != $api_cache || null != $api_cache ) {

					// validate the cache value
					$api_cache = $api_data->validate_key_value( 'cache', $api_cache );

					// store the cache number as part of the url array
					$api_url['cache'] = $api_cache;

				}

			/**
			 * Get template option: multiple
			 */
				
				// set template path option: multiple
				$template_path_multiple = $attr_all['template_multiple'];

				// set default template options
				if ( '' == $template_path_multiple || null == $template_path_multiple ) {
					
					$template_path_multiple = 'events-list.html';

				}

				// set the template slug
				$api_url['template_multiple'] = $template_path_multiple;

			/**
			 * Get template option: single
			 */
				
				// set template path option: single
				$template_path_single = $attr_all['template_single'];

				// set default template options
				if ( '' == $template_path_single || null == $template_path_single ) {
					
					$template_path_single = 'events-single.html';

				}

				// set the template slug
				$api_url['template_single'] = $template_path_single;

			/**
			 * Get event details href option
			 */
			
				// set the template slug
				$api_output['href'] = $attr_all['href'];

			/**
			 * Get date range option
			 */
				
				// set the date_range option
				$api_output['date_range'] = $attr_all['date_range'];

			/**
			 * Get url parameters and attach to the api query
			 */
				
				$url_parameters = $api_data->get_custom_query_variables( $api_type );

				// loop through the url parameters and attach to the $api_url associative array
				foreach ( $url_parameters as $key => $value ) {
					$api_url[$key] = $value;
				}

			/**
			 * Specificaly set event_id if declared in the shortcode.
			 *
			 * This must come after checking the url parameters.
			 */
			 	
				$api_event_id = $attr_all['event_id'];

				if ( '' != $api_event_id || null != $api_event_id ) {

					$api_url['event_id'] = $api_event_id;

				}
			
			/**
			 * Get allowed api attributes
			 */

				// get the available api options (based on type) from the shortcode
				$api_attr = shortcode_atts( $config['api_options'][$api_type]['allowed'], $params, $this->plugin_shortcode_calendar );

			/**
			 * Build the api url string for any options
			 */

				// get the matching api options by get type
				$parameters_string = $api_data->parameters_as_string( $api_attr, $api_type );
				
				// if we have any error messages
				if ( empty( $parameters_string ) ) {
					
					return __('Something went wrong.', $this->plugin_tag);

				}

				if ( ! empty( $parameters_string['errors'] ) ) {
					
					// there are errors
					$errors = true;
					return __( $parameters_string['errors'], $this->plugin_tag );

				} else {

					// no errors
					$api_url['options'] = $parameters_string['parameters'];

				}

			/**
			 * Get the api data if no errors are present
			 */
				
				if ( ! $errors ) {

					// perform the api call
					$api_output = $api_data->get_api( $api_url );

					// check if we have no errors in returned api data
					if ( ! isset( $api_output['errors'] ) || null == $api_output['errors'] ) {
						
						// check if we have data
						if ( isset( $api_output['data'] ) ) {
							
							// we have api array data

							// switch between api types for output of data by class type
							switch ( $api_output['api_type'] ) {
								
								// add api get types and their respective classes

								// future releases:
								// organizations
								// groups
								// search

								// events or event
								default:
									$shortcode_output = new USC_Localist_For_Wordpress_Events( $api_output );
									$shortcode_output->run();
									break;

							}


							return 'API Data Successful: ' . $api_output['url'];  // replace this with loop


						} 

					} else {

						return $api_output['errors'];

					}

				}

		}



	}

}