<?php 
/**
 * Admin Statistics page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMXI_Admin_Settings extends PMXI_Controller_Admin {

	public static $path;

	public static $upload_transient;

	public function __construct(){	

		parent::__construct();

		self::$upload_transient = 'pmxi_uploads_path';		

		$uploads = wp_upload_dir();	

		$is_secure_import = PMXI_Plugin::getInstance()->getOption('secure');
		
		if ( ! $is_secure_import ){

			self::$path = pmxi_secure_file($uploads['basedir'] . '/wpallimport/uploads', 'uploads');
			
		}
		else {			

			self::$path = get_transient( self::$upload_transient );

			if ( empty(self::$path) ) {
				self::$path = pmxi_secure_file($uploads['basedir'] . '/wpallimport/uploads', 'uploads');
				set_transient( self::$upload_transient, self::$path);
			}

		}

	}
	
	public function index() {

		$this->data['post'] = $post = $this->input->post(PMXI_Plugin::getInstance()->getOption());
		
		if ($this->input->post('is_settings_submitted')) { // save settings form
			check_admin_referer('edit-settings', '_wpnonce_edit-settings');
			
			if ( ! preg_match('%^\d+$%', $post['history_file_count'])) {
				$this->errors->add('form-validation', __('History File Count must be a non-negative integer', 'pmxi_plugin'));
			}
			if ( ! preg_match('%^\d+$%', $post['history_file_age'])) {
				$this->errors->add('form-validation', __('History Age must be a non-negative integer', 'pmxi_plugin'));
			}
			if (empty($post['html_entities'])) $post['html_entities'] = 0;
			if (empty($post['utf8_decode'])) $post['utf8_decode'] = 0;
			
			if ( ! $this->errors->get_error_codes()) { // no validation errors detected

				PMXI_Plugin::getInstance()->updateOption($post);
				$files = new PMXI_File_List(); $files->sweepHistory(); // adjust file history to new settings specified
				
				wp_redirect(add_query_arg('pmxi_nt', urlencode(__('Settings saved', 'pmxi_plugin')), $this->baseUrl)); die();
			}
		}
		
		if ($this->input->post('is_templates_submitted')) { // delete templates form

			if ($this->input->post('import_templates')){

				if (!empty($_FILES)){
					$file_name = $_FILES['template_file']['name'];
					$file_size = $_FILES['template_file']['size'];
					$tmp_name  = $_FILES['template_file']['tmp_name'];										
					
					if(isset($file_name)) 
					{				
						
						$filename  = stripslashes($file_name);
						$extension = strtolower(pmxi_getExtension($filename));
										
						if (($extension != "txt")) 
						{							
							$this->errors->add('form-validation', __('Unknown File extension. Only txt files are permitted', 'pmxi_plugin'));
						}
						else {
							$import_data = @file_get_contents($tmp_name);
							if (!empty($import_data)){
								$templates_data = json_decode($import_data, true);
								
								if (!empty($templates_data)){
									$template = new PMXI_Template_Record();
									foreach ($templates_data as $template_data) {
										unset($template_data['id']);
										$template->clear()->set($template_data)->insert();
									}
									wp_redirect(add_query_arg('pmxi_nt', urlencode(sprintf(_n('%d template imported', '%d templates imported', count($templates_data), 'pmxi_plugin'), count($templates_data))), $this->baseUrl)); die();
								}
								else $this->errors->add('form-validation', __('Wrong imported data format', 'pmxi_plugin'));							
							}
							else $this->errors->add('form-validation', __('File is empty or doesn\'t exests', 'pmxi_plugin'));
						}
					}
					else $this->errors->add('form-validation', __('Undefined entry!', 'pmxi_plugin'));
				}
				else $this->errors->add('form-validation', __('Please select file.', 'pmxi_plugin'));

			}
			else{
				$templates_ids = $this->input->post('templates', array());
				if (empty($templates_ids)) {
					$this->errors->add('form-validation', __('Templates must be selected', 'pmxi_plugin'));
				}
				
				if ( ! $this->errors->get_error_codes()) { // no validation errors detected
					if ($this->input->post('delete_templates')){
						$template = new PMXI_Template_Record();
						foreach ($templates_ids as $template_id) {
							$template->clear()->set('id', $template_id)->delete();
						}
						wp_redirect(add_query_arg('pmxi_nt', urlencode(sprintf(_n('%d template deleted', '%d templates deleted', count($templates_ids), 'pmxi_plugin'), count($templates_ids))), $this->baseUrl)); die();
					}
					if ($this->input->post('export_templates')){
						$export_data = array();
						$template = new PMXI_Template_Record();
						foreach ($templates_ids as $template_id) {
							$export_data[] = $template->clear()->getBy('id', $template_id)->toArray(TRUE);
						}	
						
						$uploads = wp_upload_dir();
						$targetDir = $uploads['basedir'] . '/wpallimport/uploads';
						$export_file_name = "templates_".uniqid().".txt";
						file_put_contents($targetDir . DIRECTORY_SEPARATOR . $export_file_name, json_encode($export_data));
						
						PMXI_download::csv($targetDir . DIRECTORY_SEPARATOR . $export_file_name);
						
					}				
				}
			}
		}
		
		$this->render();
	}
	
	public function cleanup(){

		$removedFiles = 0;

		$wp_uploads = wp_upload_dir();

		$dir = $wp_uploads['basedir'] . '/wpallimport/temp';

		$cacheDir = PMXI_Plugin::ROOT_DIR . '/libraries/cache';

		$files = array_diff(@scandir($dir), array('.','..'));

		$cacheFiles = array_diff(@scandir($cacheDir), array('.','..'));

		$msg = __('Files not found', 'pmxi_plugin');

		if ( count($files) or count($cacheFiles)){

			pmxi_clear_directory( $dir );

			pmxi_clear_directory( $cacheDir );		

			$msg = __('Clean Up has been successfully completed.', 'pmxi_plugin');
		}

		wp_redirect(add_query_arg('pmxi_nt', urlencode($msg), $this->baseUrl)); die();
	}

	public function dismiss(){

		PMXI_Plugin::getInstance()->updateOption("dismiss", 1);

		exit('OK');
	}
	
	public function meta_values(){

		global $wpdb;

		$meta_key = $_POST['key'];

		$r = $wpdb->get_results("
			SELECT DISTINCT postmeta.meta_value
			FROM ".$wpdb->postmeta." as postmeta
			WHERE postmeta.meta_key='".$meta_key."' LIMIT 0,10
		", ARRAY_A);		

		$meta_values = array();
		
		if ( ! empty($r) ){
			foreach ($r as $key => $value) { if (empty($value['meta_value'])) continue;
				$meta_values[] = esc_html($value['meta_value']);
			}
		}

		exit( json_encode(array('meta_values' => $meta_values)) );
	}

	/**
	 * upload.php
	 *
	 * Copyright 2009, Moxiecode Systems AB
	 * Released under GPL License.
	 *
	 * License: http://www.plupload.com/license
	 * Contributing: http://www.plupload.com/contributing
	 */
	public function upload(){	
		
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// Settings
		//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		//$uploads = wp_upload_dir();	

		$targetDir = self::$path;//pmxi_secure_file($uploads['basedir'] . '/wpallimport/uploads', 'uploads');

		if (! is_dir($targetDir) || ! is_writable($targetDir)){
			delete_transient( self::$upload_transient );
			exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 100, "message" => __("Uploads folder is not writable.", "pmxi_plugin")), "id" => "id")));
		}

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);

		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		} else{
			delete_transient( self::$upload_transient );
			exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 100, "message" => __("Failed to open temp directory.", "pmxi_plugin")), "id" => "id")));
		}
			

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else{
						delete_transient( self::$upload_transient );
						exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 101, "message" => __("Failed to open input stream.", "pmxi_plugin")), "id" => "id")));
					}
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else{
					delete_transient( self::$upload_transient );					
					exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 102, "message" => __("Failed to open output stream.", "pmxi_plugin")), "id" => "id")));
				}
			} else{
				delete_transient( self::$upload_transient );
				exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 103, "message" => __("Failed to move uploaded file.", "pmxi_plugin")), "id" => "id")));
			}
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else{
					delete_transient( self::$upload_transient );
					exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 101, "message" => __("Failed to open input stream.", "pmxi_plugin")), "id" => "id")));
				}

				fclose($in);
				fclose($out);
			} else{
				delete_transient( self::$upload_transient );
				exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 102, "message" => __("Failed to open output stream.", "pmxi_plugin")), "id" => "id")));
			}
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath); chmod($filePath, 0755);
			delete_transient( self::$upload_transient );

			$errors = new WP_Error;

			$uploader = new PMXI_Upload($filePath, $errors, rtrim(str_replace(basename($filePath), '', $filePath), '/'));			
			
			$upload_result = $uploader->upload();			
			
			if ($upload_result instanceof WP_Error){
				$errors = $upload_result;

				$msgs = $errors->get_error_messages();
				ob_start();
				?>
				<?php foreach ($msgs as $msg): ?>
					<div class="error inline"><p><?php echo $msg ?></p></div>
				<?php endforeach ?>
				<?php
				$response = ob_get_clean();

				exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 102, "message" => $response), "id" => "id")));
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
					
					<div class="error inline"><p><?php _e('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@wpallimport.com">support@wpallimport.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'pmxi_plugin'); ?></p></div>
					
					<?php
					$response = ob_get_clean();
					exit(json_encode(array("jsonrpc" => "2.0", "error" => array("code" => 102, "message" => $response), "id" => "id")));
				}
				
			}		

		}			

		// Return JSON-RPC response
		exit(json_encode(array("jsonrpc" => "2.0", "error" => null, "result" => null, "id" => "id", "name" => $filePath)));

	}		

	public function download(){
		PMXI_download::csv(PMXI_Plugin::ROOT_DIR.'/logs/'.$_GET['file'].'.txt');
	}

}