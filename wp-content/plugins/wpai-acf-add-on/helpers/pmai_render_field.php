<?php
if ( ! function_exists('pmai_render_field')){
	
	function pmai_render_field($field, $post = array(), $field_name = ""){

		if (empty($field['multiple'])) $field['multiple'] = false;
		if (empty($field['class'])) $field['class'] = false;
		if (empty($field['id'])) $field['id'] = false;

		$current_field = (!empty($post['fields'][$field['key']])) ? $post['fields'][$field['key']] : false;
		$current_is_multiple_field_value = (isset($post['is_multiple_field_value'][$field['key']])) ? $post['is_multiple_field_value'][$field['key']] : false;
		$current_multiple_value = (isset($post['multiple_value'][$field['key']])) ? $post['multiple_value'][$field['key']] : false;		

		if ( "" != $field_name ){

			$field_keys = str_replace(array('[',']'), array(''), str_replace('][', ':', $field_name));
			
			foreach (explode(":", $field_keys) as $n => $key) {
				$current_field = (!empty($post['fields'][$key])) ? $post['fields'][$key] : $current_field[$key];
				$current_is_multiple_field_value = (isset($post['is_multiple_field_value'][$key])) ? $post['is_multiple_field_value'][$key] : $current_is_multiple_field_value[$key];
				$current_multiple_value = (isset($post['multiple_value'][$key])) ? $post['multiple_value'][$key] : $current_multiple_value[$key];
			}

			$current_field = (!empty($current_field[$field['key']])) ? $current_field[$field['key']] : false;		
			$current_is_multiple_field_value = (isset($current_is_multiple_field_value[$field['key']])) ? $current_is_multiple_field_value[$field['key']] : false;
			$current_multiple_value = (isset($current_multiple_value[$field['key']])) ? $current_multiple_value[$field['key']] : false;

		}	

		global $acf;

		?>
		
		<?php if ( ! in_array($field['type'], array('message')) ): ?>
		
		<div class="field field_type-<?php echo $field['type'];?> field_key-<?php echo $field['key'];?>">			
			<p class="label"><label><?php echo (in_array($field['type'], array('message', 'tab'))) ? $field['type'] : ((empty($field['label']) ? '' : $field['label']));?></label></p>			
			<div class="wpallimport-clear"></div>
			<p class="label" style="display:block; margin:0;"><label></label></p>
			<div class="acf-input-wrap">
				<?php switch ($field['type']) {
					case 'user':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Specify the user ID, username, or user e-mail address. Separate multiple values with commas.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;										
					case 'acf_cf7':
					case 'gravity_forms_field':					
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Specify the form ID.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;										
					case 'page_link':
					case 'post_object':											
					case 'relationship':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Enter in the ID or slug, or IDs or slugs separated by commas.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;										
					case 'file':
					case 'image':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Specify the URL to the image or file.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;					
					case 'gallery':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Specify image URLs, separated by commas.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;					
					case 'color_picker':					
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text w95 widefat rad4"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Specify the hex code the color preceded with a # - e.g. #ea5f1a.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;					
					case 'text':					
					case 'number':
					case 'email':
					case 'password':
					case 'url':
					case 'oembed':
					case 'limiter':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="text widefat rad4"/>
						<?php
						break;					
					case 'wp_wysiwyg':
					case 'wysiwyg':	
					case 'textarea':
						?>
						<textarea name="fields<?php echo $field_name;?>[<?php echo $field['key'];?>]" class="widefat rad4"><?php echo esc_attr( $current_field );?></textarea>
						<?php
						break;				
					case 'date_picker':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>]" class="text datepicker widefat rad4" style="width:200px;"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Use any format supported by the PHP strtotime function.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;		
					case 'date_time_picker':
						?>
						<input type="text" placeholder="" value="<?php echo esc_attr( $current_field );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>]" class="text datetimepicker widefat rad4" style="width:200px;"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('Use any format supported by the PHP strtotime function.', 'pmxi_plugin'); ?>" style="top:0;">?</a>
						<?php
						break;			
					case 'google_map':
					case 'location-field':
						?>
						<div class="input">
							<label><?php _e("Address"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['address'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][address]" class="text widefat rad4"/>												
						</div>
						<div class="input">
							<label><?php _e("Lat"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['lat'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][lat]" class="text widefat rad4"/>												
						</div>
						<div class="input">
							<label><?php _e("Lng"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['lng'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][lng]" class="text widefat rad4"/>
						</div>
						<?php
						break;					
					case 'paypal_item':
						?>
						<div class="input">
							<label><?php _e("Item Name"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['item_name'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][item_name]" class="text widefat rad4"/>												
						</div>
						<div class="input">
							<label><?php _e("Item Description"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['item_description'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][item_description]" class="text widefat rad4"/>												
						</div>
						<div class="input">
							<label><?php _e("Price"); ?></label>
							<input type="text" placeholder="" value="<?php echo esc_attr( $current_field['price'] );?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][price]" class="text widefat rad4"/>
						</div>
						<?php
						break;
					case 'select':
					case 'checkbox':
					case 'radio':					
					case 'true_false':															
						?>											
						<div class="input">
							<div class="main_choise">
								<input type="radio" id="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="switcher" name="is_multiple_field_value<?php echo $field_name; ?>[<?php echo $field['key'];?>]" value="yes" <?php echo 'no' != $current_is_multiple_field_value ? 'checked="checked"': '' ?>/>
								<label for="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="chooser_label"><?php _e("Select value for all records", "pmxi_plugin"); ?></label>
							</div>
							<div class="wpallimport-clear"></div>
							<div class="switcher-target-is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes">
								<div class="input sub_input">
									<div class="input">
										<?php

										if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){
											
											$field_class = 'acf_field_' . $field['type'];										

											$field['other_choice'] = false;
											$field['_input'] = 'multiple_value'. $field_name .'[' . $field['key'] . ']';
											$field['value'] = $current_multiple_value;									

											acf_render_field( $field );
											
										}
										else{
											
											$field_class = 'acf_field_' . $field['type'];
											$new_field = new $field_class();

											$field['other_choice'] = false;
											$field['name'] = 'multiple_value'. $field_name .'[' . $field['key'] . ']';
											$field['value'] = $current_multiple_value;									

											$new_field->create_field( $field );

										}

										?>
									</div>
								</div>
							</div>
						</div>											
						
						<div class="clear"></div>

						<div class="input">
							<div class="main_choise">
								<input type="radio" id="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="switcher" name="is_multiple_field_value<?php echo $field_name; ?>[<?php echo $field['key'];?>]" value="no" <?php echo 'no' == $current_is_multiple_field_value ? 'checked="checked"': '' ?>/>
								<label for="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="chooser_label"><?php _e('Set with XPath', 'pmxi_plugin' )?></label>
							</div>
							<div class="wpallimport-clear"></div>
							<div class="switcher-target-is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no">
								<div class="input sub_input">
									<div class="input">
										<input type="text" class="smaller-text widefat rad4" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>]" style="width:300px;" value="<?php echo esc_attr($current_field); ?>"/>
										<?php										
											if ($field['type']=='select' || $field['type']=='checkbox' || $field['type']=='radio') {
												?>
												<a href="#help" class="wpallimport-help" style="top:0;" title="<?php _e('Specify the value. For multiple values, separate with commas. If the choices are of the format option : Option, option-2 : Option 2, use option and option-2 for values.', 'pmxi_plugin') ?>">?</a>
												<?php
											} else {
												?>
												<a href="#help" class="wpallimport-help" style="top:0;" title="<?php _e('Specify the 0 for false, 1 for true.', 'pmxi_plugin') ?>">?</a>
												<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>
						<?php
						break;		
					case 'taxonomy':
						?>
						<div class="input">
							<div class="main_choise">
								<input type="radio" id="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="switcher" name="is_multiple_field_value<?php echo $field_name; ?>[<?php echo $field['key'];?>]" value="yes" <?php echo 'no' != $current_is_multiple_field_value ? 'checked="checked"': '' ?>/>
								<label for="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="chooser_label"><?php _e("Select value for all records"); ?></label>
							</div>
							<div class="wpallimport-clear"></div>
							<div class="switcher-target-is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes">
								<div class="input sub_input">
									<div class="input">
										<?php

										if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

											$field_class = 'acf_field_' . $field['type'];										

											$field['other_choice'] = false;
											$field['_input'] = 'multiple_value'. $field_name .'[' . $field['key'] . ']';
											$field['value'] = $current_multiple_value;									

											acf_render_field( $field );

										} else{
										
											$field_class = 'acf_field_' . $field['type'];
											$new_field = new $field_class();

											$field['other_choice'] = false;
											$field['name'] = 'multiple_value'. $field_name .'[' . $field['key'] . ']';
											$field['value'] = $current_multiple_value;									

											$new_field->create_field( $field );

										}
										?>
									</div>
								</div>
							</div>
						</div>											
						<div class="clear"></div>
						<div class="input" style="overflow:hidden;">
							<div class="main_choise">
								<input type="radio" id="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="switcher" name="is_multiple_field_value<?php echo $field_name; ?>[<?php echo $field['key'];?>]" value="no" <?php echo 'no' == $current_is_multiple_field_value ? 'checked="checked"': '' ?>/>
								<label for="is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="chooser_label"><?php _e('Set with XPath', 'pmxi_plugin' )?></label>
							</div>
							<div class="wpallimport-clear"></div>
							<div class="switcher-target-is_multiple_field_value_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no">
								<div class="input sub_input">
									<div class="input">
										<table class="pmai_taxonomy post_taxonomy">
											<tr>
												<td>
													<div class="col2" style="clear: both;">
														<ol class="sortable no-margin">
															<?php
															if ( ! empty($current_field) ):
																	$taxonomies_hierarchy = json_decode($current_field);																
																
																	if ( ! empty($taxonomies_hierarchy) and is_array($taxonomies_hierarchy)): $i = 0; foreach ($taxonomies_hierarchy as $cat) { $i++;
																		if ( is_null($cat->parent_id) or empty($cat->parent_id) )
																		{
																			?>
																			<li id="item_<?php echo $i; ?>" class="dragging">
																				<div class="drag-element">
																					<input type="text" class="widefat xpath_field rad4" value="<?php echo esc_attr($cat->xpath); ?>"/>
																				</div>
																				<?php if ( $i > 1 ): ?><a href="javascript:void(0);" class="icon-item remove-ico"></a><?php endif; ?>

																				<?php echo reverse_taxonomies_html($taxonomies_hierarchy, $cat->item_id, $i); ?>
																			</li>
																			<?php
																		}
																	}; else:?>
																	<li id="item_1" class="dragging">
																		<div class="drag-element" >
																			<!--input type="checkbox" class="assign_post" checked="checked" title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/-->														
																			<input type="text" class="widefat xpath_field rad4" value=""/>
																			<a href="javascript:void(0);" class="icon-item remove-ico"></a>
																		</div>
																	</li>
																	<?php endif;
																  else: ?>
														    <li id="item_1" class="dragging">
														    	<div class="drag-element">
														    		<!--input type="checkbox" class="assign_post" checked="checked" title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/-->									    		
														    		<input type="text" class="widefat xpath_field rad4" value=""/>
														    		<a href="javascript:void(0);" class="icon-item remove-ico"></a>
														    	</div>
														    </li>
															<?php endif;?>
															<li id="item" class="template">
														    	<div class="drag-element">
														    		<!--input type="checkbox" class="assign_post" checked="checked" title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/-->									    		
														    		<input type="text" class="widefat xpath_field rad4" value=""/>
														    		<a href="javascript:void(0);" class="icon-item remove-ico"></a>
														    	</div>
														    </li>
														</ol>
														<input type="hidden" class="hierarhy-output" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>]" value="<?php echo esc_attr($current_field); ?>"/>													
														<?php //$taxonomies_hierarchy = json_decode($current_field, true);?>
														<div class="delim">														
															<a href="javascript:void(0);" class="icon-item add-new-ico"><?php _e('Add more','pmxi_plugin');?></a>
														</div>
													</div>
												</td>
											</tr>										
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php
						break;								
					case 'repeater':

						?>
						<div class="repeater">
							
							<div class="input" style="margin-bottom: 10px;">
							
								<div class="input">
									<input type="radio" id="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="switcher variable_repeater_mode" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][is_variable]" value="no" <?php echo 'yes' != $current_field['is_variable'] ? 'checked="checked"': '' ?>/>
									<label for="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no" class="chooser_label"><?php _e('Fixed Repeater Mode', 'pmxi_plugin' )?></label>
								</div>
								<div class="wpallimport-clear"></div>
								<div class="switcher-target-is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no">
									<div class="input sub_input">
										<div class="input">
											<input type="hidden" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][is_ignore_empties]" value="0"/>
											<input type="checkbox" value="1" id="is_ignore_empties<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][is_ignore_empties]" <?php if ( ! empty($current_field['is_ignore_empties'])) echo 'checked="checked';?>/>
											<label for="is_ignore_empties<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>"><?php _e('Ignore blank fields', 'pmxi_plugin'); ?></label>
											<a href="#help" class="wpallimport-help" style="top:0;" title="<?php _e('If the value of the element or column in your file is blank, it will be ignored. Use this option when some records in your file have a different number of repeating elements than others.', 'pmxi_plugin') ?>">?</a>
										</div>
									</div>
								</div>
								<div class="input">
									<input type="radio" id="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="switcher variable_repeater_mode" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][is_variable]" value="yes" <?php echo 'yes' == $current_field['is_variable'] ? 'checked="checked"': '' ?>/>
									<label for="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes" class="chooser_label"><?php _e('Variable Repeater Mode (XML)', 'pmxi_plugin' )?></label>
								</div>																	
								<div class="input">
									<input type="radio" id="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes_csv" class="switcher variable_repeater_mode" name="fields<?php echo $field_name; ?>[<?php echo $field['key'];?>][is_variable]" value="csv" <?php echo 'csv' == $current_field['is_variable'] ? 'checked="checked"': '' ?>/>
									<label for="is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes_csv" class="chooser_label"><?php _e('Variable Repeater Mode (CSV)', 'pmxi_plugin' )?></label>									
								</div>																	
								<div class="wpallimport-clear"></div>
								<div class="switcher-target-is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes">
									<div class="input sub_input">
										<div class="input">
											<p>
												<?php printf(__("For each %s do ..."), '<input type="text" name="fields' . $field_name . '[' . $field["key"] . '][foreach]" value="'. $current_field["foreach"] .'" class="pmai_foreach widefat rad4"/>'); ?>											
												<a href="http://www.wpallimport.com/documentation/advanced-custom-fields/repeaters/" target="_blank"><?php _e('(documentation)', 'pmxi_plugin'); ?></a>
											</p>
										</div>
									</div>
								</div>
								<div class="switcher-target-is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_yes_csv">
									<div class="input sub_input">
										<div class="input">
											<p>
												<?php printf(__("Separator Character %s"), '<input type="text" name="fields' . $field_name . '[' . $field["key"] . '][separator]" value="'. ( (empty($current_field["separator"])) ? '|' : $current_field["separator"] ) .'" class="pmai_variable_separator widefat rad4"/>'); ?>											
												<a href="#help" class="wpallimport-help" style="top:0;" title="<?php _e('Use this option when importing a CSV file with a column or columns that contains the repeating data, separated by separators. For example, if you had a repeater with two fields - image URL and caption, and your CSV file had two columns, image URL and caption, with values like "url1,url2,url3" and "caption1,caption2,caption3", use this option and specify a comma as the separator.', 'pmxi_plugin') ?>">?</a>
											</p>
										</div>
									</div>
								</div>

							</div>							

							<table class="widefat acf-input-table row_layout">								
								<tbody>
									<?php 																													
									if (!empty($current_field['rows'])) : foreach ($current_field['rows'] as $key => $row): if ("ROWNUMBER" == $key) continue; ?>									
									<tr class="row">							
										<td class="order" style="padding:8px;"><?php echo $key; ?></td>	
										<td class="acf_input-wrap" style="padding:0 !important;">
											<table class="widefat acf_input" style="border:none;">
												<tbody>
													<?php 

													if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){
																												
														$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => ((!empty($field['id'])) ? $field['id'] : $field['ID']), 'post_status' => 'publish'));

														if ( ! empty($sub_fields) ){

															foreach ($sub_fields as $n => $sub_field){
																$sub_fieldData = (!empty($sub_field->post_content)) ? unserialize($sub_field->post_content) : array();			
																$sub_fieldData['id'] = $sub_field->ID;
																$sub_fieldData['label'] = $sub_field->post_title;
																$sub_fieldData['key'] = $sub_field->post_name;																
																?>
																<tr class="field sub_field field_type-<?php echo $sub_fieldData['type'];?> field_key-<?php echo $sub_fieldData['key'];?>">
																	<td class="label">
																		<?php echo $sub_fieldData['label'];?>
																	</td>
																	<td>
																		<div class="inner input">																			
																			<?php echo pmai_render_field($sub_fieldData, $post, $field_name . "[" . $field['key'] . "][rows][" . $key . "]"); ?>
																		</div>
																	</td>
																</tr>													
																<?php 
															}
														}

													} else{
														
														foreach ($field['sub_fields'] as $n => $sub_field){ ?>
														<tr class="field sub_field field_type-<?php echo $sub_field['type'];?> field_key-<?php echo $sub_field['key'];?>">
															<td class="label">
																<?php echo $sub_field['label'];?>
															</td>
															<td>
																<div class="inner input">
																	<?php echo pmai_render_field($sub_field, $post, $field_name . "[" . $field['key'] . "][rows][" . $key . "]"); ?>
																</div>
															</td>
														</tr>													
														<?php 
														}
													} 
													?>
												</tbody>
											</table>
										</td>
									</tr>
									<?php endforeach; endif; ?>															
									<tr class="row-clone">							
										<td class="order" style="padding:8px;"></td>		
										<td class="acf_input-wrap" style="padding:0 !important;">
											<table class="widefat acf_input" style="border:none;">
												<tbody>
													<?php 
													if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

														$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => ((!empty($field['id'])) ? $field['id'] : $field['ID']), 'post_status' => 'publish'));

														if ( ! empty($sub_fields) ){

															foreach ($sub_fields as $key => $sub_field){
																$sub_fieldData = (!empty($sub_field->post_content)) ? unserialize($sub_field->post_content) : array();			
																$sub_fieldData['ID'] = $sub_field->ID;
																$sub_fieldData['label'] = $sub_field->post_title;
																$sub_fieldData['key'] = $sub_field->post_name;																
																?>
																<tr class="field sub_field field_type-<?php echo $sub_fieldData['type'];?> field_key-<?php echo $sub_fieldData['key'];?>">
																	<td class="label">
																		<?php echo $sub_fieldData['label'];?>
																	</td>
																	<td>
																		<div class="inner">
																			<?php echo pmai_render_field($sub_fieldData, $post, $field_name . "[" . $field['key'] . "][rows][ROWNUMBER]"); ?>
																		</div>	
																	</td>
																</tr>													
																<?php 
															}
														}
													}	
													else { 

														foreach ($field['sub_fields'] as $key => $sub_field){ ?>
														<tr class="field sub_field field_type-<?php echo $sub_field['type'];?> field_key-<?php echo $sub_field['key'];?>">
															<td class="label">
																<?php echo $sub_field['label'];?>
															</td>
															<td>
																<div class="inner">
																	<?php echo pmai_render_field($sub_field, $post, $field_name . "[" . $field['key'] . "][rows][ROWNUMBER]"); ?>
																</div>	
															</td>
														</tr>													
														<?php 
														} 
													} 
													?>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>								
							<div class="wpallimport-clear"></div>
							<div class="switcher-target-is_variable_<?php echo str_replace(array('[',']'), '', $field_name);?>_<?php echo $field['key'];?>_no">
								<div class="input sub_input">
									<ul class="hl clearfix repeater-footer">
										<li class="right">
											<a href="javascript:void(0);" class="acf-button delete_row" style="margin-left:15px;"><?php _e('Delete Row', 'pmxi_plugin'); ?></a>
										</li>
										<li class="right">
											<a class="add-row-end acf-button" href="javascript:void(0);"><?php _e("Add Row", "pmxi_plugin");?></a>									
										</li>								
									</ul>							
								</div>							
							</div>							
						</div>
						<?php

						break;
					case 'validated_field':

						if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

							/*$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => ((!empty($field['id'])) ? $field['id'] : $field['ID']), 'post_status' => 'publish'));

							if ( ! empty($sub_fields) ){

								foreach ($sub_fields as $key => $sub_field){
									$sub_fieldData = (!empty($sub_field->post_content)) ? unserialize($sub_field->post_content) : array();			
									$sub_fieldData['ID'] = $sub_field->ID;
									$sub_fieldData['label'] = $sub_field->post_title;
									$sub_fieldData['key'] = $sub_field->post_name;																
									?>
									<tr class="field sub_field field_type-<?php echo $sub_fieldData['type'];?> field_key-<?php echo $sub_fieldData['key'];?>">
										<td class="label">
											<?php echo $sub_fieldData['label'];?>
										</td>
										<td>
											<div class="inner">
												<?php echo pmai_render_field($sub_fieldData, $post, $field_name . "[" . $field['key'] . "][rows][ROWNUMBER]"); ?>
											</div>	
										</td>
									</tr>													
									<?php 
								}
							}*/
						}	
						else { 
							if (!empty($field['sub_fields'])){
								foreach ($field['sub_fields'] as $key => $sub_field){ ?>
								<tr class="field sub_field field_type-<?php echo $sub_field['type'];?> field_key-<?php echo $sub_field['key'];?>">
									<td class="label">
										<?php echo $sub_field['label'];?>
									</td>
									<td>
										<div class="inner">
											<?php echo pmai_render_field($sub_field, $post, $field_name . "[" . $field['key'] . "][rows][ROWNUMBER]"); ?>
										</div>	
									</td>
								</tr>													
								<?php 
								} 
							}
							elseif (!empty($field['sub_field'])){
								?>
								<tr class="field sub_field field_type-<?php echo $field['sub_field']['type'];?> field_key-<?php echo $field['sub_field']['key'];?>">									
									<td>
										<div class="inner">
											<?php echo pmai_render_field($field['sub_field'], $post, $field_name ); ?>
										</div>	
									</td>
								</tr>													
								<?php
							}
						} 
						
						break;
					
					case 'message':

						break;

					default:
						?>
						<p>
							<?php
								_e('This field type is not supported. E-mail support@soflyy.com with the details of the custom ACF field you are trying to import to, as well as a link to download the plugin to install to add this field type to ACF, and we will investigate the possiblity ot including support for it in the ACF add-on.', 'pmxi_plugin');
							?>
						</p>
						<?php
						break;
				}
				?>									
			</div>			
		</div>
		<?php endif; 		
	}
}
?>