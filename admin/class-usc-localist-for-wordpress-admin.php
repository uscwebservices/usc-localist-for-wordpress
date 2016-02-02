<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/admin
 * @author     USC Web Services <webhelp@usc.edu>
 */
class USC_Localist_For_Wordpress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register Events Template Post Type
	 * ==================================
	 *
	 * Registers the Post Type of 'Event Templates' for 
	 * custom template development with Localist events.
	 *
	 * @since 	1.0.0
	 */
	public function events_template_post_type() {

		$labels = array(
			'name'                => _x( 'Event Templates', 'Post Type General Name', '' ),
			'singular_name'       => _x( 'Event Template', 'Post Type Singular Name', '' ),
			'menu_name'           => __( 'Event Templates', '' ),
			'all_items'           => __( 'All Event Templates', '' ),
			'view_item'           => __( 'View Event Template', '' ),
			'add_new_item'        => __( 'Add New Event Template', '' ),
			'add_new'             => __( 'Add New', '' ),
			'edit_item'           => __( 'Edit Event Template', '' ),
			'update_item'         => __( 'Update Event Template', '' ),
			'search_items'        => __( 'Search Event Templates', '' ),
			'not_found'           => __( 'Not found', '' ),
			'not_found_in_trash'  => __( 'Not found in Trash', '' ),
		);
		$args = array(
			'label'               => 'event-template',
			'description'         => __( 'Template for displaying Localist events', '' ),
			'labels'              => $labels,
			'supports' 			  => array('title','editor','page-attributes','revisions'),
			'hierarchical'        => false,
			'public'              => true,
			'query_var' 		  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 40,
			'menu_icon'           => 'dashicons-calendar-alt',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' => array(	
				'slug' 			=> 'event-template',	
				'with_front'	=> false,
				'hierarchical' 	=> false
				)
		);

		register_post_type( 'event-template', $args );
	}
	

}
