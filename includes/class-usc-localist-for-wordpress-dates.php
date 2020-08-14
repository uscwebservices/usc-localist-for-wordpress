<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author     USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_WordPress_Dates' ) ) {

	/**
	 * Class: USC Localist for WordPress Dates
	 *
	 * Functions to validate and modify passed dates.
	 *
	 * @since 		1.0.0
	 */
	class USC_Localist_For_WordPress_Dates {

		/**
		 * Error message array.
		 *
		 * @var string
		 */
		private $config;

		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Return the API configurations.
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Check if the date passed matches the intended format.
		 *
		 * @param 	string $date 	Date to pass for checking.
		 * @param 	string $format 	Format to check against date.
		 * @return 	boolean
		 */
		public function valid_date( $date, $format = 'Y-m-d' ) {

			// Set a new DateTime Object.
			$date_object = new DateTime;

			// Returns new DateTime object formatted according to the specified format.
			$date_format = $date_object->createFromFormat( $format, $date );

			// Return boolean.
			return $date_format && $date_format->format( $format ) === $date;
		}

		/**
		 * Set timezone to PST
		 *
		 * @deprecated		1.4.6
		 * @return object 	DateTimeZone set to PST
		 */
		public function set_PST() {
			return new DateTimeZone( 'America/Los_Angeles' );
		}

		/**
		 * Set timezone to UTC
		 *
		 * @deprecated		1.4.7
		 * @return object 	DateTimeZone set to UTC
		 */
		public function set_UTC() {
			return new DateTimeZone( 'UTC' );
		}

		/**
		 * Change a valid date format to a specified format.
		 *
		 * @since 	1.0.0
		 * @since 	1.4.6 			Removed PST specific and added UTC option,
		 * @since	1.4.7			Add set_UTC method for timezone as Date object.
		 * @param 	string $date 	Valid date.
		 * @param	bool   $utc 	Set date to UTC or not
		 * @param 	string $format 	Format which to change the date.
		 * @return 	string 			Date in specified $format.
		 */
		public function fix_date( $date, $utc = false, $format = 'Y-m-d' ) {

			// Change the $date to $format with timezone set in admin and return.
			$date_format = wp_date( $format, strtotime( $date ) ); // works events only

			// Sometimes we need UTC time, like calling the API we don't want a conversion of 'today' to be a different date.
			if ( true === $utc ) {
				$date_format = wp_date( $format, strtotime( $date ), $this->set_UTC() );
			}

			return $date_format;

		}

		/**
		 * Checks for API event instances and returns:
		 *  - 'end' node if end is set to true
		 *  - 'start' node if end is set to false (default)
		 *  - date string if is single date string
		 *
		 * @param  array  $event_instance 	Array of single event instance to check against for the desired output.
		 * @param  string $data_type 		The date type to output if available [start, end].
		 * @return string					Date insance as a string.
		 */
		public function get_date_instance( $event_instance, $data_type = 'start' ) {

			// Map to event data structure.
			$event_instance = $event_instance['event_instance'];

			// Default output.
			$output = false;

			// Check what type of date is requested and if the instance has a value.
			if ( 'end' === $data_type && isset( $event_instance['end'] ) ) {
				$output = $event_instance['end'];
			} elseif ( isset( $event_instance['start'] ) ) {
				$output = $event_instance['start'];
			}

			return $output;

		}

		/**
		 * Pass a single event instance and send back HTML in appropriate <time> format.
		 *
		 * @param 	array $event_instance 	Single event instance.
		 * @param 	array $options			Options for output [date-type, date-instance, format-date, format-time].
		 * @return 	string 						html string using <time> format
		 */
		public function date_as_html( $event_instance, $options ) {

			// Config.
			$config = $this->config;

			// Set event mapping.
			$event_instance = $event_instance['event_instance'];

			// Defaults.
			$date_check = true;
			$has_end_date = false;

			// Set option defaults if not passed.
			$date_type            = isset( $options['date_type'] ) ? $options['date_type'] : 'date';
			$date_instance        = isset( $options['date_instance'] ) ? $options['date_instance'] : 'start';
			$format_date          = isset( $options['format_date'] ) ? $options['format_date'] : $config['default']['format_date'];
			$format_time          = isset( $options['format_time'] ) ? $options['format_time'] : $config['default']['format_time'];
			$sep_date_time_single = isset( $options['separator_date_time_single'] ) ? $options['separator_date_time_single'] : $config['default']['separator']['date_time_single'];
			$sep_date_time_multi  = isset( $options['separator_date_time_multiple'] ) ? $options['separator_date_time_multiple'] : $config['default']['separator']['date_time_multiple'];
			$separator_time       = isset( $options['separator_time'] ) ? $options['separator_time'] : $config['default']['separator']['time'];

			// Convert the string to a date.
			$converted_date = $this->fix_date( $event_instance[ $date_instance ], false, $format_date );

			// Convert the string to a time.
			$converted_time = $this->fix_date( $event_instance[ $date_instance ], false, $format_time );

			// Check for single events.
			if ( 'event' === $options['api']['type'] ) {

				// Set a date for checking against 'now'.
				$date_event = new DateTime( $event_instance[ $date_instance ] );

				// If end is set.
				if ( isset( $event_instance['end'] ) ) {

					$date_event = new DateTime( $event_instance['end'] );

					$has_end_date = true;

				}

				// Set now date instance.
				$date_now = new DateTime( 'now' );

				// Set now date to midnight if event instance is 'all day'.
				if ( $event_instance['all_day'] ) {

					$date_now = new DateTime( 'midnight' );

				}

				// Set boolean to check if $date_now is less than or equal to $date_event.
				$date_check = ( $date_now <= $date_event );

			}

			// Do not show events before today.
			if ( isset( $event_instance[ $date_instance ] ) && $date_check  ) {

				// Default single date time separtaor.
				$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_single . '</span>';

				// Date time separator outputs.
				if ( empty( $sep_date_time_single ) || 'false' === $sep_date_time_single ) {
					$sep_date_time_output = '';
				}

				// Has an end date.
				if ( $has_end_date ) {

					// Default multiple date time separator.
					$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_multi . '</span>';

					if ( empty( $sep_date_time_multi ) || 'false' === $sep_date_time_multi ) {

						$sep_date_time_output = '';

					}
				}

				// Start date only.
				if ( ! $has_end_date ) {

					// Default date time output.
					$sep_date_time_output = '';

					if ( ! empty( $sep_date_time_single ) || 'false' !== $sep_date_time_single ) {

						$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_single . '</span>';

					}
				}

				// Default Time separator output.
				$sep_time_output = '';

				// User specified Time separator outputs.
				if ( ! empty( $separator_time ) || 'false' !== $separator_time ) {
					$sep_time_output = '<span class="event-separator-time">' . $separator_time . '</span>';
				}

				// Use the date type selected.
				switch ( $date_type ) {

					case 'date':

						return '<time class="event-' . $date_type . '-' . $date_instance . '" datetime="' . $event_instance[ $date_instance ] . '">' . $converted_date . '</time>';

						break;

					case 'time':

						return '<time class="event-' . $date_type . '-' . $date_instance . '">' . $converted_time . '</time>';

						break;

					case 'datetime-start-end':

						// Default output options.
						$time_end_output = '';

						// Convert start time to time format.
						$time_start = $this->fix_date( $event_instance['start'], false, $format_time );

						// Check if there is an end time.
						if ( isset( $event_instance['end'] ) ) {

							$time_end_format = $this->fix_date( $event_instance['end'], false, $format_time );
							$time_end_output = $sep_time_output . '<span class="event-time-end">' . $time_end_format . '</span>';

						}

						return '<time class="event-' . $date_type . '" datetime="' . $event_instance[ $date_instance ] . '">'
							. '<span class="event-date-start">' . $converted_date . '</span>'
							. $sep_date_time_output
							. '<span class="event-time-start">' . $time_start . '</span>'
							. $time_end_output
							. '</time>';

						break;

					default:

						// Convert date to date format.
						$date = date( $format_date, $converted_date );

						// Convert time to time format.
						$time = date( $format_time, $converted_date );

						return '<time class="event-' . $date_type . '-' . $date_instance . '" datetime="' . $event_instance[ $date_instance ] . '">'
							. '<span class="event-date">' . $converted_date . '</span>'
							. $sep_date_time_output
							. '<span class="event-time">' . $converted_time . '</span>'
							. '</time>';

						break;
				}
			}

			// Default if no other conditions met.
			return false;

		}

	}

}
