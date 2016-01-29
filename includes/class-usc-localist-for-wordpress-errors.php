<?php

/**
 * Class: USC Localist for WordPress Errors
 *
 * Error messaging handling for functions and objects.
 *
 * @since      1.0.0
 *
 * @package    USC_Localist_for_WordPress
 * @subpackage USC_Localist_for_WordPress/includes
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Errors' ) ) {
	
	class USC_Localist_For_Wordpress_Errors {

		/**
		 * Error message array.
		 * @var string
		 */
		private $error_messages;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct() {

			// set an array for error messages
			$this->error_messages = array();

		}

		/**
		 * Add Message
		 * ===========
		 *
		 * Add a message to the array.
		 *
		 * @since 1.0.0
		 * @param 	string 	$error_messages 	the error message to be added
		 */
		public function add_message( $error_message ) {
			$this->error_messages[] = $error_message;
		}

		/**
		 * Get Messages
		 * ============
		 *
		 * Get the messages that are stored.
		 *
		 * @since 1.0.0
		 * @return array 	error messages as an array
		 */
		public function get_messages() {
			return $this->error_messages;
		}

	}

}