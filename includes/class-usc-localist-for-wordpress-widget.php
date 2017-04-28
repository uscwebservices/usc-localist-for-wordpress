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
		 * @since 1.4.0
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

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			// @codingStandardsIgnoreStart
			echo $args['before_widget'];
			// @codingStandardsIgnoreEnd
			if ( ! empty( $instance['title'] ) ) {
				// @codingStandardsIgnoreStart
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				// @codingStandardsIgnoreEnd
			}
			// @codingStandardsIgnoreStart
			echo apply_filters( 'the_content', $instance['shortcode'] );
			echo $args['after_widget'];
			// @codingStandardsIgnoreEnd
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'usc-localist-for-wordpress' );
			$shortcode = ! empty( $instance['shortcode'] ) ? $instance['shortcode'] : esc_html__( 'Enter shortcode', 'usc-localist-for-wordpress' );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'usc-localist-for-wordpress' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>"><?php esc_attr_e( 'Shortcode:', 'usc-localist-for-wordpress' ); ?></label>
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'shortcode' ) ); ?>"><?php echo esc_attr( $shortcode ); ?></textarea>
			</p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['shortcode'] = ( ! empty( $new_instance['shortcode'] ) ) ? strip_tags( $new_instance['shortcode'] ) : '';

			return $instance;
		}
	}

} // End if().
