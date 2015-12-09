<?php

/**
 * USC Localist for WordPress: Config
 * @package usc-usc-localist-for-wordpress
 */


/**
 * API Attributes
 * =========
 * 
 * Set the default paths for the API calls
 */
$localist_config = array(
	'url' => array(
		'base' => esc_url('https://calendar.usc.edu/api/2/'),
		'variables' => array(
			'pagination' => 'events-page'
		),
	),
	'api_options' => array(
		'all' => array(
			'allowed' => array(
				'get' => '', // api type: [events,organizations,groups,search]
				'organization_id' => '', // id of organization
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
			'allowed_array' => array(
				'type',
				'keyword',
				'group_id',
				'exclude_type',
			),
			'validation' => array(
				'numbers' => array(
					'page',
					'pp',
					'campus_id',
					'group_id',
					'venue_id',
					'within',
					'days',
					'exclude_type',
					'type',
					'event_instances_id'
				),
				'dates' => array(
					'end',
					'start',
					'created_after',
					'created_before',
					'dates'
				),
			),
		),
		'organization' => array(
			'allowed' => array(
				'organization_id' => '', // id of organization
				'page' => '', // page number
				'pp' => '' // number of items per page (max 100)
			),
			'allowed_array' => false
		),
		'communities' => array(
			'allowed' => array(
				'organization_id' => '',
				'communities_id' => ''
			),
			'allowed_array' => false
		),
		'search' => array(
			'allowed' => array(
				'search' => ''
			),
			'allowed_array' => false
		),
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