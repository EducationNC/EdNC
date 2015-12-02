<div class="wef-measure" style="max-width: <?php echo $width ?>px;"></div>
<?php
switch($type){
	case 'page' :
		echo WEF_Social_Plugins::page_plugin('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
	case 'video' :
		if(get_option('wpemfb_video_as_post','false') == 'true')
			echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		else
			echo WEF_Social_Plugins::embedded_video('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
	//case 'photo' :
	//case 'post' :
	default:
		echo WEF_Social_Plugins::embedded_post('https://www.facebook.com/'.$fb_data['link'],$width);
		break;
}