<div class="wef-default" style="max-width: <?php echo $width ?>px">
	<div class="row">
			<div class="col-3 text-center">
				<a href="https://www.facebook.com/<?php /** @noinspection PhpUndefinedVariableInspection */
				echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
					<img src="https://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
				</a>		
			</div>
			<div class="col-9 pl-none">
				<p>
					<a href="https://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
						<span class="title"><?php echo $fb_data['name'] ?></span>
					</a>
				</p>
				<div>
					<?php
					$opt = WP_Embed_FB_Plugin::get_option('show_follow');
					if($opt === 'true') :
						WEF_Social_Plugins::get('follow',array('href'=>'https://www.facebook.com/'.$fb_data['id']));
					endif;
					?>
				</div>
			</div>
	</div>	
</div>

