<?php
function pmxi_wp_ajax_upload_resource(){

	extract($_POST);

	$response = array(
		'success' => true,
		'errors' => false,
		'upload_result' => '',
		'filesize' => 0
	);

	if ($type == 'url'){

		$errors = new WP_Error;
		$uploader = new PMXI_Upload(trim($file), $errors);			
		$upload_result = $uploader->url();			

		if ($upload_result instanceof WP_Error){
			$errors = $upload_result;

			$msgs = $errors->get_error_messages();
			ob_start();
			?>
			<?php foreach ($msgs as $msg): ?>
				<div class="error inline"><p><?php echo $msg; ?></p></div>
			<?php endforeach ?>
			<?php
			$response = array(		
				'success' => false,
				'errors'  => ob_get_clean()
			);			

		}
		else {

			// validate XML
			$file = new PMXI_Chunk($upload_result['filePath'], array('element' => $upload_result['root_element']));										    					    					   												

			$is_valid = true;

			if ( ! empty($file->options['element']) ) 						
				$defaultXpath = "/". $file->options['element'];																			    		  
			else
				$is_valid = false;
			
			if ( $is_valid ){

				while ($xml = $file->read()) {

			    	if ( ! empty($xml) ) { 

			      		PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n" . $xml;
			    	
				      	$dom = new DOMDocument( '1.0', 'UTF-8' );
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml);
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);									
						if (($elements = $xpath->query($defaultXpath)) and $elements->length){
							break;
						}												
				    }
				    /*else {
				    	$is_valid = false;
				    	break;
				    }*/
				}

				if ( empty($xml) ) $is_valid = false;
			}

			unset($file);
				
			if ( ! $is_valid )
			{
				ob_start();
				?>
				
				<div class="error inline"><p><?php _e('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@wpallimport.com">support@wpallimport.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'wp_all_import_plugin'); ?></p></div>
				
				<?php
				$response = array(		
					'success' => false,
					'errors'  => ob_get_clean()
				);
			}
			else {
				$response['upload_result'] = $upload_result;			
				$response['filesize'] = filesize($upload_result['filePath']);
			}
		}
	} 	

	exit( json_encode($response) );
}