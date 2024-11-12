<?php ?>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>
<?php
// wp_enqueue_script('jquery-ui-datepicker');
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content admission_popup">
		<div class="modal-content">
			<div class="result"></div>
		</div>
    </div>    
</div>
<!-- POP up code end -->
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role_name=mj_smgt_get_user_role(get_current_user_id());
$active_tab = isset($_GET['tab'])?$_GET['tab']:'admission_list';
$obj_admission=new smgt_admission;
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
//------------- SAVE STUDENT ADMISSION FORM ------------------//

if(isset($_POST['student_admission']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_admission_form' ) )
	{
		$role=$_POST['role'];
		if(isset($_FILES['father_doc']) && !empty($_FILES['father_doc']) && $_FILES['father_doc']['size'] !=0)
		{			
			if($_FILES['father_doc']['size'] > 0)
				$upload_docs=mj_smgt_load_documets_new($_FILES['father_doc'],$_FILES['father_doc'],$_POST['father_document_name']);		
		}
		else
		{
			$upload_docs='';
		}
		$father_document_data=array();
		if(!empty($upload_docs))
		{
			$father_document_data[]=array('title'=>$_POST['father_document_name'],'value'=>$upload_docs);
		}
		else
		{
			$father_document_data[]='';
		}
		
		if(isset($_FILES['mother_doc']) && !empty($_FILES['mother_doc']) && $_FILES['mother_doc']['size'] !=0)
		{			
			if($_FILES['mother_doc']['size'] > 0)
				$upload_docs1=mj_smgt_load_documets_new($_FILES['mother_doc'],$_FILES['mother_doc'],$_POST['mother_document_name']);		
		}
		else
		{
			$upload_docs1='';
		}
		$mother_document_data=array();
		if(!empty($upload_docs1))
		{
			$mother_document_data[]=array('title'=>$_POST['mother_document_name'],'value'=>$upload_docs1);
		}
		else
		{
			$mother_document_data[]='';
		}
		if ($_REQUEST['action']=='edit')
		{
			//----------EDIT-------------//
			$result= $obj_admission->mj_smgt_add_admission($_POST,$father_document_data,$mother_document_data,$role);

		 	if($result)
			{   
				wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=9');
				
			} 
		}
		else
		{
			//-------- Email Check --------//
			if(email_exists($_POST['email']))
			{
				wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_form&message=2');
			} 
			elseif(email_exists($_POST['father_email']))
			{
				wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_form&message=3');
			}
			elseif(email_exists($_POST['mother_email']))
			{
				wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_form&message=4');
				
			}
			else
			{
				// wp_redirect ( admin_url().'admin.php?page=smgt_admission&tab=admission_list&message=1'); 
				//----------ADD-------------//
				  $result= $obj_admission->mj_smgt_add_admission($_POST,$father_document_data,$mother_document_data,$role);
			 
			 	if($result)
				{   
                    wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=1');					
				} 
			}
	    }
	}

}
//------------- DELETE ADMISSION  ------------------//
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
		wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=8');
	}
}

// -----------Delete Code--------
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{		
	$result=mj_smgt_delete_usedata($_REQUEST['student_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=8');
	}
}
//------------ ACTIVE ADMISSION ------------//
if(isset($_POST['active_user_admission']))
{		
		$userbyroll_no	=	get_users(
			array('meta_query'	=>
				array('relation' => 'AND',
					array('key'	=>'class_name','value'=>$_POST['class_name']),
					array('key'=>'roll_id','value'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']))
				),
				'role'=>'student')
		);
		$is_rollno = count($userbyroll_no);	
		
		if($is_rollno)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=6');
		}
		else
		{		
			$active_user_id		= 	$_REQUEST['act_user_id'];
			update_user_meta($active_user_id, 'roll_id', $_REQUEST['roll_id']);
			update_user_meta($active_user_id, 'class_name', $_REQUEST['class_name']);
			update_user_meta($active_user_id, 'class_section', $_REQUEST['class_section']);
			if( email_exists($_REQUEST['email'] ) )
			{ // if the email is registered, we take the user from this
				if( !empty($_REQUEST['password']) )
					wp_set_password($_REQUEST['password'], $active_user_id );
			}
			 
			$user_info 	= 	get_userdata($_POST['act_user_id']);
			if(!empty($user_info))
			{
				//--------- SEND STUDENT MAIL ACTIVE ACCOUNT -----------//	
				if( isset($_POST['student_approve_mail']) && ($_POST['student_approve_mail'] == 1))
				{
					// STUDENT APPROVE MAIL FOR STUDENT
					$string = array();
					$string['{{user_name}}']   =  $user_info->display_name;
					$string['{{school_name}}'] =  get_option('smgt_school_name');
					$string['{{role}}']        =  "student";
					$string['{{login_link}}']  =  site_url() .'/index.php/school-management-login-page';
					$string['{{username}}']    =  $user_info->user_login;
					$string['{{class_name}}']  =  smgt_get_class_section_name_wise($_REQUEST['class_name'],$_REQUEST['class_section']);
					$string['{{roll_no}}']  =  $_REQUEST['roll_id'];
					$string['{{email}}']  	   =  $user_info->user_email;
					$string['{{Password}}']    =  $_REQUEST['password'];
								
					$MsgContent                =  get_option('add_approve_admission_mail_content');		
					$MsgSubject				   =  get_option('add_approve_admisson_mail_subject');
					$message = mj_smgt_string_replacement($string,$MsgContent);
					$MsgSubject = mj_smgt_string_replacement($string,$MsgSubject);
					
					$email= $user_info->user_email;
					mj_smgt_send_mail($email,$MsgSubject,$message); 

					// STUDENT APPROVE MAIL FOR PARENT
					if((!empty($user_info->father_email)) && (!empty($user_info->father_first_name)))
					{
						$string_parent = array();
						$string_parent['{{parent_name}}']   =  $user_info->father_first_name." ".$user_info->father_middle_name." ".$user_info->father_last_name;
						$string_parent['{{student_name}}']   =  $user_info->display_name;
						$string_parent['{{school_name}}'] =  get_option('smgt_school_name');
						$string_parent['{{role}}']        =  "student";
						$string_parent['{{login_link}}']  =  site_url() .'/index.php/school-management-login-page';
						$string_parent['{{username}}']    =  $user_info->user_login;
						$string_parent['{{class_name}}']  =  smgt_get_class_section_name_wise($_REQUEST['class_name'],$_REQUEST['class_section']);
						$string_parent['{{roll_no}}']  =  $_REQUEST['roll_id'];
						$string_parent['{{email}}']  	   =  $user_info->user_email;
						$string_parent['{{Password}}']    =  $_REQUEST['password'];

						$MsgContent_parent                =  get_option('admission_mailtemplate_content_for_parent');		
						$MsgSubject_parent				   =  get_option('admissiion_approve_subject_for_parent');
						$message = mj_smgt_string_replacement($string_parent,$MsgContent_parent);
						$MsgSubject = mj_smgt_string_replacement($string_parent,$MsgSubject_parent);
						$email_parent= $user_info->father_email;
						mj_smgt_send_mail($email_parent,$MsgSubject,$message); 
					}
					if((!empty($user_info->mother_email)) and (!empty($user_info->mother_first_name)))
					{
						$string_parent = array();
						$string_parent['{{parent_name}}']   =  $user_info->mother_first_name." ".$user_info->mother_middle_name." ".$user_info->mother_last_name;
						$string_parent['{{student_name}}']   =  $user_info->display_name;
						$string_parent['{{school_name}}'] =  get_option('smgt_school_name');
						$string_parent['{{role}}']        =  "student";
						$string_parent['{{login_link}}']  =  site_url() .'/index.php/school-management-login-page';
						$string_parent['{{username}}']    =  $user_info->user_login;
						$string_parent['{{class_name}}']  =  smgt_get_class_section_name_wise($_REQUEST['class_name'],$_REQUEST['class_section']);
						$string_parent['{{roll_no}}']  =  $_REQUEST['roll_id'];
						$string_parent['{{email}}']  	   =  $user_info->user_email;
						$string_parent['{{Password}}']    =  $_REQUEST['password'];

						$MsgContent_parent                =  get_option('admission_mailtemplate_content_for_parent');		
						$MsgSubject_parent				   =  get_option('admissiion_approve_subject_for_parent');
						$message = mj_smgt_string_replacement($string_parent,$MsgContent_parent);
						$MsgSubject = mj_smgt_string_replacement($string_parent,$MsgSubject_parent);
						$email_parent= $user_info->mother_email;
						mj_smgt_send_mail($email_parent,$MsgSubject,$message); 
					}
				}
				//--------- SEND APPROVE SMS NOTIFICATION  -----------//	
				if( isset($_POST['student_approve_sms']) && ($_POST['student_approve_sms'] == 1))
				{
					$message_content 	= 	'Your admission has been successful approved with '.get_option('smgt_school_name');
					$type = "notice";
					MJ_smgt_send_sms_notification($user_info->ID,$type,$message_content);
				}
			}	
				 
			$role_upadte="student";
			$status="Approved";
			$result = new WP_User($active_user_id);
			$result->set_role($role_upadte);
			$result=update_user_meta($active_user_id, 'role', $role_upadte );
			$result=update_user_meta($active_user_id, 'status', $status );     
			$role_parents="parent"; 
			
			//---------- ADD PARENTS -------------------//
			$patents_add=$obj_admission->mj_smgt_add_parent($active_user_id,$role_parents); 
			
		if(get_user_meta($active_user_id, 'hash', true))  
		{
			delete_user_meta($active_user_id, 'hash'); 
		}
		wp_redirect ( home_url() . '?dashboard=user&page=admission&tab=admission_list&message=7');			
	}
}
if(isset($_REQUEST['message']))
{
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Admission Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Student Email-id Already Exist.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Father Email-id Already Exist.','school-mgt');
			break;	
		case '4':
			$message_string = esc_attr__('Mother Email-id Already Exist.','school-mgt');
			break;	
		case '5':
			$message_string = esc_attr__('Admission Added Successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Student Roll No. Already Exist.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Student Record Approved Successfully.','school-mgt');
			break;
		case '8':
		   $message_string = esc_attr__('Admission Deleted Successfully.','school-mgt');
		   break;
        case '9':
			$message_string = esc_attr__('Admission Updated Successfully.','school-mgt');
			break;  
	}
	if($message)
	{ ?>
		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<p><?php echo $message_string;?></p>
		</div>
	  <?php 
	} 
}
?>

<!-- Nav tabs -->
<div class="panel-body panel-white frontend_list_margin_30px_res">
    <!-- Tab panes -->
	<?php
	//---------------- Admission List Tab  -----------------//
	if($active_tab == 'admission_list')
	{
		if($school_obj->role == 'supportstaff' || $school_obj->role == 'teacher')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$user_id=get_current_user_id();
			     $studentdata= get_users(
							 array(
									'role' => 'student_temp',
									'meta_query' => array(
									array(
											'key' => 'created_by',
											'value' => $user_id,
											'compare' => '='
										)
									)
							));
			}
			else
			{
				$studentdata =get_users(array('role'=>'student_temp'));
			}
		}
		else
		{
			$studentdata=get_users(array('role'=>'student_temp')); 
		}
		
		if(!empty($studentdata))
		{
			?>
			<script>
				jQuery(document).ready(function() 
				{
					var table =  jQuery('#admission_list_front').DataTable({
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
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": false}],
					language:<?php echo mj_smgt_datatable_multi_language();?>
					});
					$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
					jQuery('#checkbox-select-all').on('click', function(){     
						var rows = table.rows({ 'search': 'applied' }).nodes();
						jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
					//------------- multiple delete js -----------//
					$(".delete_selected").on('click', function()
					{	
						if ($('.select-checkbox:checked').length == 0 )
						{
							alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");
							return false;
						}
						else
						{
							var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?','school-mgt');?>");
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

			<div class="panel-body"><!--------- PENAL BODY DIV --------->
				<div class="table-responsive"><!---------TABLE RESPONSIVE DIV --------->
					<!----------- ADMISSION LIST FORM START ---------->
					<form id="frm-example" name="frm-example" method="post">
						<table id="admission_list_front" class="display admin_student_datatable display" width="100%">
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
									<th><?php  _e('Photo', 'school-mgt') ;?></th>
									<th><?php echo esc_attr_e('Name & Email', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Mobile No.', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Admission No.', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Admission Date', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Gender', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Date of Birth', 'school-mgt') ;?></th>
									<th> <?php echo esc_attr_e('Status', 'school-mgt') ;?></th>
									<th class="text_align_end"><?php  _e('Action', 'school-mgt') ;?></th>
								</tr>
							</thead>	
							<tbody>
								<?php 
								if(!empty($studentdata))
								{
									foreach ($studentdata as $retrieved_data)
									{
										$user_info = get_userdata($retrieved_data->ID);
										
										?>
										<tr>
											<?php
											if($role_name == "supportstaff")
											{
												?>
												<td class="checkbox_width_10px"><input type="checkbox" name="id[]" class="smgt_sub_chk select-checkbox" value="<?php echo esc_attr($retrieved_data->ID); ?>"></td>
												<?php
											}
											?>
											<td class="user_image width_50px">
												<a href="?dashboard=user&page=admission&tab=view_admission&action=view_admission&id=<?php echo $retrieved_data->ID;?>">
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
												<a class="color_black" href="?dashboard=user&page=admission&tab=view_admission&action=view_admission&id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->first_name.' '.$retrieved_data->middle_name.' '.$retrieved_data->last_name;?></a>
												<br>
												<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
											</td>
											<td class="">+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?> <?php echo $user_info->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile No.','school-mgt');?>" ></i></td>
											<td class=""><?php echo $user_info->admission_no; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Admission No.','school-mgt');?>" ></i></td>
											<td class=""><?php echo mj_smgt_getdate_in_input_box($user_info->admission_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Admission Date','school-mgt');?>" ></i></td>
											<td class=""><?php echo esc_attr_e(ucfirst($user_info->gender),'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Gender','school-mgt');?>" ></i></td>
											<td class=""><?php echo mj_smgt_getdate_in_input_box($user_info->birth_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date of Birth','school-mgt');?>" ></i></td>
											<?php
												
											?>
											<td class=""><span class="not_approved"><?php if(!empty($user_info->status)){ echo esc_attr_e($user_info->status,'school-mgt'); }else{ echo esc_attr_e('Not Approved','school-mgt')  ; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i></span></td>
											<td class="action">  
												<div class="smgt-user-dropdown">
													<ul class="" style="margin-bottom: 0px !important;">
														<li class="">
															<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
															</a>
															<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																<li class="float_left_width_100">
																	<a href="?dashboard=user&page=admission&tab=view_admission&action=view_admission&id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a> 
																</li>
																<?php
																if($user_info->role =="student_temp" AND $user_access['add']=='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=smgt_admission&tab=admission_list&action=approve&id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100 show-admission-popup" student_id="<?php echo $retrieved_data->ID; ?>"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/admission_approve.png"?>" style="height:14px;">&nbsp;&nbsp;&nbsp;<?php esc_html_e('Approve', 'school-mgt' ) ;?></a>
																	</li>
																	<?php 
																}
																if($user_access['edit'] =='1')
																{
																	?>
																		<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=admission&tab=addadmission&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																	</li>

																	<?php 
																} 
																if($user_access['delete'] =='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=admission&tab=admission_list&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
									} 
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
									<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
								</button>
									<?php 
								if($user_access['delete'] =='1')
								{
									 ?>
									<button data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
									<?php 
								} ?>
							</div>
							<?php
						}
						?>
					</form><!----------- ADMISSION LIST FORM END ---------->
				</div><!---------TABLE RESPONSIVE DIV --------->
			</div><!--------- PENAL BODY DIV --------->
			<?php
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=admission&tab=addadmission';?>">
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
	//----------- ADMISSION VIEW PAGE TAB  ----------------//
	if($active_tab == 'view_admission')
	{
		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
		$student_data=get_userdata($_REQUEST['id']);
		$user_meta =get_user_meta($_REQUEST['id'], 'parent_id', true); 
		$custom_field_obj = new Smgt_custome_field;								
		$module='student';	
		$user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
		$sibling_information_value=str_replace('"[','[',$student_data->sibling_information);
		$sibling_information_value1=str_replace(']"',']',$sibling_information_value);
		$sibling_information=json_decode($sibling_information_value1);
		?>
		<!-- POP up code -->
		<div class="popup-bg">
			<div class="overlay-content content_width">
				<div class="modal-content d-modal-style">
					<div class="task_event_list">
					</div>
				</div>
			</div>
		</div>
		<!-- POP up code -->
	
		<div class="panel-body view_page_main"><!-- START PANEL BODY DIV-->
			<div class="content-body">
				<!-- Detail Page Header Start -->
				<section id="user_information" class="view_page_header_bg">
					<div class="view_page_header_bg">
						<div class="row">
							<div class="col-xl-10 col-md-9 col-sm-10">
								<div class="user_profile_header_left float_left_width_100">
									<?php
									$umetadata=mj_smgt_get_user_image($student_data->ID);
									if(empty($umetadata))
									{
										echo '<img src='.get_option( 'smgt_student_thumb_new' ).' class="user_view_profile_image" />';
									}
									else
									{
										echo '<img src='.$umetadata.' class="user_view_profile_image" />';
									}
									?>
									<div class="row profile_user_name">
										<div class="float_left view_top1">
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<label class="view_user_name_label"><?php echo esc_html($student_data->display_name);?></label>
												<div class="view_user_edit_btn">
												<?php 
												if($user_access['edit']=='1')
												{
													?>
													<a class="color_white margin_left_2px" href="?dashboard=user&page=admission&tab=addadmission&action=edit&student_id=<?php echo $student_data->ID;?>">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
													</a>
												<?php }	?>
												</div>
											</div>
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<div class="view_user_phone float_left_width_100">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $student_data->mobile_number;?></label>
												</div>
											</div>
										</div>
									</div>
									<div id="res_add_width" class="row">
										<div class="col-xl-12 col-md-12 col-sm-12">
											<div class="view_top2">
												<div class="row view_user_doctor_label">
													<div class="col-md-12 address_student_div">
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $student_data->address; ?></label>
													</div>		
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-md-3 col-sm-2 group_thumbs">
								<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Group.png"?>" class="group_img_rtl">
							</div>
						</div>
					</div>
				</section>
				<!-- Detail Page Header End -->


				<!-- Detail Page Body Content Section  -->
				<section id="body_area" class="">
					<div class="panel-body"><!-- START PANEL BODY DIV-->
						<?php 
						// general tab start 
						if($active_tab1 == "general")
						{
							?>
							<div class="row margin_top_15px margin_left_3">
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
									<label class="word_brack view_page_content_labels"> <?php echo $student_data->user_email; ?> </label>
								</div>
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Admission Number', 'school-mgt'); ?> </label><br/>
									<label class="word_brack view_page_content_labels"><?php echo $student_data->admission_no;?> </label>	
								</div>
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Admission Date', 'school-mgt'); ?> </label><br/>
									<label class="word_brack view_page_content_labels"> <?php echo  mj_smgt_getdate_in_input_box($student_data->admission_date); ?> </label>
								</div>

								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Previous School', 'school-mgt'); ?> </label><br/>
									<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->preschool_name)){ echo $student_data->preschool_name; }else{ echo "N/A"; } ?> </label>	
								</div>
							</div>
							<!-- student Information div start  -->
							<div class="row margin_top_20px">
								<div class="col-xl-12 col-md-12 col-sm-12">
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Student Information', 'school-mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Full Name', 'school-mgt'); ?> </label> <br>
													<label class="word_brack view_page_content_labels"><?php echo $student_data->display_name; ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>
													<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->alternet_mobile_number)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->alternet_mobile_number; }else{ echo "N/A"; } ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels">
														<?php 
														if($student_data->gender=='male') 
															echo esc_attr__('Male','school-mgt');
														elseif($student_data->gender=='female') 
															echo esc_attr__('Female','school-mgt');
														elseif($student_data->gender=='other') 
															echo esc_attr__('Other','school-mgt');
														else
															echo "N/A";
														?>
													</label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
													<label class="word_brack view_page_content_labels"><?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
													<label class="word_brack view_page_content_labels"><?php echo $student_data->city; ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
													<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->state)){ echo $student_data->state; }else{ echo "N/A"; } ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 address_rs_css margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zipcode', 'school-mgt'); ?> </label><br>
													<label class="word_brack view_page_content_labels"><?php echo $student_data->zip_code; ?></label>
												</div>
											</div>
										</div>	
									</div>

									<!-- Sibling Information  -->
									<?php
							
								if(!empty($sibling_information[0]->siblingsstudent))
								{
									?>
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<div class="guardian_div">
										<label class="view_page_label_heading"> <?php esc_html_e('Siblings Information', 'school-mgt'); ?> </label>
											<?php
											
											foreach($sibling_information as $value)
											{
												$sibling_data=get_userdata($value->siblingsstudent);

												if(!empty($sibling_data))
												{
													
													?>
													<div class="row">
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Sibling Name', 'school-mgt'); ?> </label> <br>
														<label class="word_brack view_page_content_labels"><?php  echo mj_smgt_student_display_name_with_roll($sibling_data->ID);?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Sibling Email', 'school-mgt'); ?> </label> <br>
														<label class="word_brack view_page_content_labels"><?php echo $sibling_data->user_email;?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Class', 'school-mgt'); ?> </label><br>
														<label class="word_brack ftext_style_capitalization view_page_content_labels"><?php echo smgt_get_class_section_name_wise($value->siblingsclass,$value->siblingssection); ?></label>
													</div>
													<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
														<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br>
														<label class="word_brack view_page_content_labels"><?php if(!empty($sibling_data->mobile_number)){ echo "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.$sibling_data->mobile_number;}else{ echo 'N/A'; }?></label>
													</div>
												</div>
													<?php
												}
												
											}
											?>
										</div>	
									</div>
									<?php
								}
								?>
									
									<!-- other information div start  -->
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<?php
										if($student_data->parent_status == 'Father' || $student_data->parent_status == 'Both')
										{
											if(!empty($student_data->father_first_name))
											{
												?>
												<div class="guardian_div">
													<label class="view_page_label_heading"> <?php esc_html_e('Father Information', 'school-mgt'); ?> </label>
													<div class="row">
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Name', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php echo $student_data->fathersalutation.' '.$student_data->father_first_name.' '.$student_data->father_middle_name.' '.$student_data->father_last_name; ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Email', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_email)){ echo $student_data->father_email; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels font_transfer_capitalize"><?php if(!empty($student_data->fathe_gender)){ echo esc_html_e($student_data->fathe_gender,"school-mgt"); }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_birth_date)){ echo mj_smgt_getdate_in_input_box($student_data->father_birth_date); }else{ echo "N/A"; }  ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Address', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_address)){ echo $student_data->father_address; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_state_name)){ echo $student_data->father_state_name; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_city_name)){ echo $student_data->father_city_name; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_zip_code)){ echo $student_data->father_zip_code; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile No.', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_mobile)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->father_mobile; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('School Name', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_school)){ echo $student_data->father_school; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Medium of Instruction', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_medium)){ echo $student_data->father_medium; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Qualification', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_education)){ echo $student_data->father_education; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Annual Income', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->fathe_income)){ echo mj_smgt_get_currency_symbol().''.$student_data->fathe_income; }else{ echo "N/A"; } ?></label>
														</div>

														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Occupation', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->father_occuption)){ echo $student_data->father_occuption; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Proof of Qualification', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels">
																<?php
																$father_doc=str_replace('"[','[',$student_data->father_doc);
																$father_doc1=str_replace(']"',']',$father_doc);
																$father_doc_info=json_decode($father_doc1);
																?>
																<p class="user-info"> 
																<?php if (!empty($father_doc_info[0]->value))
																{ 
																	?>
																	<a download href="<?php print content_url().'/uploads/school_assets/'.'$father_doc_info[0]->value;' ?>"  class="status_read btn btn-default"><i class="fa fa-download"></i><?php
																	if(!empty($father_doc_info[0]->title))
																	{									
																		echo $father_doc_info[0]->title;
																	}
																	else
																	{
																		esc_html_e(' Download', 'school-mgt');
																	}
																	?>
																	</a>
																	<?php
																}
																else
																{
																	echo "N/A";
																}
																?>
															</label>
														</div>
														
													</div>
												</div>	
												<br>
												<?php
											}
										}
										?>
										<?php
										if($student_data->parent_status == 'Mother' || $student_data->parent_status == 'Both')
										{
											if(!empty($student_data->mother_first_name))
											{
												?>
												<div class="guardian_div">
													<label class="view_page_label_heading"> <?php esc_html_e('Mother Information', 'school-mgt'); ?> </label>
													<div class="row">
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Name', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php echo $student_data->mothersalutation.' '.$student_data->mother_first_name.' '.$student_data->mother_middle_name.' '.$student_data->mother_last_name; ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Email', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_email)){ echo $student_data->mother_email; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels font_transfer_capitalize"><?php if(!empty($student_data->mother_gender)){ echo esc_html_e($student_data->mother_gender,"school-mgt"); }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_birth_date)){ echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date); }else{ echo "N/A"; }  ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Address', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_address)){ echo $student_data->mother_address; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_state_name)){ echo $student_data->mother_state_name; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_city_name)){ echo $student_data->mother_city_name; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_zip_code)){ echo $student_data->mother_zip_code; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Mobile No.', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_mobile)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $student_data->mother_mobile; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('School Name', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_school)){ echo $student_data->mother_school; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Medium of Instruction', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_medium)){ echo $student_data->mother_medium; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Qualification', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_education)){ echo $student_data->mother_education; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Annual Income', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_income)){ echo mj_smgt_get_currency_symbol().''.$student_data->mother_income; }else{ echo "N/A"; } ?></label>
														</div>

														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Occupation', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels"><?php if(!empty($student_data->mother_occuption)){ echo $student_data->mother_occuption; }else{ echo "N/A"; } ?></label>
														</div>
														<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
															<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Proof of Qualification', 'school-mgt'); ?> </label><br>
															<label class="word_brack view_page_content_labels">
																<?php
																$mother_doc=str_replace('"[','[',$student_data->mother_doc);
																$mother_doc1=str_replace(']"',']',$mother_doc);
																$mother_doc_info=json_decode($mother_doc1);
																?>
																<p class="user-info">  
																<?php if (!empty($mother_doc_info[0]->value))
																{
																	?>
																	<a download href="<?php print content_url().'/uploads/school_assets/'.'$mother_doc_info[0]->value;' ?>"  class=" btn btn-default" <?php if (empty($mother_doc_info[0])) { ?> disabled <?php } ?>><i class="fa fa-download"></i>
																	<?php
																	if(!empty($mother_doc_info[0]->title))
																	{									
																		echo $mother_doc_info[0]->title;
																	}
																	else
																	{
																		esc_html_e(' Download', 'school-mgt');
																	}
																	?></a>
																	<?php 
																} 
																else
																{
																	echo "N/A";
																}
																?>
															</label>
														</div>
														
													</div>
												</div>	
												<?php
											}
										}
										?>
									</div>
								</div>
							</div>
							<?php
							}
							?>
					</div><!-- END PANEL BODY DIV-->
				</section>
				<!-- Detail Page Body Content Section End -->
			</div>
		</div>
		<?php
	}	
	//-------------- ADD ADMISSION TAB ---------------//
	if($active_tab == 'addadmission')
	{
		$role='student_temp'; 
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$student_data = get_userdata($_REQUEST['student_id']);
			$user_ID = (int)$_REQUEST['student_id'];
			$key = 'status';
			$single = true;
			$user_status = get_user_meta( $user_ID, $key, $single );
			$sibling_data = $student_data->sibling_information;
			$sibling = json_decode($sibling_data);
		} 
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
		<!--Group POP up code -->
		<script>

			jQuery(document).ready(function($)
			{
				
				jQuery("body").on("change", ".input-file[type=file]", function ()
				{ 
					"use strict";
					var elmId = $(this).attr("name");
					var file = this.files[0]; 		
					var ext = $(this).val().split('.').pop().toLowerCase(); 
					//Extension Check 
					if($.inArray(ext, [,'pdf','doc','docx','gif','png','jpg','jpeg','']) == -1)
					{
						alert('Only pdf,doc,docx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
						$(this).replaceWith('<input class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file" name="'+elmId+'" value=""  type="file" />');
						return false; 
					} 
					//File Size Check 
					if (file.size > 20480000) 
					{
						alert(language_translate2.large_file_Size_alert);
						$(this).replaceWith('<input class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file" name="'+elmId+'" value=""  type="file" />');
						return false; 
					}
				});
				
				
				jQuery('.birth_date').datepicker({
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
		$('.sdate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",}); 
		$('.edate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",}); 
	
			jQuery('#admission_date').datepicker({
					maxDate: 0,
					dateFormat: "<?php echo get_option('smgt_datepicker_format'); ?>",
					changeMonth: true,
					changeYear: true,
					yearRange: '-65:+25',
					onChangeMonthYear: function(year, month, inst) {
						jQuery(this).val(month + "/" + year);
					}
			});
			});	
			function add_more_siblings()
			{	
				var click_val = $(".click_value").val();
				var curr_data = {
					action: 'mj_smgt_load_siblings_dropdown',
					click_val: click_val,
					dataType: 'json'
				};
				$.post(smgt.ajax, curr_data, function(response) 
				{
					var value =  parseInt(click_val) + 1;
					$(".click_value").val(value);
					$("#sibling_div").append(response);
				});	
			}

			function deleteParentElement(n)
			{
				var alert = confirm(language_translate2.delete_record_alert);
					if (alert == true){
						n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
					}
			}	
		</script>
   		<!----------- addadmission form design  ------------->
   		<div class="panel-body margin_top_40">	
       		<form name="admission_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="admission_form">
        		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="role" value="<?php echo $role;?>"  />
				<input type="hidden" name="user_id" value="<?php if($edit){ echo $_REQUEST['student_id'];}?>"  />
				<input type="hidden" name="status" value="<?php if($edit){ echo $user_status;}?>"  />

		  		<!--- Hidden User and password --------->
				<input id="username" type="hidden"  name="username">
				<input id="password" type="hidden"  name="password">
				
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Admission Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"> <!------  Form Body -------->
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="admission_no" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $student_data->admission_no;}elseif(isset($_POST['admission_no'])){ echo mj_smgt_generate_admission_number(); }else{ echo mj_smgt_generate_admission_number(); }?>"  name="admission_no">		
									<label for="userinput1" class=""><?php esc_html_e('Admission Number','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="admission_date" class="form-control validate[required]" type="text"  name="admission_date" readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->admission_date);}elseif(isset($_POST['admission_date'])) echo mj_smgt_getdate_in_input_box($_POST['admission_date']);else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d")); } ?>">
									<label for="userinput1" class=""><?php esc_html_e('Admission Date','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<?php
						if(get_option("smgt_admission_fees") == "yes")
						{
							$fees_id = get_option('smgt_admission_amount');
				            $obj_fees= new Smgt_fees();
						    $amount=$obj_fees->mj_smgt_get_single_feetype_data_amount($fees_id);
							?>
							<div class="col-md-6 error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 form-control">
									<input id="admission_fees" name="admission_fees" disabled class="form-control" type="text" readonly value="<?php echo mj_smgt_get_currency_symbol() .' '. $amount ?>">
									<label for="userinput1" class="active"><?php esc_html_e('Admission Fees','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
							<input id="admission_fees" class="form-control" type="hidden" name="admission_fees_id"  value="<?php echo $fees_id; ?>">
						    <input id="" class="form-control" type="hidden" name="admission_fees_amount" value="<?php echo $amount;?>">
							<?php
						}
						?>
					</div>
				</div> <!------  Form Body -------->
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Student Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="first_name" value="<?php if($edit){ echo $student_data->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  name="middle_name"  value="<?php if($edit){ echo $student_data->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="last_name" value="<?php if($edit){ echo $student_data->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="birth_date" class="form-control date_picker validate[required] birth_date" type="text"  name="birth_date"  readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->birth_date);}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
									<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 res_margin_bottom_20px rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?><span class="required">*</span></label>													
											<div class="d-inline-block">
												<?php $genderval = "male"; if($edit){ $genderval=$student_data->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
												<input type="radio"  value="male" name="gender"  class="custom-control-input" <?php  checked( 'male', $genderval);  ?>  id="male">
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
												&nbsp;&nbsp;<input type="radio" value="female"  name="gender" <?php  checked( 'female', $genderval);  ?> class="custom-control-input" id="female">
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
												&nbsp;&nbsp;<input type="radio" value="other"  name="gender" <?php  checked( 'other', $genderval);  ?> class="custom-control-input" id="other">
												<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text"  name="address" value="<?php if($edit){ echo $student_data->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
									<label for="userinput1"><?php esc_html_e('Address','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $student_data->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
									<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $student_data->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
									<label for="userinput1"><?php esc_html_e('City','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="zip_code" class="form-control validate[required,custom[phone_number],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="zip_code" value="<?php if($edit){ echo $student_data->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
									<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
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
											<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number" value="<?php if($edit){ echo $student_data->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
											<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?><span class="required red">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="alter_mobile_number">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number" value="<?php if($edit){ echo $student_data->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
											<label for="userinput6"><?php esc_html_e('Alternate Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-md-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="email" class="form-control validate[required,custom[email]] text-input email" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $student_data->user_email;}elseif(isset($_POST['user_email'])) echo $_POST['user_email'];?>">
									<label for="userinput1"><?php esc_html_e('Email','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="preschool_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="preschool_name" value="<?php if($edit){ echo $student_data->preschool_name;}elseif(isset($_POST['preschool_name'])) echo $_POST['preschool_name'];?>">
									<label for="userinput1"><?php esc_html_e('Previous School','school-mgt');?></label>
								</div>
							</div>
						</div>	
					</div>
				</div>
				<?php wp_nonce_field( 'save_admission_form' ); ?>
				<!--------------------- SIBLINGS DIV START ------------------------>
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Siblings Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12 form-control input_height_50px">
									<div class="row padding_radio">
										<div class="input-group input_checkbox ">
											<label class="custom-top-label"><?php esc_html_e('Siblings','school-mgt');?></label>													
											<div class="checkbox checkbox_lebal_padding_8px display_flex">
												<label class="display_flex">
													<input type="checkbox" id="chkIsTeamLead" 
													<?php 
													
													if($edit)
													{ 
														$sibling_data = $student_data->sibling_information;
														$sibling = json_decode($sibling_data);
														if(!empty($student_data->sibling_information))
														{ 
															foreach ($sibling as $value) 
															{
																if(!empty($value->siblingsclass) && !empty($value->siblingsstudent))
																{?> checked <?php } 
															}
														}
													} ?>/>&nbsp;&nbsp;<?php esc_html_e('In case of any sibling ? click here','school-mgt');?>
												</label>
											</div>
										</div>
									</div>												
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<?php
				if($edit)
				{
					if(!empty($student_data->sibling_information)) 
					{
						$sibling_data = $student_data->sibling_information;
						
						$sibling = json_decode($sibling_data);
						
						if(!empty($sibling))
						{
							$count_array=count($sibling);
						}
						else
						{
							$count_array=0;
						}
						$i=1;
						?>
						<div id="sibling_div" class="sibling_div_none">
							<?php
							foreach ($sibling as $value) 
							{
								?>
								<script>
							jQuery(document).ready(function($)
							{
								$("body").on("change", "#sibling_class_change_<?php echo $i; ?>", function(event)
								{	
									$('#sibling_student_list_<?php echo $i; ?>').html('');

									var selection = $(this).val();
									var optionval = $(this);
									var curr_data = {
										action: 'mj_smgt_load_user',
										class_list: selection,
										dataType:'json'
									};
										
									$.post(smgt.ajax, curr_data, function(response) 
									{
										$('#sibling_student_list_<?php echo $i;?>').append(response);	
									});	
								});
								$("body").on("change", "#sibling_class_change_<?php echo $i; ?>", function(){	
									$('#sibling_class_section_<?php echo $i; ?>').html('');
									$('#sibling_class_section_<?php echo $i; ?>').append('<option value="remove">Loading..</option>');
									var selection = $("#sibling_class_change_<?php echo $i; ?>").val();
									var optionval = $(this);
									var curr_data = {
										action: 'mj_smgt_load_class_section',
										class_id: selection,			
										dataType: 'json'
									};
									$.post(smgt.ajax, curr_data, function(response) 
									{
										$("#sibling_class_section_<?php echo $i;?> option[value='remove']").remove();
										$('#sibling_class_section_<?php echo $i;?>').append(response);	
									});					
								});
								$("#sibling_class_section_<?php echo $i;?>").on('change',function()
								{
									$('#sibling_student_list_<?php echo $i;?>').html('');
										var selection = $(this).val();
										var class_id = $("#sibling_class_change_<?php echo $i; ?>").val();	
										var optionval = $(this);
										var curr_data = {
											action: 'mj_smgt_load_section_user',
											section_id: selection,			
											class_id: class_id,			
											dataType: 'json'
										};
												
										$.post(smgt.ajax, curr_data, function(response) 
										{
											$('#sibling_student_list_<?php echo $i; ?>').append(response);	
										});				
								});
							});
							</script>
								<input type="hidden" id="admission_sibling_id" name="admission_sibling_id" value="<?php echo $count_array; ?>"  />
								<div class="form-body user_form">
									<div class="row">
										<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 input smgt_form_select">
											<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
											<select name="siblingsclass[]" class="line_height_30px form-control validate[required] class_in_student max_width_100" id="sibling_class_change_<?php echo $i;?>">
												<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
												<?php
													foreach(mj_smgt_get_allclass() as $classdata)
													{  
														?>
														<option value="<?php echo $classdata['class_id'];?>" <?php selected($value->siblingsclass, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
														<?php 
													} 	?>
											</select>
										</div>
										<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input smgt_form_select">
											<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
											<select name="siblingssection[]" class="line_height_30px form-control max_width_100" id="sibling_class_section_<?php echo $i;?>">
												<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
												<?php
												if($edit)
												{
													foreach(mj_smgt_get_class_sections($value->siblingsclass) as $sectiondata)
													{  
														?>
														<option value="<?php echo $sectiondata->id;?>" <?php selected($value->siblingssection,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
														<?php 
													} 
												}
												?>
											</select>
										</div>
										<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input class_section_hide">
											<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?></label>
											<select name="siblingsstudent[]" id="sibling_student_list_<?php echo $i; ?>" class="line_height_30px form-control max_width_100">
												<option value=""><?php esc_attr_e('Select Student','school-mgt');?></option>
												<?php 
												if($edit)
												{
													if(mj_smgt_student_display_name_with_roll($value->siblingsstudent) != 'N/A'){
														echo '<option value="'.$value->siblingsstudent.'" '.selected($value->siblingsstudent,$value->siblingsstudent).'>'.mj_smgt_student_display_name_with_roll($value->siblingsstudent).'</option>';
													}
												}
												?>
											</select>           
										</div>
										<input type="hidden"  class="click_value" name="" value="<?php echo $count_array + 1; ?>">
										<?php
										if($i == 1)
										{
										?>
										<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_more_siblings()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
										</div>
										<?php
										}
										else
										{
											?>
											<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
												<input type="image" onclick="deleteParentElement(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"; ?>" class="rtl_margin_top_15px remove_cirtificate input_btn_height_width">
											</div>
											<?php
										}
										?>
									</div>
								</div>	
								<?php
								$i++;
							}
							?>
						</div>	
						<?php
						
					}
				}
				else
				{ 
					?>
					<div id="sibling_div">
						<div class="form-body user_form">
							<div class="row">
								<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 input smgt_form_select">
									<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
									<select name="siblingsclass[]" class="line_height_30px form-control validate[required] class_in_student max_width_100" id="sibling_class_change">
										<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
											<?php
											foreach(mj_smgt_get_allclass() as $classdata)
											{  
												?>
												<option value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
												<?php 
											}
											?>
									</select>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input smgt_form_select">
									<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
									<select name="siblingssection[]" class="line_height_30px form-control max_width_100" id="sibling_class_section">
										<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									</select>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input class_section_hide">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
									<select name="siblingsstudent[]" id="sibling_student_list" class="line_height_30px form-control max_width_100 validate[required]">
										<option value=""><?php esc_attr_e('Select Student','school-mgt');?></option>
									</select>           
								</div>
								<input type="hidden"  class="click_value" name="" value="1">
								<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_more_siblings()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Family Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-md-6 margin_bottom_20px rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group ">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Parental Status','school-mgt');?></label>													
											<div class="d-inline-block family_information">
												<?php $pstatus = "Both"; if($edit){ $pstatus=$student_data->parent_status; }elseif(isset($_POST['pstatus'])) {$pstatus=$_POST['pstatus'];}?>
												<?php if($edit){ $genderval=$value->siblinggender; }elseif(isset($_POST['siblinggender'])) {$genderval=$_POST['siblinggender'];}?>
												<input type="radio" name="pstatus" class="tog" value="Father"  id="sinfather" <?php  checked( 'Father', $pstatus);  ?>>
												<label class="custom-control-label margin_right_20px" for="Father" ><?php esc_html_e('Father','school-mgt');?></label>
												&nbsp;&nbsp; <input type="radio" name="pstatus"  id="sinmother" class="tog" value="Mother" <?php  checked( 'Mother', $pstatus);  ?>>
												<label class="custom-control-label" for="Mother"><?php esc_html_e('Mother','school-mgt');?></label>
												&nbsp;&nbsp; <input type="radio" name="pstatus" id="boths" class="tog" value="Both"  <?php  checked( 'Both', $pstatus);  ?>>
												<label class="custom-control-label" for="Both" ><?php esc_html_e('Both','school-mgt');?></label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-6 margin_bottom_20px">
						</div> -->
					</div>
					<?php
					if($edit)
					{
						$pstatus = $student_data->parent_status;
						if($pstatus == 'Father') 
						{
							$m_display_none = 'display_none';
						}
						elseif($pstatus == 'Mother')
						{
							$f_display_none = 'display_none';
						}
					}
					?>
						
					<div class="row father_div <?php echo $f_display_none;  ?> ">
						<div class="header" id="fatid">	
							<h3 class="first_hed"><?php esc_html_e('Father Information','school-mgt');?></h3>
						</div>
						<div id="fatid1" class="col-md-6 input">	
							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Salutation','school-mgt');?></label>
							<select class="form-control validate[required] line_height_30px" name="fathersalutation" id="fathersalutation">
								<option value="Mr"><?php esc_attr_e('Mr','school-mgt');?></option>
							</select>                              
						</div>		
						<div id="fatid2" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_first_name" value="<?php if($edit){ echo $student_data->father_first_name;}elseif(isset($_POST['father_first_name'])) echo $_POST['father_first_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid3" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_middle_name" value="<?php if($edit){ echo $student_data->father_middle_name;}elseif(isset($_POST['father_middle_name'])) echo $_POST['father_middle_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid4" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_last_name" value="<?php if($edit){ echo $student_data->father_last_name;}elseif(isset($_POST['father_last_name'])) echo $_POST['father_last_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid13" class="col-md-6 rtl_margin_top_15px res_margin_bottom_20px">	
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?></label>													
											<div class="d-inline-block">
												<?php $father_gender = "male"; if($edit){ $father_gender=$student_data->fathe_gender; }elseif(isset($_POST['fathe_gender'])) {$father_gender=$_POST['fathe_gender'];}?>
												<input type="radio" value="male" class="tog" name="fathe_gender" <?php  checked( 'male', $father_gender);  ?>/>
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
												<input type="radio" value="female" class="tog" name="fathe_gender" <?php  checked( 'female', $father_gender);  ?> />
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
												<input type="radio" value="other" class="tog" name="fathe_gender" <?php  checked( 'other', $father_gender);  ?> />
												<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div id="fatid14" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_birth_date" class="form-control date_picker birth_date" type="text"  name="father_birth_date" value="<?php if($edit){ if($student_data->father_birth_date==""){ echo ""; }else{ echo mj_smgt_getdate_in_input_box($student_data->father_birth_date);}}elseif(isset($_POST['father_birth_date'])) echo $_POST['father_birth_date'];?>" readonly>
									<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div id="fatid15" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_address" class="form-control date_picker validate[custom[address_description_validation]]" maxlength="120" type="text"  name="father_address" value="<?php if($edit){ echo $student_data->father_address;}elseif(isset($_POST['father_address'])) echo $_POST['father_address'];?>">
									<label for="userinput1" class="date_label"><?php esc_html_e('Address','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid16" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_state_name" value="<?php if($edit){ echo $student_data->father_state_name;}elseif(isset($_POST['father_state_name'])) echo $_POST['father_state_name'];?>">
									<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid17" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_city_name" value="<?php if($edit){ echo $student_data->father_city_name;}elseif(isset($_POST['father_city_name'])) echo $_POST['father_city_name'];?>">
									<label for="userinput1"><?php esc_html_e('City','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid18" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_zip_code" class="form-control validate[custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text" name="father_zip_code" value="<?php if($edit){ echo $student_data->father_zip_code;}elseif(isset($_POST['father_zip_code'])) echo $_POST['father_zip_code'];?>">
									<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid5" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_email" class="form-control validate[custom[email]] text-input father_email" maxlength="100" type="text"  name="father_email" value="<?php if($edit){ echo $student_data->father_email;}elseif(isset($_POST['father_email'])) echo $_POST['father_email'];?>">
									<label for="userinput1"><?php esc_html_e('Email','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid6" class="col-md-6">	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="father_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="father_mobile" value="<?php if($edit){ echo $student_data->father_mobile;}elseif(isset($_POST['father_mobile'])) echo $_POST['father_mobile'];?>">
											<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="fatid7" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_school" value="<?php if($edit){ echo $student_data->father_school;}elseif(isset($_POST['father_school'])) echo $_POST['father_school'];?>">
									<label for="userinput1"><?php esc_html_e('School Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid8" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_medium" value="<?php if($edit){ echo $student_data->father_medium;}elseif(isset($_POST['father_medium'])) echo $_POST['father_medium'];?>">
									<label for="userinput1"><?php esc_html_e('Medium of Instruction','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid9" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_education" value="<?php if($edit){ echo $student_data->father_education;}elseif(isset($_POST['father_education'])) echo $_POST['father_education'];?>">
									<label for="userinput1"><?php esc_html_e('Educational Qualification','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid10" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="fathe_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" maxlength="50" type="text" name="fathe_income" value="<?php if($edit){ echo $student_data->fathe_income;}elseif(isset($_POST['fathe_income'])) echo $_POST['fathe_income'];?>">
									<label for="userinput1"><?php esc_html_e('Annual Income','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid9" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_occuption" value="<?php if($edit){ echo $student_data->father_occuption;}elseif(isset($_POST['father_occuption'])) echo $_POST['father_occuption'];?>">
									<label for="userinput1"><?php esc_html_e('Occupation','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6" id="fatid12">	
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px" style="padding:9px;">	
									<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl"><?php _e('Proof of Qualification','school-mgt');?></label>
									<div class="col-sm-12">
										<input type="file" name="father_doc" class="col-md-12 file_validation input-file" value="<?php if($edit){ echo $student_data->father_doc;}elseif(isset($_POST['father_doc'])) echo $_POST['father_doc'];?>">	
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mother_div <?php echo $m_display_none;  ?> ">
						<div class="header" id="motid">	
							<h3 class="first_hed"><?php esc_html_e('Mother Information','school-mgt');?></h3>
						</div>
						<div id="motid1" class="col-md-6 mother_info">	
							<label class="ml-1 custom-top-label res_margin_bottom_20px top" for="hmgt_contry"><?php esc_html_e('Salutation','school-mgt');?></label>
							<select class="form-control validate[required] line_height_30px" name="mothersalutation" id="mothersalutation">
								<option value="Ms"><?php esc_attr_e('Ms','school-mgt'); ?></option>
								<option value="Mrs"><?php esc_attr_e('Mrs','school-mgt'); ?></option>
								<option value="Miss"><?php esc_attr_e('Miss','school-mgt');?></option>
							</select>
						</div>		
						<div id="motid2" class="col-md-6">	
							<div class="form-group input rtl_margin_0px">
								<div class="col-md-12 form-control">
									<input id="mother_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_first_name" value="<?php if($edit){ echo $student_data->mother_first_name;}elseif(isset($_POST['mother_first_name'])) echo $_POST['mother_first_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid3" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_middle_name" value="<?php if($edit){ echo $student_data->mother_middle_name;}elseif(isset($_POST['mother_middle_name'])) echo $_POST['mother_middle_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>	
						</div>
						<div id="motid4" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_last_name" value="<?php if($edit){ echo $student_data->mother_last_name;}elseif(isset($_POST['mother_last_name'])) echo $_POST['mother_last_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid13" class="col-md-6 rtl_margin_top_15px res_margin_bottom_20px">	
							<?php $mother_gender = "female"; if($edit){ $mother_gender=$student_data->mother_gender; }elseif(isset($_POST['mother_gender'])) {$mother_gender=$_POST['mother_gender'];}?>
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?></label>													
											<div class="d-inline-block">
												<?php $father_gender = "male"; if($edit){ $father_gender=$student_data->fathe_gender; }elseif(isset($_POST['fathe_gender'])) {$father_gender=$_POST['fathe_gender'];}?>
												<input type="radio" value="male" class="tog" name="mother_gender" <?php  checked( 'male', $mother_gender);  ?>/>
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
												<input type="radio" value="female" class="tog" name="mother_gender" <?php  checked( 'female', $mother_gender);  ?> />
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
												<input type="radio" value="other" class="tog" name="mother_gender" <?php  checked( 'other', $mother_gender);  ?> />
												<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
											</div>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div id="motid14" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_birth_date" class="form-control date_picker birth_date" type="text" name="mother_birth_date" value="<?php if($edit){ if($student_data->mother_birth_date==""){ echo ""; }else{ echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date); }}elseif(isset($_POST['mother_birth_date'])) echo $_POST['mother_birth_date'];?>" readonly>
									<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div id="motid15" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_address" class="form-control validate[custom[address_description_validation]]" maxlength="120" type="text"  name="mother_address" value="<?php if($edit){ echo $student_data->mother_address;}elseif(isset($_POST['mother_address'])) echo $_POST['mother_address'];?>">
									<label for="userinput1"><?php esc_html_e('Address','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid16" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_state_name" value="<?php if($edit){ echo $student_data->mother_state_name;}elseif(isset($_POST['mother_state_name'])) echo $_POST['mother_state_name'];?>">
									<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid17" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_city_name" value="<?php if($edit){ echo $student_data->mother_city_name;}elseif(isset($_POST['mother_city_name'])) echo $_POST['mother_city_name'];?>">
									<label for="userinput1"><?php esc_html_e('City','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid18" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_zip_code" class="form-control  validate[custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="mother_zip_code" value="<?php if($edit){ echo $student_data->mother_zip_code;}elseif(isset($_POST['mother_zip_code'])) echo $_POST['mother_zip_code'];?>">
									<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid5" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_email" class="form-control  validate[custom[email]]  text-input mother_email" maxlength="100" type="text"  name="mother_email" value="<?php if($edit){ echo $student_data->mother_email;}elseif(isset($_POST['mother_email'])) echo $_POST['mother_email'];?>">
									<label for="userinput1"><?php esc_html_e('Email','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid6" class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="mother_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mother_mobile" value="<?php if($edit){ echo $student_data->mother_mobile;}elseif(isset($_POST['mother_mobile'])) echo $_POST['mother_mobile'];?>">
											<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>	
						</div>
						<div id="motid7" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_school" value="<?php if($edit){ echo $student_data->mother_school;}elseif(isset($_POST['mother_school'])) echo $_POST['mother_school'];?>">
									<label for="userinput1"><?php esc_html_e('School Name','school-mgt');?></label>
								</div>
							</div>	
						</div>
						<div id="motid8" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_medium" value="<?php if($edit){ echo $student_data->mother_medium;}elseif(isset($_POST['mother_medium'])) echo $_POST['mother_medium'];?>">
									<label for="userinput1"><?php esc_html_e('Medium of Instruction','school-mgt');?></label>
								</div>
							</div>	
						</div>
						<div id="motid9" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_education" value="<?php if($edit){ echo $student_data->mother_education;}elseif(isset($_POST['mother_education'])) echo $_POST['mother_education'];?>">
									<label for="userinput1"><?php esc_html_e('Educational Qualification','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid10" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" type="text" name="mother_income" value="<?php if($edit){ echo $student_data->mother_income;}elseif(isset($_POST['mother_income'])) echo $_POST['mother_income'];?>">
									<label for="userinput1"><?php esc_html_e('Annual Income','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid9" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_occuption" value="<?php if($edit){ echo $student_data->mother_occuption;}elseif(isset($_POST['mother_occuption'])) echo $_POST['mother_occuption'];?>">
									<label for="userinput1"><?php esc_html_e('Occupation','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div id="motid12" class="col-md-6">	
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px" style="padding:9px;">	
									<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl"><?php _e('Proof of Qualification','school-mgt');?></label>
									<div class="col-sm-12">	
										<input type="file" name="mother_doc" class="col-md-12 file_validation input-file" value="<?php if($edit){ echo $student_data->mother_doc;}elseif(isset($_POST['mother_doc'])) echo $_POST['mother_doc'];?>">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body user_form">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Admission','school-mgt');}else{ esc_attr_e('New Admission','school-mgt');}?>" name="student_admission" class="btn btn-success save_btn"/>
						</div>
					</div>
				</div>
			</form><!------ Form End ----->
		</div><!-------- penal body -------->
		<script>
			// add More Sibling script 
			<?php
			if($edit)
			{ ?> 
				var key = $('#admission_sibling_id').val();
				var value = key;
				<?php 
			}
			else
			{
				?>
				var value=0;
				<?php 
			}
			?>
			// add more sibling div add function 
			function mj_smgt_add_sibling()
			{	
				value++;
				$("#sibling_div").append('<div class="form-body user_form"><div class="row"><div class="col-md-3 col-sm-3 col-xs-12 res_margin_bottom_20px rtl_margin_top_15px"><div class="form-group"><div class="col-md-12 form-control"><div class="row padding_radio"><div class="input-group"><label class="custom-top-label margin_left_0"><?php esc_html_e('Relation','school-mgt');?></label><div class="d-inline-block"><input type="radio" name="siblinggender['+value+']" value="Brother" id="txtNumHours2" checked><label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Brother','school-mgt');?></label>&nbsp;&nbsp;<input type="radio" name="siblinggender['+value+']" value="Sister" id="txtNumHours2"><label class="custom-control-label" for="female"><?php esc_html_e('Sister','school-mgt');?></label></div></div></div></div></div></div><div class="col-md-2 col-sm-3 col-xs-12"><div class="form-group input"><div class="col-md-12 form-control"><input id="txtNumHours" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="siblingsname[]" value=""><label for="userinput1" class=""><?php esc_html_e('Full Name','school-mgt');?></label></div></div></div><div class="col-md-1 col-sm-3 col-xs-12"><div class="form-group input"><div class="col-md-12 form-control input_height_47px"><input id="txtNumHours1" class="form-control age_padding_left_right_0 validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input" type="number" maxlength="3" name="siblingage[]" value=""><label for="userinput1" class=""><?php esc_html_e('Age','school-mgt');?></label></div></div></div><div class="col-md-3 col-sm-3 col-xs-12 input"><label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Standard','school-mgt');?><span class="required">*</span></label><select class="form-control standard_category validate[required] line_height_30px" name="sibling_standard[]" id="txtNumHours3"><option value=""><?php esc_html_e('Select Standard','school-mgt');?></option><?php $activity_category=mj_smgt_get_all_category('standard_category');if(!empty($activity_category)){ foreach ($activity_category as $retrive_data){ ?><option value="<?php echo $retrive_data->ID;?>"><?php echo esc_attr($retrive_data->post_title); ?> </option><?php } } ?> </select></div><div class="col-md-2 col-sm-3 col-xs-12"><div class="form-group input"><div class="col-md-12 form-control input_height_47px"><input id="txtNumHours4" class="form-control validate[custom[onlyNumberSp],maxSize[6]] text-input" value="" type="number"  name="siblingsid[]"> <label for="userinput1" class=""><?php esc_html_e('Enter SID Number','school-mgt');?></label></div></div></div><div class="col-md-1 col-sm-3 col-xs-12"><input type="image" onclick="mj_smgt_deleteParentElement(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="rtl_margin_top_15px remove_cirtificate float_right input_btn_height_width"></div></div></div>');
			}
			// delete sibling div function
			function mj_smgt_deleteParentElement(n)
			{
				alert("<?php esc_html_e('Do you really want to delete this ?','school-mgt'); ?>");
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
			}
		</script>
		<?php 
	}
	?>
</div>