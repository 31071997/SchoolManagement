<?php 

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$role_name=mj_smgt_get_user_role(get_current_user_id());

$active_tab = isset($_GET['tab'])?$_GET['tab']:'examlist';

$obj_exam=new smgt_exam;

require_once SMS_PLUGIN_DIR. '/school-management-class.php';

//--------------- ACCESS WISE ROLE -----------//

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

$tablename="exam";

//----------------- DELETE EXAM ----------------//

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')

{

	$result=mj_smgt_delete_exam($tablename,$_REQUEST['exam_id']);

	if($result){

		wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=3'); 	

	}

}

//----------------- DELETE MULTIPLE EXAM ----------------//

if(isset($_REQUEST['delete_selected']))

{		

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $id)

		{

			$result=mj_smgt_delete_exam($tablename,$id);

			wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=3'); 	

		}

	}

	if($result)

	{ 

		wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=3'); 	

	}

}

//-----------SAVE EXAM -------------------------//

if(isset($_POST['save_exam']))

{	

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'save_exam_admin_nonce' ) )

	{

		$created_date = date("Y-m-d H:i:s");

		$examdata=array('exam_name'=>mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])),

			'class_id'=>$_POST['class_id'],

			'section_id'=>$_POST['class_section'],

			'exam_term'=>$_POST['exam_term'],

			'passing_mark'=>$_POST['passing_mark'],

			'total_mark'=>$_POST['total_mark'],

			'exam_start_date'=>date('Y-m-d', strtotime($_POST['exam_start_date'])),

			'exam_end_date'=>date('Y-m-d', strtotime($_POST['exam_end_date'])),

			'exam_comment'=>mj_smgt_address_description_validation(stripslashes($_POST['exam_comment'])),					

			'exam_creater_id'=>get_current_user_id(),

			'created_date'=>$created_date						

		);		

		if ($_POST['passing_mark'] >= $_POST['total_mark'])

		{

			wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=6');

		}

		else

		{

			//table name without prefix

			$tablename="exam";

			if($_REQUEST['action']=='edit')

			{

				if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)

				{		

					if($_FILES['exam_syllabus']['size'] > 0)

						$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		

				}

				else

				{

					if(isset($_REQUEST['old_hidden_exam_syllabus']))

					$upload_docs1=$_REQUEST['old_hidden_exam_syllabus'];

				}

				

				$document_data=array();

				if(!empty($upload_docs1))

				{

					$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);

				}

				else

				{

					$document_data[]='';

				}

					

				$grade_id=array('exam_id'=>$_REQUEST['exam_id']);

				$modified_date_date = date("Y-m-d H:i:s");

				$examdata['modified_date']=$modified_date_date;

				$examdata['exam_syllabus']=json_encode($document_data);

				$result=mj_smgt_update_record($tablename,$examdata,$grade_id);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=2'); 	

				}

			}

			else

			{

				if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)

				{		

					if($_FILES['exam_syllabus']['size'] > 0)

						$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		

				}

				else

				{

					$upload_docs1='';

				}

				

				$document_data=array();

				if(!empty($upload_docs1))

				{

					$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);

				}

				else

				{

					$document_data[]='';

				}

				$examdata['exam_syllabus']=json_encode($document_data);

							

				$result=mj_smgt_insert_record($tablename,$examdata);

				if($result)

				{ 
					if(isset($_POST['smgt_enable_exam_mail']) == '1')
					{

						if(empty($_POST['class_section']))
						{
							$class_id =$_POST['class_id'];

							$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));

						}
						else
						{

							$studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_POST['class_section'],

							'meta_query'=> array(array('key' => 'class_name','value' => $_POST['class_id'],'compare' => '=')),'role'=>'student'));
						
						}
						if(!empty($studentdata))
						{
							foreach($studentdata as $userdata)
							{
								$student_id = $userdata->ID;

								$student_name = $userdata->display_name;

								if(isset($_POST['smgt_enable_exam_mail']) && ($_POST['smgt_enable_exam_mail'] == '1'))
								{
									$student_email = $userdata->user_email;

									$add_exam_mailcontent = get_option('add_exam_mailcontent');

									$add_exam_mail_title = get_option('add_exam_mail_title');

									$parent = 	get_user_meta($student_id, 'parent_id', true);

									if($_POST['exam_start_date'] == $_POST['exam_end_date'])
									{
										$start_end_date = mj_smgt_getdate_in_input_box($_POST['exam_start_date']);
									}
									else{
										$start_end_date =  mj_smgt_getdate_in_input_box($_POST['exam_start_date']).' '.esc_attr__('TO','school-mgt').' '.mj_smgt_getdate_in_input_box($_POST['exam_end_date']);
									}
									// Add Exam Mail Send To Parent
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$user_info	 	=    get_userdata($p);

											$email_to 	=	 $user_info->user_email;	

											$searchArr = array();

											$parerntdata = get_user_by('email',$email_to);							

											$searchArr['{{user_name}}']	=	$parerntdata->display_name;

											$searchArr['{{exam_name}}']   =  mj_smgt_popup_category_validation(stripslashes($_POST['exam_name']));

											$searchArr['{{exam_start_end_date}}']   =  $start_end_date;

											$searchArr['{{exam_comment}}']   = mj_smgt_address_description_validation(stripslashes($_POST['exam_comment']));

											$searchArr['{{school_name}}']	=	get_option('smgt_school_name');

											$message = mj_smgt_string_replacement($searchArr,$add_exam_mailcontent);
											
											if(!empty($document_data[0]))
											{
												$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
											}
											else
											{
												$attechment = "";
											}
												
											$mail = mj_smgt_send_mail_for_homework($email_to,$add_exam_mail_title,$message,$attechment);

										}
									}
									// Add Exam Mail Send To Student 
									$string = array();

									$string['{{user_name}}']	=	$student_name;

									$string['{{exam_name}}']   =  mj_smgt_popup_category_validation(stripslashes($_POST['exam_name']));

									$string['{{exam_start_end_date}}']   =  $start_end_date;

									$string['{{exam_comment}}']   = mj_smgt_address_description_validation(stripslashes($_POST['exam_comment']));

									$string['{{school_name}}']	=	get_option('smgt_school_name');

									$message = mj_smgt_string_replacement($string,$add_exam_mailcontent);
									
									if(!empty($document_data[0]))
									{
										$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$document_data[0]['value'];
									}
									else
									{
										$attechment = "";
									}
												
									$mail = mj_smgt_send_mail_for_homework($student_email,$add_exam_mail_title,$message,$attechment);
								}
								
								if(isset($_POST['smgt_enable_exam_sms_student']) && ($_POST['smgt_enable_exam_sms_student'] == '1'))
								{
									$message_content = 'This is a reminder that your upcoming exam '.mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])).' is scheduled for '.$start_end_date.' At '.get_option('smgt_school_name');
									$type = "Add Exam";
									MJ_smgt_send_sms_notification($student_id,$type,$message_content);
								}

								if(isset($_POST['smgt_enable_exam_sms_parent']) && ($_POST['smgt_enable_exam_sms_parent'] == '1'))
								{
									$parent = 	get_user_meta($student_id, 'parent_id', true);
									if(!empty($parent))
									{
										foreach($parent as $p)
										{
											$message_content = 'We would like to inform you that your child '.$student_name.' will have an important exam '.mj_smgt_popup_category_validation(stripslashes($_POST['exam_name'])).' on '.$start_end_date.' At '.get_option('smgt_school_name');
											$type = "Add Exam";
											MJ_smgt_send_sms_notification($p,$type,$message_content);
										}
									}
									
								}

							}
						}
					}
					wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=examlist&message=1'); 	

				}				

			}

		}

	}		

}

//------------- SAVE EXAM TIME TABLE -----------------//

if(isset($_POST['save_exam_table']))

{	

	$obj_exam=new smgt_exam;

	$class_id=	$_POST['class_id'];

	$section_id=$_POST['section_id'];

	$exam_id=$_POST['exam_id'];

	if(isset($_POST['section_id']) && $_POST['section_id'] !=0)

	{

		$subject_data=$obj_exam->mj_smgt_get_subject_by_section_id($class_id,$section_id);

	}

	else

	{ 

		$subject_data=$obj_exam->mj_smgt_get_subject_by_class_id($class_id);

	}

	

	if(!empty($subject_data))

	{

		foreach($subject_data as $subject)

		{	

			if(isset($_POST['subject_name_'.$subject->subid]))

			{

				$save_data = $obj_exam->mj_smgt_insert_sub_wise_time_table($class_id,$exam_id,$subject->subid,$_POST['exam_date_'.$subject->subid],$_POST['start_time_'.$subject->subid],$_POST['end_time_'.$subject->subid]);

			}

		}

		if($save_data)

		{ 

			wp_redirect ( home_url() . '?dashboard=user&page=exam&tab=exam_time_table&message=5'); 	

		}

	}

	

} 

?>

<script type="text/javascript">

	jQuery(document).ready(function($)

	{

		"use strict";

		$('#exam_form_front').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

		$('#exam_time_table').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	

		$('#examt_list').DataTable({

			"initComplete": function(settings, json) {

				$(".print-button").css({"margin-top": "-5%"});

			},
			
			"dom": 'lifrtp',
			<?php
				if($role_name == "supportstaff")
				{
				?>
					"order": [[ 6, "desc" ]],
				<?php
				}
				else{
					?>
					"order": [[ 5, "desc" ]],
					<?php
				}
				?>
			"aoColumns":[	                  

					<?php

					if($role_name == "supportstaff")

					{

						?>

						{"bSortable": false},

						<?php

					}

					?>

	                  {"bSortable": false},

					  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},	                  

	                  {"bSortable": false}

				],

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

			if ($('.select-checkbox:checked').length == 0 )

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

		$('#exam_timelist').DataTable({

			bPaginate: false,

			bFilter: false, 

			bInfo: false,

			language:<?php echo mj_smgt_datatable_multi_language();?>

		});

		$('.width_200').DataTable({

			//responsive: true,

			bPaginate: false,

			bFilter: false, 

			bInfo: false,

		});

		$("#exam_start_date").datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true,
			minDate:0

		});

		$("#exam_end_date").datepicker({

			minDate:0,

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeYear:true,
			changeMonth: true
		});

		

	});

</script>

<!-- POP up code -->

<div class="popup-bg">

	<div class="overlay-content">

		<div class="modal-content">

			<div class="view_popup"></div>     

		</div>

	</div>    

</div>

<!-- End POP-UP Code -->

<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PANEL BODY ------------>

	<?php

	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

	switch($message)

	{

		case '1':

			$message_string = esc_attr__('Exam Added Successfully.','school-mgt');

			break;

		case '2':

			$message_string = esc_attr__('Exam Updated Successfully.','school-mgt');

			break;	

		case '3':

			$message_string = esc_attr__('Exam Deleted Successfully.','school-mgt');

			break;

		case '4':

			$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');

			break;

		case '5':

			$message_string = esc_attr__('Exam Time Table Saved Successfully .','school-mgt');

			break;

		case '6':

			$message_string = esc_attr__('Enter Total Marks Greater than Passing Marks.','school-mgt');

			break;

	}

	

	if($message)

	{ 

		?>

		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

			</button>

			<?php echo $message_string;?>

		</div>

		<?php 

	} ?>

	<!-------------- TABING START --------------->

	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

		<li class="<?php if($active_tab=='examlist'){?>active<?php }?>">			

			<a href="?dashboard=user&page=exam&tab=examlist" class="padding_left_0 tab <?php echo $active_tab == 'examlist' ? 'active' : ''; ?>">

			<?php esc_html_e('Exam List', 'school-mgt'); ?></a> 

		</li>

		<?php

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

		{

			?>

			<li class="<?php if($active_tab=='addexam'){?>active<?php }?>">			

				<a href="?dashboard=user&page=exam&tab=addexam" class="padding_left_0 tab <?php echo $active_tab == 'addexam' ? 'active' : ''; ?>">

				<?php esc_html_e('Edit Exam', 'school-mgt'); ?></a> 

			</li>

			<?php

		}

		else

		{

			if($active_tab=='addexam')

			{

				?>

				<li class="<?php if($active_tab=='addexam'){?>active<?php }?>">			

					<a href="?dashboard=user&page=exam&tab=addexam" class="padding_left_0 tab <?php echo $active_tab == 'addexam' ? 'active' : ''; ?>">

					<?php esc_html_e('Add Exam', 'school-mgt'); ?></a> 

				</li>

				<?php

			}

		}

		if($user_access['add']=='1')

		{

			?>

			<li class="<?php if($active_tab=='exam_time_table'){?>active<?php }?>">

				<a href="?dashboard=user&page=exam&tab=exam_time_table" class="padding_left_0 tab <?php echo $active_tab == 'exam_time_table' ? 'active' : ''; ?>">

				<?php esc_html_e('Exam Time Table', 'school-mgt'); ?></a> 

			</li>

			<?php 

		}

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')

		{ 

			?>  

			<li class="<?php if($active_tab=='view_exam_time_table'){?>active<?php }?>">

				<a href="?dashboard=user&page=exam&tab=view_exam_time_table&action=view&exam_id=<?php echo $_REQUEST['exam_id']; ?>" class="padding_left_0 tab <?php echo $active_tab == 'view_exam_time_table' ? 'active' : ''; ?>">

				<?php esc_html_e('View Exam Time Table', 'school-mgt'); ?></a> 

			</li>  

			<?php

		}

		?>

	</ul> 

	<!-------------- TABING END ----------------->

	<?php

	//--------------- EXAM LIST TAB START ---------------//

	if($active_tab == 'examlist')

	{

		$user_id=get_current_user_id();

		//------- EXAM DATA FOR STUDENT ---------//

		if($school_obj->role == 'student')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{ 

				$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);			

				$section_id 	= 	get_user_meta(get_current_user_id(),'class_section',true);	

				if(isset($class_id) && $section_id == '')

				{

					$retrieve_class	= 	mj_smgt_get_all_exam_by_class_id($class_id);

				}

				else

				{

					$retrieve_class	= mj_smgt_get_all_exam_by_class_id_and_section_id_array($class_id,$section_id);

				}

			}

			else

			{

				$retrieve_class = mj_smgt_get_all_data($tablename);			

			}

		}

		//------- EXAM DATA FOR TEACHER ---------//

		elseif($school_obj->role == 'teacher')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{ 

				$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	

				

				$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_created_by($class_id,$user_id);

			}

			else

			{

				$retrieve_class = mj_smgt_get_all_data($tablename);			

			}

		}

		//------- EXAM DATA FOR PARENT ---------//

		elseif($school_obj->role == 'parent')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{ 

				$user_meta =get_user_meta($user_id, 'child', true);
				if(!empty($user_meta))
				{
					foreach($user_meta as $student_id)

					{
		
						$result[] = mj_smgt_get_exam_data_for_parent($student_id);
						
					}
					$mergedArray = array_merge(...$result);

					$retrieve_class = array_unique($mergedArray, SORT_REGULAR);
				}
			}

			else

			{
				
				$retrieve_class = mj_smgt_get_all_data($tablename);			

			}

		}

		//------- EXAM DATA FOR SUPPORT STAFF ---------//

		else

		{ 

	       $own_data=$user_access['own_data'];

			if($own_data == '1')

			{			

				$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_created_by($user_id);

			}

			else

			{

				$retrieve_class = mj_smgt_get_all_data($tablename);	

			}

		} 

		if(!empty($retrieve_class))

		{

			?>

			<div class="panel-body"><!---------- PENAL BODY -------------->

				<div class="table-responsive"><!--------------- TABLE RESPONSIVE ------------>

					<!--------------- EXAM LIST FORM --------------->

					<form name="wcwm_report" action="" method="post">

						<table id="examt_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">

							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

								<tr>

									<?php

									if($role_name == "supportstaff")

									{

										?>

											<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>

										<?php

									}

									?>

									<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

									<th><?php esc_attr_e('Exam Name','school-mgt');?></th>

									<th><?php esc_attr_e('Class Name','school-mgt');?></th>

									<th><?php esc_attr_e('Exam Term','school-mgt');?></th>

									<th><?php esc_attr_e('Exam Start Date','school-mgt');?></th>

									<th><?php esc_attr_e('Exam End Date','school-mgt');?></th>

									<th><?php esc_attr_e('Exam Comment','school-mgt');?></th>

									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

								</tr>

							</thead>

							<tbody>

								<?php 

								$i=0;

								foreach ($retrieve_class as $retrieved_data)

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

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->exam_id;?>"></td>

											<?php

										}

										?>

										

										<td class="user_image width_50px profile_image_prescription padding_left_0">

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" >

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Exam_hall.png"?>" alt="" class="massage_image center">

												</p>

											</a>

										</td>

										<td>

											<a href="#" class="color_black view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" >

												<?php echo $retrieved_data->exam_name;?>

											</a> 

											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Name','school-mgt');?>"></i>

										</td>

										<td><?php echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

										<td><?php 

										if(!empty(get_the_title($retrieved_data->exam_term)))

										{

											echo get_the_title($retrieved_data->exam_term);

										}

										else

										{

											echo  "N/A";

										}

										?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Exam Term','school-mgt');?>"></i></td>

										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Date','school-mgt');?>"></i></td>

										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('End Date','school-mgt');?>"></i></td>

										<?php

										$comment =$retrieved_data->exam_comment;

										$exam_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;

										?>

										<td><?php if($retrieved_data->exam_comment){ echo stripslashes($exam_comment); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){ echo $comment;}else{ esc_html_e('Exam Comment','school-mgt');}?>"></i></td>              

										<td class="action">  

											<div class="smgt-user-dropdown">

												<ul class="" style="margin-bottom: 0px !important;">

													<?php

														if(!empty($retrieved_data->exam_syllabus))

														{

															$doc_data=json_decode($retrieved_data->exam_syllabus);

														}

													?>

													<li class="">

														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

														</a>

														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

															<li class="float_left_width_100 ">

																<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->exam_id;?>" type="Exam_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Exam Detail','school-mgt');?></a>

															</li>

															<li class="float_left_width_100 ">

																<a href="?dashboard=user&page=exam&tab=view_exam_time_table&action=view&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/timetable-icon.png"?>" alt="" style="height:15px;" class="">&nbsp;&nbsp;<?php esc_attr_e('Time Table Detail','school-mgt');?></a>

															</li>

															<?php

															if(!empty($doc_data[0]->value))

															{

																?>

																<li class="float_left_width_100">

																	<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="status_read float_left_width_100" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-eye"></i><?php esc_html_e('View Syllabus', 'school-mgt');?></a>

																</li>

																<?php

															}

															if($user_access['edit']=='1')

															{

																?>

																<li class="float_left_width_100 border_bottom_menu">

																	<a href="?dashboard=user&page=exam&tab=addexam&action=edit&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																</li>

																<?php 

															} 

															if($user_access['delete']=='1')

															{

																?>

																<li class="float_left_width_100 ">

																	<a href="?dashboard=user&page=exam&tab=examlist&action=delete&exam_id=<?php echo $retrieved_data->exam_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

																	<i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

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

									$i++;

								}

								?>

							</tbody>

						</table>

						<?php

						if($role_name == "supportstaff")

						{

							?>

							<div class="print-button pull-left">

								<button class="btn-sms-color button_reload">

									<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">

									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

								</button>

								<?php 

								if($user_access['delete']=='1')

								{ 

									?>

									<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<?php

								}

								?>

							</div>

							<?php

						}

						?>

					</form><!--------------- EXAM LIST FORM --------------->

				</div><!--------------- TABLE RESPONSIVE ------------>

			</div><!---------- PENAL BODY -------------->

			<?php

		}

		else

		{

			if($user_access['add']=='1')

			{

				?>

				<div class="no_data_list_div no_data_img_mt_30px"> 

					<a href="<?php echo home_url().'?dashboard=user&page=exam&tab=addexam';?>">

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

	//--------------- ADD EXAM TAB START ---------------//

	if($active_tab == 'addexam')

	{ 

		?>

		<!--Group POP up code -->

		<div class="popup-bg">

			<div class="overlay-content admission_popup">

				<div class="modal-content">

					<div class="category_list">

					</div>     

				</div>

			</div>     

		</div>

		<script>

			jQuery("body").on("change", ".exam_syllebus", function ()

			{ 

				var val = $(this).val().toLowerCase();

			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");

			 if(!(regex.test(val)))

			{

				$(this).val('');

				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");

			} 

			});

		</script>

		<?php

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

		{

			$edit=1;

			$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);

		}

		?>

		<div class="panel-body"><!------------ PENAL BODY ------------->

			<!------------ EXAM ADD FORM ------------->	

			<form name="exam_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="exam_form_front">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Exam Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="exam_name" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $exam_data->exam_name;}?>" name="exam_name">

									<label for="userinput1" class=""><?php esc_html_e('Exam Name','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Name','school-mgt');?><span class="required">*</span></label>

							<select name="class_id" class="line_height_30px form-control validate[required] width_100" id="class_list">

								<option value=""><?php echo esc_attr_e( 'Select Class', 'school-mgt' ) ;?></option>

								<?php $classval='';

								if($edit){  

									$classval=$exam_data->class_id; 

									foreach(mj_smgt_get_allclass() as $class)

									{ ?>

									<option value="<?php echo $class['class_id'];?>" <?php selected($class['class_id'],$classval);  ?>>

									<?php echo mj_smgt_get_class_name($class['class_id']);?></option> 

								<?php }

								}else

								{

									foreach(mj_smgt_get_allclass() as $classdata)

									{ ?>

									<option value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$classval);  ?>><?php echo $classdata['class_name'];?></option> 

								<?php }

								}

								?>

							</select>                              

						</div>

						<div class="col-md-6 input">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Section Name','school-mgt');?></label>

							<?php if($edit){ $sectionval=$exam_data->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

							<select name="class_section" class="line_height_30px form-control width_100" id="class_section">

								<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

								<?php

								if($edit){

									foreach(mj_smgt_get_class_sections($exam_data->class_id) as $sectiondata)

									{  ?>

									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

								<?php }

								}?>

							</select>                             

						</div>

						<div class="col-md-4 input width_75">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Exam Term','school-mgt');?><span class="required">*</span></label>

							<?php if($edit){ $sectionval1=$exam_data->exam_term; }elseif(isset($_POST['exam_term'])){$sectionval1=$_POST['exam_term'];}else{$sectionval1='';}?>

							<select class="line_height_30px form-control validate[required] term_category width_100" name="exam_term">

								<option value=""><?php esc_html_e('Select Term','school-mgt');?></option>

								<?php 

								$activity_category=mj_smgt_get_all_category('term_category');

								if(!empty($activity_category))

								{

									foreach ($activity_category as $retrive_data)

									{ 		 	

									?>

										<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$sectionval1);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>

									<?php }

								} 

								?> 

							</select>	                           

						</div>

						<div class="col-md-2 col-sm-1 input res_width_25">

							<!-- <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" alt="" model="term_category" class="rtl_margin_top_15px sibling_add_remove add_cirtificate float_right" id="addremove_cat"> -->

							<input type="button" id="addremove_cat" value="<?php esc_attr_e('ADD','school-mgt');?>" model="term_category" class="btn btn-success save_btn margin_top_0px_rtl" />	

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="passing_mark" class="form-control text-input onlyletter_number_space_validation validate[required]" type="number" value="<?php if($edit){ echo $exam_data->passing_mark;}?>" name="passing_mark">

									<label for="userinput1" class=""><?php esc_html_e('Passing Marks','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input error_msg_left_margin">

								<div class="col-md-12 form-control">

									<input id="total_mark" class="form-control validate[required] onlyletter_number_space_validation text-input" type="number" value="<?php if($edit){ echo $exam_data->total_mark;}?>" name="total_mark">

									<label for="userinput1" class=""><?php esc_html_e('Total Marks','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="exam_start_date" class="form-control date_picker validate[required] text-input" type="text" name="exam_start_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($exam_data->exam_start_date); }else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" readonly>

									<label for="userinput1" class="date_label"><?php esc_html_e('Exam Start Date','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="exam_end_date" class="form-control date_picker validate[required] text-input" type="text" name="exam_end_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($exam_data->exam_end_date); }else{echo mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" readonly>

									<label for="userinput1" class="date_label"><?php esc_html_e('Exam End Date','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<?php wp_nonce_field( 'save_exam_admin_nonce' ); ?>

						<div class="col-md-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="exam_comment" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150" id="exam_comment"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>

										<span class="txt-title-label"></span>

										<label class="text-area address active"><?php esc_attr_e('Exam Comment','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

						<?php

						if($edit)

						{ 

							$doc_data=json_decode($exam_data->exam_syllabus);

							?>

							<div class="col-md-6">	

								<div class="form-group input">

									<div class="col-md-12 form-control res_rtl_height_50px">	

										<label for="" class="custom-control-label label_position_rtl custom-top-label ml-2 margin_left_30px"><?php _e('Exam Syllabus','school-mgt');?></label>

										<div class="col-sm-12">

											<input type="file" name="exam_syllabus" class="form-control exam_syllebus file_validation input-file" style="border:0px solid;"/>						

											<input type="hidden" name="old_hidden_exam_syllabus" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['exam_syllabus'])) echo esc_attr($_POST['exam_syllabus']);?>">					

										</div>

										<?php 

										if(!empty($doc_data[0]->value))

										{ 

											?>

											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

												<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" record_id="<?php echo $exam_data->exam_id;?>">

												<i class="fa fa-download"></i><?php esc_attr_e('Download','school-mgt');?></a>

											</div>

											<?php	

										}

										?>

									</div>

								</div>

							</div>

							<?php

						}

						else

						{

							?>

							<div class="col-md-6">	

								<div class="form-group input">

									<div class="col-md-12 form-control res_rtl_height_50px">	

										<label for="" class="custom-control-label label_position_rtl custom-top-label ml-2 margin_left_30px"><?php _e('Exam Syllabus','school-mgt');?></label>

										<div class="col-sm-12">

											<input type="file" name="exam_syllabus" class="col-md-2 col-sm-2 col-xs-12 exam_syllebus form-control file_validation input-file " style="border:0px solid;">	

										</div>

									</div>

								</div>

							</div>

							<?php

						}

						if(!$edit)
						{ 
						?>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 margin_15px_rtl rtl_margin_bottom_0px">
							<div class="form-group">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl" for="smgt_enable_exam_mail"><?php esc_attr_e('Send Mail To Parents & Students','school-mgt');?></label>
											<input type="checkbox" class="check_box_input_margin" name="smgt_enable_exam_mail"  value="1" <?php echo checked(get_option('smgt_enable_exam_mail'),'yes');?>/>&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-3 margin_15px_rtl rtl_margin_bottom_0px">
							<div class="form-group">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl" for="smgt_enable_exam_sms_student"><?php esc_attr_e('Send SMS To Students','school-mgt');?></label>
											<input type="checkbox" class="check_box_input_margin" name="smgt_enable_exam_sms_student"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-3 margin_15px_rtl rtl_margin_bottom_0px">
							<div class="form-group">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl" for="smgt_enable_exam_sms_parent"><?php esc_attr_e('Send SMS To Parents','school-mgt');?></label>
											<input type="checkbox" class="check_box_input_margin" name="smgt_enable_exam_sms_parent"  value="1" />&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<?php
						}
						?>

					</div>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">        	

							<input type="submit" id="save_exam" value="<?php if($edit){ esc_attr_e('Save Exam','school-mgt'); }else{ esc_attr_e('Add Exam','school-mgt');}?>" name="save_exam" class="btn btn-success save_btn " />

						</div>    

					</div>

				</div> 

				<div class="offset-sm-2 col-sm-8">        	

					

				</div>        

			</form><!------------ EXAM ADD FORM ------------->	

		</div> <!------------ PENAL BODY ------------->

		<?php

	} 

	//--------------- VIEW EXAM TIME TABLE TAB ---------------//

    if($active_tab == 'view_exam_time_table')

	{

		if($_REQUEST['action']=='view')

		{

			$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);

			$start_date=$exam_data->exam_start_date;

			$end_date=$exam_data->exam_end_date;

			$obj_exam=new smgt_exam;

			$exam_time_table=$obj_exam->mj_smgt_get_exam_time_table_by_exam($_REQUEST['exam_id']);

		}

		?>

		<div class="panel-body margin_top_20px padding_top_25px_res"> <!--------- penal body ----------->

			<div class="form-group">

				<div class="col-md-12 rtl_padding_0px">

					<div class="exam_table_res view_exam_timetable_div">

						<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="width_100" >

							<thead>

								<tr>

									<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Exam','school-mgt');?></th>

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Class','school-mgt');?></th>							

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Section','school-mgt');?></th>							

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Term','school-mgt');?></th>							

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Start Date','school-mgt');?></th>							

									<th class="exam_hall_receipt_table_heading rtl_border_right_1px" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('End Date','school-mgt');?></th>							

								</tr>

							</thead>

							<tfoot></tfoot>

							<tbody>							

								<tr>

									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $exam_data->exam_name;?></td>							

									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>

									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>

									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;">
										<?php 	if(!empty(get_the_title($exam_data->exam_term)))
												{
													echo get_the_title($exam_data->exam_term);
												}
												else
												{
													echo  "N/A";
												} ?></td>

									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>

									<td class="exam_hall_receipt_table_value rtl_border_right_1px" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>

								</tr>

							</tbody>

						</table>

					</div>

				</div>			

			</div>

			<?php

			if(!empty($exam_time_table))

			{

				?>

				<div class="col-md-12" style="margin-top:20px;">

					<div class="exam_table_res view_exam_timetable_div">

						<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="width_100" >

							<thead>

								<tr>    

									<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Subject Code','school-mgt');?></th>

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Subject Name','school-mgt');?></th>

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam Date','school-mgt');?></th>

									<th class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>

									<th class="exam_hall_receipt_table_heading rtl_border_right_1px" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;"><?php esc_attr_e('Exam End Time','school-mgt');?></th>

								</tr>

							</thead>

							<tbody>

								<?php

								if(!empty($exam_time_table))

								{

									foreach($exam_time_table  as $retrieved_data)

									{

									?>

										<tr style="border: 1px solid #D9E1ED;">

											<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_single_subject_code($retrieved_data->subject_id); ?> </td>

											<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject_id);  ?> </td>

											<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_date); ?> </td>

											<?php

											$start_time_data = explode(":", $retrieved_data->start_time);

											$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

											$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

											$start_am_pm=$start_time_data[2];

											$start_time=$start_hour.':'.$start_min.' '.$start_am_pm;

											

											$end_time_data = explode(":", $retrieved_data->end_time);

											$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

											$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

											$end_am_pm=$end_time_data[2];

											$end_time=$end_hour.':'.$end_min.' '.$end_am_pm;

											?>

											<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $start_time;?> </td>

											<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $end_time; ?> </td>

										</tr>

									<?php 

									}

								}

								?>

							</tbody>

						</table>

					</div>

				</div>

				<?php

			}

			else

			{

				?>

				<div style="margin-top:20px !important;" id="message" class="rtl_message_display_inline_block alert_msg alert alert-success alert-dismissible " role="alert">

					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

					</button>

					<?php echo esc_html_e('No Any Time Table', 'school-mgt');?>

				</div>

				<?php

			}

			?>

		</div><!--------- penal body ----------->

		<?php

	}

	//--------------- EXAM TIME TABLE TAB ---------------//

	if($active_tab == 'exam_time_table')

	{

		?>

		<script>

			//-------- timepicker ---------//

			jQuery(document).ready(function($){

				$( "#save_exam_time" ).on("click",function(e) {
					
					var subject_data = $("#subject_data").val();

					var suj = JSON.parse(subject_data);

					var productIds = [];

					jQuery.each( suj, function( i, val ) {

					

						var exdt = $("#exam_date_"+val.subid).val();

						

						var strh = $(".start_time_"+val.subid).val();

						var endh = $(".end_time_"+val.subid).val();

						

						

						var exsdtfull = exdt+strh;

						var exedtfull = exdt+endh;

					

						if ($.inArray(exsdtfull, productIds) == -1) {

							productIds.push(exsdtfull);

						}

					

						if ($.inArray(exedtfull, productIds) == -1) {

							productIds.push(exedtfull);

						}

						

						var strfull = strh;

						var endfull = endh;

						var start_time_new = Converttimeformat_new(strfull);

						var end_time_new = Converttimeformat_new(endfull);

						
						
						if(strfull != "")

						{

							if (start_time_new >= end_time_new) 

							{

								alert('<?php echo esc_js(__("Subject", "school-mgt"));?>'+' '+val.sub_name+' '+'<?php echo esc_js(__("End time must be greater than start time.", "school-mgt"));?>');


								e.preventDefault(e);

							}

						}

						else

						{

							$('#exam_form2').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

						}

					});  

				});

				mdtimepicker('#timepicker', {

				events: {

						timeChanged: function (data) {

							

						}

					},

				theme: 'purple',

				readOnly: false,

				});

			})
			function Converttimeformat_new(strfull) {

				var hrs = Number(strfull.match(/^(\d+)/)[1]);

				var mnts = Number(strfull.match(/:(\d+)/)[1]);

				var format = strfull.match(/\s(.*)$/)[1];

				if (format == "PM" && hrs < 12) hrs = hrs + 12;

				if (format == "AM" && hrs == 12) hrs = hrs - 12;

				var hours = hrs.toString();

				var minutes = mnts.toString();

				if (hrs < 10) hours = "0" + hours;

				if (mnts < 10) minutes = "0" + minutes;

				return hours + ":" + minutes;

				}

		</script>

   		<div class="panel-body margin_top_20px padding_top_25px_res"><!-----  penal body ------->

			<!----------- Exam Time table Form ---------->

			<form name="exam_form" action="" method="post" class="mb-3 form-horizontal" enctype="multipart/form-data" id="exam_time_table">

				<div class="form-body user_form padding_top_25px_res">

					<div class="row">

						<div class="col-md-8 input exam_time_table_error_msg">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="required">*</span></label>

							<?php
							$own_data=$user_access['own_data'];
							$user_id=get_current_user_id();
							if($own_data == '1')
							{		
								if($school_obj->role == 'teacher'){

									$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	

									$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_created_by($class_id,$user_id);

								}
								else{
									
									$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_created_by($user_id);
								}
							}
							else{
								$tablename="exam"; 

								$retrieve_class = mj_smgt_get_all_data($tablename);
							}

							

							$exam_id="";

							if(isset($_REQUEST['exam_id']))

							{

								$exam_id=$_REQUEST['exam_id']; 

							}

							?>

							<select name="exam_id" class="line_height_30px form-control validate[required] width_100">

								<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>

								<?php

								foreach($retrieve_class as $retrieved_data)

								{

									$cid=$retrieved_data->class_id;

									$clasname=mj_smgt_get_class_name($cid);

									if($retrieved_data->section_id!=0)

									{

										$section_name=mj_smgt_get_section_name($retrieved_data->section_id); 

									}

									else

									{

										$section_name=esc_attr__('No Section', 'school-mgt');

									}

								?>

									<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.'('.smgt_get_class_section_name_wise($cid,$retrieved_data->section_id).')';?></option>

								<?php	

								}

								?>

							</select>               

						</div>

						<div class="col-md-4 col-sm-3 col-xs-12">        	

							<input type="submit" id="save_exam_time_table" value="<?php  esc_attr_e('Manage Exam Time','school-mgt');?>" name="save_exam_time_table" class="btn btn-success save_btn" />

						</div>  

					</div>

				</div>

			</form><!----------- Exam Time table Form ---------->

			<?php

			if(isset($_POST['save_exam_time_table']))

			{

				$exam_data= mj_smgt_get_exam_by_id($_POST['exam_id']);

				$school_obj= new School_Management;

				if($exam_data->section_id != 0)

				{

					$subject_data=$school_obj->mj_smgt_subject_list_with_calss_and_section($exam_data->class_id,$exam_data->section_id);

				}

				else

				{

					$subject_data=$school_obj->mj_smgt_subject_list($exam_data->class_id);

				}

				$start_date=$exam_data->exam_start_date;

				$end_date=$exam_data->exam_end_date;

				

				?>

				<input type="hidden" id="start" value="<?php echo date("Y-m-d",strtotime($start_date));?>">

				<input type="hidden" id="end" value="<?php echo date("Y-m-d",strtotime($end_date));?>">

				<div class="form-group"><!-------- Form Body -------->

					<div class="col-md-12 rtl_padding_3px">

						<div class="exam_table_res">

							<table class="table" style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;">

								<thead>

									<tr>

										<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Exam','school-mgt');?></th>

										<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Class','school-mgt');?></th>							

										<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Section','school-mgt');?></th>							

										<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Term','school-mgt');?></th>							

										<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Start Date','school-mgt');?></th>							

										<th  class="exam_hall_receipt_table_heading" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('End Date','school-mgt');?></th>							

									</tr>

								</thead>

								<tbody>							

									<tr>

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $exam_data->exam_name;?></td>							

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo get_the_title($exam_data->exam_term);?></td>

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>

										<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>

									</tr>

								</tbody>

							</table>

						</div>

					</div>			

				</div><!-------- Form Body -------->

				<?php

				if(isset($subject_data))

				{

					$obj_exam=new smgt_exam;

					foreach ($subject_data as $retrieved_data) 

					{ 

						$exam_time_table_data=$obj_exam->mj_smgt_check_exam_time_table($exam_data->class_id,$exam_data->exam_id,$retrieved_data->subid);	

					}

					if(!empty($subject_data))

					{

						?>

						<div class="col-md-12 rtl_padding_0px" style="margin-top: 20px !important;">

							<div class="exam_table_res">

								<form id="exam_form2" name="exam_form2" method="post">	<!-------- Exam Form -------->

									<input type='hidden' name='subject_data' id="subject_data" value='<?php echo json_encode($subject_data);?>'>

									<input type="hidden" name="class_id" value="<?php echo $exam_data->class_id;?>">

									<input type="hidden" name="section_id" value="<?php echo $exam_data->section_id;?>">

									<input type="hidden" name="exam_id" value="<?php echo $exam_data->exam_id;?>">

									<div class="exam_time_table_main_div">

										<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="exam_timelist_admin width_100" >

											<thead>

												<tr>    

													<th class="exam_hall_receipt_add_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Subject Code','school-mgt');?></th>

													<th class="exam_hall_receipt_add_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Subject Name','school-mgt');?></th>

													<th class="exam_hall_receipt_add_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam Date','school-mgt');?></th>

													<th class="exam_hall_receipt_add_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>

													<th  class="exam_hall_receipt_add_table_heading rtl_border_right_1px" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam End Time','school-mgt');?></th>

												</tr>

											</thead>

											<tbody>

												<?php 

												$obj_exam=new smgt_exam;

												$i = 1;

											

												foreach ($subject_data as $retrieved_data) 

												{ 

													//------- View Exam Time Table Data ------------//

													$exam_time_table_data=$obj_exam->mj_smgt_check_exam_time_table($exam_data->class_id,$exam_data->exam_id,$retrieved_data->subid);

													?>

													<script>

														$(document).ready(function(){

															var start = $( "#start" ).val();

															var end = $( "#end" ).val();

															$(".exam_date").datepicker({ 

																minDate: start,

																maxDate: end,
																changeYear:true,
																changeMonth: true,
																dateFormat: "yy-mm-dd",
																//console.log(minDate),

															});  

														});

													</script>

													<tr style="border: 1px solid #D9E1ED;">

														<input type="hidden" name="subject_id" value="<?php echo $retrieved_data->subid;?>">

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><input type="hidden" name = "subject_code_<?php echo $retrieved_data->subid;?>"  value="<?php echo $retrieved_data->subject_code;?>"><?php echo $retrieved_data->subject_code;?></td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><input type="hidden" name = "subject_name_<?php echo $retrieved_data->subid;?>" value="<?php echo $retrieved_data->sub_name;?>"><?php echo $retrieved_data->sub_name;?></td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;">

															<input id="exam_date_<?php echo $retrieved_data->subid; ?>" class="datepicker form-control datepicker_icon validate[required] text-input exam_date min_width_160 " placeholder="<?php esc_html_e("Select Date" , "school-mgt"); ?>" type="text" name="exam_date_<?php echo $retrieved_data->subid; ?>" value="<?php if(!empty($exam_time_table_data->exam_date)) { echo mj_smgt_getdate_in_input_box($exam_time_table_data->exam_date); } ?>" readonly>

														</td>

														<?php

														if(!empty($exam_time_table_data->start_time))

														{

															//------------ Start time convert --------------//

															$stime = explode(":", $exam_time_table_data->start_time);

															$start_hour=$stime[0];

															$start_min=$stime[1];

															$shours = str_pad($start_hour, 2, "0", STR_PAD_LEFT);

															$smin = str_pad($start_min, 2, "0", STR_PAD_LEFT);

															$start_am_pm=$stime[2];

															$start_time=$shours.':'.$smin.':'.$start_am_pm;

														}

														if(!empty($exam_time_table_data->end_time))

														{

															//-------------------- end time convert -----------------//

															$etime = explode(":", $exam_time_table_data->end_time);

															$end_hour=$etime[0];

															$end_min=$etime[1];

															$ehours = str_pad($end_hour, 2, "0", STR_PAD_LEFT);

															$emin = str_pad($end_min, 2, "0", STR_PAD_LEFT);

															$end_am_pm=$etime[2];

															$end_time=$ehours.':'.$emin.':'.$end_am_pm;

														}

														?>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;">

															<input type="text" id="timepicker" name="start_time_<?php echo $retrieved_data->subid;?>" class="form-control text-input start_time_<?php echo $retrieved_data->subid;?>" placeholder="<?php esc_html_e("Start Time" , "school-mgt"); ?>" value="<?php if(!empty($exam_time_table_data->start_time)){ echo $start_time; } ?>" />

														</td>

														<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;">

															<input type="text" id="timepicker" name="end_time_<?php echo $retrieved_data->subid;?>" class="form-control text-input end_time_<?php echo $retrieved_data->subid;?> " placeholder="<?php esc_html_e("End Time" , "school-mgt"); ?>" value="<?php if(!empty($exam_time_table_data->end_time)){ echo $end_time; } ?>" />

														</td>

													</tr>

													<?php 

													$i++;

												} ?>

											</tbody>

										</table>

									</div>

									<?php

									if(!empty($subject_data))

									{

										?>

										<div class="col-md-3 margin_top_20px padding_top_25px_res rtl_padding_0px">

											<input type="submit" id="save_exam_time" value="<?php  esc_attr_e('Save Time Table','school-mgt');?>" name="save_exam_table" class="btn btn-success width-auto save_btn rtl_margin_0px" />

										</div>

										<?php

									}

									?>

								</form><!-------- Exam Form -------->

							</div>

						</div>

						<?php

					}

					else

					{

						?>

						<div style="margin-top:20px !important;" id="message" class="rtl_message_display_inline_block alert_msg alert alert-success alert-dismissible " role="alert">

							<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

							</button>

							<?php echo esc_html_e('No Any Subject', 'school-mgt');?>

						</div>

						<?php

					}

				}

			}

			?>

		</div><!-------------  penal body ----------------->

		<?php 

	}

	?>

</div>