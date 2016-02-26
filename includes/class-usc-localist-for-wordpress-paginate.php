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

			$options = $api_data['paginate_options'];

			// set defaults if values not passed
			$paginate 					= isset ( $options['paginate'] ) ? $options['paginate'] : 'next';
			$paginate_label_next 		= isset ( $options['paginate_label_next'] ) ? $options['paginate_label_next'] : 'Next';
			$paginate_label_previous 	= isset ( $options['paginate_label_previous'] ) ? $options['paginate_label_previous'] : 'Previous';

			
			// check that the page variable is set
			if ( isset( $api_page ) ) {

				global $post_id;

				// set default output
				$output = '';

				// get current and total pages
				$page_uri = get_page_uri($post_id);
				$page_total = $api_page['total'];
				$page_current = $api_data['api']['page_current'];

				// check for first and last pages out of total available
				$is_last_page = ( $page_current == intval($page_total) ) ? true : false;
				$is_first_page = ( $page_current == 1 ) ? true : false;

				// add 'previous' label to event pages that are not the first
				if ( ! $is_first_page ) {

					$output .= '<a href="/' . $page_uri . '/' . ( $page_current - 1 ) . '">' . $paginate_label_previous . '</a>';

				}

				// if numeric is selected, add the numbers as a list

				// add 'next' label to event pages that are not the last
				if ( ! $is_last_page ) {

					$output .= '<a href="/' . $page_uri . '/' . ( $page_current + 1 ) . '">' . $paginate_label_next . '</a>';

				}

				echo $output;

			}

			// we don't have a pagination options
			else {

				return false;

			}
			// var_dump($api_data['api']);

		}

	}

}