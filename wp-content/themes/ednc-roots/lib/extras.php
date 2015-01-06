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
