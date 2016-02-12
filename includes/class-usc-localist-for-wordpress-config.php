<?php

/**
 * Class: USC Localist Config Settings
 *
 * A class to keep all configuration settings.
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists('USC_Localist_For_Wordpress_Config') ) {
	
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
			'testing' => true,
			'default' => array(
				// 'cache' => HOUR_IN_SECONDS, // in seconds
				'cache' => 0, // testing only
				'api_timeout' => 5 // in seconds
			),
			'plugin' => array(
				'name' => 'USC Localist for Wordpress',
				'version' => USC_LFWP__VERSION,
				'tag' => 'usc-localist-for-wordpress',
				'shortcode' => array(
					'calendar' => 'localist-calendar'
				),
			),
			'url' => array(
				'base' => 'https://calendar.usc.edu/api/2/',
				'parameters' => array(
					// add custom url parameters and map their relationship (will validate against relationship item settings)
					'page' => array(
						'name' => 'page', // use wp default of 'page' for nice url
						'relationship' => 'page'
					),
					'event-id' => array(
						'name' => 'event-id',
						'relationship' => 'event_id'
					)
				),
			),
			'api_options' => array(
				
				// all allowed API url parameters
				// note: 'get' is custom to the type of GET API we will perform
				'all' => array(
					'allowed' => array(
						
						// custom values
						'cache' => '', // Transient cache timeout setting
						'get' => '', // API GET type: [events,event,organizations,groups,search]
						'date_range' => '', // optional: show date range for multiple date events
						'details_page'=>'', // optional: relative path to event detail
						'is_events_page' => '', // optional: indicate single events to display on the shortcode page
						'template_multiple' => '', // opttional: slug name from templates custom post type - multiple events
						'template_single' => '', // opttional: slug name from templates custom post type - single events

						// localist values
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

					// all API parameters types that allow array values
					'allowed_array' => array(
						'type',
						'keyword',
						'group_id',
						'exclude_type',
					),

					// all API parameters that need validation
					'validation' => array(
						
						// API parameters that need to be numbers
						'numbers' => array(
							'cache',
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
							'event_id'
						),

						// API parameters that need to be date forma YYYY-MM-DD
						'dates' => array(
							'end',
							'start',
							'created_after',
							'created_before',
							'dates'
						),

						// API parameters that need to be boolean
						'boolean' => array(
							// custom values
							'is_events_page',
							'date_range',

							// localist values
							'featured',
							'require_all',
							'sponsored',
							'all_custom_fields',
							'include_attendance',
							'include_attendees',
							'include_activity'
						)
					),
				),

				// API parameters for GET 'organizations'
				'organization' => array(
					'allowed' => array(
						'organization_id' => '', // id of organization
						'page' => '', // page number
						'pp' => '' // number of items per page (max 100)
					),
					'allowed_array' => false
				),

				// API parameters for GET 'communities'
				'communities' => array(
					'allowed' => array(
						'organization_id' => '',
						'communities_id' => ''
					),
					'allowed_array' => false
				),

				// API parameters for GET 'search'
				'search' => array(
					'allowed' => array(
						'search' => ''
					),
					'allowed_array' => false
				),

				// API parameters for GET 'events'
				'events' => array(
					'allowed' => array(
						// custom value
						'event_id' => '', // added so 'is_events_page' can check for url parameter

						// localist values
						'bounds' => '',
						'campus_id' => '',
						'group_id' => '',
						'near' => '',
						'units' => '',
						'venue_id' => '',
						'within' => '',
						'days' => '',
						'end' => '',
						'start' => '',
						'page' => '',
						'pp' => '',
						'created_after' => '',
						'created_before' => '',
						'exclude_type' => '',
						'featured' => '',
						'keyword' => '',
						'match' => '',
						'require_all' => '',
						'sponsored' => '',
						'type' => '',
						'all_custom_fields' => '',
						'direction' => '',
						'distinct' => '',
						'include_attendance' => '',
						'sort' => ''
					),
					'allowed_array' => array(
						'type',
						'keyword',
						'group_id',
						'exclude_type'
					)
				),

				// API parameters for GET 'event'
				'event' => array(
					'allowed' => array(
						'event_id' => '',
						'all_custom_fields' => '',
						'include_activity' => '',
						'include_attendance' => '',
						'include_attendees' => ''
					),
					'allowed_array' => false
				)
			)
		);

	}
	
}