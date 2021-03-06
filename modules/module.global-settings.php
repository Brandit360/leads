<?php


/*SETUP NAVIGATION AND DISPLAY ELEMENTS*/
function wpleads_get_global_settings() {
	// Setup navigation and display elements
	$tab_slug = 'wpl-main';
	$wpleads_global_settings[$tab_slug]['label'] = 'Global Settings';

	$wpleads_global_settings[$tab_slug]['settings'] =
	array(
		array(
			'id'  => 'tracking-ids',
			'label' => __('IDs or Classes of forms to track' , 'leads' ),
			'description' => __("<p>Enter in a value found in a HTML form's id or class attribute to track it as a conversion as comma separated values</p><p><strong>Example ID format:</strong> #Form_ID, #Form-ID-2<br>Example Class format:</strong> .Form_class, .form-class-2</p><p>Gravity Forms, Contact Form 7, and Ninja Forms are automatically tracked (no need to add their IDs in here).</p>" , 'leads' ),
			'type'  => 'text',
			'default'  => '',
			'options' => null
		),
		array(
			'id'  => 'exclude-tracking-ids',
			'label' => __('IDs of forms <u>NOT</u> to track' , 'leads' ),
			'description' => __("<p>Enter in a value found in a HTML form's id attribute to turn off tracking.</p>" , 'leads' ),
			'type'  => 'text',
			'default'  => '',
			'options' => null
		),
		array(
			'id'  => 'form-prepopulation',
			'label' => __('Form prepopulation' , 'leads' ),
			'description' => __("<p>WordPress Leads records submitted field data for leads and will attempt to prepopulate forms with the last inputted data. Disabling this will turn this feature off.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'page-view-tracking',
			'label' => __('Page View Tracking' , 'leads' ),
			'description' => __("<p>WordPress Leads automatically tracks page views of converted leads. This is extremely valuable lead intelligence and will help with your sales followups. However with great power comes great resposibility, this extra tracking can cause problems on high high traffic sites. You can turn off tracking if you see any issues.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'search-tracking',
			'label' => __('Search Query Tracking' , 'leads' ),
			'description' => __("<p>WordPress Leads records searches made by leads and appends them to their lead record. Disabling this will turn this feature off.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'comment-tracking',
			'label' => __('Comment Tracking' , 'leads' ),
			'description' => __("<p>WordPress Leads records comments made by leads and appends them to their lead record. Disabling this will turn this feature off.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'enable-dashboard',
			'label' => __('Show Lead/List Data in Dashboard' , 'leads' ),
			'description' => __("<p>Turn this on to show graphical and list data about lead collection in WP Dashboard.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'disable-widgets',
			'label' => __('Disable Default WordPress Dashboard Widgets' , 'leads' ),
			'description' => __("<p>This turns off some default widgets on the wordpress dashboard.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '1',
			'options' => array('1'=>'On','0'=>'Off')
		),
		array(
			'id'  => 'extra-lead-data',
			'label' => __('Full Contact API Key' , 'leads' ),
			'description' => sprintf( __("<p>Enter your Full contact API key. If you don't have one. Grab a free one here: %s here %s </p>" , 'leads' ) , "<a href='https://www.fullcontact.com/developer/pricing/' target='_blank'>" , "</a>"),
			'type'  => 'text',
			'default'  => '',
			'options' => null
		),
		array(
			'id'  => 'inbound_compatibility_mode',
			'label' => __('Turn on compability mode' , 'leads' ),
			'description' => __("<p>This option turns on compability mode for the inbound now plugins. This is typically used if you are experiencing bugs caused by third party plugin conflicts.</p>" , 'leads' ),
			'type'  => 'radio',
			'default'  => '0',
			'options' => array('1'=>'On','0'=>'Off')
		),
		/*array(
			'id'  => 'lead_automation_cronjob_period',
			'label' => __('How often do you want to process marketing automation rules?' , 'leads' ),
			'description' => __("<p>Set how often you would like to process lead automation? Cronjob Settings.</p>" , 'leads' ),
			'type'  => 'dropdown',
			'default'  => 'hourly',
			'options' => array('twicedaily'=>'twice a day','daily'=>'Once a day','hourly'=>'Every Hour')
		) */
	);

	/* Setup License Keys Tab */
	$tab_slug = 'wpleads-license-keys';
	$wpleads_global_settings[$tab_slug]['label'] = __('License Keys' , 'leads' );

	/* Setup Extensions Tab */
	$tab_slug = 'wpleads-extensions';
	$wpleads_global_settings[$tab_slug]['label'] = __('Extensions' , 'leads' );

	$wpleads_global_settings = apply_filters('wpleads_define_global_settings', $wpleads_global_settings);

	return $wpleads_global_settings;
}

/* Add Extensions License Key Header if Extensions are present */
add_filter('wpleads_define_global_settings', 'wpleads_add_extension_license_key_header',1,1);
function wpleads_add_extension_license_key_header($wpleads_global_settings) {
	//print_r($wpleads_global_settings);exit;
	foreach ($wpleads_global_settings as $parent_tab => $aa) {
		if (is_array($aa)) {

			foreach ($aa as $k=>$aaa) {
				/* change 'options' key to 'settings' */
				if ($k=='options') {
					if (is_array($aaa)) {
						foreach ($aaa as $kk => $aaaa) {
							$wpleads_global_settings[$parent_tab]['settings'][] = $aaaa;
						}
					}
					unset($wpleads_global_settings[$parent_tab][$k]);
				}

			}
		}
	}

	return $wpleads_global_settings;
}

function wpleads_render_global_settings($key,$custom_fields,$active_tab) {

	/* Check if active tab */
	if ($key==$active_tab) {
		$display = 'block';
	} else {
		$display = 'none';
	}

	/* Use nonce for verification */
	echo "<input type='hidden' name='wpl_{$key}_custom_fields_nonce' value='".wp_create_nonce('wpl-nonce')."' />";

	/* Begin the field table and loop */
	echo '<table class="wpl-tab-display" id="'.$key.'" style="display:'.$display.'">';

	foreach ($custom_fields as $field) {
		/* get value of this field if it exists for this post */
		(isset($field['default'])) ? $default = $field['default'] : $default = null;

		$field['id'] = $key.'-'.$field['id'];

		if (array_key_exists('option_name',$field) && $field['option_name'] ){
			$field['id'] = $field['option_name'];
		}

		$field['value'] = get_option($field['id'], $default);

		echo '<tr><th class="wpl-gs-th" valign="top" style="font-weight:300;">';
		if ($field['type']=='header'){
			echo $field['default'];
		} else {
			echo "<div class='inbound-setting-label'>".$field['label']."</div>";
		}
		echo '</th><td>';
				switch($field['type']) {
					// text
					case 'colorpicker':
						if (!$field['value'])
						{
							$field['value'] = $field['default'];
						}
						echo '<input type="text" class="jpicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$field['value'].'" size="5" />
								<div class="wpl_tooltip tool_color" title="'. $field['description'] .'"></div>';
						break;
					case 'datepicker':
						echo '<input id="datepicker-example2" class="Zebra_DatePicker_Icon" type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$field['value'].'" size="8" />
								<div class="wpl_tooltip tool_date" title="'. $field['description'] .'"></div><p class="description">'. $field['description'] .'</p>';
						break;
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$field['value'].'" size="30" />
								<div class="wpl_tooltip tool_text"  title="'. $field['description'] .'"></div>';
						break;
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="106" rows="6">'.$field['value'].'</textarea>
								<div class="wpl_tooltip tool_textarea" title="'. $field['description'] .'"></div>';
						break;
					// wysiwyg
					case 'wysiwyg':
						wp_editor( $field['value'], $field['id'], $settings = array() );
						echo	'<span class="description">'. $field['description'] .'</span><br><br>';
						break;
					// media
						case 'media':
						//echo 1; exit;
						echo '<label for="upload_image">';
						echo '<input name="'.$field['id'].'"  id="'.$field['id'].'" type="text" size="36" name="upload_image" value="'.$field['value'].'" />';
						echo '<input class="upload_image_button" id="uploader_'.$field['id'].'" type="button" value="Upload Image" />';
						echo '<br /><div class="wpl_tooltip tool_media" title="'. $field['description'] .'"></div>';
						break;
					// checkbox
					case 'checkbox':
						$i = 1;
						echo "<table>";
						if (!isset($field['value'])){$field['value']=array();}
						elseif (!is_array($field['value'])){
							$field['value'] = array($field['value']);
						}
						foreach ($field['options'] as $value=>$label) {
							if ($i==5||$i==1) {
								echo "<tr>";
								$i=1;
							}
								echo '<td><input type="checkbox" name="'.$field['id'].'[]" id="'.$field['id'].'" value="'.$value.'" ',in_array($value,$field['value']) ? ' checked="checked"' : '','/>';
								echo '<label for="'.$value.'">&nbsp;&nbsp;'.$label.'</label></td>';
							if ($i==4) {
								echo "</tr>";
							}
							$i++;
						}
						echo "</table>";
						echo '<div class="wpl_tooltip tool_checkbox" title="'. $field['description'] .'"></div><p class="description">'. $field['description'] .'</p>';
					break;
					// radio
					case 'radio':
						foreach ($field['options'] as $value=>$label) {
							//echo $meta.":".$field['id'];
							//echo "<br>";
							echo '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$value.'" ',$field['value']==$value ? ' checked="checked"' : '','/>';
							echo '<label for="'.$value.'">&nbsp;&nbsp;'.$label.'</label> &nbsp;&nbsp;&nbsp;&nbsp;';
						}
						echo '<div class="wpl_tooltip tool_radio" title="'. $field['description'] .'"></div>';
					break;
					// select
					case 'dropdown':
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						foreach ($field['options'] as $value=>$label) {
							echo '<option', $field['value'] == $value ? ' selected="selected"' : '', ' value="'.$value.'">'.$label.'</option>';
						}
						echo '</select><div class="wpl_tooltip tool_dropdown" title="'. $field['description'] .'"></div>';
					break;
					case 'html':
						echo $field['value'];
						echo '<div class="wpl_tooltip tool_dropdown" title="'. $field['description'] .'"></div>';
					break;

				} //end switch

				do_action('wpleads_render_global_settings',$field);
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

function wpleads_display_global_settings_js() {
	global $wpleads_global_settings;
	$wpleads_global_settings = wpleads_get_global_settings();

	if (isset($_GET['tab'])) {
		$default_id = $_GET['tab'];
	} else {
		$default_id ='wpl-main';
	}
}

function wpleads_display_global_settings() {
	global $wpdb;
	$wpleads_global_settings = wpleads_get_global_settings();

	$active_tab = 'wpl-main';
	if (isset($_REQUEST['open-tab'])) {
		$active_tab = $_REQUEST['open-tab'];
	}

	wpleads_display_global_settings_js();
	wpleads_save_global_settings();

	echo '<h2 class="nav-tab-wrapper">';

	foreach ($wpleads_global_settings as $key => $data) {
		?>
		<a  id='tabs-<?php echo $key; ?>' class="wpl-nav-tab nav-tab nav-tab-special<?php echo $active_tab == $key ? '-active' : '-inactive'; ?>"><?php _e( $data['label'] , 'leads' ); ?></a>
		<?php
	}
	echo '</h2>';
	echo "<form action='edit.php?post_type=wp-lead&page=wpleads_global_settings' method='POST'>";
	echo "<input type='hidden' name='nature' value='wpl-global-settings-save'>";
	echo "<input type='hidden' name='open-tab' id='id-open-tab' value='{$active_tab}'>";

	foreach ($wpleads_global_settings as $key => $array) {
		if (!array_key_exists('settings',$array)){
			continue;
		}

		$these_settings = $wpleads_global_settings[$key]['settings'];
		wpleads_render_global_settings($key,$these_settings, $active_tab);
	}
	echo '<div style="float:left;padding-left:9px;padding-top:20px;">
			<input type="submit" value="Save Settings" tabindex="5" id="wpl-button-create-new-group-open" class="button-primary" >
		</div>';
	echo "</form>";

}

function wpleads_save_global_settings() {
	//echo "here";exit;
	$wpleads_global_settings = wpleads_get_global_settings();

	if (!isset($_POST['nature'])) {
		return;
	}

	foreach ($wpleads_global_settings as $key=>$array) {
		$wpleads_options = $wpleads_global_settings[$key]['settings'];

		if (!$wpleads_options) {
			continue;
		}

		/* loop through fields and save the data */
		foreach ($wpleads_options as $field) {
			//echo $field['id'].":".$_POST['main-landing-page-auto-format-forms']."<br>";
			$field['id'] = $key.'-'.$field['id'];

			if (array_key_exists('option_name',$field) && $field['option_name'] ) {
				$field['id'] = $field['option_name'];
			}
			
			if ( !isset($_POST[$field['id']]) ) {
				continue;
			}
			
			$field['old_value'] = get_option($field['id']);
			$field['new_value'] = $_POST[$field['id']];

			if ((isset($field['new_value']) && $field['new_value'] !== $field['old_value'] )|| !isset($field['old_value']) ) {
				//echo $field['id'];exit;
				$bool = update_option($field['id'],$field['new_value']);

				if ($field['type']=='license-key') {

					// data to send in our API request
					$api_params = array(
						'edd_action'=> 'activate_license',
						'license' 	=> $field['new_value'],
						'item_name' =>  $field['slug'] // the name of our product in EDD
					);
					//print_r($api_params);

					// Call the custom API.
					$response = wp_remote_get( add_query_arg( $api_params, WPWPL_STORE_URL ), array( 'timeout' => 30, 'sslverify' => false ) );
					//echo $response['body'];exit;

					// make sure the response came back okay
					if ( is_wp_error( $response ) ) {
						break;
					}

					// decode the license data
					$license_data = json_decode( wp_remote_retrieve_body( $response ) );


					// $license_data->license will be either "active" or "inactive"
					$license_status = update_option('wpleads_license_status-'.$field['slug'], $license_data->license);

					//echo 'lp_license_status-'.$field['slug']." :".$license_data->license;exit;
				}
			} elseif ('' == $field['new_value'] && $field['old_value']) {

				if ($field['type']=='license-key') {

					$master_key = get_option('inboundnow_master_license_key' , '');

					if ($master_key) {
						$bool = update_option($field['id'], $master_key );
					} else {
						update_option($field['id'], '' );
					}

				} else {
					$bool = update_option($field['id'],$field['default']);
				}
			}

			do_action('wpleads_save_global_settings',$field);

		} // end foreach

	}

}