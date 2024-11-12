<?php

$role_name=mj_smgt_get_user_role(get_current_user_id());

?>

<script type="text/javascript">

	jQuery(document).ready(function($)

	{

		"use strict";



		jQuery('#subject_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		//----------------- SUBJECT DATA TABLE JS --------------------//

		jQuery('#subject_list').DataTable({

			"initComplete": function(settings, json) {

			$(".print-button").css({"margin-top": "-5%"});

		},


			//stateSave: true,
			'order': [2, 'asc'],

			"dom": 'lifrtp',

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

	                  {"bSortable": false},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": true},

	                  {"bSortable": false}],

			language:<?php echo mj_smgt_datatable_multi_language();?>

		});

		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

		$("#subject_teacher").multiselect({

			nonSelectedText :'<?php esc_attr_e( 'Select Teacher', 'school-mgt' ) ;?>',

			includeSelectAllOption: true ,

			selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',

			templates: {

				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',

			}

		});

		//------------ SELECT ALL CHECKBOX JS -----------------//

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

		$('#subject_syllabus ').change(function ()
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");
			 if(!(regex.test(val)))
			{
				$(this).val('');
				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");
			}
		});

		//------------- DELETE SELECTED CONFIRM MESSAGE JS -----------------//

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

	});

</script>

<?php

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$obj_subject=new smgt_subject;

$active_tab = isset($_GET['tab'])?$_GET['tab']:'subjectlist';

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

//=============== SAVE SUBJECT =================//

if(isset($_POST['subject']))

{

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'add_subject_front_nonce' ) )

	{



		$syllabus='';

		if(isset($_FILES['subject_syllabus']) && !empty($_FILES['subject_syllabus']['name']))

		{

			$value = explode(".", $_FILES['subject_syllabus']['name']);

			$file_ext = strtolower(array_pop($value));

			$extensions = array("pdf");



			if(in_array($file_ext,$extensions )=== false)

			{

				wp_redirect (home_url()."?dashboard=user&page=subject&message=3");

				exit;

			}

			if($_FILES['subject_syllabus']['size'] > 0)

			{

			 $syllabus=inventory_image_upmj_smgt_load($_FILES['subject_syllabus']);

			}

			else {

				$syllabus=$_POST['sylybushidden'];

			}

			//------TEMPRORY ADD RECORD FOR SET SYLLABUS----------

		}



		$subjects=array(

					'subject_code'=>mj_smgt_onlyNumberSp_validation($_POST['subject_code']),

					'sub_name'=>mj_smgt_address_description_validation(stripslashes($_POST['subject_name'])),

					'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_class']),

					'section_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_section']),

					'teacher_id'=>0,

					'edition'=>mj_smgt_address_description_validation(stripslashes($_POST['subject_edition'])),

					'author_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['subject_author']),

					'syllabus'=>$syllabus,

					'created_by'=>get_current_user_id()

		);

		if(isset($_FILES['subject_syllabus']) && empty($_FILES['subject_syllabus']['name']))

		{

			unset($subjects['syllabus']);

		}

		$tablename="subject";

		$selected_teachers = isset($_REQUEST['subject_teacher'])?$_REQUEST['subject_teacher']:array();

		//----------------  SUBJECT EDIT CODE ---------------//

		if($_REQUEST['action']=='edit')

		{

			//------------ SUBJECT CODE CHECK ------------//

			$sub_id=$_REQUEST['subject_id'];

			$class_id=$_POST['subject_class'];

			global $wpdb;



			$table_name_subject = $wpdb->prefix .'subject';



			$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subid !=".$sub_id);



			if(!empty($result_sub))

			{

				foreach($result_sub as $sub_code)

				{

					$subject_code[]=$sub_code->subject_code;

				}

				$check=in_array($_POST['subject_code'], $subject_code);

				if($check)

				{

					wp_redirect (home_url().'?dashboard=user&page=subject&tab=addsubject&action=edit&subject_id='.$sub_id.'&message=5');

					die;

				}

			}

			global $wpdb;

			$table_smgt_subject = $wpdb->prefix. 'teacher_subject';



			$subid=array('subid'=>$_REQUEST['subject_id']);

			$result=mj_smgt_update_record($tablename,$subjects,$subid);

			$wpdb->delete(

				$table_smgt_subject,      // table name

				array( 'subject_id' => $_REQUEST['subject_id'] ),  // where clause

				array( '%s' )      // where clause data type (string)

			);





			if(!empty($selected_teachers))

			{

				$teacher_subject = $wpdb->prefix .'teacher_subject';

				foreach($selected_teachers as $teacher_id)

				{

					$wpdb->insert($teacher_subject,

						array(

							'teacher_id' => $teacher_id,

							'subject_id' => $_REQUEST['subject_id'],

							'created_date' => time(),

							'created_by' => get_current_user_id()

						)

					);

				}

			}



			wp_safe_redirect(home_url()."?dashboard=user&page=subject&message=2");

		}

		else  //---------------- SUBJECT INSERT CODE ---------------//

		{

			$subject_code=$_POST['subject_code'];

			$class_id=$_POST['subject_class'];

			global $wpdb;



			$table_name_subject = $wpdb->prefix .'subject';



			$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subject_code=".$subject_code);



			if(!empty($result_sub))

			{

				wp_safe_redirect(home_url()."?dashboard=user&page=subject&message=5");

				die;

			}

			$result=mj_smgt_insert_record($tablename,$subjects);

			$lastid = $wpdb->insert_id;

			if(!empty($selected_teachers))

			{

				$teacher_subject = $wpdb->prefix .'teacher_subject';

				$device_token = array();

				foreach($selected_teachers as $teacher_id)

				{

					$wpdb->insert(

					$teacher_subject,

					array(

						'teacher_id' => $teacher_id,

						'subject_id' => $lastid,

						'created_date' => time(),

						'created_by' => get_current_user_id()

						)

					);

					$device_token[] = get_user_meta($teacher_id, 'token_id' , true);

				}

				/* Send Push Notification */



				$title = esc_attr__('New Notification For Assign Subject','school-mgt');

				$text = esc_attr__('New subject','school-mgt').' '.$_POST['subject_name'].' '.esc_attr__('has been assigned to you.','school-mgt');

				$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));

				$json = json_encode($notification_data);

				$message = MJ_smgt_send_push_notification($json);



				/* Send Push Notification */

			}

			if($result)

			{
				if(isset($_POST['smgt_mail_service_enable']))

				{

					foreach($_POST['subject_teacher'] as $teacher_id)

					{

						$smgt_mail_service_enable = $_POST['smgt_mail_service_enable'];

						if($smgt_mail_service_enable)

						{

							$search['{{teacher_name}}']	 	= 	mj_smgt_get_teacher($teacher_id);

							$search['{{subject_name}}'] 	= 	$_POST['subject_name'];

							$search['{{school_name}}'] 		= 	get_option('smgt_school_name');

							$message = mj_smgt_string_replacement($search,get_option('assign_subject_mailcontent'));

							if(!empty($syllabus))
							{
								$attechment = WP_CONTENT_DIR.'/uploads/school_assets/'.$syllabus;
							}
							else
							{
								$attechment = '';
							}
							if(get_option('smgt_mail_notification') == '1')
							{
								mj_smgt_send_mail_for_homework(mj_smgt_get_emailid_byuser_id($teacher_id),get_option('assign_subject_title'),$message,$attechment);
							}

						}

					}

				}
				wp_safe_redirect(home_url()."?dashboard=user&page=subject&message=1");

			}

		}

	}

}

//--------------- MULTIPLE SELECTED SUVBJECT DELETE -----------------//

if(isset($_REQUEST['delete_selected']))

{

	if(!empty($_REQUEST['id']))

	{

		foreach($_REQUEST['id'] as $subject_id)

		{

			$tablename="subject";

			$result=mj_smgt_delete_subject($tablename,$subject_id);

			wp_redirect (home_url()."?dashboard=user&page=subject&message=4");

		}

	}

}

//-------------- Delete SUBJECT -------------------//

$teacher_obj = new Smgt_Teacher;

$tablename="subject";

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

{

	$result=mj_smgt_delete_subject($tablename,$_REQUEST['subject_id']);

	if($result)

	{

		wp_redirect (home_url()."?dashboard=user&page=subject&message=4");

	}

}

?>

<!-- POP up code -->

<div class="popup-bg">

    <div class="overlay-content">

		<div class="modal-content">

			<div class="view_popup"></div>

		</div>

    </div>

</div>

<!-- End POP-UP Code -->

<div class="panel-body panel-white frontend_list_margin_30px_res"><!----------- PENAL BODY ------------->

	<?php

	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

	switch($message)

	{

		case '1':

			$message_string = esc_attr__('Subject Added Successfully.','school-mgt');

			break;

		case '2':

			$message_string = esc_attr__('Subject Updated Successfully.','school-mgt');

			break;

		case '3':

			$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');

			break;

		case '4':

			$message_string = esc_attr__('Subject Deleted Successfully.','school-mgt');

			break;

		case '5':

			$message_string = esc_attr__('Please Enter Unique Subject Code','school-mgt');

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

	}

	//---------------- SUBJECT LIST TAB ----------------//

	if($active_tab=='subjectlist')

	{

		$user_id=get_current_user_id();

		//------- SUBJECT DATA FOR STUDENT ---------//

		if($school_obj->role == 'student')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{

				$subjects = $school_obj->subject;

			}

			else

			{

				$subjects = mj_smgt_get_all_data('subject');

			}

		}

		//------- SUBJECT DATA FOR TEACHER ---------//

		elseif($school_obj->role == 'teacher')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{

				$subjects=array();

				$subjects_data =$obj_subject->mj_smgt_get_teacher_own_subject($user_id);

				foreach($subjects_data as $s_id)

				{

					$subjects[]=mj_smgt_get_subject($s_id->subject_id);

				}

			}

			else

			{

				$subjects = mj_smgt_get_all_data('subject');

			}

		}

		//------- SUBJECT DATA FOR PARENT ---------//

		elseif($school_obj->role == 'parent')

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{

				$chid_array =$school_obj->child_list;


				$result = array();
				foreach ($chid_array as $child_id)

				{

					$class_info = $school_obj->mj_smgt_get_user_class_id($child_id);

					$result[]= $school_obj->mj_smgt_subject_list($class_info->class_id);

				}



				$mergedArray = array_merge(...$result);

				$subjects = array_unique($mergedArray, SORT_REGULAR);



		}

			else

			{

				$subjects = mj_smgt_get_all_data('subject');

			}

		}

		//------- SUBJECT DATA FOR SUPPORT STAFF ---------//

		else

		{

			$own_data=$user_access['own_data'];

			if($own_data == '1')

			{

				$subjects = mj_smgt_get_all_own_subject_data('subject',);

			}

			else

			{

				$subjects = mj_smgt_get_all_data('subject');

			}

		}



		if(!empty($subjects))

		{

			?>

			<div class="panel-body"><!------------ PENAL BODY ---------------->

				<div class="table-responsive"><!---------------- TABLE RESPONSIVE ------------------>

					<!----------- SUBJECT LIST FORM START ---------->

					<form id="frm-example" name="frm-example" method="post">

						<table id="subject_list" class="display dataTable dataTable1" cellspacing="0" width="100%">

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

									<th><?php esc_attr_e('Subject Code','school-mgt');?></th>

									<th><?php esc_attr_e('Subject Name','school-mgt');?></th>

									<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>

									<th><?php esc_attr_e('Class Name','school-mgt');?></th>

									<th><?php esc_attr_e('Author Name','school-mgt');?></th>

									<th><?php esc_attr_e('Edition','school-mgt');?></th>

									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

								</tr>

							</thead>

							<tbody>

								<?php



								$i=0;

								foreach ($subjects as $retrieved_data)

								{



									$teacher_group = array();

									$teacher_ids = mj_smgt_teacher_by_subject($retrieved_data);



									foreach($teacher_ids as $teacher_id)

									{

										$teacher_group[] = mj_smgt_get_teacher($teacher_id);

									}

									$teachers = implode(',',$teacher_group);

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

											<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->subid;?>"></td>

											<?php

										}

										?>

										<td class="user_image width_50px profile_image_prescription padding_left_0">

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view">

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Subject.png"?>" alt="" class="massage_image center">

												</p>

											</a>

										</td>

										<td><?php



										if(!empty($retrieved_data->subject_code))

										{

											echo $retrieved_data->subject_code;

										}

										else

										{

											echo "N/A";

										}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Code','school-mgt');?>"></i></td>

										<td>

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view">

												<?php echo $retrieved_data->sub_name;?>

											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i>

										</td>

										<td><?php if($teachers){ echo $teachers;}else{echo 'N/A';} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

										<td><?php $cid=$retrieved_data->class_id;

										if(!empty($cid)){

											echo  $clasname=smgt_get_class_section_name_wise($cid,$retrieved_data->section_id);

										}

										else{

											echo 'N/A';

										}

										?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

										<td><?php if(!empty($retrieved_data->author_name)){ echo $retrieved_data->author_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Author Name','school-mgt');?>"></i></td>

										<td><?php if(!empty($retrieved_data->edition)){ echo $retrieved_data->edition; }else{ echo "N/A"; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Edition','school-mgt');?>"></i></td>

										<td class="action">

											<div class="smgt-user-dropdown">

												<ul class="" style="margin-bottom: 0px !important;">

													<li class="">

														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

														</a>

														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

															<li class="float_left_width_100 ">

																<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->subid;?>" type="subject_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>

															</li>

															<?php

															if($user_access['edit']=='1')

															{

																?>

																<li class="float_left_width_100 border_bottom_menu">

																	<a href="?dashboard=user&page=subject&tab=addsubject&action=edit&subject_id=<?php echo $retrieved_data->subid;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

																</li>



																<?php

															}

															if($user_access['delete']=='1')

															{

																?>

																<li class="float_left_width_100 ">

																	<a href="?dashboard=user&page=subject&tab=Subject&action=delete&subject_id=<?php echo $retrieved_data->subid;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

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

							<div class="print-button pull-left padding_top_25px_res">

								<button class="btn-sms-color button_reload">

									<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">

									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

								</button>

								<?php

								if($user_access['delete']=='1')

								{

									?>

									<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<?php

								}

								?>

							</div>

							<?php

						}

						?>

					</form>

				</div>

			</div>

			<?php

		}

		else

		{

			if($user_access['add']=='1')

			{

				?>

				<div class="no_data_list_div no_data_img_mt_30px">

					<a href="<?php echo home_url().'?dashboard=user&page=subject&tab=addsubject';?>">

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

	//----------------- ADD SUBJECT TAB ------------------//

	if($active_tab=='addsubject')

	{

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

		{

			$edit=1;



			$subject=mj_smgt_get_subject($_REQUEST['subject_id']);

		}

		?>

		<div class="panel-body"><!------------ PENAL BODY ------------>

			<!----------- SUBJECT FORM START ---------------->

			<form name="student_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="subject_form">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<div class="header">

					<h3 class="first_hed"><?php esc_html_e('Subject Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-md-3">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="subject_code"class="form-control validate[required,custom[onlyNumberSp],maxSize[8],min[0]] text-input" type="text" maxlength="50" value="<?php if($edit){ echo $subject->subject_code;}?>" name="subject_code">

									<label for="userinput1" class=""><?php esc_html_e('Subject Code','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="subject_name" class="form-control validate[required,custom[address_description_validation]] margin_top_10_res" type="text" maxlength="50" value="<?php if($edit){ echo $subject->sub_name;}?>" name="subject_name">

									<label for="userinput1" class=""><?php esc_html_e('Subject Name','school-mgt');?><span class="required">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="required">*</span></label>

							<?php if($edit){ $classval=$subject->class_id; }else{$classval='';}?>

							<select name="subject_class" class="line_height_30px form-control validate[required] class_by_teacher" id="class_list">

									<option value=""><?php esc_attr_e('Select Class', 'school-mgt');?></option>

									<?php

										foreach(mj_smgt_get_allclass() as $classdata)

										{ ?>

										<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

									<?php } ?>

							</select>

						</div>

						<?php wp_nonce_field( 'add_subject_front_nonce' ); ?>

						<div class="col-md-6 input">

							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>

							<?php if($edit){ $sectionval=$subject->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

							<select name="class_section" class="line_height_30px form-control" id="class_section">

								<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

								<?php

								if($edit)

								{

									foreach(mj_smgt_get_class_sections($subject->class_id) as $sectiondata)

									{

										?>

									 	<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

										<?php

									}

								}?>

							</select>

						</div>

						<?php

						if($school_obj->role == 'teacher')

						{

							$user_id=get_current_user_id();

							?>

							<!-- <div class="col-md-6 form-group row"> -->

								<input type="hidden" name="subject_teacher[]" value="<?php echo $user_id;?>">

							<!-- </div> -->

							<?php

						}

						else

						{

							?>

							<div class="col-md-6 rtl_margin_top_15px teacher_list_multiselect">

								<div class="col-sm-12 multiselect_validation_class smgt_multiple_select rtl_padding_left_right_0px margin_bottom_15px_res">

									<?php

									$teachval = array();

									if($edit)

									{

									  $teachval = mj_smgt_teacher_by_subject($subject);

									  $teacherdata_array	= 	mj_smgt_get_teacher_by_class_id($subject->class_id);

									}

									else

									{

										$teacherdata_array=mj_smgt_get_usersdata('teacher');

									}

									?>

									<select name="subject_teacher[]" multiple="multiple" id="subject_teacher" class="form-control validate[required] teacher_list">

									   <?php

											foreach($teacherdata_array as $teacherdata)

											{ ?>

											 <option value="<?php echo $teacherdata->ID;?>" <?php echo $teacher_obj->mj_smgt_in_array_r($teacherdata->ID, $teachval) ? 'selected' : ''; ?>><?php echo $teacherdata->display_name;?></option>

										<?php }?>

									</select>

								</div>

							</div>

							<?php

						}?>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="subject_edition" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $subject->edition;}?>" name="subject_edition">

									<label for="userinput1" class=""><?php esc_html_e('Edition','school-mgt');?></label>

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="subject_author" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="100" type="text" value="<?php if($edit){ echo $subject->author_name;}?>" name="subject_author">

									<label for="userinput1" class=""><?php esc_html_e('Author Name','school-mgt');?></label>

								</div>

							</div>

						</div>

						<?php

						if($edit)

						{

							$syllabus=$subject->syllabus;

							?>

							<div class="col-md-6">

								<div class="form-group input">

									<div class="col-md-12 form-control res_rtl_height_50px rtl_padding_10px">

										<label for="" class="custom-control-label custom-top-label ml-2 label_position_rtl margin_left_30px"><?php _e('Syllabus','school-mgt');?></label>

										<div class="col-sm-12">

											<input type="file" accept=".pdf" name="subject_syllabus"  id="subject_syllabus"/>

											<input type="hidden" name="sylybushidden" value="<?php if($edit){ echo $subject->syllabus;} else echo "";?>">

										</div>

										<?php if(!empty($syllabus))

										{

											?>

											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 ">

												<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$syllabus; ?>" record_id="<?php echo $subject->subject;?>"><i class="fa fa-download"></i>  <?php echo esc_html_e("Download" , "school-mgt");?></a>

											</div>

											<?php

										} ?>

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

										<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl"><?php _e('Syllabus','school-mgt');?></label>

										<div class="col-sm-12">

											<input type="file" accept=".pdf" class="line_height_28px col-md-12" name="subject_syllabus"  id="subject_syllabus"/>

										</div>

									</div>

								</div>

							</div>

							<?php

						}
						if(!$edit)

						{
						if($role_name == "supportstaff")

						{

						?>

						<div class="col-md-6 rtl_margin_top_15px rtl_margin_bottom_0px" style="margin-bottom:15px;" >

							<div class="form-group">

								<div class="col-md-12 form-control input_height_50px">

									<div class="row padding_radio">

										<div class="input-group input_checkbox">

											<label class="custom-top-label"><?php esc_html_e('Send Email to Teacher','school-mgt');?></label>

											<div class="checkbox checkbox_lebal_padding_8px">

												<label>

													<input id="chk_subject_mail" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">

												</label>

											</div>

											&nbsp;&nbsp;<span><?php esc_html_e('Enable','school-mgt');?></span>

										</div>

									</div>

								</div>

							</div>

						</div>

						<?php

						}
					}

						?>

					</div>

				</div>

				<div class="form-body user_form ">

					<div class="row">

						<div class="col-sm-6">

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Subject','school-mgt'); }else{ esc_attr_e('Add Subject','school-mgt');}?>" name="subject" class="btn btn-success save_btn teacher_for_alert"/>

						</div>

					</div>

				</div>

			</form>

			<!----------- SUBJECT FORM END ---------------->

		</div><!------------ PENAL BODY ------------>

		<?php

	}

	?>

</div><!----------- PENAL BODY ------------->