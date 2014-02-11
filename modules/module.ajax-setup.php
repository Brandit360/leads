<?php

/*  Generate Lead Rule Processing Batch */
add_action('wp_ajax_rules_run_rules_on_all_leads', 'wpleads_lead_rules_build_queue');
add_action('wp_ajax_nopriv_rules_run_rules_on_all_leads', 'wpleads_lead_rules_build_queue');

function wpleads_lead_rules_build_queue()
{
	global $wpdb;

	$rule_id = $_POST['rule_id'];
	$rules_queue = get_option( 'rules_queue');
	$rules_queue = json_decode( $rules_queue , true);

	if ( !is_array($rules_queue) )
		$rules_queue = array();

	if ( !in_array( $rule_id , $rules_queue ) )
	{
		/* get all lead ids */
		$sql = "SELECT distinct(ID) FROM {$wpdb->prefix}posts WHERE post_status='publish'  AND post_type = 'wp-lead' ";
		$result = mysql_query($sql);

		$batch = 1;
		$row = 0;

		while ($lead = mysql_fetch_array($result))
		{
			if ($row>1000)
			{
				$batch++;
				$row=0;
			}

			$rules_queue[$rule_id][$batch][] = $lead['ID'];

			$row++;
		}
	}

	$rules_queue = json_encode( $rules_queue);
	update_option( 'rules_queue' , $rules_queue);

	var_dump($rules_queue);
	die();
}

/* Increases the page view statistics of lead on page load */
add_action('wp_ajax_wpl_track_user', 'wpl_track_user_callback');
add_action('wp_ajax_nopriv_wpl_track_user', 'wpl_track_user_callback');

function wpl_track_user_callback()
{
	global $wpdb;

	(isset(	$_POST['wp_lead_id'] )) ? $lead_id = $_POST['wp_lead_id'] : $lead_id = '';
	(isset(	$_POST['nature'] )) ? $nature = $_POST['nature'] : $nature = 'non-conversion'; // what is nature?
	(isset(	$_POST['json'] )) ? $json = addslashes($_POST['json']) : $json = 0;
	(isset(	$_POST['wp_lead_uid'] )) ? $wp_lead_uid = $_POST['wp_lead_uid'] : $wp_lead_uid = 0;
	(isset(	$_POST['page_id'] )) ? $page_id = $_POST['page_id'] : $page_id = 0;
	(isset(	$_POST['current_url'] )) ? $current_url = $_POST['current_url'] : $current_url = 'notfound';

	// NEW Tracking
	if(isset($_POST['wp_lead_id'])) {
		wp_leads_update_page_view_obj($lead_id, $page_id, $current_url);
	}

	die();
}

/* sets cookie of lists that lead belongs to */
add_action('wp_ajax_wpl_check_lists', 'wpl_check_lists_callback');
add_action('wp_ajax_nopriv_wpl_check_lists', 'wpl_check_lists_callback');
function wpl_check_lists_callback() {
	$wp_lead_id = $_POST['wp_lead_id'];
	if (isset( $_POST['wp_lead_id'])&&!empty( $_POST['wp_lead_id']))
	{
		wp_leads_set_current_lists($wp_lead_id);
	}
}



