<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Errors' ) ) {

	/**
	 * Class: USC Localist for WordPress Errors
	 *
	 * Error messaging handling for functions and classes.
	 *
	 * Stores error messages as an array of the object and
	 * retrieves combined messages.
	 *
	 * @since 		1.0.0
	 */
	class USC_Localist_For_Wordpress_Errors {

		/**
		 * Error message array.
		 *
		 * @var string
		 */
		private $error_messages;

		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Set an array for error messages.
			$this->error_messages = array();

		}

		/**
		 * Add a message to the array.
		 *
		 * @since 1.0.0
		 * @param string $error_message  The error message to be added.
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
