<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.mailmunch.co
 * @since      2.0.0
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/public
 * @author     MailMunch <info@mailmunch.co>
 */
class Mailmunch_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode('mailmunch-form', array($this, 'shortcode_form'));
	}

	public function shortcode_form($atts) {
		return "<div class='mailmunch-forms-short-code mailmunch-forms-widget-".$atts['id']."' style='display: none !important;'></div>";
	}

	/**
	 * Register sidebar widget
	 *
	 * @since    2.0.0
	 */
	public function sidebar_widget() {
		register_widget( 'Mailmunch_Sidebar_Widget' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mailmunch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mailmunch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mailmunch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mailmunch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Appends code for wp_head in the public-facing side of the site.
	 *
	 * @since    2.0.9
	 */
	public function append_head() {
		$siteID = get_option(MAILMUNCH_PREFIX. '_site_id');

		if (is_single() || is_page()) {
		  $post = get_post();
		  $post_data = array("ID" => $post->ID, "post_name" => $post->post_name, "post_title" => $post->post_title, "post_type" => $post->post_type, "post_author" => $post->post_author, "post_status" => $post->post_status);
		}

		echo "<script type='text/javascript' data-cfasync='false'>";
		echo "var _mmunch = {'front': false, 'page': false, 'post': false, 'category': false, 'author': false, 'search': false, 'attachment': false, 'tag': false};";
		if (is_front_page() || is_home()) { echo "_mmunch['front'] = true;"; }
		if (is_page()) { echo "_mmunch['page'] = true; _mmunch['pageData'] = ".json_encode($post_data).";"; }
		if (is_single()) { echo "_mmunch['post'] = true; _mmunch['postData'] = ".json_encode($post_data)."; _mmunch['postCategories'] = ".json_encode(get_the_category())."; _mmunch['postTags'] = ".json_encode(get_the_tags())."; _mmunch['postAuthor'] = ".json_encode(array("name" => get_the_author_meta("display_name"), "ID" => get_the_author_meta("ID"))).";"; }
		if (is_category()) { echo "_mmunch['category'] = true; _mmunch['categoryData'] = ".json_encode(get_category(get_query_var('cat'))).";"; }
		if (is_search()) { echo "_mmunch['search'] = true;"; }
		if (is_author()) { echo "_mmunch['author'] = true;"; }
		if (is_tag()) { echo "_mmunch['tag'] = true;"; }
		if (is_attachment()) { echo "_mmunch['attachment'] = true;"; }
		echo "</script>";
		echo('<script data-cfasync="false" src="//a.mailmunch.co/app/v1/site.js" id="mailmunch-script" data-plugin="'.MAILMUNCH_PREFIX.'" data-mailmunch-site-id="'.$siteID.'" async></script>');

	}

	/**
	 * Adds MailMunch form container in middle of paragraphs
	 *
	 * @since    2.0.0
	 */
	function insert_form_after_paragraph($insertion, $paragraph_id, $content) {
	  $closing_p = '</p>';
	  $paragraphs = explode($closing_p, $content);
	  if ($paragraph_id == "middle") {
	    $paragraph_id = round(sizeof($paragraphs)/2);
	  }

	  foreach ($paragraphs as $index => $paragraph) {
	    if (trim($paragraph)) {
	      $paragraphs[$index] .= $closing_p;
	    }

	    if ($paragraph_id == $index + 1) {
	      $paragraphs[$index] .= $insertion;
	    }
	  }
	  return implode('', $paragraphs);
	}

	/**
	 * Adds post containers for before, after and in the middle of post
	 *
	 * @since    2.0.0
	 */
	public function add_post_containers($content) {
		if (is_single() || is_page()) {
		  $content = $this->insert_form_after_paragraph("<div class='mailmunch-forms-in-post-middle' style='display: none !important;'></div>", "middle", $content);
		  $content = "<div class='mailmunch-forms-before-post' style='display: none !important;'></div>" . $content . "<div class='mailmunch-forms-after-post' style='display: none !important;'></div>";
		}

		return $content;
	}

}
