<div class="wef-measure" style="max-width: <?php echo $width ?>px;"></div>
<?php
switch($type){
	case 'page' :
		echo WEF_Social_Plugins::page_plugin('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
	case 'video' :
		if(get_option('wpemfb_video_as_post','false') == 'true')
			echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		else{
			echo WEF_Social_Plugins::embedded_video('https://www.facebook.com/'.$fb_data['link'],$width);
			if( get_option('wpemfb_video_download','false') == 'true' ){
				echo '<p class="wef-video-link"><a rel="nofollow" href="http://www.freemake.com/free_video_downloader/">'.__('Download this video','wp-embed-facebook').'</a></p>';
			}
		}

		break;
	//case 'photo' :
	//case 'post' :
	default:
		echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
}