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
	?>
	<div id="jo_dash_widget_wrapper">
		<table id="jo_dash_stats">
			<tr id="jo_flow_streak">
				<td class="jo_stat_text">Flow Streak</td>
				<td class="jo_stat_number"><?php echo( jo_flow_streak() ); ?></td>
			</tr>
			<tr id="jo_last_week_counter">
				<td class="jo_stat_text">Last 30 Days</td>
				<td class="jo_stat_number"><?php echo( jo_flows_in_last_days(30) ); ?></td>
			</tr>

		</table>

		<? $path = 'admin.php?page=jumpoff';
		$jumpoff_url = admin_url($path);?>
		<a href="<?php echo $jumpoff_url; ?>"><input id="jo_start_flowing" class="jo_button" value="Start Flowing!"/></a>

	</div>
	<?php
}

//returns number of Flows done in last 30 days
function jo_flows_in_last_days($days) {
	
	$days = (int) $days + 1;

	//build query args
	$args = array(
	    'post_type' => 'flow',
	    // Using the date_query to filter posts from last week
	    'date_query' => array(
	        array(
	            'after' => $days.' days ago'
	        )
	    )
	); 

	$the_query = new WP_Query( $args );
	return $the_query->found_posts;

}

//calculates flow writing streak
function jo_flow_streak(){

	$streak = 0;
	$streak_unbroken = true;

	$args = array(
		'post_type' => 'flow',
	);

	while ($streak_unbroken && $streak < 9999) {
		
		$args['date_query'] = array(
			array(
				'after' => $streak + 1 . ' days ago',
				'before' => $streak . ' days ago'
			)
		);

		$the_query = new WP_Query($args);
		if ( $the_query->found_posts > 0 ){
			$streak++;
			error_log($streak);	
		}
		else {
			$streak_unbroken = false;
		}
	}
	return $streak;

}



jumpoff_dashboard_widget();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>