<?php
/* --- Wordpress Hooks Implementations --- */

/**
 * Main function for creating the widget html representation.
 * Transforms the shorcode parameters to the desired iframe call.
 *
 * Syntax as follows:
 * shortcode name - OPINIONSTAGE_WIDGET_SHORTCODE
 *
 * Arguments:
 * @param  id - Id of the poll
 *
 */
function opinionstage_add_poll($atts) {
	extract(shortcode_atts(array('id' => 0, 'type' => 'poll'), $atts));
	if(!is_feed()) {
		$id = intval($id);
		return opinionstage_create_embed_code($id, $type);
	} else {
		return __('Note: There is a poll embedded within this post, please visit the site to participate in this post\'s poll.', OPINIONSTAGE_WIDGET_UNIQUE_ID);
	}
}

/**
 * Create the The iframe HTML Tag according to the given parameters.
 * Either get the embed code or embeds it directly in case 
 *
 * Arguments:
 * @param  id - Id of the poll
 */
function opinionstage_create_embed_code($id, $type) {
    
    // Only present if id is available 
    if (isset($id) && !empty($id)) {        		
		// Load embed code from the cache if possible
		$is_homepage = is_home();
		$transient_name = 'embed_code' . $id . '_' . $type . '_' . ($is_homepage ? "1" : "0");
		$code = get_transient($transient_name);
		if ( false === $code || '' === $code ) {
			if ($type == 'set') {
				$embed_code_url = "http://".OPINIONSTAGE_SERVER_BASE."/api/sets/" . $id . "/embed_code.json";
			} else if ($type == 'container') {
				$embed_code_url = "http://".OPINIONSTAGE_SERVER_BASE."/api/containers/" . $id . "/embed_code.json";
			} else {
				$embed_code_url = "http://".OPINIONSTAGE_SERVER_BASE."/api/debates/" . $id . "/embed_code.json";
			}
			
			if ($is_homepage) {
				$embed_code_url .= "?h=1";
			}
		
			extract(opinionstage_get_contents($embed_code_url));
			$data = json_decode($raw_data);
			if ($success) {
				$code = $data->{'code'};			
				// Set the embed code to be cached for an hour
				set_transient($transient_name, $code, 3600);
			}
		}
    }
	return $code;
}

/**
 * Perform an HTTP GET Call to retrieve the data for the required content.
 * 
 * Arguments:
 * @param $url
 * @return array - raw_data and a success flag
 */
function opinionstage_get_contents($url) {
    $response = wp_remote_get($url, array('header' => array('Accept' => 'application/json; charset=utf-8')));

    return opinionstage_parse_response($response);
}

/**
 * Parse the HTTP response and return the data and if was successful or not.
 */
function opinionstage_parse_response($response) {
    $success = false;
    $raw_data = "Unknown error";
    
    if (is_wp_error($response)) {
        $raw_data = $response->get_error_message();
    
    } elseif (!empty($response['response'])) {
        if ($response['response']['code'] != 200) {
            $raw_data = $response['response']['message'];
        } else {
            $success = true;
            $raw_data = $response['body'];
        }
    }
    
    return compact('raw_data', 'success');
}

/**
 * Adds the poll button to the html edit bar for new/edited post/page
 */
function opinionstage_poll_footer_admin() {
	echo '<script type="text/javascript">'."\n";
	echo '/* <![CDATA[ */'."\n";
	echo "\t".'var opsPollBtn = {'."\n";
	echo "\t\t".'poll: "'.esc_js(__('poll', OPINIONSTAGE_WIDGET_UNIQUE_ID)).'",'."\n";
	echo "\t\t".'insert_poll: "'.esc_js(__('Insert poll', OPINIONSTAGE_WIDGET_UNIQUE_ID)).'"'."\n";
	echo "\t".'};'."\n";
	echo "\t".'if(document.getElementById("ed_toolbar")){'."\n";
	echo "\t\t".'edButtons[edButtons.length] = new edButton("ed_o_poll",opsPollBtn.poll, "", "","");'."\n";
	echo "\t\t".'jQuery(document).ready(function($){'."\n";
	echo "\t\t\t".'var popup_width = jQuery(window).width();'."\n";
	echo "\t\t\t".'var popup_height = jQuery(window).height();'."\n";
	echo "\t\t\t".'popup_width = ( 720 < popup_width ) ? 640 : popup_width - 80;'."\n";
	echo "\t\t\t".'$(\'#qt_content_ed_o_poll\').replaceWith(\'<input type="button" id="qt_content_ed_o_poll" accesskey="" class="ed_button" onclick="tb_show( \\\'Insert Poll\\\', \\\'#TB_inline?=&height=popup_height&width=popup_width&inlineId=opinionstage-insert-poll-form\\\' );" value="\' + opsPollBtn.poll + \'" title="\' + opsPollBtn.insert_poll + \'" />\');'."\n";
	echo "\t\t".'});'."\n";
	echo "\t".'}'."\n";
	echo '/* ]]> */'."\n";
	echo '</script>'."\n";
}

/**
 * Sidebar menu
 */
function opinionstage_poll_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page(__('Add Polls', OPINIONSTAGE_WIDGET_UNIQUE_ID), __('Polls', OPINIONSTAGE_WIDGET_UNIQUE_ID), 'edit_posts', OPINIONSTAGE_WIDGET_UNIQUE_LOCATION, 'opinionstage_add_poll_page', 
			plugins_url(OPINIONSTAGE_WIDGET_UNIQUE_ID.'/images/os.png'), '25.234323221');
	}
}

/**
 * Instructions page for adding a poll 
 */
function opinionstage_add_poll_page() {
  opinionstage_add_stylesheet();
  ?>
  <div class="opinionstage-wrap">
	  <div id="opinionstage-head"></div>
	  <div class="section">
		  <h2>Actions</h2>
		  <ul class="os_links_list">
			<li><?php echo opinionstage_create_link('Create a Poll', 'new_poll', ''); ?></li>
			<li><?php echo opinionstage_create_link('View Polls', 'dashboard', ''); ?></li>
			<li><?php echo opinionstage_create_link('Create a Set', 'sets/new', ''); ?></li>
			<li><?php echo opinionstage_create_link('View Sets', 'dashboard', 'tab=sets'); ?></li>			
			<li><?php echo opinionstage_create_link('View Containers', 'dashboard', 'tab=containers'); ?></li>			
		  </ul>
		  <h2>Help</h2>
		  <ul class="os_links_list">			
			<li><a href="http://blog.opinionstage.com/wordpress-poll-how-to-add-polls-to-wordpress-sites/?o=wp35e8" target="_blank">Help</a></li>					  
			<li><?php echo opinionstage_create_link('Showcase', 'showcase', ''); ?></li>
			<li><a href="https://opinionstage.zendesk.com/anonymous_requests/new" target="_blank">Contact Us</a></li>					  
		  </ul>	  
	  </div>  
  </div>
  <?php
}

/**
 * Load the js script
 */
function opinionstage_load_scripts() {
	wp_enqueue_script( 'ospolls', plugins_url(OPINIONSTAGE_WIDGET_UNIQUE_ID.'/opinionstage_plugin.js'), array( 'jquery', 'thickbox' ), '3' );
}

/**
 * The popup window in the post/page edit/new page
 */
function opinionstage_add_poll_popup() {
	?>
	<div id="opinionstage-insert-poll-form" style="display:none;">
      <div id="content">
		<h3><strong>Type:</strong></h3>
		<p>
			<select style="width: 100%; max-width: 300px; font-size: 20px; height: 40px; line-height: 40px;" id="opinionstage-type">
				<option value="poll">Insert a Poll</option>
				<option value="set">Insert a Set</option>
			</select>
		</p>
		<style type="text/css">
			.pollWrp p, .setWrp p { padding: 1px 0 !important; }
		</style>
		<script type="text/javascript">
			jQuery(function ($)
			{
				var $pollWrp = $(".pollWrp");
				var $setWrp = $(".setWrp");
				$("#opinionstage-type").change(function ()
				{
					var $this = $(this);
					var val = $this.val();
					if (val == "poll")
					{
						$setWrp.fadeOut(0, function ()
						{
							$pollWrp.fadeIn("fast");
						});
					}
					else if (val == "set")
					{
						$pollWrp.fadeOut(0, function ()
						{
							$setWrp.fadeIn("fast");
						});					
					}
				}).trigger("change");
			});
		</script>
		<div class="pollWrp" style="display: none;">
			<h3><strong>Enter Poll ID (e.g. 2195036):</strong></h3>
			<p><input type="text" name="poll-id" id="opinionstage-poll-id" value="" /></p>
			<p class="submit">
			  <input type="button" class="opinionstage-submit button-primary" value="Insert Poll" name="submit" />
			</p>
			<p><strong>Haven't created a poll yet?</strong></br></br>
				<?php echo opinionstage_create_link('Create a new poll', 'new_poll', ''); ?>
			</p>
			<p><strong>Don't know the poll ID?</strong></br></br>
				<?php echo opinionstage_create_link('Locate ID of an existing poll', 'dashboard', ''); ?>
			</p>
		</div>
		<div class="setWrp" style="display: none;">
			<h3><strong>Enter Set ID (e.g. 2152089):</strong></h3>
			<p><input type="text" name="set-id" id="opinionstage-set-id" value="" /></p>
			<p class="submit">
			  <input type="button" class="opinionstage-submit button-primary" value="Insert Set" name="submit" />
			</p>
			<p><strong>Haven't created a set yet?</strong></br></br>
				<?php echo opinionstage_create_link('Create a new set', 'sets/new', ''); ?>
			</p>
			<p><strong>Don't know the set ID?</strong></br></br>
				<?php echo opinionstage_create_link('Locate ID of an existing set', 'dashboard', 'tab=sets', array()); ?>
			</p>
		</div>
	  </div>
	</div>  
	<?php
}

/**
 * Utility function to create a link with the correct host and all the required information.
 */
function opinionstage_create_link($caption, $page, $params = "", $options = array()) {
	$style = empty($options['style']) ? '' : $options['style'];
	$new_page = empty($options['new_page']) ? true : $options['new_page'];
	$params_prefix = empty($params) ? "" : "&";	
	$link = "http://".OPINIONSTAGE_SERVER_BASE."/".$page."?" . "o=".OPINIONSTAGE_WIDGET_API_KEY.$params_prefix.$params;
	return "<a href=\"".$link."\"".($new_page ? " target='_blank'" : "")." style=".$style.">".$caption."</a>";
}

/**
 * CSS file loading
 */
function opinionstage_add_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'opinionstage-style', plugins_url('style.css', __FILE__) );
	wp_enqueue_style( 'opinionstage-style' );
}

/**
 * Adds the poll button to the edit bar for new/edited post/page In TinyMCE >= WordPress 2.5
 */
function opinionstage_poll_tinymce_addbuttons() {
	if(!current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
		return;
	}
	if(get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "opinionstage_poll_tinymce_addplugin");
		add_filter('mce_buttons', 'opinionstage_poll_tinymce_registerbutton');
	}
}
function opinionstage_poll_tinymce_registerbutton($buttons) {
	array_push($buttons, 'separator', 'ospolls');
	return $buttons;
}
function opinionstage_poll_tinymce_addplugin($plugin_array) {
	$plugin_array['ospolls'] = plugins_url(OPINIONSTAGE_WIDGET_UNIQUE_ID.'/tinymce/plugins/polls/editor_plugin.js');
	return $plugin_array;
}
?>