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
		protected $api_data;

		/**
		 * Configuration variable
		 * @var string
		 */
		private $config;

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

			// retrun the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

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

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

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
		 * Data Datetime
		 * =============
		 *
		 * Get Template items with attribute data-datetime and set the inner text with the value of 
		 * the mapped nodes.
		 * 
		 * @param 	object 	$template 	the template object
		 * @param 	array 	$api_data 	the json array of the api data to use
		 * @param 	array 	$options 	the options of the api call passed for any call specifi functions
		 * @return 	 					the output of matching node values as the inner text of the template item
		 */
		public function data_datetime( $template, $api_data, $options ) {

			// config setting
			$config = $this->config;

			// find all data fields
			$fields = $template->find('*[data-date-type]');

			// set variable if we have a single event
			$is_single = ( $options['api']['type'] == 'event' ) ? true : false;

			// loop through the data fields found
			foreach ( $fields as $field ) {

				// set variables for data-fields and output
				$field_value = $output = '';
				
				// date types (date, time, datetime)
				if ( $is_single ) {
					
					$options['date_type'] = isset( $field->{'data-date-type'} ) ? $field->{'data-date-type'} : 'datetime';

				}

				else {

					$options['date_type'] = isset( $field->{'data-date-type'} ) ? $field->{'data-date-type'} : 'date';

				}
				
				// specific date instance to use (start, end, datetime-start-end)
				$options['date_instance'] = isset( $field->{'data-date-instance'} ) ? $field->{'data-date-instance'} : 'start';

				// data format for dates
				$options['format_date'] = isset( $field->{'data-format-date'} ) ? $field->{'data-format-date'} : $config['default']['format_date'];

				// data format for times
				$options['format_time'] = isset( $field->{'data-format-time'} ) ? $field->{'data-format-time'} : $config['default']['format_time'];

				// date and time separator
				$options['separator_date_time'] = isset( $field->{'data-separator-date-time'} ) ? $field->{'data-separator-date-time'} : $config['default']['separator']['date_time'];

				// time separator
				$options['separator_time'] = isset( $field->{'data-separator-time'} ) ? $field->{'data-separator-time'} : $config['default']['separator']['time'];
				
				// separator to use between instances output
				$separator = isset( $field->{'data-separator'} ) ? $field->{'data-separator'} : null;

				// date ranges
				$date_range = isset( $options['template_options']['date_range'] ) ? $options['template_options']['date_range'] : false;

				$date_start = date( $options['format_date'], strtotime( $api_data['first_date'] ) );
				$date_end = date( $options['format_date'], strtotime( $api_data['last_date'] ) );

				// return the date range if set and not on sigle event
				if ( ! $is_single && $date_range && ( $date_start != $date_end ) ) {
					
					$date_start = date( $options['format_date'], strtotime( $api_data['first_date'] ) );
					$date_end = date( $options['format_date'], strtotime( $api_data['last_date'] ) );

					if ( $date_start != $date_end ) {

						$output .= '<time datetime="'. $api_data['first_date'] .'">' . $date_start . '</time> - <time datetime="'. $api_data['last_date'] . '">' . $date_end . '</time>';

						$field->innertext = $output;
					}

				}

				// no date range
				else {

				
					// get the event instance(s)
					$event_instances = $api_data['event_instances'];

					$event_instances_amount = count( $event_instances );
					
					// defaults for determing number in loop
					$i= 1;

					foreach ( $event_instances as $event_instance ) {

						// new date class object
						$date_functions = new USC_Localist_For_Wordpress_Dates;

						$date_output = $date_functions->date_as_html( $event_instance, $options );
						
						if ( $date_output ) {

							// get the formatted date/time element
							$output .= $date_output;

							// add the separator if set
							if ( isset( $separator ) && $i < $event_instances_amount ) {

								$output .= $separator;

							}

						}

						// attach the formatted date/time element to the field
						$field->innertext = $output;

						// increase the number for event instances
						$i++;

					}

				}

			}

		}

		/**
		 * Data Fields Value
		 * =================
		 *
		 * Process the individual data-field value from the data_fields and data_link function loops.
		 * 
		 * @param  object	$field 		the data field to be processed
		 * @param  array 	$api_data 	the json array of the api data to use
		 * @return string				the output of matching node values as the inner text of the template item
		 */
		public function data_fields_value( $field, $api_data ) {

			// set variables for data-fields
			$field_value = '';
			
			// get the data-field
			$data_field = $field->{'data-field'};

			// get the value from the string format mapped node
			$field_value = $this->string_node( $api_data, $data_field );

			// check that we do not have an array for a field value
			if ( is_array( $field_value ) ) {

				return 'data-field: "' . $data_field . '" is an array. Please reference the help section for different data types.';

			}

			// add field value as innertext of the node
			else {
				
				return $field_value;

			}

		}

		/**
		 * Data Fields
		 * ===========
		 *
		 * Get Template items with attribute data-field and set the inner text with the value of 
		 * the mapped node.
		 * 
		 * @param 	object 	$template 	the template object
		 * @param 	array 	$api_data 	the json array of the api data to use
		 * @param 	array 	$options 	the options of the api call passed for any call specific functions
		 * @return 	 					the output of matching node values as the inner text of the template item
		 */
		public function data_fields( $template, $api_data, $options ) {

			// find all data fields
			$fields = $template->find('*[data-field]');

			// loop through the data fields found
			foreach ( $fields as $field ) {

				$field_value = $this->data_fields_value( $field, $api_data );

				$field->innertext = $field_value;

			}

		}

		/**
		 * Data Links
		 * ==========
		 *
		 * Loop through all instances that have data attribute 'data-link'
		 * 
		 * @param 	object 	$template 	the template object from get_template
		 * @param 	array 	$api_data 	the single api type array of data to map the link value
		 * @param 	array 	$options 	the options passed from the api
		 * @return 	html 				returns the links output to the $template object
		 */
		public function data_links( $template, $api_data, $options ) {

			// defaults
			$details_page = $options['template_options']['details_page'];

			// find all data links
			$links = $template->find('*[data-link]'); // handle links in templates
			
			foreach ( $links as $link ) {

				// get the data link attribute
				$data_link = $link->{'data-link'};

				// check if we have a link to a map
				if ( 'map' == $data_link ) {
					
					$map_link = $this->map_link( $api_data['location_name'] );
					
					// set the href using map_link function
					$link->href = $map_link;

				} 

				// check if we have a link to the details page
				elseif ( 'detail' == $data_link ) {
					
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

				// default to use data link with node mapping
				else {

					// if the link has a value	
					if ( empty( $api_data[$data_link] ) ) {

						// set the href to null
						$link->href = null;

						$link->class = 'non-link';

						// set the tag to be a span
						$link->tag = 'span';

					}

					// we don't have a link
					else {

						// set the href to the value of the link
						$link->href = $api_data[$data_link];

						// reset the tag to 'a' - oddity with span declaration below
						$link->tag = 'a';

					}

				}

			}

		}

		/**
		 * Data Photos
		 * ===========
		 *
		 * Get all instances with attribute 'data-photo' and attach the value as a source.
		 * The size can be modified by using 'data-format' attribute and setting the value to:
		 * 	- tiny, small, medium, big, big_300
		 * 
		 * @param 	object 	$template 	the template object from get_template
		 * @param 	array 	$api_data 	the single api type array of data to get the value
		 * @param 	array 	$options 	the options passed from the api
		 * @return 	html 				returns the photo output to the $template object
		 */
		public function data_photos( $template, $api_data, $options ) {

			// find all data photo items
			$photos = $template->find('*[data-photo]');

			// loop through the data photos found
			foreach ( $photos as $photo ) {

				// get the photo value
				$photo_value = $api_data[$photo->{'data-photo'}];

				// format 
				$data_format = isset( $photo->{'data-format'} ) ? $photo->{'data-format'} : false;

				// check if we have an overwriting image size preference: tiny, small, medium, big, big_300
				if ( $data_format ) {
					
					$photo_value = str_replace( '/huge/', '/' . $data_format . '/', $photo_value );

				}

				$photo->src = $photo_value;

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
			$template_path_multiple = $api_data['template_options']['template_multiple'];

			// single items template path
			$template_path_single = $api_data['template_options']['template_single'];

			// if we have a single template path value and the api type is a single event
			if ( '' != $template_path_single && 'event' == $api_data['api']['type'] ) {

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
		 * Map Link
		 * ========
		 *
		 * @since 	1.0.0
		 * @access 	public
		 */
		public function map_link( $location_name ) {
			
			// array of HSC locations
			$hsc = 'BMT|BCC|CCC|CHP|CLB|CPT|CRL|CSA|CSB|CSC|DEI|DOH|EDM|EFC|EMP|HCC|HCT|HMR|HRA|HSV|IRD|KAM|LRA|LRB|MCH|MOL|MMR|NML|NOR|NRT|NTT|PAV|PGD|PGF|PGT|PGV|PHH|PMB|PSC|RMR|RSC|SRH|SSB|TOW|TRC|UNH|VBB|VWB|WOH|ZNI';

			// get the map code for patterns matching (ABC)
			preg_match( '/\(([A-Z]{3})\)/' , $location_name, $matches );

			// attach the map code to the map base link
			$map_link = 'http://web-app.usc.edu/maps/';

			// if we have a map code, attach it
			if ( $matches ) {

				$map_link .= '?b=' . $matches[1];

				// add the HSC tag if the map code is found 
				$map_link = preg_replace( '/\?b=('.$hsc.')/', '?b=$1#hsc', $map_link );

			}

			// attach the location name as a query
			else {

				$map_link .= '?q=' . $location_name;

			}

			// return the constructed map link
			return $map_link;

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
			else if ( isset( $api_data[$data_field] ) ) {

				$field_value = $api_data[$data_field];
			}

			// we have nothing so return false
			else {

				 return false;

			}

			return $field_value;

		}


	}

}