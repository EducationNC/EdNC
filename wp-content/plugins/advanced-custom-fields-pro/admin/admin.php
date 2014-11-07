<?php 

class acf_admin {
	
	/*
	*  __construct
	*
	*  Initialize filters, action, variables and includes
	*
	*  @type	function
	*  @date	23/06/12
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
	
		// actions
		add_action( 'admin_menu', 				array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts',	array( $this, 'admin_enqueue_scripts') );
		add_action( 'admin_notices', 			array( $this, 'admin_notices') );
	}
	
	
	/*
	*  admin_menu
	*
	*  This function will add the ACF menu item to the WP admin
	*
	*  @type	action (admin_menu)
	*  @date	28/09/13
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function admin_menu() {
		
		// bail early if no show_admin
		if( !acf_get_setting('show_admin') )
		{
			return;
		}
		
		
		add_menu_page(__("Custom Fields",'acf'), __("Custom Fields",'acf'), 'manage_options', 'edit.php?post_type=acf-field-group', false, false, '80.025');
		
	}
	
	
	/*
	*  admin_enqueue_scripts
	*
	*  This function will add the already registered css
	*
	*  @type	function
	*  @date	28/09/13
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function admin_enqueue_scripts() {
		
		wp_enqueue_style( 'acf-global' );
		wp_enqueue_style( 'acf-input' );
	}
	
	
	/*
	*  admin_notices
	*
	*  This function will render any admin notices
	*
	*  @type	function
	*  @date	17/10/13
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function admin_notices()
	{
		// vars
		$admin_notices = acf_get_admin_notices();
		
		if( !empty($admin_notices) )
		{
			foreach( $admin_notices as $notice )
			{
				$open = '';
				$close = '';
				
				if( $notice['wrap'] )
				{
					$open = "<{$notice['wrap']}>";
					$close = "</{$notice['wrap']}>";
				}
				
				?>
			    <div class="<?php echo $notice['class']; ?>">
			        <?php echo $open . $notice['text'] . $close; ?>
			    </div>
			    <?php
			}
		}
		
	}
	
}


// initialize
new acf_admin();

?>
