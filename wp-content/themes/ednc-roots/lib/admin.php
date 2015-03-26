<?php
/**
 *Order bios and bills by menu order on admin page
 *
 */
function ednc_bios_admin_orderby( $vars ) {
	if ( isset( $vars['post_type']) && ($vars['post_type'] == 'bio' || $vars['post_type'] == 'bill' || $vars['post_type'] == 'legislator') && !isset( $vars['orderby'] ) ) {

		$vars = array_merge( $vars, array(
			'orderby' => 'menu_order',
			'order' => 'ASC'
		));
	}

	return $vars;
}
add_filter( 'request', 'ednc_bios_admin_orderby' );


/**
 * Add columns to admin screen for post lists
 *
 */

// posts
function posts_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['author'] = 'Author';
	$new_columns['appearance'] = 'Appearance';
	$new_columns['categories'] = 'Categories';
	$new_columns['column'] = 'Column';
  $new_columns['district'] = 'District';
	$new_columns['comments'] = '<span><span class="vers"><span title="Comments" class="comment-grey-bubble"></span></span></span>';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
  return $columns;
}

function posts_custom_column_content($column_name, $id) {
  if ( 'appearance' == $column_name ) {
    echo get_the_term_list($id, 'appearance', '', ', ', '');
  }
  if ( 'district' == $column_name ) {
    echo get_the_term_list($id, 'district-posts', '', ', ', '');
  }
	if ( 'column' == $column_name ) {
		echo get_the_term_list($id, 'column', '', ', ', '');
	}
}

add_filter( 'manage_post_posts_columns', 'posts_custom_column_heading', 10, 1 );
add_action( 'manage_post_posts_custom_column', 'posts_custom_column_content', 10, 2 );

// bills
function bills_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['bill-type'] = 'Bill Type';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function bills_custom_column_content($column_name, $id) {
	if ( 'bill-type' == $column_name ) {
		echo get_the_term_list($id, 'bill-type', '', ', ', '');
	}
}

add_filter( 'manage_bill_posts_columns', 'bills_custom_column_heading', 10, 1 );
add_filter( 'manage_bill_posts_custom_column', 'bills_custom_column_content', 10, 2 );

// resources
function resources_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['resource-type'] = 'Resource Type';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function resources_custom_column_content($column_name, $id) {
	if ( 'resource-type' == $column_name ) {
		echo get_the_term_list($id, 'resource-type', '', ', ', '');
	}
}

add_filter( 'manage_resource_posts_columns', 'resources_custom_column_heading', 10, 1 );
add_filter( 'manage_resource_posts_custom_column', 'resources_custom_column_content', 10, 2 );

// bios
function bios_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['author-type'] = 'Author Type';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function bios_custom_column_content($column_name, $id) {
	if ( 'author-type' == $column_name ) {
		echo get_the_term_list($id, 'author-type', '', ', ', '');
	}
}

add_filter( 'manage_bio_posts_columns', 'bios_custom_column_heading', 10, 1 );
add_filter( 'manage_bio_posts_custom_column', 'bios_custom_column_content', 10, 2 );
