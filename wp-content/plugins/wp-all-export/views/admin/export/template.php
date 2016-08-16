<h2 class="wpallexport-wp-notices"></h2>

<div class="wpallexport-wrapper">
	<div class="wpallexport-header">
		<div class="wpallexport-logo"></div>
		<div class="wpallexport-title">
			<p><?php _e('WP All Export', 'wp_all_export_plugin'); ?></p>
			<h2><?php _e('Export to XML / CSV', 'wp_all_export_plugin'); ?></h2>					
		</div>
		<div class="wpallexport-links">
			<a href="http://www.wpallimport.com/support/" target="_blank"><?php _e('Support', 'wp_all_export_plugin'); ?></a> | <a href="http://www.wpallimport.com/documentation/" target="_blank"><?php _e('Documentation', 'wp_all_export_plugin'); ?></a>
		</div>
	</div>	
	<div class="clear"></div>
</div>	

<div class="clear"></div>

<div class="wpallexport-content-section wpallexport-console" style="display: block; margin-bottom: 10px;">
	<div class="ajax-console">
		<div class="founded_records">									
			<div class="wp_all_export_preloader"></div>
			<h4><?php _e("Choose data to include in the export file."); ?></h4>
		</div>		
	</div>		
</div>

<?php XmlExportFiltering::render_filtering_block( $engine, $this->isWizard, $post, true ); ?>

<table class="wpallexport-layout wpallexport-export-template">	
	<tr>
		<td class="left">		

			<?php do_action('pmxe_template_header', $this->isWizard, $post); ?>

			<?php if ($this->errors->get_error_codes()): ?>
				<?php $this->error(); ?>
			<?php endif ?>			

			<form class="wpallexport-template <?php echo ! $this->isWizard ? 'edit' : '' ?> wpallexport-step-3" method="post" style="display:none;">		
				
				<?php 
				$selected_post_type = '';
				if (XmlExportUser::$is_active):
					$selected_post_type = empty($post['cpt'][0]) ? 'users' : $post['cpt'][0];
				endif;
				if (XmlExportComment::$is_active):
					$selected_post_type = 'comments';
				endif;
				if (empty($selected_post_type) and ! empty($post['cpt'][0]))
				{
					$selected_post_type = $post['cpt'][0];
				}
				?>				

				<input type="hidden" name="selected_post_type" value="<?php echo $selected_post_type; ?>"/>		
				<input type="hidden" name="export_type" value="<?php echo $post['export_type']; ?>"/>																			

				<div class="wpallexport-collapsed wpallexport-section">
					<div class="wpallexport-content-section">
						<div class="wpallexport-collapsed-content" style="padding-bottom: 60px;">
							<fieldset class="optionsset" style="padding: 10px 20px;">								
								<div id="columns_to_export">								
									<div class="columns-to-export-content" style="padding-right: 8px;">
										<ol id="columns" class="rad4">										
											<?php												
												$i = 0;
												$new_export = false;
												if ( ! empty($post['ids']) )
												{
													foreach ($post['ids'] as $ID => $value) 
													{
														if (is_numeric($ID)){ if (empty($post['cc_name'][$ID])) continue;
															?>
															<li>
																<div class="custom_column" rel="<?php echo ($i + 1);?>">
																	<?php 
																		$field_label = (!empty($post['cc_name'][$ID])) ? $post['cc_name'][$ID] : $post['cc_label'][$ID]; 
																		$field_name  = (!empty($post['cc_name'][$ID])) ? $post['cc_name'][$ID] : trim(str_replace(" ", "_", $post['cc_label'][$ID]));
																		$field_type = $post['cc_type'][$ID];
																		$field_options = esc_html($post['cc_options'][$ID]);
																	?>
																	<label class="wpallexport-xml-element"><?php echo (strtolower($field_label) == "id") ? "ID" : $field_label; ?></label>
																	<input type="hidden" name="ids[]" value="1"/>
																	<input type="hidden" name="cc_label[]" value="<?php echo (!empty($post['cc_label'][$ID])) ? $post['cc_label'][$ID] : ''; ?>"/>
																	<input type="hidden" name="cc_php[]" value="<?php echo (!empty($post['cc_php'][$ID])) ? $post['cc_php'][$ID] : 0; ?>"/>								
																	<input type="hidden" name="cc_code[]" value="<?php echo (!empty($post['cc_code'][$ID])) ? $post['cc_code'][$ID] : ''; ?>"/>								
																	<input type="hidden" name="cc_sql[]" value="<?php echo (!empty($post['cc_sql'][$ID])) ? $post['cc_sql'][$ID] : ''; ?>"/>								
																	<input type="hidden" name="cc_type[]" value="<?php echo $field_type; ?>"/>
																	<input type="hidden" name="cc_options[]" value="<?php echo $field_options; ?>"/>
																	<input type="hidden" name="cc_value[]" value="<?php echo esc_attr($post['cc_value'][$ID]); ?>"/>
																	<input type="hidden" name="cc_name[]" value="<?php echo (strtoupper($field_name) == "ID") ? "id" : $field_name; ?>"/>																	
																	<input type="hidden" name="cc_settings[]" value="<?php echo (!empty($post['cc_settings'][$ID])) ? esc_attr($post['cc_settings'][$ID]) : ''; ?>"/>
																</div>
															</li>
															<?php
															$i++;
														}																					
													}
												}	
												elseif ($this->isWizard)
												{
													$new_export = true;																										
													if ( empty($post['cpt']) and ! XmlExportWooCommerceOrder::$is_active and ! XmlExportUser::$is_active and ! XmlExportComment::$is_active ){
														$init_fields[] = 
															array(
																'label' => 'post_type',
																'name'  => 'post_type',
																'type'  => 'post_type'
															);
													}
													foreach ($init_fields as $k => $field) {
														?>
														<li>
															<div class="custom_column" rel="<?php echo ($i + 1);?>">															
																<label class="wpallexport-xml-element"><?php echo $field['name']; ?></label>
																<input type="hidden" name="ids[]" value="1"/>
																<input type="hidden" name="cc_label[]" value="<?php echo $field['label']; ?>"/>
																<input type="hidden" name="cc_php[]" value=""/>																		
																<input type="hidden" name="cc_code[]" value=""/>
																<input type="hidden" name="cc_sql[]" value=""/>	
																<input type="hidden" name="cc_options[]" value="<?php echo (empty($field['options'])) ? '' : $field['options']; ?>"/>																										
																<input type="hidden" name="cc_type[]" value="<?php echo $field['type']; ?>"/>
																<input type="hidden" name="cc_value[]" value="<?php echo $field['label']; ?>"/>
																<input type="hidden" name="cc_name[]" value="<?php echo (strtoupper($field['name']) == 'ID') ? 'id' : $field['name'];?>"/>													
																<input type="hidden" name="cc_settings[]" value=""/>
															</div>
														</li>
														<?php
														$i++;
													}
												}
												?>
												<li class="placeholder" <?php if ( ! empty($post['ids']) and count($post['ids']) > 1 or $new_export) echo 'style="display:none;"'; ?>><?php _e("Drag & drop data from \"Available Data\" on the right to include it in the export or click \"Add Field To Export\" below.", "wp_all_export_plugin"); ?></li>
												<?php																														
											?>
										</ol>
									</div>
								</div>							

								<div class="custom_column template">								
									<label class="wpallexport-xml-element"></label>
									<input type="hidden" name="ids[]" value="1"/>
									<input type="hidden" name="cc_label[]" value=""/>
									<input type="hidden" name="cc_php[]" value=""/>
									<input type="hidden" name="cc_code[]" value=""/>
									<input type="hidden" name="cc_sql[]" value=""/>
									<input type="hidden" name="cc_type[]" value=""/>
									<input type="hidden" name="cc_options[]" value=""/>								
									<input type="hidden" name="cc_value[]" value=""/>
									<input type="hidden" name="cc_name[]" value=""/>
									<input type="hidden" name="cc_settings[]" value=""/>									
								</div>

								<!-- Warning Messages -->
								<?php if ( ! XmlExportWooCommerceOrder::$is_active &&  ! XmlExportComment::$is_active ) : ?>
								<div class="wp-all-export-warning" <?php if ( empty($post['ids']) or count($post['ids']) > 1 ) echo 'style="display:none;"'; ?>>
									<p></p>
									<input type="hidden" id="warning_template" value="<?php _e("Warning: without %s you won't be able to re-import this data back to this site using WP All Import.", "wp_all_export_plugin"); ?>"/>
								</div>
								<?php endif; ?>

								<?php if ( XmlExportWooCommerce::$is_active ) : ?>
								<input type="hidden" id="is_product_export" value="1"/>													
								<?php endif; ?>

								<?php if ( empty($post['cpt']) and ! XmlExportWooCommerceOrder::$is_active and ! XmlExportUser::$is_active and ! XmlExportComment::$is_active ) : ?>
								<input type="hidden" id="is_wp_query" value="1"/>								
								<?php endif; ?>
																									
							</fieldset>

							<!-- Add New Field Button -->
							<div class="input" style="float:left; margin: 0 20px 15px;">
								<input type="button" value="<?php _e('Add Field', 'wp_all_export_plugin');?>" class="add_column" style="float:left;">								
								<input type="button" value="<?php _e('Add All', 'wp_all_export_plugin'); ?>" class="wp_all_export_auto_generate_data">								
								<input type="button" value="<?php _e('Clear All', 'wp_all_export_plugin'); ?>" class="wp_all_export_clear_all_data">								
							</div>

							<!-- Preview a Row Button -->
							<div class="input" style="float:right; margin: 0 20px 15px;">								
								<input type="button" value="<?php _e('Preview', 'wp_all_export_plugin');?>" class="preview_a_row">	
							</div>
						</div>
					</div>
				</div>

				<div class="wpallexport-collapsed wpallexport-section wpallexport-file-options closed" style="margin-top: -10px;">
					<div class="wpallexport-content-section" style="padding-bottom: 15px; margin-bottom: 10px;">
						<div class="wpallexport-collapsed-header" style="padding-left: 25px;">
							<h3><?php _e('Export File Options','wp_all_export_plugin');?></h3>	
						</div>
						<div class="wpallexport-collapsed-content" style="padding: 0; overflow: hidden; height: auto;">
							<div class="wpallexport-collapsed-content-inner">								
								<div class="wpallexport-choose-data-type">
									<h3 style="margin-top: 10px; margin-bottom: 40px;"><?php _e('Choose your export file type', 'wp_all_export_plugin'); ?></h3>
									<a href="javascript:void(0);" class="wpallexport-import-to-format rad4 wpallexport-csv-type <?php if ($post['export_to'] != 'xml') echo 'selected'; ?>">										
										<span class="wpallexport-import-to-title"><?php _e('Spreadsheet', 'wp_all_export_plugin'); ?></span>
										<span class="wpallexport-import-to-arrow"></span>
									</a>
									<a href="javascript:void(0);" class="wpallexport-import-to-format rad4 wpallexport-xml-type <?php if ($post['export_to'] == 'xml') echo 'selected'; ?>" style="margin-right:0;">										
										<span class="wpallexport-import-to-title"><?php _e('XML Feed', 'wp_all_export_plugin'); ?></span>
										<span class="wpallexport-import-to-arrow"></span>
									</a>
								</div>

								<div class="wpallexport-all-options">

									<input type="hidden" name="export_to" value="<?php echo $post['export_to']; ?>"/>									

									<div class="wpallexport-file-format-options">

										<div class="wpallexport-csv-options" <?php if ($post['export_to'] == 'xml') echo 'style="display:none;"'; ?>>										
											<!-- Export File Format -->
											<div class="input wp-all-export-format">																	
												<div class="input" style="float: left; padding-bottom: 5px; width: 100%;">
													<div class="input" style="float: left; margin-right: 20px; height: 35px; vertical-align: middle;">
														<label style="margin-right:10px;"><?php _e("File Format:", "wp_all_export_plugin"); ?></label>
														<input type="radio" id="export_to_xls" class="switcher" name="export_to_sheet" value="xls" <?php echo 'xls' == $post['export_to_sheet'] ? 'checked="checked"': '' ?>/>																										
														<label for="export_to_xls"><?php _e('XLS', 'wp_all_export_plugin' )?></label>
													</div>
													<div class="input" style="float:left; height: 35px; vertical-align: middle;">
														<input type="radio" id="export_to_csv" class="switcher" name="export_to_sheet" value="csv" <?php echo 'csv' == $post['export_to_sheet'] ? 'checked="checked"': '' ?>/>
														<label for="export_to_csv"><?php _e('CSV', 'wp_all_export_plugin' )?></label>
													</div>
													<div class="input switcher-target-export_to_csv" style="float: left; vertical-align:middle;  height: 35px; position: relative; top: -6px;">
														<label style="width: 80px; margin-left: 20px;"><?php _e('Separator:','wp_all_export_plugin');?></label> 
														<input type="text" name="delimiter" value="<?php echo esc_attr($post['delimiter']) ?>" style="width: 40px; height: 30px; top: 0px; text-align: center;"/>
													</div>	
													<div class="wpallexport-clear"></div>
													<div class="input switcher-target-export_to_xls" style="vertical-align:middle; position: relative; top: -13px;">														
														<span class="wpallexport-free-edition-notice">									
															<a class="upgrade_link" target="_blank" href="http://www.wpallimport.com/upgrade-to-wp-all-export-pro/?utm_source=wordpress.org&amp;utm_medium=wooco+orders&amp;utm_campaign=free+wp+all+export+plugin"><?php _e('Upgrade to the Pro edition of WP All Export to export to Excel. <br>If you already own it, remove the free edition and install the Pro edition.','wp_all_export_plugin');?></a>
														</span>														
													</div>
												</div>
												<div class="clear"></div>												
											</div>
											<!-- Display each product in its own row -->
											<?php if ( XmlExportWooCommerceOrder::$is_active ): ?>
											<div class="input" style="float: left;">
												<input type="hidden" name="order_item_per_row" value="0"/>
												<input type="checkbox" id="order_item_per_row" name="order_item_per_row" value="1" <?php if ($post['order_item_per_row']):?>checked="checked"<?php endif; ?> class="switcher"/>
												<label for="order_item_per_row"><?php _e("Display each product in its own row", "wp_all_export_plugin"); ?></label>
												<a href="#help" class="wpallexport-help" style="position: relative; top: 0px;" title="<?php _e('If an order contains multiple products, each product will have its own row. If disabled, each product will have its own column.', 'wp_all_export_plugin'); ?>">?</a>
												<div class="input switcher-target-order_item_per_row" style="margin-top: 10px; padding-left: 15px;">
													<input type="hidden" name="order_item_fill_empty_columns" value="0"/>
													<input type="checkbox" id="order_item_fill_empty_columns" name="order_item_fill_empty_columns" value="1" <?php if ($post['order_item_fill_empty_columns']):?>checked="checked"<?php endif; ?>/>
													<label for="order_item_fill_empty_columns"><?php _e("Fill in empty columns", "wp_all_export_plugin"); ?></label>
													<a href="#help" class="wpallexport-help" style="position: relative; top: 0px;" title="<?php _e('If enabled, each order item will appear as its own row with all order info filled in for every column. If disabled, order info will only display on one row with only the order item info displaying in additional rows.', 'wp_all_export_plugin'); ?>">?</a>
												</div>
											</div>
											<?php endif; ?>
										</div>

										<div class="wpallexport-xml-options" <?php if ($post['export_to'] != 'xml') echo 'style="display:none;"'; ?>>

											<div class="input" style="display: inline-block; max-width: 360px; width: 40%; margin-right: 10px;">
												<label for="main_xml_tag" style="float: left;"><?php _e('Root XML Element','wp_all_export_plugin');?></label> 
												<div class="input">
													<input type="text" name="main_xml_tag" style="vertical-align:middle; background:#fff !important; width: 100%;" value="<?php echo esc_attr($post['main_xml_tag']) ?>" />														
												</div>
											</div>
											<div class="input" style="display: inline-block; max-width: 360px; width: 40%; ">
												<?php										
													$post_type_details = ( ! empty($post['cpt'])) ? get_post_type_object( $post['cpt'][0] ) : '';				
												?>
												<label for="record_xml_tag" style="float: left;"><?php printf(__('Single %s XML Element','wp_all_export_plugin'), empty($post_type_details) ? 'Record' : $post_type_details->labels->singular_name); ?></label> 
												<div class="input">
													<input type="text" name="record_xml_tag" style="vertical-align:middle; background:#fff !important; width: 100%;" value="<?php echo esc_attr($post['record_xml_tag']) ?>" />														
												</div>
											</div>
										</div>
									</div>
								</div>																												
							</div>
						</div>
					</div>
				</div>

				<hr>
				
				<div class="input wpallexport-section" style="padding-bottom: 8px; padding-left: 8px;">								
										
					<p style="margin: 11px; float: left;">
						<input type="hidden" name="save_template_as" value="0" />
						<input type="checkbox" id="save_template_as" name="save_template_as" class="switcher-horizontal fix_checkbox" value="1" <?php echo ( ! empty($post['save_template_as'])) ? 'checked="checked"' : '' ?> /> 
						<label for="save_template_as"><?php _e('Save settings as a template','wp_all_export_plugin');?></label>
					</p>
					<div class="switcher-target-save_template_as" style="float: left; overflow: hidden;">
						<input type="text" name="name" placeholder="<?php _e('Template name...', 'wp_all_export_plugin') ?>" style="vertical-align:middle; line-height: 26px;" value="<?php echo esc_attr($post['name']) ?>" />		
					</div>				
					<?php $templates = new PMXE_Template_List(); ?>
					<div class="load-template">				
						<select name="load_template" id="load_template" style="padding:2px; width: auto; height: 40px;">
							<option value=""><?php _e('Load Template...', 'wp_all_export_plugin') ?></option>
							<?php foreach ($templates->getBy()->convertRecords() as $t): ?>
								<?php 		
									// When creating a new export you should be able to select existing saved export templates that were created for the same post type.						
									if ( $t->options['cpt'] != $post['cpt'] ) continue;
								?>
								<option value="<?php echo $t->id ?>"><?php echo $t->name ?></option>
							<?php endforeach ?>
						</select>
					</div>
					
				</div>
				
				<hr>

				<div class="wpallexport-submit-buttons">
					
					<div style="text-align:center; width:100%;">
						<?php wp_nonce_field('template', '_wpnonce_template'); ?>
						<input type="hidden" name="is_submitted" value="1" />									

						<?php if ( ! $this->isWizard ): ?>
							<a href="<?php echo remove_query_arg('id', remove_query_arg('action', $this->baseUrl)); ?>" class="back rad3" style="float:none;"><?php _e('Back to Manage Exports', 'wp_all_export_plugin') ?></a>
						<?php else: ?>						
							<a href="<?php echo add_query_arg('action', 'index', $this->baseUrl); ?>" class="back rad3"><?php _e('Back', 'wp_all_export_plugin') ?></a>							
						<?php endif; ?>					
						<input type="submit" class="button button-primary button-hero wpallexport-large-button" value="<?php _e( ($this->isWizard) ? 'Continue' : 'Update Template', 'wp_all_export_plugin') ?>" />
					</div>

				</div>

				<a href="http://soflyy.com/" target="_blank" class="wpallexport-created-by"><?php _e('Created by', 'wp_all_export_plugin'); ?> <span></span></a>

			</form>			
			
		</td>
		
		<td class="right template-sidebar" style="position: relative; width: 18%; right: 0px; padding: 0;">										

			<fieldset id="available_data" class="optionsset rad4">

				<div class="title"><?php _e('Available Data', 'wp_all_export_plugin'); ?></div>				

				<div class="wpallexport-xml resetable"> 					

					<ul>

						<?php echo $available_data_view; ?>

					</ul>		
										
				</div>					

			</fieldset>	
		</td>	
	</tr>

</table>	

<fieldset class="optionsset column rad4 wp-all-export-edit-column">
				
	<div class="title"><span class="wpallexport-add-row-title"><?php _e('Add Field To Export','wp_all_export_plugin');?></span><span class="wpallexport-edit-row-title"><?php _e('Edit Export Field','wp_all_export_plugin');?></span></div>

	<?php include_once 'template/add_new_field.php'; ?>
	
</fieldset>

<div class="wpallexport-overlay"></div>
