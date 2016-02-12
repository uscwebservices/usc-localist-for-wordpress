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
		 * Run
		 * ===
		 *
		 * Functions to perform when running the plugin.
		 *
		 * @since 	1.0.0
		 */
		public function get_events() {
			
			// get the template from the api_data
			$new_template = new USC_Localist_For_Wordpress_Templates( $this->api_data );
			
			$template = $new_template->get_template( $this->api_data );

			// var_dump($template);

			$events = $this->api_data['data']['events'];

			foreach ( $events as $event ) {

				// get to the single event attribute from the API
				$event = $event['event'];

				// find all data fields
				$fields = $template->find('*[data-field]');

				// loop through the data fields found
				foreach ( $fields as $field ) {

					// set variables for data-fields
					
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
						$data_paths = explode('.', $data_field);

						// set node to add array items as $event[node1][node2]
						$node =& $event;

						// loop through the array items
						foreach ($data_paths as $path) {
							
							// check if the item exists 
							if (array_key_exists($path, $node) ) {

								$node =& $node[$path];

							} 

							// doesn't exist so let's return a message to help the template builder
							else {

								$node =  '"' . $data_field . '" ' . __('data type does not exist');

							}

						}

						// set the field value to be returned
						$field_value = $node;

					} 

					// single node data-field
					else {

						// set the field value to be returned
						$field_value = $event[$data_field];
						

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

				
				
			}

			// save the template
			$output = $template->save();

			echo $output;

			// set the value to display in the output
			echo str_replace( array( '<html>', '</html>'), array( '', '' ), $output );
			
		}

	}

}