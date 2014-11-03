<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package EducationNC
 */

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function ednc_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'ednc_setup_author' );


// Sort terms in a custom taxonomy hierarchically
// http://wordpress.stackexchange.com/a/99516/35628
/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
 */
function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0) {
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }

    // Make sure final array is ordered in the preferred custom order
    usort($into, function($a, $b) {
        return $a->term_order - $b->term_order;
    });
}


// Allow .ai files to be uploaded
function custom_upload_mimes ($existing_mimes=array()) {

    // Add file extension 'extension' with mime type 'mime/type'
    $existing_mimes['psd'] = 'application/photoshop';
    $existing_mimes['ai'] = 'application/postscript';

    // and return the new full result
    return $existing_mimes;

}
add_filter('upload_mimes', 'custom_upload_mimes');


// Forward attachment pages to the media file itself
function ednc_attachment_redirect() {
    if ( is_attachment() ) {
        $url = wp_get_attachment_url(get_the_id());
        wp_redirect($url, 301);
    }
}
add_action('template_redirect', 'ednc_attachment_redirect');


// Get Vimeo video data from API
function get_vimeo($vid){
    // Vimeo API
    require_once('vimeo-api/vimeo.php');

    // oAuth2
    $apiKey = '0fbac824646d2b56700587abae670783ee657f0e';
    $apiSecret = 'e5419e6800c0c3a1246e81c71aaec8103f0c2002';
    $accessToken = '639554c4d0083dc24d7a67c0bfee360e';

    $vimeo = new Vimeo($apiKey, $apiSecret, $accessToken);

    // Use WP Transients API to cache the response from Vimeo
    $video = get_transient('vimeo_cache'.$vid);
    if ($video == '') {
        // Get the info about the video from Vimeo
        $video = $vimeo->request("/videos/$vid");
        set_transient( 'vimeo_cache'.get_the_id(), $video, HOUR_IN_SECONDS );
    }

    return $video;
}

// Tweet fetching and formatting
// Taken from Joe Bunn website
function ednc_tweets($twitter_id) {
    $tweet_number = 0;

    $tweets = getTweets($twitter_id, 2);

    // format each tweet
    if (is_array($tweets)) {

        foreach ($tweets as $tweet) {

            // process tweet text
            if ($tweet['text']) {
                $tweet_text = $tweet['text'];

                // replace mentions with links
                if (is_array($tweet['entities']['user_mentions'])) {
                    foreach($tweet['entities']['user_mentions'] as $key => $user_mention) {
                        $tweet_text = preg_replace(
                            '/@'.$user_mention['screen_name'].'/i',
                            '<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
                            $tweet_text
                        );
                    }
                }

                // replace hashtags with search link
                if (is_array($tweet['entities']['hashtags'])) {
                    foreach($tweet['entities']['hashtags'] as $key => $hashtag) {
                        $tweet_text = preg_replace(
                            '/#'.$hashtag['text'].'/i',
                            '<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
                            $tweet_text
                        );
                    }
                }

                // replace links with t.co shortlinks
                if (is_array($tweet['entities']['urls'])) {
                    foreach($tweet['entities']['urls'] as $key => $link) {
                        $tweet_text = preg_replace(
                            '`'.$link['url'].'`',
                            '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
                            $tweet_text
                        );
                    }
                }

                // replace media links with t.co shortlinks
                if (is_array($tweet['entities']['media'])) {
                    foreach($tweet['entities']['media'] as $key => $link) {
                        $tweet_text = preg_replace(
                            '`'.$link['url'].'`',
                            '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
                            $tweet_text
                        );
                    }
                }

                // twitter intents
                $intents = '
                    <a class="twitter-action-reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'"></a>
                    <a class="twitter-action-retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'"></a>
                    <a class="twitter-action-favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'"></a>';

                // timestamp and permalink
                $timestamp = '<a href="https://twitter.com/bunndjco/status/'.$tweet['id_str'].'" target="_blank">
                    '.date('M d',strtotime($tweet['created_at'])).'
                    </a>';
            }

            // format fragment
            $tweet_fragment = '<div class="post"><div class="meta">
                <a href="https://twitter.com/bunndjco/status/'.$tweet['id_str'].'" target="_blank">@'.$twitter_id.'</a>'.
            '</div>
            <div class="tweet">'
                .$tweet_text.
            '</div>
            <div class="intents">'
            .$intents.
            '</div></div>';

            echo $tweet_fragment;

            $tweet_number++;
        }
    }

    echo '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
}

// Filter Modern Tribe's The Events Calendar date output
function ednc_event_schedule_details($event = null, $before = '', $after = '') {
    if ( is_null( $event ) ) {
        global $post;
        $event = $post;
    }

    if ( is_numeric( $event ) )
        $event = get_post( $event );

    $schedule = '<span class="date-start dtstart">';
    $format = '';
    $date_without_year_format = tribe_get_date_format();
    $date_with_year_format = tribe_get_date_format( true );
    $time_format = get_option( 'time_format' );
    $datetime_separator = tribe_get_option('dateTimeSeparator', ' @ ');
    $time_range_separator = tribe_get_option('timeRangeSeparator', ' - ');
    $microformatStartFormat = tribe_get_start_date( $event, false, 'Y-m-dTh:i' );
    $microformatEndFormat = tribe_get_end_date( $event, false, 'Y-m-dTh:i' );

    $settings = array(
        'show_end_time' => true,
        'time' => true,
    );

    $settings = wp_parse_args( apply_filters('tribe_events_event_schedule_details_formatting', $settings), $settings );
    if ( ! $settings['time'] ) $settings['show_end_time'] = false;
    extract($settings);

    $format = $date_with_year_format;

    // if it starts and ends in the current year then there is no need to display the year
    if ( tribe_get_start_date( $event, false, 'Y' ) === date( 'Y' ) && tribe_get_end_date( $event, false, 'Y' ) === date( 'Y' ) ) {
        $format = $date_without_year_format;
    }

    if ( tribe_event_is_multiday( $event ) ) { // multi-date event

        $format2ndday = $date_with_year_format;

        //If it's all day and the end date is in the same month and year, just show the day and year.
        if ( tribe_event_is_all_day( $event ) && tribe_get_end_date( $event, false, 'm' ) === tribe_get_start_date( $event, false, 'm' ) && tribe_get_end_date( $event, false, 'Y' ) === date( 'Y' ) ) {
            $format2ndday = 'j, Y';
        }

        if ( tribe_event_is_all_day( $event ) ) {
            $schedule .= tribe_get_start_date( $event, true, $format );
            $schedule .= '<span class="value-title" title="'. $microformatStartFormat .'"></span>';
            $schedule .= '</span>'.$time_range_separator;
            $schedule .= '<span class="date-end dtend">';
            $schedule .= tribe_get_end_date( $event, true, $format2ndday );
            $schedule .= '<span class="value-title" title="'. $microformatEndFormat .'"></span>';
        } else {
            $schedule .= tribe_get_start_date( $event, false, $format ) . ( $time ? $datetime_separator . tribe_get_start_date( $event, false, $time_format ) : '' );
            $schedule .= '<span class="value-title" title="'. $microformatStartFormat .'"></span>';
            $schedule .= '</span>'.$time_range_separator;
            $schedule .= '<span class="date-end dtend">';
            $schedule .= tribe_get_end_date( $event, false, $format2ndday ) . ( $time ? $datetime_separator . tribe_get_end_date( $event, false, $time_format ) : '' );
            $schedule .= '<span class="value-title" title="'. $microformatEndFormat .'"></span>';
        }

    } elseif ( tribe_event_is_all_day( $event ) ) { // all day event
        $schedule .=  tribe_get_start_date( $event, true, $format );
        $schedule .= '<span class="value-title" title="'. $microformatStartFormat .'"></span>';
    } else { // single day event
        if ( tribe_get_start_date( $event, false, 'g:i A' ) === tribe_get_end_date( $event, false, 'g:i A' ) ) { // Same start/end time
            $schedule .= tribe_get_start_date( $event, false, $format ) . ( $time ? $datetime_separator . tribe_get_start_date( $event, false, $time_format ) : '' );
            $schedule .= '<span class="value-title" title="'. $microformatStartFormat .'"></span>';
        } else { // defined start/end time
            $schedule .= tribe_get_start_date( $event, false, $format ) . ( $time ? $datetime_separator . tribe_get_start_date( $event, false, $time_format ) : '' );
            $schedule .= '<span class="value-title" title="'. $microformatStartFormat .'"></span>';
            $schedule .= '</span>' . ( $show_end_time ? $time_range_separator : '' );
            $schedule .= '<span class="end-time dtend">';
            $schedule .= ( $show_end_time ? tribe_get_end_date( $event, false, $time_format ) : '' ) . '<span class="value-title" title="'. $microformatEndFormat .'"></span>';
        }
    }

    $schedule .= '</span>';

    $schedule = $before . $schedule . $after;

    return $schedule;
}

// Remove share buttons and related posts from bottom of blog post content so we can add them manually later
function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}

add_action( 'loop_start', 'jptweak_remove_share' );

function jetpackme_remove_rp() {
    $jprp = Jetpack_RelatedPosts::init();
    $callback = array( $jprp, 'filter_add_target_to_dom' );
    remove_filter( 'the_content', $callback, 40 );
}
add_filter( 'wp', 'jetpackme_remove_rp', 20 );
