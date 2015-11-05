<?php
/*
* Tiny Compress Images - WordPress plugin.
* Copyright (C) 2015 Voormedia B.V.
*
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the Free
* Software Foundation; either version 2 of the License, or (at your option)
* any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
* FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
* more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc., 51
* Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

abstract class Tiny_WP_Base {
    const NAME = 'tiny-compress-images';
    const PREFIX = 'tinypng_';

    private static $wp_version;
    private static $plugin_version;

    public static function wp_version() {
        if (is_null(self::$wp_version)) {
            // Try to use unmodified version
            include( ABSPATH . WPINC . '/version.php' );
            if (isset($wp_version)) {
                self::$wp_version = $wp_version;
            } else {
                self::$wp_version = $GLOBALS['wp_version'];
            }
        }
        return self::$wp_version;
    }

    public static function check_wp_version($version) {
        return floatval(self::wp_version()) >= $version;
    }

    public static function plugin_version() {
        if (is_null(self::$plugin_version)) {
            $plugin_data = get_plugin_data(dirname(__FILE__) . '/../tiny-compress-images.php');
            self::$plugin_version = $plugin_data['Version'];
        }
        return self::$plugin_version;
    }

    public static function plugin_identification() {
        return 'Wordpress/' . self::wp_version() . ' Tiny/' . self::plugin_version();
    }

    protected static function get_prefixed_name($name) {
        return self::PREFIX . $name;
    }

    protected static function translate($phrase) {
        return translate($phrase, self::NAME);
    }

    protected static function translate_escape($phrase) {
        return htmlspecialchars(translate($phrase, self::NAME));
    }

    public function __construct() {
        add_action('init', $this->get_method('init'));
        if (is_admin()) {
            add_action('admin_init', $this->get_method('admin_init'));
        }
    }

    protected function get_method($name) {
        return array($this, $name);
    }

    protected function get_static_method($name) {
        return array(get_class($this), $name);
    }

    protected function get_user_id() {
        return get_current_user_id();
    }

    protected function check_ajax_referer() {
        return check_ajax_referer('tiny-compress', '_nonce', false);
    }

    public function init() {
    }

    public function admin_init() {
    }
}
