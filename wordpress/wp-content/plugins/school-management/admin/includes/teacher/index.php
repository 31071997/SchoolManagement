<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('teacher');
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
			if ('teacher' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('teacher' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('teacher' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
<script type="text/javascript">
	jQuery(document).ready(function($){
		"use strict";	
		$('#teacher_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$("body").on("click",".teacher_csv_export_alert",function()
		{
			if ($('.selected_teacher:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}		
		}); 

		$('.sdate').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate:0,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
		$('.edate').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate:0,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		});  
		//---- attendance teacher  table start ----//
		var table =  jQuery('#attendance_teacher_list').DataTable({
			//stateSave: true,
			responsive: true,
			dom: 'Bfrtip',
			buttons: [
				{
					extend: 'print',
					title: 'View Attendance',
				}
			],
			"order": [[ 2, "DESC" ]],
			"aoColumns":[	                  
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},					           
			{"bSortable": false}],	
			language:<?php echo mj_smgt_datatable_multi_language();?>	
		});
		//----  teacher List table start ----//
		var table =  jQuery('#teacher_list').DataTable({
			"initComplete": function(settings, json) {
				$(".print-button").css({"margin-top": "-5%"});
			},
			responsive: true,
			"dom": 'lifrtp',
			"ordering": true,
			"aoColumns":[	
			{"bSortable": false},
			{"bSortable": false},
			{"bSortable": true},
			{"bSortable": true},	                
			{"bSortable": true},
			{"bSortable": true},	                  
			{"bSortable": false}],
			language:<?php echo mj_smgt_datatable_multi_language();?>
		});
		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

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
		$('.smgt_sub_chk').on('change',function()
		{ 
			if(false == $(this).prop("checked"))
			{ 
				$(".select_all").prop('checked', false); 
			}
			if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )
			{
				$(".select_all").prop('checked', true);
			}
		});
	
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

		$('#teacher_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#birth_date').datepicker({
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
			onChangeMonthYear: function(year, month, inst)
			{
				$(this).val(month + "/" + year);
			},
		}); 
		$('#class_name').multiselect({
			nonSelectedText :'<?php esc_html_e('Select Class','school-mgt');?>',
			includeSelectAllOption: true,
			selectAllText : '<?php esc_html_e('Select all','school-mgt');?>',
			templates: {
			button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		});
			
		$('#upload_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

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
	});
</script>
<?php 
$teacher_obj = new Smgt_Teacher;
$role='teacher';
if(isset($_POST['save_teacher']))
{	

	$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
	$middlename= mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);
	$lastname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);
	$userdata = array(
		'user_login'=>mj_smgt_email_validation($_POST['email']),			
		'user_nicename'=>NULL,
		'user_email'=>mj_smgt_email_validation($_POST['email']),
		'user_url'=>NULL,
		'display_name'=>$firstname." ".$middlename." ".$lastname,
	);

	if($_POST['password'] != "")
		$userdata['user_pass']=mj_smgt_password_validation($_POST['password']);
	if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
	{
		$photo=$_POST['smgt_user_avatar'];
	}
	else
	{
		$photo="";
	}
	$attechment='';
	if(!empty($_POST['attachment']))
	{
		$attechment=implode(',',$_POST['attachment']);
	}
	if(!empty($_POST['phone']))
	{
		$phone=$_POST['phone'];
	}
	else
	{
        $phone='';
	}
	if(!empty($_POST['phone']))
	{
		$phone=$_POST['phone'];
	}
	else
	{
        $phone='';
	}
	$usermetadata=array(
		'middle_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
		'gender'=>mj_smgt_onlyLetterSp_validation($_POST['gender']),
		'birth_date'=>$_POST['birth_date'],
		'address'=>mj_smgt_address_description_validation($_POST['address']),
		'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
		'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
		'zip_code'=>mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
		'class_name'=>$_POST['class_name'],
		'phone'=>mj_smgt_phone_number_validation($_POST['phone']),
		'mobile_number'=>mj_smgt_phone_number_validation($_POST['mobile_number']),
		'alternet_mobile_number'=>mj_smgt_phone_number_validation($_POST['alternet_mobile_number']),
		'working_hour'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['working_hour']),
		'possition'=>mj_smgt_address_description_validation($_POST['possition']),
		'smgt_user_avatar'=>$photo,
		'attachment'=>$attechment,
		'created_by'=>get_current_user_id()
	);
	
	if($_REQUEST['action']=='edit')
	{
		$userdata['ID']=$_REQUEST['teacher_id'];
		$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
		$result1 = $teacher_obj->mj_smgt_update_multi_class($_POST['class_name'],$_REQUEST['teacher_id']);
		wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=2'); 		
	}
	else
	{
		/* Setup Wizard */
		$wizard = smgt_setup_wizard_steps_updates('step3_teacher');
		if( !email_exists( $_POST['email'] )) 
		{
			$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
			$result1 = $teacher_obj->mj_smgt_add_muli_class($_POST['class_name'],mj_smgt_strip_tags_and_stripslashes($_POST['email']));
			wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=1'); 			
		}
		else 
		{
			?>
			<div id="message" class="alert updated_top below-h2 notice is-dismissible alert-dismissible">
				<p><?php esc_html_e('Username Or Emailid All Ready Exist.','school-mgt');?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.','school-mgt');?></span></button>
			</div>
			<?php 
		}
	}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{				
	$result=mj_smgt_delete_usedata($_REQUEST['teacher_id']);
	if($result)
	{
	     $teacher_id=$_REQUEST['teacher_id'];
	    global $wpdb;
		$smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';
		$result = $wpdb->query("DELETE FROM $smgt_teacher_class where teacher_id= ".$teacher_id);
		wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=5');
	}
}
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_usedata($id);
			if($result)
			{ 
			  	global $wpdb;
		     	 $smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';
		      	$result = $wpdb->query("DELETE FROM $smgt_teacher_class where teacher_id= ".$id);
				wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=5');  
			}
		}
	}
}
//-------------- EXPORT TEACHER DATA ---------------//
if(isset($_POST['teacher_csv_selected']))
{
	if(isset($_POST['id']))
	{	
		foreach($_POST['id'] as $p_id)
		{
			$teacher_list[]=get_userdata($p_id);
		}
	
		if(!empty($teacher_list))
		{
			$header = array();			
			$header[] = 'Username';
			$header[] = 'Email';
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
			$header[] = 'Class Name';	
			$filename='Reports/export_teacher.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($teacher_list as $retrive_data)
			{
				$row = array();
				$class_name_data = array();
				$user_info = get_userdata($retrive_data->ID);
				
				$teacher_obj = new Smgt_Teacher;
				$teacher_class = $teacher_obj->mj_smgt_get_teacher_class($retrive_data->ID);
				foreach($teacher_class as $class_id)
				{
					$class_name_data[]=mj_smgt_get_class_name_by_id($class_id);
				}
				
				$class_name=implode(",",$class_name_data);
				$row[] =  $user_info->user_login;
				$row[] =  $user_info->user_email;
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
				$row[] =   $class_name;				
				fputcsv($fh, $row);				
			}
			
			fclose($fh);
	
			//download csv file.
			ob_clean();
			$file=SMS_PLUGIN_DIR.'/admin/Reports/export_teacher.csv';//file location
			
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
			header('Connection: close');
			readfile($file);		
			exit;		
		}
		else
		{
			echo "<div style=' background: none repeat scroll 0 0 red;
			border: 1px solid;
			color: white;
			float: left;
			font-size: 17px;
			margin-top: 10px;
			padding: 10px;
			width: 98%;'>Records not found.</div>";
		}
	}
}
//------------------ IMPORT TEACHER --------------------------//
if(isset($_REQUEST['upload_teacher_csv_file']))
{
	
	$nonce = $_POST['_wpnonce'];
	if( wp_verify_nonce( $nonce, 'upload_csv_nonce' ) )
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
				wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=8');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=9');
			}
			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));
				
				$header = array_map('trim',array_map('strtolower',array_shift($rows)));
				 
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					$username = $csv['username'];
					 
					$email = $csv['email'];
					$user_id = 0;
					if(isset($csv['password']))
					{
					  $password = $csv['password'];
					}
					else
					{
						$password = wp_generate_password();
					}
					$problematic_row = false;
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
					   $user_id = wp_create_user($username, $password, $email);
					}
					if( is_wp_error($user_id) )
					{ // in case the user is generating errors after this checks
						?>
						<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/teacher-error.js'; ?>" ></script>

						<?php
						continue;
					}
					 if(!(is_multisite() && is_super_admin( $user_id ) ))
						wp_update_user(array ('ID' => $user_id, 'role' => 'teacher')) ;
					
						$user_id1 = wp_update_user( array( 'ID' => $user_id, 'display_name' =>$csv['first name'].' '.$csv['middle name'].' '.$csv['last name']) );
						$class_array=explode(",",$csv['class name']);
						$teacher_obj = new Smgt_Teacher;
						$result1 = $teacher_obj->mj_smgt_add_muli_class_import($class_array,$username);
						
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
						if(isset($csv['alternate mobile number']))
							update_user_meta( $user_id, "alternet_mobile_number", $csv['alternate mobile number'] );						
						if(isset($csv['phone number']))
							update_user_meta( $user_id, "phone", $csv['phone number'] );					
						$success = 1;
				}
			}
			else
			{
				foreach($errors as &$error)
				{  ?>
						<div id="message" class="alert updated_top below-h2 notice is-dismissible alert-dismissible">
							<p><?php  echo $error; ?></p>
							<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.','school-mgt');?></span></button>
						</div>					
				   
					<?php 
				}
			}
					
			if(isset($success))
			{				
				if($_REQUEST['smgt_import_teacher_mail'] == '1')
				{
					if($user_id)
					{
						$userdata = get_userdata($user_id);
						
						$string = array();
	
						$string['{{user_name}}']   =  $userdata->display_name;
	
						$string['{{school_name}}'] =  get_option('smgt_school_name');
	
						$string['{{role}}']        =  'teacher';
	
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
				wp_redirect ( admin_url().'admin.php?page=smgt_teacher&tab=teacherlist&message=6');
			} 
		}
    }
}
?>
<!-- POP up code Start-->
<div class="popup-bg">
    <div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="category_list">
			</div>
		</div>
    </div>    
</div>
<!-- POP up code End -->
<?php
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1)
{
	?>
	<div class="page-inner"><!--page-inner--> 
		<div class="page-title"> <!--page-title--> 
			<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle rounded-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
		</div><!--page-title-->
		<div id="main-wrapper">	<!--main-wrapper-->
			<div class="row"><!--row-->
				<div class="panel panel-white"><!----panel-white----->
					<h2 class="nav-tab-wrapper">
						<a href="?page=smgt_teacher&teacher_id=<?php echo $_REQUEST['teacher_id'];?>&attendance=1" class="nav-tab nav-tab-active">
						<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('View Attendance', 'school-mgt'); ?></a>
					</h2>
					<form name="wcwm_report" action="" method="post">
						<input type="hidden" name="attendance" value=1> 
						<input type="hidden" name="user_id" value=<?php echo $_REQUEST['teacher_id'];?>>       
						<div class="row">
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
					</form>
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
						<div class="panel-body">
							<div class="table-responsive">
								<table id="attendance_teacher_list" class="display" cellspacing="0" width="100%">
									<tbody>
										<?php 
										while ($end_date >= $curremt_date)
										{	
											echo '<tr>';
											echo '<td>';
											echo mj_smgt_get_display_name($user_id);
											echo '</td>';
											
											echo '<td>';
												$class='';
												$get_users = get_user_meta($user_id, 'class_name',true);
												if(!empty($get_users))
												{
													foreach($get_users as $class_id)
													{
														$class .= mj_smgt_get_class_name_by_id($class_id).", ";
													}
												}
												$space_trim = rtrim($class, ' '); 					
												$class_name = rtrim($space_trim, ','); 					
												echo $class_name;
											echo '</td>';
											
											echo '<td>';
											echo $curremt_date;
											echo '</td>';
											
											$attendance_status = mj_smgt_get_attendence($user_id,$curremt_date);
											echo '<td>';
											$day=date("D", strtotime($curremt_date));
											echo esc_attr__("$day","school-mgt"); 
											echo '</td>';
											
											if(!empty($attendance_status))
											{
												echo '<td>';
												$attendence_status=mj_smgt_get_attendence($user_id,$curremt_date);
												echo esc_attr__("$attendence_status","school-mgt"); 
												echo '</td>';
											}
											else 
											{
												echo '<td>';
												echo esc_attr__('Absent','school-mgt');
												echo '</td>';
											}
											
											echo '</tr>';
											$curremt_date = strtotime("+1 day", strtotime($curremt_date));
											$curremt_date = date("Y-m-d", $curremt_date);
										}
									?>
									</tbody>        
								</table>
							</div>
						</div>
						<?php 
					}?>
				</div><!----panel-white----->
			</div><!--row-->
		</div><!--main-wrapper-->
	</div><!--page-inner--> 
	<?php 
}
else 
{
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'teacherlist';
	?>
	<div class="page-inner"><!-- page-inner -->
		<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
			<?php
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
			switch($message)
			{
				case '1':
					$message_string = esc_attr__('Teacher Added Successfully.','school-mgt');
					break;
				case '2':
					$message_string = esc_attr__('Teacher Updated Successfully.','school-mgt');
					break;
				case '3':
					$message_string = esc_attr__('Roll No Already Exist.','school-mgt');
					break;
				case '4':
					$message_string = esc_attr__('Teacher Username Or Emailid Already Exist.','school-mgt');
					break;
				case '5':
					$message_string = esc_attr__('Teacher Deleted Successfully.','school-mgt');
					break;
				case '6':
					$message_string = esc_attr__('Teacher CSV Uploaded Successfully.','school-mgt');
					break;
				case '7':
					$message_string = esc_attr__('Student Activated Successfully.','school-mgt');
					break;
				case '8':
				$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
				break;
				case '9':
				$message_string = esc_attr__('File size limit 2 MB','school-mgt');
				break;
			}
			if($message)
			{
				?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible" style="margin: 7px -10px !important">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php
			}
			?>
			<div class="row"><!-- row -->
				<div class="col-md-12 padding_0"><!-- col-md-12 -->
					<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
						<?php 
						if($active_tab == 'teacherlist')
						{ 
							$teacherdata=mj_smgt_get_usersdata('teacher');
							if(!empty($teacherdata))
							{
								?>	
								<div class="panel-body"><!-- panel-body -->
									<div class="table-responsive">
										<form name="frm-example" action="" method="post">
											<table id="teacher_list" class="display admin_taecher_datatable" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
													<tr>
														<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
														<th><?php esc_attr_e( 'Teacher Name & Email', 'school-mgt' ) ;?></th>
														<th> <?php esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
														<th> <?php esc_attr_e( 'Subject', 'school-mgt' ) ;?></th>
														<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>   
														<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if(!empty($teacherdata))
													{
														foreach (mj_smgt_get_usersdata('teacher') as $retrieved_data)
														{   	
															
															$teacher_group = array();
															$teacher_ids = mj_smgt_teacher_by_subject($retrieved_data);                             
															foreach($teacher_ids as $teacher_id)
															{
																$teacher_group[] = mj_smgt_get_teacher($teacher_id);
															}
															$teachers = implode(',',$teacher_group);
															?>
															<tr>
																<td class="checkbox_width_10px">
																	<input type="checkbox" class="smgt_sub_chk selected_teacher" name="id[]" value="<?php echo esc_attr($retrieved_data->ID);?>">
																</td>

																<td class="user_image width_50px">
																	<a href="?page=smgt_teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>">
																		<?php $uid=$retrieved_data->ID;
																		$umetadata=mj_smgt_get_user_image($uid);
																		if(empty($umetadata))
																		{
																			echo '<img src='.get_option( 'smgt_teacher_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
																		}
																		else
																		{
																			echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
																		}
																		?>
																	</a>
																</td>
																<td class="name">
																	<a class="color_black" href="?page=smgt_teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>">
																		<?php echo $retrieved_data->display_name;?>
																	</a>
																	<br>
																	<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
																</td>

																<td class="">
																	<?php 
																		$classes="";
																		$classes = $teacher_obj->mj_smgt_get_class_by_teacher($retrieved_data->ID);
																		$classname = "";
																		foreach($classes as $class)
																		{
																			$classname .= mj_smgt_get_class_name($class['class_id']).",";
																		}
																		$classname_rtrim=rtrim($classname,", ");
																		$classname_ltrim=ltrim($classname_rtrim,", ");
																		if(!empty($classname_ltrim))
																		{
																			echo $classname_ltrim;
																			
																		}
																		else
																		{
																			echo "N/A";
																		}
																		//echo $classname_ltrim;
																	?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i>
																</td>
																<td class="">
																	<?php $subjectname=mj_smgt_get_subject_name_by_teacher($uid); 
																	if(!empty($subjectname))
																	{ 
																		echo rtrim($subjectname,", ");
																	}
																	else
																	{ 
																		echo "N/A"; 
																	} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Subject','school-mgt');?>" ></i>
																</td>
																<td class="">
																	<?php
																		$uid=$retrieved_data->ID;
																		?>
																		+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.get_user_meta( $uid, 'mobile_number', true );?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i>
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
																						<a href="?page=smgt_teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a> 
																					</li>

																					<?php
																					if($user_access_edit == '1')
																					{
																						?>	
																						<li class="float_left_width_100 border_bottom_menu">			
																							<a href="?page=smgt_teacher&tab=addteacher&action=edit&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
																						</li>
																						<?php
																					}
																					?>
																					<?php
																					if($user_access_delete == '1')
																					{
																						?>
																						<li class="float_left_width_100 ">
																							<a href="?page=smgt_teacher&tab=teacherlist&action=delete&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
																						</li>

																						<?php 
																					} ?>
																				</ul>
																			</li>
																		</ul>
																	</div>	
																</td>
															</tr>
															<?php 
														} 
													}?>
												</tbody>
											</table>
											<div class="print-button pull-left">
												<button class="btn btn-success btn-sms-color button_reload" style="margin-bottom: 5px;">
													<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
													<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
												</button>
												<?php 
												if($user_access_delete =='1')
												{
													?>
														<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
													<?php 
												} ?>
												<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','school-mgt');?>" name="teacher_csv_selected" class="teacher_csv_export_alert export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>
												
												<button data-toggle="tooltip"  title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" class="view_import_teacher_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
											</div>
										</form>
									</div>
								</div><!-- panel-body -->
								<?php 
							}
							else
							{
								if($user_access_add=='1')
								{
									?>
									<div class="no_data_list_div row">
										<div class="offset-md-2 col-md-4"> 	
											<a href="<?php echo admin_url().'admin.php?page=smgt_teacher&tab=addteacher';?>">
												<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
											</a>
											<div class="col-md-12 dashboard_btn margin_top_20px">
												<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
											</div> 
										</div>
										<div class="col-md-4">
											<a data-toggle="tooltip"  name="import_csv" type="button" class="view_import_teacher_csv_popup">
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
						if($active_tab == 'addteacher')
						{
							require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/add-newteacher.php';
						}
						if($active_tab == 'view_teacher')
						{
							require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/view_teacher.php';
						}
						if($active_tab == 'uploadteacher')
						{
							require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/upload_teacher.php';
						}
						?>
					</div><!-- smgt_main_listpage -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- main_list_margin_15px -->
	</div><!-- page-inner -->
	<?php 
} ?>