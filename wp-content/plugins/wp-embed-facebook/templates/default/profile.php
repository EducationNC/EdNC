<div class="wef-default" style="max-width: <?php echo $width ?>px">
	<div class="row">
			<div class="col-3 text-center">
				<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="http://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
				</a>		
			</div>
			<div class="col-9 pl-none">
				<p>
					<a href="http://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
						<span class="title"><?php echo $fb_data['name'] ?></span>
					</a>
				</p>
				<div>
					<?php
					$opt = get_option('wpemfb_show_follow');
					if($opt === 'true') :
						WEF_Social_Plugins::follow_btn('https://www.facebook.com/'.$fb_data['id']);
					endif;
					?>
				</div>
			</div>
	</div>	
</div>

