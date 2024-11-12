<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
$custom_field_obj =new Smgt_custome_field;
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
$module='student';	
$user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
?>
<?php
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('student');
	$user_access_add=$user_access['add'];
	$user_access_edit=$user_access['edit'];
	$user_access_delete=$user_access['delete'];
	$user_access_view=$user_access['view'];
	
	if (isset ( $_REQUEST ['page'] ))
	{	
		if($user_access_view=='0')
		{	
			mj_smgt_access_right_page_not_access_message_admin_side();
			die;
		}
		if(!empty($_REQUEST['action']))
		{
			if ('student' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('student' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('student' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
			{
				if($user_access_add=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			} 
		}
	}
}
?>
<script> //--------- Script Start ---------//
	jQuery(document).ready(function($)
	{

		"use strict";	
			//---------- Student datatable script ------------//
			var table =  jQuery('#students_list').DataTable(
			{
				"initComplete": function( settings, json ) {
					$('.table-responsive').show();
					$(".loader").css({"display": "none"});
					$(".print-button").css({"margin-top": "-5%"});
					$('#students_list th:first-child').removeClass('sorting_asc');
				},
				// stateSave: true,
				//responsive: true,
				dom: 'lifrtp',
				"ordering": true,
				"aoColumns":[	                  
				{"bSortable": false,"width": "20%"},
				{"bSortable": false},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				<?php
				if(!empty($user_custom_field)){
					foreach($user_custom_field as $custom_field)
					{
						if($custom_field->show_in_table == "1")
						{
							?>
							{"bSortable": true}, 
							<?php
						}
					}
				}
				?>
				{"bSortable": true},
				{"bSortable": false}],
				language:<?php echo mj_smgt_datatable_multi_language();?>		
			});
			
			$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
			//-------- Select All --------//
			$('.select_all').on('click', function(e)
			{
				if($(this).is(':checked',true))  
				{
					$(".smgt_sub_chk").prop('checked', true);  
				}  
				else  
				{  
					$(".smgt_sub_chk").prop('checked',false);  
				} 
			});
			//---------- check box check JS ---------//
			$('.smgt_sub_chk').on('change',function()
			{ 
				if(false == $(this).prop("checked"))
				{ 
					$(".select_all").prop('checked', false); 
				}
			});
			// delete selected on click event 
			$("#delete_selected").on('click', function()
			{	
				if ($('.smgt_sub_chk:checked').length == 0 )
				{
					alert(language_translate2.one_record_select_alert);
					return false;
				}
				else
				{
					var alert_msg=confirm(language_translate2.delete_record_alert);
					if(alert_msg == false)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
			});
		$("body").on("click",".print_id_card",function()
		{
			if ($('.check_for_id:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}		
		}); 
		$('.sdate').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeMonth: true,
			changeYear: true,
			maxDate: 0,
			beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			}); 
		//-------- End date datepicker ---------//
		$('.edate').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			});	
			// Custom Date Picker
			$('.custom_datepicker').datepicker({
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
					endDate: '+0d',
					autoclose: true,
					changeMonth: true,
			changeYear: true,
					orientation: "bottom" // <-- and add this
			}); 
			//----------- Attendance List script ---------//
			var table =  jQuery('#attendance_list').DataTable(
			{
				responsive: true,
				"order": [[ 0, "DESC" ]],
				dom: 'Bfrtip',

						buttons: [
						{
					extend: 'print',
					title: 'View Attendance',},
					{
					extend: 'pdf',
					title: 'View Attendance',
					},
					'csv'
						],
				"aoColumns":[	                  
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},	           
				{"bSortable": false}],	
				language:<?php echo mj_smgt_datatable_multi_language();?>		
			});

			$('#upload_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

			jQuery('#student_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	 
			jQuery('#birth_date').datepicker({
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
				maxDate : 0,
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+25',
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				},    
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "/" + year);
				}                    
			}); 
			//----------- Space Validation JS ---------//
			$('.space_validation').on('keypress',function( e ) 
			{
				if(e.which === 32) 
					return false;
			});									
			//custom field datepicker
			$('.after_or_equal').datepicker({
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",										
				minDate:0,
				changeMonth: true,
			changeYear: true,
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			}); 
			$('.date_equals').datepicker({
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
				minDate:0,
				maxDate:0,			
				changeMonth: true,
			changeYear: true,							
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			}); 
			$('.before_or_equal').datepicker({
				dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
				maxDate:0,
				changeMonth: true,
			changeYear: true,
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			}); 
			$('#upload_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

			var table =  jQuery('#exam_list').DataTable({
				responsive: true,
				"aoColumns":[	                  
					{"bSortable": true},
					{"bSortable": false}],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

			$(".view_more_details_div").on("click", ".view_more_details", function(event)
			{
				$('.view_more_details_div').removeClass("d-block");
				$('.view_more_details_div').addClass("d-none");

				$('.view_more_details_less_div').removeClass("d-none");
				$('.view_more_details_less_div').addClass("d-block");

				$('.user_more_details').removeClass("d-none");
				$('.user_more_details').addClass("d-block");

			});		
			$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
			{
				$('.view_more_details_div').removeClass("d-none");
				$('.view_more_details_div').addClass("d-block");

				$('.view_more_details_less_div').removeClass("d-block");
				$('.view_more_details_less_div').addClass("d-none");

				$('.user_more_details').removeClass("d-block");
				$('.user_more_details').addClass("d-none");
			});
			//------------ Parent List JS ----------//
			var table =  jQuery('#parents_list').DataTable({
					responsive: true,
					"order": [[ 0, "DESC" ]],
					"aoColumns":[	                  
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}],		
					language:<?php echo mj_smgt_datatable_multi_language();?>	
			});
	});

</script>
<!--------- Script Start --------->
<?php 
$custom_field_obj =new Smgt_custome_field;
$obj_mark = new Marks_Manage(); 
$role	='student';
//--------- Save Active User ------------//
if(isset($_POST['active_user']))
{		
	
	$class = get_user_meta($_REQUEST['act_user_id'],'class_name',true);		
	$args = array('meta_query'	=>
		array('relation' => 'AND',
			array('key'	=>'class_name','value'=>$class),
			array('key'=>'roll_id','value'=>$_REQUEST['roll_id'])
		),
		'role'=>'student');
				
	$userbyroll_no	=	get_users($args);		
	$is_rollno = count($userbyroll_no);	
		
	if($is_rollno)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3'); 
	}
	else
	{	
		update_user_meta( $_POST['act_user_id'], "roll_id", $_POST['roll_id'] );	
		if(isset($_POST['smgt_student_mail_service_enable']) || isset($_POST['smgt_studnet_sms_service_enable']) )
		{
			if(isset($_POST['smgt_student_mail_service_enable']))
			{
				$active_user_id		= 	$_REQUEST['act_user_id'];			
				$class_name		=	get_user_meta($active_user_id,'class_name',true);
				$user_info 	= 	get_userdata($_POST['act_user_id']);
					$to 	= 	$user_info->user_email;           
					$subject	= 	get_option('student_activation_title'); 
					$Seach['{{student_name}}']	 =	 $user_info->display_name;
					$Seach['{{user_name}}']		 =	 $user_info->user_login;
					$Seach['{{class_name}}']	 =	 mj_smgt_get_class_name($class_name);
					$Seach['{{email}}']			 =	 $to;
					$Seach['{{school_name}}']	 =	 get_option( 'smgt_school_name' );			
					$MsgContent 	= 	mj_smgt_string_replacement($Seach,get_option('student_activation_mailcontent'));
					mj_smgt_send_mail($to,$subject,$MsgContent);
				//----------- STUDENT ASSIGNED TEACHER MAIL ------------//
				$TeacherIDs = mj_smgt_check_class_exits_in_teacher_class($class_name);			
				$TeacherEmail = array();
				$string['{{school_name}}']  = get_option('smgt_school_name');
				$string['{{student_name}}'] =  mj_smgt_get_display_name($_POST['act_user_id']);
				$subject = get_option('student_assign_teacher_mail_subject');
				$MessageContent = get_option('student_assign_teacher_mail_content');			
				foreach($TeacherIDs as $teacher)
				{		
					$TeacherData = get_userdata($teacher);		
					$string['{{teacher_name}}']= mj_smgt_get_display_name($TeacherData->ID);
					$message = mj_smgt_string_replacement($string,$MessageContent);	
					mj_smgt_send_mail($TeacherData->user_email,$subject,$message);
				}
			}
			/* Approved SMS Notification */
			if(isset($_POST['smgt_studnet_sms_service_enable'])) 
			{
				$message_content = "Your account with ".get_option( 'smgt_school_name' )." is approved";
				$type = "Attendance";
				$sms = MJ_smgt_send_sms_notification($_POST['act_user_id'],$type,$message_content);
			}	
		}		
		$active_user_id	= $_REQUEST['act_user_id'];
		if(get_user_meta($active_user_id, 'hash', true))
			delete_user_meta($active_user_id, 'hash');
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=7');			
	}
}

// Export Student CSV File Code 
if(isset($_REQUEST['exportstudentin_csv']))
{
	
	if($_POST['class_name'] != "" && $_POST['class_section'] == "") //------- Only Class Select -------//
	{		
		$student_list = get_users(array('meta_key' => 'class_name', 'meta_value' => $_POST['class_name'], 'role'=>'student'));
	
	}
	elseif($_POST['class_section'] != "") //--------- Class Section ----------//
	{
		$args = array(
			'role'=>'student',
			'meta_query' => array(
			array(
				'key' => 'class_name',
				'value' => $_POST['class_name'],					
			),
			array(
				'key' => 'class_section',
				'value' =>$_POST['class_section'] 					
			)
			)
		);			
		$student_list = get_users($args);
	}		
	else
	{
		$student_list = get_users(array('role'=>'student'));
	}
	//----- CSV File Add Custom export csv File ------//
	foreach($student_list as $retrive_data)
	{
		$student_array[] = $retrive_data->ID;
		$student_id = implode(',',$student_array);
	}	
	$module='student';	
	$module_record_id = $student_id;	
	$custom_field_obj =new Smgt_custome_field;
	$all_custom_field_name = $custom_field_obj->mj_smgt_getCustomFieldByModule($module);
		
	if(!empty($student_list))
	{
		$header = array();				
		$header[] = 'Username';
		$header[] = 'Email';
		$header[] = 'Password';
		$header[] = 'Roll No';
		$header[] = 'Class Name';
		$header[] = 'Class Section';
		$header[] = 'First Name';
		$header[] = 'Middle Name';
		$header[] = 'Last Name';			
		$header[] = 'Gender';
		$header[] = 'Birth Date';
		$header[] = 'Address';
		$header[] = 'City Name';
		$header[] = 'State Name';
		$header[] = 'Zip Code';
		$header[] = 'Mobile Number';
		$header[] = 'Alternate Mobile Number';			
		$header[] = 'user_profile';
		//-----add custom filed label in excel sheet  ----//
		foreach($all_custom_field_name as $custom_field)
		{
			$header[]=$custom_field->field_label;
		}
		$filename='Reports/export_student.csv';
		$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
		fputcsv($fh, $header);
		
		foreach($student_list as $retrive_data)
		{
			$uid=$retrive_data->ID;
			$umetadata=mj_smgt_get_user_image($uid);
			
			$row = array();
			$user_info = get_userdata($retrive_data->ID);
			
			$row[] = $user_info->user_login;
			$row[] = $user_info->user_email;
			$row[] = '';
			$row[] =  get_user_meta($retrive_data->ID, 'roll_id',true);				
			$class_id=  get_user_meta($retrive_data->ID, 'class_name',true);	
			$classname=mj_smgt_get_class_name($class_id);
			$row[] = $classname;
			$class_section_id=  get_user_meta($retrive_data->ID, 'class_section',true);		
			$class_sections_name=mj_smgt_get_class_sections_name($class_section_id);	
			$row[] = $class_sections_name;	
			$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
			$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
			$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
			$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
			$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
			$row[] =  get_user_meta($retrive_data->ID, 'address',true);
			$row[] =  get_user_meta($retrive_data->ID, 'city',true);
			$row[] =  get_user_meta($retrive_data->ID, 'state',true);
			$row[] =  get_user_meta($retrive_data->ID, 'zip_code',true);
			$row[] =  get_user_meta($retrive_data->ID, 'mobile_number',true);
			$row[] =  get_user_meta($retrive_data->ID, 'alternet_mobile_number',true);
			$uid=$retrive_data->ID;
			$umetadata=mj_smgt_get_user_image($uid);
			if(!empty($umetadata))
			{
				$row[] =  $umetadata;
			}
			else{
				$row[] = '';
			}
			
			//-----add custom filed value in excel sheet  ----//
			foreach($all_custom_field_name as $custom_field)
			{ 
				//$row[] = $custom_field->id;
				$all_custom_field_value = $custom_field_obj->mj_smgt_get_single_field_value_meta_value_by_filed_and_student_id($retrive_data->ID,$custom_field->id);
				
				if($custom_field->field_type == "file")
				{
					$row[] = content_url().'/uploads/school_assets/'.$all_custom_field_value;
				}
				else
				{
					$row[] = $all_custom_field_value;
				}
				
			}
			fputcsv($fh, $row);		
		}
		
		fclose($fh);

		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_student.csv';//file location
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
		exit;		
	}
	else
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&message=10');
	}
}
//--------- Save Student -----------//
if(isset($_POST['save_student']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_teacher_admin_nonce' ) )
	{
		$firstname	=	mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
		$lastname	=	mj_smgt_strip_tags_and_stripslashes($_POST['last_name']);
		$middlename = mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);
		
		$userdata 	= 	array(
			'user_login'	=>	mj_smgt_email_validation($_POST['email']),			
			'user_nicename'	=>	NULL,
			'user_email'	=>	mj_smgt_email_validation($_POST['email']),
			'user_url'		=>	NULL,
			'display_name'	=>	$firstname." ".$middlename." ".$lastname,
		);
		
		//--------- Password & Email Varification --------//
		if($_POST['password'] != "")
			$userdata['user_pass']=mj_smgt_password_validation($_POST['password']);
			
		if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
		{
			$photo	=	$_POST['smgt_user_avatar'];
		}
		else
		{
			$photo	=	"";
		}
		if (get_option( 'smgt_enable_virtual_classroom' ) == 'yes')
		{
			$zoom_add_status = 'yes';
		}
		else
		{
			$zoom_add_status = 'no';
		}
		if(isset($_POST['phone']) && $_POST['phone'] != "")
		{
			$phone	=	$_POST['phone'];
		}
		else
		{
			$phone	="";
		}
		$usermetadata	=	array(
			'roll_id'	=>	mj_smgt_address_description_validation($_POST['roll_id']),
			'middle_name'	=>	mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
			'gender'	=>	mj_smgt_onlyLetterSp_validation($_POST['gender']),
			'birth_date'=>	$_POST['birth_date'],
			'address'	=>	mj_smgt_address_description_validation($_POST['address']),
			'city'		=>	mj_smgt_city_state_country_validation($_POST['city_name']),
			'state'		=>	mj_smgt_city_state_country_validation($_POST['state_name']),
			'zip_code'	=>	mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
			'class_name'	=>	mj_smgt_onlyNumberSp_validation($_POST['class_name']),
			'class_section'	=>	mj_smgt_onlyNumberSp_validation($_POST['class_section']),
			'phone'		=>	mj_smgt_phone_number_validation($phone),
			'mobile_number'	=>	mj_smgt_phone_number_validation($_POST['mobile_number']),
			'alternet_mobile_number'	=>	mj_smgt_phone_number_validation($_POST['alternet_mobile_number']),
			'smgt_user_avatar'	=>	$photo,	
			'zoom_add_status'	=>	$zoom_add_status,		
			'created_by'=>get_current_user_id()			
		);
		$userbyroll_no	=	get_users(
			array('meta_query'	=>
				array('relation' => 'AND',
					array('key'	=>'class_name','value'=>$_POST['class_name']),
					array('key'=>'roll_id','value'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']))
				),
				'role'=>'student')
		);
		$is_rollno = count($userbyroll_no);	
		if($_REQUEST['action']=='edit') //--------- Edit Student ----------//
		{
			
			$userdata['ID']	=	$_REQUEST['student_id'];
			$roll_no_cheack = mj_smgt_cheack_student_rollno_exist_or_not($_POST['roll_id'],$_REQUEST['student_id']);
			if($roll_no_cheack == 1)
			{ 
				$result	=	mj_smgt_update_user($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);

				$student = $userdata['display_name'];
				school_append_audit_log(''.esc_html__('Student Updated','hospital_mgt').'('.$student.')'.'',$_REQUEST['student_id'],get_current_user_id(),'edit',$_REQUEST['page']);
				// Custom Field File Update //
				$custom_field_file_array=array();
				
				if(!empty($_FILES['custom_file']['name']))
				{
					$count_array=count($_FILES['custom_file']['name']);
					for($a=0;$a<$count_array;$a++)
					{			
						foreach($_FILES['custom_file'] as $image_key=>$image_val)
						{
							foreach($image_val as $image_key1=>$image_val2)
							{
								if($_FILES['custom_file']['name'][$image_key1]!='')
								{ 
									$custom_file_array[$image_key1]=array(
									'name'=>$_FILES['custom_file']['name'][$image_key1],
									'type'=>$_FILES['custom_file']['type'][$image_key1],
									'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],
									'error'=>$_FILES['custom_file']['error'][$image_key1],
									'size'=>$_FILES['custom_file']['size'][$image_key1]
									);							
								}						
							}
						}
					}	
					
					$file_value=$_REQUEST['hidden_custom_file'];
					
					foreach($file_value as $filed_key=>$filed_val)
					{
						
						if($filed_val!="")
						{
						
							if(!empty($custom_file_array))
							{
								foreach($custom_file_array as $key=>$value)		
								{
									global $wpdb;
									$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
									$get_file_name=$custom_file_array[$key]['name'];
									$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);
									//Add File in Custom Field Meta//				
									$module='student';					
									$updated_at=date("Y-m-d H:i:s");
									$update_custom_meta_data =$wpdb->query($wpdb->prepare("UPDATE `$wpnc_custom_field_metas` SET `field_value` = '$custom_field_file_value',updated_at='$updated_at' WHERE `$wpnc_custom_field_metas`.`module` = %s AND  `$wpnc_custom_field_metas`.`module_record_id` = %d AND `$wpnc_custom_field_metas`.`custom_fields_id` = %d",$module,$result,$key));
								
								} 	
							}		 		
						}
						else
						{
						
							if(!empty($custom_file_array))
							{
								foreach($custom_file_array as $key=>$value)		
								{	
									global $wpdb;
									$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
									$get_file_name=$custom_file_array[$key]['name'];	
									$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);		
								
									//Add File in Custom Field Meta//
									$custom_meta_data['module']='student';
									$custom_meta_data['module_record_id']=$result;
									$custom_meta_data['custom_fields_id']=$key;
									$custom_meta_data['field_value']=$custom_field_file_value;
									$custom_meta_data['created_at']=date("Y-m-d H:i:s");
									$custom_meta_data['updated_at']=date("Y-m-d H:i:s");
									$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );
									
								} 	
							}		 		
						}

					}
					
				}
			
				$update_custom_field=$custom_field_obj->mj_smgt_update_custom_field_metas('student',$_POST['custom'],$result);
				
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=2');
			} 
			else
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3');
			} 
										
		}
		else //---------- Insert Student -----------//
		{ 
			/* Setup Wizard */
			smgt_setup_wizard_steps_updates('step6_student');

			if( !email_exists( $_POST['email'] ))
			{			
				if($is_rollno)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3'); 
				}
				else 
				{			
					$result	= mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
					
					$student = $userdata['display_name'];
					school_append_audit_log(''.esc_html__('Student Added','hospital_mgt').'('.$student.')'.'',$result,get_current_user_id(),'insert',$_REQUEST['page']);
					// Custom Field File Insert //
					$custom_field_file_array=array();
					if(!empty($_FILES['custom_file']['name']))
					{
						$count_array=count($_FILES['custom_file']['name']);
						
						for($a=0;$a<$count_array;$a++)
						{			
							foreach($_FILES['custom_file'] as $image_key=>$image_val)
							{
								foreach($image_val as $image_key1=>$image_val2)
								{
									if($_FILES['custom_file']['name'][$image_key1]!='')
									{  	
										$custom_file_array[$image_key1]=array(
										'name'=>$_FILES['custom_file']['name'][$image_key1],
										'type'=>$_FILES['custom_file']['type'][$image_key1],
										'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],
										'error'=>$_FILES['custom_file']['error'][$image_key1],
										'size'=>$_FILES['custom_file']['size'][$image_key1]
										);							
									}	
								}
							}
						}			
						if(!empty($custom_file_array))
						{
							foreach($custom_file_array as $key=>$value)		
							{	
								global $wpdb;
								$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
								$get_file_name=$custom_file_array[$key]['name'];	
								$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);		
								
								//Add File in Custom Field Meta//
								$custom_meta_data['module']='student';
								$custom_meta_data['module_record_id']=$result;
								$custom_meta_data['custom_fields_id']=$key;
								$custom_meta_data['field_value']=$custom_field_file_value;
								$custom_meta_data['created_at']=date("Y-m-d H:i:s");
								$custom_meta_data['updated_at']=date("Y-m-d H:i:s");
								$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );
								
							} 	
						}		 		
					}
					$add_custom_field=$custom_field_obj->mj_smgt_add_custom_field_metas('student',$_POST['custom'],$result);					
					
					if($result)
					{ 
						wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=1'); 	  
					}
				}
			}
			else 
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=4');
			}
		}
	}
}	
// -----------Delete Student Code -------- //
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$childs=get_user_meta($_REQUEST['student_id'], 'parent_id', true);
	if(!empty($childs))
	{
		foreach($childs as $key=>$childvalue)
		{					
			$parents=get_user_meta($childvalue, 'child',true);
			if(!empty($parents))
			{
				if(($key = array_search($_REQUEST['student_id'], $parents)) !== false) 
				{
					unset($parents[$key]);						
					update_user_meta( $childvalue,'child', $parents );							
				}					
			}				
		}
	}
		
	$result=mj_smgt_delete_usedata($_REQUEST['student_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=5');
	}
}
//------- multiple Student record delete code -------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$childs=get_user_meta($id, 'parent_id', true);			
		if(!empty($childs))
		{
			foreach($childs as $key=>$childvalue)
			{						
				$parents=get_user_meta($childvalue, 'child',true);						
				if(!empty($parents))
				{
					if(($key = array_search($id, $parents)) !== false)
					{
						unset($parents[$key]);						
						update_user_meta( $childvalue,'child', $parents );								
					}						
				}					
			}
		}			
		$result=mj_smgt_delete_usedata($id);
	}

	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=5');
	}
}


//---------- Student Print ------------//
if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')
{
	$sudent_id = $_REQUEST['student'];
	mj_smgt_downlosd_smgt_result_pdf($sudent_id);
}
//------ upload CSV Code  -----------//
if(isset($_REQUEST['upload_csv_file']))
{
	
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'upload_teacher_admin_nonce' ) )
	{
		if(isset($_FILES['csv_file']))
		{				
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];
			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));				
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			if(in_array($file_ext,$extensions )=== false)
			{
				$err=esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
				$errors[]=$err;
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=uploadstudent&message=8');
			}
			//------------ Check File Size ------------//
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=uploadstudent&message=9');
			}
			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));
				
				$header = array_map('trim',array_map('strtolower',array_shift($rows)));

				$arraycheck = array('username', 'email', 'password', 'roll no', 'class name', 'class section', 'first name', 'middle name', 'last name', 'gender', 'birth date', 'address', 'city name', 'state name', 'zip code', 'mobile number', 'alternate mobile number', 'user_profile');

				$fields = array_diff($header, $arraycheck);
				
				
				
				$csv = array();
			
				
				foreach ($rows as $row) 
				{
					
					$csv = array_combine($header, $row);
					
					$custom_fields = array();
					$username = $csv['username'];
					$email = $csv['email'];
					$user_id = 0;
					if(isset($csv['password']))
					{
					    $password = $csv['password'];
					}
					else
					{
						$password = rand();
					}
					$problematic_row = false;
					$studentExists = true;
					if( username_exists($username) )
					{ // if user exists, we take his ID by login
						$user_object = get_user_by( "login", $username );
						$user_id = $user_object->ID;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					elseif( email_exists( $email ) )
					{ // if the email is registered, we take the user from this
						$user_object = get_user_by( "email", $email );
						$user_id = $user_object->ID;					
						$problematic_row = true;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					else
					{
						$studentExists = false;
						if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
							$password = wp_generate_password();						
							$user_id = wp_create_user($username, $password, $email);
						
					}

					if( is_wp_error($user_id) ){ // in case the user is generating errors after this checks
						echo '<script>alert("Problems with user: ' . $username . ', we are going to skip");</script>';
						continue;
					}
					
					if(!(is_multisite() && is_super_admin( $user_id ) ))
						
						$studentClass = get_user_meta($user_id,"class_name",true);
						wp_update_user(array ('ID' => $user_id, 'role' => 'student')) ;
						update_user_meta( $user_id, "active", true );
						update_user_meta( $user_id, "class_name", $_POST['class_name']);					
					
						$userbyroll_no	=	get_users(
							array('meta_query'	=>
								array('relation' => 'AND',
									array('key'	=>'class_name','value'=>$_POST['class_name']),
									array('key'=>'roll_id','value'=>$csv['roll no'])
								),
								'role'=>'student'
							)
						);
						
						$rollReset = true;
						if($studentExists){
							$rollReset = false;
						}else{
							// student not exists
							if(count($userbyroll_no) || $csv['roll no'] == '')
							{  // roll exists
								$rollReset = true;
							}else{ // roll not exists
								$rollReset = false;
							}
						}

						if($rollReset)
						{
							$roll = "";	
							add_user_meta($user_id,'hash',rand());
						}
						else{						
							$roll = $csv['roll no'];
						}
						
						//---------- Student Record Insert ----------//
						$user_id1 = wp_update_user( array( 'ID' => $user_id, 'display_name' =>$csv['first name'].' '.$csv['middle name'].' '.$csv['last name']) );
						if(isset($_POST['class_section']))
							update_user_meta( $user_id, "class_section", $_POST['class_section'] );
						if(isset($csv['roll no']))
							update_user_meta( $user_id, "roll_id", $roll );
						if(isset($csv['first name']))
							update_user_meta( $user_id, "first_name", $csv['first name'] );
						if(isset($csv['last name']))
							update_user_meta( $user_id, "last_name", $csv['last name'] );
						if(isset($csv['middle name']))
							update_user_meta( $user_id, "middle_name", $csv['middle name'] );
						if(isset($csv['gender']))
							update_user_meta( $user_id, "gender", $csv['gender'] );
						if(isset($csv['birth date']))
							update_user_meta( $user_id, "birth_date", $csv['birth date'] );
						if(isset($csv['address']))
							update_user_meta( $user_id, "address", $csv['address'] );
						if(isset($csv['city name']))
							update_user_meta( $user_id, "city", $csv['city name'] );
						if(isset($csv['state name']))
							update_user_meta( $user_id, "state", $csv['state name'] );						
						if(isset($csv['zip code']))
							update_user_meta( $user_id, "zip_code", $csv['zip code'] );
						if(isset($csv['mobile number']))
							update_user_meta( $user_id, "mobile_number", $csv['mobile number'] );
						if(!empty($csv['user_profile']))
						{
							$upload_dir   = wp_upload_dir();
							$photo = $upload_dir['baseurl'].'/'.$csv['user_profile'];
							update_user_meta( $user_id, "smgt_user_avatar", $photo);	
						}
						if(isset($csv['alternate mobile number']))
							update_user_meta( $user_id, "alternet_mobile_number", $csv['alternate mobile number'] );						
						if(isset($csv['phone number']))
							update_user_meta( $user_id, "phone", $csv['phone number'] );

						foreach($fields as $field_name)
						{
							global $wpdb;
							$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
							$custom_field = mj_smgt_get_single_custom_field_data_by_name($field_name);
							if($custom_field->field_type == "date")
							{
								$field_value = date("Y-m-d", strtotime($csv[$field_name]));
							}
							elseif($custom_field->field_type == "file")
							{
								
								if(!empty($csv[$field_name]))
								{
									$upload_dir   = wp_upload_dir();
									$field_value = $csv[$field_name];
								}
								else{
									$field_value ='';
								}
							}
							else{
								$field_value = $csv[$field_name];
							}
							$custom_meta_data['module']='student';
							$custom_meta_data['module_record_id']=$user_id;
							$custom_meta_data['custom_fields_id']=$custom_field->id;
							$custom_meta_data['field_value']=$field_value;
							$custom_meta_data['created_at']=date("Y-m-d H:i:s");
							$custom_meta_data['updated_at']=date("Y-m-d H:i:s");
							
							$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );
						}
						
						
				}
				$success = 1;
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
					
			if(isset($success))
			{
				if($_REQUEST['smgt_import_student_mail'] == '1')
				{
					if($user_id)
					{
						$userdata = get_userdata($user_id);
						
						$string = array();
	
						$string['{{user_name}}']   =  $userdata->display_name;
	
						$string['{{school_name}}'] =  get_option('smgt_school_name');
	
						$string['{{role}}']        =  'student';
	
						$string['{{login_link}}']  =  site_url() .'/index.php/school-management-login-page';
	
						$string['{{username}}']    =  $userdata->user_email;
	
						$string['{{Password}}']    =  $password;
	
						$MsgContent                =  get_option('add_user_mail_content');
	
						$MsgSubject				   =  get_option('add_user_mail_subject');
	
						$message = mj_smgt_string_replacement($string,$MsgContent);
	
						$MsgSubject = mj_smgt_string_replacement($string,$MsgSubject);
	
						$email=  $userdata->user_email;
					
						mj_smgt_send_mail($email,$MsgSubject,$message);
					}
				}
				
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=6');
			} 
		}
	}
}
?>
<!-- POP up code -->


<?php 
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1) 
{ 
	?>
	<div class="page-inner"><!--------- Page Inner ----------->
		<div class="page-title"> 
			<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle rounded-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
		</div>
		<?php
		$student_data=get_userdata($_REQUEST['student_id']);
		?>
		<div class="main_list_margin_15px">
			<div class="row"> <!-------- Row Div ----------->
				<div class="panel panel-white"><!-------- Penal White ----------->
					<div class="panel-body"><!-------- Penal Body ----------->
						<h2 class="nav-tab-wrapper">
							<a href="?page=smgt_student&student_id=<?php echo $_REQUEST['student_id'];?>&attendance=1" class="nav-tab nav-tab-active">
							<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('View Attendance', 'school-mgt'); ?></a>
						</h2>
						<form name="wcwm_report" action="" method="post"><!-------- Attendance Form Start ----------->
							<input type="hidden" name="attendance" value=1> 
							<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>> 
							<div class="row">
								<div class="col-md-3 col-sm-4 col-xs-12">	
									<?php
									$umetadata=mj_smgt_get_user_image($_REQUEST['student_id']);
									if(empty($umetadata))
									{
										echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.get_option( 'smgt_student_thumb_new' ).'>';
									}
									else
										echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata.'>';
									?>
								</div>
									
								<div class="col-md-9 col-sm-8 col-xs-12 ">
									<div class="row">
										<h2><?php echo $student_data->display_name;?></h2>
									</div>
									<div class="row">
										<div class="col-md-4 col-sm-3 col-xs-12">
											<i class="fa fa-envelope"></i>&nbsp;
											
											<span class="email-span"><?php echo $student_data->user_email;?></span>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-12">
											<i class="fa fa-phone"></i>&nbsp;
											<span><?php echo $student_data->phone;?></span>
										</div>
										<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
											<i class="fa fa-list-alt"></i>&nbsp;
											<span><?php echo $student_data->roll_id;?></span>
										</div>
									</div>					
								</div>
							
								<div class="mt-4 row">
									<div class="form-group col-md-3">
										<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
										<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
									</div>
									<div class="form-group col-md-3">
										<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
										<input type="text"  class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
									</div>
									<div class="form-group col-md-3 button-possition">
										<label for="subject_id">&nbsp;</label>
										<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
									</div>
								</div>	
						</form><!-------- Attendance Form Start ----------->
						<div class="clearfix"></div>
						<?php 
						if(isset($_REQUEST['view_attendance']))
						{	
							$start_date = $_REQUEST['sdate'];
							$end_date = $_REQUEST['edate'];
							$user_id = $_REQUEST['user_id'];
							$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);	
							$curremt_date =$start_date;
							?>	

							<div class="panel-body"><!------- Penal Body --------->
								<div class="table-responsive"> <!------- Table Responsive ---------->
									<table id="attendance_list" class="display" cellspacing="0" width="100%"><!--------- Student list ---------->
										<thead>
											<tr>
												<th width="200px"><?php esc_attr_e('Student Name','school-mgt');?></th>
												<th width="200px"><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
												<th><?php esc_attr_e('Day','school-mgt');?></th>
												<th><?php esc_attr_e('Attendance','school-mgt');?></th>
												<th><?php esc_attr_e('Comment','school-mgt');?></th>
											</tr>
										</thead>
								
										<tfoot>
											<tr>
												<th width="200px"><?php esc_attr_e('Student Name','school-mgt');?></th>
												<th width="200px"><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
												<th><?php esc_attr_e('Day','school-mgt');?></th>
												<th><?php esc_attr_e('Attendance','school-mgt');?></th>
												<th><?php esc_attr_e('Comment','school-mgt');?></th>
											</tr>
										</tfoot>
								
										<tbody>
											<?php
												foreach($attendance as $attendance_data)
												{
														
													echo '<td>';
													echo mj_smgt_get_display_name($attendance_data->user_id);
													echo '</td>';
													
													
													echo '<td>';
													echo mj_smgt_get_class_name_by_id(get_user_meta($attendance_data->user_id, 'class_name',true));
													echo '</td>';
													
													echo '<td>';
													echo mj_smgt_getdate_in_input_box($attendance_data->attendence_date);
													echo '</td>';
													

													echo '<td>';
													echo esc_attr_e(date("D", strtotime($attendance_data->attendence_date)),'school-mgt');
													echo '</td>';
													
													$attendance_status = $attendance_data->status;
													if(!empty($attendance_status))
													{
														echo '<td>';
														if($attendance_status=="Present")
														{
															echo esc_attr__('Present','school-mgt');
														}
														elseif($attendance_status=="Late")
														{
															echo esc_attr__('Late','school-mgt');
														}
														else
														{
															echo esc_attr__('Absent','school-mgt');
														}
														echo '</td>';
													}
													else 
													{
														echo '<td>';
														echo esc_attr__('Absent','school-mgt');
														echo '</td>';
													}
													echo '<td>';
													echo $attendance_data->comment;
													echo '</td>';
													echo '</tr>';
												}
											?>
										</tbody>     
									</table><!--------- Student list ---------->
								</div>	<!------- Table Responsive ---------->
							</div><!------- Penal Body ---------->
							<?php 
						} ?>
					</div><!-------- Penal Body ----------->
				</div><!-------- Penal White ----------->
			</div><!-------- Row Div ----------->
		</div>
	</div><!-------- Page Inner ----------->
	<?php 
}
else 
{
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'studentlist';
	?>
	
	<div class="page-inner"><!--------- page inner -------->
		<div class="main_list_margin_15px"><!----- list page padding --------->
			<?php
			//---------- Student Messages ---------//
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
			switch($message) 
			{
				case '1':
					$message_string = esc_attr__('Student Added Successfully.','school-mgt');
					break;
				case '2':
					$message_string = esc_attr__('Student Updated Successfully.','school-mgt');
					break;
				case '3':
					$message_string = esc_attr__('Student Roll No. Already Exist.','school-mgt');
					break;
				case '4':
					$message_string = esc_attr__("Student's Username Or Email-id Already Exist.",'school-mgt');
					break;
				case '5':
					$message_string = esc_attr__('Student Deleted Successfully.','school-mgt');
					break;
				case '6':
					$message_string = esc_attr__('Student CSV Uploaded Successfully.','school-mgt');
					break;
				case '7':
					$message_string = esc_attr__('Student Record Approved Successfully.','school-mgt');
					break;
				case '8':
					$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
					break;
				case '9':
					$message_string = esc_attr__('File size limit 2 MB.','school-mgt');
					break;
				case '10':
					$message_string = esc_attr__('Records not found.','school-mgt');
					break;
			}
			if($message)
			{ ?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible" style="margin: 7px -10px !important">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php 
			} ?>
			<div class="row"> <!------- Row Div --------->
				<div class="col-md-12 padding_0">
					<div class="smgt_main_listpage">
						<?php 
							//---------- Student list tab --------//
							if($active_tab == 'studentlist')
							{	
								?>
								<div class="popup-bg">
									<div class="overlay-content max_height_overflow">
										<div class="modal-content">
											<div class="result"></div>
											<div class="view-parent"></div>
											<div class="student_list">
											</div>
											<div class="category_list">
											</div>
										</div>
									</div>    
								</div>
								<?php
								if(get_option('smgt_enable_video_popup_show') == 'yes')
								{
								?>
								<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/Nk-iedcC4Y0?si=OshxLuh2R77dCUWT";?>" title="Student ID Card">
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
								</a>
								<?php  
								}
								
								if(isset($_REQUEST['filter_class']) )
								{
									if(empty($_REQUEST['class_id']) && empty($_REQUEST['class_section']))
									{
										$exlude_id = mj_smgt_approve_student_list();
										$studentdata =get_users(array('role'=>'student'));
										
									}
									elseif(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")
									{
										$class_id =$_REQUEST['class_id'];
										$class_section =$_REQUEST['class_section'];
										$studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student'));	
									}
									elseif(isset($_REQUEST['class_id']) && ($_REQUEST['class_section']) == "")
									{
										$class_id =$_REQUEST['class_id'];
										$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));	
									}
								}	
								else 
								{
									$exlude_id = mj_smgt_approve_student_list();
									$studentdata =get_users(array('role'=>'student'));
								}
								if(!empty($studentdata))
								{	
									?>  
									<div id="show_student_list">
										<div class="loader">
											<img src="<?php echo SMS_PLUGIN_URL.'/assets/images/school_app_loader_gif.gif'?>" alt="">
										</div>
										<div class="table-responsive" style="display:none;"><!-------- Table Responsive ----------->
											<form id="frm-example" name="frm-example" method="post"><!-------- Student Form Start ----------->
												<table id="students_list" class="display" cellspacing="0" width="100%">
													<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
														<tr>
															<th class="checkbox_width_10px text-end"><input type="checkbox" class="smgt_sub_chk select_all" id="select_all" style="float:left;"></th>
															<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
															<th><?php echo esc_attr_e( 'Student Name & Email', 'school-mgt' ) ;?></th>
															<th> <?php echo esc_attr_e( 'Mobile No.', 'school-mgt' ) ;?></th>
															<th> <?php echo esc_attr_e( 'Class & Section', 'school-mgt' ) ;?></th>
															<th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
															<th> <?php echo esc_attr_e( 'Gender', 'school-mgt' ) ;?></th>
															<?php
															if(!empty($user_custom_field)){
																foreach($user_custom_field as $custom_field)
																{
																	if($custom_field->show_in_table == "1")
																	{
																		?>
																		<th> <?php echo esc_attr_e( $custom_field->field_label, 'school-mgt' ) ;?></th>
																		<?php
																	}
																}
															}
															?>
															<th> <?php echo esc_attr_e( 'Status', 'school-mgt' ) ;?></th>
															<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
														</tr>
													</thead>
													<tbody>
														<?php 
														if(!empty($studentdata))
														{
															foreach ($studentdata as $retrieved_data)
															{ 
											
																?>
																<tr>
																	
																	<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk check_for_id" value="<?php echo esc_attr($retrieved_data->ID); ?>"></td>
																	<td class="user_image width_50px">
																		<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">
																			<?php
																				$uid=$retrieved_data->ID;
																				$umetadata=mj_smgt_get_user_image($uid);
																				if(empty($umetadata))
																				{
																					echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="img-circle" />';
																				}
																				else
																				{
																					echo '<img src='.$umetadata.' class="img-circle" />';
																				}
																			?>
																		</a>
																	</td>
																	<td class="name">
																		<a class="color_black" href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>">
																			<?php echo $retrieved_data->display_name;?>
																		</a>
																		<br>
																		<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
																	</td>
																	<td class="name">
																		+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>
																		<?php
																		if(!empty($retrieved_data->mobile_number)){echo $retrieved_data->mobile_number;}else{echo 'N/A';}
																		?>

																		<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile No.','school-mgt');?>" ></i>
																	</td>
																	<td class="name">
																		<?php 
																		$class_id = get_user_meta($retrieved_data->ID, 'class_name',true);
																		$section_id = get_user_meta($retrieved_data->ID, 'class_section',true);
																		$classname = smgt_get_class_section_name_wise($class_id,$section_id);
																		if(!empty($classname))
																		{
																			echo $classname;
																		}
																		else
																		{
																			echo "N/A";
																		}
																	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class & Section','school-mgt');?>" ></i>
																	</td>
																	<td class="roll_no">
																		<?php 
																			if(get_user_meta($retrieved_data->ID, 'roll_id', true))
																			{
																				echo get_user_meta($retrieved_data->ID, 'roll_id',true);
																			}
																			else
																			{
																				echo "N/A";
																			}
																		?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Roll No.','school-mgt');?>" ></i>
																	</td>

																	<td class="gender">
																		<?php 
																				
																			echo esc_attr_e(ucfirst($retrieved_data->gender),'school-mgt');
																		?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Gender','school-mgt');?>" ></i>
																	</td>
																	<?php
																	// Custom Field Values
																	if(!empty($user_custom_field))
																	{
																		foreach($user_custom_field as $custom_field)
																		{
																			if($custom_field->show_in_table == "1")
																			{
																				$module='student';
																				$custom_field_id = $custom_field->id;
																				$module_record_id = $retrieved_data->ID;
																				$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
																				if($custom_field->field_type =='date')
																				{
																					?>
																					<td><?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo 'N/A'; } ?></td>
																					<?php
																				}
																				elseif($custom_field->field_type =='file')
																				{
																					?>
																					<td>
																						<?php
																						if(!empty($custom_field_value))
																						{
																							?>
																							<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">

																							<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a>
																							<?php
																						}
																						else
																						{
																							echo 'N/A';
																						}
																						?>
																					</td>
																					<?php
																				}
																				else
																				{
																					?>
																					<td> <?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo 'N/A'; } ?> </td>
																					<?php
																				}

																			}
																		}
																	}
																	?>
																	<td> <?php 
																		if( get_user_meta($retrieved_data->ID, 'hash', true))
																		{
																			echo '<span class="btn btn-success active-user" idtest="'.$retrieved_data->ID.'"> ';
																				esc_attr_e('Active', 'school-mgt' ) ;
																			echo " </span>";
																		}
																		else
																		{
																			echo '<span style="color:green;">';
																			esc_attr_e( 'Approved', 'school-mgt' );
																			echo " </span>";
																		}	
																		?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Status','school-mgt');?>" ></i>
																	</td>
																	<td class="action"> 
																		<div class="smgt-user-dropdown">
																			<ul class="" style="margin-bottom: 0px !important;">
																				<li class="">
																					<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																						<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																					</a>
																					<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																						<li class="float_left_width_100">
																							<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i> <?php esc_html_e('View', 'school-mgt' ) ;?> </a>
																						</li>
																						<li class="float_left_width_100">
																							<a href="?page=smgt_student&tab=studentlist&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup float_left_width_100" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i><?php esc_attr_e('View Result', 'school-mgt');?></a>
																						</li>
																						<?php
																						if($user_access_edit == '1')
																						{
																						?>
																							<li class="float_left_width_100 border_bottom_menu">
																								<a href="?page=smgt_student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"></i> <?php esc_html_e( 'Edit', 'school-mgt' ) ;?></a>
																							</li>

																							<?php 
																						} 
																						if($user_access_delete =='1')
																						{
																							?>
																							<li class="float_left_width_100 ">
																							<a href="?page=smgt_student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
																							<i class="fa fa-trash"> </i>  <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>
																							</li>
																							<?php
																						}
																						?>
																					</ul>
																				</li>
																			</ul>
																		</div>										
																	</td>
																</tr>
																<?php 
															} 
														} ?>
													</tbody>        
												</table>
												<!-------- Delete And Select All Button ----------->
												<div class="print-button pull-left">
													<button class="btn btn-success btn-sms-color button_reload" style="margin-bottom: 5px;">
														<input type="checkbox" class="smgt_sub_chk check_for_id select_all" value="" id="select_all" style="margin-top: 0px;">
														<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
													</button>

													<?php 
													if($user_access_delete =='1')
													{ 
														?>
														<button data-toggle="tooltip"  id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
														<?php 
													} 
													?>
													<button data-toggle="tooltip"  title="<?php esc_html_e('Print ID Card','school-mgt');?>" name="print_id_card" class="print_id_card padding_0" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/print.png" ?>" alt=""></button>
													<button data-toggle="tooltip"  title="<?php esc_html_e('Export CSV','school-mgt');?>" type="button" class="view_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>
													<button data-toggle="tooltip"  title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" class="view_import_student_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
												</div>
												<!-------- Delete And Select All Button ----------->
											</form><!-------- Student Form Start ----------->
										</div><!-------- Table Responsive ----------->
									</div>
									<?php 	
								}
								else
								{
									if($user_access_add=='1')
									{
										?>
										<div class="no_data_list_div row"> 
											<div class="offset-md-2 col-md-4">
												<a href="<?php echo admin_url().'admin.php?page=smgt_student&tab=addstudent';?>">
													<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
												</a>
												<div class="col-md-12 dashboard_btn margin_top_20px">
													<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
												</div> 
											</div>
											<div class="col-md-4">
												<a data-toggle="tooltip"  name="import_csv" type="button" class="view_import_student_csv_popup">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/thumb_icon/Import_list.png" ?>" alt="">
												</a>
												<div class="col-md-12 dashboard_btn margin_top_20px">
													<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to import CSV.','school-mgt'); ?> </label>
												</div> 
											</div>
										</div>		
										<?php
									}
									else
									{
										?>
										<div class="calendar-event-new"> 
											<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
										</div>		
										<?php
									}
								}
							}	
							if($active_tab == 'addstudent')
							{
								require_once SMS_PLUGIN_DIR. '/admin/includes/student/student.php';
							}
							if($active_tab == 'uploadstudent')
							{
								require_once SMS_PLUGIN_DIR. '/admin/includes/student/uploadstudent.php';
							}
							if($active_tab == 'view_student')
							{
								require_once SMS_PLUGIN_DIR. '/admin/includes/student/view_student.php';
							}
							if($active_tab == 'exportstudent')
							{
								require_once SMS_PLUGIN_DIR. '/admin/includes/student/exportstudent.php';
							}
							if($active_tab == 'view_exam_receipt')
							{
								require_once SMS_PLUGIN_DIR. '/admin/includes/student/view_exam_receipt.php';
							}
						?>
						</div>
					</div>
				</div>
			</div><!------- Row Div --------->
		</div><!----- list page padding --------->
	</div> <!--------- page inner -------->
	<?php 
} ?>