<?php


/**
 *
 * wp_get_archive hooks.
 *
 * @package Custom_Post_Type_Permalinks
 * @since 0.9.4
 *
 * */

class CPTP_Module_GetArchives extends CPTP_Module {

	public function add_hook() {
		if(get_option( "permalink_structure") != "") {
			add_filter( 'getarchives_join', array( $this,'getarchives_join'), 10, 2 ); // [steve]
			add_filter( 'getarchives_where', array( $this,'getarchives_where'), 10 , 2 );
			add_filter( 'get_archives_link', array( $this,'get_archives_link'), 20, 1 );
		}

	}

	/**
	 *
	 * wp_get_archives fix for custom post
	 * Ex:wp_get_archives('&post_type='.get_query_var( 'post_type' ));
	 * @version 2.0
	 *
	 */

	public $get_archives_where_r;

	// function modified by [steve]
	public function getarchives_where( $where, $r ) {
		$this->get_archives_where_r = $r;
		if ( isset($r['post_type']) ) {
			$where = str_replace( '\'post\'', '\'' . $r['post_type'] . '\'', $where );
		}

		if(isset($r['taxonomy']) && is_array($r['taxonomy']) ){
			global $wpdb;
			$where = $where . " AND $wpdb->term_taxonomy.taxonomy = '".$r['taxonomy']['name']."' AND $wpdb->term_taxonomy.term_id = '".$r['taxonomy']['termid']."'";
		}

		return $where;
	}



	//function added by [steve]
	/**
	 *
	 * get_archive_join
	 * @author Steve
	 * @since 0.8
	 * @version 1.0
	 *
	 *
	 */
	public function getarchives_join( $join, $r ) {
		global $wpdb;
		$this->get_archives_where_r = $r;
		if(isset($r['taxonomy']) && is_array($r['taxonomy']) )
		$join = $join . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";

		return $join;
	}



	/**
	 *
	 * get_arcihves_link
	 * @version 2.2 03/27/14
	 *
	 */
	public function get_archives_link( $link ) {
		global $wp_rewrite;


		if(!isset($this->get_archives_where_r['post_type'])) {
			return $link;
		}

		$c = isset($this->get_archives_where_r['taxonomy']) && is_array($this->get_archives_where_r['taxonomy']) ? $this->get_archives_where_r['taxonomy'] : "";  //[steve]
		$t =  $this->get_archives_where_r['post_type'];


		$this->get_archives_where_r['post_type'] = isset($this->get_archives_where_r['post_type_slug']) ? $this->get_archives_where_r['post_type_slug'] : $t; // [steve] [*** bug fixing]

		if (isset($this->get_archives_where_r['post_type'])  and  $this->get_archives_where_r['type'] != 'postbypost'){
			$blog_url = rtrim( home_url() ,'/');

			//remove front
			$front = substr( $wp_rewrite->front, 1 );
			$link = str_replace($front,"",$link);

			$blog_url = preg_replace('/https?:\/\//', '', $blog_url);
			$ret_link = str_replace($blog_url,$blog_url.'/'.'%link_dir%',$link);

			$post_type = get_post_type_object( $this->get_archives_where_r['post_type'] );
			if(empty($c) ){    // [steve]
				if(isset( $post_type->rewrite["slug"] )) {
					$link_dir = $post_type->rewrite["slug"];
				}
				else{
					$link_dir = $this->get_archives_where_r['post_type'];
				}
			}
			else{   // [steve]
				$c['name'] = ($c['name'] == 'category' && get_option('category_base')) ? get_option('category_base') : $c['name'];
				$link_dir = $post_type->rewrite["slug"]."/".$c['name']."/".$c['termslug'];
			}

			if(!strstr($link,'/date/')){
				$link_dir = $link_dir .'/date';
			}

			if($post_type->rewrite['with_front']) {
				$link_dir = $front.$link_dir;
			}else {
			}


			$ret_link = str_replace('%link_dir%',$link_dir,$ret_link);
		}else {
			$ret_link = $link;
		}
		$this->get_archives_where_r['post_type'] = $t;	// [steve] reverting post_type to previous value

		return $ret_link;
	}

}
