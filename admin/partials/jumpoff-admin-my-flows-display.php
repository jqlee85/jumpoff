<?php

/**
 * Provide an admin area view for the Flows created in JumpOff
 *
 * This file is used to markup the My Flows page of the plugin
 *
 * @link       http://jumpoff.io
 * @since      1.0.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/admin/partials
 */

//echos My Flows  page
function jumpoff_my_flows_page() {
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div id="jo_wrapper">';

		jo_display_flow_archive();

	echo '</div>';

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
	echo '<h2 class="hide-if-no-js jo_recent_flows_title">' . __( 'Recent Flows' ) . "</h2>";

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

jumpoff_my_flows_page();


?>