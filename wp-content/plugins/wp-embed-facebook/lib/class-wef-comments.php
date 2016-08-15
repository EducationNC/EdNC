<?php

/**
 * Handles comments auto embeds and comment count synchronization. It includes all actions and filters. Comments plugin
 * can also be invoked using the [fb_plugin comments] shortocode.
 *
 * @see WEF_Social_Plugins
 */
class  WEF_Comments extends WP_Embed_FB_Plugin {
	static function hooks() {

		/** @see WEF_Comments::comments_template */
		add_filter( 'comments_template', __CLASS__ . '::comments_template' );

		if ( self::get_option( 'comments_count_active' ) === 'true' ) {

			/** @see WEF_Comments::get_comments_number */
			add_filter( 'get_comments_number', __CLASS__ . '::get_comments_number', 10, 2 );

			/** @see WEF_Comments::save_post */
			add_filter( 'save_post', __CLASS__ . '::save_post', 10, 3 );

			/** @see WEF_Comments::pre_get_posts */
			add_action( 'pre_get_posts', __CLASS__ . '::pre_get_posts' );

			/** @see WEF_Comments::wpemfb_comments */
			add_filter( 'wp_ajax_wpemfb_comments', __CLASS__ . '::wpemfb_comments' );
			add_filter( 'wp_ajax_nopriv_wpemfb_comments', __CLASS__ . '::wpemfb_comments' );

		}

		if ( self::get_option( 'comments_open_graph' ) === 'true' ) {
			/** @see WEF_Comments::wp_head */
			add_action( 'wp_head', __CLASS__ . '::wp_head' );
		}
	}

	/**
	 * Adds FB open graph app_id meta tag to head
	 */
	static function wp_head() {
		$app_id = self::get_option( 'app_id' );
		if ( ! empty( $app_id ) ) {
			echo '<meta property="fb:app_id" content="' . $app_id . '" />' . PHP_EOL;
		}
	}

	/**
	 * Replace theme template for FB comments.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	static function comments_template( $template ) {
		$array = self::string_to_array( self::get_option( 'auto_comments_post_types' ) );
		if ( in_array( $GLOBALS['post']->post_type, $array ) ) {
			$template = self::path() . 'templates/comments.php';
		}

		return $template;

	}

	/**
	 * @see get_comments_number
	 *
	 * @param string $number Number of comments on WP
	 * @param int    $post_id
	 *
	 * @return mixed|string
	 */
	static function get_comments_number(
		/** @noinspection PhpUnusedParameterInspection */
		$number, $post_id
	) {
		$count = get_post_meta( $post_id, '_wef_comment_count', true );
		if ( $count ) {
			return $count;
		}

		return '0';
	}

	/**
	 * Update the comment count on post update
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 */
	static function save_post( $post_id, $post, $update ) {
		if ( wp_is_post_revision( $post_id ) || ! $update ) {
			return;
		}
		$options = self::get_option();
		$array   = self::string_to_array( $options['auto_comments_post_types'] );
		//https://graph.facebook.com/?id=http://t-underboot.sigami.net/?p=4
		if ( in_array( $post->post_type, $array ) ) {
			$args     = array(
				'fields' => 'share{comment_count}',
				'id'     => home_url( "/?p=$post_id" )
			);
			$url      = "https://graph.facebook.com/{$options[ 'sdk_version' ]}/?" . http_build_query( $args );
			$request  = wp_remote_get( $url );
			$response = wp_remote_retrieve_body( $request );
			if ( ! is_wp_error( $request ) && ! empty( $response ) ) {
				$data = json_decode( $response, true );
//					print_r($data);die();
				if ( is_array( $data ) && isset( $data['share'] ) && isset( $data['share']['comment_count'] ) ) {
					update_post_meta( $post->ID, '_wef_comment_count', intval( $data['share']['comment_count'] ) );
				}

			}
		}
	}

	/**
	 * Alter order by 'comment_count' to use _wef_comment_count meta instead
	 *
	 * @param WP_Query $query
	 *
	 * @return WP_Query
	 */
	static function pre_get_posts( $query ) {
		if ( isset( $query->query_vars['orderby'] ) && $query->query_vars['orderby'] == 'comment_count' ) {
			$query->set(
				'meta_query',
				array(
					'relation' => 'OR',
					array(
						'key'     => '_wef_comment_count',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key'     => '_wef_comment_count',
						'compare' => 'EXISTS'
					)
				)
			);
			$query->set( 'orderby', 'meta_value_num' );
		}

		return $query;
	}

	/**
	 * Ajax function for updating comment count
	 */
	static function wpemfb_comments() {
		if ( isset( $_POST['response'] ) && isset( $_POST['response']['href'] ) ) {
			$post_id = url_to_postid( $_POST['response']['href'] );
			$count   = self::get_comments_number( '', $post_id );
			if ( isset( $_POST['response']['message'] ) ) {
				$count ++;
			} else {
				$count --;
			}
			update_post_meta( $post_id, '_wef_comment_count', intval( $count ) );
		}
		wp_die();
	}
}