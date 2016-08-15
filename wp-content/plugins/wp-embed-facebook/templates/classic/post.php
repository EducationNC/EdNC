<?php $fb_post = /** @noinspection PhpUndefinedVariableInspection */
	$fb_data ?>
<div class="wef-classic aligncenter" style="max-width: <?php echo $width ?>px" >
	<div class="col-3 text-center">
		<a href="https://www.facebook.com/<?php echo $fb_post['from']['id'] ?>" target="_blank" rel="nofollow">
			<img src="https://graph.facebook.com/<?php echo $fb_post['from']['id'] ?>/picture" width="50px" height="50px" />
		</a>
	</div>
	<div class="col-9 pl-none">
		<p>
			<a href="https://www.facebook.com/<?php echo $fb_post['from']['id'] ?>" target="_blank" rel="nofollow">
				<span class="title"><?php echo $fb_post['from']['name'] ?></span>
			</a>
		</p>
		<div>
			<?php
			$opt = WP_Embed_FB_Plugin::get_option('show_like');
			if($opt === 'true') :
				echo WEF_Social_Plugins::get('like',array('href'=>'https://www.facebook.com/'.$fb_data['id'],'share'=>'true','layout'=>'button_count'));
			else :
				printf( __( '%d people like this.', 'wp-embed-facebook' ), $fb_post['likes'] );
			endif;
			?>
		</div>
	</div>
	<?php if(isset($fb_post['picture']) || isset($fb_post['message'])) : ?>
		<?php
		global $wp_embed;
		include('single-post.php');
		?>
	<?php endif; ?>
</div>
