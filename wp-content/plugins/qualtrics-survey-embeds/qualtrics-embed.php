<?php 
/*
Plugin Name: Qualtrics Survey Embeds
Description: Adds a Qualtrics Embed Handler to WordPress allowing for quick survey embeds. 
Plugin URI: https://github.com/michaelryanmcneill/qualtrics-embed/
Author: Michael McNeill (webdotunc)
Version: 1.0
Author URI: http://michaelryanmcneill.com
License: MIT License - http://opensource.org/licenses/MIT
*/

class QSEmbed {

    private $plugin_path;
    private $qse;

    function __construct()
    {
        $this->plugin_path = plugin_dir_path( __FILE__ );
        add_action( 'admin_menu', array(&$this, 'admin_menu'), 99 );
        require_once( $this->plugin_path .'inc/options.php' );
        require_once( $this->plugin_path .'inc/embed.php' );
        $this->qse = new QSEmbedSettings( $this->plugin_path .'inc/settings/default.php', 'qse_settings' );
        add_filter( $this->qse->get_option_group() .'_settings_validate', array(&$this, 'validate_settings') );
    }

    function admin_menu()
    {
        add_options_page( __( 'Qualtrics Settings', 'qse' ), __( 'Qualtrics Settings', 'qse' ), 'manage_options', 'qse', array(&$this, 'settings_page') );
    }

    function settings_page()
	{
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Qualtrics Survey Embeds</h2>
			<?php
			$this->qse->settings();
			?>
		</div>
		<?php
	}

	function validate_settings( $input )
	{
    	return $input;
	}

}
new QSEmbed();
?>
