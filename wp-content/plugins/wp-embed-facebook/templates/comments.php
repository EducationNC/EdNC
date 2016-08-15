<?php
if ( post_password_required() ) {
	return;
}
echo WEF_Social_Plugins::get('comments',array('href'=>wp_get_shortlink(get_queried_object_id()),'width'=>'100%'));