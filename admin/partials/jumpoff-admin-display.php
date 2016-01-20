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

		jo_display_jumpoff_logo();

	echo '</div>';

}

//display prompt box
function jo_display_prompt_box() {
	?>
	<div id="jo_prompt_times_container">
		<?php // CHANGE to customized elements with data attributes instead of radio buttons, make the length is the button, with a border or color change to indicate selected value ?>
		<ul id="jo_prompt_times">
			<li class="jo_prompt_time jo_checked" id="jo_prompt_time_20" data-value="20">20<br><span>sec</span></li>
			<li class="jo_prompt_time" id="jo_prompt_time_60" data-value="60">1<br><span>min</span></li>
			<li class="jo_prompt_time" id="jo_prompt_time_300" data-value="300">5<br><span>min</span></li>	
			<li class="jo_prompt_time" id="jo_prompt_time_600" data-value="600">10<br><span>min</span></li>
		</ul>
	</div>
	<div id="jo_prompt" class="jo_hide"></div>
	<input id="jo_prompt_me" class="jo_button" type="submit" value="Prompt Me"/>
	<?php
}

//display flow textarea
function jo_display_flow_box() {	
	?>
	<div class="auto_wrapper">
		
		<input type="textarea" id="jo_flow_box"/>
		<div id="jo_flow_box_overlay"></div>
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

function jo_display_jumpoff_logo() {
	?>
	<a id="jo_bottom_logo" href="http://jumpoff.io"><img src="<?php echo(plugin_dir_url( $file ).'jumpoff/assets/jumpoff-logo-wide-400.jpg'); ?>" alt="JumpOff Creative Writing App"/></a>
	<?php
}

jumpoff_page();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>