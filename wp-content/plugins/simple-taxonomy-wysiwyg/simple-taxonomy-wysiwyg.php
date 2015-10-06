<?php
/**
 * Plugin Name: Simple Taxonomy WYSIWYG
 * Plugin URI: https://wordpress.org/plugins/simple-taxonomy-wysiwyg/
 * Description: This is a very simple and lightweight plugin that will convert the taxonomy/category description textarea to a WYSIWYG (TinyMCE).
 * Version: 1.3.1
 * Author: Earl Evan Amante
 * Author URI: https://github.com/earlamante/
 * License: GPL2
 */

/**
 * Common fix for adding menu page in plugins
 */
include(ABSPATH . "wp-includes/pluggable.php"); 

// NOTES: MAKE THIS TO AN OBJECT TO OPTIMIZE

Class W3B_WYSIWYG {
	var $screen;
	var $plugin_url;

	public function __construct() {
		$this->_declare_hooks();
	}

	private function _declare_hooks() {
		add_action('current_screen', array( $this, 'init' ));
		add_action('admin_head', array( $this, 'convert_textarea_js' ));
		add_action('admin_footer', array( $this, 'convert_textarea' ));

		add_action('created_term', array( $this, 'force_HTML_description' ), 1, 3);
	}

	public function init() {
		$this->screen = get_current_screen();
		$this->plugin_url = plugin_dir_url( __FILE__ );

	}

	function convert_textarea_js() {
		if( $this->screen->base == 'edit-tags' )
			echo '<script src="' . $this->plugin_url . 'js/w3b_convert_textarea.js" type="text/javascript"></script>'."\n";
	}

	public function convert_textarea() {
		if( $this->screen->base == 'edit-tags' ) {
			$content = '';
			$rows = 5;
			if( !empty( $_GET['tag_ID'] ) ) {
				global $tag;
				$content = $tag->description;
				$rows = 10;
			}
			echo '<div style="display: none;">';
			wp_editor( html_entity_decode($content), 'w3b_description', array(
				'textarea_rows'	=> $rows
			) );
			echo '
			</div>
			';
		}
	}

	public function force_HTML_description( $term_id, $tt_id, $taxonomy ) {
		global $wpdb;
		$wpdb->update(
			$wpdb->term_taxonomy,
			array(
				'description'		=> $_POST['description']
			),
			array(
				'term_taxonomy_id'	=> $tt_id
			),
			array(
				'%s'
			)
		);
	}
}

new W3B_WYSIWYG;

?>