<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  // Add authors names to posts
  if (is_single()) {
    if ( function_exists( 'coauthors_posts_links' ) ) {
      $authors = get_coauthors();
      foreach ($authors as $a) {
        $classes[] = $a->user_nicename;
      }
    } else {
      $classes[] = get_the_author_meta('user_nicename');
    }
  }

  if (is_page('story-2015-16')) {
    $classes[] = 'single';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Utility function to test if page has children
 */
 function has_children($type = 'page') {
   global $post;

   $children = get_pages(['child_of' => $post->ID, 'post_type' => $type]);
   if( count( $children ) == 0 ) {
     return false;
   } else {
     return true;
   }
}

/**
 * Get post ID by full post URL
 */
function full_url_to_postid($url) {
  global $wpdb;
  $postid = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));
  $postid = array_filter($postid);
  if (!empty($postid)) {
    return $postid[0];
  }
}

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
* Retrieve adjacent post by author.
* Modified function from get_adjacent_post in wp-includes/link-template.php
*
* Can either be next or previous post.
*
* @param bool         $previous       Optional. Whether to retrieve previous post.
* @return mixed       Post object if successful. Null if global $post is not set. Empty string if no corresponding post exists.
*/
function get_adjacent_author_post( $previous = true ) {
  global $wpdb;

  if ( ( ! $post = get_post() ) )
  return null;

  $current_post_date = $post->post_date;

  $author = $post->post_author;

  $join = '';
  $where = '';

  $adjacent = $previous ? 'previous' : 'next';
  $op = $previous ? '<' : '>';
  $order = $previous ? 'DESC' : 'ASC';

  /**
  * Filter the JOIN clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $join           The JOIN clause in the SQL.
  * @param bool   $in_same_term   Whether post should be in a same taxonomy term.
  * @param array  $excluded_terms Array of excluded term IDs.
  */
  $in_same_term = false;
  $excluded_terms = '';
  $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_term, $excluded_terms );

  print_r($join);

  /**
  * Filter the WHERE clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $where          The `WHERE` clause in the SQL.
  * @param bool   $in_same_term   Whether post should be in a same taxonomy term.
  * @param array  $excluded_terms Array of excluded term IDs.
  */
  $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare( "WHERE p.post_date $op %s AND p.post_author = %d AND p.post_type = %s AND p.post_status = 'publish' $where", $current_post_date, $author, $post->post_type ), $in_same_term, $excluded_terms );

  /**
  * Filter the ORDER BY clause in the SQL for an adjacent post query.
  *
  * The dynamic portion of the hook name, `$adjacent`, refers to the type
  * of adjacency, 'next' or 'previous'.
  *
  * @since 2.5.0
  *
  * @param string $order_by The `ORDER BY` clause in the SQL.
  */
  $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

  $query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";
  $query_key = 'adjacent_post_' . md5( $query );
  $result = wp_cache_get( $query_key, 'counts' );
  if ( false !== $result ) {
    if ( $result )
    $result = get_post( $result );
    return $result;
  }

  $result = $wpdb->get_var( $query );
  if ( null === $result )
  $result = '';

  wp_cache_set( $query_key, $result, 'counts' );

  if ( $result )
  $result = get_post( $result );

  return $result;
}

/**
 * Extend plugin Taxonomy Term Image to multiple taxonomies
 */
function the_term_image_taxonomy( $taxonomy ) {
  return array('column', 'category', 'map-column');
}
add_filter( 'taxonomy-term-image-taxonomy', __NAMESPACE__ . '\\the_term_image_taxonomy' );


/**
* Add options pages for weekly wrapups
*/
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
    'page_title'     => 'Weekly Wrapup',
    'menu_title'    => 'Weekly Wrapup',
    'menu_slug'     => 'weekly-wrapup',
    'redirect'        => false
  ));
}

/**
 * Modify TinyMCE editor to remove unused items
 */
add_filter('tiny_mce_before_init', function($init) {
  // Block format elements to show in dropdown
  $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h3;Heading 5=h5;Heading 6=h6;';
	return $init;
});

/**
 * Retrieves the embed code for a specific post.
 * MODIFIED: To allow-popups in sandboxed iframe
 *
 * @since 4.4.0
 *
 * @param int         $width  The width for the response.
 * @param int         $height The height for the response.
 * @param int|WP_Post $post   Optional. Post ID or object. Default is global `$post`.
 * @return string|false Embed code on success, false if post doesn't exist.
 */
function get_post_embed_html( $width, $height, $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$embed_url = get_post_embed_url( $post );

	$output = '<blockquote class="wp-embedded-content"><a href="' . esc_url( get_permalink( $post ) ) . '">' . get_the_title( $post ) . "</a></blockquote>\n";

	$output .= "<script type='text/javascript'>\n";
	$output .= "<!--//--><![CDATA[//><!--\n";
	if ( SCRIPT_DEBUG ) {
		$output .= file_get_contents( ABSPATH . WPINC . '/js/wp-embed.js' );
	} else {
		/*
		 * If you're looking at a src version of this file, you'll see an "include"
		 * statement below. This is used by the `grunt build` process to directly
		 * include a minified version of wp-embed.js, instead of using the
		 * file_get_contents() method from above.
		 *
		 * If you're looking at a build version of this file, you'll see a string of
		 * minified JavaScript. If you need to debug it, please turn on SCRIPT_DEBUG
		 * and edit wp-embed.js directly.
		 */
		$output .=<<<JS
		!function(a,b){"use strict";function c(){if(!e){e=!0;var a,c,d,f,g=-1!==navigator.appVersion.indexOf("MSIE 10"),h=!!navigator.userAgent.match(/Trident.*rv:11\./),i=b.querySelectorAll("iframe.wp-embedded-content"),j=b.querySelectorAll("blockquote.wp-embedded-content");for(c=0;c<j.length;c++)j[c].style.display="none";for(c=0;c<i.length;c++)if(d=i[c],d.style.display="",!d.getAttribute("data-secret")){if(f=Math.random().toString(36).substr(2,10),d.src+="#?secret="+f,d.setAttribute("data-secret",f),g||h)a=d.cloneNode(!0),a.removeAttribute("security"),d.parentNode.replaceChild(a,d)}else;}}var d=!1,e=!1;if(b.querySelector)if(a.addEventListener)d=!0;if(a.wp=a.wp||{},!a.wp.receiveEmbedMessage)if(a.wp.receiveEmbedMessage=function(c){var d=c.data;if(d.secret||d.message||d.value)if(!/[^a-zA-Z0-9]/.test(d.secret)){var e,f,g,h,i,j=b.querySelectorAll('iframe[data-secret="'+d.secret+'"]'),k=b.querySelectorAll('blockquote[data-secret="'+d.secret+'"]');for(e=0;e<k.length;e++)k[e].style.display="none";for(e=0;e<j.length;e++)if(f=j[e],c.source===f.contentWindow){if(f.style.display="","height"===d.message){if(g=parseInt(d.value,10),g>1e3)g=1e3;else if(200>~~g)g=200;f.height=g}if("link"===d.message)if(h=b.createElement("a"),i=b.createElement("a"),h.href=f.getAttribute("src"),i.href=d.value,i.host===h.host)if(b.activeElement===f)a.top.location.href=d.value}else;}},d)a.addEventListener("message",a.wp.receiveEmbedMessage,!1),b.addEventListener("DOMContentLoaded",c,!1),a.addEventListener("load",c,!1)}(window,document);
JS;
	}
	$output .= "\n//--><!]]>";
	$output .= "\n</script>";

	$output .= sprintf(
		'<iframe sandbox="allow-scripts allow-popups allow-popups-to-escape-sandbox" security="restricted" src="%1$s" width="%2$d" height="%3$d" title="%4$s" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>',
		esc_url( $embed_url ),
		absint( $width ),
		absint( $height ),
		esc_attr__( 'Embedded EdNC.org Post' )
	);

	/**
	 * Filter the embed HTML output for a given post.
	 *
	 * @since 4.4.0
	 *
	 * @param string  $output The default HTML.
	 * @param WP_Post $post   Current post object.
	 * @param int     $width  Width of the response.
	 * @param int     $height Height of the response.
	 */
	return apply_filters( 'embed_html', $output, $post, $width, $height );
}

/**
 * Modify FB Instant Articles RSS feed output for content
 *
 * Have to make sure embeds and images all appear correctly. For some reason
 * (and I don't understand why), the filter 'the_content' is not rendering the
 * content the same in this feed as it is on the front end of site. Debugged
 * by changing theme to Twenty Sixteen and disabling all plugins. Still rendered
 * shortcodes differently in feed and on front end.
 */

function preg_replace_all($find, $replacement, $s) {
	while(preg_match($find, $s)) {
		$s = preg_replace($find, $replacement, $s);
	}
	return $s;
}

add_filter('instant_articles_content', function($content) {

	// Replace all whitespace with spaces
	$content = trim(preg_replace('/\s+/', ' ', $content));

	// Make sure there are no spaces between HTML tags
	$content = str_replace('> <', '><', $content);

	// Strip all divs
	$content = preg_replace_all('/(?:<div(?:.*?)>)(.*?)<\/div>/i', '$1', $content);
	// Strip all links around images
	$content = preg_replace_all('/<a.*?(<img.*?>)<\/a>/', '$1', $content);
	// Replace all <br>s between images with <p>s
	$content = preg_replace_all('/(<img.*?)(<br.*?>)(<img)/', '$1</p><p>$3', $content);

  return $content;
});
