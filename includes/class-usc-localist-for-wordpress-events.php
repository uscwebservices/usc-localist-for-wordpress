<?php

/**
 * Class: USC Localist for WordPress Events
 * 
 * A class to handle events displaying.  Accepts JSON array
 * and checks template options for output format.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Events' ) ) {

	class USC_Localist_For_Wordpress_Events {

		/**
		 * The array API data and options.
		 * @var array
		 */
		protected $api_data;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $api_data ) {

			$this->api_data = $api_data;

			$this->load_dependencies();

		}

		/**
		 * Load Dependencies
		 * =================
		 * 
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-templates.php';

			// require the date class for date and time functions
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-dates.php';

		}

		/**
		 * Get Events
		 * ==========
		 *
		 * Get the template, apply to the event(s) and output.
		 *
		 * @since 	1.0.0
		 */
		public function get_events() {
			
			// get the template from the api_data
			$template_data = new USC_Localist_For_Wordpress_Templates( $this->api_data );
			$template = $template_data->get_template( $this->api_data );

			// local scope of api_data
			$api_data = $this->api_data;

			// get the events from the class api data (single event)
			$events = $this->api_data['data'];

			// if we have 'events' (multiple events), map to that node
			if ( isset( $events['events'] ) ) {
				$events = $events['events'];
			}

			// loop through the events
			foreach ( $events as $single ) {

				// get to the single event node
				$event = $single;

				// if we have sub 'event' (multiple events), map to that node
				if ( isset( $single['event'] ) ) {
					$event = $single['event'];
				}
				
				// get the data fields, pass the template, api data and options
				$template_data->data_fields( $template, $event, $api_data );

				// get the data datetime, pass the template, api data and options
				$template_data->data_datetime( $template, $event, $api_data );

				// get the data links, pass the template, api data and options
				$template_data->data_links( $template, $event, $api_data );

				// get the data links, pass the template, api data and options
				$template_data->data_photos( $template, $event, $api_data );

				// save the template
				$output = $template->save();

				// set the value to display in the output
				echo str_replace( array( '<html>', '</html>'), array( '', '' ), $output );	
				
			}

			// clear the template to prevent memory leak
			$template->clear();
			unset( $template );
			
		}

	}

}