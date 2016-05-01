<div class="wef-measure" style="max-width: <?php echo $width ?>px;"></div>
<?php
switch($type){
	case 'page' :
		/** @noinspection PhpUndefinedVariableInspection */
		echo WEF_Social_Plugins::page_plugin('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
	case 'video' :
		if(get_option('wpemfb_video_as_post','false') == 'true')
			/** @noinspection PhpUndefinedVariableInspection */
			echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		else{
			/** @noinspection PhpUndefinedVariableInspection */
			echo WEF_Social_Plugins::embedded_video('https://www.facebook.com/'.$fb_data['link'],$width);
			if( get_option('wpemfb_video_download','false') == 'true' ){
				echo '<p class="wef-video-link"><a title="Download this video" href="http://www.freemake.com/free_video_downloader/">'.__('Download this video','wp-embed-facebook').'</a></p>';
			}
		}

		break;
	//case 'photo' :
	//case 'post' :
	default:
		/** @noinspection PhpUndefinedVariableInspection */
		echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
}