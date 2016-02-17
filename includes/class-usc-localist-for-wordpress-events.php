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
		 * Get Event
		 * =========
		 */

		/**
		 * Get Events
		 * ==========
		 *
		 * Get the events list and parse through the data
		 *
		 * @since 	1.0.0
		 */
		public function get_events() {
			
			// get the template from the api_data
			$template_data = new USC_Localist_For_Wordpress_Templates( $this->api_data );
			$template = $template_data->get_template( $this->api_data );

			// store options as array
			$options = array();

			// get the date range if set
			// $date_range = $this->api_data['date_range'];
			$options['date_range'] = $this->api_data['date_range'];
			

			// get the details page link, if set
			// $details_page = $this->api_data['details_page'];
			$options['details_page'] = $this->api_data['details_page'];

			// get the events from the class api data
			$events = $this->api_data['data'];

			
			if ( $events['events'] ) {
				$events = $events['events'];
			}

			// loop through the events
			foreach ( $events as $single ) {

				// get to the single event attribute from the API
				$event = $single;

				if ( $single['event'] ) {
					$event = $single['event'];
				}
				
				// get the data fields, pass the template, api data and options
				$template_data->data_fields( $template, $event, $options );

				/**
				 * Data Links
				 */
				
					// find all data links
					$links = $template->find('*[data-link]'); // handle links in templates
					
					foreach ( $links as $link ) {

						// get the data link attribute
						$data_link = $link->{'data-link'};

						// check if we have a link to a map
						if ( 'map' == $data_link ) {
							
							$map_link = $template_data->map_link($event['location_name']);
							
							// set the href using map_link function
							$link->href = $map_link;

							// set the text to the location name
							$link->innertext = $event['location_name'];


						} 

						// check if we have a link to the details page
						else if ( 'detail' == $data_link ) {
							
							// check if we have a set details page link
							if ( '' != $details_page ) {
								
								// attach the event-id url parameter to the link
								$link->href = $details_page . '?event-id=' . $event['id'];

							}

							// default: link to the localist details page
							else {
								
								$link->href = $event['localist_url'];
							
							}

						}

						// defautl to use data link with node mapping
						else {
							
							$link->href = $event[$data_link];

						}

					}

				

				// save the template
				$output = $template->save();

				// set the value to display in the output
				echo str_replace( array( '<html>', '</html>'), array( '', '' ), $output );	
				
			}
			
		}

	}

}