<?php


/**
* Registers the 'author-type' taxonomy for users.  This is a taxonomy for the 'user' object type rather than a
* post being the object type.
*/
register_taxonomy(
'author-type',
'user',
array(
  'public' => true,
  'labels' => array(
    'name' => __( 'Author Type' ),
    'singular_name' => __( 'Author Type' ),
    'menu_name' => __( 'Author Types' ),
    'search_items' => __( 'Search Author Types' ),
    'popular_items' => __( 'Popular Author Types' ),
    'all_items' => __( 'All Author Types' ),
    'edit_item' => __( 'Edit Author Type' ),
    'update_item' => __( 'Update Author Type' ),
    'add_new_item' => __( 'Add New Author Type' ),
    'new_item_name' => __( 'New Author Type Name' ),
    'separate_items_with_commas' => __( 'Separate types with commas' ),
    'add_or_remove_items' => __( 'Add or remove types' ),
    'choose_from_most_used' => __( 'Choose from the most popular types' ),
  ),
  'rewrite' => false,
  'capabilities' => array(
    'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
    'edit_terms'   => 'edit_users',
    'delete_terms' => 'edit_users',
    'assign_terms' => 'read',
  ),
  'update_count_callback' => 'ednc_update_author_type_count' // Use a custom function to update the count.
  )
);

/**
* Function for updating the 'profession' taxonomy count.  What this does is update the count of a specific term
* by the number of users that have been given the term.  We're not doing any checks for users specifically here.
* We're just updating the count with no specifics for simplicity.
*
* See the _update_post_term_count() function in WordPress for more info.
*
* @param array $terms List of Term taxonomy IDs
* @param object $taxonomy Current taxonomy object of terms
*/
function ednc_update_author_type_count( $terms, $taxonomy ) {
  global $wpdb;

  foreach ( (array) $terms as $term ) {

    $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

    do_action( 'edit_term_taxonomy', $term, $taxonomy );
    $wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
    do_action( 'edited_term_taxonomy', $term, $taxonomy );
  }
}

/**
* Creates the admin page for the 'profession' taxonomy under the 'Users' menu.  It works the same as any
* other taxonomy page in the admin.  However, this is kind of hacky and is meant as a quick solution.  When
* clicking on the menu item in the admin, WordPress' menu system thinks you're viewing something under 'Posts'
* instead of 'Users'.  We really need WP core support for this.
*/
function ednc_add_author_type_admin_page() {

  $tax = get_taxonomy( 'author-type' );

  add_users_page(
  esc_attr( $tax->labels->menu_name ),
  esc_attr( $tax->labels->menu_name ),
  $tax->cap->manage_terms,
  'edit-tags.php?taxonomy=' . $tax->name
  );
}
add_action( 'admin_menu', 'ednc_add_author_type_admin_page' );

/* Fixes the parent menu for the user taxonomy */
function fix_user_tax_page( $parent_file = '' ) {
  global $pagenow;

  if ( ! empty( $_GET[ 'taxonomy' ] ) && $_GET[ 'taxonomy' ] == 'author-type' && $pagenow == 'edit-tags.php' ) {
    $parent_file = 'users.php';
  }

  return $parent_file;
}
add_filter( 'parent_file', 'fix_user_tax_page' );

/* Add section to the edit user page in the admin to select profession. */
add_action( 'show_user_profile', 'ednc_edit_author_type_section' );
add_action( 'edit_user_profile', 'ednc_edit_author_type_section' );

/**
* Adds an additional settings section on the edit user/profile page in the admin.  This section allows users to
* select a profession from a checkbox of terms from the profession taxonomy.  This is just one example of
* many ways this can be handled.
*
* @param object $user The user object currently being edited.
*/
function ednc_edit_author_type_section( $user ) {

  $tax = get_taxonomy( 'author-type' );

  /* Make sure the user can assign terms of the author-type taxonomy before proceeding. */
  if ( !current_user_can( $tax->cap->assign_terms ) )
  return;

  /* Get the terms of the 'author-type' taxonomy. */
  $terms = get_terms( 'author-type', array( 'hide_empty' => false ) ); ?>

  <h3><?php _e( 'Author Type' ); ?></h3>

  <table class="form-table">

    <tr>
      <th><label for="author-type"><?php _e( 'Select Author Type' ); ?></label></th>

      <td><?php

        /* If there are any author-type terms, loop through them and display checkboxes. */
        if ( !empty( $terms ) ) {

          foreach ( $terms as $term ) { ?>
            <input type="radio" name="author-type" id="author-type-<?php echo esc_attr( $term->slug ); ?>" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( true, is_object_in_term( $user->ID, 'author-type', $term ) ); ?> /> <label for="author-type-<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></label> <br />
          <?php }
        }

        /* If there are no author-type terms, display a message. */
        else {
          _e( 'There are no author types available.' );
        }

        ?>
      </td>
    </tr>

  </table>
<?php }

/* Update the profession terms when the edit user page is updated. */
add_action( 'personal_options_update', 'ednc_save_author_type_terms' );
add_action( 'edit_user_profile_update', 'ednc_save_author_type_terms' );

/**
* Saves the term selected on the edit user/profile page in the admin. This function is triggered when the page
* is updated.  We just grab the posted data and use wp_set_object_terms() to save it.
*
* @param int $user_id The ID of the user to save the terms for.
*/
function ednc_save_author_type_terms( $user_id ) {

  $tax = get_taxonomy( 'author-type' );

  /* Make sure the current user can edit the user and assign terms before proceeding. */
  if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
  return false;

  $term = esc_attr( $_POST['author-type'] );

  /* Sets the terms (we're just using a single term) for the user. */
  wp_set_object_terms( $user_id, array( $term ), 'author-type', false);

  clean_object_term_cache( $user_id, 'author-type' );
}
?>
