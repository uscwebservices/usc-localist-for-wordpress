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
			$paginate_type 				= isset ( $options['paginate_type'] ) ? $options['paginate_type'] : 'next';
			$paginate_offset 			= isset ( $options['paginate_offset'] ) ? $options['paginate_offset'] : 3;
			$paginate_numeric_separator = isset ( $options['paginate_numeric_separator'] ) ? $options['paginate_numeric_separator'] : ' -- ';
			$paginate_label_next 		= isset ( $options['paginate_label_next'] ) ? $options['paginate_label_next'] : null;
			$paginate_label_previous 	= isset ( $options['paginate_label_previous'] ) ? $options['paginate_label_previous'] : null;
			$paginate_label_first 		= isset ( $options['paginate_label_first'] ) ? $options['paginate_label_first'] : null;
			$paginate_label_last	 	= isset ( $options['paginate_label_last'] ) ? $options['paginate_label_last'] : null;

			// check if paginate type is numeric
			$is_numeric = ( $paginate_type == 'numeric' ) ? true : false;
			
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

				// add 'first' label to event pages that are not the first and have a label set
				if ( ! $is_first_page && ! is_null( $paginate_label_first ) ) {

					$output .= '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . 1 . '">' . $paginate_label_first . '</a>';

				}

				// add 'previous' label to event pages that are not the first
				if ( ! $is_first_page && ! is_null( $paginate_label_previous ) ) {

					$output .= '<a class="paginate paginate-prev" href="/' . $page_uri . '/' . ( $page_current - 1 ) . '">' . $paginate_label_previous . '</a>';

				}

				// if numeric is selected, add the numbers as a list
				if ( $is_numeric ) {

					// add the first page to show the first
					if ( $page_current > ( 1 + $paginate_offset ) ) {

						$output .= '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . 1 . '">' . 1 . '</a>' . $paginate_numeric_separator;

					}

					for ( $i=1; $i < $page_total ; $i++) { 
						
						// check if we are on the current page number
						if ( $i == $page_current ) {

							$output .= '<span class="paginate paginate-number current">' . $i . '</span>';

						} 

						// check that we are within the pagination offset amount from the current page
						else if ( $i >= ( $page_current - $paginate_offset ) && $i <= ( $page_current + $paginate_offset )  ) {

							$output .= '<a class="paginate paginate-number" href="/' . $page_uri . '/' . $i . '">' . $i . '</a>';

						}

					}

					// add the last page to show how many pages we have
					if ( $page_current < ( $page_total - $paginate_offset ) ) {

						$output .= $paginate_numeric_separator . '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . $page_total . '">' . $page_total . '</a>';

					}

				}

				// add 'next' label to event pages that are not the last
				if ( ! $is_last_page && ! is_null( $paginate_label_next ) ) {

					$output .= '<a class="paginate paginate-next" href="/' . $page_uri . '/' . ( $page_current + 1 ) . '">' . $paginate_label_next . '</a>';

				}

				// add 'first' label to event pages that are not the first and have a label set
				if ( ! $is_last_page && ! is_null( $paginate_label_last ) ) {

					$output .= '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . $page_total . '">' . $paginate_label_last . '</a>';

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