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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('supportstaff');
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
			if ('supportstaff' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('supportstaff' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('supportstaff' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	jQuery(document).ready(function($)
	{
		"use strict";	
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
			onChangeMonthYear: function(year, month, inst) {
				$(this).val(month + "/" + year);
			}
		}); 
	});
</script>
<?php 
$role='supportstaff';
if(isset($_POST['save_supportstaff']))
{
	// var_dump($_POST);
	// die;
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_supportstaff_admin_nonce' ) )
	{
		$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
		$middlename=mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);
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
		
		$usermetadata=array('middle_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
							'gender'=>mj_smgt_onlyLetterSp_validation($_POST['gender']),
							'birth_date'=>$_POST['birth_date'],
							'address'=>mj_smgt_address_description_validation($_POST['address']),
							'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
							'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
							'zip_code'=>mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
							
							'phone'=>mj_smgt_phone_number_validation($_POST['phone']),
							'mobile_number'=>mj_smgt_phone_number_validation($_POST['mobile_number']),
							'alternet_mobile_number'=>mj_smgt_phone_number_validation($_POST['alternet_mobile_number']),
							'working_hour'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['working_hour']),
							'possition'=>mj_smgt_address_description_validation($_POST['possition']),
							'smgt_user_avatar'=>$photo,
							
		);
		
		if($_REQUEST['action']=='edit')
		{
			$userdata['ID']=$_REQUEST['supportstaff_id'];
			$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=2'); 
			}		
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( mj_smgt_strip_tags_and_stripslashes($_POST['username']))) {
				$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
				if($result)
				{ 
						wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=1');
				 }
			}
			else 
			{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=3');
			}
		}
	}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=mj_smgt_delete_usedata($_REQUEST['supportstaff_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=4');
	}
}
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=mj_smgt_delete_usedata($id);
	if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=4');
		}
}
	//-------------- EXPORT Staff DATA ---------------//
if(isset($_POST['staff_csv_selected']))
{
	if(isset($_POST['id']))
	{	
		foreach($_POST['id'] as $s_id)
		{
			$staff_list[]=get_userdata($s_id);
		}
		if(!empty($staff_list))
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
			$filename='Reports/export_staff.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			foreach($staff_list as $retrive_data)
			{
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				
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
				fputcsv($fh, $row);				
			}
			fclose($fh);
			//download csv file.
			ob_clean();
			$file=SMS_PLUGIN_DIR.'/admin/Reports/export_staff.csv';//file location
		
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
			echo "<div class='parent-error'>Records not found.</div>";
		}	
	}
}
//------------------ IMPORT STAFF MEMBER --------------------------//
if(isset($_REQUEST['upload_staff_csv_file']))
{
	
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'upload_csv_nonce' ) )
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
				$err=esc_attr__('this file not allowed, please choose a CSV file.','school-mgt');
				$errors[]=$err;
				wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=6');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=7');
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
						$password = rand();
					}
					$problematic_row = false;
					if( username_exists($username) )
					{ // if user exists, we take his ID by login
						$user_object = get_user_by( "login", $username );
						$user_id = $user_object->ID;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					elseif( email_exists( $email ) ){ // if the email is registered, we take the user from this
						$user_object = get_user_by( "email", $email );
						$user_id = $user_object->ID;					
						$problematic_row = true;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					else
					{
						if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
							$password = wp_generate_password();						
							$user_id = wp_create_user($username, $password, $email);
						
					}

					if( is_wp_error($user_id) )
					{ // in case the user is generating errors after this checks
					?>	
						<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/support-error.js'; ?>" ></script>
					<?php	
						continue;
					}
					 if(!( in_array("administrator", (array)mj_smgt_get_roles($user_id), FALSE) || is_multisite() && is_super_admin( $user_id ) ))
						wp_update_user(array ('ID' => $user_id, 'role' => 'supportstaff')) ;
						
					
						$user_id1 = wp_update_user( array( 'ID' => $user_id, 'display_name' =>$csv['first name'].' '.$csv['middle name'].' '.$csv['last name']) );
						 
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
				foreach($errors as &$error) echo $error;
			}	
			if(isset($success))
			{		
				if($_REQUEST['smgt_import_staff_mail'] == '1')
				{
					if($user_id)
					{
						$userdata = get_userdata($user_id);
						
						$string = array();
	
						$string['{{user_name}}']   =  $userdata->display_name;
	
						$string['{{school_name}}'] =  get_option('smgt_school_name');
	
						$string['{{role}}']        =  'supportstaff';
	
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
				wp_redirect ( admin_url().'admin.php?page=smgt_supportstaff&tab=supportstaff_list&message=5');
			} 
		}
    }
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'supportstaff_list';	
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
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Support Staff Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Support Staff Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Username Or Email-id Already Exist.','school-mgt');
				break;
			case '4':
				$message_string = esc_attr__('Support Staff Deleted Successfully.','school-mgt');
				break;	
			case '5':
				$message_string = esc_attr__('Support Staff CSV Uploaded Successfully .','school-mgt');
				break;
			case '6':
			$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
			break;
			case '7':
			$message_string = esc_attr__('File size limit 2 MB.','school-mgt');
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
		<div class="row"><!-- row -->
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
					<?php  
					if($active_tab == 'supportstaff_list')
					{ 
						$teacherdata=mj_smgt_get_usersdata('supportstaff');
						if(!empty($teacherdata))
						{
							?>	
							<script type="text/javascript">
								jQuery(document).ready(function($)
								{
									"use strict";
									$("body").on("click",".staff_csv_selected",function()
									{
										if ($('.selected_staff:checked').length == 0 )
										{
											alert(language_translate2.one_record_select_alert);
											return false;
										}		
									});  

									var table =  jQuery('#supportstaff_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true,
										responsive: true,
										"dom": 'lifrtp',
										"order": [[ 2, "DESC" ]],
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
								});
							</script>
							<form name="wcwm_report" action="" method="post">
								<div class="panel-body">
									<div class="table-responsive">
										<form name="frm-example" action="" method="post">
											<table id="supportstaff_list" class="display admin_supportstaff_datatable" cellspacing="0" width="100%">
												<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
													<tr>
														<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
														<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
														<th><?php esc_attr_e( 'Support Staff Name & Email', 'school-mgt' ) ;?></th>			  
                										<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>
														<th> <?php echo esc_attr_e( 'Gender', 'school-mgt' ) ;?></th>
														<th> <?php echo esc_attr_e( 'Date of Birth', 'school-mgt' ) ;?></th>
														<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if(!empty($teacherdata))
													{
														foreach (mj_smgt_get_usersdata('supportstaff') as $retrieved_data)
														{ 
															$uid=$retrieved_data->ID;
															?>
															<tr>
															<td class="checkbox_width_10px">
																<input type="checkbox" class="smgt_sub_chk selected_staff" name="id[]" value="<?php echo $retrieved_data->ID;?>">
															</td>
															<td class="user_image width_50px">
																<a class="color_black" href="?page=smgt_supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>">
																	<?php 
																		$uid=$retrieved_data->ID;
																		$umetadata=mj_smgt_get_user_image($uid);
																		if(empty($umetadata))
																		{
																			echo '<img src='.get_option( 'smgt_supportstaff_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
																		}
																		else
																		{
																			echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
																		}
																	?>
																</a>
															</td>
																<td class="name">
																	<a class="color_black" href="?page=smgt_supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>">
																		<?php echo $retrieved_data->display_name;?>
																	</a>
																	<br>
																	<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label>
																</td>

																<td class="">
																	+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '. get_user_meta( $uid, 'mobile_number', true );?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i>
																</td>

																<td class="">
																	<?php echo esc_html_e(ucfirst(get_user_meta( $uid, 'gender', true )),'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Gender','school-mgt');?>" ></i>
																</td>

																<td class="">
																	<?php
																	$birthdate = get_user_meta( $uid, 'birth_date', true ); ?>
																	<?php echo mj_smgt_getdate_in_input_box($birthdate); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date of Birth','school-mgt');?>" ></i>
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
																						<a href="?page=smgt_supportstaff&tab=view_supportstaff&action=view_supportstaff&supportstaff_id=<?php echo $retrieved_data->ID;?>"
																						class="float_left_width_100"><i class="fa fa-eye"> </i><?php esc_attr_e('View','school-mgt');?></a>
																					</li>
																					<?php
																					if($user_access_edit == '1')
																					{
																					?>
																						<li class="float_left_width_100 border_bottom_item">
																							<a href="?page=smgt_supportstaff&tab=addsupportstaff&action=edit&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
																						</li>
																						<?php 
																					} ?>
																					<?php 
																					if($user_access_delete =='1')
																					{
																						?>
																						<li class="float_left_width_100 ">
																							<a href="?page=smgt_supportstaff&tab=supportstaff_list&action=delete&supportstaff_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"> </i>
																							<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
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
													} ?>
												</tbody>		
											</table>
								
											<div class="print-button pull-left">
												<button class="btn btn-success btn-sms-color button_reload" style="margin-bottom: 5px;">
													<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
													<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
												</button>
												<?php 
												if($user_access_delete =='1')
												{
													?>
													<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
													<?php 
												} ?>

												<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','school-mgt');?>" name="staff_csv_selected" class="staff_csv_selected export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>

												<button data-toggle="tooltip"  title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" class="view_import_support_staff_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
											</div>
										</form>
									</div>
								</div>
							</form>
							<?php 
						}
						else
						{
							if($user_access_add=='1')
							{
								?>
								<div class="no_data_list_div row"> 
									<div class="offset-md-2 col-md-4">
										<a href="<?php echo admin_url().'admin.php?page=smgt_supportstaff&tab=addsupportstaff';?>">
											<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
										</div>
									</div>
									<div class="col-md-4">
										<a data-toggle="tooltip"  name="import_csv" type="button" class="view_import_support_staff_csv_popup">
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

					if($active_tab == 'addsupportstaff')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/add-staff.php';
					}
					if($active_tab == 'view_supportstaff')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/view_supportstaff.php';
					}
					if($active_tab == 'uploadstaff')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/upload_staff.php';
					}
					?>
				</div><!-- smgt_main_listpage -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->