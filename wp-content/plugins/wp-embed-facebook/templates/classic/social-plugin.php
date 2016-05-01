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
		else
			/** @noinspection PhpUndefinedVariableInspection */
			echo WEF_Social_Plugins::embedded_video('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
	//case 'photo' :
	//case 'post' :
	default:
		/** @noinspection PhpUndefinedVariableInspection */
		echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
}