<div class="wef-default" style="max-width: <?php echo $width ?>px">
	<div class="row">
		<div class="col-3 text-center">
			<a href="https://www.facebook.com/<?php /** @noinspection PhpUndefinedVariableInspection */
			echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
				<img src="https://graph.facebook.com/<?php echo $fb_data['id'] ?>/picture" />
			</a>
		</div>
		<div class="col-9 pl-none">
			<a href="https://www.facebook.com/<?php echo $fb_data['id'] ?>" target="_blank" rel="nofollow">
				<span class="title"><?php echo $fb_data['name'] ?></span>
			</a>
			<br>
			<div>
				<?php
				$opt = WP_Embed_FB_Plugin::get_option('show_like');
				if($opt === 'true') :
					echo WEF_Social_Plugins::get('like',array('href'=>'https://www.facebook.com/'.$fb_data['id'],'share'=>'true','layout'=>'button_count','show-faces'=> 'false'));
				else :
					printf( __( '%d people like this.', 'wp-embed-facebook' ), $fb_data['likes'] );
				endif;
				?>
			</div>
			<?php if(isset($fb_data["website"])) : ?>
				<br>
				<a href="<?php echo WP_Embed_FB::getwebsite($fb_data["website"]) ?>" title="<?php _e('Web Site', 'wp-embed-facebook')  ?>" target="_blank">
					<?php _e('Web Site','wp-embed-facebook') ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
