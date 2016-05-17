<div class="wef-classic aligncenter" style="max-width: <?php echo $width ?>px" >
	<?php if(isset($fb_data['cover'])) : ?>
		<div class="relative-container cover"><div class="relative" style="background-image: url('<?php echo $fb_data['cover']['source'] ?>'); background-position-y: <?php echo $fb_data['cover']['offset_y'] ?>%" onclick="window.open('https://www.facebook.com/<?php echo $fb_data['id'] ?>', '_blank')"></div></div>
	<?php endif; ?>
	<div class="row pad-top">
			<div class="col-2 text-center">
				<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
					<img src="<?php echo $fb_data['picture']['data']['url'] ?>" width="50px" height="50px" />
				</a>		
			</div>
			<div class="col-10 pl-none">
				<a href="<?php echo $fb_data['link'] ?>" target="_blank" rel="nofollow">
					<span class="title"><?php echo $fb_data['name'] ?></span>
				</a>
				<br>
				<?php
					if($fb_data['category'] == 'Musician/band'){
						echo isset($fb_data['genre']) ? $fb_data['genre'] : '';
					} else {
						_e($fb_data['category'],'wp-embed-facebook');
					}
				?><br>
				<?php if(isset($fb_data["website"])) : ?>
					<a  href="<?php echo WP_Embed_FB::getwebsite($fb_data["website"]) ?>" title="<?php _e('Web Site', 'wp-embed-facebook')  ?>" target="_blank">
						<?php _e('Web Site','wp-embed-facebook') ?>
					</a>						
				<?php endif; ?>
				<div style="float: right;">
					<?php
					$opt = WP_Embed_FB_Plugin::get_option('show_like');
					if($opt === 'true') :
						echo WEF_Social_Plugins::like_btn('https://www.facebook.com/'.$fb_data['id'],array('share'=>'true','layout'=>'button_count'));
					else :
						printf( __( '%d people like this.', 'wp-embed-facebook' ), $fb_data['likes'] );
					endif;
					?>
				</div>
			</div>
	</div>	
	<?php if(isset($fb_data['posts'])) : global $wp_embed;   ?>
		<?php foreach($fb_data['posts']['data'] as $fb_post) : ?>
			<?php if(isset($fb_post['picture']) || isset($fb_post['message'])) : ?>
				<?php include('single-post.php') ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>