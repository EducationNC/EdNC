<?php
/**
* Plugin Name: Why 404?
* Description: Debugs why posts 404 after "publish"
*/

//WPE Debug Scheduled Publish
add_action('publish_post','wpe_debug_publish_post',10,2);
function wpe_debug_publish_post($post_id, $post) {
        $date = date('Y-m-d h:i:s');
        $output = "Date $date \n";
        foreach ((array) $post as $k => $v) {
                $output .= "$k : $v \n";
        }

        $cache = wp_cache_get($post_id);
        if($cache) {
                foreach( (array) $cache as $k=>$v) {
                        $diff = ($post->$k == $v) ? "SAME" : "DIFFERENT";
                        $output .= "CACHED $k: $v ($diff) \n";
                }
        }

        $link = get_permalink( $post_id );
        $output .= "PERMALINK: $link \n";
        $check = (array) wp_remote_get( $link );
        $output .= "\n --CURL-- \n";
        foreach( $check as $k=>$v) {
                if( $k != 'body' )
                {
                        if(is_array($v)) {
                                $output .= "$k: \n";
                                foreach($v as $q => $r) {
                                        $output .= "\t $q: $r \n";
                                }
                        } else {
                                $output .= "$k: $v \n";
                        }
                }
        }

        if( $check['response']['code'] == '404' ) {
                $output .= "--RECIEVED 404 - PURGING VARNISH--";
                 wp_mail('aherr@ednc.org','ednc.org missed a post', "$output");
                Wpe_Common::purge_varnish_cache();
        } else {
                $output .= "--RESPONSE WAS ".$check['response']['code']."--\n";
        }

        file_put_contents(rtrim(dirname(__FILE__),'/').'/logs/'.$post->post_name.'.txt', "$output",FILE_APPEND);
}
