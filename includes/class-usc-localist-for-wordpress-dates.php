<?php

/**
 * Class: USC Localist for WordPress Dates
 *
 * Functions to validate and modify passed dates.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Dates' ) ) {

	class USC_Localist_For_Wordpress_Dates {

		/**
		 * Error message array.
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
		public function __construct() {

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// return the API configurations
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Valid Date
		 * ==========
		 *
		 * Check if the date passed matches the intended format.
		 *
		 * @param 	string 	$date 		date to pass for checking
		 * @param 	string 	$format 	format to check against date
		 * @return 	boolean
		 */
		public function valid_date( $date, $format = 'Y-m-d' ) {

			// returns new DateTime object formatted according to the specified format
			$date_format = DateTime::createFromFormat( $format, $date );

			// return boolean
			return $date_format && $date_format->format( $format ) === $date;
		}

		/**
		 * Fix Date
		 * ========
		 *
		 * Change a valid date format to a specified format.
		 *
		 * @param 	string 	$date 		valid date
		 * @param 	string 	$format 	format which to change the date
		 * @return 	string 				date in specified $format
		 */
		public function fix_date( $date, $format = 'Y-m-d' ) {

			// change the $date to $format and return
			$date_format = date( $format, strtotime( $date ) );
			return $date_format;

		}

		/**
		 * Dates Instance
		 * ==============
		 *
		 * Checks for API event instances and returns:
		 *  - 'end' node if end is set to true
		 *  - 'start' node if end is set to false (default)
		 *  - date string if is single date string
		 *
		 * @param  array  $dates 	array of date(s) to check against for the desired output
		 * @param  boolean $end 	boolean to check if there is an end date
		 * @return string			date as string
		 */
		public function get_date_instance( $event_instance, $data_type = 'start' ) {

			// Map to event data structure.
			$event_instance = $event_instance['event_instance'];

			// Default output.
			$output = false;

			if ( 'end' === $data_type && isset( $event_instance['end'] ) ) {
				$output = $event_instance['end'];
			}

			elseif ( isset( $event_instance['start'] ) ) {
				$output = $event_instance['start'];
			}

			return $output;

		}

		/**
		 * Date as HTML
		 * ============
		 *
		 * Pass a single event instance and send back HTML in appropriate <time> format.
		 *
		 * @param 	array	$event_instance 	single event instance
		 * @param 	array 	$options			options for output
		 *								 		[date-type, date-instance, format-date, format-time]
		 * @return 	string 						html string using <time> format
		 */
		public function date_as_html( $event_instance, $options ) {

			// config
			$config = $this->config;

			// set event mapping
			$event_instance = $event_instance['event_instance'];

			// defaults
			$date_check = true;
			$has_end_date = false;

			// set option defaults if not passed
			$date_type = isset( $options['date_type'] ) ? $options['date_type'] : 'date';
			$date_instance = isset( $options['date_instance'] ) ? $options['date_instance'] : 'start';
			$format_date = isset( $options['format_date'] ) ? $options['format_date'] : $config['default']['format_date'];
			$format_time = isset( $options['format_time'] ) ? $options['format_time'] : $config['default']['format_time'];
			$sep_date_time_single = isset( $options['separator_date_time_single'] ) ? $options['separator_date_time_single'] : $config['default']['separator']['date_time_single'];
			$sep_date_time_multi = isset( $options['separator_date_time_multiple'] ) ? $options['separator_date_time_multiple'] : $config['default']['separator']['date_time_multiple'];
			$separator_time = isset( $options['separator_time'] ) ? $options['separator_time'] : $config['default']['separator']['time'];

			// convert the string to a date
			$converted_date = strtotime( $event_instance[ $date_instance ] );

			// set var to check if is single event
			$is_single = ( 'event' === $options['api']['type'] ) ? true : false;

			// check for single events
			if ( $is_single ) {

				// set a date for checking against 'now'
				$date_event = new DateTime( $event_instance[ $date_instance ] );

				// if end is set
				if ( isset( $event_instance['end'] ) ) {

					$date_event = new DateTime( $event_instance['end'] );

					$has_end_date = true;

				}

				// set now date instance
				$date_now = new DateTime( 'now' );

				// set now date to midnight if event instance is 'all day'
				if ( $event_instance['all_day'] ) {

					$date_now = new DateTime( 'midnight' );

				}

				// set boolean to check if $date_now is less than or equal to $date_event
				$date_check = ( $date_now <= $date_event );

			}

			// do not show events before today
			if ( isset( $event_instance[ $date_instance ] ) && $date_check  ) {

				// default single date time separtaor
				$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_single . '</span>';

				// datetime separator outputs
				if ( empty( $sep_date_time_single ) || 'false' === $sep_date_time_single ) {
					$sep_date_time_output = '';
				}

				// has an end date
				if ( $has_end_date ) {

					// default multiple date time separator
					$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_multi . '</span>';

					if ( empty( $sep_date_time_multi ) || 'false' === $sep_date_time_multi ) {

						$sep_date_time_output = '';

					}
				}
				// start date only
				else {

					if ( empty( $sep_date_time_single ) || 'false' === $sep_date_time_single ) {

						$sep_date_time_output = '';

					} else {

						$sep_date_time_output = '<span class="event-separator-datetime">' . $sep_date_time_single . '</span>';

					}

				}

				//////

				// time separator outputs
				if ( empty( $separator_time ) || 'false' === $separator_time ) {
					$sep_time_output = '';
				}
				else {
					$sep_time_output = '<span class="event-separator-time">' . $separator_time . '</span>';
				}

				// use the date type selected
				switch ( $date_type ) {

					case 'date':

						$date = date( $format_date, $converted_date );
						return '<time class="event-' . $date_type . '-' . $date_instance . '" datetime="' . $event_instance[ $date_instance ] . '">' . $date . '</time>';

						break;

					case 'time':

						$time = date( $format_time, $converted_date );
						return '<time class="event-' . $date_type . '-' . $date_instance . '">' . $time . '</time>';

						break;

					case 'datetime-start-end':

						// default output options
						$time_end_output = '';

						// convert date to date format
						$date = date( $format_date, $converted_date );

						// convert start time to time format
						$time_start = date( $format_time, strtotime( $event_instance['start'] ) );

						// check if there is an end time
						if ( isset( $event_instance['end'] ) ) {

							$time_end_format = date( $format_time, strtotime( $event_instance['end'] ) );
							$time_end_output = $sep_time_output . '<span class="event-time-end">' . $time_end_format . '</span>';

						}

						return '<time class="event-' . $date_type . '" datetime="' . $event_instance[ $date_instance ] . '">'
							. '<span class="event-date-start">' . $date . '</span>'
							. $sep_date_time_output
							. '<span class="event-time-start">' . $time_start . '</span>'
							. $time_end_output
							. '</time>';

						break;

					default:

						// convert date to date format
						$date = date( $format_date, $converted_date );

						// convert time to time format
						$time = date( $format_time, $converted_date );

						return '<time class="event-' . $date_type . '-' . $date_instance . '" datetime="' . $event_instance[ $date_instance ] . '">'
							. '<span class="event-date">' . $date . '</span>'
							. $sep_date_time_output
							. '<span class="event-time">' . $time . '</span>'
							. '</time>';

						break;
				}
			}
			else {

				return false;

			}

		}

	}

}
