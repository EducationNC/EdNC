<?php
class Mailmunch_Sidebar_Widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      MAILMUNCH_PREFIX.'_widget', // Base ID
      __('Sidebar MailMunch Form', 'text_domain'), // Name
      array( 'description' => __( 'Displays a MailMunch optin form in Sidebar', 'text_domain' ), ) // Args
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
    if ( isset( $instance[ 'form_id' ] ) ) {
      $form_id = $instance[ 'form_id' ];
    }

    if (!empty($form_id)) {
      echo $args['before_widget'];
      if ( ! empty( $instance['title'] ) ) {
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
      }
      echo "<div class='mailmunch-wordpress-widget mailmunch-wordpress-widget-".$form_id."' style='display: none !important;'></div>";
      echo $args['after_widget'];
    }
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Optin Form', 'text_domain' );
    }

    if ( isset( $instance[ 'form_id' ] ) ) {
      $form_id = $instance[ 'form_id' ];
    }

    $mm = new Mailmunch_Api();
    $result = $mm->widgets("Sidebar");
    if ( !is_wp_error( $result ) ) {
      $widgets = json_decode($result['body']);
    }
    
    ?>
    <script type="text/javascript">
    window.onmessage = function (e) {
      if (e.data === 'refresh') {
        top.location.reload();
      }
    };
    </script>
    <?php
    if (sizeof($widgets) > 0) {
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php _e( 'Optin Form:' ); ?></label>
      <select class="widefat" id="<?php echo $this->get_field_id( 'form_id' ); ?>" name="<?php echo $this->get_field_name( 'form_id' ); ?>">
        <option value="">None</option>
        <?php
        foreach ($widgets as $widget) {
          echo "<option value='".$widget->id."'";
          if ($form_id == $widget->id) { echo " selected"; };
          echo ">".$widget->name."</option>";
        }
        ?>
      </select>
    </p>

    <p><a href="<?php echo MAILMUNCH_URL ?>/sso?token=<?php echo get_option(MAILMUNCH_PREFIX."_user_token") ?>&next_url=<?php echo urlencode("/sites/".get_option(MAILMUNCH_PREFIX."_site_id")."/widgets/new?wp_layout=1&widget_type=Sidebar") ?>" target="_blank">Create New Sidebar Form</a></p>
    <?php 
    } else {
    ?>
    <p>No sidebar forms found. <a href="<?php echo MAILMUNCH_URL ?>/sso?token=<?php echo get_option(MAILMUNCH_PREFIX."_user_token") ?>&next_url=<?php echo urlencode("/sites/".get_option(MAILMUNCH_PREFIX."_site_id")."/widgets/new?wp_layout=1&widget_type=Sidebar") ?>" target="_blank">Create Your First One</a></p>
    <?php
    }

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
    $instance['form_id'] = ( ! empty( $new_instance['form_id'] ) ) ? strip_tags( $new_instance['form_id'] ) : '';

    return $instance;
  }

}
