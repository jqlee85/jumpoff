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

		jo_display_flow_archive();

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
			<input id="jo_flow_trash" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red" type="submit" value="Trash"/>
			<input id="jo_flow_archive" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--grey" type="submit" value="Archive"/>
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

//display archive
function jo_display_flow_archive() {
	?>
	<div id="jo_recent_flows_table">
		<table>
			<tr class="jo_table_header">
				<th>Date</th>
				<th>Prompt</th>
				<th>Flow</th>
				<th>Flagged?</th>
				<th>Edit</th>
			</tr>
			
			<?php 
			//echo most recent flows 
			jo_recent_flows();
			?>
			 
		</table>
	</div>
	<?php
}

function jo_recent_flows( $flows = false ) {
	
	if ( ! $flows ) {
		$query_args = array(
			'post_type'      => 'flow',
			'post_status'	 => 'draft, publish, pending',
			'posts_per_page' => 10,
			'orderby'        => 'modified',
			'order'          => 'DESC'
		);
		$flows = get_posts( $query_args );
		if ( ! $flows ) {
			return;
 		}
 	}

	
	if ( count( $flows ) > 10 ) {
		echo '<p class="view-all"><a href="' . esc_url( admin_url( 'edit.php?post_status=draft' ) ) . '">' . _x( 'View all', 'drafts' ) . "</a></p>\n";
 	}
	echo '<h4 class="hide-if-no-js jo_recent_flows_title">' . __( 'Recent Flows' ) . "</h4>";

	$flows = array_slice( $flows, 0, 10 );
	foreach ( $flows as $flow ) {
		$url = get_edit_post_link( $flow->ID );
		$title = _draft_or_post_title( $flow->ID );
		$flow_time = get_the_time( ('n/j/Y'), $flow );
		//echo flow row
		?>
		<tr>
			<td>
				<p><?php echo $flow_time;?></p>
			</td>
			<td>
				<p><?php echo $title;?></p>
			</td>
			<td>
				<p><?php if ( $the_content = wp_trim_words( $flow->post_content, 10 ) ) {
				echo $the_content;
 				}?></p>
 			</td>
			<td>
				<input type="checkbox"></input>
			</td>
			<td>
				<?php echo '<div class="flow_title"><a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '"><button>Edit</button></a>';?>
			</td>
		</tr>
		<?php
	}//end foreach
}

jumpoff_page();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>