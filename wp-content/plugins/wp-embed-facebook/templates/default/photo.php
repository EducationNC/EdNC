<div class="wef-default" style="max-width: <?php echo $width ?>px" >
	<a href="<?php /** @noinspection PhpUndefinedVariableInspection */
	echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
		<img src="<?php echo $fb_data['source'] ?>" width="100%" height="auto" >
	</a>
	<a class="post-link" href="<?php echo $fb_data['link'] ?> " target="_blank" rel="nofollow">
		<?php echo isset($fb_data['likes']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/y6/r/l9Fe9Ugss0S.gif" />'.$fb_data['likes']['summary']['total_count'].' ' : ""  ?>
		<?php echo isset($fb_data['comments']) ? '<img src="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yg/r/V8Yrm0eKZpi.gif" />'.$fb_data['comments']['summary']['total_count'].' ' : ""  ?>
	</a>
</div>
