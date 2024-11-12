<?php 
$role_name=mj_smgt_get_user_role(get_current_user_id());
?>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";	
	
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
		var table =  jQuery('#child_list').DataTable({
					
					"order": [[ 1, "asc" ]],
					"aoColumns":[	                  
						{"bSortable": false},
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true},
						{"bSortable": true}
					],	
					language:<?php echo mj_smgt_datatable_multi_language();?>	
		});

		$('#parent_list').DataTable({
			"initComplete": function(settings, json) {
				$(".print-button").css({"margin-top": "-5%"});
			},
				"dom": 'lifrtp',
				"ordering": true,
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
		//--------------- MULTIPLE DELETE JS ------------//
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
		$('#parent_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#birth_date').datepicker({
			maxDate : 0,
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
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
		{$('#revove_item').hide();}
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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'parentlist';
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
//--------------------------  SAVE PARENT ----------------------//
if(isset($_POST['save_parent']))
{
	$role='parent';
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
		
		if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
		{
			if($_FILES['upload_user_avatar_image']['size'] > 0)
				$member_image=mj_smgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
				$photo=content_url().'/uploads/school_assets/'.$member_image;
		}
		else
		{
			if(isset($_REQUEST['hidden_upload_user_avatar_image']))
			$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
			$photo=$member_image;
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
				wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=1'); 		
			}
		}
		else
		{
			if( !email_exists($_POST['email'])) 
			{
				$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$middlename,$lastname,$role);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=2'); 		
				} 
			}
			else 
			{ 
				wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=3'); 		
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
//------------------------ DELETE PARENT ------------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$childs=get_user_meta($_REQUEST['parent_id'], 'child', true);
	if(!empty($childs))
	{
		foreach($childs as $childvalue)
		{
			$parents=get_user_meta($childvalue, 'parent_id', true);
			if(!empty($parents))
			{
				if(($key = array_search($_REQUEST['parent_id'], $parents)) !== false) {
					unset($parents[$key]);
					update_user_meta( $childvalue,'parent_id', $parents );
				}
			}
		}
	}
	$result=mj_smgt_delete_usedata($_REQUEST['parent_id']);	
	if($result)
	{ 
		wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=4'); 		
	}
}
//------------- MULTIPLE DELETE PARENTS -------------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	{
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
						if(($key = array_search($id, $parents)) !== false)
						{
							unset($parents[$key]);
							update_user_meta( $childvalue,'parent_id', $parents );
						}
					}
				}
			}
			$result=mj_smgt_delete_usedata($id);	
		}
	}
	if($result) 
	{ 
		wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=4'); 
	}
}	
$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
switch($message)
{
	case '1':
		$message_string = esc_attr__('Parent Updated Successfully.','school-mgt');
		break;
	case '2':
		$message_string = esc_attr__('Parent Added successfully.','school-mgt');
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
<div class="panel-body panel-white frontend_list_margin_30px_res"><!------------ PENAL BODY ------------->
	<div class="">
    <?php 
	//------------------- PERENT LIST TAB -------------------//
	if($active_tab == 'parentlist')		
	{ 
		$user_id=get_current_user_id();
		//------- PARENT DATA FOR STUDENT ---------//
		if($school_obj->role == 'student')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$parentdata1=$school_obj->parent_list;
				foreach($parentdata1 as $pid)
				{
					$parentdata[]=get_userdata($pid);
				}
			}
			else
			{
				$parentdata=mj_smgt_get_usersdata('parent');
			}
		}
		//------- PARENT DATA FOR TEACHER ---------//
		elseif($school_obj->role == 'teacher')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$parent = mj_smgt_parent_own_data_for_teacher();
				foreach($parent as $pid)
				{
					$parentdata[]=get_userdata($pid);
				}
			}
			else
			{
				$parentdata=mj_smgt_get_usersdata('parent');
			}
		}
		//------- PARENT DATA FOR PARENT ---------//
		elseif($school_obj->role == 'parent')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$parentdata[]=get_userdata($user_id);	
			}
			else
			{
				$parentdata=mj_smgt_get_usersdata('parent');
			}
		}
		//------- PARENT DATA FOR SUPPORT STAFF ---------//
		else
		{ 
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$parentdata= get_users(
								array(
									'role' => 'parent',
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
				$parentdata=mj_smgt_get_usersdata('parent');
			}
		}
		if(!empty($parentdata))
		{
			?>
			<div class="panel-body"><!------------ PENAL BODY ------------->
				<!--------------- PARENT LIST FORM --------------->
				<form name="wcwm_report" action="" method="post">
					<div class="table-responsive"><!--------------- TABLE RESPONSIVE --------------->
						<table id="parent_list" class="display dataTable" cellspacing="0" width="100%">
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
									<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr_e( 'Parent Name & Email', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Mobile Number', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Gender', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Relation', 'school-mgt' ) ;?></th>
									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								
								if(!empty($parentdata))
								{
									foreach ($parentdata as $retrieved_data)
									{ 
										$uid=$retrieved_data->ID;
										?>	
										<tr>
											<?php
											if($role_name == "supportstaff")
											{
												?>
												<td class="checkbox_width_10px">
													<input type="checkbox" class="smgt_sub_chk selected_parent" name="id[]" value="<?php echo $retrieved_data->ID;?>">
												</td>
												<?php
											}
											?>
											<td class="user_image width_50px">
												<a class="" href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>">
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
												<a class="color_black" href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>"><?php echo mj_smgt_get_parent_name_byid($retrieved_data->ID);?></a>
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
																	<a href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-eye"></i><?php esc_attr_e('View','school-mgt');?></a>
																</li>
																<?php 
																if($user_access['edit']=='1')
																{ 
																	?>
																	<li class="float_left_width_100 border_bottom_item">
																		<a href="?dashboard=user&page=parent&tab=addparent&action=edit&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100"><i class="fa fa-edit"></i><?php echo esc_attr_e( 'Edit', 'school-mgt' ) ;?></a> 
																	</li>
																	<?php 
																}  
																if($user_access['delete']=='1')
																{ 
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=parent&tab=parentlist&action=delete&parent_id=<?php echo $retrieved_data->ID;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php echo esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
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
									<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
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
					</div><!--------------- TABLE RESPONSIVE --------------->
				</form><!--------------- PARENT LIST FORM --------------->
			</div><!------------ PENAL BODY ------------->
			<?php
		}
		else
		{
			if($user_access['add']=='1')
			{
				?>
				<div class="no_data_list_div no_data_img_mt_30px"> 
					<a href="<?php echo home_url().'?dashboard=user&page=parent&tab=addparent';?>">
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
	//------------------- PERENT ADD FORM TAB -------------------//
	if($active_tab == 'addparent')
	{
		$students = mj_smgt_get_student_groupby_class();
		$role='parent';
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' ) 
		{
			$edit=1;	
			$user_info = get_userdata($_REQUEST['parent_id']);
		} 
		?>       
		<div class="panel-body"><!---------- PENAL BODY ------------>
		    <!---------------- PARENT ADD FORM ---------------->
			<form name="parent_form" action="" method="post" class="mt-3 form-horizontal" id="parent_form" enctype="multipart/form-data">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="role" value="<?php echo $role;?>"  />
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('PERSONAL Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!-- user form -->
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
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">	  
							<label class="ml-1 custom-top-label top" for="relation"><?php esc_attr_e('Relation','school-mgt');?><span class="require-field">*</span></label>
							<?php if($edit){ $relationval=$user_info->relation; }elseif(isset($_POST['relation'])){$relationval=$_POST['relation'];}else{$relationval='';}?>
							<select name="relation" class="line_height_30px form-control validate[required]" id="relation">
								<option value=""><?php esc_attr_e('Select Relation','school-mgt');?></option>
								<option value="Father" <?php selected( $relationval, 'Father'); ?>><?php esc_attr_e('Father','school-mgt');?></option>
								<option value="Mother" <?php selected( $relationval, 'Mother'); ?>><?php esc_attr_e('Mother','school-mgt');?></option>
							</select>
						</div>
					</div>
				</div><!-- user form -->
				<hr>
				<div class="form-body user_form"><!-- user form -->
					<?php 
					if($edit)
					{
						$parent_data = get_user_meta($user_info->ID, 'child', true);
						if(!empty($parent_data)) 	
						{
							$i=1;
							foreach($parent_data as $id1)
							{ 	
								?>
								<!-- Edit time -->
								<div id="parents_child" class="form-group row mb-3 parents_child">
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
									
										<select name="chield_list[]" id="student_list" class="form-control validate[required] max_width_100" style="height: 47px !important;">
										<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
										<?php 
										foreach ($students as $label => $opt){ ?>
											<optgroup label="<?php echo "Class : ".$label; ?>">
												<?php foreach ($opt as $id => $name): ?>
												<option value="<?php echo $id; ?>" <?php selected($id, $id1);  ?> ><?php echo $name; ?></option>
												<?php endforeach; ?>
											</optgroup>
											<?php } ?>
										</select>
									</div>
									<?php
									if($i == 1)
									{
										?>
										<div class="col-md-1 col-sm-1 col-xs-12 width_20px_res">	
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_Child()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
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
								<?php 
								$i++;
							}
						}
						else
						{ ?>
							<div id="parents_child" class="row mb-3 parents_child">
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
												
									<select name="chield_list[]" id="student_list" class="line_height_30px form-control validate[required]">
										<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
										<?php 
											foreach ($students as $label => $opt)
											{ ?>
												
												<optgroup label="<?php echo esc_attr_e('Class','school-mgt');?><?php echo ": ".$label; ?>">
												<?php foreach ($opt as $id => $name): ?>
													<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
												<?php endforeach; ?>
												</optgroup>
										<?php }  ?>
									</select>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-12 width_20px_res">	
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_Child()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
								</div>
							</div>
							<?php 
						}
					}
					else
					{ 	?>
						<div id="parents_child" class="row mb-3 parents_child">
							<div class="col-md-6 input width_80px_res">	
								<label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
								<select name="chield_list[]" id="student_list" class="line_height_30px form-control validate[required]">
									<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
									<?php 
										foreach ($students as $label => $opt)
										{ ?>
											
											<optgroup label="<?php echo esc_attr_e('Class','school-mgt');?><?php echo ": ".$label; ?>">
											<?php foreach ($opt as $id => $name): ?>
												<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
											<?php endforeach; ?>
											</optgroup>
									<?php }  ?>
								</select>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-12 width_20px_res">	
								<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_Child()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
							</div>
						</div>
						<?php 
					} ?>		
				
				</div><!-- user form -->
				<hr>
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Contact Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!-- user form -->
					<div class="row">				
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
									<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
									value="<?php if($edit){ echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
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
									<input id="zip_code" class="form-control  validate[required,custom[zipcode]]" maxlength="15" type="text"  name="zip_code" 
									value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
									<label class="" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_parent_admin_nonce' ); ?>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control country_code phonecode" name="phonecode">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>
										</div>											
									</div>
								</div>
								<div class="col-md-8 mobile_error_massage_left_margin">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="mobile_number" class="form-control btn_top validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number" value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
											<label class="" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>
						</div> 

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="phone" class="form-control validate[custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" 
									value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
									<label class="" for="phone"><?php esc_attr_e('Alternate Mobile Number','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div><!-- user form -->

				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Login Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"> <!-- user form -->  
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
				</div><!-- user form -->

				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Profile Image','school-mgt');?></h3>
				</div>
				<div class="form-body user_form"><!-- user form -->
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control upload-profile-image-frontend res_rtl_height_50px">	
									<label for="gmgt_membershipimage" class="custom-control-label custom-top-label ml-2 label_position_rtl"><?php _e('Profile Image','school-mgt');?></label>
									<div class="col-sm-12">
										<input type="hidden" id="smgt_user_avatar_url" class="form-control" name="smgt_user_avatar" value="<?php if($edit)echo esc_html( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" readonly />
										<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo esc_html($user_info->smgt_user_avatar);}elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image'];?>">
										<input id="upload_user_avatar" name="upload_user_avatar_image" type="file" class="form-control file" onchange="fileCheck(this);" value="<?php esc_html_e( 'Upload image', 'school-mgt' ); ?>" style="border:0px solid;"/>
									</div>
								</div>
				
								<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview" >
										<?php 
										if($edit) 
										{
											if($user_info->smgt_user_avatar == "")
											{ 
												?>
												<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb_new' ); ?>">
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
											<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb_new' ); ?>">
											<?php 
										} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>  <!-- user form -->
				<div class="form-body user_form"><!-- user form -->
					<div class="row">
						<div class="col-sm-6">        	
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Parent','school-mgt'); }else{ esc_attr_e('Add Parent','school-mgt');}?>" name="save_parent" class="btn btn-success save_btn"/>
						</div>      
					</div>
				</div>
			</form><!---------------- PARENT ADD FORM ---------------->
		</div><!---------- PENAL BODY ------------>
		<?php
	}
	?>
	<script>
		function add_Child()
		{
			var curr_data = {
			action: 'mj_smgt_load_child_dropdown',
			dataType: 'json'
			};
			$.post(smgt.ajax, curr_data, function(response) 
			{
				$("#parents_child").append(response);
			});	
			// $("#parents_child").append('<div class="form-body user_form"><div id="parents_child" class="row parents_child"><div class="col-md-6 input width_80px_res"><label class="ml-1 custom-top-label top" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label><select name="chield_list[]" id="student_list" class="line_height_30px form-control validate[required] max_width_100"><option value=""><?php esc_attr_e('Select Child','school-mgt');?></option><?php foreach ($students as $label => $opt){ ?><optgroup label="<?php echo "Class : ".$label; ?>"><?php foreach ($opt as $id => $name): ?><option value="<?php echo $id; ?>"><?php echo $name; ?></option><?php endforeach; ?></optgroup><?php } ?></select></div><div class="col-md-1 col-sm-3 col-xs-12 width_20px_res"><input type="image" onclick="deleteParentElement(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="remove_cirtificate input_btn_height_width"></div></div></div>'); 
		}
		// REMOVING INVOICE ENTRY
		function deleteParentElement(n)
		{
			var alert = confirm(language_translate2.delete_record_alert);
			if (alert == true){
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}
		}
	</script>
	<?php
	//---------------- VIEW PARENT TAB ---------------//
	if($active_tab == 'view_parent')
	{
		$active_tab1 = isset($_REQUEST['tab1'])?$_REQUEST['tab1']:'general';
		$parent_data=get_userdata($_REQUEST['parent_id']);
		$user_meta =get_user_meta($_REQUEST['parent_id'], 'child', true); 
		$parent_id = $_REQUEST['parent_id'];
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
									$umetadata=mj_smgt_get_user_image($parent_data->ID);
									?>
									<img class="user_view_profile_image" src="<?php if(!empty($umetadata)) {echo $umetadata; }else{ echo get_option( 'smgt_parent_thumb_new' );}?>">
									<div class="row profile_user_name">
										<div class="float_left view_top1">
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<label class="view_user_name_label"><?php echo esc_html($parent_data->display_name);?></label>
												<?php
												if($user_access['edit']=='1')
												{
													?>
													<div class="view_user_edit_btn">
														<a class="color_white margin_left_2px" href="?dashboard=user&page=parent&tab=addparent&action=edit&parent_id=<?php echo $parent_data->ID;?>">
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/edit.png"?>">
														</a>
													</div>
													<?php
												}
												?>
											</div>
											<div class="col-xl-12 col-md-12 col-sm-12 float_left_width_100">
												<div class="view_user_phone float_left_width_100">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/phone_figma.png"?>">&nbsp;+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<lable><?php echo $parent_data->mobile_number;?></label>
												</div>
											</div>
										</div>
									</div>
									<div class="row padding_top_15px_res view_user_teacher_label">
										<div class="col-xl-12 col-md-12 col-sm-12">
											<div class="view_top2">
												<div class="row view_user_teacher_label">
													<div class="col-md-12 address_student_div">
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/location.png"?>" alt="">&nbsp;&nbsp;<lable class="address_detail_page"><?php echo $parent_data->address; ?></label>
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
									<a href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&tab1=general&parent_id=<?php echo $_REQUEST['parent_id'];?>" 
									class="padding_left_0 tab <?php echo $active_tab1 == 'general' ? 'active' : ''; ?>">
									<?php esc_html_e('GENERAL', 'school-mgt'); ?></a> 
								</li>
								<li class="<?php if($active_tab1=='Child'){?>active<?php }?>">
									<a href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&tab1=Child&parent_id=<?php echo $_REQUEST['parent_id'];?>" class="padding_left_0 tab <?php echo $active_tab1 == 'Child' ? 'active' : ''; ?>">
									<?php esc_html_e('Child List', 'school-mgt'); ?></a> 
								</li>  
							</ul>
						</div>
					</div>
				</section>
				<!-- Detail Page Tabing End -->

				<!-- Detail Page Body Content Section  -->
				<section id="body_content_area" class="">
					<div class="panel-body"><!-- START PANEL BODY DIV-->
						<?php 
						// general tab start 
						if($active_tab1 == "general")
						{
							?>
							<div class="row margin_top_15px margin_left_3">
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Email ID', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo $parent_data->user_email; ?> </label>
								</div>
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Mobile Number', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels">
									+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $parent_data->mobile_number; ?>
									</label>	
								</div>

								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Date of Birth', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo mj_smgt_getdate_in_input_box($parent_data->birth_date); ?>
									</label>
								</div>
								
								<div class="col-xl-2 col-md-2 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Gender', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"> <?php echo esc_html_e(ucfirst($parent_data->gender),'school-mgt'); ?></label>	
								</div>
								
								<div class="col-xl-3 col-md-3 col-sm-12 margin_bottom_10_res">
									<label class="view_page_header_labels"> <?php esc_html_e('Relation', 'school-mgt'); ?> </label><br/>
									<label class="view_page_content_labels"><?php echo esc_html_e($parent_data->relation,'school-mgt'); ?></label>
								</div>

							</div>
							<!-- student Information div start  -->
							<div class="row margin_top_20px">
								<div class="col-xl-12 col-md-12 col-sm-12 rtl_padding_0px">
									<div class="col-xl-12 col-md-12 col-sm-12 margin_top_20px margin_top_15px_rs">
										<div class="guardian_div">
											<label class="view_page_label_heading"> <?php esc_html_e('Contact Information', 'school-mgt'); ?> </label>
											<div class="row">
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('City', 'school-mgt'); ?> </label> <br>
													<label class="view_page_content_labels"><?php if(!empty($parent_data->cit)){ echo $parent_data->city;}else{ echo "N/A";} ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('State', 'school-mgt'); ?> </label><br>
													<label class="ftext_style_capitalization view_page_content_labels"><?php if(!empty($parent_data->state)){ echo $parent_data->state; }else{ echo "N/A"; } ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Zip Code', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels"><?php echo $parent_data->zip_code; ?></label>
												</div>
												<div class="col-xl-3 col-md-3 col-sm-12 margin_top_15px">
													<label class="guardian_labels view_page_header_labels"> <?php esc_html_e('Alt. Mobile Number', 'school-mgt'); ?> </label><br>
													<label class="view_page_content_labels"><?php if(!empty($parent_data->phone)){ ?>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;<?php echo $parent_data->phone; }else{ echo "N/A"; } ?></label>
										
												</div>
												
											</div>
										</div>	
									</div>
								</div>
							</div>
							<?php
						}
						// attendance tab start 
						elseif($active_tab1 == "Child")
						{
							?>
							<script type="text/javascript">
								jQuery(document).ready(function($)
								{
									"use strict";	
									jQuery('#parents_list_detailpage').DataTable({
										//stateSave: true,
										"order": [[ 1, "asc" ]],
										dom: '<"float-right"f>rt<"row"<"col-sm-1"l><"col-sm-8"i><"col-sm-3"p>>',
										"aoColumns":[	                  
													{"bSortable": false},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true},
													{"bSortable": true}],
										language:<?php echo mj_smgt_datatable_multi_language();?>
									});
									$('.dataTables_filter').addClass('search_btn_view_page');
									$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
								});
							</script>
							<div class="">
								<div id="Section1" class="">
									<div class="row">
										<div class="col-lg-12">
											<div class="">
												<div class="card-content">
													<div class="table-responsive">
													<?php if(!empty($user_meta))
														{ ?>
														<table id="parents_list_detailpage" class="display table" cellspacing="0" width="100%">
															<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
																<tr>
																	<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
																	<th><?php esc_attr_e('Child Name & Email','school-mgt');?></th>  
																	<th><?php esc_attr_e('Mobile Number','school-mgt');?> </th>  
																	<th><?php esc_attr_e('Class','school-mgt');?></th>  
																	<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
																</tr>
															</thead>
															<tbody>
																<?php
																	foreach($user_meta as $childsdata)
																	{
																		$child=get_userdata($childsdata);
																		if(!empty($child))
																		{

																		?>
																	
																		<tr>
																			<td class="width_50px">
																			<?php 
																				if($childsdata)
																				{
																					$umetadata=mj_smgt_get_user_image($childsdata);
																				}
																				if(empty($umetadata))
																				{
																					echo '<img src='.get_option( 'smgt_student_thumb_new' ).' height="50px" width="50px" class="img-circle" />';
																				}
																				else
																					echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
																				?>
																			</td>
																			<td class="name">
																				<a class="color_black" href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $child->ID;?>"><?php echo $child->first_name." ".$child->middle_name." ".$child->last_name;?></a>
																				<br>
																				<label class="list_page_email"><?php echo $child->user_email;?></label>
																			</td>
																				<td>+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>&nbsp;&nbsp;<?php echo $child->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Mobile Number','school-mgt');?>" ></i></td>
																				<td>
																				<?php  
																				$class_id=get_user_meta($child->ID, 'class_name',true);
																				$section_name=get_user_meta($child->ID, 'class_section',true);
																				echo $classname=smgt_get_class_section_name_wise($class_id,$section_name);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class','school-mgt');?>" ></i></td>
																				<td>
																				<?php echo get_user_meta($child->ID, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Roll No.','school-mgt');?>" ></i></td>
																		</tr>
																		<?php
																		}
																	}
																
															?>
														</tbody>
														</table>
														<?php }else{
															?>
															<div class="calendar-event-new"> 
																<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
															</div>	
															<?php
														} ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
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
</div><!------------ PENAL BODY ------------->
<?php ?>