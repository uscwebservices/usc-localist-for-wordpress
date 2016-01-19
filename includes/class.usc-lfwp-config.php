<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
if ( ! class_exists('USC_Localist_for_WordPress_Config') ) {
	
	class USC_Localist_for_WordPress_Config {
		
		/**
		 * Define the core configuration of the Localist API
		 *
		 * Set the allowable API types and allowed API types that can be passed per
		 * API type as well as those that can be passed as arrays.  Also check for 
		 * types that need validation as numbers or dates.
		 *
		 * @since    1.0.0
		 */

		public static $config = array(
			'url' => array(
				'base' => 'https://calendar.usc.edu/api/2/',
				'parameters' => array(
					'localist-page-number',
					'localist-event-id'
				),
			),
			'api_options' => array(
				
				// all allowed API url parameters
				// note: 'get' is custom to the type of GET API we will perform
				'all' => array(
					'allowed' => array(
						'get' => '', // API GET type: [events,organizations,groups,search]
						'cache' => '', // Transient cache timeout setting
						'organization_id' => '',
						'communities_id' => '',
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
						'sort' => '',
						'search' => '',
						'event_id' => '',
						'all_custom_filds' => '',
						'include_activity' => '',
						'include_attendance' => '',
						'include_attendees' => ''
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
						'all_custom_filds' => '',
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