<?php

/**
 * Class: USC Localist for WordPress Template
 * 
 * A class to handle parsing output of API data using 
 * custom templates stored as post types.  Post types
 * are enabled in USC_Localist_For_Wordpress_Admin
 *
 * @since 		1.0.0
 * @package 	Usc_Localist_For_Wordpress
 * @subpackage 	Usc_Localist_For_Wordpress/includes
 * @author 		USC Web Services <webhelp@usc.edu>
 */

if ( ! class_exists( 'USC_Localist_For_Wordpress_Templates' ) ) {

	class USC_Localist_For_Wordpress_Templates {

		/**
		 * The array of events template options 'template', 'href', 'date_range'
		 * @var array
		 */
		public $template_options;

		/**
		 * Construct
		 * =========
		 *
		 * @since 1.0.0
		 * 
		 * Constructor to run when the class is called.
		 */
		public function __construct( $template_options ) {

			// get the template path opton
			$this->template_options = $template_options;

			// load the dependencies
			$this->load_dependencies();

		}

		/**
		 * Load Dependencies
		 * =================
		 * 
		 * Load the required dependencies for this class.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// require the config class for API variables
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions-simple-html-dom.php';

		}

		/**
		 * Valid URL
		 * =========
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		function valid_url( $url ) {
			
			$headers = @get_headers( $url );
			$httpStatus = intval( substr( $headers[0], 9, 3 ) );
			
			if ( $httpStatus<400 ) {
				
				return true;

			}

			return false;

		}

		/**
		 * Get Template
		 * ============
		 *
		 * Get the template based on the 
		 *
		 * @since 	1.0.0
		 */
		public function get_template( $template_options ) {

			// template path
			$template_path = $template_options['template'];

			// default template path
			$default_template = plugin_dir_path( dirname( __FILE__ ) ) . '/templates/' . $template_path;

			// if the template location is at http
			if ( strpos( $template_path, 'http' ) === 0 ) {
				
				// check that we have a vaild url
				$valid_template = $this->valid_url( $template_path );

				if ( $valid_template ) {
				
					$html = file_get_contents( $template_path );

					return file_get_html($html);

				}

				else {

					return file_get_html( $default_template );

				}

			} 

			// if the template is in the templates directory as file
			else if ( strpos( $template_path, '.html' ) ) {

				return file_get_html( $default_template );

			} 

			// else let's use the custom post type
			else {

				// get the post by slug name
				$template_post = get_posts( array(
					'name' => $template_options,
					'posts_per_page' => 1,
					'post_type' => 'event-template',
					'post_status' => 'publish'
				) );

				// fallback to default path 
				if( ! $template_post ) {

					$html = $default_html;

				} else {
					
					$html = $template_post[0]->post_content;

				}
			
				// if we have valid html returned
				if ( ! empty( $html ) ) {
					
					if ( ! strpos( '<html>', ' ' . $html ) ) {
						
						$html = '<html>' . $html . '</html>';

					}
					
					return str_get_html($html);

				}

			}
			
			
		}

	}

}