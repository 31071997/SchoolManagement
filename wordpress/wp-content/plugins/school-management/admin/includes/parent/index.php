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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('parent');
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
			if ('parent' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('parent' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('parent' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
		$("body").on("click",".parent_csv_selected",function()
		{
			if ($('.selected_parent:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}		
		}); 
		$('#parent_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#birth_date').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			maxDate : 0,
			changeMonth: true,
			changeYear: true,
			yearRange:'-122:+25',
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
	
		var numItems = $('.parents_child').length;
		if(numItems == 1)
		{
			$('#revove_item').hide();
		}

		function deleteParentElement(n)
		{
			alert(language_translate2.do_delete_record);
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
		$('#add-another_item').on('click',function(event) {
			event.preventDefault();
			var $this = $(this);
			var $last = $this.prev(); // $this.parents('.something').prev() also useful
			var $clone = $last.clone(true);
			var $inputs = $clone.find('input,textarea,select');
			$last.after($clone);
			$inputs.eq(0).focus();
			
			var numItems = $('.parents_child').length;
			if(numItems > 1)
			{
					$('#revove_item').show();
			}
		});		
		$('#revove_item').on('click',function(event) {
			event.preventDefault();
			var numItems = $('.parents_child').length;
			if(numItems > 1)
			{
					$(this).prev().prev().remove();
					if(numItems == 2)
						$('#revove_item').hide();
			}
			else
			{ $('#revove_item').hide();}
		});	
		$('#upload_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		var table =  jQuery('#child_list').DataTable({
			//stateSave: true,
			responsive: true,
			"order": [[ 0, "asc" ]],
			"aoColumns":[	                  
			{"bSortable": false},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true}],
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
	});

</script>
<?php 
	$role='parent';
	if(isset($_POST['save_parent']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_parent_admin_nonce' ) )
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
			
			
			$usermetadata	=	array(
				'middle_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
				'gender'=>mj_smgt_onlyLetterSp_validation($_POST['gender']),
				'birth_date'=>$_POST['birth_date'],
				'address'=>mj_smgt_address_description_validation($_POST['address']),
				'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
				'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
				'zip_code'=>mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
				'phone'=>mj_smgt_phone_number_validation($_POST['phone']),
				'mobile_number'=>mj_smgt_phone_number_validation($_POST['mobile_number']),
				'relation'=>mj_smgt_onlyLetterSp_validation($_POST['relation']),
				'smgt_user_avatar'=>$photo,	
				'created_by'=>get_current_user_id()
			);
		
			if($_REQUEST['action']=='edit')
			{			
				$userdata['ID']=$_REQUEST['parent_id'];			
				$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
				if($result)
				{ 
					wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=1'); 
				}
			}
			else
			{
				if( !email_exists($_POST['email'])) 
				{
					$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
					if($result)
					{ 
						wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=2'); 
					} 
				}
				else 
				{ 
					wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=3'); 
				}		  
			}
	    }
	}
	$addparent	=	0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'addparent')
	{
		if(isset($_REQUEST['student_id']))
		{			
			$student=get_userdata($_REQUEST['student_id']);
			$addparent=1;
		}
	}	
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'parentlist';
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$childs=get_user_meta($_REQUEST['parent_id'], 'child', true);
		
		if(!empty($childs))
		{
			foreach($childs as $childvalue)
			{
				
				$parents = get_user_meta($childvalue, 'parent_id', true);
				
				if(!empty($parents))
				{
					if(($key = array_search($_REQUEST['parent_id'], $parents)) != false) {
						unset($parents[$key]);
						update_user_meta( $childvalue,'parent_id', $parents );
					}
				}
			}
		}
		$result=mj_smgt_delete_usedata($_REQUEST['parent_id']);	
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=4'); 
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$childs=get_user_meta($id, 'child', true);
			if(!empty($childs))
			{
				foreach($childs as $childvalue)
				{
					$parents=get_user_meta($childvalue, 'parent_id', true);
					if(!empty($parents))
					{
						if(($key = array_search($id, $parents)) != false)
						{
							unset($parents[$key]);
							update_user_meta( $childvalue,'parent_id', $parents );
						}
					}
				}
			}
			$result=mj_smgt_delete_usedata($id);	
		}
			
		if($result) { 
			wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=4'); 
		}
	}	
	//-------------- EXPORT Parent DATA ---------------//
	if(isset($_POST['parent_export_csv_selected']))
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
				$header[] = 'Password';
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
				$header[] = 'child';	
				$header[] = 'Relation';	
				$filename='Reports/export_parent.csv';
				$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
				fputcsv($fh, $header);
				foreach($staff_list as $retrive_data)
				{
					$row = array();
					$user_info = get_userdata($retrive_data->ID);
					$child_id = get_user_meta($retrive_data->ID, 'child',true);
					$childid = array();
					foreach($child_id as $childsdata)
					{
						$child=get_userdata($childsdata);
						$childid[] = $child->data->user_email;
					}
					$row[] =  $user_info->user_login;
					$row[] =  $user_info->user_email;
					$row[] =  $user_info->user_pass;
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
					$child_record_id = implode("," , $childid);
					$row[] =  $child_record_id;	
					
					$row[] =  get_user_meta($retrive_data->ID, 'relation',true);			
					fputcsv($fh, $row);				
				}
				
				fclose($fh);
		
				//download csv file.
				ob_clean();
				$file=SMS_PLUGIN_DIR.'/admin/Reports/export_parent.csv';//file location
			
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
	//------------------ IMPORT Parent MEMBER --------------------------//
	if(isset($_REQUEST['upload_parent_csv_file']))
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
					$err= esc_attr__('this file not allowed, please choose a CSV file.','school-mgt');
					$errors[]=$err;
					wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=uploadparent&message=6');
				}
				if($file_size > 2097152)
				{
					$errors[]='File size limit 2 MB';
					wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=uploadparent&message=7');
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
							
							if( !empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
								$password = $csv['password'];	
								$user_id = wp_create_user($username, $password, $email);
						}
					
						if( is_wp_error($user_id) )
						{ // in case the user is generating errors after this checks
							echo '<script>alert("Problems with user: ' . $username . ', we are going to skip");</script>';
							continue;
						}

						if(!(is_multisite() && is_super_admin( $user_id ) ))
							wp_update_user(array ('ID' => $user_id, 'role' => 'parent')) ;
							
						
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
							if(isset($csv['user_profile']))
							{
								$upload_dir   = wp_upload_dir();
								$photo = $upload_dir['baseurl'].'/'.$csv['user_profile'];
								update_user_meta( $user_id, "smgt_user_avatar", $photo);	
							}
							if(isset($csv['alternate mobile number']))
								update_user_meta( $user_id, "alternet_mobile_number", $csv['alternate mobile number'] );						
							if(isset($csv['phone number']))
								update_user_meta( $user_id, "phone", $csv['phone number'] );	
							if(isset($csv['relation']))
								update_user_meta( $user_id, "relation", $csv['relation'] );	
							
							if(isset($csv['child']))
							{	
								$child_username = explode(',' ,$csv['child']);
								foreach($child_username as $child_id)
								{
									$user = get_user_by( 'email',$child_id);
									$student_id = $user->data->ID;
									$student_data = get_user_meta($student_id, 'parent_id', true);
									
									$parent_data = get_user_meta($user_id, 'child', true); 
									
									if($student_data)
									{
										if(!in_array($user_id, $student_data))
										{
											$update = array_push($student_data,$user_id);				
											$returnans=update_user_meta($student_id,'parent_id', $student_data);
											if($returnans)
											{
												$returnval=$returnans;
											}
										}
									}
									if ($parent_data)
									{
										if(!in_array($student_id, $parent_data))
										{
											$update = array_push($parent_data,$student_id);			
											$returnans = update_user_meta($user_id,'child', $parent_data);
											if($returnans)
											$returnval=$returnans;
										}
									}
									else 
									{	
										if(!empty($student_id)){
											$child_id = array($student_id);
											$returnans=add_user_meta($user_id,'child', $child_id);
											if($returnans)
												$returnval=$returnans;
										}
									}		
								}
							}	
							
							$success = 1;
					}
				}
				else
				{
					foreach($errors as &$error) echo $error;
				}
						
				if(isset($success))
				{				
					if($_REQUEST['smgt_import_parent_mail'] == '1')
					{
						if($user_id)
						{
							$userdata = get_userdata($user_id);
							
							$string = array();
		
							$string['{{user_name}}']   =  $userdata->display_name;
		
							$string['{{school_name}}'] =  get_option('smgt_school_name');
		
							$string['{{role}}']        =  'parent';
		
							$string['{{login_link}}']  =  site_url() .'/index.php/school-management-login-page';
		
							$string['{{username}}']    =  $userdata->user_email;
		
							$string['{{Password}}']    =  $csv['password'];
		
							$MsgContent                =  get_option('add_user_mail_content');
		
							$MsgSubject				   =  get_option('add_user_mail_subject');
		
							$message = mj_smgt_string_replacement($string,$MsgContent);
		
							$MsgSubject = mj_smgt_string_replacement($string,$MsgSubject);
		
							$email=  $userdata->user_email;
							
							mj_smgt_send_mail($email,$MsgSubject,$message);
						}
					}
					wp_redirect ( admin_url().'admin.php?page=smgt_parent&tab=parentlist&message=5');
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
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Parent Updated Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Parent Added Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Username Or Emailid Already Exist.','school-mgt');
				break;	
			case '4':
				$message_string = esc_attr__('Parent Deleted Successfully.','school-mgt');
				break;	
			case '5':
				$message_string = esc_attr__('Parent CSV Uploaded Successfully .','school-mgt');
				break;	
			case '6':
				$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
				break;
			case '7':
				$message_string = esc_attr__('File size limit 2 MB','school-mgt');
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
					if($active_tab == 'parentlist')
					{ 
						$parentdata=mj_smgt_get_usersdata('parent');
						if(!empty($parentdata))
						{
							?>  
							<script>
								jQuery(document).ready(function() 
								{
									"use strict";
									var table =  jQuery('#parent_list').DataTable({
										"initComplete": function( settings, json ) {
											$('.table-responsive').show();
											$(".loader").css({"display": "none"});
											$(".print-button").css({"margin-top": "-5%"});
											$('#parent_list th:first-child').removeClass('sorting_asc');
										},
										//stateSave: true,
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
											alert("<?php esc_html_e('Please select at least one record','school-mgt');?>");
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
							<div class="">
								<div class="loader">
									<img src="<?php echo SMS_PLUGIN_URL.'/assets/images/school_app_loader_gif.gif'?>" alt="">
								</div>
								<div class="table-responsive" style="display:none;">
									<form name="frm-example" action="" method="post">
										<table id="parent_list" class="display admin_parent_datatable" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all" style="float:left;"></th>
													<th><?php  _e('Photo', 'school-mgt' ) ;?></th>
													<th><?php echo esc_attr_e('Parent Name & Email', 'school-mgt' ) ;?></th>
													<th> <?php esc_attr_e('Mobile Number', 'school-mgt' ) ;?></th>
													<th> <?php echo esc_attr_e('Gender', 'school-mgt' ) ;?></th>
													<th> <?php echo esc_attr_e('Relation', 'school-mgt' ) ;?></th>
													<th class="text_align_end"><?php _e('Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 
												if($parentdata)
												{
													foreach ($parentdata as $retrieved_data)
													{ 
														$uid=$retrieved_data->ID;
														?>	
														<tr>
															<td class="checkbox_width_10px">
																<input type="checkbox" class="smgt_sub_chk selected_parent" name="id[]" value="<?php echo $retrieved_data->ID;?>">
															</td>

															<td class="user_image width_50px">
																<a class="color_black" href="?page=smgt_parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>">
																	<?php 
																	$uid=$retrieved_data->ID;
																	$umetadata=mj_smgt_get_user_image($uid);
																	if(empty($umetadata))
																	{
																		echo '<img src='.get_option( 'smgt_parent_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
																	}
																	else
																	{
																		echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
																	}
																	?>
																</a>
															</td>
															<td class="name">
																<a class="color_black" href="?page=smgt_parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>">
																	<?php echo mj_smgt_get_parent_name_byid($retrieved_data->ID);?>
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
																<?php echo esc_html_e(ucfirst(get_user_meta( $uid, 'relation', true )),'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Relation','school-mgt');?>" ></i>
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
																					<a href="?page=smgt_parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"></i><?php esc_attr_e('View','school-mgt');?></a>
																				</li>

																				<?php 
																				if($user_access_edit == '1')
																				{ ?>
																					<li class="float_left_width_100 border_bottom_item">
																					<a href="?page=smgt_parent&tab=addparent&action=edit&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"></i><?php echo esc_attr_e( 'Edit', 'school-mgt' ) ;?></a> 
																					</li>
																					<?php 
																				} ?>
																				<?php 
																				if($user_access_delete =='1')
																				{ ?>
																					<li class="float_left_width_100 ">
																						<a href="?page=smgt_parent&tab=parentlist&action=delete&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php echo esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
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
												}   ?>
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
											} 
											?>
											<button data-toggle="tooltip" title="<?php esc_html_e('Export CSV','school-mgt');?>" name="parent_export_csv_selected" class="parent_csv_selected export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>

											<!-- <button data-toggle="tooltip" title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" name="import_csv" class="importdata export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button> -->

											<button data-toggle="tooltip"  title="<?php esc_html_e('Import CSV','school-mgt');?>" type="button" class="view_import_parent_csv_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
										</div>
									</form>
								</div>
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
										<a href="<?php echo admin_url().'admin.php?page=smgt_parent&tab=addparent';?>">
											<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
										</a>
										<div class="col-md-12 dashboard_btn margin_top_20px">
											<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
										</div> 
									</div>
									<div class="col-md-4">
										<a data-toggle="tooltip"  name="import_csv" type="button" class="view_import_parent_csv_popup">
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
	
					if($active_tab == 'addparent')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/parent/add-newparent.php';
					}
					if($active_tab == 'view_parent')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/parent/view_parent.php';
					}
					if($active_tab == 'uploadparent')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/parent/upload_parent.php';
					}
					?>				
				</div><!-- smgt_main_listpage -->
			</div><!-- col-md-12 -->
		</div><!-- Row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->