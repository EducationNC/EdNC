<?php

final class XmlExportCpt
{
	/**
	 * Singletone instance
	 * @var XmlExportCpt
	 */
	protected static $instance;

	/**
	 * Return singletone instance
	 * @return XmlExportCpt
	 */
	static public function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}	

	private function __construct(){}

	public static function prepare_data( $entry, $xmlWriter = false, &$acfs, &$woo, &$woo_order, $implode_delimiter, $preview, $is_item_data = false, $subID = false )
	{
		$article = array();	

		// associate exported post with import
		if ( ! $is_item_data and wp_all_export_is_compatible() and XmlExportEngine::$exportOptions['is_generate_import'] and XmlExportEngine::$exportOptions['import_id'])
		{				
			$postRecord = new PMXI_Post_Record();
			$postRecord->clear();
			$postRecord->getBy(array(
				'post_id' => $entry->ID,
				'import_id' => XmlExportEngine::$exportOptions['import_id'],
			));

			if ($postRecord->isEmpty()){
				$postRecord->set(array(
					'post_id' => $entry->ID,
					'import_id' => XmlExportEngine::$exportOptions['import_id'],
					'unique_key' => $entry->ID,
					'product_key' => $entry->ID						
				))->save();				
			}
			unset($postRecord);
		}

		foreach (XmlExportEngine::$exportOptions['ids'] as $ID => $value) 
		{		
			$pType = $entry->post_type;

			if ( $is_item_data and $subID != $ID ) continue;

			// skip shop order items data
			if ( $pType == "shop_order" and strpos(XmlExportEngine::$exportOptions['cc_label'][$ID], "item_data__") !== false and ! $is_item_data ) continue;

			$fieldName    = XmlExportEngine::$exportOptions['cc_name'][$ID];
			$fieldValue   = str_replace("item_data__", "", XmlExportEngine::$exportOptions['cc_value'][$ID]);
			$fieldLabel   = str_replace("item_data__", "", XmlExportEngine::$exportOptions['cc_label'][$ID]);
			$fieldSql     = XmlExportEngine::$exportOptions['cc_sql'][$ID];
			$fieldPhp     = XmlExportEngine::$exportOptions['cc_php'][$ID];
			$fieldCode    = XmlExportEngine::$exportOptions['cc_code'][$ID];
			$fieldType    = XmlExportEngine::$exportOptions['cc_type'][$ID];
			$fieldOptions = XmlExportEngine::$exportOptions['cc_options'][$ID];
			$fieldSettings = empty(XmlExportEngine::$exportOptions['cc_settings'][$ID]) ? $fieldOptions : XmlExportEngine::$exportOptions['cc_settings'][$ID];

			if ( empty($fieldName) or empty($fieldType) or ! is_numeric($ID) ) continue;
			
			$element_name = ( ! empty($fieldName) ) ? $fieldName : 'untitled_' . $ID;
			$element_name_ns = '';

			if (XmlExportEngine::$exportOptions['export_to'] == 'xml')
			{				
				$element_name = ( ! empty($fieldName) ) ? preg_replace('/[^a-z0-9_:-]/i', '', $fieldName) : 'untitled_' . $ID;				

				if (strpos($element_name, ":") !== false)
				{
					$element_name_parts = explode(":", $element_name);
					$element_name_ns = (empty($element_name_parts[0])) ? '' : $element_name_parts[0];
					$element_name = (empty($element_name_parts[1])) ? 'untitled_' . $ID : preg_replace('/[^a-z0-9_-]/i', '', $element_name_parts[1]);							
				}
			} 

			$fieldSnipped = ( ! empty($fieldPhp ) and ! empty($fieldCode)) ? $fieldCode : false;			
			
			switch ($fieldType)
			{
				case 'id':									
					if ($element_name == 'ID') $element_name = 'id';
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_id', pmxe_filter($entry->ID, $fieldSnipped), $entry->ID) );
					break;
				case 'permalink':
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_guid', pmxe_filter(get_permalink(), $fieldSnipped), $entry->ID) );					
					break;
				case 'post_type':							
					if ($entry->post_type == 'product_variation') $pType = 'product';					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_type', pmxe_filter($pType, $fieldSnipped), $entry->ID) );
					break;					
				case 'title':								
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_title', pmxe_filter($entry->post_title, $fieldSnipped), $entry->ID) );					
					break;						
				case 'content':
					$val = apply_filters('pmxe_post_content', pmxe_filter($entry->post_content, $fieldSnipped), $entry->ID);					
					wp_all_export_write_article( $article, $element_name, ($preview) ? trim(preg_replace('~[\r\n]+~', ' ', htmlspecialchars($val))) : $val );
					break;

				// Media Attachments		
				case 'attachments':
				case 'attachment_id':
				case 'attachment_url':
				case 'attachment_filename':
				case 'attachment_path':
				case 'attachment_title':
				case 'attachment_caption':
				case 'attachment_description':
				case 'attachment_alt':

					XmlExportMediaGallery::getInstance($entry->ID);

					$attachment_data = XmlExportMediaGallery::get_attachments($fieldType);								

					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_' . $fieldType, pmxe_filter( implode($implode_delimiter, $attachment_data), $fieldSnipped), $entry->ID) );

					break;

				// Media Images	
				case 'media':
				case 'image_id':
				case 'image_url':				
				case 'image_filename':
				case 'image_path':
				case 'image_title':
				case 'image_caption':
				case 'image_description':
				case 'image_alt':			

					$field_options = json_decode($fieldOptions, true);

					XmlExportMediaGallery::getInstance($entry->ID);

					$images_data = XmlExportMediaGallery::get_images($fieldType, $field_options);			

					$images_separator = empty($field_options['image_separator']) ? $implode_delimiter : $field_options['image_separator'];			
					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_' . $fieldType, pmxe_filter( implode($images_separator, $images_data), $fieldSnipped), $entry->ID) );

					break;		

				case 'date':
					
					if ( ! empty($fieldSettings))
					{
						switch ($fieldSettings) 
						{
							case 'unix':
								$post_date = get_post_time('U', true, $entry->ID);
								break;									
							default:
								$post_date = date($fieldSettings, get_post_time('U', true, $entry->ID));
								break;
						}														
					}
					else
					{
						$post_date = date("Ymd", get_post_time('U', true, $entry->ID));
					}
					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_date', pmxe_filter($post_date, $fieldSnipped), $entry->ID) );
					break;		

				case 'parent':
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_parent', pmxe_filter($entry->post_parent, $fieldSnipped), $entry->ID) );
					break;
				case 'comment_status':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_comment_status', pmxe_filter($entry->comment_status, $fieldSnipped), $entry->ID) );
					break;
				case 'ping_status':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_ping_status', pmxe_filter($entry->ping_status, $fieldSnipped), $entry->ID) );
					break;
				case 'template':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_template', pmxe_filter(get_post_meta($entry->ID, '_wp_page_template', true), $fieldSnipped), $entry->ID) );
					break;
				case 'order':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_menu_order', pmxe_filter($entry->menu_order, $fieldSnipped), $entry->ID) );
					break;
				case 'status':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_status', pmxe_filter($entry->post_status, $fieldSnipped), $entry->ID) );
					break;
				case 'format':				
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_format', pmxe_filter(get_post_format($entry->ID), $fieldSnipped), $entry->ID) );
					break;
				case 'author':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_author', pmxe_filter($entry->post_author, $fieldSnipped), $entry->ID) );
					break;
				case 'slug':					
					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_slug', pmxe_filter($entry->post_name, $fieldSnipped), $entry->ID) );
					break;
				case 'excerpt':
					$val = apply_filters('pmxe_post_excerpt', pmxe_filter($entry->post_excerpt, $fieldSnipped), $entry->ID);					
					wp_all_export_write_article( $article, $element_name, ($preview) ? trim(preg_replace('~[\r\n]+~', ' ', htmlspecialchars($val))) : $val );
					break;
				case 'cf':
					if ( ! empty($fieldValue) )
					{																		
						$val = "";

						$cur_meta_values = get_post_meta($entry->ID, $fieldValue);	

						if ( ! empty($cur_meta_values) and is_array($cur_meta_values))
						{
							foreach ($cur_meta_values as $key => $cur_meta_value) 
							{
								if (empty($val))
								{
									$val = apply_filters('pmxe_custom_field', pmxe_filter(maybe_serialize($cur_meta_value), $fieldSnipped), $fieldValue, $entry->ID);																	
								}
								else
								{
									$val = apply_filters('pmxe_custom_field', pmxe_filter($val . $implode_delimiter . maybe_serialize($cur_meta_value), $fieldSnipped), $fieldValue, $entry->ID);
								}
							}
							wp_all_export_write_article( $article, $element_name, $val );
						}		

						if ( empty($cur_meta_values))
						{
							if ( empty($article[$element_name]))
							{								
								wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_custom_field', pmxe_filter('', $fieldSnipped), $fieldValue, $entry->ID) );
							}					
						}																																																																
					}	
					break;

				case 'acf':							

					if ( ! empty($fieldLabel) and class_exists( 'acf' ) ){		

						global $acf;

						$field_options = unserialize($fieldOptions);

						if (XmlExportEngine::$exportOptions['export_to'] == 'csv')
						{
							switch ($field_options['type']) {
								case 'textarea':
								case 'oembed':
								case 'wysiwyg':
								case 'wp_wysiwyg':
								case 'date_time_picker':
								case 'date_picker':
									
									$field_value = get_field($fieldLabel, $entry->ID, false);

									break;
								
								default:
									
									$field_value = get_field($fieldLabel, $entry->ID);								

									break;
							}
						}		
						else
						{
							$field_value = get_field($fieldLabel, $entry->ID);	
						}				
						
						XmlExportACF::export_acf_field(
							$field_value, 
							XmlExportEngine::$exportOptions, 
							$ID, 
							$entry->ID, 
							$article, 
							$xmlWriter, 
							$acfs, 
							$element_name, 
							$element_name_ns, 
							$fieldSnipped, 
							$field_options['group_id'], 
							$preview
						);
																																																																					
					}				
								
				break;

				case 'woo':

					if ( $xmlWriter and XmlExportEngine::$exportOptions['export_to'] == 'xml')
					{
						XmlExportEngine::$woo_export->export_xml($xmlWriter, $entry, XmlExportEngine::$exportOptions, $ID); 
					}
					else
					{
						XmlExportEngine::$woo_export->export_csv($article, $woo, $entry, XmlExportEngine::$exportOptions, $ID); 	
					}										
													
					break;
				
				case 'woo_order':								

					if ( $xmlWriter and XmlExportEngine::$exportOptions['export_to'] == 'xml')
					{
						XmlExportEngine::$woo_order_export->export_xml($xmlWriter, $entry, XmlExportEngine::$exportOptions, $ID, $preview); 						
					}
					else
					{
						XmlExportEngine::$woo_order_export->export_csv($article, $woo_order, $entry, XmlExportEngine::$exportOptions, $ID, $preview); 			
					}									

					break;

				case 'attr':
					
					if ( ! empty($fieldValue))
					{
						if ( $entry->post_parent == 0 )
						{
							$txes_list = get_the_terms($entry->ID, $fieldValue);
							if ( ! is_wp_error($txes_list) and ! empty($txes_list)) 
							{
								$attr_new = array();										
								foreach ($txes_list as $t) 
								{
									$attr_new[] = $t->name;
								}																		
								wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_woo_attribute', pmxe_filter(implode($implode_delimiter, $attr_new), $fieldSnipped), $entry->ID, $fieldValue) );
							}																		
						}
						else
						{
							$attribute_pa = apply_filters('pmxe_woo_attribute', get_post_meta($entry->ID, 'attribute_' . $fieldValue, true), $entry->ID, $fieldValue);
							
							wp_all_export_write_article( $article, $element_name, $attribute_pa );
							
						}								

						// if ( ! in_array($element_name, $attributes)) $attributes[] = $element_name;						
					}							
					break;
				
				case 'cats':

					if ( ! empty($fieldValue) )
					{	
						// $article[$element_name] = null;										
						$txes_list = get_the_terms($entry->ID, $fieldValue);
						if ( ! is_wp_error($txes_list) and ! empty($txes_list) ) 
						{															
							$txes_ids = array();										
							$hierarchy_groups = array();
																
							foreach ($txes_list as $t) {																						
								$txes_ids[] = $t->term_id;
							}

							foreach ($txes_list as $t) {
								if ( wp_all_export_check_children_assign($t->term_id, $fieldValue, $txes_ids) ){
									$ancestors = get_ancestors( $t->term_id, $fieldValue );
									if (count($ancestors) > 0){
										$hierarchy_group = array();
										for ( $i = count($ancestors) - 1; $i >= 0; $i-- ) { 															
											$term = get_term_by('id', $ancestors[$i], $fieldValue);
											if ($term){
												$hierarchy_group[] = $term->name;
											}
										}
										$hierarchy_group[]  = $t->name;
										$hierarchy_groups[] = implode('>', $hierarchy_group);
									}
									else{
										$hierarchy_groups[] = $t->name;
									}
								}
							}	

							if ( ! empty($hierarchy_groups) )
							{
								wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_post_taxonomy', pmxe_filter(implode($implode_delimiter, $hierarchy_groups), $fieldSnipped), $entry->ID) );
							}																	
							
						}
						
						// if ( ! in_array($element_name, $taxes)) $taxes[] = $element_name;

						if ($fieldLabel == 'product_type'){ 
							
							if ( $entry->post_type == 'product_variation' ) $article[$element_name] = 'variable';																			

							$article['parent_id']  = $entry->post_parent;	

							if ($xmlWriter and XmlExportEngine::$exportOptions['export_to'] == 'xml')
							{
								$xmlWriter->beginElement($element_name_ns, 'parent_id', null);
									$xmlWriter->writeData($article['parent_id'], 'parent_id');
								$xmlWriter->endElement();
							}
						}							
					}							

					break;
					
				case 'sql':						

					if ( ! empty($fieldSql) ) 
					{
						global $wpdb;
						$val = $wpdb->get_var( $wpdb->prepare( stripcslashes(str_replace("%%ID%%", "%d", $fieldSql)), $entry->ID ));
						if ( ! empty($fieldPhp) and !empty($fieldCode) )
						{
							// if shortcode defined
							if (strpos($fieldCode, '[') === 0)
							{
								$val = do_shortcode(str_replace("%%VALUE%%", $val, $fieldCode));
							}	
							else
							{
								$val = eval('return ' . stripcslashes(str_replace("%%VALUE%%", $val, $fieldCode)) . ';');
							}										
						}						
						wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_sql_field', $val, $element_name, $entry->ID) );
					}
					break;

				case 'wpml_trid':

					$post_type = get_post_type($entry->ID);

					$post_type = apply_filters( 'wpml_element_type', $post_type );

					$post_language_details = apply_filters('wpml_element_language_details',
						null, 
						array(
							'element_id'   => $entry->ID,
							'element_type' => $post_type
						)
					);

					$trid = empty($post_language_details->trid) ? '' : $post_language_details->trid;

					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_trid_field', $trid, $element_name, $entry->ID) );

					break;
				
				case 'wpml_lang':

					$post_type = get_post_type($entry->ID);

					$post_type = apply_filters( 'wpml_element_type', $post_type );

					$post_language_details = apply_filters('wpml_element_language_details',
						null, 
						array(
							'element_id'   => $entry->ID,
							'element_type' => $post_type
						)
					);

					$language_code = empty($post_language_details->language_code) ? '' : $post_language_details->language_code;

					wp_all_export_write_article( $article, $element_name, apply_filters('pmxe_trid_field', $language_code, $element_name, $entry->ID) );

					break;
					
				default:
					# code...
					break;
			}
			
			if ( $xmlWriter and XmlExportEngine::$exportOptions['export_to'] == 'xml' and isset($article[$element_name]) )
			{								
				$element_name_in_file = XmlCsvExport::_get_valid_header_name( $element_name );
				
				$xmlWriter = apply_filters('wp_all_export_add_before_element', $xmlWriter, $element_name_in_file, XmlExportEngine::$exportID, $entry->ID);

				$xmlWriter->beginElement($element_name_ns, $element_name_in_file, null);
					$xmlWriter->writeData($article[$element_name], $element_name_in_file);
				$xmlWriter->endElement();	

				$xmlWriter = apply_filters('wp_all_export_add_after_element', $xmlWriter, $element_name_in_file, XmlExportEngine::$exportID, $entry->ID);
			}
		}
		return $article;
	}	

	public static function prepare_import_template( $exportOptions, &$templateOptions, &$cf_list, &$attr_list, &$taxs_list, $element_name, $ID)
	{
		$options = $exportOptions;

		$element_type = $options['cc_type'][$ID];

		$is_xml_template = $options['export_to'] == 'xml';

		$implode_delimiter = ($options['delimiter'] == ',') ? '|' : ',';		

		switch ($element_type) 
		{
			case 'id':
				if ($element_name == 'ID') $element_name = 'id';
				$templateOptions['unique_key'] = '{'. $element_name .'[1]}';										
				$templateOptions['tmp_unique_key'] = '{'. $element_name .'[1]}';	
				$templateOptions['single_product_id'] = '{'. $element_name .'[1]}';
				break;
			case 'title':	
			case 'content':
			case 'author':				
			case 'parent':				
			case 'slug':
				$templateOptions[$element_type] = '{'. $element_name .'[1]}';										
				$templateOptions['is_update_' . $options['cc_type'][$ID]] = 1;
				break;	
			case 'excerpt':
				$templateOptions['post_excerpt'] = '{'. $element_name .'[1]}';										
				$templateOptions['is_update_' . $options['cc_type'][$ID]] = 1;
				break;
			case 'status':
				$templateOptions['status_xpath'] = '{'. $element_name .'[1]}';
				$templateOptions['is_update_status'] = 1;
				break;								
			case 'date':
				$templateOptions[$element_type] = '{'. $element_name .'[1]}';										
				$templateOptions['is_update_dates'] = 1;
				break;																						
			case 'post_type':

				if ( empty($options['cpt']) )
				{
					$templateOptions['is_override_post_type'] = 1;	
					$templateOptions['post_type_xpath'] = '{'. $element_name .'[1]}';
				}
				break;

			case 'cf':
				
				if ( ! empty($options['cc_value'][$ID]) )
				{																																
					$exclude_cf = array('_thumbnail_id');

					if (strpos($options['cc_value'][$ID], 'attribute_') === 0 and ! in_array($options['cc_value'][$ID], $attr_list))
					{
						$templateOptions['attribute_name'][] = str_replace('attribute_', '', $options['cc_value'][$ID]);
						$templateOptions['attribute_value'][] = '{'. $element_name .'[1]}';
						$templateOptions['in_variations'][] = "1";
						$templateOptions['is_visible'][] = "1";
						$templateOptions['is_taxonomy'][] = "0";
						$templateOptions['create_taxonomy_in_not_exists'][] = "0";	
						$attr_list[] = $options['cc_value'][$ID];
					}
					elseif ( ! in_array($options['cc_value'][$ID], $cf_list) and ! in_array($options['cc_value'][$ID], $exclude_cf) )
					{
						$cf_list[] = $options['cc_value'][$ID];
						
						$templateOptions['custom_name'][] = $options['cc_value'][$ID];								
						$templateOptions['custom_value'][] = '{'. $element_name .'[1]}';		
						$templateOptions['custom_format'][] = 0;
					} 						
				}

				break;								

			case 'attr':				

				if ( ! empty($options['cc_value'][$ID]) and ! in_array($options['cc_value'][$ID], $attr_list) ) 
				{
					$templateOptions['attribute_name'][] = str_replace('pa_', '', $options['cc_value'][$ID]);
					$templateOptions['attribute_value'][] = '{'. $element_name .'[1]}';
					$templateOptions['in_variations'][] = "1";
					$templateOptions['is_visible'][] = "1";
					$templateOptions['is_taxonomy'][] = "1";
					$templateOptions['create_taxonomy_in_not_exists'][] = "1";	
					$attr_list[] = $options['cc_value'][$ID];
				}								
				
				break;
			case 'cats':
				if ( ! empty($options['cc_value'][$ID]) )
				{																															
					switch ($options['cc_label'][$ID]) 
					{
						case 'product_type':
							$templateOptions['is_multiple_product_type'] = 'no';
							$templateOptions['single_product_type'] = '{'. $element_name .'[1]}';
							break;
						case 'product_shipping_class':
							$templateOptions['is_multiple_product_shipping_class'] = 'no';
							$templateOptions['single_product_shipping_class'] = '{'. $element_name .'[1]}';
							break;
						default:
							$taxonomy = $options['cc_value'][$ID];											
							$templateOptions['tax_assing'][$taxonomy] = 1;

							if (is_taxonomy_hierarchical($taxonomy))
							{								
								$templateOptions['tax_logic'][$taxonomy] = 'hierarchical';
								$templateOptions['tax_hierarchical_logic_entire'][$taxonomy] = 1;
								$templateOptions['multiple_term_assing'][$taxonomy] = 1;
								$templateOptions['tax_hierarchical_delim'][$taxonomy] = '>';
								$templateOptions['is_tax_hierarchical_group_delim'][$taxonomy] = 1;
								$templateOptions['tax_hierarchical_group_delim'][$taxonomy] = $is_xml_template ? '|' : $implode_delimiter;
								$templateOptions['tax_hierarchical_xpath'][$taxonomy] = array('{'. $element_name .'[1]}');								
							}
							else{
								$templateOptions['tax_logic'][$taxonomy] = 'multiple';
								$templateOptions['multiple_term_assing'][$taxonomy] = 1;
								$templateOptions['tax_multiple_xpath'][$taxonomy] = '{'. $element_name .'[1]}';
								$templateOptions['tax_multiple_delim'][$taxonomy] = $is_xml_template ? '|' : $implode_delimiter;
							}
							$taxs_list[] = $taxonomy;										
						break;
					}
				}
				break;			
			
		}		
	}

	/**
     * __get function.
     *
     * @access public
     * @param mixed $key
     * @return mixed
     */
    public function __get( $key ) {
        return $this->get( $key );
    }	

    /**
     * Get a session variable
     *
     * @param string $key
     * @param  mixed $default used if the session variable isn't set
     * @return mixed value of session variable
     */
    public function get( $key, $default = null ) {        
        return isset( $this->{$key} ) ? $this->{$key} : $default;
    }
}