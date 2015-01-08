<?php
/**
 * Define image sizes
 */



/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');



/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);



// Load CSS to TinyMCE editor
function add_mce_css( $mce_css ) {
  if ( ! empty( $mce_css ) )
  $mce_css .= ',';

  $mce_css .= get_template_directory_uri() . '/assets/public/css/editor-style.css';

  return $mce_css;
}
add_filter( 'mce_css', 'add_mce_css' );


// Get first image inside post content
function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];
  
  return $first_img;
}


/**
* Auto-subscribe or unsubscribe an Edit Flow user group when a post changes status
*
* @see http://editflow.org/extend/auto-subscribe-user-groups-for-notifications/
*
* @param string $new_status New post status
* @param string $old_status Old post status (empty if the post was just created)
* @param object $post The post being updated
* @return bool $send_notif Return true to send the email notification, return false to not
*/
function ednc_ef_auto_subscribe_usergroup( $new_status, $old_status, $post ) {
  global $edit_flow;

  // When the post is first created, you might want to automatically set
  // all of the user's user groups as following the post
  // if ( 'draft' == $new_status ) {
  //   // Get all of the user groups for this post_author
  //   $usergroup_ids_to_follow = $edit_flow->user_groups->get_usergroups_for_user( $post->post_author );
  //   $usergroup_ids_to_follow = array_map( 'intval', $usergroup_ids_to_follow );
  //   $edit_flow->notifications->follow_post_usergroups( $post->ID, $usergroup_ids_to_follow, true );
  // }

  // You could also follow a specific user group based on post_status
  if ( 'pending' == $new_status ) {
    // You'll need to get term IDs for your user groups and place them as
    // comma-separated values
    $usergroup_ids_to_follow = array(
      87, // id of editors group on prod site
    );
    $edit_flow->notifications->follow_post_usergroups( $post->ID, $usergroup_ids_to_follow, true );
  }

  // Return true to send the email notification
  return $new_status;
}
add_filter( 'ef_notification_status_change', 'ednc_ef_auto_subscribe_usergroup', 10, 3 );



/**
* Retrieve adjacent post by author.
* Modified function from get_adjacent_post in wp-includes/link-template.php
*
* Can either be next or previous post.
*
* @param bool         $previous       Optional. Whether to retrieve previous post.
* @return mixed       Post object if successful. Null if global $post is not set. Empty string if no corresponding post exists.
*/
function get_adjacent_author_post( $previous = true ) {
  global $wpdb;

  if ( ( ! $post = get_post() ) )
  return null;

  $current_post_date = $post->post_date;

  $author = $post->post_author;

  $join = '';
  $where = '';

  $adjacent = $previous ? 'previous' : 'next';
  $op = $previous ? '<' : '>';
  $order = $previous ? 'DESC' : 'ASC';

  /**
  * Filter the JOIN clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $join           The JOIN clause in the SQL.
  * @param bool   $in_same_term   Whether post should be in a same taxonomy term.
  * @param array  $excluded_terms Array of excluded term IDs.
  */
  $in_same_term = false;
  $excluded_terms = '';
  $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_term, $excluded_terms );

print_r($join);

  /**
  * Filter the WHERE clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $where          The `WHERE` clause in the SQL.
  * @param bool   $in_same_term   Whether post should be in a same taxonomy term.
  * @param array  $excluded_terms Array of excluded term IDs.
  */
  $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare( "WHERE p.post_date $op %s AND p.post_author = %d AND p.post_type = %s AND p.post_status = 'publish' $where", $current_post_date, $author, $post->post_type ), $in_same_term, $excluded_terms );

  /**
  * Filter the ORDER BY clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $order_by The `ORDER BY` clause in the SQL.
  */
  $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

  $query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";
  $query_key = 'adjacent_post_' . md5( $query );
  $result = wp_cache_get( $query_key, 'counts' );
  if ( false !== $result ) {
    if ( $result )
    $result = get_post( $result );
    return $result;
  }

  $result = $wpdb->get_var( $query );
  if ( null === $result )
  $result = '';

  wp_cache_set( $query_key, $result, 'counts' );

  if ( $result )
  $result = get_post( $result );

  return $result;
}
