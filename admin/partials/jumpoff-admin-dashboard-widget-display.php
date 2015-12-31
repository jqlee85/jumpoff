<?php

/**
 * Provide a dashboard widget for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://jessequinnlee.com
 * @since      1.0.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/admin/partials
 */


/*------------------------------------ Render JumpOff Page ------------------------------------*/

//echos main jumpoff page
function jumpoff_dashboard_widget() {
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div id="jo_wrapper">';
		
		echo '<h1>widget content here</h1>';

		

	echo '</div>';

}


jumpoff_dashboard_widget();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>