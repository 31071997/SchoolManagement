<?php 
$role_name=mj_smgt_get_user_role(get_current_user_id());
?>
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		"use strict";	
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
			$('.edate').datepicker({
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

		var table =  jQuery('#attendance_teacher_list').DataTable({
					//stateSave: true,
					"order": [[ 0, "asc" ]],
					"dom": 'lifrtp',
					"aoColumns":[	
						{"bSortable": false},
						{"bSortable": true},					           
						{"bSortable": true},					           
						{"bSortable": true},					           
						{"bSortable": false}
					],	
					language:<?php echo mj_smgt_datatable_multi_language();?>	
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
		//-------------- multiple select checkbox ----------//
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
		//----------- multiple select delete js -----------//
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

		jQuery('#teacher_list1').DataTable({
			"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
		//stateSave: true,
		"ordering": true,
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
					{"bSortable": true},					           
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					<?php
					if($role_name == "supportstaff" || $role_name == "teacher")
					{
					?>					           
						{"bSortable": true},					           
						{"bSortable": false}
						<?php
					}
					?>
			],	
			language:<?php echo mj_smgt_datatable_multi_language();?>	
		});
		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
		$('#teacher_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#class_id').multiselect(
		{
				nonSelectedText :'<?php esc_attr_e( 'Select Class', 'school-mgt' ) ;?>',
				includeSelectAllOption: true,
				selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
				templates: {
					button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
				}
		});
		$('#birth_date').datepicker({
			maxDate : 0,
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
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
					$(this).val(month + "/" + year);
				},
		}); 

		$(".class_for_alert").click(function()
		{	
			let checked = $(".form-check-input:checked").length;
			if(!checked)
			{
			alert(language_translate2.one_class_select_alert);
			return false;
			}	
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
	});
	function fileCheck(obj) 
	{
		var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
		if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
		{
			alert(language_translate2.image_forame_alert);
			$(obj).val('');
		}	
	}
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'teacherlist';
$teacher_obj = new Smgt_Teacher;
$role='teacher';
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
//------------- SAVE TEACHER -------------//
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
		wp_redirect (home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=2'); 		
	}
	else
	{
		/* Setup Wizard */
		$wizard = smgt_setup_wizard_steps_updates('step3_teacher');
		if( !email_exists( $_POST['email'] )) 
		{
			$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
			$result1 = $teacher_obj->mj_smgt_add_muli_class($_POST['class_name'],mj_smgt_strip_tags_and_stripslashes($_POST['email']));
			wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=1'); 			
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
//-------------------- DELETE TEACHER ---------------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{				
	$result=mj_smgt_delete_usedata($_REQUEST['teacher_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=5'); 			
	}
}
//------------------ MULTIPLE DELETE TEACHER -------------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_usedata($id);
		}
	}
	if($result)
	{ 
		wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=5'); 	
	}
}
$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
//-------------- MESSAGES --------------//
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
		$message_string = esc_attr__('Teacher CSV Uploaded Successfully .','school-mgt');
		break;
	case '7':
		$message_string = esc_attr__('Student Activated Auccessfully.','school-mgt');
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
?>
<div class="panel-body panel-white frontend_list_margin_30px_res">
	<?php 
	//------------ TEACHER LIST ---------------//
	if($active_tab == 'teacherlist')
	{ 
		$user_id=get_current_user_id();
		//------- TEACHER DATA FOR STUDENT ---------//
		if($school_obj->role == 'student')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);
					
				$teacherdata	= 	mj_smgt_get_teacher_by_class_id($class_id);	
			
			}
			else
			{
				$teacherdata	=	mj_smgt_get_usersdata('teacher');
			}
		}
		//------- TEACHER DATA FOR TEACHER ---------//
		elseif($school_obj->role == 'teacher')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$user_id=get_current_user_id();		
				
				$teacher_own=array();
				$teacherdata_created_by=array();
				
				$teacher_own[]=get_userdata($user_id);					
					
				$teacherdata_created_by[]= get_users(
										array(
												'role' => 'teacher',
												'meta_query' => array(
												array(
														'key' => 'created_by',
														'value' => $user_id,
														'compare' => '='
													)
												)
										));	
				$teacherdata1=array_merge($teacher_own,$teacherdata_created_by);
				
				$teacherdata=array_unique($teacherdata1, SORT_NUMERIC );
			}
			else
			{
				$teacherdata	=	mj_smgt_get_usersdata('teacher');
			}
		}
		//------- TEACHER DATA FOR PARENT ---------//
		elseif($school_obj->role == 'parent')
		{
			$teacherdata_data=array();
			$child 	= 	get_user_meta(get_current_user_id(),'child',true);
			foreach($child as $c_id)
			{
				$class_id 	= 	get_user_meta($c_id,'class_name',true);
				$teacherdata_data1	= 	mj_smgt_get_teacher_by_class_id($class_id);	
				if(!empty($teacherdata_data1))
				{
					$teacherdata_data = array_merge($teacherdata_data,$teacherdata_data1);
				}
				else{
					$teacherdata_data="";
				}
			}
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$teacherdata_created_by= get_users(
					array(
							'role' => 'teacher',
							'meta_query' => array(
							array(
									'key' => 'created_by',
									'value' => $user_id,
									'compare' => '='
								)
							)
					));	
					if(!empty($teacherdata_data)){
						$teacherdata_array=array_merge($teacherdata_data,$teacherdata_created_by);
					}
					else{
						$teacherdata_array = $teacherdata_created_by;
					}
			}
			else
			{
				$teacherdata_array	=	mj_smgt_get_usersdata('teacher');
			}
			
			$teacherdata=array_unique($teacherdata_array, SORT_REGULAR );
		}
		//------- TEACHER DATA FOR SUPPORT STAFF ---------//
		else
		{ 
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$teacherdata_created_by= get_users(
					array(
							'role' => 'teacher',
							'meta_query' => array(
							array(
									'key' => 'created_by',
									'value' => $user_id,
									'compare' => '='
								)
							)
					));	
				$teacherdata=$teacherdata_created_by;	
			}
			else
			{
				$teacherdata	=	mj_smgt_get_usersdata('teacher');
			}
		} 
		if(!empty($teacherdata))
		{
			?>	
			<div class="panel-body"><!--------- PENAL BODY ----------->
				<div class="table-responsive"><!--------- TABLE RESPONSIVE ----------->
					<!----------- TEACHER LIST FORM START ---------->
					<form id="frm-example" name="frm-example" method="post">
						<table id="teacher_list1" class="display dataTable teacher_datatable" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
								<tr>
									<?php
									if($role_name == "supportstaff")
									{
										?>
										<th class="padding_0"><input type="checkbox" class="smgt_sub_chk select_all" id="select_all"></th>
										<?php
									}
									?>
									<th><?php  esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
									<th><?php esc_attr_e( 'Teacher Name & Email', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Subject', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Date of Birth', 'school-mgt' ) ;?></th>
									<?php
									if($role_name == "supportstaff" || $role_name == "teacher")
									{
									?>
										<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>   
										<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
									<?php
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($teacherdata as $retrieved_data)
								{   
									if(!username_exists($retrieved_data->user_login)){ continue; } /* IF Teacher not exists then we dont want to print emprt row. */
									?>
									<tr>
										<?php
										if($role_name == "supportstaff")
										{
											?>
											<td class="checkbox_width_10px">
												<input type="checkbox" class="smgt_sub_chk selected_teacher" name="id[]" value="<?php echo esc_attr($retrieved_data->ID);?>">
											</td>
											<?php
										}
										?>
										<td class="user_image width_50px">
											<?php
												if($role_name == "supportstaff" || $role_name == "teacher")
												{
											?>
											<a class="" href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>">
											<?php
												}else{
													?>
													<a class="" href="#">
													<?php
												}
											 ?>
												<?php 
												$uid=$retrieved_data->ID;
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
											<?php
											if($role_name == "supportstaff" || $role_name == "teacher")
											{
											?>
												<a class="color_black" href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>">
											<?php
											}else{
												?>
												<a class="" href="#">
												<?php
											}	
											?>	
												<?php echo $retrieved_data->display_name;?>
												</a>
												<?php
												if($role_name == "supportstaff" || $role_name == "teacher")
												{
											?>
											<br>
											<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
											<?php
											}	
											?>	
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
										<?php echo mj_smgt_getdate_in_input_box($retrieved_data->birth_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date Of Birth','school-mgt');?>" ></i>
										</td>
										<?php
										if($role_name == "supportstaff" || $role_name == "teacher")
										{
										?>
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
																<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a> 
															</li>
															<?php
															if($user_access['edit']=='1')
															{
																?>	
																<li class="float_left_width_100 border_bottom_menu">			
																	<a href="?dashboard=user&page=teacher&tab=addteacher&action=edit&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
																</li>
																<?php
															}
															if($user_access['delete']=='1')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=teacher&tab=teacherlist&action=delete&teacher_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
																</li>
																<?php 
															} 
															?>
														</ul>
													</li>
												</ul>
											</div>	
										</td>
										<?php
										}
										?>
									</tr>
									<?php 
								}
								 ?>     
							</tbody>        
						</table>
						<?php
						if($role_name == "supportstaff")
						{
							?>
							<div class="print-button pull-left">
								<button class="btn btn-success btn-sms-color button_reload">
									<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
								</button>
								<?php 
								if($user_access['delete'] =='1')
								{
									?>
										<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
									<?php 
								} ?>
							</div>
							<?php
						}
						?>
					</form><!----------- TEACHER LIST FORM START ---------->
				</div><!--------- TABLE RESPONSIVE ----------->
			</div><!--------- PENAL BODY ----------->
			<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=teacher&tab=addteacher';?>">
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
	//------------ TEACHER ADD FORM ---------------//
	if($active_tab == 'addteacher')
	{  
		$role='teacher';
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$user_info = get_userdata($_REQUEST['teacher_id']);
		}
		?>
		<div class="panel-body"><!----------- PENAL BODY ------------->
			<!------------------ TEACHER FORM --------------------->
			<form name="teacher_form" action="" method="post" class="mt-3 form-horizontal" id="teacher_form" enctype="multipart/form-data">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="role" value="<?php echo $role;?>"  />
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Personal Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!-- user_form -->
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
									<label class="" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
									<label class="" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
									<label class="" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>

											<div class="d-inline-block">	
												<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
												<label class="radio-inline">
												<input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?> 
												</label>
												&nbsp;&nbsp;
												<label class="radio-inline">
												<input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
												</label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 padding_top_15px_res">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="birth_date" class="form-control date_picker validate[required]" type="text"  name="birth_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo mj_smgt_getdate_in_input_box($_POST['birth_date']);?>" readonly>
									<label class="date_label" for="birth_date"><?php esc_attr_e('Date of Birth','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
					</div>
				</div><!-- user_form -->
				<div class="header"><!-- header -->
					<h3 class="first_hed"><?php esc_html_e('Contact Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"> <!--user_form div-->  
					<div class="row"><!--row div--> 
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text"  name="address" value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
									<label class="" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
									<label class="" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
									value="<?php if($edit){ echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
									<label class="" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="zip_code" class="form-control  validate[required,custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="zip_code" 
									value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
									<label class="" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>	
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px">
							<div class="col-sm-12 multiselect_validation_class smgt_multiple_select rtl_padding_left_right_0px">
								<?php
									if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){ $classval=$_POST['class_name'];}else{$classval='';}
									$classes = array();
									if(isset($_REQUEST['teacher_id']))
									$classes = $teacher_obj->mj_smgt_get_class_by_teacher($_REQUEST['teacher_id']);
								?>
								<select name="class_name[]" multiple="multiple" id="class_id" class="form-control validate[required]">
									<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{ 
											?>
											<option value="<?php echo $classdata['class_id'];?>"<?php echo $teacher_obj->mj_smgt_in_array_r($classdata['class_id'], $classes) ? 'selected' : ''; ?>><?php echo $classdata['class_name'];?></option>
											<?php 
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 padding_top_15px_res">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phonecode">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8 mobile_error_massage_left_margin">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="mobile_number" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number" value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
											<label class="" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>" class="form-control phonecode" name="alter_mobile_number">

											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></span></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="alternet_mobile_number" class="form-control text-input" type="text"  name="alternet_mobile_number" value="<?php if($edit){ echo $user_info->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
											<label class="" for="mobile_number"><?php esc_attr_e('Alternate Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px" >	
							<label class="ml-1 custom-top-label top" for="working_hour"><?php esc_attr_e('Working Hour','school-mgt');?></label>       
							<?php if($edit){ $workrval=$user_info->working_hour; }elseif(isset($_POST['working_hour'])){$workrval=$_POST['working_hour'];}else{$workrval='';}?>
							<select name="working_hour" class="line_height_30px form-control" id="working_hour">
								<option value=""><?php esc_attr_e('Select Job Time','school-mgt');?></option>
								<option value="full_time" <?php selected( $workrval, 'full_time'); ?>><?php esc_attr_e('Full Time','school-mgt');?></option>
								<option value="half_day" <?php selected( $workrval, 'half_day'); ?>><?php esc_attr_e('Part time','school-mgt');?></option>
							</select>
						</div>		
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 padding_top_15px_res">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="possition" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text"  name="possition" 
									value="<?php if($edit){ echo $user_info->possition;}elseif(isset($_POST['possition'])) echo $_POST['possition'];?>">
									<label class="" for="possition "><?php esc_attr_e('Position','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div><!--row div--> 
				</div><!--user_form div--> 
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Login Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"> <!--user_form div-->  
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="email" class="student_email_id form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
									<label class="" for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
								</div>
								<div class="email_validation_div">
									<div class="formError" style="opacity: 0.87; position: absolute; top: 33px; left: 482.5px; margin-top: 0px; display: block;"><div class="formErrorArrow formErrorArrowBottom"><div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div></div><div class="formErrorContent"><?php esc_html_e('Email id Already Exist.','hospital_mgt');?><br></div></div>
								</div>
							</div>
						</div>	
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password" value="">
									<label class="" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
									<i class="fa fa-eye-slash" id="togglePassword"></i>
								</div>
							</div>
						</div>
					</div>
				</div><!--user_form div--> 
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Profile Image','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!--user_form div--> 
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 upload-profile-image-patient p-0">
									<div class="col-md-12 form-control upload-profile-image-frontend res_rtl_height_50px">	
										<label for="gmgt_membershipimage" class="custom-control-label profile_rtl_css custom-top-label ml-2 label_position_rtl"><?php _e('Profile Image','school-mgt');?></label>
										<div class="col-sm-12">
											<input type="hidden" id="amgt_user_avatar_url" class="form-control" name="smgt_user_avatar" value="<?php if($edit)echo esc_html( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" readonly />
											<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo esc_html($user_info->smgt_user_avatar);}elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image'];?>">
											<input id="upload_user_avatar" name="upload_user_avatar_image" type="file" class="form-control file" onchange="fileCheck(this);" value="<?php esc_html_e( 'Upload image', 'school-mgt' ); ?>" style="border:0px solid;"/>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview" >
										<?php if($edit) 
										{
											if($user_info->smgt_user_avatar == "")
											{?>
												<img class="image_preview_css" src="<?php echo get_option( 'smgt_teacher_thumb_new' ) ?>">
												<?php 
											}
											else 
											{
												?>
												<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar ); ?>" />
												<?php 
											}
										}
										else 
										{
											?>
											<img class="image_preview_css" src="<?php echo get_option( 'smgt_teacher_thumb_new' ) ?>">
											<?php  
										} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!--user_form div--> 
				<div class="header">	
					<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Submitted Documents','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!--user_form div--> 
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label " for="attachment"><?php esc_attr_e('Submitted Documents','school-mgt');?></label>
											<?php 
											if($edit)
											{	 
												$attachval=explode(',',$user_info->attachment); 
											}
											?>
											<label class="res_doc_lasbel display_flex">
												<input type="checkbox" name="attachment[]"  class="me-1" value="cv" <?php if($edit){ if(in_array("cv", $attachval)){ echo "checked=\'checked\'"; } } ?> />&nbsp;<?php esc_attr_e('Curriculum Vitae','school-mgt');?>
											</label>
											<label class="res_doc_lasbel display_flex">
												<input type="checkbox" name="attachment[]" class="me-1" value="edu_certificate" <?php if($edit){ if(in_array("edu_certificate", $attachval)) {echo  "checked=\'checked\'"; } } ?>/>&nbsp;<?php esc_attr_e('Education Certificate','school-mgt');?>
											</label>
											<label class="res_doc_lasbel display_flex">
												<input type="checkbox" name="attachment[]" class="me-1" value="experience_certificate" <?php if($edit){ if(in_array("experience_certificate", $attachval)) { echo "checked=\'checked\'"; }  }?> />&nbsp;<?php esc_attr_e('Experience Certificate','school-mgt');?>
											</label>
										</div>												
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!--user_form div--> 
				<div class="form-body user_form"><!--user_form div--> 
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12 mt-3"><!--save btn--> 
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Teacher','school-mgt'); }else{ esc_attr_e('Add Teacher','school-mgt');}?>" name="save_teacher" class="btn btn-success class_for_alert save_btn"/>
						</div><!--save btn--> 
					</div>
				</div>
			</form><!------------------ TEACHER FORM --------------------->
		</div><!----------- PENAL BODY ------------->
		<?php
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_teacher')
	{
		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
		$teacher_obj = new Smgt_Teacher;
		$obj_route = new Class_routine();	
		$teacher_data=get_userdata($_REQUEST['teacher_id']);
		$teacher_id = $_REQUEST['teacher_id'];
		?>
		<div class="panel-body view_page_main"><!-- START PANEL BODY DIV-->
			<div class="content-body"><!-- START CONTENT BODY DIV-->
				<!-- Detail Page Header Start -->
				<section id="user_information" class="">
					<div class="view_page_header_bg">
						<div class="row">
							<div class="col-xl-10 col-md-9 col-sm-10">
								<div class="user_profile_header_left float_left_width_100">
									<?php
									$umetadata=mj_smgt_get_user_image($teacher_data->ID);
									?>
									<img class="user_view_profile_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_teacher_thumb_new' );}?>">
									<div class="row profile_user_name">
										<div class="float_left view_top1">
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<label class="view_user_name_label"><?php echo esc_html($teacher_data->display_name);?></label>
												<div class="view_user_edit_btn">
													<?php
													if($user_access['edit']=='1')
													{
														?>
														<a class="color_white margin_left_2px" href="?dashboard=user&page=teacher&tab=addteacher&action=edit&teacher_id=<?php echo $teacher_data->ID;?>">
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
														</a>
														<?php
													}	
													?>
												</div>
											</div>
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<div class="view_user_phone float_left_width_100">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $teacher_data->mobile_number;?></label>
												</div>
											</div>
										</div>
									</div>
									<div class="row padding_top_15px_res view_user_teacher_label">
										<div class="col-xl-12 col-md-12 col-sm-12">
											<div class="view_top2">
												<div class="row view_user_teacher_label">
													<div class="col-md-12 address_student_div">
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $teacher_data->address; ?></label>
													</div>		
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
								<div class="group_thumbs">
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>">
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- Detail Page Header End -->
		
				<!-- Detail Page Tabing Start -->
				<section id="body_area" class="">
					<div class="row">
						<div class="col-xl-12 col-md-12 col-sm-12">
							<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
								<li class="<?php if($active_tab1=='general'){?>active<?php }?>">			
									<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&tab1=general&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" 
									class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
									<?php esc_html_e('GENERAL', 'school-mgt'); ?></a> 
								</li>
								<?php
								$page1='class';
								$class=mj_smgt_page_access_rolewise_accessright_dashboard($page1);
								if($class==1)
								{ 
									?>
									<li class="<?php if($active_tab1=='class_list'){?>active<?php }?>">
										<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&tab1=class_list&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'class_list' ? 'active' : ''; ?>">
										<?php esc_html_e('Class List', 'school-mgt'); ?></a> 
									</li>  
									<?php
								}
								$page2='schedule';
								$schedule=mj_smgt_page_access_rolewise_accessright_dashboard($page2);
								if($schedule==1)
								{ 
									if($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')
									{
										?>
										<li class="<?php if($active_tab1=='schedule'){?>active<?php }?>">
											<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&tab1=schedule&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'schedule' ? 'active' : ''; ?>">
											<?php esc_html_e('Class Schedule', 'school-mgt'); ?></a> 
										</li>  
										<?php
									}
								}
								$page='attendance';
								$attendance=mj_smgt_page_access_rolewise_accessright_dashboard($page);
								if($attendance==1)
								{ 
									?>
									<li class="<?php if($active_tab1=='attendance'){?>active<?php }?>">
										<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&tab1=attendance&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'attendance' ? 'active' : ''; ?>">
										<?php esc_html_e('Attendance', 'school-mgt'); ?></a> 
									</li>  
									<?php
								}
								
								?>
							</ul>
						</div>
					</div>
				</section>
				<!-- Detail Page Tabing End -->
		
				<!-- Detail Page Body Content Section  -->
				<section id="body_content_area" class="">
					<div class="panel-body"><!-- START PANEL BODY DIV-->
						<?php 
						//--- general tab start ----//
						if($active_tab1 == "general")
						{
							?>
							<div class="row margin_top_15px margin_left_3">
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo $teacher_data->user_email; ?> </label>
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels">
									+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $teacher_data->mobile_number; ?>
									</label>	
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo esc_html_e(ucfirst($teacher_data->gender),'school-mgt'); ?></label>	
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($teacher_data->birth_date); ?>
									</label>
								</div>
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Position', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php if(!empty($teacher_data->possition)){ echo $teacher_data->possition; }else{ echo "N/A"; } ?>
									</label>
								</div>
	
							</div>
							<!-- student Information div start  -->
							<div class="row margin_top_20px">
								<div class="col-xl-12 col-md-12 col-sm-12 rtl_padding_0px">
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'school-mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label> <br>
													<label class="view_page_content_labels"><?php echo $teacher_data->city; ?></label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
													<label class="ftext_style_capitalization view_page_content_labels"><?php if(!empty($teacher_data->state)){ echo $teacher_data->state; }else{ echo "N/A"; } ?></label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels"><?php echo $teacher_data->zip_code; ?></label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alternate Mobile Number', 'school-mgt'); ?> </label><br>
													<lable class="view_page_content_labels"><?php if(!empty($teacher_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $teacher_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Working Hour', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels">
														<?php 
														if(!empty($teacher_data->working_hour))
														{ 
															$working_data = $teacher_data->working_hour; 
															if($working_data == 'full_time'){
																echo esc_html_e('Full Time', 'school-mgt');
															}
															else
															{
																echo esc_html_e('Part Time', 'school-mgt');
															}
														}
														else
														{ 
															echo "N/A"; 
														} 
														?>
													</label>
												</div>
												<div class="col-xl-4 col-md-4 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Class Name', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels">
													<?php 
														$classes="";
														$classes = $teacher_obj->mj_smgt_get_class_by_teacher($teacher_data->ID);
														$classname = "";
														foreach($classes as $class)
														{
															$classname .= mj_smgt_get_class_name($class['class_id']).",";
														}
														$classname_rtrim=rtrim($classname,", ");
														$classname_ltrim=ltrim($classname_rtrim,", ");
														echo $classname_ltrim;
													?>
													</label>
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<?php
						}
						//--- general tab End ----//
						//---  attendance tab start --//
						elseif($active_tab1 == "attendance")
						{
							$attendance_list = mj_smgt_monthly_attendence_teacher($teacher_id);
							if(!empty($attendance_list))
							{
								?>
								<script type="text/javascript">
									jQuery(document).ready(function($) {
										"use strict";
										jQuery('#attendance_list_detailpage').DataTable({
											//"order": [[ 1, "desc" ]],
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
													<th><?php esc_attr_e('Attendance By','school-mgt');?> </th>
													<th><?php esc_attr_e('Status','school-mgt');?> </th>  
													<th><?php esc_attr_e('Comment','school-mgt');?> </th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$attendance_list = mj_smgt_monthly_attendence_teacher($teacher_id);
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
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center">
																</p>
															</td>

															<td class=""><?php echo mj_smgt_get_user_name_byid($retrieved_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>
															
															<td class="name"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendence Date','school-mgt');?>"></i></td>
		
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
															<td> <?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
																<span style="color:<?php echo $status_color;?>;">
																	<?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
																</span> 
																<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Status','school-mgt');?>"></i></td>
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
							else
							{
								$page_1='attendance';
								$fattendance_1 = mj_smgt_get_userrole_wise_filter_access_right_array($page_1);
								if($fattendance_1['add']=='1')
								{
									?>
									<div class="no_data_list_div no_data_img_mt_30px"> 
										<a href="<?php echo home_url().'?dashboard=user&page=attendance&tab=teacher_attendance&tab1=teacher_attendences';?>">
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
						//---  attendance tab End --//

						//---  Class List tab Start --//
					elseif($active_tab1 == "class_list")
					{
						$classes = $teacher_obj->mj_smgt_get_class_by_teacher($teacher_id);
						if($classes)
						{
						?>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									"use strict";
									jQuery('#class_list_detailpage').DataTable({
										//stateSave: true,
										"responsive": true,
										//"order": [[ 1, "desc" ]],
										"aoColumns":[
													{"bSortable": false},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": false},],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',
										language:<?php echo mj_smgt_datatable_multi_language();?>
										});
										$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
									$('.dataTables_filter').addClass('search_btn_view_page');
								} );
							</script>
							<div class="table-div"><!-- PANEL BODY DIV START -->
								<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
									<table id="class_list_detailpage" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Class Name','school-mgt');?></th>
												<th><?php esc_attr_e('Section','school-mgt');?></th>
												<th><?php esc_attr_e('Class Numeric Value','school-mgt');?></th>  
												<th><?php esc_attr_e('Student Capacity','school-mgt');?> </th>  
												<th class="text_align_end"><?php esc_attr_e('Action','school-mgt');?> </th>  
											</tr>
										</thead>
										<tbody>
											<?php
											$i=0;
											
											if(!empty($classes))
											{
												foreach ($classes as $class_id)
												{
													$section_id = mj_smgt_get_section_by_class_id($class_id);
													$section_name = "";
													$retrieved_data = mj_smgt_get_class_data_by_class_id($class_id);

													if(!empty($retrieved_data))
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
														<td class="user_image width_50px"><img src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>" class="img-circle" /></td>
														<td ><?php if($retrieved_data->class_name){ echo $retrieved_data->class_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i></td>
														<td >
															<?php
															
																foreach($section_id as $section)
																{
																	$section_name.= $section->section_name.", ";
																}
																$section_name_rtrim=rtrim($section_name,", ");
																$section_name_ltrim=ltrim($section_name_rtrim,", ");
																if(!empty($section_name_ltrim))
																{
																	echo $section_name_ltrim;
																	
																}
																else
																{
																	esc_attr_e('No Section', 'school-mgt');;
																} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Section','school-mgt');?>" ></i></td>
														<td><?php if($retrieved_data->class_num_name){ echo $retrieved_data->class_num_name; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Numeric Name','school-mgt');?>" ></i></td>
														<?php
														$class_id=$retrieved_data->class_id;
														$user=count(get_users(array(
															'meta_key' => 'class_name',
															'meta_value' => $class_id
														)));
														?>
														<td>
														<?php	
															echo $user.' ';
															esc_attr_e('Out Of', 'school-mgt');
															echo ' '.$retrieved_data->class_capacity;
														?>	
														<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Capacity','school-mgt');?>" ></i></td>
														<td class="action">  
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			<li class="float_left_width_100 ">
																				<a class="float_left_width_100" href="?dashboard=user&page=class&tab=class_wise_student_list&class_id=<?php echo $retrieved_data->class_id;?>"><i class="fa fa-list"></i><?php esc_attr_e('Student List','school-mgt');?></a>
																			</li>
																		</ul>
																	</li>
																</ul>
															</div>	
														</td>
														</tr>
														<?php
														$i++;
													}
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
						?>
							<div class="calendar-event-new"> 
								<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
							</div>	
						<?php
						}
					}
					//---  Class List tab End --//

						//---- class schedule tab start ----//
						elseif($active_tab1 == "schedule")
						{
							?>
								<div id="Section1" class="">
									<div class="row">
										<div class="col-lg-12">
											<div class="">
												<div class="class_border_div card-content">
													<table class="table table-bordered">
														<?php 
														foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
														{	?>
														<tr>
													<th width="100"><?php echo $dayname;?></th>
														<td>
															<?php
																
																$period_1 = $obj_route->mj_smgt_get_periad_by_teacher($teacher_data->ID,$daykey);
																$period_2 = $obj_route->mj_smgt_get_periad_by_particular_teacher($teacher_data->ID,$daykey);
																if(!empty($period_1))
																{
																	$period = array_merge($period_1, $period_2);
																}
																if(!empty($period))
																	foreach($period as $period_data)
																	{
																		echo '<div class="btn-group m-b-sm">';
																		echo '<button class="btn btn-primary class_list_button dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
																		
																		$start_time_data = explode(":", $period_data->start_time);
																		$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																		$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																		$start_am_pm=$start_time_data[2];
																		
																		$end_time_data = explode(":", $period_data->end_time);
																		$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																		$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																		$end_am_pm=$end_time_data[2];
																		echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
																		
																		echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';
																		echo '</span></span><span class="caret"></span></button>';
																		echo '<ul role="menu" class="dropdown-menu">
																				<li><a href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit','school-mgt').'</a></li>
																				<li><a href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>
																			</ul>';
																		echo '</div>';					
																	}
																?>
															</td>
														</tr>
														<?php	
														}
														?>
													</table>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php
						}
						//---- class schedule tab End ----//
						?>	
					</div><!-- END PANEL BODY DIV-->
				</section>
				<!-- Detail Page Body Content Section End -->
			</div><!-- END CONTENT BODY DIV-->
		</div><!-- END PANEL BODY DIV-->
		<?php
	}
	?>
</div>