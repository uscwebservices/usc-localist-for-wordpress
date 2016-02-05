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
		 * The array of events API data.
		 * @var array
		 */
		protected $api_data;

		/**
		 * The array of events template options.
		 * @var array
		 */
		protected $template_options;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $api_data, $template_options ) {

			$this->api_data = $api_data;
			$this->template_options = $template_options;

		}

		/**
		 * Run
		 * ===
		 *
		 * Functions to perform when running the plugin.
		 *
		 * @since 	1.0.0
		 */
		public function run() {

			// run the loading functions for actions and filters
			
			// TODO: proceess the event(s) output using the api_data, template_options
			
			
		}

	}

}