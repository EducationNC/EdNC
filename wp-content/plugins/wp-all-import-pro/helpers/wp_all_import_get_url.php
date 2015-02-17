<?php
/**
 * Reading large files from remote server
 * @ $filePath - file URL
 * return local path of copied file
 */

if ( ! function_exists('wp_all_import_get_url')){

	function wp_all_import_get_url($filePath, $targetDir = false, $contentType = false, $contentEncoding = false, $detect = false){
		
		$type = $contentType;

		$uploads = wp_upload_dir();
		
		$targetDir = ( ! $targetDir ) ? wp_all_import_secure_file($uploads['basedir'] . '/wpallimport/uploads', 'uploads') : $targetDir;

		$tmpname   = wp_unique_filename($targetDir, ($type and strlen(basename($filePath)) < 30) ? basename($filePath) : time());	
		$localPath = $targetDir  .'/'. urldecode(sanitize_file_name($tmpname)) . ((!$type) ? '.tmp' : '');							

		if ( $contentEncoding == 'gzip'){
			$file = @fopen($filePath);
		}
		else{
			$file = @fopen($filePath, "rb");
		}

   		if (is_resource($file)){   				   			
   			
   			$fp = @fopen($localPath, 'w');
		   	$first_chunk = true;
			while ( ! @feof($file) ) {
				$chunk = @fread($file, 1024);
				if (!$type and $first_chunk and (strpos($chunk, "<?") !== false or strpos($chunk, "<rss") !== false) or strpos($chunk, "xmlns") !== false) $type = 'xml'; elseif (!$type and $first_chunk) $type = 'csv'; // if it's a 1st chunk, then chunk <? symbols to detect XML file
				$first_chunk = false;
			 	@fwrite($fp, $chunk);		 	
			}
			@fclose($file);
			@fclose($fp); 	   	
			
		}									
		
	   	if ( ! file_exists($localPath) ) {
	   		
	   		$request = get_file_curl($filePath, $localPath);
	   		
	   		if ( ! is_wp_error($request) ){
		   		
	   			if ( ! $type ){
	   				if ( $contentEncoding == 'gzip'){
		   				$file = @fopen($localPath);
		   			}
		   			else{
						$file = @fopen($localPath, "rb");
					}					   		 		
					while (!@feof($file)) {
						$chunk = @fread($file, 1024);					
						if (strpos($chunk, "<?") !== false or strpos($chunk, "<rss") !== false or strpos($chunk, "xmlns") !== false) $type = 'xml'; else $type = 'csv'; // if it's a 1st chunk, then chunk <? symbols to detect XML file					
					 	break;		 	
					}
					@fclose($file);	
				}				
			}
			else return $request;
	   		
	   	} 	

	   	if ( ! preg_match('%\W('. $type .')$%i', basename($localPath)) ){
			if (@rename($localPath, $localPath . '.' . $type))
		    	$localPath = $localPath . '.' . $type;
		}
		
		return ($detect) ? array('type' => $type, 'localPath' => $localPath) : $localPath;
	}
}	