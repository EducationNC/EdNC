<?php

namespace Roots\Sage\Widgets;

class Features_Recent_News extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
    parent::__construct(
			'features_recent_news', // Base ID
			__( 'Features and Recent News', 'sage' ), // Name
			array( 'description' => __( 'Displays today\'s features and recent news', 'sage' ), ) // Args
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

		echo $before_widget;
    include(locate_template('templates/widgets/features-recent-news.php'));
		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$features_n = ! empty ( $instance['features_n'] ) ? $instance['features_n'] : 2;
    $news_n = ! empty( $instance['news_n'] ) ? $instance['news_n'] : 4;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'features_n' ); ?>"><?php _e( 'Number of features to display:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'features_n' ); ?>" name="<?php echo $this->get_field_name( 'features_n' ); ?>" type="text" value="<?php echo esc_attr( $features_n ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'news_n' ); ?>"><?php _e( 'Number of recent news to display:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'news_n' ); ?>" name="<?php echo $this->get_field_name( 'news_n' ); ?>" type="text" value="<?php echo esc_attr( $news_n ); ?>">
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
		$instance['features_n'] = ( ! empty( $new_instance['features_n'] ) ) ? strip_tags( $new_instance['features_n'] ) : '';
		$instance['news_n'] = ( ! empty( $new_instance['news_n'] ) ) ? strip_tags( $new_instance['news_n'] ) : '';

		return $instance;
	}
}
