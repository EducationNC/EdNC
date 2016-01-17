<?php

namespace Roots\Sage\Widgets;

class Spotlight extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
    parent::__construct(
			'spotlight', // Base ID
			__( 'Spotlight', 'sage' ), // Name
			array( 'description' => __( 'Displays recent posts from selected category', 'sage' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$before_widget = $args['before_widget'];
		$after_widget = $args['after_widget'];
		$category = $instance['category'];
		$number = $instance['number'];

		echo $before_widget;
    include(locate_template('templates/widgets/spotlight.php'));
		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$category = ! empty( $instance['category'] ) ? $instance['category'] : -1;
		$number = ! empty( $instance['number'] ) ? $instance['number'] : 1;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label>
			<?php
				wp_dropdown_categories([
					'show_option_none' => 'Select one:',
					'id' => $this->get_field_id( 'category' ),
					'name' => $this->get_field_name( 'category' ),
					'orderby' => 'name',
					'selected' => $category
				]);
			?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

		return $instance;
	}
}
