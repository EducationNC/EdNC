<?php $fb_post = $fb_data ?>
<div class="wef-default" style="max-width: <?php echo $width ?>px" >
	<div class="col-3 text-center">
		<a href="http://www.facebook.com/<?php echo $fb_post['from']['id'] ?>" target="_blank" rel="nofollow">
			<img src="http://graph.facebook.com/<?php echo $fb_post['from']['id'] ?>/picture" width="50px" height="50px" />
		</a>
	</div>
	<div class="col-9 pl-none">
		<p>
			<a href="http://www.facebook.com/<?php echo $fb_post['from']['id'] ?>" target="_blank" rel="nofollow">
				<span class="title"><?php echo $fb_post['from']['name'] ?></span>
			</a>
		</p>
		<div>
			<?php
			$opt = get_option('wpemfb_show_like');
			if($opt === 'true') :
				echo WEF_Social_Plugins::like_btn($fb_post['link'],array('share'=>'true','layout'=>'button_count'));
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
