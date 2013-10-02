<?php
	if (isset($_GET['page'])&&($_GET['page']=='wpleads_global_settings'&&$_GET['page']=='wpleads_global_settings'))
	{
		add_action('admin_init','wpleads_global_settings_enqueue');
		function wpleads_global_settings_enqueue()
		{		
			wp_enqueue_style('wpl-css-global-settings-here', WPL_URL . '/css/wpl.admin-global-settings.css');
			//wp_enqueue_script('wpl-js-global-settings', WPL_URL . '/js/admin.global-settings.js');			
		}
	}
	
	/*SETUP NAVIGATION AND DISPLAY ELEMENTS*/
	function wpleads_get_global_settings()
	{
		// Setup navigation and display elements
		$tab_slug = 'wpl-main';
		$wpleads_global_settings[$tab_slug]['label'] = 'Global Settings';	
		
		$wpleads_global_settings[$tab_slug]['settings'] = 
		array(	
			array(
				'id'  => 'tracking-ids',
				'label' => 'IDs of forms to track',
				'description' => "<p>Enter in a value found in a HTML form's id attribute to track it as a conversion.</p><p>Do not include the # in the id. <strong>Example format: Form_ID, Form-ID-2</strong></p><p>Gravity Forms, Contact Form 7, and Ninja Forms are automatically tracked (no need to add their IDs in here)</p>",
				'type'  => 'text', 
				'default'  => '',
				'options' => null
			),
			array(
				'id'  => 'exclude-tracking-ids',
				'label' => 'IDs of forms NOT to track',
				'description' => "<p>Enter in a value found in a HTML form's id attribute to turn off tracking.</p>",
				'type'  => 'text', 
				'default'  => '',
				'options' => null
			),
			array(
				'id'  => 'form-prepopulation',
				'label' => 'Form prepopulation',
				'description' => "<p>WordPress Leads records submitted field data for leads and will attempt to prepopulate forms with the last inputted data. Disabling this will turn this feature off.</p>",
				'type'  => 'radio', 
				'default'  => '1',
				'options' => array('1'=>'On','0'=>'Off')
			),
			array(
				'id'  => 'page-view-tracking',
				'label' => 'Page View Tracking',
				'description' => "<p>WordPress Leads automatically tracks page views of converted leads. This is extremely valuable lead intelligence and will help with your sales followups. However with great power comes great resposibility, this extra tracking can cause problems on high high traffic sites. You can turn off tracking if you see any issues.</p>",
				'type'  => 'radio', 
				'default'  => '1',
				'options' => array('1'=>'On','0'=>'Off')
			),
			array(
				'id'  => 'create-roles',
				'label' => 'Create WP Roles for Lists',
				'description' => "<p>Turn this on to create WordPress user roles that correspond with Lead lists.</p>",
				'type'  => 'radio', 
				'default'  => '1',
				'options' => array('1'=>'On','0'=>'Off')
			),
			array(
				'id'  => 'enable-dashboard',
				'label' => 'Show Lead/List Data in Dashboard',
				'description' => "<p>Turn this on to show graphical and list data about lead collection in WP Dashboard.</p>",
				'type'  => 'radio', 
				'default'  => '1',
				'options' => array('1'=>'On','0'=>'Off')
			),			
			array(
				'id'  => 'extra-lead-data',
				'label' => 'Full Contact API Key',
				'description' => "<p>Enter your Full contact API key. If you don't have one. Grab a free one here: <a href='https://www.fullcontact.com/developer/pricing/' target='_blank'>here</a></p>",
				'type'  => 'text', 
				'default'  => '',
				'options' => null
			)
		);
		
		$wpleads_global_settings = apply_filters('wpleads_define_global_settings', $wpleads_global_settings);

		return $wpleads_global_settings;
	}

	/* Add Extensions License Key Header if Extensions are present */
	add_filter('wpleads_define_global_settings', 'wpleads_add_extension_license_key_header',1,1);
	function wpleads_add_extension_license_key_header($wpleads_global_settings)
	{
		//print_r($wpleads_global_settings);exit;
		foreach ($wpleads_global_settings as $parent_tab => $aa)
		{
			if (is_array($aa))
			{
				
				foreach ($aa as $k=>$aaa)
				{
					/* change 'options' key to 'settings' */
					if ($k=='options')
					{
						if (is_array($aaa))
						{
							foreach ($aaa as $kk => $aaaa)
							{
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
		
	function wpleads_render_global_settings($key,$custom_fields,$active_tab)
	{

		//Check if active tab
		if ($key==$active_tab)
		{
			$display = 'block';
		}
		else
		{
			$display = 'none';
		}
		
		// Use nonce for verification
		echo "<input type='hidden' name='wpl_{$key}_custom_fields_nonce' value='".wp_create_nonce('wpl-nonce')."' />";

		// Begin the field table and loop
		echo '<table class="wpl-tab-display" id="'.$key.'" style="display:'.$display.'">';
		//print_r($custom_fields);exit;
		foreach ($custom_fields as $field) {
			//echo $field['type'];exit; 
			// get value of this field if it exists for this post
			if (isset($field['default']))
			{
				$default = $field['default'];
			}
			else
			{
				$default = null;
			}
			
			$field['id'] = $key.'-'.$field['id'];
			$option = get_option($field['id'], $default);
			
			// begin a table row with
			echo '<tr>
					<th class="wpl-gs-th" valign="top" style="font-weight:300px;"><small>'.$field['label'].':</small></th>
					<td>';
					switch($field['type']) {
						// text
						case 'colorpicker':
							if (!$option)
							{
								$option = $field['default'];
							}
							echo '<input type="text" class="jpicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$option.'" size="5" />
									<div class="wpl_tooltip tool_color" title="'.$field['desc'].'"></div>';
							break;
						case 'datepicker':
							echo '<input id="datepicker-example2" class="Zebra_DatePicker_Icon" type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$option.'" size="8" />
									<div class="wpl_tooltip tool_date" title="'.$field['desc'].'"></div><p class="description">'.$field['desc'].'</p>';
							break;	
						case 'text':
							echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$option.'" size="30" />
									<div class="wpl_tooltip tool_text"  title="'.$field['desc'].'"></div>';
							break;
						// textarea
						case 'textarea':
							echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="106" rows="6">'.$option.'</textarea>
									<div class="wpl_tooltip tool_textarea" title="'.$field['desc'].'"></div>';
							break;
						// wysiwyg
						case 'wysiwyg':
							wp_editor( $option, $field['id'], $settings = array() );
							echo	'<span class="description">'.$field['desc'].'</span><br><br>';							
							break;
						// media					
							case 'media':
							//echo 1; exit;
							echo '<label for="upload_image">';
							echo '<input name="'.$field['id'].'"  id="'.$field['id'].'" type="text" size="36" name="upload_image" value="'.$option.'" />';
							echo '<input class="upload_image_button" id="uploader_'.$field['id'].'" type="button" value="Upload Image" />';
							echo '<br /><div class="wpl_tooltip tool_media" title="'.$field['desc'].'"></div>'; 
							break;
						// checkbox
						case 'checkbox':
							$i = 1;
							echo "<table>";				
							if (!isset($option)){$option=array();}
							elseif (!is_array($option)){
								$option = array($option);
							}
							foreach ($field['options'] as $value=>$label) {
								if ($i==5||$i==1)
								{
									echo "<tr>";
									$i=1;
								}
									echo '<td><input type="checkbox" name="'.$field['id'].'[]" id="'.$field['id'].'" value="'.$value.'" ',in_array($value,$option) ? ' checked="checked"' : '','/>';
									echo '<label for="'.$value.'">&nbsp;&nbsp;'.$label.'</label></td>';					
								if ($i==4)
								{
									echo "</tr>";
								}
								$i++;
							}
							echo "</table>";
							echo '<br><div class="wpl_tooltip tool_checkbox" title="'.$field['desc'].'"></div><p class="description">'.$field['desc'].'</p>';
						break;
						// radio
						case 'radio':
							foreach ($field['options'] as $value=>$label) {
								//echo $meta.":".$field['id'];
								//echo "<br>";
								echo '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$value.'" ',$option==$value ? ' checked="checked"' : '','/>';
								echo '<label for="'.$value.'">&nbsp;&nbsp;'.$label.'</label> &nbsp;&nbsp;&nbsp;&nbsp;';								
							}
							echo '<div class="wpl_tooltip tool_radio" title="'.$field['desc'].'"></div>';
						break;
						// select
						case 'dropdown':
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
							foreach ($field['options'] as $value=>$label) {
								echo '<option', $option == $value ? ' selected="selected"' : '', ' value="'.$value.'">'.$label.'</option>';
							}
							echo '</select><br /><div class="wpl_tooltip tool_dropdown" title="'.$field['desc'].'"></div>';
						break;
						


					} //end switch
			echo '</td></tr>';
		} // end foreach
		echo '</table>'; // end table
	}
	
	function wpleads_display_global_settings_js()
	{	
		global $wpleads_global_settings;
		$wpleads_global_settings = wpleads_get_global_settings();
		
		if (isset($_GET['tab']))
		{
			$default_id = $_GET['tab'];
		}
		else
		{
			$default_id ='wpl-main';
		}
			
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function() 
			{
				jQuery('#<? echo $default_id; ?>').css('display','block');
				 setTimeout(function() {
	     			var getoption = document.URL.split('&option=')[1];
					var showoption = "#" + getoption;
					jQuery(showoption).click();
    			}, 100);
				
				<?php
				foreach ($wpleads_global_settings as $key => $array)
				{
				?>
					jQuery('.wpl-nav-tab').live('click', function() {
					
						var this_id = this.id.replace('tabs-','');
						//alert(this_id);
						jQuery('.wpl-tab-display').css('display','none');
						jQuery('#'+this_id).css('display','block');
						jQuery('.wpl-nav-tab').removeClass('nav-tab-special-active');
						jQuery('.wpl-nav-tab').addClass('nav-tab-special-inactive');
						jQuery('#tabs-'+this_id).addClass('nav-tab-special-active');						
						jQuery('#id-open-tab').val(this_id);

						
					});
				<?php
				}
				?>
			});			
		</script>
		<?php
	}
	
	function wpleads_display_global_settings()
	{	
		global $wpdb;
		$wpleads_global_settings = wpleads_get_global_settings();

		$active_tab = 'wpl-main'; 
		if (isset($_REQUEST['open-tab']))
		{
			$active_tab = $_REQUEST['open-tab'];
		}

		wpleads_display_global_settings_js();
		wpleads_save_global_settings();
	
		echo '<h2 class="nav-tab-wrapper">';		
	
		foreach ($wpleads_global_settings as $key => $data)
		{
			?>
			<a  id='tabs-<?php echo $key; ?>' class="wpl-nav-tab nav-tab nav-tab-special<?php echo $active_tab == $key ? '-active' : '-inactive'; ?>"><?php echo $data['label']; ?></a> 
			<?php
		}
		echo '</h2>';
		echo "<form action='edit.php?post_type=wp-lead&page=wpleads_global_settings' method='POST'>";
		echo "<input type='hidden' name='nature' value='wpl-global-settings-save'>";
		echo "<input type='hidden' name='open-tab' id='id-open-tab' value='{$active_tab}'>";
				
		foreach ($wpleads_global_settings as $key => $array)
		{
			
			$these_settings = $wpleads_global_settings[$key]['settings'];	
			wpleads_render_global_settings($key,$these_settings, $active_tab);
		}
		echo '<div style="float:left;padding-left:9px;padding-top:20px;">
				<input type="submit" value="Save Settings" tabindex="5" id="wpl-button-create-new-group-open" class="button-primary" >
			</div>';
		echo "</form>";
		
	}
	
	function wpleads_save_global_settings() 
	{
		//echo "here";exit;
		$wpleads_global_settings = wpleads_get_global_settings();
		
		if (!isset($_POST['nature']))
			return;
	
		
		foreach ($wpleads_global_settings as $key=>$array)
		{	
			$wpleads_options = $wpleads_global_settings[$key]['settings'];		
			//echo 1; 

			// loop through fields and save the data
			foreach ($wpleads_options as $option) 
			{
				//echo $option['id'].":".$_POST['main-landing-page-auto-format-forms']."<br>";
				$option['id'] = $key.'-'.$option['id'];
				
				$old = get_option($option['id']);				
				$new = $_POST[$option['id']];	
			
				if ((isset($new) && $new !== $old )|| !isset($old) ) 
				{
					//echo $option['id'];exit;
					$bool = update_option($option['id'],$new);								
				} 
				elseif ('' == $new && $old) 
				{
					$bool = update_option($option['id'],$option['default']);
				}
			} // end foreach		
		}
		
	}
?>