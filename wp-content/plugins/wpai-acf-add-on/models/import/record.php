<?php

class PMAI_Import_Record extends PMAI_Model_Record {		

	/**
	 * Associative array of data which will be automatically available as variables when template is rendered
	 * @var array
	 */
	public $data = array();

	public $parsing_data = array();

	public $articleData = array();

	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) { 
		parent::__construct($data);
		$this->setTable(PMXI_Plugin::getInstance()->getTablePrefix() . 'imports');
	}	
	
	/**
	 * Perform import operation
	 * @param string $xml XML string to import
	 * @param callback[optional] $logger Method where progress messages are submmitted
	 * @return pmai_Import_Record
	 * @chainable
	 */
	public function parse($parsing_data = array()) { //$import, $count, $xml, $logger = NULL, $chunk = false, $xpath_prefix = ""

		$this->parsing_data = $parsing_data;

		add_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // do not perform special filtering for imported content			

		$this->data = array();

		$records = array();

		$this->parsing_data['chunk'] == 1 and $this->parsing_data['logger'] and call_user_func($this->parsing_data['logger'], __('Composing advanced custom fields...', 'pmxi_plugin'));

		$acfs = $this->parsing_data['import']->options['acf'];

		global $acf;
		
		if ( ! empty($acfs) ):

			foreach ($acfs as $id => $status) { if ( ! $status ) continue;

				if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){
					
					if (is_numeric($id)){
						$acf_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => $id, 'post_status' => 'publish'));

						if (!empty($acf_fields)):

							foreach ($acf_fields as $field) {
								$fieldData = (!empty($field->post_content)) ? unserialize($field->post_content) : array();							
								$fieldData['ID'] = $field->ID;
								$fieldData['label'] = $field->post_title;
								$fieldData['key'] = $field->post_name;
								$fieldData['name'] = $field->post_excerpt; 
								$this->data[$fieldData['key']] = $this->parse_field($fieldData, $this->parsing_data['import']->options[ 'fields' ][ $fieldData['key'] ]);
							}

						endif;					
					}
					else{

						$fields = acf_local()->fields;
					
						if ( ! empty($fields) ) {
							foreach ($fields as $key => $field) {
								
								if ($field['parent'] == $id){		

									$fieldData = $field;
								
									$fieldData['ID']    = uniqid();
									$fieldData['label'] = $field['label'];
									$fieldData['key']   = $field['key'];					
									$fieldData['name']   = $field['name'];

									$this->data[$fieldData['key']] = $this->parse_field($fieldData, $this->parsing_data['import']->options[ 'fields' ][ $fieldData['key'] ]);
								}
							}
						}
					}

				}
				else{

					if (is_numeric($id)){
						foreach (get_post_meta($id, '') as $cur_meta_key => $cur_meta_val)
						{	
							if (strpos($cur_meta_key, 'field_') !== 0) continue;

							$field = (!empty($cur_meta_val[0])) ? unserialize($cur_meta_val[0]) : array();

							$field_xpath = (!empty($this->parsing_data['import']->options[ 'fields' ][ $field['key'] ])) ? $this->parsing_data['import']->options[ 'fields' ][ $field['key'] ] : "";															

							$this->data[$field['key']] = $this->parse_field($field, $field_xpath);
											
						}
					}
					else{
						global $acf_register_field_group;

						if ( ! empty($acf_register_field_group) ){

							foreach ($acf_register_field_group as $key => $group) {							
								
								if ($group['id'] == $id){
									
									foreach ($group['fields'] as $field) {			

										$field_xpath = (!empty($this->parsing_data['import']->options[ 'fields' ][ $field['key'] ])) ? $this->parsing_data['import']->options[ 'fields' ][ $field['key'] ] : "";															

										$this->data[$field['key']] = $this->parse_field($field, $field_xpath);																									

									}
								}
							}
						}	
					}

				}
			}
		endif;		

		remove_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // return any filtering rules back if they has been disabled for import procedure					

		return $this->data;
	}

	public function parse_field($field, $CurrentFieldXpath, $fieldPath = "", $xpath_suffix = "", $repeater_count_rows = 0){				

		$cxpath = $this->parsing_data['xpath_prefix'] . $this->parsing_data['import']->xpath . $xpath_suffix;		

		$currentIsMultipleField = (isset($this->parsing_data['import']->options['is_multiple_field_value'][ $field['key'] ])) ? $this->parsing_data['import']->options['is_multiple_field_value'][ $field['key'] ] : false;
		$currentMultipleValue   = (isset($this->parsing_data['import']->options['multiple_value'][ $field['key'] ])) ? $this->parsing_data['import']->options['multiple_value'][ $field['key'] ] : false;

		if ( "" != $fieldPath ){

			$fieldKeys = str_replace(array('[',']'), array(''), str_replace('][', ':', $fieldPath));
			
			foreach (explode(":", $fieldKeys) as $n => $key) {
				$CurrentFieldXpath      = (!$n) ? $this->parsing_data['import']->options['fields'][$key] : $CurrentFieldXpath[$key];
				$currentIsMultipleField = (!$n) ? $this->parsing_data['import']->options['is_multiple_field_value'][$key] : $currentIsMultipleField[$key];
				$currentMultipleValue   = (!$n) ? $this->parsing_data['import']->options['multiple_value'][$key] : $currentMultipleValue[$key];
			}

			$CurrentFieldXpath 		= (!empty($CurrentFieldXpath[ $field['key'] ])) ? $CurrentFieldXpath[ $field['key'] ] : false;
			$currentIsMultipleField = (isset($currentIsMultipleField[ $field['key'] ])) ? $currentIsMultipleField[ $field['key'] ] : false;
			$currentMultipleValue   = (isset($currentMultipleValue[ $field['key'] ])) ? $currentMultipleValue[ $field['key'] ] : false;			
			
		}

		$count_records = ($repeater_count_rows) ? $repeater_count_rows : $this->parsing_data['count'];
		
		$values = array_fill(0, $count_records, "");

		$is_multiple = false;	
		$is_variable = false;
		$is_ignore_empties = false;

		$xml = $this->parsing_data['xml'];		

		$tmp_files	= array();

		if ($field['type'] == 'validated_field' and ! empty($field['sub_field'])){
			$field = $field['sub_field'];
		}

		switch ($field['type']) {
			case 'text':
			case 'textarea':
			case 'number':
			case 'email':
			case 'password':
			case 'wysiwyg':																							
			case 'color_picker':
			case 'message':														
			case 'image':
			case 'file':																						
			case 'gallery':		
			case 'user':			
			case 'limiter':
			case 'wp_wysiwyg':			
			case 'acf_cf7':	
			case 'gravity_forms_field':
			case 'relationship':	
			case 'page_link':
			case 'post_object':
			case 'oembed':
			case 'url':
					if ( "" != $CurrentFieldXpath )
					{
						$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;									
					}
				break;			
			case 'date_picker':
					if ( "" != $CurrentFieldXpath )
					{
						$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;
						$warned = array(); // used to prevent the same notice displaying several times
						foreach ($values as $i => $d) {
							if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
							$time = strtotime($d);
							if (FALSE === $time) {									
								$values[$i] = $d;
							}
							else{ 
								$values[$i] = date('Ymd', $time);
							}
						}
					}					
				break;
			case 'date_time_picker':
					if ( "" != $CurrentFieldXpath )
					{
						$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;
						$warned = array(); // used to prevent the same notice displaying several times
						foreach ($values as $i => $d) {
							if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
							$time = strtotime($d);
							if (FALSE === $time) {									
								$time = time();
							}
							$values[$i] = $time;
						}
					}					
				break;
			case 'google_map':
			case 'location-field':
					if ( "" != $CurrentFieldXpath['address'] )
					{
						$addresses = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['address'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$addresses = array_fill(0, $count_records, "");	
					}
					if ( "" != $CurrentFieldXpath['lat'] ){
						$lat = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['lat'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$lat = array_fill(0, $count_records, "");	
					}
					if ( "" != $CurrentFieldXpath['lng'] ){
						$lng = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['lng'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$lng = array_fill(0, $count_records, "");	
					}
					$values = array(
						'address' => $addresses,
						'lat' => $lat,
						'lng' => $lng
					);
				break;
			case 'paypal_item':
					if ( "" != $CurrentFieldXpath['item_name'] )
					{
						$item_names = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['item_name'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$item_names = array_fill(0, $count_records, "");	
					}
					if ( "" != $CurrentFieldXpath['item_description'] ){
						$item_descriptions = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['item_description'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$item_descriptions = array_fill(0, $count_records, "");	
					}
					if ( "" != $CurrentFieldXpath['price'] ){
						$prices = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath['price'], $file)->parse(); $tmp_files[] = $file;
					}
					else{
						$prices = array_fill(0, $count_records, "");	
					}
					$values = array(
						'item_name' => $item_names,
						'item_description' => $item_descriptions,
						'price' => $prices
					);
				break;
			// field types with possiblity for miltiple values										
			case 'radio':
			case 'true_false':
					
					if ( ! empty($currentIsMultipleField) and "yes" == $currentIsMultipleField){

						if ( ! is_array($currentMultipleValue) ) {
							$values = array_fill(0, $count_records, $currentMultipleValue);									
						}								
						else{
							$values = array();
							foreach ($currentMultipleValue as $single_value) {
								$values[] = array_fill(0, $count_records, $single_value);	
							}									
							$is_multiple = true;
						}
					}
					else{

						if ("" != $CurrentFieldXpath){
							$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;
						}
					}					

				break;			
			case 'checkbox':			
			case 'select':	

					if ( ! empty($currentIsMultipleField) and "yes" == $currentIsMultipleField){

						if ( ! is_array($currentMultipleValue) ) {
							$values = array_fill(0, $count_records, $currentMultipleValue);
						}								
						else{
							
							$values = array_fill(0, $count_records, $currentMultipleValue);

							$is_multiple = true;
						}
					}
					else{
						
						if ( "" != $CurrentFieldXpath ){
							
							if ( empty($field['multiple']) and ( ! in_array($field['type'], array('checkbox')) or ( ! empty($field['field_type']) and ! in_array($field['field_type'], array('checkbox', 'multi_select'))))) {

								$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;

							}
							else {
								
								$values = array();

								$values = XmlImportParser::factory($xml, $cxpath, $CurrentFieldXpath, $file)->parse(); $tmp_files[] = $file;

								foreach ($values as $key => $value) {
									$values[$key] = explode(",", $value);
								}
								
								$is_multiple = true;
							}
						}
					}
				break;
			case 'taxonomy':

					if ( ! empty($currentIsMultipleField) and "yes" == $currentIsMultipleField){

						if ( ! is_array($currentMultipleValue) ) {
							$values = array_fill(0, $count_records, $currentMultipleValue);									
						}								
						else{
							$values = array();
							foreach ($currentMultipleValue as $single_value) {
								$values[] = array_fill(0, $count_records, $single_value);	
							}									
							$is_multiple = true;
						}

					}
					else{
						
						if ( "" != $CurrentFieldXpath ){																					

							$values = array();							

							$is_multiple = 'nesting';

							$tx_name = $field['taxonomy'];
							$taxonomies_hierarchy = json_decode($CurrentFieldXpath);
							foreach ($taxonomies_hierarchy as $k => $taxonomy){	if ("" == $taxonomy->xpath) continue;								
								$txes_raw =  XmlImportParser::factory($xml, $cxpath, str_replace('\'','"',$taxonomy->xpath), $file)->parse(); $tmp_files[] = $file;						
								$warned = array();
								foreach ($txes_raw as $i => $tx_raw) {
									if (empty($taxonomies_hierarchy[$k]->txn_names[$i])) $taxonomies_hierarchy[$k]->txn_names[$i] = array();
									if (empty($values[$tx_name][$i])) $values[$tx_name][$i] = array();
									$count_cats = count($values[$tx_name][$i]);
									
									$delimeted_taxonomies = explode((!empty($taxonomy->delim)) ? $taxonomy->delim : ',', $tx_raw);

									if ('' != $tx_raw) foreach (explode((!empty($taxonomy->delim)) ? $taxonomy->delim : ',', $tx_raw) as $j => $cc) if ('' != $cc) {										
																																		
										$cat = get_term_by('name', trim($cc), $tx_name) or $cat = get_term_by('slug', trim($cc), $tx_name) or ctype_digit($cc) and $cat = get_term_by('id', $cc, $tx_name);
										if (!empty($taxonomy->parent_id)) {																			
											foreach ($taxonomies_hierarchy as $key => $value){
												if ($value->item_id == $taxonomy->parent_id and !empty($value->txn_names[$i])){													
													foreach ($value->txn_names[$i] as $parent) {	
														//if (!$j or !$taxonomy->auto_nested){																																																																
															$values[$tx_name][$i][] = array(
																'name' => trim($cc),
																'parent' => $parent,
																'assign' => 1 //$taxonomy->assign
															);
														/*}
														elseif ($taxonomy->auto_nested){
															$values[$tx_name][$i][] = array(
																'name' => trim($cc),
																'parent' => (!empty($delimeted_taxonomies[$j - 1])) ? trim($delimeted_taxonomies[$j - 1]) : false,
																'assign' => $taxonomy->assign
															);
														}*/																	
													}											
												}
											}
											
										}
										else {	
											//if (!$j or !$taxonomy->auto_nested){
												$values[$tx_name][$i][] = array(
													'name' => trim($cc),
													'parent' => false,
													'assign' => 1 //$taxonomy->assign
												);
											/*}
											elseif ($taxonomy->auto_nested) {
												$values[$tx_name][$i][] = array(
													'name' => trim($cc),
													'parent' => (!empty($delimeted_taxonomies[$j - 1])) ? trim($delimeted_taxonomies[$j - 1]) : false,
													'assign' => $taxonomy->assign
												);
											}*/
										}								
									}
									if ($count_cats < count($values[$tx_name][$i])) $taxonomies_hierarchy[$k]->txn_names[$i][] = $values[$tx_name][$i][count($values[$tx_name][$i]) - 1];
								}
							}								
						}
					}
				break;
			case 'repeater':

					if ( ! empty($CurrentFieldXpath['rows']) and count($CurrentFieldXpath['rows']) > 1){

						global $acf;	
														
						$values = array();

						if ( "yes" == $CurrentFieldXpath['is_variable'] and "" != $CurrentFieldXpath['foreach'] ){		

							$is_variable = 'xml';							

							for ($k = 0; $k < $count_records; $k++) { 
																								
								$base_xpath = '[' . ( $k + 1 ) . ']/'.  ltrim(trim($CurrentFieldXpath['foreach'],'{}!'), '/');																							
								$repeater_rows = XmlImportParser::factory($xml, $cxpath . $base_xpath, "{.}", $file)->parse(); $tmp_files[] = $file;								
								
								foreach ($CurrentFieldXpath['rows'] as $key => $row_fields) 
								{ 
									if ($key == 'ROWNUMBER') continue;																		

									$row_array = array();

									if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

										$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => $field['ID'], 'post_status' => 'publish'));

										if (!empty($sub_fields)):
											foreach ($sub_fields as $n => $sub_field)
											{							
												$sub_fieldData = (!empty($sub_field->post_content)) ? unserialize($sub_field->post_content) : array();			
												$sub_fieldData['ID'] = $sub_field->ID;
												$sub_fieldData['label'] = $sub_field->post_title;
												$sub_fieldData['key'] = $sub_field->post_name;
												$sub_fieldData['name'] = $sub_field->post_excerpt;									
												
												$row_array[$sub_fieldData['key']] = $this->parse_field(
														$sub_fieldData, 
														$row_fields[$sub_fieldData['key']], 
														$fieldPath . "[" . $field['key'] . "][rows][" . $key . "]",  
														((strpos($row_fields[$sub_fieldData['key']], "!") === 0) ? "" : ( (strpos($CurrentFieldXpath['foreach'], "!") === 0) ? $base_xpath : $xpath_suffix . $base_xpath)), 
														count($repeater_rows),
														true
													); 

											}
										endif;																																
									}
									else{

										foreach ($field['sub_fields'] as $n => $sub_field)
										{							
									//		if ( in_array($sub_field['type'], array('repeater', 'flexible_content')) ) $base_xpath = "";										
											$row_array[$sub_field['key']] = $this->parse_field($sub_field, $row_fields[$sub_field['key']], $fieldPath . "[" . $field['key'] . "][rows][" . $key . "]",  ((strpos($row_fields[$sub_field['key']], "!") === 0) ? "" : ( (strpos($CurrentFieldXpath['foreach'], "!") === 0) ? $base_xpath : $xpath_suffix . $base_xpath)), count($repeater_rows)); 

										}

									}																															
									
									$values[] = array(
										'countRows' => count($repeater_rows),
										'vals' => $row_array
									);

									// stop parsing after one repeater row
									break;
								}
							}																					
						}
						else {

							/*if ( "csv" == $CurrentFieldXpath['is_variable'] ){

								foreach ($CurrentFieldXpath['rows'] as $key => $row_fields) 
								{ 
									if ($key == 'ROWNUMBER') continue;	

									if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

										$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => $field['ID'], 'post_status' => 'publish'));

										if (!empty($sub_fields)):
											foreach ($sub_fields as $n => $sub_field)
											{			

												$repeater_rows = XmlImportParser::factory($xml, $cxpath, $row_fields[$sub_fieldData['key']], $file)->parse(); $tmp_files[] = $file;		

											}
										endif;
									}

								}

							}
							else{*/
								
								if ( "csv" == $CurrentFieldXpath['is_variable'] ) $is_variable = $CurrentFieldXpath['separator'];

								if ($CurrentFieldXpath['is_ignore_empties']) $is_ignore_empties = true;

								foreach ($CurrentFieldXpath['rows'] as $key => $row_fields) 
								{ 
									if ($key == 'ROWNUMBER') continue;									

									$row_array = array();

									if ($acf and version_compare($acf->settings['version'], '5.0.0') >= 0){

										$sub_fields = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf-field', 'post_parent' => $field['ID'], 'post_status' => 'publish'));

										if (!empty($sub_fields)):
											foreach ($sub_fields as $n => $sub_field)
											{			
												$sub_fieldData = (!empty($sub_field->post_content)) ? unserialize($sub_field->post_content) : array();			
												$sub_fieldData['ID'] = $sub_field->ID;
												$sub_fieldData['label'] = $sub_field->post_title;
												$sub_fieldData['key'] = $sub_field->post_name;				
												$sub_fieldData['name'] = $sub_field->post_excerpt;				
												$row_array[$sub_fieldData['key']] = $this->parse_field($sub_fieldData, $row_fields[$sub_fieldData['key']], $fieldPath . "[" . $field['key'] . "][rows][" . $key . "]"); 
											}
										endif;							
									}
									else{

										foreach ($field['sub_fields'] as $n => $sub_field)
										{							
											$row_array[$sub_field['key']] = $this->parse_field($sub_field, $row_fields[$sub_field['key']], $fieldPath . "[" . $field['key'] . "][rows][" . $key . "]"); 
										}
										
									}							

									$values[] = $row_array;
								}
							//}
						}
					}

				break;
			case 'flexible_content':

					if ( ! empty($CurrentFieldXpath['layouts']) and count($CurrentFieldXpath['layouts']) > 1 ){
														
						$values = array();																	

						foreach ($CurrentFieldXpath['layouts'] as $key => $layout_fields) 
						{ 
							if ($key == 'ROWNUMBER') continue;									

							$row_array = array();

							$current_field = false;

							foreach ($field['layouts'] as $layout) {
								if ($layout['name'] == $layout_fields['acf_fc_layout']){
									$current_field = $layout;
									break;
								}
							}

							$row_array['acf_fc_layout'] = $layout_fields['acf_fc_layout'];

							if ( ! empty($current_field['sub_fields']) and is_array($current_field['sub_fields'])) 
							{
								foreach ($current_field['sub_fields'] as $n => $sub_field)
								{							
									
									$row_array['fields'][$sub_field['key']] = $this->parse_field($sub_field, $layout_fields[$sub_field['key']], $fieldPath . "[" . $field['key'] . "][layouts][". $key ."]"); 
																	
								}							
							}
							$values[] = $row_array;
						}
					}

				break;
			default:
				# code...
				break;
		}

		foreach ( (array) $tmp_files as $file) { // remove all temporary files created
			@unlink($file);
		}	

		return array(
			'type'   => $field['type'],
			'name'   => $field['name'],
			'multiple' => $field['multiple'],
			'values' => $values,
			'is_multiple' => $is_multiple,
			'is_variable' => $is_variable,
			'is_ignore_empties' => $is_ignore_empties,
			'xpath' => $CurrentFieldXpath
		);

	}

	public function import($importData = array()){ //$pid, $i, $import, $articleData, $xml, $is_cron = false, $xpath_prefix = ""

		extract($importData);

		$logger and call_user_func($logger, __('<strong>ACF ADD-ON:</strong>', 'pmxi_plugin'));

		$this->articleData = $articleData;

		$cxpath = $xpath_prefix . $import->xpath;						

		if (!empty($this->data)){
			foreach ((array) $this->data as $key => $field) {

				$this->import_field($pid, $i, $key, $field);
							
			}
		}

	}

	public function import_field($pid, $i, $key, $field, $fieldContainerName = ""){	

		$this->parsing_data['logger'] and call_user_func($this->parsing_data['logger'], sprintf(__('- Importing field `%s`', 'pmxi_plugin'), $fieldContainerName . $field['name']));	

		// If update is not allowed
		if ( ! pmai_is_acf_update_allowed( $fieldContainerName . $field['name'], $this->parsing_data['import']->options ) ){ 
			$this->parsing_data['logger'] and call_user_func($this->parsing_data['logger'], sprintf(__('- Field `%s` is skipped attempted to import options', 'pmxi_plugin'), $fieldContainerName . $field['name']));
			return false;
		}

		update_post_meta($pid, "_" . $fieldContainerName . $field['name'], $key);

		switch ($field['type']) {
			case 'text':
			case 'textarea':
			case 'number':
			case 'email':
			case 'password':
			case 'wysiwyg':																							
			case 'color_picker':
			case 'message':																										
			case 'date_picker':
			case 'limiter':
			case 'wp_wysiwyg':
			case 'date_time_picker':
			case 'oembed':
			case 'url':
					update_post_meta($pid, $fieldContainerName . $field['name'], $field['values'][$i]);			
					//$this->parsing_data['logger'] and call_user_func($this->parsing_data['logger'], sprintf(__('- Field `%s` updated with value `%s`', 'pmxi_plugin'), $fieldContainerName . $field['name'], $field['values'][$i]));		
				break;		
			case 'google_map':										
					update_post_meta($pid, $fieldContainerName . $field['name'], array(
						'address' => $field['values']['address'][$i],
						'lat' => $field['values']['lat'][$i],
						'lng' => $field['values']['lng'][$i]
					));													
				break;
			case 'paypal_item':										
					update_post_meta($pid, $fieldContainerName . $field['name'], array(
						'item_name' => $field['values']['item_name'][$i],
						'item_description' => $field['values']['item_description'][$i],
						'price' => $field['values']['price'][$i]
					));													
				break;
			case 'location-field':
					update_post_meta($pid, $fieldContainerName . $field['name'], $field['values']['address'][$i] . "|" . $field['values']['lat'][$i] . "," . $field['values']['lng'][$i]);	
				break;
			case 'gallery':
					$imgs = explode(",", $field['values'][$i]);
					$gallery_ids = array();
					foreach ((array) $imgs as $url) {
						if ("" != $url and $attid = $this->import_image($url, $pid, $this->parsing_data['logger'])) $gallery_ids[] = $attid;
					}
					update_post_meta($pid, $fieldContainerName . $field['name'], $gallery_ids);
				break;		
			case 'user':				
					if (strpos($field['values'][$i], ",")){
						$users = array_map('trim', explode(",", $field['values'][$i]));
						if ( ! empty($users)):
							foreach ($users as $key => $author) {
								$user = get_user_by('login', $author) or $user = get_user_by('slug', $author) or $user = get_user_by('email', $author) or ctype_digit($author) and $user = get_user_by('id', $author);
								$users[$key] = (!empty($user)) ? $user->ID : "";
							}
						endif;
						update_post_meta($pid, $fieldContainerName . $field['name'], $users);
					}
					else{ 
						$author = $field['values'][$i];
						$user = get_user_by('login', $author) or $user = get_user_by('slug', $author) or $user = get_user_by('email', $author) or ctype_digit($author) and $user = get_user_by('id', $author);
						update_post_meta($pid, $fieldContainerName . $field['name'], (!empty($user)) ? $user->ID : "");
					}
				break;
			case 'image':
					if ("" != $field['values'][$i] and $attid = $this->import_image($field['values'][$i], $pid, $this->parsing_data['logger'])) 
						update_post_meta($pid, $fieldContainerName . $field['name'], $attid);																
				break;
			case 'file':
					if ("" != $field['values'][$i] and $attid = $this->import_file($field['values'][$i], $pid, $this->parsing_data['logger'], $this->parsing_data['import']->options['is_fast_mode'])) 
						update_post_meta($pid, $fieldContainerName . $field['name'], $attid);					
				break;			
			case 'checkbox':				
			case 'select':
			case 'radio':
			case 'true_false':

					if ($field['is_multiple'])
					{						
						update_post_meta($pid, $fieldContainerName . $field['name'], (!empty($field['values'][$i]) and is_array($field['values'][$i])) ? $field['values'][$i] : array());
					}
					else
					{						
						update_post_meta($pid, $fieldContainerName . $field['name'], $field['values'][$i]);
					}									

				break;
			case 'taxonomy':
					
					if ( $field['is_multiple'] !== true and $field['is_multiple'] == 'nesting' )
					{

						if (!empty($field['values'])){	

							foreach ($field['values'] as $tx_name => $txes) {														
								
								$assign_taxes = array();									

								// create term if not exists
								if (!empty($txes[$i])):
									foreach ($txes[$i] as $key => $single_tax) {
										if (is_array($single_tax)){																														

											$parent_id = (!empty($single_tax['parent'])) ? pmxi_recursion_taxes($single_tax['parent'], $tx_name, $txes[$i], $key) : '';
											
											$term = is_exists_term($tx_name, $single_tax['name'], (int)$parent_id);		
											
											if ( empty($term) and !is_wp_error($term) ){
												$term_attr = array('parent'=> (!empty($parent_id)) ? $parent_id : 0);
												$term = wp_insert_term(
													$single_tax['name'], // the term 
												  	$tx_name, // the taxonomy
												  	$term_attr
												);
											}
											
											if ( is_wp_error($term) ){									
												
											}
											elseif (!empty($term)) {
												$cat_id = $term['term_id'];
												if ($cat_id and $single_tax['assign']) 
												{																									
													if (!in_array($cat_id, $assign_taxes)) $assign_taxes[] = $cat_id;		
												}									
											}									
										}
									}				
								endif;			

								if ( ! empty($assign_taxes) ) update_post_meta($pid, $fieldContainerName . $field['name'], $assign_taxes);																	
									
							}							
						}
					} 
					elseif ($field['is_multiple'])
					{
						$mult_values = array();
						foreach ($field['values'] as $number => $values) {
							$mult_values[] = trim($values[$i]);	
						}						
						update_post_meta($pid, $fieldContainerName . $field['name'], $mult_values);
					}
					else
					{						
						update_post_meta($pid, $fieldContainerName . $field['name'], $field['values'][$i]);
					}
				break;
			case 'page_link':
				if ( "" != $field['values'][$i] ){
						$post_ids = array();						
						$entries = explode(",", $field['values'][$i]);
						if (!empty($entries) and is_array($entries)){
							foreach ($entries as $ev) {															
								$args = array(
								  'name' => $ev,
								  'post_type' => 'page',								  
								  'numberposts' => 1
								);
								//$the_query = new WP_Query( $args );
								$my_posts = get_posts($args);
								if ( $my_posts ) {
								  	$post_ids[] = get_permalink($my_posts[0]->ID);
								}
								elseif (ctype_digit($ev)){
									$my_post = get_post($ev);
									if ($my_post)
										$post_ids[] = get_permalink($my_post->ID);
								}			
								//wp_reset_postdata();					
							}
						}
						if (!empty($post_ids)){
							if ($field['multiple']){
								update_post_meta($pid, $fieldContainerName . $field['name'], $post_ids);
							}
							else{
								update_post_meta($pid, $fieldContainerName . $field['name'], array_shift($post_ids));
							}
						}
						else{
							update_post_meta($pid, $fieldContainerName . $field['name'], '');	
						}
					}
					else{
						update_post_meta($pid, $fieldContainerName . $field['name'], '');
					}
				break;
			case 'post_object':					
					if ( "" != $field['values'][$i] ){
						$post_ids = array();						
						$entries = explode(",", $field['values'][$i]);
						if (!empty($entries) and is_array($entries)){
							foreach ($entries as $ev) {
								$args = array(
								  'name' => $ev,
								  'post_type' => 'post',								  
								  'numberposts' => 1
								);
								$my_posts = get_posts($args);
								if ( $my_posts ) {
								  	$post_ids[] = $my_posts[0]->ID;
								}
								elseif (ctype_digit($ev)){
									$post_ids[] = $ev;
								}								
							}
						}
						
						if (!empty($post_ids)){
							if ($field['multiple']){
								update_post_meta($pid, $fieldContainerName . $field['name'], $post_ids);
							}
							else{
								update_post_meta($pid, $fieldContainerName . $field['name'], array_shift($post_ids));
							}
						}
						else{
							update_post_meta($pid, $fieldContainerName . $field['name'], '');	
						}
					}
					else{
						update_post_meta($pid, $fieldContainerName . $field['name'], '');
					}					

				break;
			case 'relationship':
					if ( "" != $field['values'][$i] ){
						$post_ids = array();						
						$entries = explode(",", $field['values'][$i]);
						if (!empty($entries) and is_array($entries)){
							foreach ($entries as $ev) {
								$args = array(
								  'name' => $ev,
								  'post_type' => 'any',
								  'numberposts' => 1
								);
								$my_posts = get_posts($args);
								if ( $my_posts ) {
								  	$post_ids[] = $my_posts[0]->ID;
								}
								elseif (ctype_digit($ev)){
									$post_ids[] = $ev;
								}								
							}
						}
						if (!empty($post_ids)){
							
							update_post_meta($pid, $fieldContainerName . $field['name'], $post_ids);
							
						}
						else{
							update_post_meta($pid, $fieldContainerName . $field['name'], '');	
						}
					}
					else{
						update_post_meta($pid, $fieldContainerName . $field['name'], '');
					}	
				break;
			case 'gravity_forms_field':
			case 'acf_cf7':
					if ($field['is_multiple'])
					{
						update_post_meta($pid, $fieldContainerName . $field['name'], explode(",", $field['values'][$i]));
					}
					else{
						update_post_meta($pid, $fieldContainerName . $field['name'], $field['values'][$i]);
					}
				break;
			case 'repeater':

					if ( ! empty($field['values']) ):						

						if ( $field['is_variable'] == 'xml' ){ // is variable repeater mode enabled
							
							for ($k = 0; $k < $field['values'][$i]['countRows']; $k++) { 
								foreach ($field['values'][$i]['vals'] as $sub_field_key => $sub_field)
								{
									$this->import_field($pid, $k, $sub_field_key, $sub_field, $fieldContainerName . $field['name'] . "_" . $k . "_");		
								}	
							}							
							update_post_meta($pid, $fieldContainerName . $field['name'], $field['values'][$i]['countRows']);

						}
						else{ // is fixed repeater mode enabled

							$countRows = 0;

							foreach ($field['values'] as $row_number => $row)
							{
								if ( ! empty($row)){
									$countRows++;

									$is_row_import_allowed = true;

									if ( $field['is_ignore_empties'] and $field['is_variable'] === false){
										$is_row_import_allowed = false;
										foreach ($row as $sub_field_key_check => $sub_field_check){
											
											if ( ! empty($sub_field_check['xpath']) and ! empty( $sub_field_check['values'][$i] ) and ! in_array($sub_field_check['type'], array('repeater')) )
											{										
												$is_row_import_allowed = true;
												break;
											}
										}											
									}

									if ($is_row_import_allowed) {

										if ( $field['is_variable'] !== false and $field['is_variable'] != '' ){											
											$countCSVrows = 0;
											foreach ($row as $sub_field_key => $sub_field){
												$entries = explode($field['is_variable'], $sub_field['values'][$i]);
												if (count($entries) > $countCSVrows)
													$countCSVrows = count($entries);
											}	

											for ( $k=0; $k < $countCSVrows; $k++) { 
												foreach ($row as $sub_field_key => $sub_field){
													$entries = explode($field['is_variable'], $sub_field['values'][$i]);
													$sub_field['values'][$i] = (empty($entries[$k])) ? '' : $entries[$k];
													$this->import_field($pid, $i, $sub_field_key, $sub_field, $fieldContainerName . $field['name'] . "_" . $k . "_");		
												}
											}

											$countRows = $countCSVrows;
											
										}
										else{

											foreach ($row as $sub_field_key => $sub_field){																													
																					
												$this->import_field($pid, $i, $sub_field_key, $sub_field, $fieldContainerName . $field['name'] . "_" . $row_number . "_");																				
											}
										}	

									}
									else
										$countRows--;

								}
							}
							update_post_meta($pid, $fieldContainerName . $field['name'], $countRows);
						}

					endif;

				break;
			case 'flexible_content':

				if ( ! empty($field['values']) ):						

					$layouts = array();				

					foreach ($field['values'] as $layout_number => $layout)
					{
						if ( ! empty($layout['fields'])){
							$layouts[] = $layout['acf_fc_layout'];
							foreach ($layout['fields'] as $sub_field_key => $sub_field) 			
								$this->import_field($pid, $i, $sub_field_key, $sub_field, $fieldContainerName . $field['name'] . "_" . $layout_number . "_");														
						}
					}
					update_post_meta($pid, $fieldContainerName . $field['name'], $layouts);

				endif;

				break;
			default:
				# code...
				break;
		}	

		$v = get_post_meta($pid, $fieldContainerName . $field['name'], true);
					
		$this->parsing_data['logger'] and call_user_func($this->parsing_data['logger'], sprintf(__('- Field `%s` updated with value `%s`', 'pmxi_plugin'), $fieldContainerName . $field['name'], maybe_serialize($v)));

	}

	public function import_image($img_url, $pid, $logger){
		
		$uploads = wp_upload_dir();

		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		$url = str_replace(" ", "%20", trim($img_url));
		$bn = preg_replace('/[\\?|&].*/', '', basename($url));
		
		$img_ext = pmxi_getExtensionFromStr($url);									
		$default_extension = pmxi_getExtension($bn);																									

		if ($img_ext == "") 										
			$img_ext = pmxi_get_remote_image_ext($url);																				

		// generate local file name
		$image_name = urldecode(sanitize_file_name((($img_ext) ? str_replace("." . $default_extension, "", $bn) : $bn))) . (("" != $img_ext) ? '.' . $img_ext : '');																	
		
		// if wizard store image data to custom field									
		$create_image = false;
		$download_image = true;
	
		$image_filename = wp_unique_filename($uploads['path'], $image_name);
		$image_filepath = $uploads['path'] . '/' . $image_filename;

		if ($download_image){											

			// do not download images
			if ( ! $this->parsing_data['import']->options['download_images'] ){ 		

				$image_filename = $image_name;
				$image_filepath = $uploads['path'] . '/' . $image_filename;																							
				
				$existing_attachment = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->prefix ."posts WHERE guid = '%s'", $uploads['url'] . '/' . $image_filename ) );
				
				if ( ! empty($existing_attachment->ID) ){

					$download_image = false;	
					$create_image = false;	
					
					do_action( 'pmxi_gallery_image', $pid, $existing_attachment->ID, $image_filepath); 

					return $existing_attachment->ID;

				}
				else{													
					
					if ( @file_exists($image_filepath) ){
						$download_image = false;																				
						if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
							$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as ACF image', 'pmxi_plugin'), $image_filepath));
							@unlink($image_filepath);
						} else {
							$create_image = true;											
						}
					}
				}
			}	

			if ($download_image){
				
				$request = get_file_curl($url, $image_filepath);

				if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
					@unlink($image_filepath); // delete file since failed upload may result in empty file created
				} elseif( ($image_info = @getimagesize($image_filepath)) and in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
					$create_image = true;											
				}												
				
				if ( ! $create_image ){

					$url = str_replace(" ", "%20", trim(pmxi_convert_encoding($img_url)));
					
					$request = get_file_curl($url, $image_filepath);

					if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
						$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s cannot be saved locally as %s', 'pmxi_plugin'), $url, $image_filepath));						
						@unlink($image_filepath); // delete file since failed upload may result in empty file created										
					} elseif( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
						$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'pmxi_plugin'), $url));						
						@unlink($image_filepath);
					} else {
						$create_image = true;											
					}
				}
			}
		}		

		if ($create_image){

			$attachment = array(
				'post_mime_type' => image_type_to_mime_type($image_info[2]),
				'guid' => $uploads['url'] . '/' . $image_filename,
				'post_title' => $image_filename,
				'post_content' => '',
				'post_author' => $this->articleData['post_author'],
			);
			if (($image_meta = wp_read_image_metadata($image_filepath))) {
				if (trim($image_meta['title']) && ! is_numeric(sanitize_title($image_meta['title'])))
					$attachment['post_title'] = $image_meta['title'];
				if (trim($image_meta['caption']))
					$attachment['post_content'] = $image_meta['caption'];
			}

			$attid = ($this->parsing_data['import']->options['is_fast_mode']) ? pmxi_insert_attachment($attachment, $image_filepath, $pid) : wp_insert_attachment($attachment, $image_filepath, $pid);										

			if (is_wp_error($attid)) {
				$logger and call_user_func($logger, __('- <b>WARNING</b>', 'pmxi_plugin') . ': ' . $attid->get_error_message());				
			} else {
				
				wp_update_attachment_metadata($attid, wp_generate_attachment_metadata($attid, $image_filepath));																															

				do_action( 'pmxi_gallery_image', $pid, $attid, $image_filepath); 

				return $attid;
			}
		}

		return false;

	}

	public function import_file($atch_url, $pid, $logger, $fast = false){
		
		$uploads = wp_upload_dir();
		$attachment_filename = wp_unique_filename($uploads['path'], basename(parse_url(trim($atch_url), PHP_URL_PATH)));												
		$attachment_filepath = $uploads['path'] . '/' . sanitize_file_name($attachment_filename);
										
		if ( ! get_file_curl(trim($atch_url), $attachment_filepath) and ! @file_put_contents($attachment_filepath, @file_get_contents(trim($atch_url)))) {												
			$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Attachment file %s cannot be saved locally as %s', 'pmxi_plugin'), trim($atch_url), $attachment_filepath));			
			unlink($attachment_filepath); // delete file since failed upload may result in empty file created												
		} elseif( ! $wp_filetype = wp_check_filetype(basename($attachment_filename), null )) {
			$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Can\'t detect attachment file type %s', 'pmxi_plugin'), trim($atch_url)));			
		} else {
			
			$attachment_data = array(
			    'guid' => $uploads['baseurl'] . '/' . _wp_relative_upload_path( $attachment_filepath ), 
			    'post_mime_type' => $wp_filetype['type'],
			    'post_title' => preg_replace('/\.[^.]+$/', '', basename($attachment_filepath)),
			    'post_content' => '',
			    'post_status' => 'inherit'
			);
			$attach_id = ($fast) ? pmxi_insert_attachment( $attachment_data, $attachment_filepath, $pid ) : wp_insert_attachment( $attachment_data, $attachment_filepath, $pid );												

			if (is_wp_error($attach_id)) {
				$logger and call_user_func($logger, __('- <b>WARNING</b>', 'pmxi_plugin') . ': ' . $pid->get_error_message());				
			} else {
				// you must first include the image.php file
				// for the function wp_generate_attachment_metadata() to work
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				
				do_action( 'pmxi_attachment_uploaded', $pid, $attach_id, $attachment_filepath); 
				wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $attachment_filepath));											
				return $attach_id;
			}										
		}	
		return false;
	}

	public function _filter_has_cap_unfiltered_html($caps)
	{
		$caps['unfiltered_html'] = true;
		return $caps;
	}
	
	public function filtering($var){
		return ("" == $var) ? false : true;
	}		
}
