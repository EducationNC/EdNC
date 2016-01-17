<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.mailmunch.co
 * @since      2.0.0
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/admin/partials
 */
?>

<form id="unlink-account" action="<?php echo add_query_arg( array('step' => 'sign_out') ); ?>" method="POST"></form>

<?php echo $this->mailmunch_api->getWidgetsHtml(); ?>
