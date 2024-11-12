<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$role=mj_smgt_get_user_role(get_current_user_id());

if($role == 'administrator')

{

	$user_access_view=1;

}
else
{

	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('attendance');

	$user_access_view=$user_access['view'];

	if(isset($_REQUEST ['page']))
	{	
		if($user_access_view=='0')

		{	
			mj_smgt_access_right_page_not_access_message_admin_side();
			die;
		}
	}
}
?>

<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";
		$('#student_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#curr_date').datepicker({
			maxDate:'0',
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 

		$('#subject_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#curr_date_subject').datepicker({
			maxDate:'0',
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		});

		$('#teacher_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#curr_date_teacher').datepicker({
			maxDate:'0',
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true,
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 

		$("#subject_teacher").multiselect({ 
				nonSelectedText :'<?php esc_html_e('Select Teacher','school-mgt');?>',
				includeSelectAllOption: true ,
				selectAllText : '<?php esc_html_e('Select all','school-mgt');?>',
				templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		});

		$('#class_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#upload_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

	});

</script>

<?php 

	$obj_attend=new Attendence_Manage();

	$class_id =0;

	$current_date = date("y-m-d");

	$active_tab = isset($_GET['tab'])?$_GET['tab']:'student_attendance';

	if($active_tab == 'teacher_attendance'){

		$active_tab1 = isset($_GET['tab1'])?$_GET['tab1']:'teacher_attendences_list';

	}

	if($active_tab == 'student_attendance'){

		$active_tab1 = isset($_GET['tab1'])?$_GET['tab1']:'attendence_list';

	}

	$MailCon = get_option('absent_mail_notification_content');

	$Mailsub= get_option('absent_mail_notification_subject');

	/* Save Attendance */

	if(isset($_REQUEST['save_attendence']))

	{	
		
		$class_id		=	$_POST['class_id'];

		//$parent_list 	= 	mj_smgt_get_user_notice('parent',$class_id);		

		$attend_by		=	get_current_user_id();		

		

		if(isset($_POST['class_section']) && $_POST['class_section'] !=0)

		{

			$exlude_id 	= 	mj_smgt_approve_student_list();

			$students 	= 	get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],

			'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	

		}

		else

		{ 

			$exlude_id 	= 	mj_smgt_approve_student_list();

			$students 	= 	get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));

		}

		

		$parent_list 	=	array();

		foreach($students as $stud)

		{			

			if(isset($_POST['attendanace_'.$stud->ID]))

			{

				if(isset($_POST['smgt_sms_service_enable']) || isset($_POST['smgt_mail_service_enable']))

				{	
					
					$current_sms_service 	= 	get_option( 'smgt_sms_service');					

					if($_POST['attendanace_'.$stud->ID] == 'Absent')

					{
						
						$parent_list 	= 	mj_smgt_get_student_parent_id($stud->ID);						
						
						if(!empty($parent_list))

						{
							// SEND SMS NOTIFICATION
							if(isset($_POST['smgt_sms_service_enable']))
							{
								foreach ($parent_list as $user_id)
								{
									$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)." is absent on ".$_POST['curr_date'];
									$type = "Attendance";
									MJ_smgt_send_sms_notification($user_id,$type,$message_content);
								}
							}

							if(isset($_POST['smgt_mail_service_enable']))

							{
								
								if(!empty($parent_list))

								{

									foreach ($parent_list as $parent_user_id)

									{
										
										$parent_data = get_userdata($parent_user_id);
										
										if($parent_data == true)

										{
											
											$MailArr['{{parent_name}}'] = mj_smgt_get_display_name($parent_user_id);								

											$MailArr['{{child_name}}'] = mj_smgt_get_display_name($stud->ID);								

											$MailArr['{{school_name}}'] =  	get_option('smgt_school_name');								

											$Mail_content = mj_smgt_string_replacement($MailArr,$MailCon);								
											$subject = mj_smgt_string_replacement($MailArr,$Mailsub);
											
											$attendance_mail =  mj_smgt_send_mail($parent_data->user_email,$subject,$Mail_content);	
											
										}

									}

								}

							}		

						} 

					} 				

				}

				$attendence_type = 'Web';

				$savedata = $obj_attend->mj_smgt_insert_student_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID],$attendence_type);				

			}

		} 

		wp_redirect ( admin_url().'admin.php?page=smgt_attendence&message=1');

	}



	/* Subject Wise Attendance */

	if(isset($_REQUEST['save_sub_attendence']))
	{			
		
		$class_id=$_POST['class_id'];

		$parent_list = mj_smgt_get_user_notice('parent',$class_id);		

		$attend_by=get_current_user_id();		

		$exlude_id = mj_smgt_approve_student_list();

		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));

		foreach($students as $stud)

		{

			if(isset($_POST['attendanace_'.$stud->ID]))

			{

				if(isset($_POST['smgt_sms_service_enable']) || isset($_POST['smgt_subject_mail_service_enable']))

				{

					$current_sms_service = get_option( 'smgt_sms_service');

					if($_POST['attendanace_'.$stud->ID] == 'Absent')

					{

						$parent_list = mj_smgt_get_student_parent_id($stud->ID);

						if(!empty($parent_list))

						{
							
							foreach ($parent_list as $user_id)
							{
								$parent_data = get_userdata($user_id);
								if(isset($_POST['smgt_sms_service_enable']))
								{
									$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)."  is absent in ".$sub->sub_name . " Subject on ".$_POST['curr_date'];
									$type = "Attendance";
									MJ_smgt_send_sms_notification($user_id,$type,$message_content);
								}
								if(isset($_POST['smgt_subject_mail_service_enable']))
								{
									$MailArr['{{child_name}}'] = mj_smgt_get_display_name($stud->ID);	

									$MailArr['{{school_name}}'] =  	get_option('smgt_school_name');								

									$Mail = mj_smgt_string_replacement($MailArr,$MailCon);								

									$MailSub = mj_smgt_string_replacement($MailArr,$Mailsub);								

									mj_smgt_send_mail($parent_data->user_email,$MailSub,$Mail);	
								}						

							}
							
						}

					}

				}				

				$savedata = $obj_attend->mj_smgt_insert_subject_wise_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['sub_id'],$_POST['attendanace_comment_'.$stud->ID],'Web',$_POST['class_section']);

			}					

		}		

			wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=student_attendance&message=1');
	}

	

	/* Teacher attendence */

	if(isset($_REQUEST['save_teach_attendence']))

	{

		$attend_by=get_current_user_id();

		$teacher = get_users(array('role' => 'teacher'));

		foreach($teacher as $stud)

		{

			if(isset($_POST['attendanace_'.$stud->ID]))

			{

				$savedata = $obj_attend->mj_smgt_insert_teacher_attendance($_POST['tcurr_date'],$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID]);

			}

		}

		wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=teacher_attendance&message=1');

	}



	/* Export Teacher Attendance */

	if(isset($_POST['export_attendance_in_csv']))

	{
		if(empty($_POST['filtered_date_type']) && empty($_POST['filtered_class_id']))
		{
			$class_id = '';
			$date_type = '';
			$start_date = date('Y-m-d',strtotime('first day of this month'));
			$end_date = date('Y-m-d',strtotime('last day of this month'));
			$student_attendance_list = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);
		}
		else
		{
			$date_type = $_POST['filtered_date_type'];
			$class_id = $_REQUEST['filtered_class_id'];
			$student_attendance_list = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);
		}
		if(!empty($student_attendance_list))
		{
			$header = array();			
			$header[] = 'Roll No';
			$header[] = 'Student Name';
			$header[] = 'Student Email';
			$header[] = 'Class Name';
			$header[] = 'Section Name';
			$header[] = 'Subject Name';
			$header[] = 'Attend_by_name';
			$header[] = 'Attendence_date';
			$header[] = 'Status';
			$header[] = 'Comment';		
			$filename='Reports/export_attendance.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($student_attendance_list as $retrive_data)
			{
				if($retrive_data->role_name == 'student')
				{
					$row = array();
					$user_info = get_userdata($retrive_data->user_id);
					$roll_no= get_user_meta($retrive_data->user_id, 'roll_id',true);
					if(!empty($roll_no))
					{
						$roll_no=$roll_no;
					}
					else
					{
						$roll_no='-';
					}					
					$row[] = $roll_no;
					$row[] = $user_info->display_name;
					$row[] = $user_info->user_email;
					$class_id=$retrive_data->class_id;
					$classname=mj_smgt_get_class_name($class_id);
					if(!empty($classname))
					{
						$classname=$classname;
					}
					else
					{
						$classname='';
					}	
					$row[] = $classname;	
					$section = mj_smgt_get_section_name($retrive_data->section_id);
					if(!empty($section))
					{
						$section = $section;
					}
					else
					{
						$section = '';
					}
					$row[] = $section;
					$subject = mj_smgt_get_single_subject_name($retrive_data->sub_id);
					if(!empty($subject))
					{
						$subject = $subject;
					}
					else
					{
						$subject = '';
					}
					$row[] = $subject;
					$attend_by = get_userdata($retrive_data->attend_by);				
					$row[] =  $attend_by->display_name;							
					$row[] =  $retrive_data->attendance_date;
					$row[] =  $retrive_data->status;
					$row[] =  $retrive_data->comment;
					fputcsv($fh, $row);		
				}					
			}
			fclose($fh);
			//download csv file.
			ob_clean();
			$file=SMS_PLUGIN_DIR.'/admin/Reports/export_attendance.csv';//file location
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

			wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=student_attendance&message=3');

		}

	}

	

	/* Upload Student Attendance */

	if(isset($_REQUEST['upload_attendance_csv_file']))

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

				wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=import_attendence&message=5');

				//$err= esc_attr__('this file not allowed, please choose a CSV file.','school-mgt');

				$errors[]=$err;

			}

			if($file_size > 2097152)

			{

				$errors[]='File size limit 2 MB';

			}
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));
				$header = array_map('strtolower',array_shift($rows));
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					$class_id=mj_smgt_get_class_id_by_name($csv['class name']);
					if(!empty($csv['section name']))
					{
						$section_name=$csv['section name'];
						$section_data=mj_smgt_get_section_id_by_section_name($section_name,$class_id);
						$section_id=$section_data[0]->id;
					}

					$curr_date = date("Y-m-d", strtotime($csv['attendence_date']));
					$user = get_user_by( 'email', $csv['student email'] );
                    $userId = $user->ID;
					$attend_by=1;
					$status=$csv['status'];
					$sub_name=$csv['subject name'];
					if(!empty($sub_name))
					{
						$sub_id=mj_smgt_get_subject_id_by_subject_name($sub_name,$class_id,$section_id);
					}
					$comment=$csv['comment'];
					$attendence_type = 'Web';
					$savedata = $obj_attend->mj_smgt_insert_subject_wise_attendance($curr_date,$class_id,$userId,$attend_by,$status,$sub_id,$comment,$attendence_type,$section_id);	
					$success = 1;
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
			if(isset($success))
			{				

				wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=student_attendance&message=4');

			} 
		}
	}
	/* Export Teacher Attendance */

	if(isset($_POST['export_teacher_attendance_in_csv']))
	{
		if(empty($_POST['filtered_date_type']) && empty($_POST['filtered_member_id']))
		{
			$start_date = date('Y-m-d',strtotime('first day of this month'));
			$end_date = date('Y-m-d',strtotime('last day of this month'));
			$date_type = '';
			$member_id = '';
			$type='teacher';
			$teacher_attendance_list = smgt_get_all_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$type);
		}
		else{
			$result =  mj_smgt_all_date_type_value($_POST['filtered_date_type']);

			$response =  json_decode($result);
			$start_date = $response[0];
			$end_date = $response[1];
		
			if(!empty($_POST['filtered_member_id'])  && $_POST['filtered_member_id'] != "all_teacher")
			{
				$member_id = $_REQUEST['filtered_member_id'];
				$teacher_attendance_list=smgt_get_member_attendence_beetween_satrt_date_to_enddate_for_admin($start_date,$end_date,$member_id);
			}else{
				$type='teacher';
				$teacher_attendance_list = smgt_get_all_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$type);
				
			}
		}
		
		if(!empty($teacher_attendance_list))

		{

			$header = array();			

			$header[] = 'Teacher Name';

			$header[] = 'User_id';

			$header[] = 'Attend_by_name';

			$header[] = 'Attend_by';

			$header[] = 'Attendence_date';

			$header[] = 'Status';

			$header[] = 'Role_name';

			$header[] = 'Comment';			

				

			$filename='Reports/export_teacher_attendance.csv';

			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");

			fputcsv($fh, $header);

			foreach($teacher_attendance_list as $retrive_data)

			{

				if($retrive_data->role_name == 'teacher')

				{

					$row = array();

					$user_info = get_userdata($retrive_data->user_id);

					$row[] = $user_info->display_name;

					$row[] = $retrive_data->user_id;

					$attend_by = get_userdata($retrive_data->attend_by);			

					$row[] =  $attend_by->display_name;				

					$row[] =  $retrive_data->attend_by;				

					$row[] =  $retrive_data->attendence_date;

					$row[] =  $retrive_data->status;

					$row[] =  $retrive_data->role_name;

					$row[] =  $retrive_data->comment;

					fputcsv($fh, $row);		

				}					

			}

			fclose($fh);

			//download csv file.

			ob_clean();

			$file=SMS_PLUGIN_DIR.'/admin/Reports/export_teacher_attendance.csv';//file location

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

			wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=teacher_attendance&message=3');

		}

	}

?>

<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/attendance.js'; ?>" ></script>
<div class="popup-bg">
	<div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="result"></div>
			<div class="view-parent"></div>
			<div class="category_list">
			</div>
		</div>
	</div>    
</div>
<?php
if(get_option('smgt_enable_video_popup_show') == 'yes')
{
?>
<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/TaO7Xh4SmXY?si=v4zQa-CmiEE0h151";?>" title="Student Attendance">
	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
</a>
<?php
}
?>
<div class="popup-bg">
	<div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="result"></div>
			<div class="view-parent"></div>
			<div class="category_list">
			</div>
		</div>
	</div>    
</div>
<div class="page-inner"><!-- page-inner --> 

	<div id="" class=" attendance_list main_list_margin_5px"> <!-- attendance_list --> 

		<?php

		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

		switch($message)

		{

			case '1':

				$message_string = esc_attr__('Attendance saved successfully.','school-mgt');

				break;

			case '2':

				$message_string = esc_attr__('Record Deleted Successfully.','school-mgt');

				break;			

			case '3':

				$message_string = esc_attr__('Attendance records not found.','school-mgt');

				break;

			case '4':

				$message_string = esc_attr__('Attendance records imported successfully.','school-mgt');

				break;

			case '5':

				$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');

				break;

		}

	

		if($message)

		{ ?>

			<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

				<p><?php echo $message_string;?></p>

				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

			</div>

			<?php 

		} ?>

		<div class="">

			<div class="panel-body"> <!-- panel-body -->    

				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per mb-4" role="tablist">

					<?php

					if($active_tab == 'teacher_attendance')
					{

						?>

							<li class="<?php if($active_tab1=='teacher_attendences_list'){?>active<?php }?>">

								<a href="?page=smgt_attendence&tab=teacher_attendance&tab1=teacher_attendences_list" class="padding_left_0 tab <?php echo $active_tab1 == 'teacher_attendences_list' ? 'nav-tab-active' : ''; ?>">

								<?php echo esc_attr__('Teacher Attendance List', 'school-mgt'); ?></a>

							</li>

							<li class="<?php if($active_tab1=='teacher_attendences'){?>active<?php }?>">

								<a href="?page=smgt_attendence&tab=teacher_attendance&tab1=teacher_attendences" class="padding_left_0 tab <?php echo $active_tab1 == 'teacher_attendences' ? 'nav-tab-active' : ''; ?>">

								<?php echo esc_attr__('Teacher Attendance', 'school-mgt'); ?></a>

							</li>

							<!-- <li class="<?php if($active_tab1=='export_teacher_attendences'){?>active<?php }?>">

								<a href="?page=smgt_attendence&tab=teacher_attendance&tab1=export_teacher_attendences" class="padding_left_0 tab <?php echo $active_tab1 == 'export_teacher_attendences' ? 'nav-tab-active' : ''; ?>">

								<?php echo esc_attr__('Export Teacher Attendance', 'school-mgt'); ?></a>

							</li> -->

						<?php

					}

					if($active_tab == 'student_attendance'){

					?>

						<li class="<?php if($active_tab1=='attendence_list'){?>active<?php }?>">

							<a href="?page=smgt_attendence&tab=student_attendance&tab1=attendence_list" class="padding_left_0 tab <?php echo $active_tab1 == 'attendence_list' ? 'nav-tab-active' : ''; ?>">

							<?php echo esc_attr__('Attendance List', 'school-mgt'); ?></a>

						</li>

						<!-- <li class="<?php if($active_tab1=='attendence'){?>active<?php }?>">

							<a href="?page=smgt_attendence&tab=student_attendance&tab1=attendence" class="padding_left_0 tab <?php echo $active_tab1 == 'attendence' ? 'nav-tab-active' : ''; ?>">

							<?php echo esc_attr__('Attendance', 'school-mgt'); ?></a>

						</li>	 -->

						<li class="<?php if($active_tab1=='subject_attendence'){?>active<?php }?>">	

							<a href="?page=smgt_attendence&tab=student_attendance&tab1=subject_attendence" class="padding_left_0 tab <?php echo $active_tab1 == 'subject_attendence' ? 'nav-tab-active' : ''; ?>">

							<?php echo esc_attr__('Attendance', 'school-mgt'); ?></a>

						</li>	
						<li class="<?php if($active_tab1=='attendence_with_qr'){?>active<?php }?>">

							<a href="?page=smgt_attendence&tab=student_attendance&tab1=attendence_with_qr" class="padding_left_0 tab <?php echo $active_tab1 == 'attendence_with_qr' ? 'nav-tab-active' : ''; ?>">

							<?php echo esc_attr__('Attendance With QR Code', 'school-mgt'); ?></a>

						</li>

					<?php

					}

					?>	

				</ul>

    			<?php

				// attendence list 

				if(isset($active_tab1) && $active_tab1 == 'attendence')

				{ ?>	 

					<div class="panel-body"> 

						<form method="post" id="student_attendance">  

							<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />

							<div class="form-body user_form">

								<div class="row">

									<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">

										<div class="form-group input">

											<div class="col-md-12 form-control">

												<input id="curr_date" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="curr_date" readonly>		<label class="l" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>	

											</div>

										</div>

									</div>



									<div class="col-md-3 mb-3 input">

										<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			

										<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>                 

										<select name="class_id"  id="class_list"  class="form-control validate[required]">

											<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>

												<?php 

												foreach(mj_smgt_get_allclass() as $classdata)

												{  

													?>

													<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>

													<?php 

												}?>

										</select>			

									</div>



									<div class="col-md-3 mb-3 input">

										<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>			

										<?php 

										$class_section="";

										if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>

										<select name="class_section" class="form-control" id="class_section">

											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

											<?php if(isset($_REQUEST['class_section']))

											{

												$class_section=$_REQUEST['class_section']; 

												foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)

												{  ?>

													<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

													<?php 

												} 

											} ?>	

										</select>

									</div>

									<div class="col-md-3 mb-3">

										<!-- <label for="subject_id">&nbsp;</label> -->

										<input type="submit" value="<?php esc_attr_e('Take Attendance','school-mgt');?>" name="attendence"  class="save_btn"/>

									</div>

							</div>

						</form>

					</div>

					<div class="clearfix"> </div>

					<?php 

					if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_attendence']))

					{

						$class_id=$_REQUEST['class_id'];

						$user=count(get_users(array(

							'meta_key' => 'class_name',

							'meta_value' => $class_id

						)));

						$attendanace_date=$_REQUEST['curr_date'];

						$holiday_dates=mj_smgt_get_all_date_of_holidays();

						if (in_array($attendanace_date, $holiday_dates))

						{

							?>

							<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

								<p><?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?></p>

								<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><?php esc_attr_e('Please Select Class','school-mgt');?></span></button>

							</div>

							<?php 

						}

						elseif(0 < $user)

						{

							if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != "")

							$class_id = $_REQUEST['class_id'];

							else 

							$class_id = 0;

							if($class_id == 0)

							{ ?>

								<div class="panel-heading">

									<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>

								</div>

								<?php 

							}

							else

							{

								$class_section=0;

								if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] !=0)

								{

									$class_section	=	$_REQUEST['class_section'];

									$exlude_id 		= 	mj_smgt_approve_student_list();

									$student 	= 	get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id,'orderby' => 'display_name','order' => 'ASC'));

									// sort($student);

								}

								else

								{ 

									$exlude_id = mj_smgt_approve_student_list();

									$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id,'orderby' => 'display_name','order' => 'ASC'));

									// sort($student);

								} ?>

				   				<div class="panel-body">  

									<form method="post"  class="form-horizontal">           

										<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />

										<input type="hidden" name="class_section" value="<?php echo $class_section;?>" />

										<input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />

			

										<div class="panel-heading">

											<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 

											<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?></h4>

										</div>

			

										<div class="col-md-12 padding_payment smgt_att_tbl_list">

											<div class="table-responsive">

												<table class="table">

													<tr>

														<th class="multiple_subject_mark"><?php esc_attr_e('Srno','school-mgt');?></th>

														<th class="multiple_subject_mark"><?php esc_attr_e('Roll No.','school-mgt');?></th>

														<th class="multiple_subject_mark"><?php esc_attr_e('Student Name','school-mgt');?></th>

														<th class="multiple_subject_mark"><?php esc_attr_e('Attendance','school-mgt');?></th>

														<th class="multiple_subject_mark"><?php esc_attr_e('Comment','school-mgt');?></th>

													</tr>

													<?php

													$date = $_POST['curr_date'];

													$i = 1;



													foreach ( $student as $user ) 

													{

															$date = $_POST['curr_date'];                   

															$check_attendance = $obj_attend->mj_smgt_check_attendence($user->ID,$class_id,$date);



															$attendanc_status = "Present";

															if(!empty($check_attendance))

															{

																$attendanc_status = $check_attendance->status;                    	

															}                   

															echo '<tr>';              

															echo '<td>'.$i.'</td>';

															echo '<td><span>' .get_user_meta($user->ID, 'roll_id',true). '</span></td>';

															echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';

															?>

															<td>

																<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>

																<?php esc_attr_e('Present','school-mgt');?></label>

																<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>

																<?php esc_attr_e('Absent','school-mgt');?></label>

																<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>

																<?php esc_attr_e('Late','school-mgt');?></label>

																<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Half Day" <?php checked( $attendanc_status, 'Half Day' );?>>

																<?php esc_attr_e('Half Day','school-mgt');?></label>

															</td>

															<td class="padding_left_right_0">

																<div class="form-group input margin_bottom_0px">

																	<div class="col-md-12 form-control">

																		<input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>">

																	</div>

																</div>

															</td>

															<?php 

														echo '</tr>';

														$i++;

													}?>

												</table>

											</div>

											<div class="d-flex mt-2">



												<div class="form-group row mb-3">

													<label class="col-sm-8 control-label " for="enable"> <?php esc_attr_e('If student absent then Send Email to his/her parents','school-mgt');?></label>

													<div class="col-sm-2 ps-0">

														<div class="checkbox">

															<label>

																<input class="smgt_check_box" id="smgt_mail_service_enable" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">

															</label>

														</div>				 

													</div>

												</div>



												<div class="form-group row mb-3">

													<label class="col-sm-10 control-label " for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>

													<div class="col-sm-2 ps-0">

														<div class="checkbox">

															<label>

																<input class="smgt_check_box" id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">

															</label>

														</div>				 

													</div>

												</div>

												

											</div>				

										</div>

										<div class="col-md-6 rtl_res_att_save"> 					    	

											<input type="submit" value="<?php esc_attr_e('Save  Attendance','school-mgt');?>" name="save_attendence" class="col-sm-6 save_btn" />

										</div>

									</form>

	 							</div>

								<?php 

							}

						}

						else

						{

							?>

							<div class="">

								<h4 class="panel-title"><?php esc_html_e("No Any Student In This Class" , "school-mgt"); ?></h4>

							</div>

							<?php

						}

					}

				}



				if(isset($active_tab1) && $active_tab1 == 'attendence_list')

				{

					require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/student_attendence_list.php';

				}

				if(isset($active_tab1) && $active_tab1 == 'teacher_attendences_list')

				{

					require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/teacher_attendences_list.php';

				}

				if(isset($active_tab1) && $active_tab1 == 'teacher_attendences')

				{ 

					?>

					<form method="post" id="teacher_attendance">           

						<div class="form-body user_form">

							<div class="row">

								<div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="curr_date_teacher" class="form-control" type="text" value="<?php if(isset($_POST['tcurr_date'])) echo mj_smgt_getdate_in_input_box($_POST['tcurr_date']); else echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="tcurr_date" readonly>	

											<label class="" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>	

										</div>

									</div>

								</div>

								<div class="col-md-3">

									<!-- <label for="subject_id">&nbsp;</label> -->

									<input type="submit" value="<?php esc_attr_e('Take Attendance','school-mgt');?>" name="teacher_attendence"  class="save_btn"/>

								</div>

							</div>

						</div>

					</form>

					<?php

				}

				if(isset($active_tab1) && $active_tab1 == 'export_teacher_attendences')

				{ 

				?>

					<div class="panel-body"><!-- panel-body --> 

						<form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">

							<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

							<input type="hidden" name="action" value="<?php echo $action;?>">

							<div class="form-body user_form">

								<div class="row">	

									<div class="col-md-6 error_msg_left_margin input">

										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Teacher','school-mgt');?><span class="required">*</span></label>

										<?php if(isset($_POST['teacher_name'])){$workrval=$_POST['teacher_name'];}else{$workrval='';}?>

										<select name="teacher_name" class="form-control validate[required] width_100 class_by_teacher" id="teacher_name">

											<option value=""><?php echo esc_attr_e( 'Select Teacher', 'school-mgt' ) ;?></option>

											<?php

											$teacherdata_array=mj_smgt_get_usersdata('teacher');

											foreach($teacherdata_array as $techer_data)

											{

												

												?>

												<option value="<?php echo $techer_data->ID;?>" <?php selected($techer_data->ID);  ?>><?php echo $techer_data->display_name;?></option> 

												<?php 

											}

											?>

										</select>                            

									</div> 

									<div class="col-sm-3">        	

										<input type="submit" value="<?php esc_attr_e('Export Teacher Attendance','school-mgt');?>" name="export_teacher_attendance_in_csv" class="save_att_btn"/>

									</div>

								</div>

							</div>

						</form>

					</div>

				<?php

				}

				?>

				<div class="clearfix"> </div>

				<?php 

				if(isset($_REQUEST['teacher_attendence']) || isset($_REQUEST['save_teach_attendence']))

				{	

					$attendanace_date=$_REQUEST['tcurr_date'];

					$holiday_dates=mj_smgt_get_all_date_of_holidays();

					if (in_array($attendanace_date, $holiday_dates))

					{

						?>

						<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">

							<p><?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?></p>

							<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>

						</div>

						<?php 

					}

					else

					{

						?>

						<div class="panel-body"> <!-- panel-body -->

							<form method="post">        

								<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />

								<input type="hidden" name="tcurr_date" value="<?php echo $_POST['tcurr_date'];?>" />

								<div class="panel-heading">

									<h4 class="panel-title"><?php esc_attr_e('Teacher Attendance','school-mgt');?> , 

									<?php esc_attr_e('Date','school-mgt')?> : <?php echo $_POST['tcurr_date'];?></h4>

								</div>

								<div class="col-md-12 padding_payment smgt_att_tbl_list">

									<div class="table-responsive">

										<table class="table">

											<tr>

												<th style="width: 0px!important;"><?php esc_attr_e('Srno','school-mgt');?></th>

												<th><?php esc_attr_e('Teacher','school-mgt');?></th>

												<th style="width: 250px!important;"><?php esc_attr_e('Attendance','school-mgt');?></th>

												<th><?php esc_attr_e('Comment','school-mgt');?></th>

											</tr>

											<?php 

											$date = $_POST['tcurr_date'];

											$i=1;

											$teacher = get_users(array('role'=>'teacher'));

											foreach ($teacher as $user)

											{

												$class_id=0;

												$check_attendance = $obj_attend->mj_smgt_check_attendence($user->ID,$class_id,$date);

												

												$attendanc_status = "Present";

												if(!empty($check_attendance))

												{

													$attendanc_status = $check_attendance->status;

													

												}

												echo '<tr>';  

												echo '<tr>';

											

												echo '<td>'.$i.'</td>';

												echo '<td style="padding-left:0px!important;"><span>' .$user->first_name.' '.$user->last_name. '</span></td>';

												?>

												<td style="padding-left:0px!important;">

													<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>

													<?php esc_attr_e('Present','school-mgt');?></label>

													<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>

													<?php esc_attr_e('Absent','school-mgt');?></label><br>

													<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>

													<?php esc_attr_e('Late','school-mgt');?></label>

													<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Half Day" <?php checked( $attendanc_status, 'Half Day' );?>>

													<?php esc_attr_e('Half Day','school-mgt');?></label>

												</td>



												<td class="">

													<div class="form-group input margin_bottom_0px">

														<div class="col-md-12 form-control">

															<input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>">

														</div>

													</div>

												</td>



												

												<?php 

												

												echo '</tr>';

												$i++;

											}

											?>   

										</table>

									</div>

								</div>		

								<div class="cleatrfix"></div>

								<div class="col-sm-12 padding_top_10px rtl_res_att_save">    

									<input type="submit" value="<?php esc_attr_e("Save Attendance","school-mgt");?>" name="save_teach_attendence" id="res_rtl_width_100" class="col-sm-6 save_att_btn " />

								</div>       

							</form>

						</div><!-- panel-body -->

						<?php

					}

				} 

				if(isset($active_tab1) && $active_tab1 == 'subject_attendence')

				{ 

					require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/subject-attendence.php';

				}

				if(isset($active_tab1) && $active_tab1 == 'import_attendence')

				{ 

					require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/import_attendence.php';

				}

				if(isset($active_tab1) && $active_tab1 == 'attendence_with_qr')

				{ 

					require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/attendence_qr.php';

				}

				?>

			</div><!-- panel-body -->  

		</div>

	</div><!-- attendance_list --> 

</div><!-- page-inner --> 