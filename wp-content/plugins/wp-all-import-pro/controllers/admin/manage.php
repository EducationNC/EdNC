<?php 
/**
 * Manage Imports
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMXI_Admin_Manage extends PMXI_Controller_Admin {
	
	public function init() {
		parent::init();
		
		if ('update' == PMXI_Plugin::getInstance()->getAdminCurrentScreen()->action) {
			$this->isInline = true;			
		}
	}
	
	/**
	 * Previous Imports list
	 */
	public function index() {		
		
		$get = $this->input->get(array(
			's' => '',
			'order_by' => 'registered_on',
			'order' => 'DESC',
			'pagenum' => 1,
			'perPage' => 25,
		));
		$get['pagenum'] = absint($get['pagenum']);
		extract($get);
		$this->data += $get;
		
		$list = new PMXI_Import_List();
		$post = new PMXI_Post_Record();
		$by = array('parent_import_id' => 0);
		if ('' != $s) {
			$like = '%' . preg_replace('%\s+%', '%', preg_replace('/[%?]/', '\\\\$0', $s)) . '%';
			$by[] = array(array('name LIKE' => $like, 'type LIKE' => $like, 'path LIKE' => $like, 'friendly_name LIKE' => $like), 'OR');
		}
		
		$this->data['list'] = $list->join($post->getTable(), $list->getTable() . '.id = ' . $post->getTable() . '.import_id', 'LEFT')
			->setColumns(
				$list->getTable() . '.*',
				'COUNT(' . $post->getTable() . '.post_id' . ') AS post_count'
			)
			->getBy($by, "$order_by $order", $pagenum, $perPage, $list->getTable() . '.id');
			
		$this->data['page_links'] = paginate_links(array(
			'base' => add_query_arg('pagenum', '%#%', $this->baseUrl),
			'format' => '',
			'prev_text' => __('&laquo;', 'pmxi_plugin'),
			'next_text' => __('&raquo;', 'pmxi_plugin'),
			'total' => ceil($list->total() / $perPage),
			'current' => $pagenum,
		));
		
		//pmxi_session_unset();		
		PMXI_Plugin::$session->clean_session();

		$this->render();
	}
	
	/**
	 * Edit Template
	 */
	public function edit() {				

		// deligate operation to other controller
		$controller = new PMXI_Admin_Import();
		$controller->set('isTemplateEdit', true);
		$controller->template();
	}
	
	/**
	 * Edit Options
	 */
	public function options() {		
		
		// deligate operation to other controller
		$controller = new PMXI_Admin_Import();
		$controller->set('isTemplateEdit', true);
		$controller->options();
	}

	/**
	 * Cron Scheduling
	 */
	public function scheduling() {
		$this->data['id'] = $id = $this->input->get('id');
		$this->data['cron_job_key'] = PMXI_Plugin::getInstance()->getOption('cron_job_key');
		$this->data['item'] = $item = new PMXI_Import_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl); die();
		}

		$this->render();
	}

	/**
	 * Cancel import processing
	 */
	public function cancel(){
		
		$id = $this->input->get('id');
		
		PMXI_Plugin::$session->clean_session( $id );

		$item = new PMXI_Import_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl); die();
		}
		$item->set(array(
			'triggered'   => 0,
			'processing'  => 0,
			'executing'   => 0,
			'canceled'    => 1,
			'canceled_on' => date('Y-m-d H:i:s')
		))->update();		

		wp_redirect(add_query_arg('pmxi_nt', urlencode(__('Import canceled', 'pmxi_plugin')), $this->baseUrl)); die();
	}
	
	/**
	 * Re-run import
	 */
	public function update() {

		$id = $this->input->get('id');
		
		PMXI_Plugin::$session->clean_session( $id );
		
		$action_type = false;

		$this->data['import'] = $item = new PMXI_Import_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl); die();
		}				

		$this->data['isWizard'] = false;

		$default = PMXI_Plugin::get_default_import_options();

		$DefaultOptions = $item->options + $default;
		foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
			if (class_exists($class)) $DefaultOptions += call_user_func(array($class, "get_default_import_options"));			
		}		

		$this->data['post'] =& $DefaultOptions;	

		$this->data['source'] = array(
			'path' => $item->path,
			'root_element' => $item->root_element			
		);

		$this->data['xpath'] = $item->xpath;
		$this->data['count'] = $item->count;	
		
		$history = new PMXI_File_List();
		$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $item->id), 'id DESC');				
		if ($history->count()){
			foreach ($history as $file){						
				if (@file_exists($file['path'])) {
					$this->data['locfilePath'] = $file['path'];
					break;
				}				
			}
		}							

		$chunks = 0;
		
		if ( ($this->input->post('is_confirmed') and check_admin_referer('confirm', '_wpnonce_confirm')) ) {
			
			$continue = $this->input->post('is_continue', 'no');

			// mark action type ad continue
			if ($continue == 'yes') $action_type = 'continue';						
			
			$filePath = '';
			
			// upload new file in case when import is not continue			
			if ( empty(PMXI_Plugin::$session->chunk_number) ) {			
								
				if ($item->type == 'url'){ // up to date the file from URL
					
					$uploader = new PMXI_Upload(trim($item->path), $this->errors);			
					$upload_result = $uploader->url($item->feed_type);
					if ($upload_result instanceof WP_Error)
						$this->errors = $upload_result;					
					else
						$filePath  = $upload_result['filePath'];									
				} 
				elseif ( $item->type == 'file' ) { // copy file from /uploads/wpallimport folder

					$uploader = new PMXI_Upload(trim(basename($item->path)), $this->errors);			
					$upload_result = $uploader->file();					
					if ($upload_result instanceof WP_Error)
						$this->errors = $upload_result;					
					else										
						$filePath  = $upload_result['filePath'];												
				} 
				elseif ( ! in_array($item->type, array('ftp'))){ // retrieve already uploaded file

					$uploader = new PMXI_Upload(trim($item->path), $this->errors, rtrim(str_replace(basename($item->path), '', $item->path), '/'));			
					$upload_result = $uploader->upload();					
					if ($upload_result instanceof WP_Error)
						$this->errors = $upload_result;					
					else						
						$filePath  = $upload_result['filePath'];						
				}	

				if (empty($item->options['encoding'])){
					$currentOptions = $item->options;
					$currentOptions['encoding'] = 'UTF-8';
					$item->set(array(
						'options' => $currentOptions
					))->update();
				}			

				@set_time_limit(0);

				$local_paths = ( ! empty($local_paths) ) ? $local_paths : array($filePath);								

				foreach ($local_paths as $key => $path) {

					if (!empty($action_type) and $action_type == 'continue'){
						$chunks = $item->count;							
					}
					else{

						$file = new PMXI_Chunk($path, array('element' => $item->root_element, 'encoding' => $item->options['encoding']));					
				    						    
					    while ($xml = $file->read()) {					      						    					    					    	
					    	
					    	if ( ! empty($xml) )
					      	{												      		
					      		PMXI_Import_Record::preprocessXml($xml);	
					      		$xml = "<?xml version=\"1.0\" encoding=\"". $item->options['encoding'] ."\"?>" . "\n" . $xml;					      		      						      							      					      	
						      					      		
						      	$dom = new DOMDocument('1.0', ( ! empty($item->options['encoding']) ) ? $item->options['encoding'] : 'UTF-8');															
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load							
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);
								if (($elements = @$xpath->query($item->xpath)) and !empty($elements) and !empty($elements->length)) $chunks += $elements->length;
								unset($dom, $xpath, $elements);										
						    }
						}	
						unset($file);
					}
														
					!$key and $filePath = $path;					
				}				

				if (empty($chunks)) 
					$this->errors->add('form-validation', __('No matching elements found for Root element and XPath expression specified', 'pmxi_plugin'));						
																		   							
			}							
			
			if ( $chunks ) { // xml is valid						
				
				if ( ! PMXI_Plugin::is_ajax() and empty(PMXI_Plugin::$session->chunk_number)){

					// compose data to look like result of wizard steps				
					$sesson_data = array(						
						'filePath' => $filePath,
						'source' => array(
							'name' => $item->name,
							'type' => $item->type,						
							'path' => $item->path,
							'root_element' => $item->root_element,
						),
						'feed_type' => $item->feed_type,
						'update_previous' => $item->id,
						'parent_import_id' => $item->parent_import_id,
						'xpath' => $item->xpath,						
						'options' => $item->options,
						'encoding' => (!empty($item->options['encoding'])) ? $item->options['encoding'] : 'UTF-8',
						'is_csv' => (!empty($item->options['delimiter'])) ? $item->options['delimiter'] : PMXI_Plugin::$is_csv,
						'csv_path' => PMXI_Plugin::$csv_path,																		
						'chunk_number' => 1,						
						'log' => '',						
						'warnings' => 0,
						'errors' => 0,
						'start_time' => 0,
						'pointer' => 1,
						'count' => (isset($chunks)) ? $chunks : 0,
						'local_paths' => (!empty($local_paths)) ? $local_paths : array(), // ftp import local copies of remote files
						'action' => (!empty($action_type) and $action_type == 'continue') ? 'continue' : 'update',					
					);										
					
					foreach ($sesson_data as $key => $value) {
						PMXI_Plugin::$session->set($key, $value);
					}

					PMXI_Plugin::$session->save_data();
					
				}

				$item->set(array('canceled' => 0, 'failed' => 0))->update();

				// deligate operation to other controller
				$controller = new PMXI_Admin_Import();
				$controller->data['update_previous'] = $item;
				$controller->process();
				return;
			}
		}		

		$this->render('admin/import/confirm');
	}
	
	/**
	 * Delete an import
	 */
	public function delete() {
		$id = $this->input->get('id');
		$this->data['item'] = $item = new PMXI_Import_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl); die();
		}
		
		if ($this->input->post('is_confirmed')) {
			check_admin_referer('delete-import', '_wpnonce_delete-import');
			
			do_action('pmxi_before_import_delete', $item, $this->input->post('is_delete_posts'));

			$item->delete( ! $this->input->post('is_delete_posts'));
			wp_redirect(add_query_arg('pmxi_nt', urlencode(__('Import deleted', 'pmxi_plugin')), $this->baseUrl)); die();
		}
		
		$this->render();
	}
	
	/**
	 * Bulk actions
	 */
	public function bulk() {
		check_admin_referer('bulk-imports', '_wpnonce_bulk-imports');
		if ($this->input->post('doaction2')) {
			$this->data['action'] = $action = $this->input->post('bulk-action2');
		} else {
			$this->data['action'] = $action = $this->input->post('bulk-action');
		}		
		$this->data['ids'] = $ids = $this->input->post('items');
		$this->data['items'] = $items = new PMXI_Import_List();
		if (empty($action) or ! in_array($action, array('delete')) or empty($ids) or $items->getBy('id', $ids)->isEmpty()) {
			wp_redirect($this->baseUrl); die();
		}
		
		if ($this->input->post('is_confirmed')) {
			$is_delete_posts = $this->input->post('is_delete_posts');
			foreach($items->convertRecords() as $item) {
				$item->delete( ! $is_delete_posts);
			}
			
			wp_redirect(add_query_arg('pmxi_nt', urlencode(sprintf(__('<strong>%d</strong> %s deleted', 'pmxi_plugin'), $items->count(), _n('import', 'imports', $items->count(), 'pmxi_plugin'))), $this->baseUrl)); die();
		}
		
		$this->render();
	}
	
}