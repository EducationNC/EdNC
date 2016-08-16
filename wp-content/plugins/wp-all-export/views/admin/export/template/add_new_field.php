<form>	
	<div class="wp-all-export-field-options">
		<div class="input" style="margin-bottom:10px;">
			<label for="column_value_default" style="padding:4px; display: block;"><?php _e('What field would you like to export?', 'wp_all_export_plugin' )?></label>
			<div class="clear"></div>
			<?php echo $available_fields_view; ?>																													
		</div>					
		
		<div class="input">
			<label style="padding:4px; display: block;"><?php _e('What would you like to name the column/element in your exported file?','wp_all_export_plugin');?></label>
			<div class="clear"></div>
			<input type="text" class="column_name" value="" style="width:50%"/>
		</div>
		
		<a href="javascript:void(0);" class="wp-all-export-advanced-field-options"><span>+</span> <?php _e("Advanced", 'wp_all_export_plugin'); ?></a>

		<!-- Advanced Field Options -->
		
		<?php include_once 'advanced_field_options.php'; ?>

	</div>																		
	<div class="input wp-all-export-edit-column-buttons">			
		<input type="button" class="delete_action" value="<?php _e("Delete", "wp_all_export_plugin"); ?>" style="border: none;"/>									
		<input type="button" class="save_action"   value="<?php _e("Done", "wp_all_export_plugin"); ?>"   style="border: none;"/>	
		<input type="button" class="close_action"  value="<?php _e("Close", "wp_all_export_plugin"); ?>"  style="border: none;"/>
	</div>
</form>