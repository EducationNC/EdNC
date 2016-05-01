<?php
/*
 * Plugin Name: Oembed Cache
 * Version: 1.0
 * Plugin URI: 
 * Description: Adds an admin page where you can change oEmbed cache related settings and clear cached embed content. 
 * Author: 61 Degrees North
 * Author URI: http://www.61degrees.se/
 */

/*** load translation ***/
load_plugin_textdomain('emdcache', false, dirname(plugin_basename( __FILE__ )) . '/languages/' );

require_once 'filehandler.class.php';
require_once 'adminpage.class.php';

//create instances
new Sixtyonedegrees\plugins\oembedCache\AdminPage();
new Sixtyonedegrees\plugins\oembedCache\FilterHandler();