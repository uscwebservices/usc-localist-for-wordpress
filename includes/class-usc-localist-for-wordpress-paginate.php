<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Paginate' ) ) {
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
	class USC_Localist_For_Wordpress_Paginate {

		/**
		 * The array API data and options.
		 *
		 * @var array
		 */
		protected $api_data;

		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->load_dependencies();

		}

		/**
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

		}

		/**
		 * Get the pagination for the mulitple instances output.
		 *
		 * @param   array $api_data   Array of the API data to use in this class.
		 * @return  string            Pagination as HTML output (if specified).
		 */
		public function get_pagination( $api_data ) {

			// Check that the page variable is set.
			if ( isset( $api_data['api']['data']['page'] ) ) {

				$options = $api_data['paginate_options'];

				// Set defaults if values not passed.
				$paginate_type 				= isset( $options['paginate_type'] ) ? $options['paginate_type'] : 'next';

				// Check if paginate type is next.
				$is_next = ( 'next' === $paginate_type ) ? true : false;

				// Check if paginate type is numeric.
				$is_numeric = ( 'numeric' === $paginate_type ) ? true : false;

				$paginate_offset 			= isset( $options['paginate_offset'] ) ? $options['paginate_offset'] : 3;
				$paginate_numeric_sep 		= isset( $options['paginate_numeric_separator'] ) ? $options['paginate_numeric_separator'] : ' ... ';
				$paginate_label_next 		= isset( $options['paginate_label_next'] ) ? $options['paginate_label_next'] : ( ( $is_next ) ? 'Next' : null );
				$paginate_label_prev 		= isset( $options['paginate_label_previous'] ) ? $options['paginate_label_previous'] : ( ( $is_next ) ? 'Previous' : null );
				$paginate_label_first 		= isset( $options['paginate_label_first'] ) ? $options['paginate_label_first'] : null;
				$paginate_label_last	 	= isset( $options['paginate_label_last'] ) ? $options['paginate_label_last'] : null;

				global $post_id;

				// Set default output.
				$output = '';

				// Get current and total pages.
				$page_uri = get_page_uri( $post_id );
				$page_total = $api_data['api']['data']['page']['total'];
				$page_current = $api_data['api']['page_current'];

				// Check for first and last pages out of total available.
				$is_last_page = ( intval( $page_total ) === $page_current ) ? true : false;
				$is_first_page = ( 1 === $page_current ) ? true : false;

				// Add 'first' label to event pages that are not the first and have a label set.
				if ( ! $is_first_page && ! is_null( $paginate_label_first ) ) {

					$output .= '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . 1 . '">' . $paginate_label_first . '</a>';

				}

				// Add 'previous' label to event pages that are not the first.
				if ( ! $is_first_page && ! is_null( $paginate_label_prev ) ) {

					$output .= '<a class="paginate paginate-prev" href="/' . $page_uri . '/' . ( $page_current - 1 ) . '">' . $paginate_label_prev . '</a>';

				}

				// If numeric is selected, add the numbers as a list.
				if ( $is_numeric ) {

					// Check that we have more than one page of results.
					if ( $page_total > 1 ) {

						// Add the first page to show the first.
						if ( $page_current > ( 1 + $paginate_offset ) ) {

							$output .= '<a class="paginate paginate-number first" href="/' . $page_uri . '/' . 1 . '">' . 1 . '</a>' . $paginate_numeric_sep;

						}

						for ( $i = 1; $i < ( $page_total + 1 ) ; $i++ ) {

							// Check if we are on the current page number.
							if ( $i === $page_current ) {

								$output .= '<span class="paginate paginate-number current">' . $i . '</span>';

							}

							// Check that we are within the pagination offset amount from the current page.
							if ( $i !== $page_current && $i >= ( $page_current - $paginate_offset ) && $i <= ( $page_current + $paginate_offset )  ) {

								$output .= '<a class="paginate paginate-number" href="/' . $page_uri . '/' . $i . '">' . $i . '</a>';

							}
						}

						// Add the last page to show how many pages we have.
						if ( $page_current < ( $page_total - $paginate_offset ) ) {

							$output .= $paginate_numeric_sep . '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . $page_total . '">' . $page_total . '</a>';

						}
					}
				} // End if().

				// Add 'next' label to event pages that are not the last.
				if ( ! $is_last_page && ! is_null( $paginate_label_next ) ) {

					$output .= '<a class="paginate paginate-next" href="/' . $page_uri . '/' . ( $page_current + 1 ) . '">' . $paginate_label_next . '</a>';

				}

				// Add 'first' label to event pages that are not the first and have a label set.
				if ( ! $is_last_page && ! is_null( $paginate_label_last ) ) {

					$output .= '<a class="paginate paginate-number last" href="/' . $page_uri . '/' . $page_total . '">' . $paginate_label_last . '</a>';

				}

				// Start the object collection.
				ob_start();

				// Output the html.
				echo $output;

				// Return the clean object.
				return ob_get_clean();
			} // End if().

			// We don't have a pagination options.
			return false;

		}

	}

} // End if().
