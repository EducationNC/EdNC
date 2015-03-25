<?php
/**
 * Custom functions used to copy pub date to custom field on save
 *
 * @param int $post_id The ID of the post.
 */

/**
 * Add updated date to pub box with ability to edit date & time
 *
 */
function cpd_updated_date_in_pub_box() {
  global $post_id;
  $post_type = get_post_type($post_id);

  wp_nonce_field('cpd','cpd_nonce');

  $updated_date = get_post_meta($post_id, 'updated_date', true);

  if ($updated_date) {
    $m = date('m', $updated_date);
    $j = date('j', $updated_date);
    $Y = date('Y', $updated_date);
    $G = date('G', $updated_date);
    $i = date('i', $updated_date);
    $s = date('s', $updated_date);
    ?>

    <div class="misc-pub-section curtime misc-pub-updated">
      <span id="timestamp">Updated on: <b><?php echo date('M j, Y @ G:i', $updated_date); ?></b></span>
      <a href="#edit_update" class="edit-update hide-if-no-js"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit updated date and time</span></a>
      <div id="updatediv" class="hide-if-js">
        <div class="timestamp-wrap">
          <label for="update_mm" class="screen-reader-text">Month</label>
          <select id="update_mm" name="update_mm">
            <option value="01" <?php if ($m == '01') { echo 'selected="selected"'; } ?>>01-Jan</option>
            <option value="02" <?php if ($m == '02') { echo 'selected="selected"'; } ?>>02-Feb</option>
            <option value="03" <?php if ($m == '03') { echo 'selected="selected"'; } ?>>03-Mar</option>
            <option value="04" <?php if ($m == '04') { echo 'selected="selected"'; } ?>>04-Apr</option>
            <option value="05" <?php if ($m == '05') { echo 'selected="selected"'; } ?>>05-May</option>
            <option value="06" <?php if ($m == '06') { echo 'selected="selected"'; } ?>>06-Jun</option>
            <option value="07" <?php if ($m == '07') { echo 'selected="selected"'; } ?>>07-Jul</option>
            <option value="08" <?php if ($m == '08') { echo 'selected="selected"'; } ?>>08-Aug</option>
            <option value="09" <?php if ($m == '09') { echo 'selected="selected"'; } ?>>09-Sep</option>
            <option value="10" <?php if ($m == '10') { echo 'selected="selected"'; } ?>>10-Oct</option>
            <option value="11" <?php if ($m == '11') { echo 'selected="selected"'; } ?>>11-Nov</option>
            <option value="12" <?php if ($m == '12') { echo 'selected="selected"'; } ?>>12-Dec</option>
          </select>
          <label for="update_jj" class="screen-reader-text">Day</label>
          <input type="text" id="update_jj" name="update_jj" value="<?php echo $j; ?>" size="2" maxlength="2" autocomplete="off">,
          <label for="update_aa" class="screen-reader-text">Year</label>
          <input type="text" id="update_aa" name="update_aa" value="<?php echo $Y; ?>" size="4" maxlength="4" autocomplete="off"> @
          <label for="update_hh" class="screen-reader-text">Hour</label>
          <input type="text" id="update_hh" name="update_hh" value="<?php echo $G; ?>" size="2" maxlength="2" autocomplete="off"> :
          <label for="update_mn" class="screen-reader-text">Minute</label>
          <input type="text" id="update_mn" name="update_mn" value="<?php echo $i; ?>" size="2" maxlength="2" autocomplete="off">
          <input type="hidden" id="update_ss" name="update_ss" value="<?php echo $s; ?>">
        </div>
        <p>
          <a href="#edit_update" class="save-update hide-if-no-js button">OK</a>
          <a href="#edit_update" class="cancel-update hide-if-no-js button-cancel">Cancel</a>
        </p>
      </div>
    </div>

    <?php
  }
}
add_action( 'post_submitbox_misc_actions', 'cpd_updated_date_in_pub_box' );


/**
 * Update custom meta for updated datetime
 *
 */
function cpd_copy_pub_date( $post_id, $post, $update ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['cpd_nonce'] ) ) {
    return;
  }

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $_POST['cpd_nonce'], 'cpd' ) ) {
    return;
  }

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // Check the user's permissions.
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  // Only do this on posts, maps, and ednews
  if ($post->post_type != 'post' && $post->post_type != 'map' && $post->post_type != 'ednews') {
    return;
  }

  /* OK, it's safe for us to save the data now. */

  // Get the saved updated datetime
  $saved_updated_time = get_post_meta($post_id, 'updated_date', true);

  // Get the updated datetime
  if (isset($_POST['update_mm'])) {
    $m = $_POST['update_mm'];
    $j = $_POST['update_jj'];
    $Y = $_POST['update_aa'];
    $G = $_POST['update_hh'];
    $i = $_POST['update_mn'];
    $s = $_POST['update_ss'];

    $updated_time = strtotime("$Y-$m-$j $G:$i:$s");
  }

  // Get the WP publish datetime
  $pub_time = get_the_time('U', $post_id);

  // If there is no saved updated datetime, set to publish datetime
  if (!$saved_updated_time) {
    update_post_meta( $post_id, 'updated_date', $pub_time );
  } else {
    // If saved updated datetime is older than publish datetime, then we want to set it equal to publish datetime
    if ($saved_updated_time < $pub_time) {
      update_post_meta( $post_id, 'updated_date', $pub_time );
    } else {
      // Otherwise, set it to the configured datetime
      update_post_meta( $post_id, 'updated_date', $updated_time );
    }
  }
}
add_action( 'save_post', 'cpd_copy_pub_date', 10, 3 );


/**
 * Add columns to admin screen for posts
 *
 */

function cpd_custom_column_heading($columns) {
  $columns['last-updated'] = 'Last Updated';
  return $columns;
}

function cpd_custom_column_content($column_name, $id) {
  if ( 'last-updated' == $column_name ) {
    $updated_time = get_post_meta($id, 'updated_date', true);
    if ($updated_time) {
      echo date('Y/m/j', $updated_time);
    }
  }
}

function cpd_custom_column_sort($columns) {
  $columns['last-updated'] = 'updated';
    return $columns;
}

function cpd_custom_column_orderby($query) {
  if( ! is_admin() )
    return;

  $orderby = $query->get( 'orderby');

  if( 'updated' == $orderby ) {
    $query->set('meta_key','updated_date');
    $query->set('orderby','meta_value_num');
  }
}

add_action( "pre_get_posts", 'cpd_custom_column_orderby' );

// posts
add_filter( "manage_post_posts_columns", 'cpd_custom_column_heading', 10, 1 );
add_action( "manage_post_posts_custom_column", 'cpd_custom_column_content', 10, 2 );
add_action( "manage_edit-post_sortable_columns", 'cpd_custom_column_sort', 10, 2 );
// maps
add_filter( "manage_map_posts_columns", 'cpd_custom_column_heading', 10, 1 );
add_action( "manage_map_posts_custom_column", 'cpd_custom_column_content', 10, 2 );
add_action( "manage_edit-map_sortable_columns", 'cpd_custom_column_sort', 10, 2 );
// ednews
add_filter( "manage_ednews_posts_columns", 'cpd_custom_column_heading', 10, 1 );
add_action( "manage_ednews_posts_custom_column", 'cpd_custom_column_content', 10, 2 );
add_action( "manage_edit-ednews_sortable_columns", 'cpd_custom_column_sort', 10, 2 );
