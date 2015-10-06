<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Does user have the capability?
if ( current_user_can( $this->info['cap'] ) == false || ( is_multisite() && is_super_admin() ) ) return; // stop executing file

// Does the trigger value match the currently logged in user ID?
if ( empty( $this->user_ID ) || $this->GET[$this->info['trigger']] != $this->user_ID ) return; // stop executing file

// Nonce
if ( isset( $this->GET[$this->info['nonce']] ) == false || wp_verify_nonce( $this->GET[$this->info['nonce']], $this->info['nonce'] ) == false ) return; // stop executing file

// Include required WordPress function files
include_once( ABSPATH . WPINC . '/post.php' ); // wp_delete_post
include_once( ABSPATH . 'wp-admin/includes/bookmark.php' ); // wp_delete_link
include_once( ABSPATH . 'wp-admin/includes/comment.php' ); // wp_delete_comment
include_once( ABSPATH . 'wp-admin/includes/user.php' ); // wp_delete_user, get_blogs_of_user

if ( is_multisite() ) {
	
	include_once( ABSPATH . WPINC . '/ms-functions.php' ); // remove_user_from_blog
	include_once( ABSPATH . 'wp-admin/includes/ms.php' ); // wpmu_delete_user
	
}

// Posts
//->>> Start: WordPress wp_delete_user Post types to delete
$post_types_to_delete = array();

foreach ( get_post_types( array(), 'objects' ) as $post_type ) {
	
	if ( $post_type->delete_with_user ) {
		
		$post_types_to_delete[] = $post_type->name;
		
	} elseif ( null === $post_type->delete_with_user && post_type_supports( $post_type->name, 'author' ) ) {
		
		$post_types_to_delete[] = $post_type->name;
		
	}
	
}

$post_types_to_delete = apply_filters( 'post_types_to_delete_with_user', $post_types_to_delete, $this->user_ID );
//<<<- End: WordPress wp_delete_user Post types to delete

$posts_list = array();
$posts = $this->wpdb->get_results( "SELECT `ID`, `post_title`, `post_type` FROM " . $this->wpdb->posts . " WHERE `post_author`='" . $this->user_ID . "' AND `post_type` IN ('" . implode( "', '", $post_types_to_delete ) . "')", ARRAY_A );
foreach ( $posts as $post ) $posts_list[] = wp_specialchars_decode( $post['post_title'], ENT_QUOTES ) . "\n" . ucwords( $post['post_type'] ) . ' ' . get_permalink( $post['ID'] );

// Links
$links_list = array();
$links = $this->wpdb->get_results( "SELECT `link_id`, `link_url`, `link_name` FROM " . $this->wpdb->links . " WHERE `link_owner`='" . $this->user_ID . "'", ARRAY_A );
foreach ( $links as $link ) $links_list[] = wp_specialchars_decode( $link['link_name'], ENT_QUOTES ) . "\n" . $link['link_url'];

// Comments
$comments_list = array();

if ( $this->option['settings']['delete_comments'] == true ) :
	
	$comments = $this->wpdb->get_results( "SELECT `comment_ID` FROM " . $this->wpdb->comments . " WHERE `user_id`='" . $this->user_ID . "'", ARRAY_A );
	
	foreach ( $comments as $comment ) {
		
		$comments_list[] = $comment['comment_ID'];
		
		// Delete comments if option set		
		wp_delete_comment( $comment['comment_ID'] );
		
	}
	
endif;

// E-mail notification
if ( $this->option['settings']['email_notification'] == true ) :
	
	$email = array();
	$email['to'] = get_option( 'admin_email' );
	$email['subject'] = '[' . wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) . '] Deleted User Notification';
	$email['message'] =
	'Deleted user on your site ' . wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) . ':' . "\n\n" .
	'Username: ' . $this->user_login . "\n\n" .	
	'E-mail: ' . $this->user_email . "\n\n" .	
	'This user deleted themselves using the WordPress plugin ' . $this->info['name'] . "\n\n" .	
	count( $posts_list ) . ' Post(s)' . "\n" .
	'----------------------------------------------------------------------' . "\n" .
	implode( "\n\n", $posts_list ) . "\n\n" .	
	count( $links_list ) . ' Link(s)' . "\n" .
	'----------------------------------------------------------------------' . "\n" .
	implode( "\n\n", $links_list ) . "\n\n" .	
	count( $comments_list ) . ' Comment(s)';	
	wp_mail( $email['to'], $email['subject'], $email['message'] );
	
endif;

// Delete user
if ( is_multisite() && $this->option['settings']['ms_delete_from_network'] == true && count( get_blogs_of_user( $this->user_ID ) ) == 1 ) {
	
	// Multisite: Deletes user's Posts and Links, then deletes from WP Users|Usermeta
	// ONLY IF "Delete From Network" setting checked and user only belongs to this blog	
	wpmu_delete_user( $this->user_ID );
	
} else {
	
	// Deletes user's Posts and Links
	// Multisite: Removes user from current blog
	// Not Multisite: Deletes user from WP Users|Usermeta	
	wp_delete_user( $this->user_ID );
	
}

// Logout
wp_logout();

// Redirect to same or landing URL
$shortcode_landing_url = isset( $this->GET[$this->info['trigger'] . '_landing_url'] ) ? $this->GET[$this->info['trigger'] . '_landing_url'] : $this->option['settings']['shortcode_landing_url'];
$same_url = remove_query_arg( array( $this->info['trigger'], $this->info['nonce'], $this->info['trigger'] . '_landing_url' ), $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
is_admin() ? wp_redirect( ( $this->option['settings']['your_profile_landing_url'] == '' ) ? $same_url : $this->option['settings']['your_profile_landing_url'] ) : wp_redirect( ( $shortcode_landing_url == '' ) ? $same_url : $shortcode_landing_url );

exit;
