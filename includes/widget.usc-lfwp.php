<?php
	
/**
 * USC Localist for WordPress: Widget
 * @package usc-localist-for-wordpress
 */


/**
 * Adds Localist_for_WordPress
 */
class USC_Localist_for_WordPress extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'usc_lfwp_widget', // Base ID
			__( 'USC Localist for WordPress', 'usc-localist-for-wordpress' ), // Name
			array( 'description' => __( 'USC Localist for WordPress', 'usc-localist-for-wordpress' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @since 1.0.0
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		if ( ! empty( $instance['usc_lfwp_view_more_checkbox'] )  ) {
			if ( ! empty( $instance['usc_lfwp_view_more_url'] ) ) {
				echo '<a class="view-more-events" href="' . $instance['usc_lfwp_view_more_url'] . '">';
				
				if ( ! empty( $instance['usc_lfwp_view_more_label'] ) ) {
					echo $instance['usc_lfwp_view_more_label'];
				} else {
					echo __('View More', 'usc-localist-for-wordpress');
				}
				
				echo '</a>';
			}
		}
		
		// do widget output stuff here
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @since 1.0.0
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) { ?>
		
		<?php
			
			/**
			 * Title Input
			 * ================
			 */
			
			$title_label = 'Calendar Title:';
			$title_value = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Calendar', 'usc-localist-for-wordpress' );
			$title_id = $this->get_field_id( 'title' );
			$title_name = $this->get_field_name( 'title' );
			
		?>
			<p>
				<label for="<?php echo $title_id ?>"><?php _e( $title_label ); ?></label>
				<input class="widefat" id="<?php echo $title_id ?>" name="<?php echo $title_name; ?>" type="text" value="<?php echo esc_attr( $title_value ); ?>">
			</p>
		
		<?php
			
			/**
			 * View More Section
			 * ================-
			 */
			
		?>
		<h5><?php _e('View More Link','usc-localist-for-wordpress'); ?></h5>

		<p><?php _e('Checkbox must be checked and URL with value for link to display.','usc-localist-for-wordpress'); ?></p>
			
		
		<?php
			
			/**
			 * View More Checkbox
			 * ==================
			 */
			
			$view_more_checkbox_label = 'Display View More Link?';
			$view_more_checkbox_value = ! empty( $instance['usc_lfwp_view_more_checkbox'] ) ? $instance['usc_lfwp_view_more_checkbox'] : '';
			$view_more_checkbox_id = $this->get_field_id( 'usc_lfwp_view_more_checkbox' );
			$view_more_checkbox_name = $this->get_field_name( 'usc_lfwp_view_more_checkbox' );
		?>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( '1', $view_more_checkbox_value ); ?> id="<?php echo $view_more_checkbox_id ?>" name="<?php echo $view_more_checkbox_name; ?>" value="1" /> 
				<label for="<?php echo $view_more_checkbox_id ?>"><?php _e( $view_more_checkbox_label ); ?></label>
			</p>
		
		<?php
			
			/**
			 * View More Text
			 * ==============
			 */
			
			$view_more_text_label = 'View More Text:';
			$view_more_text_value = ! empty( $instance['usc_lfwp_view_more_label'] ) ? $instance['usc_lfwp_view_more_label'] : __( 'View More Events', 'usc-localist-for-wordpress' );
			$view_more_text_id = $this->get_field_id( 'usc_lfwp_view_more_label' );
			$view_more_text_name = $this->get_field_name( 'usc_lfwp_view_more_label' );
		?>
			<p>
				<label for="<?php echo $view_more_text_id ?>"><?php _e( $view_more_text_label ); ?></label>
				<input class="widefat" id="<?php echo $view_more_text_id ?>" name="<?php echo $view_more_text_name; ?>" type="text" value="<?php echo esc_attr( $view_more_text_value ); ?>">
			</p>
		
		<?php
			
			/**
			 * View More URL
			 * =============
			 */
			
			$view_more_link_label = 'View More URL:';
			$view_more_link_value = ! empty( $instance['usc_lfwp_view_more_url'] ) ? $instance['usc_lfwp_view_more_url'] : __( '', 'usc-localist-for-wordpress' );
			$view_more_link_id = $this->get_field_id( 'usc_lfwp_view_more_url' );
			$view_more_link_name = $this->get_field_name( 'usc_lfwp_view_more_url' );
		?>
			<p>
				<label for="<?php echo $view_more_link_id ?>"><?php _e( $view_more_link_label ); ?></label>
				<input class="widefat" id="<?php echo $view_more_link_id ?>" name="<?php echo $view_more_link_name; ?>" type="text" value="<?php echo esc_url( $view_more_link_value ); ?>" placeholder="https://">
			</p>
		
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @since 1.0.0
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		// title
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		// link
		$instance['usc_lfwp_view_more_checkbox'] = strip_tags($new_instance['usc_lfwp_view_more_checkbox']);
		
		$instance['usc_lfwp_view_more_label'] = ( ! empty( $new_instance['usc_lfwp_view_more_label'] ) ) ? strip_tags( $new_instance['usc_lfwp_view_more_label'] ) : '';
		$instance['usc_lfwp_view_more_url'] = ( ! empty( $new_instance['usc_lfwp_view_more_url'] ) ) ? strip_tags( esc_url( $new_instance['usc_lfwp_view_more_url'] ) ) : '';

		return $instance;
	}

} // class Foo_Widget

add_action( 'widgets_init', function(){
	register_widget( 'USC_Localist_for_WordPress' );
});

?>