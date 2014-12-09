<?php
/**
* Custom Post Types
*
* Defines all custom post types and custom taxonomies
*
* @package EducationNC
*/

function add_custom_post_types() {
	register_post_type( 'feature',
		array('labels' => array(
			'name' => 'Features',
			'singular_name' => 'Feature',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Feature',
			'edit' => 'Edit',
			'edit_item' => 'Edit Feature',
			'new_item' => 'New Feature',
			'view_item' => 'View Feature',
			'search_items' => 'Search Feature',
			'not_found' =>  'Nothing found in the Database.',
			'not_found_in_trash' => 'Nothing found in Trash',
			'parent_item_colon' => ''
		), /* end of arrays */
		'public' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'menu_position' => 8,
		//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'revisions', 'comments', 'trackbacks'),
		'has_archive' => false,
		'rewrite' => true,
		'query_var' => true
	)
	);

	register_post_type( 'underwriter',
		array('labels' => array(
				'name' => 'Underwriters',
				'singular_name' => 'Underwriter',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Underwriter',
				'edit' => 'Edit',
				'edit_item' => 'Edit Underwriter',
				'new_item' => 'New Underwriter',
				'view_item' => 'View Underwriter',
				'search_items' => 'Search Underwriter',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
	 	)
	);

	register_post_type( 'gallery',
		array('labels' => array(
				'name' => 'Galleries',
				'singular_name' => 'Gallery',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Gallery',
				'edit' => 'Edit',
				'edit_item' => 'Edit Gallery',
				'new_item' => 'New Gallery',
				'view_item' => 'View Gallery',
				'search_items' => 'Search Gallery',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'ednews',
		array('labels' => array(
				'name' => 'EdNews',
				'singular_name' => 'EdNews',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New EdNews',
				'edit' => 'Edit',
				'edit_item' => 'Edit EdNews',
				'new_item' => 'New EdNews',
				'view_item' => 'View EdNews',
				'search_items' => 'Search EdNews',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'district',
		array('labels' => array(
				'name' => 'Districts',
				'singular_name' => 'District',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New District',
				'edit' => 'Edit',
				'edit_item' => 'Edit District',
				'new_item' => 'New District',
				'view_item' => 'View District',
				'search_items' => 'Search District',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions', 'thumbnail'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'bio',
	array('labels' => array(
		'name' => 'Bios',
		'singular_name' => 'Bio',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Bio',
		'edit' => 'Edit',
		'edit_item' => 'Edit Bio',
		'new_item' => 'New Bio',
		'view_item' => 'View Bio',
		'search_items' => 'Search Bio',
		'not_found' =>  'Nothing found in the Database.',
		'not_found_in_trash' => 'Nothing found in Trash',
		'parent_item_colon' => ''
	), /* end of arrays */
	'public' => true,
	'exclude_from_search' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_nav_menus' => false,
	'menu_position' => 8,
	//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
	'capability_type' => 'post',
	'hierarchical' => false,
	'supports' => array( 'title', 'editor', 'revisions', 'thumbnail'),
	'has_archive' => false,
	'rewrite' => true,
	'query_var' => true
)
);
}
add_action( 'init', 'add_custom_post_types');


register_taxonomy( 'district-type',
	array('district'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'District Types', /* name of the custom taxonomy */
			'singular_name' => 'District Type', /* single taxonomy name */
			'search_items' =>  'Search District Types', /* search title for taxomony */
			'all_items' => 'All District Types', /* all title for taxonomies */
			'parent_item' => 'Parent Disrict Type', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent District Type:', /* parent taxonomy title */
			'edit_item' => 'Edit District Type', /* edit custom taxonomy title */
			'update_item' => 'Update District Type', /* update title for taxonomy */
			'add_new_item' => 'Add New District Type', /* add new title for taxonomy */
			'new_item_name' => 'New District Type Name' /* name title for taxonomy */
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
