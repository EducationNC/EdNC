<?php
/*
    Should take human readable date strings
    and turn them into unix timestamps
*/
require_once "DateUtilities.php";

// find posts that need to take some expiration action
global $wpdb;
// select all Posts / Pages that have "enable-expiration" set and have expiration date older than right now
$querystring = 'SELECT postmetadate.post_id, postmetadate.meta_value  
    FROM 
    ' .$wpdb->postmeta. ' AS postmetadate WHERE postmetadate.meta_key = "_cs-expire-date"';
$result = $wpdb->get_results($querystring);
// Act upon the results
if ( ! empty( $result ) )
{
    // Proceed with the updating process	      	        
    // step through the results
    foreach ( $result as $cur_post )
    {
        // do the date munging
        $unixTimestamp = DateUtilities::getTimestampFromReadableDate( $cur_post->meta_value, 0 );
        // update it
        update_post_meta( $cur_post->post_id, '_cs-expire-date', $unixTimestamp, $cur_post->meta_value );
    } // end foreach
} // endif
?>