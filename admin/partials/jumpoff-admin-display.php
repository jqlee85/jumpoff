<?php

/**
 * Provide a admin area view for the plugin
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
function jumpoff_page() {
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div id="jo_wrapper">';
		
		jo_display_flow_end_box();

		jo_display_prompt_box();
		
		jo_display_flow_box();

		jo_display_flow_counter();

	echo '</div>';

}

//display prompt box
function jo_display_prompt_box() {
	?>
	<div id="jo_prompt_times">
		<?php // CHANGE to customized elements with data attributes instead of radio buttons, make the length is the button, with a border or color change to indicate selected value ?>
		<div class="jo_prompt_time jo_checked" id="jo_prompt_time_20" data-value="20">20sec</div>
		<div class="jo_prompt_time" id="jo_prompt_time_60" data-value="60">1min</div>
		<div class="jo_prompt_time" id="jo_prompt_time_300" data-value="300">5min</div>	
		<div class="jo_prompt_time" id="jo_prompt_time_600" data-value="600">10min</div>
	</div>
	<div id="jo_prompt" class="jo_hide"></div>
	<input id="jo_prompt_me" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" value="Prompt Me"/>
	<?php
}

//display flow textarea
function jo_display_flow_box() {	
	?>
	<div class="auto_wrapper">
		<input type="textarea" id="jo_flow_box"/>
		
		<div id="jo_flow_fade"></div>
	
	</div>
	<?php
}

//display overlay at end of flow
function jo_display_flow_end_box() {
	?>
	<div id="jo_flow_end_overlay">
		
		<div id="jo_flow_end_box">
			<input id="jo_flow_archive" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--grey" type="submit" value="Done"/>
			<input id="jo_flow_save_as_draft" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--grey" type="submit" value="Save As Draft"/>
			<input id="jo_flow_edit_now" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--light-green" type="submit" value="Edit Now"/>
		</div>
	</div>
	<?php
}

//display timer
function jo_display_flow_counter() {
	?>
	<div id="jo_flow_counter"></div>
	<?php
}

jumpoff_page();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>