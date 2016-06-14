<?php
/*
Plugin Name: Canva
Version: 1.0.2
Plugin URI: https://www.canva.com
Description: Utilise the full features of Canva directly in the edit screen of a page, post and custom post type.
Author: Canva
Author URI: https://www.canva.com
Text Domain: canva
Domain Path: /languages/
License: GPL v3

Canva Media Plugin
Copyright (C) 2014, Canva - info@canva.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'CANVA_URL' ) ) {
	define( 'CANVA_URL', plugins_url('', __FILE__) );
}

if ( ! defined( 'CANVA_PATH' ) ) {
	define( 'CANVA_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CANVA_BASENAME' ) ) {
	define( 'CANVA_BASENAME', plugin_basename( __FILE__ ) );
}

// Load Canva
require_once( CANVA_PATH . 'inc/media.php' );