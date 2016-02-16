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
		 * Map Links
		 * =========
		 *
		 * @since 	1.0.0
		 * @access 	public
		 */
		public function map_link( $location_name ) {
			
			// set the from argument replacement regex for '(ABC)'
			$from = array('/\(([A-Z]{3})\)/');
			
			// set the to argument
			$to = array('http://web-app.usc.edu/maps/?b=$1');
			
			// array of HSC locations
			$hsc = 'BMT|BCC|CCC|CHP|CLB|CPT|CRL|CSA|CSB|CSC|DEI|DOH|EDM|EFC|EMP|HCC|HCT|HMR|HRA|HSV|IRD|KAM|LRA|LRB|MCH|MOL|MMR|NML|NOR|NRT|NTT|PAV|PGD|PGF|PGT|PGV|PHH|PMB|PSC|RMR|RSC|SRH|SSB|TOW|TRC|UNH|VBB|VWB|WOH|ZNI';

			// return the map link
			return preg_replace( '/\?b=('.$hsc.')/', '?b=$1#hsc', preg_replace( $from, $to, $location_name ) );

		}

		/**
		 * Get Events
		 * ==========
		 *
		 * Functions to perform when running the plugin.
		 *
		 * @since 	1.0.0
		 */
		public function get_events() {
			
			// get the template from the api_data
			$new_template = new USC_Localist_For_Wordpress_Templates( $this->api_data );
			$template = $new_template->get_template( $this->api_data );

			// get the events from the class api data
			$events = $this->api_data['data']['events'];

			$range = $this->api_data['date_range'];
			var_dump($range);

			// get the details page link, if set
			$details_page = $this->api_data['details_page'];

			foreach ( $events as $single ) {
				
				// get to the single event attribute from the API
				$event = $single['event'];

				/**
				 * Data Fields
				 */
				
					// find all data fields
					$fields = $template->find('*[data-field]');

					// loop through the data fields found
					foreach ( $fields as $field ) {

						// set variables for data-fields
						$field_value = '';
						
						// field
						$data_field = $field->{'data-field'};

						// type
						$data_type = $field->{'data-type'};

						// format 
						$data_format = isset( $field->{'data-format'} ) ? $field->{'data-format'} : false;

						// image size
						$data_image_size = isset( $field->{'image_size'} ) ? $field->{'image_size'} : false;

						// new date class object
						$datetime = new USC_Localist_For_Wordpress_Dates;

						// check if we have data type fields for specific handling
						if ( isset( $data_type ) ) {

							switch ( $data_type ) {

								// date events
								case 'date':

									

									break;
								
								// time events
								case 'time':

									break;
								
								// datetime events
								default:

									break;
							}

						}

						// multiple node data field
						if ( strpos ( $data_field, '.' ) ) {

							// convert dot path to array
							$paths = explode('.', $data_field);
							// set node to add array items as $event[node1][node2]
							$node =& $event;

							// loop through the array items
							foreach ($paths as $path) {
								
								// check if the item exists 
								if (array_key_exists($path, $node) ) {

									$node =& $node[$path];

								}

							}

							$field_value = $node;

						} 

						// single node data-field
						else {

							$field_value = $event[$data_field];

						}

						// check that we do not have an array for a field value
						if ( is_array( $field_value ) ) {

							$field_value = 'data-field: "' . $data_field . '" is an array. Please select a "data-type" option to process the data.';

						}

						// specific data types for handling non innertext output
						
						// photo
						if ( 'photo_url' == $data_field ) {

							// check if we have an overwriting image size preference: tiny, small, medium, big, big_300
							if ( $data_format ) {
								
								$field_value = str_replace( '/huge/', '/' . $data_format . '/', $field_value );

							}

							$field->src = $field_value;
						}

						// default
						else {
							
							$field->innertext = $field_value;

						}

					}

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
							
							$map_link = $this->map_link($event['location_name']);
							
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