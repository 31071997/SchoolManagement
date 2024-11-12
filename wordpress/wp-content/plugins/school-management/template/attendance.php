<?php



mj_smgt_browser_javascript_check();



$role=mj_smgt_get_user_role(get_current_user_id());



$user_access=mj_smgt_get_userrole_wise_access_right_array();



if (isset ( $_REQUEST ['page'] ))



{	



	if($user_access['view']=='0')



	{	



		mj_smgt_access_right_page_not_access_message();



		die;



	}



	if(!empty($_REQUEST['action']))



	{



		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))



		{



			if($user_access['edit']=='0')



			{	



				mj_smgt_access_right_page_not_access_message();



				die;



			}			



		}



		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))



		{



			if($user_access['delete']=='0')



			{	



				mj_smgt_access_right_page_not_access_message();



				die;



			}	



		}



		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))



		{



			if($user_access['add']=='0')



			{	



				mj_smgt_access_right_page_not_access_message();



				die;



			}	



		} 



	}



}



?>



<script type="text/javascript">



	jQuery(document).ready(function($)



	{



		"use strict";	



		$('#student_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



		$('#curr_date_sub123').datepicker({maxDate:'0',dateFormat: "<?php echo get_option('smgt_datepicker_format');?>"});



		$('#subject_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



		$('#curr_date_sub').datepicker({
			maxDate:'0',
			changeYear:true,

			changeMonth: true,
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		});



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



	});



</script>



<?php



if($active_tab == 'student_attendance'){



	if ($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff') {



		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'attedance_list';



	}



	if($school_obj->role == 'student' || $school_obj->role == 'parent'){



		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'student_attedance_list';



	}



	



}



if($active_tab == 'teacher_attendance'){



	if($school_obj->role == 'supportstaff'){



		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'teacher_attedance_list';



	}



	if($school_obj->role == 'teacher')
	{



		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'role_teacher_attedance_list';



	}



	



}







	







$obj_attend=new Attendence_Manage();



$current_date = date("y-m-d");



$class_id =0;



$MailCon = get_option('absent_mail_notification');



$Mailsub= get_option('absent_mail_notification_subject');



include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//--------------- SAVE ATTENDANCE ---------------------//

if(isset($_REQUEST['save_attendence']))
{	 
    $nonce = $_POST['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'save_attendence_front_nonce' ) )
	{
		die( 'Failed security check' );
	}
	else
	{
		$class_id=$_POST['class_id'];

		$attend_by=get_current_user_id();	

		$exlude_id = mj_smgt_approve_student_list();

		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));

		foreach($students as $stud)
		{

			if(isset($_POST['attendanace_'.$stud->ID]))
			{

				if(isset($_POST['smgt_sms_service_enable']))
				{

					$current_sms_service = get_option( 'smgt_sms_service');

					if($_POST['attendanace_'.$stud->ID] == 'Absent')
					{

						$parent_list = mj_smgt_get_student_parent_id($stud->ID);

						if(!empty($parent_list))
						{

							$parent_number =array();

							foreach ($parent_list as $user_id)
							{
								// SEND SMS NOTIFICATION
								$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)." is absent today.";

								$type = "Attendanace";

								MJ_smgt_send_sms_notification($user_id,$type,$message_content);
							}						

						}

					}

				}
				if($_POST['attendanace_'.$stud->ID] == 'Absent')
				{

					if(isset($_POST['smgt_mail_service_enable']))
					{

						$parent_list = mj_smgt_get_student_parent_id($stud->ID);

						if(!empty($parent_list))
						{

							foreach ($parent_list as $parent_user_id)
							{
								$parent_data = get_userdata($parent_user_id);



								if($parent_data == true)



								{

									$MailCon = get_option('absent_mail_notification_content');



									$MailArr['{{parent_name}}'] = mj_smgt_get_display_name($parent_user_id);								



									$MailArr['{{child_name}}'] = mj_smgt_get_display_name($stud->ID);								



									$MailArr['{{school_name}}'] =  	get_option('smgt_school_name');								



									$Mail_content = mj_smgt_string_replacement($MailArr,$MailCon);		



									$subject = mj_smgt_string_replacement($MailArr,$Mailsub);

									

									mj_smgt_send_mail($parent_data->user_email,$subject,$Mail_content);	



								}



							}



						}



					}



				}



				$attendence_type = 'web';



				$savedata = $obj_attend->mj_smgt_insert_student_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID],$attendence_type);



				



			}					



		}



		?>



		<div id="message" class="alert_msg alert alert-success alert-dismissible  margin_left_right_0"  role="alert">



			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>



			</button>



			<?php esc_attr_e('Attendance saved successfully.','school-mgt');?>



		</div>



		<?php 



	}



}



//------------------------ SAVE SUBJECT WISE ATTENDANCE ---------------------//



if(isset($_REQUEST['save_sub_attendence']))

{
	$nonce = $_POST['_wpnonce'];
	
	$MailCon = get_option('absent_mail_notification_content');
	$Mailsub= get_option('absent_mail_notification_subject');

	if ( ! wp_verify_nonce( $nonce, 'save_sub_attendence_front_nonce' ) )



	{



		die( 'Failed security check' );



	}



	else



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


			if(isset($_POST['smgt_sms_service_enable']) || isset($_POST['smgt_mail_service_enable']))

				{	
					$current_sms_service 	= 	get_option( 'smgt_sms_service');					

					if($_POST['attendanace_'.$stud->ID] == 'Absent')

					{
						
						$parent_list 	= 	mj_smgt_get_student_parent_id($stud->ID);						
						
						if(!empty($parent_list))
						{
							if(isset($_POST['smgt_sms_service_enable']))
							{
								$parent_number 	=	array();

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



				$savedata = $obj_attend->mj_smgt_insert_subject_wise_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['sub_id'],$_POST['attendanace_comment_'.$stud->ID],'Web',$_POST['class_section']);



			}					



		}



	}
	wp_redirect ( home_url().'?dashboard=user&page=attendance&tab=student_attendance&message=1');
}







/* Export Studant Attendance */



if(isset($_POST['export_attendance_in_csv']))
{
		if(empty($_POST['filtered_date_type']))
		{
			if($school_obj->role == 'teacher')
			{
				$date_type = '';
				$class_id = $_POST['filtered_class_id'];		
				$student_attendance_list = smgt_get_student_attendance_by_class_id($start_date,$end_date,$class_id,$date_type);
			}
			else
			{
				$date_type = '';
				$class_id = '';
				$start_date = date('Y-m-d',strtotime('first day of this month'));
	
				$end_date = date('Y-m-d',strtotime('last day of this month'));
	
				$student_attendance_list = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);

			}
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

			$header[] = 'User_id';

			$header[] = 'Class_name';

			$header[] = 'Class_id';

			$header[] = 'Attend_by_name';



			$header[] = 'Attend_by';



			$header[] = 'Attendence_date';



			$header[] = 'Status';



			$header[] = 'Role_name';



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



					$row[] = $retrive_data->user_id;



					$class_id= $retrive_data->class_id;	



					$classname=mj_smgt_get_class_name($class_id);



					if(!empty($classname))



					{



						$classname=$classname;



					}



					else



					{



						$classname='-';



					}	



					$row[] = $classname;	



					$row[] = $retrive_data->class_id;	



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



	}







	/* Save Teacher Attendance */



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



	wp_redirect ( home_url().'?dashboard=user&page=attendance&tab=teacher_attendance&message=1');



}



?>



<div class="panel-body panel-white attendance_list frontend_list_margin_30px_res"><!-------------- PENAL BODY ----------------->



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


		}



	



		if($message)



		{ ?>



			<div id="message" class="alert_msg alert alert-success alert-dismissible  margin_left_right_0"  role="alert">



				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>



				</button>



				<?php echo $message_string; ?>



			</div>



			<?php 



		} ?>



	<!--------------- TABING START ------------------->



	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per mb-4" role="tablist">



		<?php



		if($active_tab == 'student_attendance'){



			if($school_obj->role == 'student' || $school_obj->role == 'parent'){



			?>



			<li class="<?php if($active_tab1=='student_attedance_list'){?>active<?php }?>">



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=student_attedance_list" class="padding_left_0 tab <?php echo $active_tab1 == 'student_attedance_list' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Attendance List', 'school-mgt'); ?></a>



			</li>



			<?php



			}



			if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff'){



			?>



			<li class="<?php if($active_tab1=='attedance_list'){?>active<?php }?>">



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=attedance_list" class="padding_left_0 tab <?php echo $active_tab1 == 'attedance_list' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Student Attendance List', 'school-mgt'); ?></a>



			</li>



			<!-- <li class="<?php if($active_tab1=='attendence'){?>active<?php }?>">



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=attendence" class="padding_left_0 tab <?php echo $active_tab1 == 'attendence' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Attendance', 'school-mgt'); ?></a>



			</li>	 -->



			<li class="<?php if($active_tab1=='subject_attendence'){?>active<?php }?>">	



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=subject_attendence" class="padding_left_0 tab <?php echo $active_tab1 == 'subject_attendence' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Attendance', 'school-mgt'); ?></a>



			</li>



			<li class="<?php if($active_tab1=='attendence_with_qr'){?>active<?php }?>">



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=attendence_with_qr" class="padding_left_0 tab <?php echo $active_tab1 == 'attendence_with_qr' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Attendance With QR Code', 'school-mgt'); ?></a>



			</li>	



			<!-- <li class="<?php if($active_tab1=='export_attendance'){?>active<?php }?>">



				<a href="?dashboard=user&page=attendance&tab=student_attendance&tab1=export_attendance" class="padding_left_0 tab <?php echo $active_tab1 == 'export_attendance' ? 'nav-tab-active' : ''; ?>">



				<?php echo esc_attr__('Export Student Attendance', 'school-mgt'); ?></a>	



			</li>	 -->



		<?php



			}



		}



		if($active_tab == 'teacher_attendance'){







			if($school_obj->role == 'teacher'){



				?>



					<li class="<?php if($active_tab1=='role_teacher_attedance_list'){?>active<?php }?>">



						<a href="?dashboard=user&page=attendance&tab=teacher_attendance&tab1=role_teacher_attedance_list" class="padding_left_0 tab <?php echo $active_tab1 == 'role_teacher_attedance_list' ? 'nav-tab-active' : ''; ?>">



						<?php echo esc_attr__('Attendance List', 'school-mgt'); ?></a>



					</li>



				<?php



			}



			if($school_obj->role == 'supportstaff'){



				?>



				<li class="<?php if($active_tab1=='teacher_attedance_list'){?>active<?php }?>">



					<a href="?dashboard=user&page=attendance&tab=teacher_attendance&tab1=teacher_attedance_list" class="padding_left_0 tab <?php echo $active_tab1 == 'teacher_attedance_list' ? 'nav-tab-active' : ''; ?>">



					<?php echo esc_attr__('Teacher Attendance List', 'school-mgt'); ?></a>



				</li>



				<li class="<?php if($active_tab1=='teacher_attendences'){?>active<?php }?>">



					<a href="?dashboard=user&page=attendance&tab=teacher_attendance&tab1=teacher_attendences" class="padding_left_0 tab <?php echo $active_tab1 == 'teacher_attendences' ? 'nav-tab-active' : ''; ?>">



					<?php echo esc_attr__('Teacher Attendance', 'school-mgt'); ?></a>



				</li>



				<?php



			}



			?>



				



				



			<?php



	}



		?>



	</ul>



	<!--------------- TABING END ------------------->



	<?php







	if($active_tab1=='student_attedance_list'){



		$user_id = get_current_user_id();



		// Attendance For Student



		if($role == 'student'){



			$attendance_list = mj_smgt_monthly_attendence($user_id);



		}



		// Attendance For Parent



		elseif($role == 'parent'){



			$attendance_list = mj_smgt_monthly_attendence_for_parent($user_id);



		}



		



		if(!empty($attendance_list))



		{



			?>



			<script type="text/javascript">



				jQuery(document).ready(function($) {



					"use strict";



					jQuery('#attendance_list_detailpage').DataTable({

						//stateSave: true,
						"responsive": true,

						dom: 'lifrtp',

						"order": [[ 1, "desc" ]],



						"aoColumns":[	                  



									{"bSortable": false},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},

									{"bSortable": true},
									{"bSortable": true},
									{"bSortable": true}],



						// dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',



						language:<?php echo mj_smgt_datatable_multi_language();?>



						});



						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");



					// $('.dataTables_filter').addClass('search_btn_view_page');



				} );



			</script>



	



			<div class="table-div"><!-- PANEL BODY DIV START -->



				<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->



					<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">



						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">



							<tr>

								<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>

								<th><?php esc_attr_e('Student Name','school-mgt');?></th>

								<th><?php esc_attr_e('Class Name','school-mgt');?></th>  

								<th><?php esc_attr_e('Date','school-mgt');?> </th>  

								<th><?php esc_attr_e('Day','school-mgt');?> </th>  

								<th><?php esc_attr_e('Attendance Status','school-mgt'); ?></th>

								<th><?php esc_html_e('Attendance By','school-mgt');?></th>

								<th><?php esc_attr_e('Attendance With QR Code','school-mgt');?></th>
								<th><?php esc_html_e('Comment','school-mgt');?></th>

							</tr>



						</thead>



						<tbody>



							<?php 



							$i=0;	



							$srno = 1;



							if(!empty($attendance_list))



							{



								foreach ($attendance_list as $retrieved_data)



								{



									

									$class_section_sub_name=smgt_get_class_section_subject($retrieved_data->class_id,$retrieved_data->section_id,$retrieved_data->sub_id);
									$created_by = get_userdata($retrieved_data->attend_by);
									if($i == 10)



									{



										$i=0;



									}



									if($i == 0)



									{



										$color_class='smgt_class_color0';



									}



									elseif($i == 1)



									{



										$color_class='smgt_class_color1';







									}



									elseif($i == 2)



									{



										$color_class='smgt_class_color2';







									}



									elseif($i == 3)



									{



										$color_class='smgt_class_color3';







									}



									elseif($i == 4)



									{



										$color_class='smgt_class_color4';







									}



									elseif($i == 5)



									{



										$color_class='smgt_class_color5';







									}



									elseif($i == 6)



									{



										$color_class='smgt_class_color6';







									}



									elseif($i == 7)



									{



										$color_class='smgt_class_color7';







									}



									elseif($i == 8)



									{



										$color_class='smgt_class_color8';







									}



									elseif($i == 9)



									{



										$color_class='smgt_class_color9';







									}



									?>



									<tr>



										<td class="user_image width_50px profile_image_prescription">



											<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	



												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image attendace_module_image center">



											</p>



										</td>



										<td class="department"><?php echo mj_smgt_student_display_name_with_roll($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Roll No.','school-mgt');?>"></i></td>



										<td class="">



											<?php echo $class_section_sub_name; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i>



										</td>



										<?php $curremt_date=mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); $day=date("D", strtotime($curremt_date)); ?>



										<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance Date','school-mgt');?>"></i></td>



										<td class="department"><?php 



										if($day == 'Mon')



										{



											esc_html_e('Monday','school-mgt');



										}  



										elseif($day == 'Sun')



										{



											esc_html_e('Sunday','school-mgt');



										} 



										elseif($day == 'Tue')



										{



											esc_html_e('Tuesday','school-mgt');



										}



										elseif($day == 'Wed')



										{



											esc_html_e('Wednesday','school-mgt');



										}



										elseif($day == 'Thu')



										{



											esc_html_e('Thursday','school-mgt');



										}



										elseif($day == 'Fri')



										{



											esc_html_e('Friday','school-mgt');



										}



										elseif($day == 'Sat')



										{



											esc_html_e('Saturday','school-mgt');



										}



										?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>



								<td class="name">
                                    <?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
                                    <span style="color:<?php echo $status_color;?>;">
                                        <?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
                                    </span>
                                    
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
                                </td>



										<td class="name">
											<?php echo esc_html__($created_by->display_name,"school-mgt"); ?>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
										</td>



										<td class="width_20"><?php  if ($retrieved_data->attendence_type == 'QR') {



                                        echo esc_html__("Yes","school-mgt");



										}



										else{



                                        echo esc_html__("No","school-mgt");



                                    }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance With QR Code','school-mgt');?>"></i></td>

									<td class="name">
                                    <?php
                                    if(!empty($retrieved_data->comment))
                                    {   $comment = $retrieved_data->comment;
                                        $grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
                                        echo $grade_comment;
                                    }
                                    else
                                    {
                                        echo "N/A";
                                    }
                                    ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
                                    if(!empty($retrieved_data->comment))
                                    { 
                                        echo $retrieved_data->comment;
                                    } 
                                    else
                                    {  _e('Comment','school-mgt');
                                    }
                                    ?>
                                    "></i>
                                </td>

									</tr>



									<?php 



									$i++;	



									$srno++;



								}	 



							}



							?>



						</tbody>



					</table>



					



				</div><!-- TABLE RESPONSIVE DIV END -->



			</div>



			<?php



		}



		else



		{



			$page_1='attendance';



			$fattendance_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);



			if($role == 'admin' || $fattendance_1['add']=='1')



			{



				?>



				<div class="no_data_list_div"> 



					<a href="<?php echo admin_url().'admin.php?page=smgt_attendence';?>">



						<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >



					</a>



					<div class="col-md-12 dashboard_btn margin_top_20px">



						<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>



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

	if($active_tab1=='role_teacher_attedance_list'){

		$teacher_id = get_current_user_id();

		$attendance_list = mj_smgt_monthly_attendence_teacher($teacher_id);

		if(!empty($attendance_list))

		{

			?>

			<script type="text/javascript">

				jQuery(document).ready(function($) {

					"use strict";

					jQuery('#attendance_list_detailpage').DataTable({
						"initComplete": function(settings, json) {
							$(".print-button").css({"margin-top": "-5%"});
						},
						//stateSave: true,
						"responsive": true,

						"order": [[ 1, "desc" ]],

						"aoColumns":[

									{"bSortable": false},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true},

									{"bSortable": true}],

						dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',

						language:<?php echo mj_smgt_datatable_multi_language();?>

						});

						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

					$('.dataTables_filter').addClass('search_btn_view_page');

				} );

			</script>

			<div class="table-div"><!-- PANEL BODY DIV START -->

				<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->

					<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">

						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

							<tr>

								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

								<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>	

								<th><?php esc_attr_e('Attendance Date','school-mgt');?></th>  

								<th><?php esc_attr_e('Day','school-mgt');?> </th>  

								<th><?php esc_attr_e('Attendance By','school-mgt');?></th>  

								<th><?php esc_attr_e('Status','school-mgt');?> </th>  

								<th><?php esc_attr_e('Comment','school-mgt');?> </th>  

							</tr>

						</thead>

						<tbody>

							<?php

							$i=0;

							$srno = 1;

							if(!empty($attendance_list))
							{
								foreach ($attendance_list as $retrieved_data)
								{
									if($i == 10)
									{
										$i=0;
									}
									if($i == 0)
									{
										$color_class='smgt_class_color0';
									}
									elseif($i == 1)
									{
										$color_class='smgt_class_color1';
									}
									elseif($i == 2)
									{
										$color_class='smgt_class_color2';
									}
									elseif($i == 3)
									{
										$color_class='smgt_class_color3';
									}
									elseif($i == 4)
									{
										$color_class='smgt_class_color4';
									}
									elseif($i == 5)
									{
										$color_class='smgt_class_color5';
									}
									elseif($i == 6)
									{
										$color_class='smgt_class_color6';
									}
									elseif($i == 7)
									{
										$color_class='smgt_class_color7';
									}
									elseif($i == 8)
									{
										$color_class='smgt_class_color8';
									}
									elseif($i == 9)
									{
										$color_class='smgt_class_color9';
									}

									?>

									<tr>

										<td class="user_image width_50px profile_image_prescription">

											<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image attendace_module_image center">

											</p>

										</td>

										<td class=""><?php echo mj_smgt_get_user_name_byid($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

										<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance Date','school-mgt');?>"></i></td>
									
										<td class="">

											<?php

												$curremt_date = $retrieved_data->attendence_date;

												$day=date("D", strtotime($curremt_date));

												if($day == 'Mon')
												{
													esc_html_e('Monday','school-mgt');
												}  
												elseif($day == 'Sun')
												{
													esc_html_e('Sunday','school-mgt');
												} 
												elseif($day == 'Tue')
												{
													esc_html_e('Tuesday','school-mgt');
												}
												elseif($day == 'Wed')
												{
													esc_html_e('Wednesday','school-mgt');
												}
												elseif($day == 'Thu')
												{
													esc_html_e('Thursday','school-mgt');
												}
												elseif($day == 'Fri')
												{
													esc_html_e('Friday','school-mgt');
												}
												elseif($day == 'Sat')
												{
													esc_html_e('Saturday','school-mgt');
												}
											?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i>

										</td>
										<td class="name">
											<?php echo mj_smgt_get_display_name($retrieved_data->attend_by); ?>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
										</td>
										<td class="name">
                                    <?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
                                    <span style="color:<?php echo $status_color;?>;">
                                        <?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
                                    </span>
                                    
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
                                </td>
										<td class="name">
											<?php
											if(!empty($retrieved_data->comment))
											{
												$comment =$retrieved_data->comment;
												$grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
												echo $grade_comment;
											}
											else
											{
												echo "N/A";
											}
											?>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
											if(!empty($retrieved_data->comment))
											{ 
												echo $retrieved_data->comment;
											} 
											else
											{  _e('Comment','school-mgt');
											}
											?>
											"></i>
										</td>


									</tr>



									<?php



									$i++;



									$srno++;



								}



							}



							?>



						</tbody>



					</table>







				</div><!-- TABLE RESPONSIVE DIV END -->



			</div>



			<?php



		}
		else{
			?>

			<div class="calendar-event-new"> 



				<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >



			</div>
			<?php
		}



	}


	if($active_tab1=='attedance_list')



	{



		?>



		<form method="post" id="attendance_list" class="attendance_list">  



			<div class="form-body user_form margin_top_15px">



				<div class="row">



					<div class="col-md-3 mb-3 input">



						<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>			



							<select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">



								<!-- <option value=""><?php esc_attr_e('Select','school-mgt');?></option> -->



								<option value="today"><?php esc_attr_e('Today','school-mgt');?></option>



								<option value="this_week"><?php esc_attr_e('This Week','school-mgt');?></option>



								<option value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>



								<option value="this_month" 	selected><?php esc_attr_e('This Month','school-mgt');?></option>



								<option value="last_month"><?php esc_attr_e('Last Month','school-mgt');?></option>



								<option value="last_3_month"><?php esc_attr_e('Last 3 Months','school-mgt');?></option>



								<option value="last_6_month"><?php esc_attr_e('Last 6 Months','school-mgt');?></option>



								<option value="last_12_month"><?php esc_attr_e('Last 12 Months','school-mgt');?></option>



								<option value="this_year"><?php esc_attr_e('This Year','school-mgt');?></option>



								<option value="last_year"><?php esc_attr_e('Last Year','school-mgt');?></option>



								<option value="period"><?php esc_attr_e('Period','school-mgt');?></option>



							</select>



					</div>



					<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 input">



						<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>



						<?php if(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>



						<select name="class_id"  id="attendance_class_list_id" class="form-control user_select max_width_100">



							<?php



							if($school_obj->role == 'supportstaff'){



								?>



								<option value="all class"><?php esc_attr_e('All Class','school-mgt');?></option>



								<?php



							}



							?>



							<?php



							foreach(mj_smgt_get_allclass() as $classdata)



							{  



								?>



								<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>



								<?php 



							}?>



						</select>



					</div>



					<div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	



					<div class="col-md-3 mb-2">



						<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>



					</div>



				</div>



			</div>



		</form> 



		<div class="clearfix"></div>



		<?php







		if(isset($_REQUEST['view_attendance']))



		{



		$date_type = $_POST['date_type'];



		$class_id = $_REQUEST['class_id'];



		$attendence_data = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);



		}



		else{



			$date_type = '';



			if($school_obj->role == 'teacher'){



				$teacher_id = get_current_user_id();



				$cla_id = smgt_get_class_by_teacher_id($teacher_id);


				$class_id = $cla_id[0]->class_id;	

				

				$attendence_data = smgt_get_student_attendance_by_class_id($start_date,$end_date,$class_id,$date_type);
				
				

			}



			else{



				$start_date = date('Y-m-d',strtotime('first day of this month'));



				$end_date = date('Y-m-d',strtotime('last day of this month'));



				$attendence_data = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);


				
			}



			



			



		}



		if($start_date > $end_date )



		{



		echo '<script type="text/javascript">alert("'.esc_html__('End Date should be greater than the Start Date','school-mgt').'");</script>';



		}



		if(!empty($attendence_data))



		{

			

			



		?>



		<script type="text/javascript">



			$(document).ready(function() 



			{



				"use strict";



				var table = jQuery('#attend_list').DataTable({

					"initComplete": function(settings, json) {
						$(".print-button").css({"margin-top": "-5%"});
					},
					//stateSave: true,
					"ordering": true,



					dom: 'lifrtp',



					"aoColumns":[



							{"bSortable": false},



							{"bSortable": false},



							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true},
							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true}



						],



					language:<?php echo mj_smgt_datatable_multi_language();?>		   



				});



				$('.btn-place').html(table.buttons().container()); 



				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");



				$("#delete_selected").on('click', function()



        {	



            if ($('.select-checkbox:checked').length == 0 )



            {



                alert(language_translate2.one_record_select_alert);



                return false;



            }



            else



            {



                    var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?', 'school-mgt') ?>");



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



			} );



		</script>



		<?php



		if(isset($_REQUEST['delete_selected_attendance']))



		{		
			


			if(!empty($_REQUEST['id']))



			foreach($_REQUEST['id'] as $id)

					$result = mj_smgt_delete_attendance($id);

			if($result)

			{ 
				wp_redirect ( home_url().'?dashboard=user&page=attendance&tab=student_attendance&message=2');
			}



		}



		?>



		<div class="table-div"><!-- PANEL BODY DIV START -->







			<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->



				<div class="btn-place"></div>	



				<form id="frm-example" name="frm-example" method="post">



				<table id="attend_list" class="display" cellspacing="0" width="100%">



					<thead class="<?php echo MJ_smgt_datatable_heder(); ?>">



						<tr>



							<th class="padding_0"><input type="checkbox" class=" multiple_select select_all attendance_select_all_option" id="select_all"></th>



							<th><?php esc_html_e('Photo','school-mgt');?></th>



							<th><?php esc_html_e('Student Name','school-mgt');?></th>



							<th><?php esc_html_e('Class Name','school-mgt');?></th>



							<th><?php esc_html_e('Date','school-mgt');?></th>



							<th><?php esc_html_e('Day','school-mgt');?></th>



							<th><?php esc_html_e('Attendance By','school-mgt');?></th>



							<th><?php esc_html_e('Attendance Status','school-mgt');?></th>



							<th><?php esc_html_e('Attendance With QR','school-mgt');?></th>
							<th><?php esc_html_e('Comment','school-mgt');?></th>



						</tr>



					</thead>



					<tbody>



						<?php



							foreach ($attendence_data as $retrieved_data)



							{



								if(isset($retrieved_data->class_id) && $retrieved_data->class_id)



								{

									$class_section_sub_name=smgt_get_class_section_subject($retrieved_data->class_id,$retrieved_data->section_id,$retrieved_data->sub_id);

									$member_data = get_userdata($retrieved_data->user_id);



									$created_by = get_userdata($retrieved_data->attend_by);



									if(!empty($member_data->parent_id))



									{



										$parent_data = get_userdata($member_data->parent_id);



									}



									if($i == 10)



									{



										$i=0;



									}



									if($i == 0)



									{



										$color_class='smgt_class_color0';



									}



									elseif($i == 1)



									{



										$color_class='smgt_class_color1';



									}



									elseif($i == 2)



									{



										$color_class='smgt_class_color2';



									}



									elseif($i == 3)



									{



										$color_class='smgt_class_color3';



									}



									elseif($i == 4)



									{



										$color_class='smgt_class_color4';



									}



									elseif($i == 5)



									{



										$color_class='smgt_class_color5';



									}



									elseif($i == 6)



									{



										$color_class='smgt_class_color6';



									}



									elseif($i == 7)



									{



										$color_class='smgt_class_color7';



									}



									elseif($i == 8)



									{



										$color_class='smgt_class_color8';



									}



									elseif($i == 9)



									{



										$color_class='smgt_class_color9';



									}



									?>



									<tr>



									<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->attendance_id;?>"></td>



										<td class="cursor_pointer user_image width_50px profile_image_prescription padding_left_0">



											<p class="remainder_title_pr Bold prescription_tag para_margin <?php echo $color_class; ?>">	



												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center" style="padding-top:28%;">



											</p>



										</td>



										<td class="name">



											<?php



											if($member_data->roles[0] == "student")



											{



												echo $member_data->display_name; 



											}else{



													echo $member_data->display_name; 



											}



												?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php echo $class_section_sub_name; ?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); ?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php 

												$day=date("l", strtotime($retrieved_data->attendance_date));
												esc_html_e($day,'school-mgt');
											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Day','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php echo esc_html__($created_by->display_name,"school-mgt"); ?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>



										</td>



										<td class="name">
											<?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
											<span style="color:<?php echo $status_color;?>;">
												<?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
											</span>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
										</td>



										<td class="name">



											<?php



											if ($retrieved_data->attendence_type == 'QR') {



												echo esc_html__("Yes","school-mgt");



											}



											else{



												echo esc_html__("No","school-mgt");



											}



											?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance With QR','school-mgt');?>" ></i>



										</td>
										
										
										<td class="name">
											<?php
											$comment =$retrieved_data->comment;
											if(!empty($comment))
											{
												$comment_out = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
												echo  $comment_out;
											}
											else{
												echo  "N/A";
											}
											
											?>

											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php  echo $retrieved_data->comment;?>" ></i>



										</td>
										
										



									</tr>



									<?php 



								}



								$i++;



							}



						?>



					</tbody>



				</table>



				<div class="print-button pull-left">



					<button class="btn-sms-color button_reload">



						<input type="checkbox" name="" class="smgt_sub_chk select-checkbox select_all" value="" style="margin-top: 0px;">



						<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>



					</button>



						<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_attendance" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
						<input type="hidden" name="filtered_date_type" value="<?php echo $date_type;?>" />
                		<input type="hidden" name="filtered_class_id" value="<?php echo $class_id;?>" />
						<button data-toggle="tooltip" title="<?php esc_html_e('Export Attendance','school-mgt');?>" name="export_attendance_in_csv" class="export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>


				</div>



				</form>



			</div><!-- TABLE RESPONSIVE DIV END -->



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



		?>



		<?php



	}







	if($active_tab1=='attendence'){



		?>



		<div class="panel-body"><!------------ PENAL BODY ------------->



			<!-------------- STUDENT ATTENDENCE FORM -------------------->



			<form method="post" id="student_attendance">  



				<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />  



				<div class="form-body user_form">



					<div class="row">



						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">



							<div class="form-group input">



								<div class="col-md-12 form-control">



									<input id="curr_date_sub123" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" name="curr_date" readonly>



									<label class="control-label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>



								</div>



							</div>



						</div>



						<div class="col-md-3 mb-3 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			



							<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>



							<select name="class_id"  id="class_list"  class="line_height_30px form-control validate[required]">



								<option value=" "><?php esc_attr_e('Select class','school-mgt');?></option>



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



							<select name="class_section" class="line_height_30px form-control" id="class_section">



									<option value=""><?php esc_attr_e('Select Section','school-mgt');?></option>



								<?php if(isset($_REQUEST['class_section'])){



										$class_section=$_REQUEST['class_section'];



										foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)



										{  ?>



										<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>



									<?php }



									} ?>



							</select>



						</div>



						<div class="col-md-3 mb-3">



							<input type="submit" value="<?php esc_attr_e('Take Attendance','school-mgt');?>" name="attendence" class="btn btn-success save_btn"/>



						</div>



					</div>



				</div>



			</form><!-------------- STUDENT ATTENDENCE FORM -------------------->



			<div class="clearfix"></div>



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



					<div id="message" class=" alert alert-warning alert-dismissible alert_attendence" role="alert">



						<button type="button" class="btn-default notice-dismiss " data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>



						</button>



						<?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?>



					</div>



				<?php 



				}



				elseif(0 < $user)



				{



					if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != " ")



					$class_id =$_REQUEST['class_id'];



					else 



						$class_id = 0;



					if($class_id == 0)



					{



						?>



						<div class="panel-heading">



							<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>



						</div>



						<?php 



					}



					else



					{               



						if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")



						{



							$exlude_id = mj_smgt_approve_student_list();



							$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],



									'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id,'orderby' => 'display_name','order' => 'ASC'));	



							// sort($student);



						}



						else



						{ 



							$exlude_id = mj_smgt_approve_student_list();



							$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id,'orderby' => 'display_name','order' => 'ASC'));



							// sort($student);



						}



						?>              



					



						<form method="post" class="form-horizontal">        



							<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />



							<input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />



							<input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />



							<div class="panel-heading">



								<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 



								<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?></h4>



							</div>



							<div class="col-md-12">



								<div class="table-responsive">



									<table class="table">



										<tr>



											<th class="multiple_subject_mark"><?php esc_attr_e('Sr. No.','school-mgt');?></th>



											<th class="multiple_subject_mark"><?php esc_attr_e('Roll No.','school-mgt');?></th>



											<th class="multiple_subject_mark"><?php esc_attr_e('Student','school-mgt');?></th>



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



										$i++; } ?>                   



									</table>



								</div>



								<div class="d-flex mt-2">



								<div class="form-group row mb-3">



									<label class="col-sm-8 control-label" for="enable"> <?php esc_attr_e('If student absent then Send Mail','school-mgt');?></label>



									<div class="col-sm-2 ps-0">



										<div class="checkbox">



											<label>



												<input class="smgt_check_box" id="smgt_mail_service_enable" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">



											</label>



										</div>				 



									</div>



								</div>



												



								<div class="form-group row mb-3">



									<label class="col-sm-10 control-label col-form-label" for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>



									<div class="col-sm-2 pt-2 ps-0">



										<div class="checkbox">



											<label>



												<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">



											</label>



										</div>				 



									</div>



								</div>



								



							</div>



							<?php wp_nonce_field( 'save_attendence_front_nonce' ); ?>



							<?php 



							if($user_access['add'] == 1 OR $user_access['edit'] == 1)



							{



								?>



								<div class="col-sm-6 rtl_res_att_save"> 



									<input type="submit" value="<?php esc_attr_e('Save  Attendance','school-mgt');?>" name="save_attendence" class="save_btn btn btn-success" />



								</div>   



								<?php 



							} ?>	



						</form>		



						<?php 



					}  



				} 



				else



				{



					?>



					<div class="smgt_no_attence_css">



						<h4 style=" font-size: 24px;font-weight: 500;"><?php esc_html_e("No Any Student In This Class" , "school-mgt"); ?></h4>



					</div>



					<?php



				}



			} ?>



		</div><!------------ PENAL BODY ------------->



		<?php



	}















	if($active_tab1=='subject_attendence'){



		?>



		<div class="panel-body"><!-------------- PENAL BODY --------------->



			<!---------------- SUBJECT WISE ATTENDANCE FORM -------------->



			<form method="post" id="subject_attendance">  



				<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />



				<div class="form-body user_form">



					<div class="row">



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">



							<div class="form-group input">



								<div class="col-md-12 form-control">



									<input id="curr_date_sub" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="curr_date" readonly>



									<label class="" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>	



								</div>



							</div>



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			



							<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>



							<select name="class_id"  id="class_list"  class="line_height_30px form-control validate[required]">



								<option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option>



								<?php



								foreach(mj_smgt_get_allclass() as $classdata)



								{ ?>



									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>



									<?php 



								}?>



							</select>			



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>			



							<?php 



							$class_section="";



							if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>



							<select name="class_section" class="line_height_30px form-control" id="class_section">



							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>



								<?php if(isset($_REQUEST['class_section'])){



								$class_section=$_REQUEST['class_section']; 



									foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)



									{  ?>



										<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>



									<?php } 



									} ?>		



							</select>



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field"></span></label>



							<select name="sub_id"  id="subject_list"  class="line_height_30px form-control">



								<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>



								<?php $sub_id=0;



								if(isset($_POST['sub_id']))



								{



									$sub_id=$_POST['sub_id'];



									?>



									<?php $allsubjects = mj_smgt_get_subject_by_classid($_POST['class_id']);



									foreach($allsubjects as $subjectdata)



									{ ?>



										<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>



										<?php



									}



								} ?>



							</select>			



						</div>



					</div>



				</div>



				<div class="form-body user_form">



					<div class="row">		



						<div class="col-md-6">



							<input type="submit" value="<?php esc_attr_e('Take Attendance','school-mgt');?>" name="attendence"  class="btn btn-success save_btn"/>



						</div>



					</div>



				</div>  



			</form><!---------------- SUBJECT WISE ATTENDANCE FORM -------------->



		</div><!-------------- PENAL BODY --------------->



		<div class="clearfix"> </div>



		<?php 



		if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_sub_attendence']))



		{



			$attendanace_date=$_REQUEST['curr_date'];



			$holiday_dates=mj_smgt_get_all_date_of_holidays();



			if (in_array($attendanace_date, $holiday_dates))



			{



				?>



					<div id="message" class=" alert alert-warning alert-dismissible alert_attendence" role="alert">



						<button type="button" class="btn-default notice-dismiss " data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>



						</button>



						<?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?>



					</div>



			<?php 



			}



			else



			{



				if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != " ")



					$class_id =$_REQUEST['class_id'];



				else 



					$class_id = 0;



				if($class_id == 0)



				{



				?>



					<div class="panel-heading">



						<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>



					</div>



			<?php  



				}



				else



				{                



					if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")



					{						



						$exlude_id = mj_smgt_approve_student_list();



						$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],



						'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));



						sort($student);					



					}



					else



					{ 



						$exlude_id = mj_smgt_approve_student_list();



						$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));



						sort($student);		



					} 

					if($student)
					{

					?>



					<div class="panel-body">  



						<form method="post"  class="form-horizontal mt-4 mt-4">



							<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />



							<input type="hidden" name="sub_id" value="<?php echo $sub_id;?>" />



							<input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />



							<input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />



					



							<div class="panel-heading">



								<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 



								<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?></h4>



							</div>



					



							<div class="col-md-12">



								<div class="table-responsive">



									<table class="table">



										<tr>



											<th class="multiple_subject_mark" style="width: 75px!important;"><?php esc_attr_e('Sr. No.','school-mgt');?></th>



											<th class="multiple_subject_mark"><?php esc_attr_e('Student Name','school-mgt');?></th>



											<th class="multiple_subject_mark" style="width: 250px!important;"><?php esc_attr_e('Attendance','school-mgt');?></th>



											<th class="multiple_subject_mark"><?php esc_attr_e('Comment','school-mgt');?></th>



										</tr>



										<?php



										$date = $_POST['curr_date'];



										$i = 1;



										foreach ( $student as $user ) 



										{



											$date = date('Y-m-d',strtotime($_POST['curr_date']));                   



											//$check_attendance = $obj_attend->mj_smgt_check_sub_attendence($user->ID,$class_id,$date,$_POST['sub_id']);
											$check_attendance = $obj_attend->mj_smgt_check_has_subject_attendace($user->ID,$class_id,$date,$_POST['sub_id'],$_POST['class_section']);



											$attendanc_status = "Present";



											if(!empty($check_attendance))

											{

												$attendanc_status = $check_attendance->status;

											}                   

											echo '<tr>';              

											echo '<td>'.$i.'</td>';

											echo '<td><span>' .mj_smgt_student_display_name_with_roll($user->ID). '</span></td>';

											?>

											<td>

												<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>

												<?php esc_attr_e('Present','school-mgt');?></label>

												<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>

												<?php esc_attr_e('Absent','school-mgt');?></label><br>

												<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>

												<?php esc_attr_e('Late','school-mgt');?></label>

												<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Half Day" <?php checked( $attendanc_status, 'Half Day' );?>>

												<?php esc_attr_e('Half Day','school-mgt');?></label>

											</td>

											<td class="padding_left_right_0">

												<div class="form-group input margin_bottom_0px">

													<div class="col-md-12 form-control"> 

														<input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control " value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>">

													</div>

												</div>

											</td>

											<?php 

											echo '</tr>';

											$i++; } ?>

									</table>

								</div>

							<?php wp_nonce_field( 'save_sub_attendence_front_nonce' ); ?>
							
							<div class="d-flex mt-2">
							
							<div class="form-group row mb-3">

								<label class="col-sm-10 control-label pt-2" for="enable"> <?php esc_attr_e('If student absent then Send Email to his/her parents','school-mgt');?></label>

								<div class="col-sm-2 pt-2 ps-0">

									<div class="checkbox">

										<label>

											<input class="smgt_check_box" id="smgt_mail_service_enable" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">

										</label>

									</div>				 

								</div>

						    </div>

							<div class="form-group row mb-3 margin_rtl_right_20px rtl_margin_bottom_0px">

								<label class="col-sm-10 control-label col-form-label" for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>

								<div class="col-sm-2 pt-2 ps-0">

									<div class="checkbox">

										<label>

											<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">

										</label>

									</div>				 

								</div>

							</div>

							</div>
							
							</div>

							<?php 

							if($user_access['add'] == 1 OR $user_access['edit'] == 1)

							{
								?>

								<div class="form-body user_form">

									<div class="row">

										<div class="col-sm-6 rtl_res_att_save"> 

											<input type="submit" value="<?php esc_attr_e("Save Attendance","school-mgt");?>" name="save_sub_attendence" class="btn btn-success rtl_margin_0px save_btn" />

										</div>  

									</div>

								</div>

								<?php 

							} ?>						

						</form>

					</div>

			<?php 
					}
					else
					{
						?>
						<div class=" mt-2">
							<h4 class="panel-title"><?php esc_html_e("No Any Student In This Class" , "school-mgt"); ?></h4>
						</div>
						<?php
					}


				}



			}



		}



	}







	if($active_tab1=='attendence_with_qr'){



		?>



			<script type="text/javascript">



			$(document).ready(function() 



			{	



				"use strict";



				$('#curr_date').datepicker({



					maxDate:'0',

					changeYear:true,

					changeMonth: true,

					dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",



					beforeShow: function (textbox, instance) 



					{



						instance.dpDiv.css({



							marginTop: (-textbox.offsetHeight) + 'px'                   



						});



					}



				});



			}); 



			</script>



			<div class="panel-body attendence_penal_body">



				<form method="post">



					<div class="form-body user_form"> <!-- user_form Strat-->



						<div class="row"><!--Row Div Strat-->



							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">



								<div class="form-group input">



									<div class="col-md-12 form-control">



									<input id="curr_date" class="form-control qr_date" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="curr_date" readonly>		



									<label class="l" for="curr_date"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>



									</div>



								</div>



							</div>



							<!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



								<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			



								<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>                 



								<select name="class_id"  id="class_list"  class="form-control validate[required] user_select qr_class_id">



									<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>



										<?php 



										foreach(mj_smgt_get_allclass() as $classdata)



										{  



											?>



											<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>



											<?php 



										}?>



								</select>			



							</div> -->

							

							

							

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*


							
							</span></label>			



							<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>



							<select name="class_id"  id="class_list"  class="line_height_30px form-control validate[required] qr_class_id">



								<option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option>



								<?php



								foreach(mj_smgt_get_allclass() as $classdata)



								{ ?>



									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>



									<?php 



								}?>



							</select>			



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>			



							<?php 



							$class_section="";



							if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>



							<select name="class_section" class="line_height_30px form-control qr_class_section" id="class_section">



							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>



								<?php if(isset($_REQUEST['class_section'])){



								$class_section=$_REQUEST['class_section']; 



									foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)



									{  ?>



										<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>



									<?php } 



									} ?>		



							</select>



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field"></span></label>



							<select name="sub_id"  id="subject_list"  class="line_height_30px form-control validate[required] qr_class_subject">



								<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>



								<?php $sub_id=0;



								if(isset($_POST['sub_id']))



								{



									$sub_id=$_POST['sub_id'];



									?>



									<?php $allsubjects = mj_smgt_get_subject_by_classid($_POST['class_id']);



									foreach($allsubjects as $subjectdata)



									{ ?>



										<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>



										<?php



									}



								} ?>



							</select>			



						</div>



							



						</div>



					</div> 



						<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL. '/lib/jsqrscanner/jsqrscanner.nocache.js'; ?>"></script>



						<div class="panel-heading">



							<h4 class="panel-title"><?php _e('Scan QR Code To Take Attendance','school-mgt');?> 			



						</div>



						<div class="col-md-12">



						<div class="qrscanner" id="scanner">



						</div>



							<hr>







							<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">



							<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>



							<script type="text/javascript">





								function onQRCodeScanned(result)

								{

								    

								       const result_obj = JSON.parse(result);

                                       

                                        var user_id = result_obj.user_id;

                                        var user_class_id = result_obj.class_id;

                                        var user_section_id = result_obj.section_id;

                                        var qr_code_name = result_obj.qr_type;

                                        

                                        if (qr_code_name == 'schoolqr')

                                        {

    										var selected_class_id = $(".qr_class_id").val();

    										var selected_class_section = $(".qr_class_section").val();

    										var selected_class_subject = $(".qr_class_subject").val();

    										var qr_date = $(".qr_date").val();

    										var attendance_url=user_id+'_'+user_class_id+'_'+qr_date+'_'+user_section_id+'_'+selected_class_id+'_'+selected_class_subject+'_'+selected_class_section;

    										var serch = attendance_url.search("data");

    										if(user_class_id != " ")

    										{
												if(user_class_id == selected_class_id && selected_class_id !="")
												{
													if(qr_date != " ")

													{

														var myString = attendance_url.substr(attendance_url.indexOf("=") + 1)

														

														$.ajax({

		

															type: "POST",  

		

															url: "<?php echo admin_url('admin-ajax.php'); ?>",

		

															data: { action: 'MJ_smgt_qr_code_take_attendance',attendance_url:myString},

		

															dataType: "json",

		

															complete: function (e)

															{
																if(e.responseText == 1)
																{


																swal("<?php esc_html_e('Success!','gym_mgt'); ?>", "<?php esc_html_e('Attendance successfully','gym_mgt'); ?>", "success");



																return true;

																}
																else if(e.responseText == '2')

																{

		

																swal("<?php esc_html_e('Oops!','gym_mgt'); ?>", "<?php esc_html_e('Please select correct class!','gym_mgt'); ?>", "error");

																return true;



																

																}

																else if(e.responseText == '3')

																{

																swal("<?php esc_html_e('Oops!','gym_mgt'); ?>", "<?php esc_html_e('Student Not Found!','gym_mgt'); ?>", "error");

																return true;


																}
																else
																{
																	swal("<?php esc_html_e('Oops!','gym_mgt'); ?>", "<?php esc_html_e('Something went wrong, you should choose again!','gym_mgt'); ?>", "error");

																return true;

																}

		

															}

		

														});	

		

													}

		

													else

		

													{

		

													

														swal("<?php esc_html_e('Warning!','gym_mgt'); ?>", "<?php esc_html_e('Please select date!','gym_mgt'); ?>", "warning");

														return true;

													}

												}
												else
												{

													swal("<?php esc_html_e('Warning!','school-mgt'); ?>", "<?php esc_html_e('Selected class not match to student class!','school-mgt'); ?>", "warning");

													return true;

												}

    					                 	}

    										else

    										{

    

    										swal("<?php esc_html_e('Warning!','gym_mgt'); ?>", "<?php esc_html_e('Please select  class!','gym_mgt'); ?>", "warning");

                                            return true;

    						                 }

										

                                        }

                                        else if (result == 'Invalid constraint')

                                        {

                                        }

                                        else if (result == 'Requested device not found')

                                        {

                                            swal("<?php esc_html_e('Oops!','gym_mgt'); ?>", "<?php esc_html_e('Camera device not found!','school-mgt'); ?>", "error"); 

                                         return true;

                                            

                                        }

                                        else

                            			{

                            		    	swal("<?php esc_html_e('Oops!','gym_mgt'); ?>", "<?php esc_html_e('QR code does not match, you should choose again!','school-mgt'); ?>", "error"); 

                                        return true;

                            			}



							    	}



									function JsQRScannerReady()

									{



										//create a new scanner passing to it a callback function that will be invoked when



										//the scanner succesfully scan a QR code



										var jbScanner = new JsQRScanner(onQRCodeScanned);



										//reduce the size of analyzed images to increase performance on mobile devices



										jbScanner.setSnapImageMaxSize(200);



										var scannerParentElement = document.getElementById("scanner");



										if(scannerParentElement)



										{



											//append the jbScanner to an existing DOM element



											jbScanner.appendTo(scannerParentElement);



										}        



									}



							</script>



						</div>   



				</form>



			</div>



		<?php



	}















	if($active_tab1=='export_attendance'){



		?>



		<div class="panel-body"><!-- panel-body --> 



			<form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">



				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>



				<input type="hidden" name="action" value="<?php echo $action;?>">		



				<div class="col-sm-12">        	



					<input type="submit" value="<?php esc_attr_e('Export Student Attendance','school-mgt');?>" name="export_attendance_in_csv" class="col-sm-6 save_att_btn"/>



				</div>



			</form>



		</div><!-- panel-body --> 



		<?php



	}











	if($active_tab1=='teacher_attendences'){



		?>



		<form method="post" id="teacher_attendance">           



			<div class="form-body user_form">



				<div class="row">



					<div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">



						<div class="form-group input">



							<div class="col-md-12 form-control">



								<input id="curr_date_teacher" class="form-control" type="text" value="<?php if(isset($_POST['tcurr_date'])) echo mj_smgt_getdate_in_input_box($_POST['tcurr_date']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="tcurr_date" readonly>	



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



	//------------------------ SAVE TEACHER ATTENDENCE ----------------------//



	if(isset($_REQUEST['teacher_attendence']) || isset($_REQUEST['save_teach_attendence']))



	{	



		$attendanace_date=$_REQUEST['tcurr_date'];



		$holiday_dates=mj_smgt_get_all_date_of_holidays();



		if (in_array($attendanace_date, $holiday_dates))



		{



			?>



			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">



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



						<input type="submit" value="<?php esc_attr_e("Save  Attendance","school-mgt");?>" name="save_teach_attendence" id="res_rtl_width_100" class="col-sm-12 save_att_btn" />



					</div>       



				</form>



			</div><!-- panel-body -->



			<?php



		}



	}



	if($active_tab=='attedance_list'){



		$student_id = get_current_user_id();



		$attendance_list = mj_smgt_monthly_attendence($student_id);



		if(!empty($attendance_list))



		{



			?>



			<script type="text/javascript">



				jQuery(document).ready(function($) {



					"use strict";



					jQuery('#attendance_list_detailpage').DataTable({

						//stateSave: true,

						"responsive": true,



						"order": [[ 1, "desc" ]],



						"aoColumns":[	                  



									{"bSortable": false},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true},



									{"bSortable": true}],



						dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',



						language:<?php echo mj_smgt_datatable_multi_language();?>



						});



						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");



					$('.dataTables_filter').addClass('search_btn_view_page');



				} );



			</script>



	



			<div class="table-div"><!-- PANEL BODY DIV START -->



				<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->



					<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">



						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">



							<tr>



								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>



								<th><?php esc_attr_e('Student Name & Roll No.','school-mgt');?></th>



								<th><?php esc_attr_e('Class Name','school-mgt');?></th>  



								<th><?php esc_attr_e('Attendance Date','school-mgt');?> </th>  



								<th><?php esc_attr_e('Day','school-mgt');?> </th>  



								<th><?php esc_attr_e('Status','school-mgt'); ?></th>



								<th><?php esc_attr_e('Comment','school-mgt');?></th>



							</tr>



						</thead>



						<tbody>



							<?php 



							$i=0;	



							$srno = 1;



							if(!empty($attendance_list))



							{



								foreach ($attendance_list as $retrieved_data)



								{



									if($i == 10)



									{



										$i=0;



									}



									if($i == 0)



									{



										$color_class='smgt_class_color0';



									}



									elseif($i == 1)



									{



										$color_class='smgt_class_color1';







									}



									elseif($i == 2)



									{



										$color_class='smgt_class_color2';







									}



									elseif($i == 3)



									{



										$color_class='smgt_class_color3';







									}



									elseif($i == 4)



									{



										$color_class='smgt_class_color4';







									}



									elseif($i == 5)



									{



										$color_class='smgt_class_color5';







									}



									elseif($i == 6)



									{



										$color_class='smgt_class_color6';







									}



									elseif($i == 7)



									{



										$color_class='smgt_class_color7';







									}



									elseif($i == 8)



									{



										$color_class='smgt_class_color8';







									}



									elseif($i == 9)



									{



										$color_class='smgt_class_color9';







									}



									?>



									<tr>



										<td class="user_image width_50px profile_image_prescription">



											<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	



												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image attendace_module_image center">



											</p>



										</td>



										<td class="department"><?php echo mj_smgt_get_user_name_byid($retrieved_data->user_id);?>-<?php echo get_user_meta($retrieved_data->user_id, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Roll No.','school-mgt');?>"></i></td>



										<td class="">



											<?php echo mj_smgt_get_class_name($retrieved_data->class_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i>



										</td>



										<?php $curremt_date=mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); $day=date("D", strtotime($curremt_date)); ?>



										<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance Date','school-mgt');?>"></i></td>



										<td class="department"><?php 



										if($day == 'Mon')



										{



											esc_html_e('Monday','school-mgt');



										}  



										elseif($day == 'Sun')



										{



											esc_html_e('Sunday','school-mgt');



										} 



										elseif($day == 'Tue')



										{



											esc_html_e('Tuesday','school-mgt');



										}



										elseif($day == 'Wed')



										{



											esc_html_e('Wednesday','school-mgt');



										}



										elseif($day == 'Thu')



										{



											esc_html_e('Thursday','school-mgt');



										}



										elseif($day == 'Fri')



										{



											esc_html_e('Friday','school-mgt');



										}



										elseif($day == 'Sat')



										{



											esc_html_e('Saturday','school-mgt');



										}



										?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>



										<td><?php echo esc_html_e($retrieved_data->status,'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>



										<?php



										$comment =$retrieved_data->comment;



										$comment_out = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;



										?>



										<td class="width_20"><?php if(!empty($retrieved_data->comment)){ echo esc_html_e($comment_out); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Comment','school-mgt');?>"></i></td>



									</tr>



									<?php 



									$i++;	



									$srno++;



								}	 



							}



							?>



						</tbody>



					</table>



					



				</div><!-- TABLE RESPONSIVE DIV END -->



			</div>



			<?php



		}



		else



		{



			$page_1='attendance';



			$fattendance_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);



			if($role == 'admin' || $fattendance_1['add']=='1')



			{



				?>



				<div class="no_data_list_div"> 



					<a href="<?php echo admin_url().'admin.php?page=smgt_attendence';?>">



						<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >



					</a>



					<div class="col-md-12 dashboard_btn margin_top_20px">



						<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>



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







	if($active_tab1=='teacher_attedance_list'){



		?>



		<form method="post" id="attendance_list" class="attendance_list">  



			<div class="form-body user_form margin_top_15px">



				<div class="row">



					<div class="col-md-3 mb-3 input">



						<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>			



							<select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">



								<!-- <option value=""><?php esc_attr_e('Select','school-mgt');?></option> -->



								<option value="today"><?php esc_attr_e('Today','school-mgt');?></option>



								<option value="this_week"><?php esc_attr_e('This Week','school-mgt');?></option>



								<option value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>



								<option value="this_month" 	selected><?php esc_attr_e('This Month','school-mgt');?></option>



								<option value="last_month"><?php esc_attr_e('Last Month','school-mgt');?></option>



								<option value="last_3_month"><?php esc_attr_e('Last 3 Months','school-mgt');?></option>



								<option value="last_6_month"><?php esc_attr_e('Last 6 Months','school-mgt');?></option>



								<option value="last_12_month"><?php esc_attr_e('Last 12 Months','school-mgt');?></option>



								<option value="this_year"><?php esc_attr_e('This Year','school-mgt');?></option>



								<option value="last_year"><?php esc_attr_e('Last Year','school-mgt');?></option>



								<option value="period"><?php esc_attr_e('Period','school-mgt');?></option>



							</select>



					</div>







					<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 input">



						<!-- <label class="ml-1 custom-top-label top" for="staff_name"><?php //esc_html_e('Member','school-mgt');?><span class="require-field">*</span></label> -->



						<?php if(isset($_POST['teacher_name'])){$workrval=$_POST['teacher_name'];}else{$workrval='';}?>



						<select id="teacher_list" class="form-control user_select display-members" name="teacher_name">



							<option value="all_teacher"><?php esc_html_e('All Teacher','school-mgt');?></option>



								<?php $teacherdata=mj_smgt_get_usersdata('teacher');



								if(!empty($teacherdata))



								{



									foreach ($teacherdata as $teacher)



									{



										?>



											<option value="<?php echo $teacher->ID;?>" <?php selected($teacher->ID);  ?>><?php echo $teacher->display_name;?></option>



											<?php		



									}



								}?>



						</select>



					</div>



					<div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	



					<div class="col-md-3 mb-2">



						<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>



					</div>



				</div>



			</div>



		</form> 



		<div class="clearfix"></div>







		<?php







		if(isset($_REQUEST['view_attendance']))



		{







		$date_type = $_POST['date_type'];



		if($date_type=="period")



		{



			$start_date = $_REQUEST['start_date'];



			$end_date = $_REQUEST['end_date'];







			$type='teacher';



			$attendence_data = smgt_get_all_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$type);



		}



		else



		{



			$result =  mj_smgt_all_date_type_value($date_type);







			$response =  json_decode($result);



			$start_date = $response[0];



			$end_date = $response[1];







			if(!empty($_REQUEST['teacher_name'])  && $_REQUEST['teacher_name'] != "all_teacher")



			{



				$member_id = $_REQUEST['teacher_name'];



				$attendence_data=smgt_get_member_attendence_beetween_satrt_date_to_enddate_for_admin($start_date,$end_date,$member_id);



			}else{



				$type='teacher';



				$attendence_data = smgt_get_all_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$type);



			}



		}



		}else{



		$start_date = date('Y-m-d',strtotime('first day of this month'));



		$end_date = date('Y-m-d',strtotime('last day of this month'));







		$type='teacher';



		$attendence_data = smgt_get_all_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$type);



		}



		if($start_date > $end_date )



		{



		echo '<script type="text/javascript">alert("'.esc_html__('End Date should be greater than the Start Date','school-mgt').'");</script>';



		}



		if(!empty($attendence_data))



		{



		?>



		<script type="text/javascript">



			$(document).ready(function() 



			{



				"use strict";



				var table = jQuery('#attend_list').DataTable({



					//stateSave: true,

					"order": [[ 3, "desc" ]],



					dom: 'lifrtp',



					"aoColumns":[



							{"bSortable": false},



							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true},



							{"bSortable": true},

							{"bSortable": true},

							{"bSortable": true},
							{"bSortable": true}


						],



					language:<?php echo mj_smgt_datatable_multi_language();?>		   







				});



				$('.btn-place').html(table.buttons().container()); 



				$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");







			} );



		</script>







		<div class="table-div"><!-- PANEL BODY DIV START -->







			<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->



				<div class="btn-place"></div>	



				<table id="attend_list" class="display" cellspacing="0" width="100%">



					<thead class="<?php echo MJ_smgt_datatable_heder(); ?>">



						<tr>



							<th><?php esc_html_e('Photo','school-mgt');?></th>



							<th><?php esc_html_e('Teacher Name','school-mgt');?></th>



							<th><?php esc_html_e('Class Name','school-mgt');?></th>



							<th><?php esc_html_e('Date','school-mgt');?></th>



							<th><?php esc_html_e('Day','school-mgt');?></th>

							<th><?php esc_html_e('Attendance By','school-mgt');?></th>

							<th><?php esc_html_e('Attendance Status','school-mgt');?></th>

							<th><?php esc_html_e('Comment','school-mgt');?></th>


						</tr>



					</thead>



					<tbody>







						<?php



							



							foreach ($attendence_data as $retrieved_data)







							{



									$member_data = get_userdata($retrieved_data->user_id);



									$class = smgt_get_class_name_by_teacher_id($member_data->data->ID);



									



									if(!empty($member_data->parent_id))



									{



										$parent_data = get_userdata($member_data->parent_id);



									}



									if($i == 10)



									{



										$i=0;



									}



									if($i == 0)



									{



										$color_class='smgt_class_color0';



									}



									elseif($i == 1)



									{



										$color_class='smgt_class_color1';



									}



									elseif($i == 2)



									{



										$color_class='smgt_class_color2';



									}



									elseif($i == 3)



									{



										$color_class='smgt_class_color3';



									}



									elseif($i == 4)



									{



										$color_class='smgt_class_color4';



									}



									elseif($i == 5)



									{



										$color_class='smgt_class_color5';



									}



									elseif($i == 6)



									{



										$color_class='smgt_class_color6';



									}



									elseif($i == 7)



									{



										$color_class='smgt_class_color7';



									}



									elseif($i == 8)



									{



										$color_class='smgt_class_color8';



									}



									elseif($i == 9)



									{



										$color_class='smgt_class_color9';



									}



									?>



									<tr>



										<td class="cursor_pointer user_image width_50px profile_image_prescription padding_left_0">



											<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">	



												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center" style="padding-top:12px;">



											</p>



										</td>



										<td class="name">



											<?php



											if($member_data->roles[0] == "student")



											{



												echo $member_data->display_name; 



											}else{



													echo $member_data->display_name; 



											}



												?>


											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Teacher Name','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php echo mj_smgt_get_class_name($class->class_id); ?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?>



											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date','school-mgt');?>" ></i>



										</td>



										<td class="name">



											<?php 



												$day=date("D", strtotime($retrieved_data->attendence_date));



												if($day == 'Mon')



												{
		
		
		
													esc_html_e('Monday','school-mgt');
		
		
		
												}  
		
		
		
												elseif($day == 'Sun')
		
		
		
												{
		
		
		
													esc_html_e('Sunday','school-mgt');
		
		
		
												} 
		
		
		
												elseif($day == 'Tue')
		
		
		
												{
		
		
		
													esc_html_e('Tuesday','school-mgt');
		
		
		
												}
		
		
		
												elseif($day == 'Wed')
		
		
		
												{
		
		
		
													esc_html_e('Wednesday','school-mgt');
		
		
		
												}
		
		
		
												elseif($day == 'Thu')
		
		
		
												{
		
		
		
													esc_html_e('Thursday','school-mgt');
		
		
		
												}
		
		
		
												elseif($day == 'Fri')
		
		
		
												{
		
		
		
													esc_html_e('Friday','school-mgt');
		
		
		
												}
		
		
		
												elseif($day == 'Sat')
		
		
		
												{
		
		
		
													esc_html_e('Saturday','school-mgt');
		
		
		
												}



											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Day','school-mgt');?>" ></i>



										</td>

										<td class="name">
											<?php echo mj_smgt_get_display_name($retrieved_data->attend_by); ?>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
										</td>

										<td class="name">
											<?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
											<span style="color:<?php echo $status_color;?>;">
												<?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
											</span>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
										</td>
										<td class="name">
											<?php
											if(!empty($retrieved_data->comment))
											{
												$comment =$retrieved_data->comment;
												$grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
												echo $grade_comment;
											}
											else
											{
												echo "N/A";
											}
											?>
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
											if(!empty($retrieved_data->comment))
											{ 
												echo $retrieved_data->comment;
											} 
											else
											{  _e('Comment','school-mgt');
											}
											?>
											"></i>
										</td>


									</tr>



									<?php 



								$i++;



							}



						?>



					</tbody>



				</table>



			</div><!-- TABLE RESPONSIVE DIV END -->



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



		?>



		<?php



	}







	







	if($active_tab=='teacher_attedance_list'){



		$teacher_id = get_current_user_id();



		$attendance_list = mj_smgt_monthly_attendence($teacher_id);



		if(!empty($attendance_list))



		{



			?>



			<script type="text/javascript">



				jQuery(document).ready(function($) {



					"use strict";



					jQuery('#attendance_list_detailpage').DataTable({

						//stateSave: true,

						"responsive": true,



						"order": [[ 1, "desc" ]],



						"aoColumns":[



									{"bSortable": false},



									{"bSortable": true},



									{"bSortable": false},



									{"bSortable": false},



									{"bSortable": false},



									{"bSortable": false}],



						dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',



						language:<?php echo mj_smgt_datatable_multi_language();?>



						});



						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");



					$('.dataTables_filter').addClass('search_btn_view_page');



				} );



			</script>







			<div class="table-div"><!-- PANEL BODY DIV START -->



				<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->



					<table id="attendance_list_detailpage" class="display" cellspacing="0" width="100%">



						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">



							<tr>



								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>



								<th><?php esc_attr_e('No.','school-mgt');?></th>  



								<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>



								<th><?php esc_attr_e('Attendance Date','school-mgt');?></th>  



								<th><?php esc_attr_e('Day','school-mgt');?> </th>  



								<th><?php esc_attr_e('Status','school-mgt');?> </th>  



							</tr>



						</thead>



						<tbody>



							<?php



							$i=0;



							$srno = 1;



							if(!empty($attendance_list))



							{



								foreach ($attendance_list as $retrieved_data)



								{



									if($i == 10)



									{



										$i=0;



									}



									if($i == 0)



									{



										$color_class='smgt_class_color0';



									}



									elseif($i == 1)



									{



										$color_class='smgt_class_color1';







									}



									elseif($i == 2)



									{



										$color_class='smgt_class_color2';







									}



									elseif($i == 3)



									{



										$color_class='smgt_class_color3';







									}



									elseif($i == 4)



									{



										$color_class='smgt_class_color4';







									}



									elseif($i == 5)



									{



										$color_class='smgt_class_color5';







									}



									elseif($i == 6)



									{



										$color_class='smgt_class_color6';







									}



									elseif($i == 7)



									{



										$color_class='smgt_class_color7';







									}



									elseif($i == 8)



									{



										$color_class='smgt_class_color8';







									}



									elseif($i == 9)



									{



										$color_class='smgt_class_color9';







									}



									?>



									<tr>



										<td class="user_image width_50px profile_image_prescription">



											<p class="remainder_title_pr Bold prescription_tag <?php echo $color_class; ?>">



												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image attendace_module_image center">



											</p>



										</td>







										<td><?php echo $srno;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('No.','school-mgt');?>"></i></td>







										<td class=""><?php echo mj_smgt_get_user_name_byid($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>







										<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance Date','school-mgt');?>"></i></td>







										<td class="">



											<?php



												$curremt_date = $retrieved_data->attendence_date;



												$day=date("D", strtotime($curremt_date));



												echo esc_attr__("$day","school-mgt");



											?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i>



										</td>







										<td><?php echo esc_html_e($retrieved_data->status,'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>



									</tr>



									<?php



									$i++;



									$srno++;



								}



							}



							?>



						</tbody>



					</table>







				</div><!-- TABLE RESPONSIVE DIV END -->



			</div>



			<?php



		}



		else



		{ ?>



			



				<div class="no_data_list_div">



					<a href="<?php echo admin_url().'admin.php?page=smgt_attendence&tab=teacher_attendence';?>">



						<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >



					</a>



					<div class="col-md-12 dashboard_btn margin_top_20px">



						<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>



					</div>



				</div>



			<?php



		}



	}



	?>



</div> <!-------------- PENAL BODY ----------------->