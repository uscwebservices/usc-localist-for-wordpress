<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Shortcode' ) ) {

	/**
	 * Class: USC Localist for WordPress Shortcode
	 *
	 * Add shortcode(s) for the site to get calendar data from Localist API.
	 *
	 * @since 		1.0.0
	 * @package 	Usc_Localist_For_Wordpress
	 * @subpackage 	Usc_Localist_For_Wordpress/includes
	 * @author 		USC Web Services <webhelp@usc.edu>
	 */
	class USC_Localist_For_Wordpress_Shortcode {

		/**
		 * Configuration variable.
		 *
		 * @access private
		 * @var string
		 */
		private $config;

		/**
		 * User friendly name used to identify the plugin.
		 *
		 * @access protected
		 * @var string
		 */
		protected $plugin_name;

		/**
		 * Current version of the plugin.  Set in plugin root @ usc-localist-for-wordpress.php
		 *
		 * @access protected
		 * @var string $plugin_version
		 */
		protected $plugin_version;

		/**
		 * Tag identifier used by file includes and selector attributes.
		 *
		 * @access protected
		 * @var string $plugin_tag
		 */
		protected $plugin_tag;

		/**
		 * Tag identifier used by shortcode generator for the calendar.
		 *
		 * @access protected
		 * @var string $plugin_shortcode_cal
		 */
		protected $plugin_shortcode_cal;

		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param  string $plugin_name     The plugin name.
		 * @param  string $plugin_version  The plugin version.
		 * @param  string $plugin_tag      The plugin tag.
		 */
		public function __construct( $plugin_name, $plugin_version, $plugin_tag ) {

			$this->plugin_name = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_tag = $plugin_tag;

			// Load dependencies for this class.
			$this->load_dependencies();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// Require the config class for API variables.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-config.php';

			// Require the api class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-api.php';

			// Require the api class.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-usc-localist-for-wordpress-events.php';

			// Return the API configurations.
			$this->config = USC_Localist_For_Wordpress_Config::$config;

			$this->plugin_shortcode_calendar = $this->config['plugin']['shortcode']['calendar'];

		}

		/**
		 * Add the shortcode for Localist Widget
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @require array $this->config
		 * @param 	string $params 	Shortcode api options.
		 * @return 	string 			Events list as HTML.
		 *
		 * @usage: [localist-events {option=value}]
		 */
		public function events_shortcode( $params ) {

			// Get the default config file.
			$config = $this->config;

			// Default setting for error checking, date_range, details_page.
			$errors = $api_output = $details_page = $date_range = false;

			// Default for api url and template options build.
			$api_url = $template_options = $paginate_options = array();

			$api_data = new USC_Localist_For_Wordpress_API;

			// Get all api options.
			$attr_all = shortcode_atts( $config['api_options']['all']['allowed'], $params, $this->plugin_shortcode_calendar );

			/** ---------------------------------------------------
				Get type
			--------------------------------------------------- */

			// Store the api type as a variable.
			$api_type = $attr_all['get'];

			// Check that we have a valid 'get' type.
			if ( empty( $api_type ) ) {

				// Let's default to events.
				$api_type = 'events';

			}

			// Set the get type for api url and options.
			$api_url['api']['type'] = $api_type;

			/** --------------------------------------------------
				Get flag for event inline in page
			--------------------------------------------------- */

			// Set variable.
			$api_is_events_page = $api_data->convert_to_bool( $attr_all['is_events_page'] );

			// Check that we have a valid 'cache' value.
			if ( $api_is_events_page ) {

				// Validate the cache value.
				$api_is_events_page = $api_data->validate_key_value( 'is_events_page', $api_is_events_page );

				// Store the cache number as part of the url array.
				$api_url['is_events_page'] = $api_is_events_page;

			}

			/** --------------------------------------------------
				Get cache
			--------------------------------------------------- */

			// Set transient cache expiration (in seconds).
			$api_cache = $attr_all['cache'];

			// Check that we have a valid 'cache' value.
			if ( $api_cache || '0' === $api_cache ) {

				// Validate the cache value.
				$api_cache = $api_data->validate_key_value( 'cache', $api_cache );

				// Store the cache number as part of the url array.
				$api_url['cache'] = $api_cache;

			}

			/** --------------------------------------------------
				Get page specified page number
			--------------------------------------------------*/

			// Get the paginate last label.
			$page = $attr_all['page'];

			if ( ! empty( $page ) ) {

				$api_url['page'] = $page;

			}

			/** --------------------------------------------------
				Get template option: multiple
			--------------------------------------------------- */

			// Set template path option: multiple.
			$template_path_multi = $attr_all['template_multiple'];

			// Set default template options.
			if ( empty( $template_path_multi ) ) {

				$template_path_multi = 'events-list.html';

			}

			// Set the template slug.
			$template_options['template_multiple'] = $template_path_multi;

			/** --------------------------------------------------
				Get template option: single
			--------------------------------------------------- */

			// Set template path option: single.
			$template_path_single = $attr_all['template_single'];

			// Set default template options.
			if ( empty( $template_path_single ) ) {

				$template_path_single = 'events-single.html';

			}

			// Set the template slug.
			$template_options['template_single'] = $template_path_single;

			/** --------------------------------------------------
				Get event details page option
			--------------------------------------------------- */

			// Details page from shortcode options.
			$shortcode_dtl_page = $attr_all['details_page'];

			// Details page from global options.
			$options_dtl_page_id = get_option( 'usc_lfwp_events_detail_page' );
			$options_dtl_page_uri = get_page_uri( $options_dtl_page_id );

			// First check that is_events_page isn't set to true.
			if ( $api_is_events_page ) {

				$template_options['details_page'] = get_permalink();

			}

			// is_events_page is false or not set in the shortcode.
			if ( ! $api_is_events_page ) {

				if ( ! empty( $shortcode_dtl_page ) ) {

					$details_page = $shortcode_dtl_page;

				} elseif ( $options_dtl_page_id ) {

					$details_page = $options_dtl_page_uri;

				}

				// Set the details_page option.
				$template_options['details_page'] = $details_page;

			}

			/** --------------------------------------------------
				Get date range option
			--------------------------------------------------- */

			// Date range from global options.
			$options_date_range = get_option( 'usc_lfwp_date_range' );

			// Default value.
			$date_range = $options_date_range;

			// Date range from shortcode options.
			$shortcode_date_range = $attr_all['date_range'];

			if ( ! empty( $shortcode_date_range ) ) {

				$date_range = $api_data->validate_key_value( 'date_range', $shortcode_date_range );

			}

			// Set the date_range option.
			$template_options['date_range'] = $date_range;

			/** --------------------------------------------------
				Get No Events Message
			--------------------------------------------------- */

			// Default message.
			$msg_none = $config['default']['messages']['no_events'];

			// Get the message for no events.
			$shortcode_msg_none = $attr_all['message_no_events'];

			if ( ! empty( $shortcode_msg_none ) ) {

				$msg_none = $shortcode_msg_none;

			}

			$template_options['message_no_events'] = $msg_none;

			/** --------------------------------------------------
				Get pagination setting
			--------------------------------------------------- */

			// Get the paginate option if it exists.
			$paginate_options['paginate'] = isset( $attr_all['paginate'] ) ? true : false ;

			if ( $paginate_options['paginate'] ) {

				// Set the paginate type to the value from the shortcode.
				$paginate_options['paginate_type'] = $attr_all['paginate'];

			}

			/** --------------------------------------------------
				Get paginate offset
			--------------------------------------------------- */

			// Get the paginate offset number.
			$paginate_offset = $attr_all['paginate_offset'];

			if ( ! empty( $paginate_offset ) ) {

				$paginate_options['paginate_offset'] = $api_data->validate_key_value( 'paginate_offset', $paginate_offset );

			}

			/** --------------------------------------------------
				Get paginate numeric separator
			--------------------------------------------------- */

			// Get the paginate numeric separator.
			$paginate_numeric_sep = $attr_all['paginate_numeric_separator'];

			if ( ! empty( $paginate_numeric_sep ) ) {

				$paginate_options['paginate_numeric_separator'] = $paginate_numeric_sep;

			}

			/** --------------------------------------------------
				Get paginate label next
			--------------------------------------------------- */

			// Get the paginate next label.
			$paginate_label_next = $attr_all['paginate_label_next'];

			if ( ! empty( $paginate_label_next ) ) {

				$paginate_options['paginate_label_next'] = $paginate_label_next;

			}

			/** --------------------------------------------------
				Get paginate label previous
			--------------------------------------------------- */

			// Get the paginate previous label.
			$paginate_label_prev = $attr_all['paginate_label_previous'];

			if ( ! empty( $paginate_label_prev ) ) {

				$paginate_options['paginate_label_previous'] = $paginate_label_prev;

			}

			/** --------------------------------------------------
				Get paginate label first
			--------------------------------------------------- */

			// Get the paginate first label.
			$paginate_label_first = $attr_all['paginate_label_first'];

			if ( ! empty( $paginate_label_first ) ) {

				$paginate_options['paginate_label_first'] = $paginate_label_first;

			}

			/** --------------------------------------------------
				Get paginate label last
			--------------------------------------------------- */

			// Get the paginate last label.
			$paginate_label_last = $attr_all['paginate_label_last'];

			if ( ! empty( $paginate_label_last ) ) {

				$paginate_options['paginate_label_last'] = $paginate_label_last;

			}

			/** --------------------------------------------------
				Get url parameters and attach to the api query
			--------------------------------------------------- */

			$url_parameters = $api_data->get_custom_query_variables( $api_type );

			// Loop through the url parameters and attach to the $api_url associative array.
			foreach ( $url_parameters as $key => $value ) {
				$api_url[ $key ] = $value;
			}

			/** --------------------------------------------------
				Specifically set event_id if declared in the shortcode.

				This must come after checking the url parameters.
			--------------------------------------------------- */

			$api_event_id = $attr_all['event_id'];

			if ( ! empty( $api_event_id ) ) {

				$api_url['event_id'] = $api_event_id;

			}

			/** --------------------------------------------------
				Get allowed api attributes
			--------------------------------------------------- */

			// Get the available api options (based on type) from the shortcode.
			$api_attr = shortcode_atts( $config['api_options'][ $api_type ]['allowed'], $params, $this->plugin_shortcode_calendar );

			/** --------------------------------------------------
				Build the api url string for any options
			--------------------------------------------------- */

			// Get the matching api options by get type.
			$parameters_string = $api_data->parameters_as_string( $api_attr, $api_type );

			// If we have any error messages.
			if ( empty( $parameters_string ) ) {

				return __( 'Something went wrong.', 'usc-localist-for-wordpress' );

			}

			if ( ! empty( $parameters_string['errors'] ) ) {

				// There are errors.
				$errors = true;
				return $parameters_string['errors'];

			}

			if ( empty( $parameters_string['errors'] ) ) {

				// No errors.
				$api_url['options'] = $parameters_string['string'];
				$api_url['parameters'] = $parameters_string['parameters'];

			}

			/** --------------------------------------------------
				Get the api data if no errors are present
			--------------------------------------------------- */

			if ( ! $errors ) {

				// Perform the api call.
				$api_output = $api_data->get_api( $api_url );

				// Add the template options.
				$api_output['template_options'] = $template_options;

				// Add the paginate options.
				$api_output['paginate_options'] = $paginate_options;

				// Return the errors if any were found.
				if ( isset( $api_output['errors'] ) && ! empty( $api_output['errors'] ) ) {

					return $api_output['errors'];

				}

				// Check if we have no errors in returned api data.
				if ( ! isset( $api_output['errors'] ) || empty( $api_output['errors'] ) ) {

					// Check if we have data.
					if ( isset( $api_output['api']['data'] ) ) {

						// We have api array data. Switch between api types for output of data by class type.
						switch ( $api_output['api']['type'] ) {

							/**
							 * Add api get types and their respective classes.
							 *
							 * Future releases:
							 * organizations
							 * groups
							 * search
							 */

							// Default api type 'events' or 'event'.
							default:

								$shortcode_output = new USC_Localist_For_Wordpress_Events( $api_output );
								$output = $shortcode_output->get_events();

								break;

						}

						// Start the object collection.
						ob_start();

						// Add the api url as a comment in the output for any debugging.
						echo '<!-- ' . esc_url( $api_output['api']['url'] ) . ' -->';

						// Output the html.
						echo $output;

						// Return the shortcode cleaned object.
						return ob_get_clean();

					}
				}
			}

		}



	}

}
