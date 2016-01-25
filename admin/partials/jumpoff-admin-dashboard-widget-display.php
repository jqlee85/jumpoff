<?php

/**
 * Provide a dashboard widget for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://jessequinnlee.com
 * @since      0.5.0
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
	<? //set flow streak 
		$jo_flow_streak = jo_flow_streak();
		$jo_flows_in_last_30_days = jo_flows_in_last_days(30);
	?>

	<div id="jo_dash_widget_wrapper">
		<img id="jo_dash_widget_logo" src="<?php echo(plugin_dir_url().'jumpoff/assets/jumpoff-logo-wide-400.jpg'); ?>" alt="JumpOff Logo" />
		<div id="jo_flow_written_today"><?php if ( !$jo_flow_streak['today'] ) { echo('Need Flow Today For Streak'); } ?></div>
		<table id="jo_dash_stats">
			<tr id="jo_flow_streak">
				<td class="jo_stat_text">Flow Streak</td>
				<td class="jo_stat_number"><?php echo( $jo_flow_streak['streak'] ); ?> <span>Day<?php if ( $jo_flow_streak['streak'] != 1 ){ echo 's';  }?></span></td>
			</tr>
			<tr id="jo_last_week_counter">
				<td class="jo_stat_text">Last 30 Days</td>
				<td class="jo_stat_number"><?php echo( $jo_flows_in_last_30_days ); ?> <span>Flow<?php if ( $jo_flows_in_last_30_days != 1 ){ echo 's';  }?></span></td>
			</tr>

		</table>

		<? $path = 'admin.php?page=jumpoff';
		$jumpoff_url = admin_url($path);?>
		<a href="<?php echo $jumpoff_url; ?>"><input id="jo_start_flowing" class="jo_button" value="Start Writing!"/></a>

		<div class="jo_contact_info">
			<p>Thanks for using <a href="http://jumpoff.io" target="_blank">JumpOff</a>!<br>If you have any questions/suggestions email jesse@jumpoff.io</p>
		</div>

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
	$i = 0;
	$streak = 0;
	$streak_unbroken = true;
	$today = true;

	$args = array(
		'post_type' => 'flow',
	);

	while ($streak_unbroken && $streak < 9999) {
		
		if ( $i == 0 ) {
			$args['date_query'] = array(
				array(
					'after' => $i + 1 . ' days ago',
					'inclusive' => true,
					
				)
			);
		}
		else {
			$args['date_query'] = array(
				array(
					'after' => ($i+ 1) . ' days ago',
					'before' => ($i) . ' days ago',
					'inclusive' => true,
				)
			);
		}
		$the_query = new WP_Query($args);
		if ( $the_query->found_posts > 0 ){
			$streak++;
			error_log('POSTS');
		}
		else {
			error_log('no posts found');
			if ( $i > 0 ) {$streak_unbroken = false;}
			else {$today = false;}
			
		}
		$i++;
	}
	return array('streak' => $streak, 'today' => $today);

}



jumpoff_dashboard_widget();

/*------------------------------------ /Render JumpOff Page ------------------------------------*/

?>