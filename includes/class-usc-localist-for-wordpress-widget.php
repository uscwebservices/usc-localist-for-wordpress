<?php
/**
 * USC Localist for WordPress Widget Class.
 *
 * @package	Usc_Localist_For_Wordpress
 * @subpackage Usc_Localist_For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Widget' ) ) {

	/**
	 * Class: USC Localist for WordPress Widget
	 *
	 * Widget to run shortcode for events.
	 *
	 * @since 		1.4.0
	 */
	class USC_Localist_For_Wordpress_Widget extends WP_Widget {


		/**
		 * Constructor to run when the class is called.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				'usc_localist_for_wordpress',
				esc_html__( 'USC Localist for Wordpress', 'usc-localist-for-wordpress' ),
				array(
					'description' => esc_html__( 'Widget to display Localist Events using shortcode', 'usc-localist-for-wordpress' ),
					)
			);
		}


	}

} // End if().
