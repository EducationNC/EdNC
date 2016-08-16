<?php

class PMXE_XMLWriter extends XMLWriter
{

	public function putElement( $ns, $element, $uri, $value )
	{
		if (empty($ns))
		{
			return $this->writeElement( $element, $value );
		}
		else
		{
			return $this->writeElementNS( $ns, $element, $uri, $value );
		}
	}

	public function beginElement($ns, $element, $uri)
	{
		if (empty($ns))
		{
			return $this->startElement( $element );
		}
		else
		{
			return $this->startElementNS( $ns, $element, $uri );
		}
	}

	public function writeData( $value, $element_name )
	{
		$is_wrap_into_cdata = ! ( empty($value) or is_numeric($value) );
		$wrap_value_into_cdata = apply_filters('wp_all_export_is_wrap_value_into_cdata', $is_wrap_into_cdata, $value, $element_name);
		if ( $wrap_value_into_cdata === false ) $this->text($value); else $this->writeCData($value);
	}
	
} 