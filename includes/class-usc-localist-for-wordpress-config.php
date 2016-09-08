<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author     USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Config' ) ) {

	/**
	 * Class: USC Localist Config Settings
	 *
	 * A class to keep all configuration settings.
	 *
	 * @since 		1.0.0
	 */
	class USC_Localist_For_Wordpress_Config {

		/**
		 * Configuration settings for the plugin.
		 *
		 * Defines urls for localist and allowed api attributes by
		 * types and validation methods.
		 *
		 * @since 1.0.0
		 * @var array
		 */

		public static $config = array(
			'testing' => array(
				'enabled' => true, // Set to true to use local events.json data.
				'json' => array(
					'single' => '/sample/event-settings-sample-date.json',
					'multiple' => '/sample/events.json',
				),
			),
			'default' => array(
				'cache' => HOUR_IN_SECONDS, // in seconds
				// 'cache' => 0, // testing only
				'api_timeout' => 5, // In seconds.
				'format_date' => 'm/d/Y',
				'format_time' => 'g:i a',
				'separator' => array(
					'default' => null,
					'date_time_single' => ' at ',
					'date_time_multiple' => ' from ',
					'time' => ' to ',
					'range' => ' - ',
				),
				'messages' => array(
					'no_events' => 'No scheduled events.',
				),
				'class' => array(
					'no_events' => 'no-events-message',
				),
			),
			'plugin' => array(
				'name' => 'USC Localist for Wordpress',
				'version' => USC_LFWP__VERSION,
				'tag' => 'usc-localist-for-wordpress',
				'shortcode' => array(
					'calendar' => 'localist-calendar',
				),
			),
			'url' => array(
				'base' => 'https://calendar.usc.edu/api/2/',
				'google_maps' => 'https://www.google.com/maps/place/',
				'parameters' => array(
					// Add custom url parameters and map their relationship (will validate against relationship item settings).
					'page' => array(
						'name' => 'page', // Use wp default of 'page' for nice url.
						'relationship' => 'page',
					),
					'event-id' => array(
						'name' => 'event-id',
						'relationship' => 'event_id',
					),
				),
			),
			'api_options' => array(

				// All allowed API url parameters.
				// Note: 'get' is custom to the type of GET API we will perform.
				'all' => array(
					'allowed' => array(

						// Custom values: API type.
						'get' => '', // API GET type: [events,event,organizations,groups,search].

						// Custom values: cache.
						'cache' => '', // Transient cache timeout setting.

						// Custom values: dates.
						'date_range' => '', // Optional: show date range for multiple date events.

						// Custom values: event details link.
						'details_page' => '', // Optional: relative path to event detail.
						'is_events_page' => '', // Optional: indicate single events to display on the shortcode page.

						// Custom values: templates.
						'template_multiple' => '', // Optional: slug name from templates custom post type - multiple events.
						'template_single' => '', // Optional: slug name from templates custom post type - single events.

						// Custom values: pagination.
						'paginate' => '', // Optional: show pagination on multi-events (next, numeric).
						'paginate_offset' => '', // Optional: define the amount of numbers to the left and right of current page.
						'paginate_numeric_separator' => '', // Optional: define the separator to use between first [separator] current [separator] last.
						'paginate_label_next' => '', // Optional: define the text for the 'next' label in pagination.
						'paginate_label_previous' => '', // Optional: define the text for the 'next' label in pagination.
						'paginate_label_first' => '', // Optional: define the text for the 'first' label in pagination.
						'paginate_label_last' => '', // Optional: define the text for the 'last' label in pagination.

						// Custom values: messages.
						'message_no_events' => '', // Optional: the message to display if there are no events.

						// Localist values.
						'all_custom_fields' => '',
						'bounds' => '',
						'campus_id' => '',
						'communities_id' => '',
						'created_after' => '',
						'created_before' => '',
						'days' => '',
						'direction' => '',
						'distinct' => '',
						'end' => '',
						'event_id' => '',
						'exclude_type' => '',
						'featured' => '',
						'group_id' => '',
						'include_activity' => '',
						'include_attendance' => '',
						'include_attendees' => '',
						'keyword' => '',
						'match' => '',
						'near' => '',
						'organization_id' => '',
						'page' => '',
						'pp' => '',
						'require_all' => '',
						'search' => '',
						'sort' => '',
						'sponsored' => '',
						'start' => '',
						'type' => '',
						'units' => '',
						'venue_id' => '',
						'within' => '',
					),

					// All API parameters types that allow array values.
					'allowed_array' => array(
						'type',
						'keyword',
						'group_id',
						'exclude_type',
					),

					// All API parameters that need validation.
					'validation' => array(

						// API parameters that need to be numbers.
						'numbers' => array(
							'cache',
							'paginate_offset',
							'page',
							'pp',
							'campus_id',
							'group_id',
							'venue_id',
							'within',
							'days',
							'exclude_type',
							'type',
							'event_instances_id',
							'event_id',
						),

						// API parameters that need to be date format YYYY-MM-DD.
						'dates' => array(
							'end',
							'start',
							'created_after',
							'created_before',
							'dates',
						),

						// API parameters that need to be boolean.
						'boolean' => array(
							// Custom values.
							'is_events_page',
							'date_range',

							// Localist values.
							'distinct',
							'featured',
							'require_all',
							'sponsored',
							'all_custom_fields',
							'include_attendance',
							'include_attendees',
							'include_activity',
						),
					),
				),

				// API parameters for GET 'organizations'.
				'organization' => array(
					'allowed' => array(
						'organization_id' => '', // ID of organization.
						'page' => '', // Page number.
						'pp' => '', // Number of items per page (max 100).
					),
					'allowed_array' => false,
				),

				// API parameters for GET 'communities'.
				'communities' => array(
					'allowed' => array(
						'organization_id' => '',
						'communities_id' => '',
					),
					'allowed_array' => false,
				),

				// API parameters for GET 'search'.
				'search' => array(
					'allowed' => array(
						'search' => '',
					),
					'allowed_array' => false,
				),

				// API parameters for GET 'events'.
				'events' => array(
					'allowed' => array(
						// Custom value.
						'event_id' => '', // Added so 'is_events_page' can check for url parameter.

						// Localist values.
						'all_custom_fields' => '',
						'bounds' => '',
						'campus_id' => '',
						'created_after' => '',
						'created_before' => '',
						'days' => '',
						'direction' => '',
						'distinct' => '',
						'end' => '',
						'exclude_type' => '',
						'featured' => '',
						'group_id' => '',
						'include_attendance' => '',
						'keyword' => '',
						'match' => '',
						'near' => '',
						'page' => '',
						'pp' => '',
						'require_all' => '',
						'sponsored' => '',
						'sort' => '',
						'start' => '',
						'type' => '',
						'units' => '',
						'venue_id' => '',
						'within' => '',
					),
					'allowed_array' => array(
						'type',
						'keyword',
						'group_id',
						'exclude_type',
					),
				),

				// API parameters for GET 'event'.
				'event' => array(
					'allowed' => array(
						'event_id' => '',
						'all_custom_fields' => '',
						'include_activity' => '',
						'include_attendance' => '',
						'include_attendees' => '',
					),
					'allowed_array' => false,
				),
			),
		);

	}

}
