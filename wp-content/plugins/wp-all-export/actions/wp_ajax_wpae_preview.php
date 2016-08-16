<?php
/**
*	AJAX action for preview export row
*/
function pmxe_wp_ajax_wpae_preview(){

	if ( ! check_ajax_referer( 'wp_all_export_secure', 'security', false )){
		exit( json_encode(array('html' => __('Security check', 'wp_all_export_plugin'))) );
	}

	if ( ! current_user_can( PMXE_Plugin::$capabilities ) ){
		exit( json_encode(array('html' => __('Security check', 'wp_all_export_plugin'))) );
	}
	
	ob_start();

	$values = array();
	
	parse_str($_POST['data'], $values);	

	$export_id = (isset($_GET['id'])) ? stripcslashes($_GET['id']) : 0;

	$exportOptions = $values + (PMXE_Plugin::$session->has_session() ? PMXE_Plugin::$session->get_clear_session_data() : array()) + PMXE_Plugin::get_default_import_options();	

	$errors = new WP_Error();

	$engine = new XmlExportEngine($exportOptions, $errors);

	XmlExportEngine::$exportOptions     = $exportOptions;
	XmlExportEngine::$is_user_export    = $exportOptions['is_user_export'];
	XmlExportEngine::$is_comment_export = $exportOptions['is_comment_export'];
	XmlExportEngine::$exportID 			= $export_id;

	if ( 'advanced' == $exportOptions['export_type'] ) 
	{		
		if ( XmlExportEngine::$is_user_export )
		{
			$exportQuery = eval('return new WP_User_Query(array(' . $exportOptions['wp_query'] . ', \'offset\' => 0, \'number\' => 10));');
		}
		elseif ( XmlExportEngine::$is_comment_export )
		{
			$exportQuery = eval('return new WP_Comment_Query(array(' . $exportOptions['wp_query'] . ', \'offset\' => 0, \'number\' => 10));');
		}
		else
		{
			$exportQuery = eval('return new WP_Query(array(' . $exportOptions['wp_query'] . ', \'offset\' => 0, \'posts_per_page\' => 10));');
		}		
	}
	else
	{
		XmlExportEngine::$post_types = $exportOptions['cpt'];

		if ( in_array('users', $exportOptions['cpt']) or in_array('shop_customer', $exportOptions['cpt']))
		{									
			$exportQuery = new WP_User_Query( array( 'orderby' => 'ID', 'order' => 'ASC', 'number' => 10 ));			
		}
		elseif( in_array('comments', $exportOptions['cpt']))
		{									
			global $wp_version;					

			if ( version_compare($wp_version, '4.2.0', '>=') ) 
			{
				$exportQuery = new WP_Comment_Query( array( 'orderby' => 'comment_ID', 'order' => 'ASC', 'number' => 10 ));
			}
			else
			{
				$exportQuery = get_comments( array( 'orderby' => 'comment_ID', 'order' => 'ASC', 'number' => 10 ));
			}			
		}
		else
		{			
			remove_all_actions('parse_query');
			remove_all_actions('pre_get_posts');
			remove_all_filters('posts_clauses');			
			
			add_filter('posts_join', 'wp_all_export_posts_join', 10, 1);
			add_filter('posts_where', 'wp_all_export_posts_where', 10, 1);
			$exportQuery = new WP_Query( array( 'post_type' => $exportOptions['cpt'], 'post_status' => 'any', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => 10 ));
			remove_filter('posts_where', 'wp_all_export_posts_where');
			remove_filter('posts_join', 'wp_all_export_posts_join');					
		}
	}	

	XmlExportEngine::$exportQuery = $exportQuery;	

	?>

	<div id="post-preview" class="wpallexport-preview">

		<p class="wpallexport-preview-title"><?php echo sprintf("Preview first 10 %s", wp_all_export_get_cpt_name($exportOptions['cpt'], 10)); ?></p>
		
		<div class="wpallexport-preview-content">
			
		<?php
		$wp_uploads = wp_upload_dir();	
		
		$functions = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_EXPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
		if ( @file_exists($functions) )
			require_once $functions;

		switch ($exportOptions['export_to']) {

			case 'xml':				

				$dom = new DOMDocument('1.0', $exportOptions['encoding']);
				$old = libxml_use_internal_errors(true);							

				$xml = XmlCsvExport::export_xml( true );
				
				$dom->loadXML($xml);
				libxml_use_internal_errors($old);
				$xpath = new DOMXPath($dom);

				$main_xml_tag = apply_filters('wp_all_export_main_xml_tag', $exportOptions['main_xml_tag'], XmlExportEngine::$exportID);

				if (($elements = @$xpath->query('/' . $main_xml_tag)) and $elements->length){
					pmxe_render_xml_element($elements->item( 0 ), true);
				}			
													
				break;

			case 'csv':
				?>			
				<small>
				<?php					
					
					$csv = XmlCsvExport::export_csv( true );					

					if (!empty($csv)){
						$csv_rows = array_filter(explode("\n", $csv));
						if ($csv_rows){
							?>
							<table class="pmxe_preview" cellpadding="0" cellspacing="0">
							<?php
							foreach ($csv_rows as $rkey => $row) {							
								$cells = str_getcsv($row, $exportOptions['delimiter']);															
								if ($cells){
									?>
									<tr>
										<?php
										foreach ($cells as $key => $value) {
											?>
											<td>
												<?php if (!$rkey):?><strong><?php endif;?>
												<?php echo $value; ?>
												<?php if (!$rkey):?></strong><?php endif;?>
											</td>
											<?php
										}
										?>
									</tr>
									<?php
								}							
							}
							?>
							</table>
							<?php
						}						
					}
					else{
						_e('Data not found.', 'wp_all_export_plugin');
					}
				?>
				</small>			
				<?php
				break;

			default:

				_e('This format is not supported.', 'wp_all_export_plugin');

				break;
		}
		wp_reset_postdata();
		?>

		</div>

	</div>

	<?php

	exit(json_encode(array('html' => ob_get_clean()))); die;
}
