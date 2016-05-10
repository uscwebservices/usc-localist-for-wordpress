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
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	protected $plugin_version;

	/**
	 * The tag of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_tag    The current version of this plugin.
	 */
	protected $plugin_tag;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_version, $plugin_tag ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->plugin_tag = $plugin_tag;

	}

	/**
	 * Activate: Init
	 * ========
	 *
	 * Activate any functions that should run during the admin setup with 'init' hook.
	 *
	 * @since 	1.0.0
	 */
	public function activate_init() {
		
		// register the custom post types
		$this->custom_post_types();

		// let's get the permalinks to work with any custom post types
		flush_rewrite_rules();
		
	}

	/**
	 * Activate: Customize Register
	 * ============================
	 *
	 * Activate any settings the should run during the admin setup with 'customize_register' hook.
	 *
	 * @since 	1.0.0
	 */
	public function activate_customize_register( $wp_customize ) {

		$this->customize_section_events( $wp_customize, $this->plugin_tag );
		$this->customize_events_detail_page( $wp_customize, $this->plugin_tag );
		$this->customize_events_date_range( $wp_customize, $this->plugin_tag );

	}

	/**
	 * Customize: Calendar Section
	 * ===================================
	 *
	 * Set up the customizer section for the calendar options.
	 *
	 * The action hook is set in class USC_Localist_For_Wordpress::define_admin_hooks
	 *
	 * @since 	1.0.0
	 */
	public static function customize_section_events( $wp_customize, $plugin_tag ) {

		// localist events sections
		$wp_customize->add_section( 'customize_section_events', array(
			'title'			=> __( 'Localist Calendar Options', $plugin_tag ),
			'priority'		=> 130,
		) );

	}

	/**
	 * Customize: Events Date Range
	 * ============================
	 *
	 * Add option to have global setting for multiple dates returned as range.
	 *
	 * @since 	1.0.0
	 */
	public static function customize_events_date_range( $wp_customize, $plugin_tag ) {

		// radio controls
		$wp_customize->add_setting( 'usc_lfwp_date_range', array(
			'default'		=> false,
			'type'			=> 'option'
		) );

		$wp_customize->add_control( 'usc_lfwp_date_range', array(
			'label'			=> 'Dates Range',
			'section'		=> 'customize_section_events',
			'type'			=> 'radio',
			'description'	=> 'Display multiple dates as a Range',
			'choices'		=> array( 
				true => 'Yes [first - last instance]: 1/1/16 - 6/1/16',
				false => 'No [current instance]: 2/2/16'
			),
			'priority'		=> 1
		) );


	}

	/**
	 * Customize: Events Detail Page
	 * =============================
	 *
	 * Set up Customizer options to store settings for the events detail page (if selected).
	 *
	 * @since 	1.0.0
	 */
	public static function customize_events_detail_page( $wp_customize, $plugin_tag ) {
        
		// drop down of pages
		$wp_customize->add_setting( 'usc_lfwp_events_detail_page', array(
			'default'		=> 'Events',
			'type'			=> 'option'
		) );

		$wp_customize->add_control( 'usc_lfwp_events_detail_page', array(
			'label'			=> __( 'Event Details Page', $plugin_tag ),
			'section' 		=> 'customize_section_events',
			'type'			=> 'dropdown-pages',
			'description' 	=> __( 'Choose a page where the events link to an event details page.<br><br>  On the selected page below, you must use the shortcode: <br><code>[localist-calendar get="event"]</code><br><br>Or, to display events and the event detail with one shortcode:<br><br><code>[localist-calendar get="events" is_events_page="true"]</code><br><br> If you leave this blank, the event links will go to the event detail page on the <a href="http://calendar.usc.edu" target="_blank">USC Calendar</a>', $plugin_tag ),
			'priority'		=> 2
		) );

	}

	/**
	 * Register Custom Post Types
	 * ==========================
	 *
	 * Registers the Post Types for the plugin
	 *
	 * @since 	1.0.0
	 */
	public function custom_post_types() {

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
			'public'              => false,
			'query_var' 		  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 40,
			'menu_icon'           => 'dashicons-calendar-alt',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
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
