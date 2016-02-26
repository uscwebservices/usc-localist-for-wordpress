<?php

/**
 * Class: USC Localist for WordPress Paginate
 * 
 * A class to handle pagination.  Accepts JSON array
 * and checks template options for output format.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Paginate' ) ) {

	class USC_Localist_For_Wordpress_Paginate {

		/**
		 * The array API data and options.
		 * @var array
		 */
		protected $api_data;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct() {

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

		}


		public function get_pagination( $api_data ) {

			// map to the page option returned in the api
			$api_page = $api_data['api']['data']['page'];
			
			// check that the page variable is set
			if ( isset( $api_page ) ) {

				$page_total = $api_page['total'];
				$page_current = $api_data['api']['page_current'];

				// check for first and last pages out of total available
				$is_last_page = ( $page_current == intval($page_total) ) ? true : false;
				$is_first_page = ( $page_current == 1 ) ? true : false;

			}

			// we don't have a pagination options
			else {

				return false;

			}
			// var_dump($api_data['api']);

		}

	}

}