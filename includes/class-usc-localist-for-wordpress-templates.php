<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Templates' ) ) {

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
	class USC_Localist_For_Wordpress_Templates {

		/**
		 * The array of api data.
		 *
		 * @var array
		 */
		protected $api_data;

		/**
		 * Configuration variable
		 *
		 * @var string
		 */
		private $config;

		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 *
		 * @param array $api_data   Array of the API data to use in this class.
		 */
		public function __construct( $api_data ) {

			// Get the template path opton.
			$this->api_data = $api_data;

			// Load the dependencies.
			$this->load_dependencies();

			// Retrun the API configurations.
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions-simple-html-dom.php';

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

		}

		/**
		 * Check if the $url is valid.
		 *
		 * @param  string $url URL to check if valid.
		 * @return bool
		 */
		function valid_url( $url ) {

			$headers = get_headers( $url );
			$http_status = intval( substr( $headers[0], 9, 3 ) );

			if ( $http_status < 300 ) {

				return true;

			}

			return false;

		}

		/**
		 * Check if a value is empty and return $true or $false values and pass through $urlencode option.
		 *
		 * @since   1.3.0
		 * @param   string $check 		Value to check.
		 * @param   string $true 		Value to return if $check not empty.
		 * @param   string $false 		Value to return if $check empty.
		 * @param   bool   $urlencode 	Bool if return value should pass through urlencode.
		 * @return  string 				Return value.
		 */
		public function check_empty_value( $check, $true, $false, $urlencode ) {

			$value = ! empty( $check ) ? $true : $false;

			if ( $urlencode ) {
				$value = urlencode( $value );
			}

			return $value;
		}

		/**
		 * Check if a value is set and return $true or $false values.
		 *
		 * @since   1.3.0
		 * @param   string $check 		Value to check.
		 * @param   string $true 		Value to return if $check not empty.
		 * @param   string $false 		Value to return if $check empty.
		 * @return  string 				Return value.
		 */
		public function check_isset_value( $check, $true, $false ) {

			// Check if the value is set but also doesn't equal false.
			if ( isset( $check ) && false !== $check ) {

				// Return the true value.
				return $true;

			}

			// Return the false [default] value.
			return $false;
		}

		/**
		 * Get Template items with attribute data-datetime and set the inner text with the value of the mapped nodes.
		 *
		 * @param 	object $template 	The template object.
		 * @param 	array  $api_data 	The json array of the api data to use.
		 * @param 	array  $options 	The options of the api call passed for any call specific functions.
		 * @return 	void 				The output of matching node values as the inner text of the template item.
		 */
		public function data_datetime( $template, $api_data, $options ) {

			// Config setting.
			$config = $this->config;

			// Find all data fields.
			$fields = $template->find( '*[data-date-type]' );

			// Set variable if we have a single event.
			$is_single = ( 'event' === $options['api']['type'] ) ? true : false;

			// Loop through the data fields found.
			foreach ( $fields as $field ) {

				// Set variables for data-fields and output.
				$output = '';

				// Date types (date, time, datetime).
				if ( $is_single ) {
					$options['date_type'] = $this->check_isset_value( $field->{'data-date-type'}, $field->{'data-date-type'}, 'datetime' );
				}

				if ( ! $is_single ) {
					$options['date_type'] = $this->check_isset_value( $field->{'data-date-type'}, $field->{'data-date-type'}, 'date' );
				}

				// Specific date instance to use (start, end, datetime-start-end).
				$options['date_instance'] = $this->check_isset_value( $field->{'data-date-instance'}, $field->{'data-date-instance'}, 'start' );

				// Data format for dates.
				$options['format_date'] = $this->check_isset_value( $field->{'data-format-date'}, $field->{'data-format-date'}, $config['default']['format_date'] );

				// Data format for times.
				$options['format_time'] = $this->check_isset_value( $field->{'data-format-time'}, $field->{'data-format-time'}, $config['default']['format_time'] );

				// Date and time separator for events only with start date.
				$options['separator_date_time_single'] = $this->check_isset_value( $field->{'data-separator-date-time'}, $field->{'data-separator-date-time'}, $config['default']['separator']['date_time_single'] );

				// Date and time separator for events with start and end dates.
				$options['separator_date_time_multiple'] = $this->check_isset_value( $field->{'data-separator-date-time-multiple'}, $field->{'data-separator-date-time-multiple'}, $config['default']['separator']['date_time_multiple'] );

				// Time separator.
				$options['separator_time'] = $this->check_isset_value( $field->{'data-separator-time'}, $field->{'data-separator-time'}, $config['default']['separator']['time'] );

				// Range separator.
				$options['separator_range'] = $this->check_isset_value( $field->{'data-separator-range'}, $field->{'data-separator-range'}, $config['default']['separator']['range'] );

				// Separator to use between instances output.
				$separator = $this->check_isset_value( $field->{'data-separator'}, $field->{'data-separator'}, $config['default']['separator']['default'] );

				// Date ranges.
				$date_range = $this->check_isset_value( $options['template_options']['date_range'], $options['template_options']['date_range'], false );

				$date_start = date( $options['format_date'], strtotime( $api_data['first_date'] ) );
				$date_end = date( $options['format_date'], strtotime( $api_data['last_date'] ) );

				// Return the date range if set and not on single event.
				if ( ! $is_single && $date_range && ( $date_start !== $date_end ) ) {

					$date_start = date( $options['format_date'], strtotime( $api_data['first_date'] ) );
					$date_end = date( $options['format_date'], strtotime( $api_data['last_date'] ) );

					if ( $date_start !== $date_end ) {
						$output .= '<time class="event-date-range-start" datetime="' . $api_data['first_date'] . '">' . $date_start . '</time>';
						$output .= '<span class="event-separator-range">' . $options['separator_range'] . '</span>';
						$output .= '<time class="event-date-range-end" datetime="' . $api_data['last_date'] . '">' . $date_end . '</time>';
						$field->innertext = $output;
					}
				}

				// We are on a single page, or no date range, or the start does not equal the end date.
				if ( $is_single || ! $date_range || ( $date_start === $date_end ) ) {

					// Get the event instance(s).
					$event_instances = $api_data['event_instances'];
					$event_instances_amt = count( $event_instances );

					// Defaults for determining number in loop.
					$i = 1;

					foreach ( $event_instances as $event_instance ) {

						// New date class object.
						$date_functions = new USC_Localist_For_Wordpress_Dates;
						$date_output = $date_functions->date_as_html( $event_instance, $options );

						if ( $date_output ) {

							// Get the formatted date/time element.
							$output .= $date_output;

							// Add the separator if set.
							if ( isset( $separator ) && $i < $event_instances_amt ) {
								$output .= $separator;
							}
						}

						// Attach the formatted date/time element to the field.
						$field->innertext = $output;

						// Increase the number for event instances.
						$i++;
					}
				}
			} // End foreach().
		}

		/**
		 * Process the individual 'data-field' values from the data_fields and data_link function loops.
		 *
		 * @param  object $field 		The data field to be processed.
		 * @param  array  $api_data 	The json array of the api data to use.
		 * @return string				The output of matching node values as the inner text of the template item.
		 */
		public function data_fields_value( $field, $api_data ) {

			// Set variables for data-fields.
			$field_value = '';

			// Get the data-field.
			$data_field = $field->{'data-field'};

			// Get the value from the string format mapped node.
			$field_value = $this->string_node( $api_data, $data_field );

			// Check that we do not have an array for a field value.
			if ( is_array( $field_value ) ) {

				$output = array();
				foreach ( $field_value as $key => $value ) {
					if ( is_array( $value ) ) {
						return 'data-field: "' . $data_field . '" is a nested array. Please reference the help section for different data types.';
					}

					$output[] = $value;
				}

				$field_value = implode( ', ', $output );

			}

			// Add field value as innertext of the node.
			return $field_value;

		}

		/**
		 * Get Template items with attribute 'data-field' and set the inner text with the value of the mapped node.
		 *
		 * @param 	object $template 	The template object.
		 * @param 	array  $api_data 	The json array of the api data to use.
		 * @return 	void 				Set the output of matching node values as the inner text of the template item.
		 */
		public function data_fields( $template, $api_data ) {

			// Find all data fields.
			$fields = $template->find( '*[data-field]' );

			// Loop through the data fields found.
			foreach ( $fields as $field ) {

				$field_value = $this->data_fields_value( $field, $api_data );

				$field->innertext = $field_value;

			}

		}

		/**
		 * Sets the data link to be null and change the <a> tag to span.
		 *
		 * Simple HTML DOM has a recursive issue and this function helps set items.
		 *
		 * @since 1.1.7
		 *
		 * @param  object $link 	The single html node object.
		 * @return void 		 	Sets the html node object attributes.
		 */
		public function data_link_null( $link ) {

			// Remove the href attribute.
			$link->href = null;

			// Change the a tag to a span.
			$link->tag = 'span';

		}

		/**
		 * Sets the data link to be an <a> tag and attaches the url as the href.
		 *
		 * Simple HTML DOM has a recursive issue and this function helps set items.
		 *
		 * @since 1.1.7
		 *
		 * @param  object $link 	The single html node object.
		 * @param  string $url 		The url to set the a tag href.
		 * @return void 		 	Sets the html node object attributes.
		 */
		public function data_link_reset( $link, $url ) {

			// Set the href to the url.
			$link->href = $url;

			// Reset the tag to 'a' - oddity with span declaration using simple html dom.
			$link->tag = 'a';

		}

		/**
		 * Loop through all instances that have data attribute 'data-link'.
		 *
		 * @param 	object $template 	The template object from get_template.
		 * @param 	array  $api_data 	The single api type array of data to map the link value.
		 * @param 	array  $options 	The options passed from the api.
		 * @return 	void 				Sets the links output to the $template object.
		 */
		public function data_links( $template, $api_data, $options ) {

			// Defaults.
			$details_page = $options['template_options']['details_page'];

			// Find all data links.
			$links = $template->find( '*[data-link]' );

			// Loop through the links.
			foreach ( $links as $link ) {

				// Get the data link attribute.
				$data_link = $link->{'data-link'};

				switch ( $data_link ) {
					case 'map':

						$url = $this->map_link( $api_data['location_name'], $api_data['address'], $api_data['geo'] );

						// Set the href using map_link function.
						if ( ! empty( $url ) ) {

							$this->data_link_reset( $link, $url );

						}

						if ( empty( $url ) ) {
							$this->data_link_null( $link );
						}

						break;

					case 'detail':

						// Default: link to the localist details page.
						$url = $api_data['localist_url'];

						// Check if we have a set details page link.
						if ( ! empty( $details_page ) ) {

							// Attach the api_data url parameter to the link.
							$url = $details_page . '?event-id=' . $api_data['id'];

						}

						$this->data_link_reset( $link, $url );

						break;

					default:

						// If the link node has no value.
						if ( empty( $api_data[ $data_link ] ) ) {

							$this->data_link_null( $link );
						}

						// Default: We have a link so let's use it.
						if ( ! empty( $api_data[ $data_link ] ) ) {
							$this->data_link_reset( $link, $api_data[ $data_link ] );
						}

						break;
				}  // End switch().
			} // End foreach().

		}

		/**
		 * Get all instances with attribute 'data-photo' and attach the value as a source.
		 * The size can be modified by using 'data-format' attribute and setting the value to:
		 * 	- tiny, small, medium, big, big_300
		 *
		 * @param 	object $template 	The template object from get_template.
		 * @param 	array  $api_data 	The single api type array of data to get the value.
		 * @return 	void 				Sets the photo output to the $template object.
		 */
		public function data_photos( $template, $api_data ) {

			// Find all data photo items.
			$photos = $template->find( '*[data-photo]' );

			// Loop through the data photos found.
			foreach ( $photos as $photo ) {

				// Get the photo value.
				$photo_value = $api_data[ $photo->{'data-photo'} ];

				// Format.
				$data_format = isset( $photo->{'data-format'} ) ? $photo->{'data-format'} : false;

				// Check if we have an overwriting image size preference: tiny, small, medium, big, big_300.
				if ( $data_format ) {

					$photo_value = str_replace( '/huge/', '/' . $data_format . '/', $photo_value );

				}

				$photo->src = $photo_value;

			}

		}

		/**
		 * Get the template based on the passed parameter.
		 *
		 * @since 	1.0.0
		 *
		 * @param array $api_data 	The single api type array of data to get the value.
		 */
		public function get_template( $api_data ) {

			// Default template path.
			$default_template = plugin_dir_path( dirname( __FILE__ ) ) . $this->config['default']['templates']['multiple'];

			// Multiple items template path.
			$template_path_multi = $api_data['template_options']['template_multiple'];

			// Single items template path.
			$template_path_single = $api_data['template_options']['template_single'];

			// Get the mulitple events template as the default.
			$template_path = $template_path_multi;

			// If we have a single template path value and the api type is a single event.
			if ( '' !== $template_path_single && 'event' === $api_data['api']['type'] ) {

				// Set the template to the user supplied event.
				$template_path = $template_path_single;

				// Set the default template to the single event template.
				$default_template = plugin_dir_path( dirname( __FILE__ ) ) . $this->config['default']['templates']['single'];

			}

			// If the template location is at http.
			if ( strpos( $template_path, 'http' ) === 0 ) {

				// Check that we have a valid url.
				$valid_template = $this->valid_url( $template_path );

				// Check if we have a valid user passed template.
				if ( ! $valid_template ) {

					return file_get_html( $default_template );

				}

				$html = file_get_contents( $template_path );

				return file_get_html( $html );
			}

			// If the template is an HTML file.
			if ( strpos( $template_path, '.html' ) ) {

				// If the template is in the templates directory as file.
				return file_get_html( $default_template );

			}

			/**
			 * Default: Use the custom post type.
			 */

			// Set $html to the default template.
			$html = $default_template;

			// Get the post by slug name.
			$template_post = get_posts( array(
				'name' => $template_path,
				'posts_per_page' => 1,
				'post_type' => 'event-template',
				'post_status' => 'publish',
			) );

			if ( $template_post ) {

				// We have a CPT Template, reset $html to it's content.
				$html = $template_post[0]->post_content;

			}

			// Add missing custom post type template message.
			if ( empty( $template_post ) ) {

				$html = 'Custom template \'' . $template_path . '\' not found.<br>';

			}

			// If we have valid html returned.
			if ( ! empty( $html ) ) {

				if ( ! strpos( '<html>', ' ' . $html ) ) {

					$html = '<html>' . $html . '</html>';

				}

				return str_get_html( $html );

			}

		}

		/**
		 * Return a link to USC Maps for HSC or UPC, fall back to address in google maps or return false.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $location_name 	Location name in three letter campus location.
		 * @param  string $address 			Address node for use with google maps.
		 * @param  array  $geo				Array of [geo] location data.
		 * @return string 					Returns link value or boolean false.
		 */
		public function map_link( $location_name, $address, $geo ) {

			// Config setting.
			$config = $this->config;

			// Array of HSC locations.
			$hsc = 'BMT|BCC|CCC|CHP|CLB|CPT|CRL|CSA|CSB|CSC|DEI|DOH|EDM|EFC|EMP|HCC|HCT|HMR|HRA|HSV|IRD|KAM|LRA|LRB|MCH|MOL|MMR|NML|NOR|NRT|NTT|PAV|PGD|PGF|PGT|PGV|PHH|PMB|PSC|RMR|RSC|SRH|SSB|TOW|TRC|UNH|VBB|VWB|WOH|ZNI';

			// Get the map code for patterns matching (ABC), assign $matches to results found.
			preg_match( '/\(([A-Z]{3})\)/' , $location_name, $matches );

			// Attach the map code to the map base link.
			$usc_map_link = 'http://web-app.usc.edu/maps/';

			// Google maps base link.
			$google_maps = $config['url']['google_maps'];

			// If we have a map code, attach it.
			if ( $matches ) {

				// Set.
				$map_link = $usc_map_link . '?b=' . $matches[1];

				// Add the HSC tag if the map code is found.
				$map_link = preg_replace( '/\?b=(' . $hsc . ')/', '?b=$1#hsc', $map_link );

				return $map_link;

			}

			if ( ! empty( $geo['street'] ) || ! empty( $geo['city'] ) || ! empty( $geo['state'] ) ) {

				/**
				 * Set the link to google map based on geo location.
				 */

				// Reset base to google maps.
				$map_link = $google_maps;

				// Add the street if it exists.
				$map_link .= $this->check_empty_value( $geo['street'], $geo['street'] . ', ', '', true );

				// Add the city if it exists.
				$map_link .= $this->check_empty_value( $geo['city'], $geo['city'] . ', ', '', true );

				// Add the state if it exists.
				$map_link .= $this->check_empty_value( $geo['state'], $geo['state'], '', true );

				return $map_link;

			}

			if ( ! empty( $geo['latitude'] ) && ! empty( $geo['longitude'] )  ) {

				// Set the link to google map based on latitude/longitude.
				$map_link = $google_maps . $geo['latitude'] . ',' . $geo['longitude'];

				return $map_link;

			}

			if ( ! empty( $location_name ) ) {

				// Attach the location name as a query.
				$map_link = $usc_map_link . '?q=' . $location_name;

				return $map_link;

			}

			if ( ! empty( $address ) ) {

				// Set the link to a google map based on address.
				$map_link = 'https://www.google.com/maps/place/' . urlencode( $address );

				return $map_link;
			}

			// Default.
			return '';

		}

		/**
		 * String Node
		 * ===========
		 *
		 * Parses a dot syntax string into an associative array.
		 *
		 * some.data.type becomes $api_data['some']['data']['type']
		 *
		 * @param 	array  $api_data 	Single item array of api data node (i.e. - event).
		 * @param 	string $data_field 	The data field to check against.
		 * @return 	array 				An array (or single) value of converted dot syntax.
		 */
		public function string_node( $api_data, $data_field ) {

			// Multiple node data field.
			if ( strpos( $data_field, '.' ) ) {

				// Convert dot path to array.
				$paths = explode( '.', $data_field );

				// Set node to add array items as $event[node1][node2].
				$node =& $api_data;

				// Loop through the array items.
				foreach ( $paths as $path ) {

					// Check if the item exists.
					if ( array_key_exists( $path, $node ) ) {

						$node =& $node[ $path ];

					}
				}

				return $node;

			}

			if ( isset( $api_data[ $data_field ] ) ) {

				// Single node data field.
				return $api_data[ $data_field ];

			}

			// We have nothing so return false.
			return false;

		}


	}

} // End if().
