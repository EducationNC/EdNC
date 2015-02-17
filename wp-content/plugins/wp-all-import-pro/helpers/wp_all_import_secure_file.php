<?php
if ( ! function_exists('wp_all_import_secure_file') ){

	function wp_all_import_secure_file( $targetDir, $folder = 'temp', $importID = false){

		$is_secure_import = PMXI_Plugin::getInstance()->getOption('secure');

		if ( $is_secure_import ){

			$wp_uploads = wp_upload_dir();

			$dir = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . 'wpallimport' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . ( ( $importID ) ? md5($importID) : md5(time()) );							

			@mkdir($dir, 0755);

			if (@is_writable($dir) and @is_dir($dir)){
				$targetDir = $dir;	
				@touch( $dir . DIRECTORY_SEPARATOR . 'index.php' );
			}
			
		}

		return $targetDir;
	}
}	