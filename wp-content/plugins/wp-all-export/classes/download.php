<?php

class PMXE_Download
{		
	
	static public function zip($file_name)
	{
		$uploads    = wp_upload_dir();
		$bundle_url = $uploads['baseurl'] . str_replace($uploads['basedir'], '', $file_name);
		wp_redirect($bundle_url);
		die;		
		header('Content-type: application/zip');
		header("Content-Disposition: attachment; filename=\"".basename($file_name)."\"");
		header('Content-Length: ' . filesize($file_name));
		readfile($file_name);		
		die;
	}

	static public function xls($file_name)
	{
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"".basename($file_name)."\"");
        readfile($file_name);
        die;
	}

	static public function csv($file_name)
	{
		header("Content-Type: text/plain; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"".basename($file_name)."\"");
        readfile($file_name);
        die;
	}

	static public function xml($file_name)
	{
		header("Content-Type: application/xhtml+xml; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"".basename($file_name)."\"");
        readfile($file_name);
        die;
	}	

}