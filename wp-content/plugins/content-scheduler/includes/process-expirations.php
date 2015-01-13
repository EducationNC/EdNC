<?php
// find posts that need to take some expiration action
			global $wpdb;
			
            if( $this->debug ) {
                $details = get_blog_details( get_current_blog_id() );
            }

			// select all Posts / Pages that have "enable-expiration" set and have expiration date older than right now
			$querystring = 'SELECT postmetadate.post_id 
				FROM 
				' .$wpdb->postmeta. ' AS postmetadate, 
				' .$wpdb->postmeta. ' AS postmetadoit, 
				' .$wpdb->posts. ' AS posts 
				WHERE postmetadoit.meta_key = "_cs-enable-schedule" 
				AND postmetadoit.meta_value = "Enable" 
				AND postmetadate.meta_key = "_cs-expire-date" 
			    AND postmetadate.meta_value <= "' . time() . '" 
				AND postmetadate.post_id = postmetadoit.post_id 
				AND postmetadate.post_id = posts.ID 
				AND posts.post_status = "publish"';
			$result = $wpdb->get_results($querystring);
			// Act upon the results
			if ( ! empty( $result ) )
			{
			    // stop, we don't want to notify UNLESS the post gets expired and set to expiration disabled
				if( $this->options['notify-on'] == '1' )
				{
					// build array of posts to send to do_notifications
					$posts_to_notify_on = array();
					foreach ( $result as $cur_post )
					{
						$posts_to_notify_on[] = $cur_post->post_id;
					}
					// call the notification function
					$this->do_notifications($posts_to_notify_on, 'expired');
				} // end if for notification on expiration
				// Shortcut: If exp-status = "Delete" then let's just delete and get on with things.
				if( $this->options['exp-status'] == '2' )
				{
					// Delete all those posts
					foreach ( $result as $cur_post )
					{
					    if( $this->debug ) {
					        error_log( "Blog: " . $details->blogname . ", Send to Trash: " . $cur_post->post_id );
					    }
						// Move the item to the trash
						wp_delete_post( $cur_post->post_id );
					} // end foreach
				}
				else
				{
					// Proceed with the updating process	      	        
					// step through the results
					foreach ( $result as $cur_post )
					{
					    if( $this->debug ) {
					        error_log( "Blog: " . $details->blogname . ", processing: " . $cur_post->post_id );
					    }
						// find out if it is a Page, Post, or what
						$post_type = $wpdb->get_var( 'SELECT post_type FROM ' . $wpdb->posts .' WHERE ID = ' . $cur_post->post_id );
						if ( $post_type == 'post' )
						{
							$this->process_post( $cur_post->post_id );
						}
						elseif ( $post_type == 'page' )
						{
							$this->process_page( $cur_post->post_id );
						}
						else
						{
							// it could be a custom post type
							$this->process_custom( $cur_post->post_id );
						} // end if
					} // end foreach
				} // end if (checking for DELETE)
			} // endif
?>