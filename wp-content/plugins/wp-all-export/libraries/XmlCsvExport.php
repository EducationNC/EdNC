<?php

final Class XmlCsvExport
{
	/**
	 * Singletone instance
	 * @var XmlCsvExport
	 */
	protected static $instance;

	/**
	 * Return singletone instance
	 * @return XmlCsvExport
	 */
	static public function getInstance() {		
		if ( self::$instance == NULL ) {
			self::$instance = new self();
		}			
		return self::$instance;
	}

	private function __construct(){}	

	public static function export()
	{				
		switch ( XmlExportEngine::$exportOptions['export_to'] ) 
		{
			case 'xml':									
				self::export_xml();
				break;

			case 'csv':
				self::export_csv();								
				break;								

			default:
				# code...
				break;
		}
	}

	public static function export_csv( $preview = false, $is_cron = false, $file_path = false, $exported_by_cron = 0 )
	{
		
		if ( XmlExportEngine::$exportOptions['delimiter'] == '\t' ) XmlExportEngine::$exportOptions['delimiter'] = "\t";

		ob_start();					

		$stream = fopen("php://output", 'w');				
		
		$headers 	= array();
		$woo 		= array();
		$woo_order  = array();
		$acfs 		= array();
		$taxes 		= array();
		$attributes = array();
		$articles 	= array();

		$implode_delimiter = (XmlExportEngine::$exportOptions['delimiter'] == ',') ? '|' : ',';		

		// [ Exporting requested data ]

		if ( XmlExportEngine::$is_user_export )  // exporting WordPress users
		{
			foreach ( XmlExportEngine::$exportQuery->results as $user ) :				
				$articles[] = XmlExportUser::prepare_data( $user, false, $acfs, $implode_delimiter, $preview );
				$articles   = apply_filters('wp_all_export_csv_rows', $articles, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);					
				if ( ! $preview) do_action('pmxe_exported_post', $user->ID, XmlExportEngine::$exportRecord );
			endforeach;			
		}
		elseif ( XmlExportEngine::$is_comment_export )  // exporting comments
		{
			global $wp_version;

			if ( version_compare($wp_version, '4.2.0', '>=') ) 
			{
				$comments = XmlExportEngine::$exportQuery->get_comments();
			}
			else
			{
				$comments = XmlExportEngine::$exportQuery;
			}
					
			foreach ( $comments as $comment ) :
				$articles[] = XmlExportComment::prepare_data( $comment, false, $implode_delimiter, $preview );
				$articles   = apply_filters('wp_all_export_csv_rows', $articles, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);					
				if ( ! $preview) do_action('pmxe_exported_post', $comment->comment_ID, XmlExportEngine::$exportRecord );
			endforeach;			
		}
		else  // exporting custom post types
		{			
			while ( XmlExportEngine::$exportQuery->have_posts() ) :		
				XmlExportEngine::$exportQuery->the_post();
				$record = get_post( get_the_ID() );
				$articles[] = XmlExportCpt::prepare_data( $record, false, $acfs, $woo, $woo_order, $implode_delimiter, $preview );
				$articles   = apply_filters('wp_all_export_csv_rows', $articles, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);					
				if ( ! $preview) do_action('pmxe_exported_post', $record->ID, XmlExportEngine::$exportRecord );
			endwhile;			
			wp_reset_postdata();									
		}
		// [ \Exporting requested data ]

		// [ Prepare CSV headers ]
		if (XmlExportEngine::$exportOptions['ids']):

			foreach (XmlExportEngine::$exportOptions['ids'] as $ID => $value) 
			{
				if ( empty(XmlExportEngine::$exportOptions['cc_name'][$ID]) or empty(XmlExportEngine::$exportOptions['cc_type'][$ID]) or ! is_numeric($ID) ) continue;					

				self::prepare_csv_headers( $headers, $ID, $taxes, $attributes, $acfs );												
			}						

		endif;

		$headers = apply_filters( 'wp_all_export_csv_headers', $headers, XmlExportEngine::$exportID );

		if ($is_cron)
		{
			if ( ! $exported_by_cron )
			{
				fputcsv($stream, array_map(array('XmlCsvExport', '_get_valid_header_name'), $headers), XmlExportEngine::$exportOptions['delimiter']);		
			} 
			else
			{
				self::merge_headers( $file_path, $headers );	
			}
		}
		else
		{
			if ($preview or empty(PMXE_Plugin::$session->file))
			{
				fputcsv($stream, array_map(array('XmlCsvExport', '_get_valid_header_name'), $headers), XmlExportEngine::$exportOptions['delimiter']);		
			}
			else
			{
				self::merge_headers( PMXE_Plugin::$session->file, $headers );		
			}
		}
		// [ \Prepare CSV headers ]				

		foreach ($articles as $article) {									
			$line = array();			
			foreach ($headers as $header) {
				$line[$header] = ( isset($article[$header]) ) ? $article[$header] : '';						
			}											
			fputcsv($stream, $line, XmlExportEngine::$exportOptions['delimiter']);
		}			

		if ($preview) return ob_get_clean();	

		return self::save_csv_to_file( $file_path, $is_cron, $exported_by_cron );
		
	}

	public static $main_xml_tag = '';
	public static $node_xml_tag = '';
	public static function export_xml( $preview = false, $is_cron = false, $file_path = false, $exported_by_cron = 0 )
	{
		if ( XmlExportEngine::$exportOptions['delimiter'] == '\t' ) XmlExportEngine::$exportOptions['delimiter'] = "\t";

		require_once PMXE_ROOT_DIR . '/classes/XMLWriter.php';
		
		$woo 		= array();
		$woo_order  = array();
		$acfs 		= array();
		$taxes 		= array();
		$attributes = array();

		self::$main_xml_tag = apply_filters('wp_all_export_main_xml_tag', XmlExportEngine::$exportOptions['main_xml_tag'], XmlExportEngine::$exportID);
		self::$node_xml_tag = apply_filters('wp_all_export_record_xml_tag', XmlExportEngine::$exportOptions['record_xml_tag'], XmlExportEngine::$exportID);
		
		$implode_delimiter = (XmlExportEngine::$exportOptions['delimiter'] == ',') ? '|' : ',';	

		$xmlWriter = new PMXE_XMLWriter();
		$xmlWriter->openMemory();
		$xmlWriter->setIndent(true);
		$xmlWriter->setIndentString("\t");
		$xmlWriter->startDocument('1.0', XmlExportEngine::$exportOptions['encoding']);
		$xmlWriter->startElement(self::$main_xml_tag);		

		// add additional data after XML root element
		self::xml_header( $xmlWriter, $is_cron, $exported_by_cron );			

		// [ Exporting requested data ]

		if ( XmlExportEngine::$is_user_export ) // exporting WordPress users
		{
			foreach ( XmlExportEngine::$exportQuery->results as $user ) :

				$is_export_record = apply_filters('wp_all_export_xml_rows', true, $user, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);		

				if ( ! $is_export_record ) continue;

				// add additional information before each node			
				self::before_xml_node( $xmlWriter, $user->ID);				

				$xmlWriter->startElement(self::$node_xml_tag);

					XmlExportUser::prepare_data( $user, $xmlWriter, $acfs, $implode_delimiter, $preview );

				$xmlWriter->endElement(); // end post

				// add additional information after each node			
				self::after_xml_node( $xmlWriter, $user->ID);													

				if ( ! $preview) do_action('pmxe_exported_post', $user->ID, XmlExportEngine::$exportRecord );

			endforeach;
			
		}
		elseif ( XmlExportEngine::$is_comment_export ) // exporting comments
		{
			global $wp_version;
					
			if ( version_compare($wp_version, '4.2.0', '>=') ) 
			{
				$comments = XmlExportEngine::$exportQuery->get_comments();
			}
			else
			{
				$comments = XmlExportEngine::$exportQuery;
			}

			foreach ( $comments as $comment ) :
				
				$is_export_record = apply_filters('wp_all_export_xml_rows', true, $comment, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);		

				if ( ! $is_export_record ) continue;

				// add additional information before each node			
				self::before_xml_node( $xmlWriter, $comment->comment_ID);				

				$xmlWriter->startElement(self::$node_xml_tag);

					XmlExportComment::prepare_data( $comment, $xmlWriter, $implode_delimiter, $preview );

				$xmlWriter->endElement(); // end post

				// add additional information after each node			
				self::after_xml_node( $xmlWriter, $comment->comment_ID);									

				if ( ! $preview) do_action('pmxe_exported_post', $comment->comment_ID, XmlExportEngine::$exportRecord );

			endforeach;				
		}				
		else // exporting custom post types
		{						
			while ( XmlExportEngine::$exportQuery->have_posts() ) :		
				
				XmlExportEngine::$exportQuery->the_post();
				
				$record = get_post( get_the_ID() );

				$is_export_record = apply_filters('wp_all_export_xml_rows', true, $record, XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);		

				if ( ! $is_export_record ) continue;

				// add additional information before each node			
				self::before_xml_node( $xmlWriter, $record->ID);				

				$xmlWriter->startElement(self::$node_xml_tag);

					XmlExportCpt::prepare_data( $record, $xmlWriter, $acfs, $woo, $woo_order, $implode_delimiter, $preview );

				$xmlWriter->endElement(); // end post

				// add additional information after each node			
				self::after_xml_node( $xmlWriter, $record->ID);									

				if ( ! $preview) do_action('pmxe_exported_post', $record->ID, XmlExportEngine::$exportRecord );

			endwhile;			
			wp_reset_postdata();		
		}
		// [ \Exporting requested data ]

		$xmlWriter->endElement(); // close root XML element

		if ($preview) return $xmlWriter->flush(true);

		return self::save_xml_to_file( $xmlWriter, $file_path, $is_cron, $exported_by_cron );
			
	}

	// [ XML Export Helpers ]
	private static function xml_header($xmlWriter, $is_cron, $exported_by_cron)
	{
		if ($is_cron)
		{							
			if ( ! $exported_by_cron )
			{
				$additional_data = apply_filters('wp_all_export_additional_data', array(), XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);

				if ( ! empty($additional_data))
				{
					foreach ($additional_data as $key => $value) 
					{
						$xmlWriter->startElement(preg_replace('/[^a-z0-9_-]/i', '', $key));
							$xmlWriter->writeData($value, preg_replace('/[^a-z0-9_-]/i', '', $key));
						$xmlWriter->endElement();		
					}
				}
			}					
		}
		else
		{

			if ( empty(PMXE_Plugin::$session->file) ){

				$additional_data = apply_filters('wp_all_export_additional_data', array(), XmlExportEngine::$exportOptions, XmlExportEngine::$exportID);

				if ( ! empty($additional_data))
				{
					foreach ($additional_data as $key => $value) 
					{
						$xmlWriter->startElement(preg_replace('/[^a-z0-9_-]/i', '', $key));
							$xmlWriter->writeData($value, preg_replace('/[^a-z0-9_-]/i', '', $key));
						$xmlWriter->endElement();		
					}
				}
			}			
		}
	}

	private static function before_xml_node( $xmlWriter, $pid )
	{
		$add_before_node = apply_filters('wp_all_export_add_before_node', array(), XmlExportEngine::$exportOptions, XmlExportEngine::$exportID, $pid);

		if ( ! empty($add_before_node))
		{
			foreach ($add_before_node as $key => $value) 
			{
				$xmlWriter->startElement(preg_replace('/[^a-z0-9_-]/i', '', $key));
					$xmlWriter->writeData($value, preg_replace('/[^a-z0-9_-]/i', '', $key));
				$xmlWriter->endElement();		
			}
		}
	}

	private static function after_xml_node( $xmlWriter, $pid )
	{
		$add_after_node = apply_filters('wp_all_export_add_after_node', array(), XmlExportEngine::$exportOptions, XmlExportEngine::$exportID, $pid);

		if ( ! empty($add_after_node))
		{
			foreach ($add_after_node as $key => $value) 
			{
				$xmlWriter->startElement(preg_replace('/[^a-z0-9_-]/i', '', $key));
					$xmlWriter->writeData($value, preg_replace('/[^a-z0-9_-]/i', '', $key));
				$xmlWriter->endElement();		
			}
		}
	}

	private static function save_xml_to_file( $xmlWriter, $file_path, $is_cron, $exported_by_cron )
	{
		if ($is_cron)
		{					
			$xml_header = apply_filters('wp_all_export_xml_header', '<?xml version="1.0" encoding="UTF-8"?>', XmlExportEngine::$exportID);

			$xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', $xml_header, $xmlWriter->flush(true));

			if ( ! $exported_by_cron )
			{			
				// The BOM will help some programs like Microsoft Excel read your export file if it includes non-English characters.
				if (XmlExportEngine::$exportOptions['include_bom']) 
				{
					file_put_contents($file_path, chr(0xEF).chr(0xBB).chr(0xBF).substr($xml, 0, (strlen(self::$main_xml_tag) + 4) * (-1)));
				}
				else
				{
					file_put_contents($file_path, substr($xml, 0, (strlen(self::$main_xml_tag) + 4) * (-1)));
				}			
			}
			else
			{
				file_put_contents($file_path, substr(substr($xml, 41 + strlen(self::$main_xml_tag)), 0, (strlen(self::$main_xml_tag) + 4) * (-1)), FILE_APPEND);
			}
			
			return $file_path;	
			
		}
		else
		{

			if ( empty(PMXE_Plugin::$session->file) ){

				// generate export file name
				$export_file = wp_all_export_generate_export_file( XmlExportEngine::$exportID );			

				$xml_header = apply_filters('wp_all_export_xml_header', '<?xml version="1.0" encoding="UTF-8"?>', XmlExportEngine::$exportID);

				$xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', $xml_header, $xmlWriter->flush(true));

				// The BOM will help some programs like Microsoft Excel read your export file if it includes non-English characters.
				if (XmlExportEngine::$exportOptions['include_bom']) 
				{				
					file_put_contents($export_file, chr(0xEF).chr(0xBB).chr(0xBF).substr($xml, 0, (strlen(self::$main_xml_tag) + 4) * (-1)));
				}
				else
				{
					file_put_contents($export_file, substr($xml, 0, (strlen(self::$main_xml_tag) + 4) * (-1)));
				}

				PMXE_Plugin::$session->set('file', $export_file);
				
				PMXE_Plugin::$session->save_data();

			}	
			else
			{
				file_put_contents(PMXE_Plugin::$session->file, substr(substr($xmlWriter->flush(true), 41 + strlen(self::$main_xml_tag)), 0, (strlen(self::$main_xml_tag) + 4) * (-1)), FILE_APPEND);
			}

			return true;

		}
	}
	// [ \XML Export Helpers ]

	// [ CSV Export Helpers ]
	public static function prepare_csv_headers( & $headers, $ID, & $taxes, & $attributes, & $acfs )
	{									
		$element_name = ( ! empty(XmlExportEngine::$exportOptions['cc_name'][$ID]) ) ? XmlExportEngine::$exportOptions['cc_name'][$ID] : 'untitled_' . $ID;

		if ( strpos(XmlExportEngine::$exportOptions['cc_label'][$ID], "item_data__") !== false )
		{
			XmlExportEngine::$woo_order_export->get_element_header( $headers, XmlExportEngine::$exportOptions, $ID );
			return;
		}	
		
		switch (XmlExportEngine::$exportOptions['cc_type'][$ID]) 
		{					
			case 'woo':
				
				XmlExportEngine::$woo_export->get_element_header( $headers, XmlExportEngine::$exportOptions, $ID );		
				
				break;

			case 'woo_order':

				XmlExportEngine::$woo_order_export->get_element_header( $headers, XmlExportEngine::$exportOptions, $ID );												
				
				break;

			case 'acf':
				
				if ( ! empty($acfs) )
				{
					$single_acf_field = array_shift($acfs);								

					if ( is_array($single_acf_field))
					{					
						foreach ($single_acf_field as $acf_header) {
							if ( ! in_array($acf_header, $headers)) $headers[] = $acf_header;
						}
					}
					else
					{
						if ( ! in_array($single_acf_field, $headers)) $headers[] = $single_acf_field;
					}
				}
				
				break;
			
			default:

				if ($element_name == 'ID') $element_name = 'id';

				if ( ! in_array($element_name, $headers)) 
				{
					$headers[] = $element_name;
				}
				else
				{
					$is_added = false;
					$i = 0;
					do
					{
						$new_element_name = $element_name . '_' . md5($i);

						if ( ! in_array($new_element_name, $headers) )
						{
							$headers[] = $new_element_name;
							$is_added = true;
						}

						$i++;
					}
					while ( ! $is_added );						
				}

				if ( XmlExportEngine::$exportOptions['cc_label'][$ID] == 'product_type' and ! in_array('parent_id', $headers)) $headers[] = 'parent_id';
			
				break;
		}		
											
	}

	public static function _get_valid_header_name( $element_name )
	{		
		$element_name_parts = explode("_", $element_name);				
				
		$elementIndex = array_pop($element_name_parts);
		
		if (wp_all_export_isValidMd5($elementIndex))
		{
			$element_name_in_file = str_replace("_" . $elementIndex, "", $element_name);
		}										
		else
		{
			$element_name_in_file = $element_name;
		}

		return $element_name_in_file;
	}


	public static function merge_headers( $file, &$headers )
	{				

		$in  = fopen($file, 'r');			

		$clear_old_headers = fgetcsv($in, 0, XmlExportEngine::$exportOptions['delimiter']);		

		fclose($in);		

		$old_headers = array();

		foreach ($clear_old_headers as $i => $header) 
		{
			$header = str_replace("'", "", str_replace('"', "", str_replace(chr(0xEF).chr(0xBB).chr(0xBF), "", $header)));

			if ( ! in_array($header, $old_headers)) 
			{
				$old_headers[] = $header;
			}
			else
			{
				$is_added = false;
				$i = 0;
				do
				{
					$new_element_name = $header . '_' . md5($i);

					if ( ! in_array($new_element_name, $old_headers) )
					{
						$old_headers[] = $new_element_name;
						$is_added = true;
					}

					$i++;
				}
				while ( ! $is_added );						
			}
		}

		$is_update_headers = false;

		foreach ($headers as $header) 
		{
			if ( ! in_array($header, $old_headers))	
			{
				$is_update_headers = true;
				break;
			}			
		}		
		
		if ($is_update_headers)
		{												

			$headers = $old_headers + $headers;							
			
			$tmp_file = str_replace(basename($file), 'iteration_' . basename($file), $file);

			copy($file, $tmp_file);					

			$in  = fopen($tmp_file, 'r');															

			$out = fopen($file, 'w');

			$headers = apply_filters('wp_all_export_csv_headers', $headers, XmlExportEngine::$exportID);

			if ( XmlExportEngine::$exportOptions['include_bom'] ) 
			{												
				fputcsv($out, chr(0xEF).chr(0xBB).chr(0xBF) . array_map(array('XmlCsvExport', '_get_valid_header_name'), $headers), XmlExportEngine::$exportOptions['delimiter']);
			}
			else
			{								
				fputcsv($out, array_map(array('XmlCsvExport', '_get_valid_header_name'), $headers), XmlExportEngine::$exportOptions['delimiter']);
			}						

			$exclude_old_headers = fgetcsv($in);		

			if (is_resource($in))
			{
				while ( ! feof($in) ) {
				    $data = fgetcsv($in, 0, XmlExportEngine::$exportOptions['delimiter']);	
					if ( empty($data) ) continue;
				    $data_assoc = array_combine($old_headers, array_values($data));	    			    			    			    
				    $line = array();
					foreach ($headers as $header) {					
						$line[$header] = ( isset($data_assoc[$header]) ) ? $data_assoc[$header] : '';	
					}					
					fputcsv($out, $line, XmlExportEngine::$exportOptions['delimiter']);
				}
				fclose($in);
			}	
			fclose($out);
			@unlink($tmp_file);
		}								
	}

	private static function save_csv_to_file( $file_path, $is_cron, $exported_by_cron )
	{
		if ($is_cron)
		{		
			if ( ! $exported_by_cron )
			{
				// The BOM will help some programs like Microsoft Excel read your export file if it includes non-English characters.					
				if (XmlExportEngine::$exportOptions['include_bom']) 
				{
					file_put_contents($file_path, chr(0xEF).chr(0xBB).chr(0xBF).ob_get_clean());
				}
				else
				{
					file_put_contents($file_path, ob_get_clean());
				}			
			}
			else
			{
				file_put_contents($file_path, ob_get_clean(), FILE_APPEND);
			}		

			return $file_path;

		}
		else
		{						
			if ( empty(PMXE_Plugin::$session->file) ){		

				// generate export file name
				$export_file = wp_all_export_generate_export_file( XmlExportEngine::$exportID );			

				// The BOM will help some programs like Microsoft Excel read your export file if it includes non-English characters.					
				if (XmlExportEngine::$exportOptions['include_bom']) 
				{
					file_put_contents($export_file, chr(0xEF).chr(0xBB).chr(0xBF).ob_get_clean());
				}
				else
				{
					file_put_contents($export_file, ob_get_clean());
				}

				PMXE_Plugin::$session->set('file', $export_file);
				
				PMXE_Plugin::$session->save_data();

			}	
			else
			{
				file_put_contents(PMXE_Plugin::$session->file, ob_get_clean(), FILE_APPEND);
			}

			return true;
		}
	}
	// [ \CSV Export Helpers ]

	public static function auto_genetate_export_fields( $post, $errors = false )
	{		
		$errors or $errors = new WP_Error();

		remove_all_filters( "wp_all_export_init_fields", 10 );
		remove_all_filters( "wp_all_export_default_fields", 10 );
		remove_all_filters( "wp_all_export_other_fields", 10 );
		remove_all_filters( "wp_all_export_available_sections", 10 );
		remove_all_filters( "wp_all_export_available_data", 10 );

		$engine = new XmlExportEngine($post, $errors);	
		$engine->init_additional_data();													

		$auto_generate = array(
			'ids' 		 => array(),
			'cc_label' 	 => array(),
			'cc_php' 	 => array(),
			'cc_code' 	 => array(),
			'cc_sql' 	 => array(),
			'cc_type' 	 => array(),
			'cc_options' => array(),
			'cc_value' 	 => array(),
			'cc_name' 	 => array()
		);

		$available_data     = $engine->init_available_data();		

		$available_sections = apply_filters("wp_all_export_available_sections", $engine->get('available_sections'));
		
		foreach ($available_sections as $slug => $section) 
		{
			if ( ! empty($section['content']) and ! empty($available_data[$section['content']]))
			{				
				foreach ($available_data[$section['content']] as $field) 
				{					
					if ( is_array($field) and (isset($field['auto']) or ! in_array('product', $post['cpt']) ))
					{							
						$auto_generate['ids'][] 	   = 1;
						$auto_generate['cc_label'][]   = is_array($field) ? $field['label'] : $field;
						$auto_generate['cc_php'][] 	   = 0;
						$auto_generate['cc_code'][]    = '';
						$auto_generate['cc_sql'][]     = '';
						$auto_generate['cc_settings'][]     = '';
						$auto_generate['cc_type'][]    = is_array($field) ? $field['type'] : $slug;
						$auto_generate['cc_options'][] = '';
						$auto_generate['cc_value'][]   = is_array($field) ? $field['label'] : $field;
						$auto_generate['cc_name'][]    = is_array($field) ? $field['name'] : $field;
					}
				}				
			}		
			if ( ! empty($section['additional']) )
			{
				foreach ($section['additional'] as $sub_slug => $sub_section) 
				{
					foreach ($sub_section['meta'] as $field) 
					{		
						$field_options = ( in_array($sub_slug, array('images', 'attachments')) ) ? esc_attr('{"is_export_featured":true,"is_export_attached":true,"image_separator":"|"}') : '0';
						$field_name = '';
						switch ($sub_slug) {
							case 'images':
								$field_name = 'Image ' . $field['name'];
								break;
							case 'attachments':
								$field_name = 'Attachment ' . $field['name'];
								break;							
							default:
								$field_name = $field['name'];
								break;
						}

						if ( is_array($field) and isset($field['auto']) )
						{
							$auto_generate['ids'][] 	   = 1;
							$auto_generate['cc_label'][]   = is_array($field) ? $field['label'] : $field;
							$auto_generate['cc_php'][] 	   = 0;
							$auto_generate['cc_code'][]    = '';
							$auto_generate['cc_sql'][]     = '';
							$auto_generate['cc_settings'][]     = '';
							$auto_generate['cc_type'][]    = is_array($field) ? $field['type'] : $sub_slug;
							$auto_generate['cc_options'][] = $field_options;
							$auto_generate['cc_value'][]   = is_array($field) ? $field['label'] : $field;
							$auto_generate['cc_name'][]    = $field_name;
						}
					}
				}
			}	
		}		

		if ( XmlExportWooCommerceOrder::$is_active ) {
			foreach (XmlExportWooCommerceOrder::$order_sections as $slug => $section) {
				if ( ! empty($section['meta']) ) {
					foreach ($section['meta'] as $cur_meta_key => $field) {									
						$auto_generate['ids'][] 	   = 1;
						$auto_generate['cc_label'][]   = is_array($field) ? $field['label'] : $cur_meta_key;
						$auto_generate['cc_php'][] 	   = 0;
						$auto_generate['cc_code'][]    = '';
						$auto_generate['cc_sql'][]     = '';
						$auto_generate['cc_settings'][]     = '';
						$auto_generate['cc_type'][]    = is_array($field) ? $field['type'] : 'woo_order';
						$auto_generate['cc_options'][] = is_array($field) ? $field['options'] : $slug;
						$auto_generate['cc_value'][]   = is_array($field) ? $field['label'] : $cur_meta_key;
						$auto_generate['cc_name'][]    = is_array($field) ? $field['name'] : $field;
					}
				}
			}
		}

		return $auto_generate;
	}
}