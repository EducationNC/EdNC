<?php
	class OpinionStageWidget extends WP_Widget {
		function OpinionStageWidget() {
			$widget_ops = array('classname' => 'opinionstage_widget', 'description' => __('Adds a highly engaging social polling system to your widget section.', OPINIONSTAGE_WIDGET_UNIQUE_ID));
			$this->WP_Widget('opinionstage_widget', __('Polls by Opinion Stage', OPINIONSTAGE_WIDGET_UNIQUE_ID), $widget_ops);
		}

		function widget($args, $instance) {
			extract($args);
			echo $before_widget;
			$title = @$instance['title'];
			$id    = @$instance['id'];
			$type  = @$instance['type'];
			$type  = !isset($type) || empty($type) ? 0 : $type;
			if (!empty($title)) echo $before_title . apply_filters('widget_title', $title) . $after_title;
			if (!empty($id) && !empty($type)) echo do_shortcode('[' . OPINIONSTAGE_WIDGET_SHORTCODE . ' id="' . $id . '" type="' . $type . '"]');
			echo $after_widget;
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['id']    = strip_tags($new_instance['id']);
			$instance['type']  = strip_tags($new_instance['type']);
			return $instance;
		}

		function form($instance) {
			$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
			$id    = isset($instance['id'])    ? esc_attr($instance['id'])    : '';
			$type  = isset($instance['type'])  ? esc_attr($instance['type'])  : 'poll';
			?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', OPINIONSTAGE_WIDGET_UNIQUE_ID); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
				
				<p>
					<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Display:', OPINIONSTAGE_WIDGET_UNIQUE_ID); ?></label>
					<select class="widefat" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>">
						<option value="poll" <?php selected($type, 'poll') ?>>Display Poll</option>
						<option value="set" <?php selected($type, 'set') ?>>Display Set</option>
						<option value="container" <?php selected($type, 'container') ?>>Display Container</option>
						<option value="0" <?php selected($type, 0) ?>>Do not display anything (Disable)</option>
					</select>
				</p>
				
				<p>
					<label for="<?php echo $this->get_field_id('id'); ?>">
						<span class="pollWrp" style="display: none;">
							<?php _e('Poll ID:', OPINIONSTAGE_WIDGET_UNIQUE_ID); ?>
						</span>
						<span class="setWrp" style="display: none;">
							<?php _e('Set ID:', OPINIONSTAGE_WIDGET_UNIQUE_ID); ?>
						</span>
						<span class="containerWrp" style="display: none;">
							<?php _e('Container ID:', OPINIONSTAGE_WIDGET_UNIQUE_ID); ?>
						</span>
					</label>
					<input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" />
				</p>
				
				<div class="pollWrp" style="display: none;">
					<p><?php echo opinionstage_create_link('Locate the Poll ID', 'dashboard', ''); ?></p>
				</div>
				<div class="setWrp" style="display: none;">
					<p><?php echo opinionstage_create_link('Locate the Set ID', 'dashboard', 'tab=sets'); ?></p>
				</div>
				<div class="containerWrp" style="display: none;">
					<p><?php echo opinionstage_create_link('Locate the Container ID', 'dashboard', 'tab=containers'); ?></p>
				</div>
				
				<script type="text/javascript">
					jQuery(function ($)
					{
						var $pollWrp = $(".pollWrp");
						var $setWrp = $(".setWrp");
						var $containerWrp = $(".containerWrp");
						$("#<?php echo $this->get_field_id('type'); ?>").change(function (e)
						{
							var $this = $(this);
							var val = $this.val();
							if (val == "poll")
							{
								$containerWrp.stop(false, true).fadeOut(0);
								$setWrp.stop(false, true).fadeOut(0, function ()
								{
									$pollWrp.stop(false, true).fadeIn(e.isTrigger ? 0 : "fast");
								});
							}
							else if (val == "set")
							{
								$containerWrp.stop(false, true).fadeOut(0);
								$pollWrp.stop(false, true).fadeOut(0, function ()
								{
									$setWrp.stop(false, true).fadeIn(e.isTrigger ? 0 : "fast");
								});					
							}
							else if (val == "container")
							{
								$setWrp.stop(false, true).fadeOut(0);
								$pollWrp.stop(false, true).fadeOut(0, function ()
								{
									$containerWrp.stop(false, true).fadeIn(e.isTrigger ? 0 : "fast");
								});					
							}
						}).trigger("change");
						$(window).load(function ()
						{
							$("#<?php echo $this->get_field_id('type'); ?>").trigger("change");
						});
					});
				</script>				
			<?php
		}
	}

	function opinionstage_init_widget() {
		register_widget('OpinionStageWidget');
	}

	add_action('widgets_init', 'opinionstage_init_widget');
?>