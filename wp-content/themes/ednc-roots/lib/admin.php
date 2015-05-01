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
	if ( function_exists( 'coauthors' ) ) {
		$new_columns['coauthors'] = 'Authors';
	} else {
		$new_columns['author'] = 'Author';
	}
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

// maps
function maps_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['author'] = 'Author';
	$new_columns['map-category'] = 'Map Category';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function maps_custom_column_content($column_name, $id) {
	if ( 'map-category' == $column_name ) {
		echo get_the_term_list($id, 'map-category', '', ', ', '');
	}
}

add_filter( 'manage_map_posts_columns', 'maps_custom_column_heading', 10, 1 );
add_filter( 'manage_map_posts_custom_column', 'maps_custom_column_content', 10, 2 );

// bills
function bills_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['bill-type'] = 'Bill Type';
	$new_columns['bill-status'] = 'Bill Status';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function bills_custom_column_content($column_name, $id) {
	if ( 'bill-type' == $column_name ) {
		echo get_the_term_list($id, 'bill-type', '', ', ', '');
	}

	if ( 'bill-status' == $column_name ) {
		echo get_the_term_list($id, 'bill-status', '', ', ', '');
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

// galleries
function galleries_custom_column_heading($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['id'] = 'ID';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}

function galleries_custom_column_content($column_name, $id) {
	if ( 'id' == $column_name ) {
		echo get_the_id();
	}
}

add_filter( 'manage_gallery_posts_columns', 'galleries_custom_column_heading', 10, 1 );
add_filter( 'manage_gallery_posts_custom_column', 'galleries_custom_column_content', 10, 2 );

/**
 * Patch to allow private pages to appear as options in parent select menu for other pages
 * https://core.trac.wordpress.org/ticket/8592#comment:129
 *
 */
function admin_private_parent_metabox($output) {
	global $post;

	$args = array(
		'post_type'			=> $post->post_type,
		'exclude_tree'		=> $post->ID,
		'selected'			=> $post->post_parent,
		'name'				=> 'parent_id',
		'show_option_none'	=> __('(no parent)'),
		'sort_column'		=> 'menu_order, post_title',
		'echo'				=> 0,
		'post_status'		=> array('publish', 'private'),
	);

	$defaults = array(
		'depth'					=> 0,
		'child_of'				=> 0,
		'selected'				=> 0,
		'echo'					=> 1,
		'name'					=> 'page_id',
		'id'					=> '',
		'show_option_none'		=> '',
		'show_option_no_change'	=> '',
		'option_none_value'		=> '',
	);

	$r = wp_parse_args($args, $defaults);
	extract($r, EXTR_SKIP);

	$pages = get_pages($r);
	$name = esc_attr($name);
	// Back-compat with old system where both id and name were based on $name argument
	if (empty($id))
	{
		$id = $name;
	}

	if (!empty($pages))
	{
		$output = "<select name=\"$name\" id=\"$id\">\n";

		if ($show_option_no_change)
		{
			$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
		}
		if ($show_option_none)
		{
			$output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";
		}
		$output .= walk_page_dropdown_tree($pages, $depth, $r);
		$output .= "</select>\n";
	}

	return $output;
}

add_filter('wp_dropdown_pages', 'admin_private_parent_metabox');
