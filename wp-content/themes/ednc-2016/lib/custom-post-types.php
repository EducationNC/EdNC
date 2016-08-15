<?php

namespace Roots\Sage\CPT;

function register_post_types() {
	register_post_type( 'ad',
		array('labels' => array(
				'name' => 'Ads',
				'singular_name' => 'Ad',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Ad',
				'edit' => 'Edit',
				'edit_item' => 'Edit Ad',
				'new_item' => 'New Ad',
				'view_item' => 'View Ad',
				'search_items' => 'Search Ads',
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
				'name' => 'Editor\'s Picks',
				'singular_name' => 'Editor\'s Picks',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Editor\'s Picks',
				'edit' => 'Edit',
				'edit_item' => 'Edit Editor\'s Picks',
				'new_item' => 'New Editor\'s Picks',
				'view_item' => 'View Editor\'s Picks',
				'search_items' => 'Search Editor\'s Picks',
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
			'capability_type' => array('district','districts'),
			'map_meta_cap' => true,
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions', 'thumbnail'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

		register_post_type( 'legislator',
			array('labels' => array(
					'name' => 'Legislators',
					'singular_name' => 'Legislator',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Legislator',
					'edit' => 'Edit',
					'edit_item' => 'Edit Legislator',
					'new_item' => 'New Legislator',
					'view_item' => 'View Legislator',
					'search_items' => 'Search Legislator',
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
				'supports' => array( 'title', 'revisions', 'thumbnail', 'page-attributes'),
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
			'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'map',
		array('labels' => array(
				'name' => 'Maps',
				'singular_name' => 'Map',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Map',
				'edit' => 'Edit',
				'edit_item' => 'Edit Map',
				'new_item' => 'New Map',
				'view_item' => 'View Map',
				'search_items' => 'Search Map',
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
			'supports' => array( 'title', 'editor', 'author', 'revisions', 'thumbnail', 'comments'),
			'has_archive' => false,
			'rewrite' => false,	// set to false and then create custom rewrite rules below
			'query_var' => true
		)
	);

	register_post_type( 'resource',
		array('labels' => array(
				'name' => 'Resources',
				'singular_name' => 'Resource',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Resource',
				'edit' => 'Edit',
				'edit_item' => 'Edit Resource',
				'new_item' => 'New Resource',
				'view_item' => 'View Resource',
				'search_items' => 'Search Resources',
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
			'supports' => array( 'title', 'editor', 'revisions'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
		)
	);

	register_post_type( 'bill',
		array('labels' => array(
				'name' => 'Bills',
				'singular_name' => 'Bill',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Bill',
				'edit' => 'Edit',
				'edit_item' => 'Edit Bill',
				'new_item' => 'New Bill',
				'view_item' => 'View Bill',
				'search_items' => 'Search Bills',
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
			'supports' => array( 'title', 'revisions', 'page-attributes'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true
		)
	);

	register_post_type( 'flash-cards',
		array('labels' => array(
				'name' => 'Flash Cards',
				'singular_name' => 'Flash Cards',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Flash Cards',
				'edit' => 'Edit',
				'edit_item' => 'Edit Flash Cards',
				'new_item' => 'New Flash Cards',
				'view_item' => 'View Flash Cards',
				'search_items' => 'Search Flash Cards',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'taxonomies' => array('category'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'revisions', 'thumbnail', 'author'),
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true
		)
	);

	register_post_type( 'edtalk',
		array('labels' => array(
				'name' => 'EdTalk',
				'singular_name' => 'EdTalk Episode',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New EdTalk Episode',
				'edit' => 'Edit',
				'edit_item' => 'Edit EdTalk Episode',
				'new_item' => 'New EdTalk Episode',
				'view_item' => 'View EdTalk Episode',
				'search_items' => 'Search EdTalk Episode',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'taxonomies' => array('category'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'author', 'revisions', 'editor', 'comments'),
			'has_archive' => true,
			'rewrite' => true,
			'query_var' => true
		)
	);

}
add_action( 'init', __NAMESPACE__ . '\\register_post_types');


register_taxonomy( 'ad-type',
	array('ad'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
	'labels' => array(
		'name' => 'Ad Types', /* name of the custom taxonomy */
		'singular_name' => 'Ad Type', /* single taxonomy name */
		'search_items' =>  'Search Ad Types', /* search title for taxomony */
		'all_items' => 'All Ad Types',  /*all title for taxonomies */
		'parent_item' => 'Parent Ad Type', /* parent title for taxonomy */
		'parent_item_colon' => 'Parent Ad Type:', /* parent taxonomy title */
		'edit_item' => 'Edit Ad Type', /* edit custom taxonomy title */
		'update_item' => 'Update Ad Type', /* update title for taxonomy */
		'add_new_item' => 'Add New Ad Type', /* add new title for taxonomy */
		'new_item_name' => 'New Ad Type Name' /* name title for taxonomy */
	),
	'show_ui' => true,
	'query_var' => true,
	'public' => false
	)
);

register_taxonomy( 'district-type',
	array('district'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
	'labels' => array(
		'name' => 'District Types', /* name of the custom taxonomy */
		'singular_name' => 'District Type', /* single taxonomy name */
		'search_items' =>  'Search District Types', /* search title for taxomony */
		'all_items' => 'All District Types',  /*all title for taxonomies */
		'parent_item' => 'Parent District Type', /* parent title for taxonomy */
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

register_taxonomy( 'author-type',
	array('bio'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'Author Types', /* name of the custom taxonomy */
			'singular_name' => 'Author Type', /* single taxonomy name */
			'search_items' =>  'Search Author Types', /* search title for taxomony */
			'all_items' => 'All Author Types',  /*all title for taxonomies */
			'parent_item' => 'Parent Author Type', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent Author Type:', /* parent taxonomy title */
			'edit_item' => 'Edit Author Type', /* edit custom taxonomy title */
			'update_item' => 'Update Author Type', /* update title for taxonomy */
			'add_new_item' => 'Add New Author Type', /* add new title for taxonomy */
			'new_item_name' => 'New Author Type Name' /* name title for taxonomy */
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => false
	)
);

register_taxonomy( 'author-year',
	array('bio'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => 'Contributing Years', /* name of the custom taxonomy */
			'singular_name' => 'Contributing Year', /* single taxonomy name */
			'search_items' =>  'Search Contributing Years', /* search title for taxomony */
			'all_items' => 'All Contributing Years',  /*all title for taxonomies */
			'parent_item' => 'Parent Contributing Year', /* parent title for taxonomy */
			'parent_item_colon' => 'Parent Contributing Year:', /* parent taxonomy title */
			'edit_item' => 'Edit Contributing Year', /* edit custom taxonomy title */
			'update_item' => 'Update Contributing Year', /* update title for taxonomy */
			'add_new_item' => 'Add New Contributing Year', /* add new title for taxonomy */
			'new_item_name' => 'New Contributing Year Name' /* name title for taxonomy */
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => false
	)
);

register_taxonomy( 'resource-type',
	array('resource'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Resource Types' ),
			'singular_name' => __( 'Resource Type' ),
			'search_items' =>  __( 'Search Resource Types' ),
			'all_items' => __( 'All Resource Types' ),
			'parent_item' => __( 'Parent Resource Type' ),
			'parent_item_colon' => __( 'Parent Resource Type:' ),
			'edit_item' => __( 'Edit Resource Type' ),
			'update_item' => __( 'Update Resource Type' ),
			'add_new_item' => __( 'Add New Resource Type' ),
			'new_item_name' => __( 'New Resource Type Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'session',
	array('bill'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Sessions' ),
			'singular_name' => __( 'Session' ),
			'search_items' =>  __( 'Search Sessions' ),
			'all_items' => __( 'All Sessions' ),
			'parent_item' => __( 'Parent Session' ),
			'parent_item_colon' => __( 'Parent Session:' ),
			'edit_item' => __( 'Edit Session' ),
			'update_item' => __( 'Update Session' ),
			'add_new_item' => __( 'Add New Session' ),
			'new_item_name' => __( 'New Session Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'bill-type',
	array('bill'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Bill Types' ),
			'singular_name' => __( 'Bill Type' ),
			'search_items' =>  __( 'Search Bill Types' ),
			'all_items' => __( 'All Bill Types' ),
			'parent_item' => __( 'Parent Bill Type' ),
			'parent_item_colon' => __( 'Parent Bill Type:' ),
			'edit_item' => __( 'Edit Bill Type' ),
			'update_item' => __( 'Update Bill Type' ),
			'add_new_item' => __( 'Add New Bill Type' ),
			'new_item_name' => __( 'New Bill Type Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'bill-status',
	array('bill'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Bill Status' ),
			'singular_name' => __( 'Bill Status' ),
			'search_items' =>  __( 'Search Bill Statuses' ),
			'all_items' => __( 'All Bill Statuses' ),
			'parent_item' => __( 'Parent Bill Status' ),
			'parent_item_colon' => __( 'Parent Bill Status:' ),
			'edit_item' => __( 'Edit Bill Status' ),
			'update_item' => __( 'Update Bill Status' ),
			'add_new_item' => __( 'Add New Bill Status' ),
			'new_item_name' => __( 'New Bill Status Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'appearance',
	array('post', 'map', 'edtalk', 'flash-cards'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Appearances' ),
			'singular_name' => __( 'Appearance' ),
			'search_items' =>  __( 'Search Appearances' ),
			'all_items' => __( 'All Appearances' ),
			'parent_item' => __( 'Parent Appearance' ),
			'parent_item_colon' => __( 'Parent Appearance:' ),
			'edit_item' => __( 'Edit Appearance' ),
			'update_item' => __( 'Update Appearance' ),
			'add_new_item' => __( 'Add New Appearance' ),
			'new_item_name' => __( 'New Appearance Name' )
		),
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		'rewrite' => true
	)
);

register_taxonomy( 'column',
	array('post', 'map', 'edtalk', 'flash-cards'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Columns' ),
			'singular_name' => __( 'Column' ),
			'search_items' =>  __( 'Search Columns' ),
			'all_items' => __( 'All Columns' ),
			'parent_item' => __( 'Parent Column' ),
			'parent_item_colon' => __( 'Parent Column:' ),
			'edit_item' => __( 'Edit Column' ),
			'update_item' => __( 'Update Column' ),
			'add_new_item' => __( 'Add New Column' ),
			'new_item_name' => __( 'New Column Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'district-posts',
	array('post'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Districts' ),
			'singular_name' => __( 'District' ),
			'search_items' =>  __( 'Search Districts' ),
			'all_items' => __( 'All Districts' ),
			'parent_item' => __( 'Parent District' ),
			'parent_item_colon' => __( 'Parent District:' ),
			'edit_item' => __( 'Edit District' ),
			'update_item' => __( 'Update District' ),
			'add_new_item' => __( 'Add New District' ),
			'new_item_name' => __( 'New District Name' )
		),
		'show_ui' => true,
		'rewrite' => 'district-posts'
	)
);

register_taxonomy( 'map-category',
	array('map'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Map Categories' ),
			'singular_name' => __( 'Map Category' ),
			'search_items' =>  __( 'Search Map Categories' ),
			'all_items' => __( 'All Map Categories' ),
			'parent_item' => __( 'Parent Map Category' ),
			'parent_item_colon' => __( 'Parent Map Category:' ),
			'edit_item' => __( 'Edit Map Category' ),
			'update_item' => __( 'Update Map Category' ),
			'add_new_item' => __( 'Add New Map Category' ),
			'new_item_name' => __( 'New Map Category Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);

register_taxonomy( 'map-column',
	array('map'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Map Columns' ),
			'singular_name' => __( 'Map Column' ),
			'search_items' =>  __( 'Search Map Column' ),
			'all_items' => __( 'All Map Column' ),
			'parent_item' => __( 'Parent Map Column' ),
			'parent_item_colon' => __( 'Parent Map Column:' ),
			'edit_item' => __( 'Edit Map Column' ),
			'update_item' => __( 'Update Map Column' ),
			'add_new_item' => __( 'Add New Map Column' ),
			'new_item_name' => __( 'New Map Column Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);


/**
 * Modify queries on specific templates
 */
function pre_get_posts($query) {
	// all archives should hide anything tagged with 'hide from archives'
	if ($query->is_author() || $query->is_category() || $query->is_date()) {
		$tax_query = array(
			array(
				'taxonomy' => 'appearance',
				'field' => 'slug',
				'terms' => 'hide-from-archives',
				'operator' => 'NOT IN'
			)
		);
		$query->set('tax_query', $tax_query);
	}

	// resource-type should query the resource CPT
	if ($query->is_tax('resource-type')) {
		$query->set('post_type', 'resource');
	}

	// bill-type and session taxonomies should query bills and show them in menu order
	if ($query->is_tax('bill-type') || $query->is_tax('session')) {
		$query->set('post_type', 'bill');
		$query->set('orderby', 'menu_order');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}

	// author archives should query posts and maps
	if ($query->is_author()) {
		$query->set('post_type', array('post', 'map', 'edtalk'));
	}

	// 1868 category archives should show in asc order
	if ($query->is_category('1868-constitutional-convention')) {
		$query->set('order' , 'ASC');
	}

	// include ednews podcasts in category archives
	if ($query->is_category() && $query->is_main_query()) {
		$query->set('post_type', ['post', 'edtalk']);
	}

	// date archives should show extra post types
	if ($query->is_day()) {
		$query->set('post_type', ['post', 'map', 'ednews', 'edtalk', 'flash-cards']);
		$tax_query = array(
			array(
				'taxonomy' => 'appearance',
				'field' => 'slug',
				'terms' => 'hide-from-archives',
				'operator' => 'NOT IN'
			)
		);
		$query->set('tax_query', $tax_query);
		$query->set('posts_per_page', '-1');
		$query->set('nopaging', true);
	}

	// additional date archive for taxonomy archives
	if ($query->is_tax() || $query->is_category()) {
		if (isset($_GET['date'])) {
			if ($query->get('fields') != 'ids') {
				$date_array = explode('/', $_GET['date']);
				$date_query = array(
					array(
						'year' => $date_array[0],
						'month' => $date_array[1],
						'day' => $date_array[2]
					)
				);
				$query->set('date_query', $date_query);
			}
		}
	}
}
add_action('pre_get_posts', __NAMESPACE__ . '\\pre_get_posts');



/**
 * Add rewrite rules for map and edtalk permalinks
 * http://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2
 *
 */
function rewrite_rules() {
	global $wp_rewrite;

	$map_permalink_structure = '/map/%year%/%monthnum%/%map%';
	$wp_rewrite->add_rewrite_tag("%map%", '([^/]+)', "map=");
	$wp_rewrite->add_permastruct('map', $map_permalink_structure, false);

	$edtalk_permalink_structure = '/edtalk/%year%/%monthnum%/%day%/%edtalk%';
	$wp_rewrite->add_rewrite_tag("%edtalk%", '([^/]+)', "edtalk=");
	$wp_rewrite->add_permastruct('edtalk', $edtalk_permalink_structure, false);
}
add_action('init', __NAMESPACE__ . '\\rewrite_rules');

// Translate custom post type permalink tokens (%year% and %monthnum%)
// Adapted from get_permalink function in wp-includes/link-template.php
function replace_permalink_tokens($permalink, $post_id, $leavename) {
  $post = get_post($post_id);
  $rewritecode = array(
    '%year%',
    '%monthnum%',
    '%day%',
    '%hour%',
    '%minute%',
    '%second%',
    $leavename? '' : '%postname%',
    '%post_id%',
    '%category%',
    '%author%',
    $leavename? '' : '%pagename%',
  );

  if ( '' != $permalink && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
      $unixtime = strtotime($post->post_date);

      $category = '';
      if ( strpos($permalink, '%category%') !== false ) {
          $cats = get_the_category($post->ID);
          if ( $cats ) {
              usort($cats, '_usort_terms_by_ID'); // order by ID
              $category = $cats[0]->slug;
              if ( $parent = $cats[0]->parent )
                  $category = get_category_parents($parent, false, '/', true) . $category;
          }
          // show default category in permalinks, without
          // having to assign it explicitly
          if ( empty($category) ) {
              $default_category = get_category( get_option( 'default_category' ) );
              $category = is_wp_error( $default_category ) ? '' : $default_category->slug;
          }
      }

      $author = '';
      if ( strpos($permalink, '%author%') !== false ) {
          $authordata = get_userdata($post->post_author);
          $author = $authordata->user_nicename;
      }

      $date = explode(" ",date('Y m d H i s', $unixtime));
      $rewritereplace =
      array(
          $date[0],
          $date[1],
          $date[2],
          $date[3],
          $date[4],
          $date[5],
          $post->post_name,
          $post->ID,
          $category,
          $author,
          $post->post_name,
      );
      $permalink = str_replace($rewritecode, $rewritereplace, $permalink);
  } else { // if they're not using the fancy permalink option
  }
  return $permalink;
}
add_filter('post_type_link', __NAMESPACE__ . '\\replace_permalink_tokens', 10, 3);
