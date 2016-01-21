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
				<th class="jo_hide_sm">Flow</th>
				<th>Star</th>
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
			'posts_per_page' => 100,
			'orderby'        => 'modified',
			'order'          => 'DESC'
		);
		$flows = get_posts( $query_args );
		if ( ! $flows ) {
			return;
 		}
 	}

	
	if ( count( $flows ) > 100 ) {
		echo '<p class="view-all"><a href="' . esc_url( admin_url( 'edit.php?post_status=draft' ) ) . '">' . _x( 'View all', 'drafts' ) . "</a></p>\n";
 	}
	echo '<img class="jo_logo_header" src="'.plugin_dir_url( $file ).'jumpoff/assets/jumpoff-logo-wide-400.jpg" alt="JumpOff Logo" />';
	echo '<h2 class="hide-if-no-js jo_recent_flows_title">' . __( 'Recent Flows' ) . "</h2>";

	$flows = array_slice( $flows, 0, 100 );
	foreach ( $flows as $flow ) {
		
		//get Flow edit link
		$url = get_edit_post_link( $flow->ID );
		
		//get Flow title
		$title = _draft_or_post_title( $flow->ID );
		
		//get formatted Date created
		$flow_time = get_the_time( ('n/j/Y'), $flow );

		//get if Flow has been starred or not
		$is_starred = get_post_meta( $flow->ID, 'jumpoff_flow_flag', true ); 

		//echo flow row
		?>
		<tr>
			<td>
				<p><?php echo $flow_time;?></p>
			</td>
			<td>
				<p><?php echo $title;?></p>
			</td>
			<td class="jo_hide_sm">
				<p><?php if ( $the_content = wp_trim_words( $flow->post_content, 10 ) ) {
				echo $the_content;
 				}?></p>
 			</td>
			<td>
				<input type="checkbox" class="jo_flow_star" name="jo_flow_star_<?php echo $flow->ID; ?>" id="jo_flow_star_<?php echo $flow->ID; ?>" <?php if ( $is_starred ) { ?>checked="checked"<?php } ?> />
			</td>
			<td>
				<?php echo '<a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '"><div class="wp-menu-image dashicons-before dashicons-edit jo_recent_flow_edit"><br></div></a>';?>
			</td>
		</tr>
		<?php
	}//end foreach
}

jumpoff_my_flows_page();


?>