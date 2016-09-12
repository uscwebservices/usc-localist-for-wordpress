<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Events' ) ) {

	/**
	 * Class: USC Localist for WordPress Events
	 *
	 * A class to handle events displaying.  Accepts JSON array
	 * and checks template options for output format.
	 *
	 * @since 1.0.0
	 */
	class USC_Localist_For_Wordpress_Events {

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
		 *
		 * @param array $api_data   Array of the API data to use in this class.
		 */
		public function __construct( $api_data ) {

			$this->api_data = $api_data;

			$this->load_dependencies();

		}

		/**
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-templates.php';

			// Require the date class for date and time functions.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-dates.php';

			// Require the paginate class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-paginate.php';

			// Return the API configurations.
			$this->config = USC_Localist_For_Wordpress_Config::$config;

		}

		/**
		 * Get the template, apply to the event(s) and output.
		 *
		 * @since 	1.0.0
		 */
		public function get_events() {

			// Get the default config file.
			$config = $this->config;

			// Set default output.
			$output = '';

			// Get the template from the api_data.
			$template_data = new USC_Localist_For_Wordpress_Templates( $this->api_data );
			$template = $template_data->get_template( $this->api_data );

			// Local scope of api_data.
			$api_data = $this->api_data;

			// Get the events from the class api data (single event).
			$events = $this->api_data['api']['data'];

			// If we have 'events' (multiple events), map to that node.
			if ( isset( $events['events'] ) ) {
				$events = $events['events'];
			}

			// Check that we have actual events returned.
			if ( count( $events ) === 0 ) {

				$output .= '<p class="' . $config['default']['class']['no_events'] . '">';
				$output .= $api_data['template_options']['message_no_events'];
				$output .= '</p>';

			}

			// We have events.
			if ( count( $events ) !== 0 ) {

				// Loop through the events.
				foreach ( $events as $single ) {

					// Get to the single event node.
					$event = $single;

					// If we have sub 'event' (multiple events), map to that node.
					if ( isset( $single['event'] ) ) {
						$event = $single['event'];
					}

					// Get the data fields, pass the template, api data and options.
					$template_data->data_fields( $template, $event, $api_data );

					// Get the data datetime, pass the template, api data and options.
					$template_data->data_datetime( $template, $event, $api_data );

					// Get the data links, pass the template, api data and options.
					$template_data->data_links( $template, $event, $api_data );

					// Get the data links, pass the template, api data and options.
					$template_data->data_photos( $template, $event, $api_data );

					// Save the template.
					$template_output = $template->save();

					// Set the value to display in the output.
					$output .= str_replace( array( '<html>', '</html>' ), array( '', '' ), $template_output );

				}

				// Clear the template to prevent memory leak.
				$template->clear();
				unset( $template );

				// Get the paginate setting.
				$option_paginate = $this->api_data['paginate_options']['paginate'];

				// Only run pagination if true and on multiple events api.
				if ( $option_paginate && 'events' === $api_data['api']['type'] ) {

					$paginate = new USC_Localist_For_Wordpress_Paginate();

					$output .= $paginate->get_pagination( $api_data );

				}
			}

			// Return the output for the event(s).
			return $output;

		}

	}

}
