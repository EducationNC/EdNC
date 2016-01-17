<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.mailmunch.co
 * @since      2.0.0
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Mailmunch
 * @subpackage Mailmunch/includes
 * @author     MailMunch <info@mailmunch.co>
 */
class Mailmunch_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
    update_option('mailmunch_activation_redirect', 'true');
	}

}
