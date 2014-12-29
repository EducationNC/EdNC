<?php

function pmai_wp_ajax_get_acf(){

	global $acf;

	$version = ($acf) ? $acf->settings['version'] : false;	

	ob_start();	

	$acf_groups = PMXI_Plugin::$session->acf_groups;

	$acf_obj = false;

	if (!empty($acf_groups)){
		foreach ($acf_groups as $key => $group) {			
			if ($group['ID'] == $_GET['acf']){
				$acf_obj = $group;
				break;				
			}
		}
	}	
	
	$import = new PMXI_Import_Record();				
	
	if ( ! empty($_GET['id']) )
		$import->getById($_GET['id']);

	if ( ! $import->isEmpty() ) {
		$options = $import->options;
	}
	else
	{
		$options = PMXI_Plugin::$session->options;
	}

	?>
	<div class="postbox  acf_postbox default acf_signle_group rad4" rel="<?php echo $acf_obj['ID']; ?>">
		<h3 class="hndle" style="margin-top:0;"><span><?php echo $acf_obj['title']; ?></span></h3>
		<div class="inside">
		<?php

			if ($version and version_compare($version, '5.0.0') >= 0){								

				if ( is_numeric($acf_obj['ID'])){
					$acf_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => $_GET['acf'], 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC'));				

					if ( ! empty($acf_fields) ){

						foreach ($acf_fields as $field) {				

							$fieldData = (!empty($field->post_content)) ? unserialize($field->post_content) : array();			
							
							$fieldData['id']    = $field->ID;
							$fieldData['label'] = $field->post_title;
							$fieldData['key']   = $field->post_name;					

							echo pmai_render_field($fieldData, ( ! empty($options) ) ? $options : array() );
						}
					}
				}
				else{
					$fields = acf_local()->fields;
					
					if (!empty($fields)){
						foreach ($fields as $key => $field) {
							if ($field['parent'] == $acf_obj['key']){								
								$fieldData = $field;
							
								$fieldData['id']    = uniqid();
								$fieldData['label'] = $field['label'];
								$fieldData['key']   = $field['key'];					

								echo pmai_render_field($fieldData, ( ! empty($options) ) ? $options : array() );
							}
						}
					}			
				}
				
			}
			else {

				if (is_numeric($acf_obj['ID'])){

					$fields = array();

					foreach (get_post_meta($acf_obj['ID'], '') as $cur_meta_key => $cur_meta_val)
					{	
						if (strpos($cur_meta_key, 'field_') !== 0) continue;

						$fields[] = (!empty($cur_meta_val[0])) ? unserialize($cur_meta_val[0]) : array();			
												
					}

					if (count($fields)){

						$sortArray = array();

						foreach($fields as $field){
						    foreach($field as $key=>$value){
						        if(!isset($sortArray[$key])){
						            $sortArray[$key] = array();
						        }
						        $sortArray[$key][] = $value;
						    }
						}

						$orderby = "order_no"; 

						array_multisort($sortArray[$orderby],SORT_ASC,$fields); 

						foreach ($fields as $field) {
							echo pmai_render_field($field, ( ! empty($options) ) ? $options : array() );
						}
					}

				}
				else{

					global $acf_register_field_group;

					if (!empty($acf_register_field_group)){
						foreach ($acf_register_field_group as $key => $group) {							
							if ($group['id'] == $acf_obj['ID']){
								
								foreach ($group['fields'] as $field) {									
									
									echo pmai_render_field($field, ( ! empty($options) ) ? $options : array() );

								}
							}
						}
					}					

				}											

			}
			
		?>								
		</div>
	</div>
	<?php			

	exit(json_encode(array('html' => ob_get_clean()))); die;

}

?>