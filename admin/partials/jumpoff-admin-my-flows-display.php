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
			//get query var
	$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;

	$max_flows = 999;

	if ( ! $flows ) {
		$query_args = array(
			'post_type'      => 'flow',
			'post_status'	 => 'draft, publish, pending',
			'posts_per_page' => $max_flows,
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'paged'			 => '$paged'
		);
		$flows = get_posts( $query_args );
		if ( ! $flows ) {
			return;
 		}
 	}

	
	if ( count( $flows ) > $max_flows ) {
		echo '<p class="view-all"><a href="' . esc_url( admin_url( 'edit.php?post_status=draft' ) ) . '">' . _x( 'View all', 'drafts' ) . "</a></p>\n";
 	}
	echo '<img class="jo_logo_header" src="'.plugin_dir_url( $file ).'jumpoff/assets/jumpoff-logo-wide-400.jpg" alt="JumpOff Logo" />';
	echo '<h2 class="hide-if-no-js jo_recent_flows_title">' . __( 'Recent Flows' ) . "</h2>";

	$flows = array_slice( $flows, 0, $max_flows );
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
				echo esc_html__($the_content);
 				}?></p>
 			</td>
			<td>
				<div class="jo_flow_star" name="jo_flow_star_<?php echo $flow->ID; ?>" id="jo_flow_star_<?php echo $flow->ID; ?>" data-checked="<?php if ( $is_starred ) { echo 1; } ?>" ></div>
			</td>
			<td>
				<?php echo '<a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '"><div class="wp-menu-image dashicons-before dashicons-edit jo_recent_flow_edit"><br></div></a>';?>
			</td>
		</tr>
		<?php
	}//end foreach


			?>
			 
		</table>

	</div>
	<?php
}


jumpoff_my_flows_page();


?>