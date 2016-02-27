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

			// retrun the API configurations
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
		    $d = DateTime::createFromFormat( $format, $date );

		    // return boolean
		    return $d && $d->format( $format ) == $date;
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
			$d = date( $format, strtotime( $date ) );
			return $d;

		}

		/**
		 * Is Midnight
		 * ===========
		 *
		 * Check if the date passed is Midnight
		 *
		 * @param 	string 	$timestamp 	the date object to check against
		 * @return 	boolean
		 */
		public function is_midnight( $timestamp ) {
			
			// check if the hour and minute are set to '0'
			if( date( 'H', $timestamp ) == 0 && date( 'i', $timestamp ) == 0 ) {
				
				// it is midnight
				return true;
			
			} else {

				// it is not midnight
				return false;

			}

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
			
			// map to event data structure
			$event_instance = $event_instance['event_instance'];

			if ( $data_type == 'end' && isset ( $event_instance['end'] ) ) {
				$output = $event_instance['end'];
			} 

			else if ( isset ( $event_instance['start'] ) ) {
				$output = $event_instance['start'];
			}

			else {

				return false;
			}
			
			return $output;

		}

		/**
		 * Date as HTML
		 * ============
		 *
		 * Pass a single event instance and send back HTML in approriate <time> format.
		 * 
		 * @param 	array	$event_instance 	single event instance
		 * @param 	array 	$options        	options for output 
		 *                                 		[date-type, date-instance, format-date, format-time]
		 * @return 	string 						html string using <time> format
		 */
		public function date_as_html( $event_instance, $options ) {
			
			// config
			$config = $this->config;

			// set event mapping
			$event_instance = $event_instance['event_instance'];

			// set option defaults if not passed
			$date_type = isset( $options['date_type'] ) ? $options['date_type'] : 'date';
			$date_instance = isset( $options['date_instance'] ) ? $options['date_instance'] : 'start';
			$format_date = isset( $options['format_date'] ) ? $options['format_date'] : $config['default']['format_date'];
			$format_time = isset( $options['format_time'] ) ? $options['format_time'] : $config['default']['format_time'];

			// convert the string to a date
			$converted_date = strtotime( $event_instance[$date_instance] );

			// check defaults
			$date_check = true;

			$is_single = ( $options['api']['type'] == 'event') ? true : false;

			// check for single events
			if ( $is_single) {

				// set a date for checking against 'now'
				$date_event = new DateTime( $event_instance[$date_instance] );

				// if end is set
				if ( isset( $event_instance['end'] ) ) {

					$date_event = new DateTime( $event_instance['end'] );

				}

				// set now date instance
				$date_now = new DateTime('now');
				
				// set now date to midnight if event instance is 'all day'
				if ( $event_instance['all_day'] ) {

					$date_now = new DateTime('midnight');

				}

				// set boolean to check if $date_now is less than or equal to $date_event
				$date_check = $date_now <= $date_event;

			}

			// do not show events before today
			if ( isset( $event_instance[$date_instance] ) && $date_check  ) {

				// use the date type selected
				switch ( $date_type ) {
					
					case 'date':
						
						$date = date( $format_date, $converted_date );
						return '<time datetime="' . $event_instance[$date_instance] . '">' . $date . '</time>';

						break;
					
					case 'time':
						
						$time = date( $format_time, $converted_date );
						return '<time>' . $time . '</time>';

						break;
					
					case 'datetime-start-end':
						
						// default output options
						$time_end_output = '';

						$date = date( $format_date, $converted_date );
						$time_start = date( $format_time, strtotime( $event_instance['start'] ) );
						
						if ( isset( $event_instance['end'] ) ) {
							
							$time_end = date( $format_time, strtotime( $event_instance['end'] ) );
							$time_end_output = ' to ' . $time_end;

						}

						return '<time datetime="' . $event_instance[$date_instance] . '">' . $date . ' at ' . $time_start . $time_end_output . '</time>';
						
						break;

					default:
						
						$date = date( $format_date, $converted_date );
						$time = date( $format_time, $converted_date );
						return '<time datetime="' . $event_instance[$date_instance] . '">' . $date . ' at ' . $time . '</time>';
						
						break;
				}

			}

			else {

				return false;

			}

		}

		/**
		 * Simple Date Range
		 * =================
		 * @param 	string 	$date_start 	the start date
		 * @param 	string 	$date_end 		the end date
		 * @param 	string 	$format 		the php format to use for the returned date
		 * @param 	string 	$separator 		the separator between the two
		 * @return 	string 					the start date + separator + end date
		 */
		public function simple_date_range( $date_start, $date_end, $format = 'n/j/Y', $separator = ' - ' ) {

			$start = date( $format, strtotime( $date_start ) );
			$end = date( $format, strtotime( $date_end ) );

			return $start . $separator . $end;

		}

	}

}