<?php
$fb_post['link'] = isset($fb_post['link']) ? $fb_post['link'] : '';
$link = explode("_", $fb_post['id']);
$picture_link = isset($fb_post['link']) ? $fb_post['link']  : "https://www.facebook.com/".$link[0]."/posts/".$link[1];
$message = null;
$description = isset($fb_post['description']) && !empty($fb_post['description']) ? WP_Embed_FB::make_clickable($fb_post['description']) : null ;
$caption = isset($fb_post['caption']) && !empty($fb_post['caption']) ? $fb_post['caption'] : null ;
$name = isset($fb_post['name']) && !empty($fb_post['name']) ? $fb_post['name'] : null ;
$is_fb_link = strpos($fb_post['link'],'facebook.com') !== false;
$video_ratio = (get_option('wpemfb_video_ratio') == 'true') ? true : false;
if(isset($fb_post['message']) && !empty($fb_post['message'])) :
	$message = str_replace($fb_post['link'],'',$fb_post['message']);
	$message =  WP_Embed_FB::make_clickable($message);
endif;
$name = $name ? '<p class="caption-title">'.$name.'</p>':'';
$description = $description ? '<div class="caption-description">'.$description.'</div>':'';
$caption = $caption ? '<p class="caption-link"><a href="'.$fb_post["link"].'" rel="nofollow">'.$caption.'</a></p>':'';
$link_info = $name.$description.$caption;
$icon = isset($fb_post["icon"]) ? '<img class="icon" src="'.$fb_post["icon"].'">' : '';
$old_time_zone = date_default_timezone_get();
date_default_timezone_set(get_option('timezone_string'));
$post_time = $icon.date_i18n('l, j F Y g:s a', strtotime($fb_post['created_time'])) ;
date_default_timezone_set($old_time_zone);
?>
<hr>
<div class="row">
	<div class="col-12 page-post">
		<?php //echo '<pre>'.wpautop(print_r($fb_post,true)).'</pre>'; ?>
		<?php echo isset($fb_post['story']) ? '<p>'.$fb_post['story'].'</p>' : '';?>
		<p class="post-time" ><?php echo $post_time ?></p>
		<?php
		echo $message ? '<p>'.$message.'</p>':'';
		switch($fb_post["type"]) :
			case 'video':
				if($is_fb_link){
					$raw = WP_Embed_FB::$raw;
					$width_r = WP_Embed_FB::$width;
					WP_Embed_FB::$raw = true;
					WP_Embed_FB::$width = $width-40;
					echo $wp_embed->shortcode(array('src'=>$fb_post['link']));
					WP_Embed_FB::$raw = $raw;
					WP_Embed_FB::$width = $width_r;
					echo $link_info;
				} else {
					$use_ratio = (get_option('wpemfb_video_ratio') == 'true');
					echo '<div class="post-link">';
					echo $use_ratio ? '<div class="video">' : '';
					echo $wp_embed->shortcode(array('src'=>$fb_post['link'], 'width'=>$width - 20));
					echo $use_ratio ? '</div>' : '';
					echo $link_info;
					echo '</div>';
				}
				break;
			case 'event':
				WP_Embed_FB::$width = $width-40;
				echo $wp_embed->shortcode(array('src'=>$fb_post['link']));
				WP_Embed_FB::$width = $width;
				break;
			case 'photo':
				?>
				<a href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?>" rel="nofollow" target="_blank">
					<img src="<?php echo $fb_post['full_picture'] ?>" width="100%" height="auto"  /><br>
				</a>
				<?php echo $link_info; ?>
				<?php
				break;
			case 'music':
			case 'link':
				?>
				<div class="post-link" style="max-width: <?php echo $width?>px;">
					<a href="<?php echo $fb_post['link'] ?>" rel="nofollow" target="_blank">
						<img src="<?php echo $fb_post['full_picture'] ?>" width="100%" height="auto"  /><br>
					</a>
					<?php if($fb_post["type"] == 'music') : ?>
						<p>
							<audio controls>
								<source src="<?php echo $fb_post['source'] ?>" type="audio/mpeg">
							</audio>
						</p>
					<?php endif ?>
					<?php echo $link_info; ?>
				</div>
				<?php
				break;
			case 'status':
			default:
				?>
				<?php if(isset($fb_post['full_picture'],$fb_post['link']) && !empty($fb_post['full_picture']) && !empty($fb_post['link'])) : ?>
					<a href="<?php echo $fb_post['link'] ?>" rel="nofollow" target="_blank">
						<img src="<?php echo $fb_post['full_picture'] ?>" width="100%" height="auto"  /><br>
					</a>
				<?php endif; ?>
				<?php echo $link_info; ?>
				<?php
				break;
		endswitch;
		?>
		<a class="post-likes" href="<?php echo "https://www.facebook.com/".$link[0]."/posts/".$link[1] ?> " target="_blank" rel="nofollow">
			<?php echo isset($fb_post['likes']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y6/r/l9Fe9Ugss0S.gif" />'.$fb_post['likes']['summary']['total_count'].' ' : ""  ?>
			<?php echo isset($fb_post['comments']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yg/r/V8Yrm0eKZpi.gif" />'.$fb_post['comments']['summary']['total_count'].' ' : ""  ?>
			<?php echo isset($fb_post['shares']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y2/r/o19N6EzzbUm.png" />'.$fb_post['shares']['count'].' ' : ""  ?>
		</a>
	</div>
</div>