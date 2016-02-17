<?php

/**
 * Class: USC Localist for WordPress Template
 * 
 * A class to handle parsing output of API data using 
 * custom templates stored as post types.  Post types
 * are enabled in USC_Localist_For_Wordpress_Admin
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Templates' ) ) {

	class USC_Localist_For_Wordpress_Templates {

		/**
		 * The array of api data.
		 * @var array
		 */
		public $api_data;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $api_data ) {

			// get the template path opton
			$this->api_data = $api_data;

			// load the dependencies
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
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions-simple-html-dom.php';

		}

		/**
		 * Valid URL
		 * =========
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		function valid_url( $url ) {
			
			$headers = @get_headers( $url );
			$httpStatus = intval( substr( $headers[0], 9, 3 ) );
			
			if ( $httpStatus<400 ) {
				
				return true;

			}

			return false;

		}

		/**
		 * Data Type
		 * =========
		 * 
		 * @param 	string 	$data_type 		specific handling for data_fields function
		 * @param 	array 	$api_data 		api data array used to get node values
		 *                            		(i.e - event(s))
		 * @param 	array 	$options 		api options passed:
		 *                           		[date_range, details_page, api_type]
		 * @return 	string 	field_value		returns the value of the data_field + data_type
		 *                               	combination
		 */
		public function data_type( $data_type, $api_data, $options ) {

			// new date class object
			$date_functions = new USC_Localist_For_Wordpress_Dates;

			switch ( $data_type ) {

				// date events
				case 'date':

					// if date range selected and non matching first/last dates
					// if ( $options['date_range'] && $api_data['first_date'] != $api_data['last_date'] ) {

					// 	$dates = array( $api_data['first_date'], $api_data['last_date'] );
						
					// }

					// otherwise, choose the array of event_instances
					// else {

					// 	$dates = $api_data['event_instances'];

					// }

					// $field_value = $date_functions->format_dates( $dates, $data_format, $date_range );

					break;
				
				// time events
				case 'time':

					// $field_value = $date_functions->format_times( $api_data['event_instances'], $format );

					break;
				
				// datetime events
				default:

					// if date range selected and non matching first/last dates
					// if ( $date_range && $api_data['first_date'] != $api_data['last_date'] ) {

					// 	$dates = array( $api_data['first_date'], $api_data['last_date'] );
						
					// }

					// otherwise, choose the array of event_instances
					// else {

					// 	$dates = $api_data['event_instances'];

					// }

					// $field_value = $date_functions->format_dates( $dates, $data_format, $date_range );
					//$fieldvalue = $date_functions->format_datetime($dates,$fmt,false,$range);

					break;

			}

			// return $field_value;

		}

		/**
		 * Data Fields
		 * ===========
		 *
		 * Get 
		 * @param 	array 	$data 		[description]
		 * @param 	object 	$template 	the template object
		 * @param 	[type] 	$options 	[description]
		 * @return 	[type] 				[description]
		 */
		public function data_fields( $template, $api_data, $options ) {

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

				// check if we have data type fields for specific handling
				if ( isset( $data_type ) ) {

					$field_value = $this->data_type( $data_type, $api_data, $options );

				}

				
				$field_value = $this->string_node( $api_data, $data_field );

				// check that we do not have an array for a field value
				if ( is_array( $field_value ) &! $data_type ) {

					$field_value = 'data-field: "' . $data_field . '" is an array. Please select a "data-type" option to process the data.';

				}

				// default
				else {
					
					$field->innertext = $field_value;

				}

			}

		}

		public function data_links( $template, $api_data, $options ) {

			// defaults
			$details_page = $options['details_page'];

			// find all data links
			$links = $template->find('*[data-link]'); // handle links in templates
			
			foreach ( $links as $link ) {

				// get the data link attribute
				$data_link = $link->{'data-link'};

				// check if we have a link to a map
				if ( 'map' == $data_link ) {
					
					$map_link = $template_data->map_link($api_data['location_name']);
					
					// set the href using map_link function
					$link->href = $map_link;

					// set the text to the location name
					$link->innertext = $api_data['location_name'];


				} 

				// check if we have a link to the details page
				else if ( 'detail' == $data_link ) {
					
					// check if we have a set details page link
					if ( '' != $details_page ) {
						
						// attach the api_data url parameter to the link
						$link->href = $details_page . '?event-id=' . $api_data['id'];

					}

					// default: link to the localist details page
					else {
						
						$link->href = $api_data['localist_url'];
					
					}

				}

				// defautl to use data link with node mapping
				else {
					
					$link->href = $api_data[$data_link];

				}

			}

		}

		/**
		 * Get Template
		 * ============
		 *
		 * Get the template based on the 
		 *
		 * @since 	1.0.0
		 */
		public function get_template( $api_data ) {
			
			// multiple items template path
			$template_path_multiple = $api_data['template_multiple'];

			// single items template path
			$template_path_single = $api_data['template_single'];

			// if we have a single template path value and the api type is a single event
			if ( '' != $template_path_single && 'event' == $api_data['api_type'] ) {

				$template_path = $template_path_single;

			} else {

				$template_path = $template_path_multiple;

			}



			// default template path
			$default_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/' . $template_path;

			// if the template location is at http
			if ( strpos( $template_path, 'http' ) === 0 ) {
				
				// check that we have a vaild url
				$valid_template = $this->valid_url( $template_path );

				if ( $valid_template ) {
				
					$html = file_get_contents( $template_path );

					return file_get_html($html);

				}

				else {

					return file_get_html( $default_template );

				}

			} 

			// if the template is in the templates directory as file
			else if ( strpos( $template_path, '.html' ) ) {

				return file_get_html( $default_template );

			} 

			// else let's use the custom post type
			else {

				// get the post by slug name
				$template_post = get_posts( array(
					'name' => $template_path,
					'posts_per_page' => 1,
					'post_type' => 'event-template',
					'post_status' => 'publish'
				) );

				// fallback to default path 
				if( ! $template_post ) {

					$html = $default_template;

				} else {
					
					$html = $template_post[0]->post_content;

				}
			
				// if we have valid html returned
				if ( ! empty( $html ) ) {
					
					if ( ! strpos( '<html>', ' ' . $html ) ) {
						
						$html = '<html>' . $html . '</html>';

					}
					
					return str_get_html($html);

				}

			}
			
			
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
		 * String Node
		 * ===========
		 *
		 * Parses a dot syntax string into an associative array.
		 *
		 * some.data.type becomes $api_data['some']['data']['type']
		 * 
		 * @param 	array 	$api_data 		single item array of api data node (i.e. - event)
		 * @param 	string 	$data_field 	the data field to check against
		 * @return 	assoc array path 		the p
		 */
		public function string_node( $api_data, $data_field ) {

			// multiple node data field
			if ( strpos ( $data_field, '.' ) ) {

				// convert dot path to array
				$paths = explode('.', $data_field);

				// set node to add array items as $event[node1][node2]
				$node =& $api_data;

				// loop through the array items
				foreach ($paths as $path) {
					
					// check if the item exists 
					if (array_key_exists($path, $node) ) {

						$node =& $node[$path];

					}

				}

				$field_value = $node;

			}

			// single node data field
			else {

				$field_value = $api_data[$data_field];
			}

			return $field_value;

		}


	}

}