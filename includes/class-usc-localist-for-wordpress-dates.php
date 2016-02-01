<?php

/**
 * Class: USC Localist for WordPress Dates
 *
 * Date functions and 
 *
 * @since      1.0.0
 *
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
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
		function is_midnight( $timestamp ) {
			
			// check if the hour and minute are set to '0'
			if( date('H',$timestamp) == 0 && date('i',$timestamp) == 0 ) {
				
				// it is midnight
				return true;
			
			} else {

				// it is not midnight
				return false;

			}

		}

	}

}