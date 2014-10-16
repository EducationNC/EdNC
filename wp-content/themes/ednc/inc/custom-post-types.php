<?php
/**
* Custom Post Types
*
* Defines all custom post types and custom taxonomies
*
* @package EducationNC
*/

function add_custom_post_types() {
	register_post_type( 'sales', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		array('labels' => array(
				'name' => 'Sales Network',
				'singular_name' => 'Network',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Network',
				'edit' => 'Edit',
				'edit_item' => 'Edit Network',
				'new_item' => 'New Network',
				'view_item' => 'View Network',
				'search_items' => 'Search Sales Network',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => 'These are Nomacorc Sales',
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
	 	)
	);

	register_post_type( 'videos', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		array('labels' => array(
				'name' => 'Videos',
				'singular_name' => 'Video',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Video',
				'edit' => 'Edit',
				'edit_item' => 'Edit Video',
				'new_item' => 'New Video',
				'view_item' => 'View Video',
				'search_items' => 'Search Videos',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => 'These are Nomacorc videos',
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
	 	)
	);

	register_post_type( 'enology-videos', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		array('labels' => array(
				'name' => 'Enology Videos',
				'singular_name' => 'Enology Video',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Enology Video',
				'edit' => 'Edit',
				'edit_item' => 'Edit Enology Video',
				'new_item' => 'New Enology Video',
				'view_item' => 'View Enology Video',
				'search_items' => 'Search Enology Videos',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => 'These are Enology videos',
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
	 	)
	);
}
add_action( 'init', 'add_custom_post_types');

/*
for more information on taxonomies, go here:
http://codex.wordpress.org/Function_Reference/register_taxonomy
*/

// now let's add custom categories (these act like categories)

register_taxonomy( 'type',
	array('sales'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'Type', /* name of the custom taxonomy */
			'singular_name' => 'Type', /* single taxonomy name */
			'search_items' =>  'Search Types', /* search title for taxomony */
			'all_items' => 'All Types', /* all title for taxonomies */
			'parent_item' => 'Parent Type', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent Type:', /* parent taxonomy title */
			'edit_item' => 'Edit Type', /* edit custom taxonomy title */
			'update_item' => 'Update Type', /* update title for taxonomy */
			'add_new_item' => 'Add New Type', /* add new title for taxonomy */
			'new_item_name' => 'New Type Name' /* name title for taxonomy */
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => false
	)
);

// register_taxonomy( 'product-line',
// 	array('sales'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
// 	array('hierarchical' => true,     /* if this is true it acts like categories */
// 		'labels' => array(
// 			'name' => 'Product Lines', /* name of the custom taxonomy */
// 			'singular_name' => 'Product Line', /* single taxonomy name */
// 			'search_items' =>  'Search Product Lines', /* search title for taxomony */
// 			'all_items' => 'All Product Lines', /* all title for taxonomies */
// 			'parent_item' => 'Parent Product Line', /* parent title for taxonomy */
// 			'parent_item_colon' => 'Parent Product Line:', /* parent taxonomy title */
// 			'edit_item' => 'Edit Product Line', /* edit custom taxonomy title */
// 			'update_item' => 'Update Product Line', /* update title for taxonomy */
// 			'add_new_item' => 'Add New Product Line', /* add new title for taxonomy */
// 			'new_item_name' => 'New Product Line Name' /* name title for taxonomy */
// 		),
// 		'show_ui' => true,
// 		'query_var' => true,
// 		'public' => false
// 	)
// );

register_taxonomy( 'region',
	array('sales'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'Regions', /* name of the custom taxonomy */
			'singular_name' => 'Region', /* single taxonomy name */
			'search_items' =>  'Search Regions', /* search title for taxomony */
			'all_items' => 'All Regions', /* all title for taxonomies */
			'parent_item' => 'Parent Region', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent Region:', /* parent taxonomy title */
			'edit_item' => 'Edit Region', /* edit custom taxonomy title */
			'update_item' => 'Update Region', /* update title for taxonomy */
			'add_new_item' => 'Add New Region', /* add new title for taxonomy */
			'new_item_name' => 'New Region Name' /* name title for taxonomy */
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => false
	)
);

register_taxonomy( 'video-category',
	array('videos'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'Video Categories', /* name of the custom taxonomy */
			'singular_name' => 'Video Category', /* single taxonomy name */
			'search_items' =>  'Search Video Categories', /* search title for taxomony */
			'all_items' => 'All Video Categories',  /*all title for taxonomies */
			'parent_item' => 'Parent Video Category', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent Video Category:', /* parent taxonomy title */
			'edit_item' => 'Edit Video Category', /* edit custom taxonomy title */
			'update_item' => 'Update Video Category', /* update title for taxonomy */
			'add_new_item' => 'Add New Video Category', /* add new title for taxonomy */
			'new_item_name' => 'New Video Category Name' /* name title for taxonomy */
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => false
	)
);

/**
 *	Add metadata to region taxonomy for translation purposes
 */

function region_term_metabox($term) {
	$es_ES = get_term_meta($term->term_id, 'es_ES', true);
	$es_AR = get_term_meta($term->term_id, 'es_AR', true);
	$de_DE = get_term_meta($term->term_id, 'de_DE', true);
	$fr_FR = get_term_meta($term->term_id, 'fr_FR', true);
	$it_IT = get_term_meta($term->term_id, 'it_IT', true);
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="fr_FR">Français</label></th>
		<td><input type="text" name="fr_FR" id="fr_FR" value="<?php echo $fr_FR; ?>" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="de_DE">Deutsch</label></th>
		<td><input type="text" name="de_DE" id="de_DE" value="<?php echo $de_DE; ?>" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="it_IT">Italiano</label></th>
		<td><input type="text" name="it_IT" id="it_IT" value="<?php echo $it_IT; ?>" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="es_ES">Español - España</label></th>
		<td><input type="text" name="es_ES" id="es_ES" value="<?php echo $es_ES; ?>" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="es_AR">Español - América Latina</label></th>
		<td><input type="text" name="es_AR" id="es_AR" value="<?php echo $es_AR; ?>" /></td>
	</tr>

	<?php
}
add_action('edit_tag_form_fields', 'region_term_metabox');

function save_region_term_data($term_id) {
	if (isset($_POST['es_ES'])) {
		$es_ES = esc_attr($_POST['es_ES']);
		update_term_meta($term_id, 'es_ES', $es_ES);
	}
	if (isset($_POST['es_AR'])) {
		$es_AR = esc_attr($_POST['es_AR']);
		update_term_meta($term_id, 'es_AR', $es_AR);
	}
	if (isset($_POST['de_DE'])) {
		$de_DE = esc_attr($_POST['de_DE']);
		update_term_meta($term_id, 'de_DE', $de_DE);
	}
	if (isset($_POST['fr_FR'])) {
		$fr_FR = esc_attr($_POST['fr_FR']);
		update_term_meta($term_id, 'fr_FR', $fr_FR);
	}
	if (isset($_POST['it_IT'])) {
		$it_IT = esc_attr($_POST['it_IT']);
		update_term_meta($term_id, 'it_IT', $it_IT);
	}
}
add_action('edited_terms', 'save_region_term_data');



// Add columns in the admin view for the sales network
function nomacorc_sales_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Title',
		'type' => 'Type',
		'region' => 'Region',
		'lat-lng' => 'Lat/Lng',
		'date' => 'Added Date'
	);
	return $columns;
}
// Add dates to new columns
function nomacorc_sales_custom_columns($column) {
	global $post;
	if ('type' == $column) {
		$type = get_field('type');
		if ($type) {
			foreach ($type as $t) {
				$ts[] = $t->name;
			}
			echo implode(', ', $ts);
		}
	} elseif ('region' == $column) {
		$region = get_field('region');
		if ($region) {
			foreach ($region as $r) {
				$rs[] = $r->name;
			}
			echo implode(', ', $rs);
		}
	} elseif ('lat-lng' == $column) {
		$geo = get_post_meta($post->ID, 'pin_location', true);
		if ($geo) {
			echo $geo['lat'] . '/' . $geo['lng'];
		}
	}
}
// Make columns sortable
function nomacorc_sales_sort($columns) {
	$columns = array(
		'title' => 'title',
		'date' => 'date'
	);
	return $columns;
}
add_action('manage_posts_custom_column', 'nomacorc_sales_custom_columns');
add_filter('manage_edit-sales_columns', 'nomacorc_sales_columns');
add_filter('manage_edit-sales_sortable_columns', 'nomacorc_sales_sort');


?>
