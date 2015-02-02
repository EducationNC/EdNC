<?php
/*
Plugin Name: WPEngine Why 404?
Description: WPE 404 logger
*/
#ini_set('display_errors',1);
if( !class_exists('WPE_404_Logger') ) {

  class WPE_404_Logger {

    public $_dir = '';
    public static $instance = false;
    public $logitem = '';
    public $datetime = '';


    function __construct() {
      $this->instance = $this;
      $this->_dir = rtrim(ABSPATH,'/').'/404-logs';
      add_action('wp',array($this,'sniffer'),1000);
    }

    public static function instance() {
      if( self::$instance )
        return self::$instance;

      self::$instance = new WPE_404_Logger();
    }

    function setupDir() {
      if(!is_dir($this->_dir) )
        @mkdir($this->_dir);

      if(!is_writable($this->_dir))
        @chmod($this->_dir, '0775');

      if(!file_exists($this->_dir.'/log.txt')) {
        @touch($this->_dir.'/log.txt');
        @chmod($this->_dir.'/log.txt', '0755');
      }

    }

    function getDir() {
      $dir = scandir($this->_dir);
      print_r($dir);
    }

    function sniffer() {
      global $wp,$wp_query;
      if(!$wp_query->is_404) return;
      $this->setupDir();

      $this->datetime = date('M-d-y h:i:s a');
      $this->logitem = $this->datetime." | ";
      $raw = $_SERVER['REQUEST_URI'];
      $this->logitem .= "RAW:$raw | WP: query_string:".$wp->query_string." request: ".$wp->request." matched_rule: ".$wp->matched_rule." matched_query: ".$wp->matched_query."\n";
      @file_put_contents($this->_dir.'/log.txt',$this->logitem, FILE_APPEND);
    }

  }

  function killLog() {
    unlink($this->_dir.'/log.txt');
  }

}
