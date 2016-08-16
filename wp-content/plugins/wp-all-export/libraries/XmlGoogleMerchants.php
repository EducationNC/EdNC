<?php

final class XmlGoogleMerchants
{
	private $export_id = false;
	private $add_data  = array();

	public function __construct( $id, $additional_data ) 
	{
		$this->export_id = $id;
		$this->add_data  = $additional_data;

		if ( ! empty($this->export_id))
		{
			add_filter('wp_all_export_xml_header', array( &$this, 'wpae_xml_header'), 10, 2);
			add_filter('wp_all_export_additional_data', array( &$this, 'wpae_additional_data'), 10, 3);
			add_filter('wp_all_export_xml_footer', array( &$this, 'wpae_xml_footer'), 10, 2);
			add_filter('wp_all_export_main_xml_tag', array( &$this, 'wpae_main_xml_tag'), 10, 2);
			add_filter('wp_all_export_record_xml_tag', array( &$this, 'wpae_record_xml_tag'), 10, 2);
		}
	}

	public function wpae_xml_header($header, $export_id)
	{	
		if ( $export_id == $this->export_id )
		{
			$header .= "\n<rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">";
		}
		return $header;
	}

	public function wpae_additional_data($add_data, $options, $export_id)
	{
		if ( $export_id == $this->export_id && ! empty($this->add_data))
		{
			$add_data = array_merge($add_data, $this->add_data);			
		}
		return $add_data;
	} 

	public function wpae_xml_footer($footer, $export_id)
	{			
		if ( $export_id == $this->export_id )
		{
			$footer = "</rss>";
		}
		return $footer;
	}

	public function wpae_main_xml_tag( $tag, $export_id )
	{
		return ( $export_id == $this->export_id ) ? 'channel' : $tag;
	}

	public function wpae_record_xml_tag( $tag, $export_id )
	{
		return ( $export_id == $this->export_id ) ? 'item' : $tag;
	}
}