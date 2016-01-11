<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.mailmunch.co
 * @since      2.0.0
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Mailmunch
 * @subpackage Mailmunch/includes
 * @author     MailMunch <info@mailmunch.co>
 */
class Mailmunch_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function deactivate() {
    update_option('mailmunch_activation_redirect', 'true');
	}

}
