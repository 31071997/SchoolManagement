<script>
jQuery(document).ready(function() 
{
	var table =  jQuery('#custome_field_list').DataTable({
		"initComplete": function(settings, json) {
			$(".print-button").css({"margin-top": "-5%"});
		},
		//stateSave: true,
		"dom": 'lifrtp',
		"order": [[ 2, "asc" ]],
		"aoColumns":[	                  
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
$(document).ready(function()
	{
		jQuery('#select_all').on('click', function(e)
		{
			 if($(this).is(':checked',true))  
			 {
				$(".sub_chk").prop('checked', true);  
			 }  
			 else  
			 {  
				$(".sub_chk").prop('checked',false);  
			 } 
		});
		$('.sub_chk').on("change",function()
		{ 
			if(false == $(this).prop("checked"))
			{ 
				$("#select_all").prop('checked', false); 
			}
			if ($('.sub_chk:checked').length == $('.sub_chk').length )
			{	
				$("#select_all").prop('checked', true);
			}
		});
	});		
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'gradelist';
$tablename="grade";
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
    $obj_custome_field=new Smgt_custome_field;
	//save Custom Field Data
	if(isset($_POST['add_custom_field']))
	{	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='insert')
		{		
			//add Custom Field data
			$result=$obj_custome_field->mj_smgt_add_custom_field($_POST);		   
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=custom_field&tab=custome_field_list&message=1');
			}			
		}
		else
		{		
			//update Custom Field data
			$result=$obj_custome_field->mj_smgt_add_custom_field($_POST);			
			if($result)
			{
                wp_redirect ( home_url() . '?dashboard=user&page=custom_field&tab=custome_field_list&message=2');				
			}	
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_custome_field->mj_smgt_delete_custome_field($_REQUEST['id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=custom_field&tab=custome_field_list&message=3');
		}
	}
	if(isset($_POST['custome_delete_selected']))
	{
		if (isset($_POST["selected_id"]))
		{	
			foreach($_POST["selected_id"] as $custome_id)
			{		
				$record_id=$custome_id;
				$result=$obj_custome_field->mj_smgt_delete_selected_custome_field($record_id);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=custom_field&tab=custome_field_list&message=3');
				}	
			}	
		}
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=custom_field&tab=custome_field_list&message=3');
		}	
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'custome_field_list';
	if(isset($_REQUEST['message']))
	{
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Custom Field Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Custom Field Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Custom Field Deleted Successfully.','school-mgt');
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
	<?php
	if($active_tab == 'custome_field_list')
	{
		if($school_obj->role == 'supportstaff' OR $school_obj->role == 'teacher' )
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$custom_field_data=$obj_custome_field->mj_smgt_get_all_custom_field_data_own();
			}
			else
			{
				$custom_field_data=$obj_custome_field->mj_smgt_get_all_custom_field_data();
			}
		}
		else
		{
			$custom_field_data=$obj_custome_field->mj_smgt_get_all_custom_field_data();
		} 
		 ?>
		<div class="panel-body margin_top_40">
			<?php
			if(!empty($custom_field_data))
			{
				?>
				<div class="table-responsive">
					<form id="frm-example" name="frm-example" method="post">
						<table id="custome_field_list" class="display dataTable student_datatable" cellspacing="0" width="100%">		
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
								<tr>
									<th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
									<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
									<th><?php esc_attr_e('Form Name','school-mgt');?></th>
									<th><?php esc_attr_e('Lable','school-mgt');?></th>
									<th><?php esc_attr_e('Type','school-mgt');?></th>
									<th><?php esc_attr_e('Custom Field Id','school-mgt');?></th>
									<th><?php esc_attr_e('Validation','school-mgt');?></th>
									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$i=0;
								foreach ($custom_field_data as $retrieved_data)
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
										<td class="checkbox_width_10px">
											<input type="checkbox" name="selected_id[]" class="smgt_sub_chk sub_chk" value="<?php echo esc_html($retrieved_data->id); ?>">
										</td>	
										<td class="user_image width_50px profile_image_prescription padding_left_0">
											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/custome_field.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
											</p>
										</td>
										<td class="added"><?php echo esc_html_e($retrieved_data->form_name,'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Form Name','school-mgt');?>" ></i></td>	
										<td class="added"><?php echo esc_html($retrieved_data->field_label);?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Lable','school-mgt');?>" ></i></td>	
										<td class="added"><?php echo esc_attr_e($retrieved_data->field_type,'school-mgt'); ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Type','school-mgt');?>" ></i></td>
										<td class="added"><?php echo esc_attr_e($retrieved_data->id,'school-mgt'); ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Custom Field Id','school-mgt');?>" ></i></td>
										<td class="added"><?php echo esc_attr_e($retrieved_data->field_validation,'school-mgt'); ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Validation','school-mgt');?>" ></i></td>
										<td class="action"> 
											<div class="smgt-user-dropdown">
												<ul class="" style="margin-bottom: 0px !important;">
													<li class="">
														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
														</a>
														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
															
															<?php 
															if($user_access['edit'] == '1')
															{ ?>
																<li class="float_left_width_100 border_bottom_item">
																	<a href="?dashboard=user&page=custom_field&tab=add_custome_field&action=edit&id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a> 
																</li>
																<?php 
															} ?>
															<?php 
															if($user_access['delete'] =='1')
															{ ?>
																<li class="float_left_width_100">
																	<a href="?dashboard=user&page=custom_field&tab=custome_field_list&action=delete&id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"> </i><?php esc_attr_e('Delete','school-mgt');?></a> 
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
									$i++;
								} ?>
							</tbody>  
						</table>
						<div class="print-button pull-left">
							<button class="btn btn-success btn-sms-color button_reload">
								<input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">
								<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
							</button>
							<?php
							if($user_access['delete'] =='1')
							{ 
								?>
								<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="custome_delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
								<?php 
							} 
							?>
							
						</div>
					</form>
				</div>
				<?php
			}
			else
			{	
				if($user_access['add']=='1')
				{
					?>
					<div class="no_data_list_div no_data_img_mt_30px"> 
						<a href="<?php echo home_url().'?dashboard=user&page=custom_field&tab=add_custome_field';?>">
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
			?>
		</div>
	<?php
	}
	if($active_tab == 'add_custome_field')
	{
		?>
		<?php	
		$obj_custome_field=new Smgt_custome_field;
		$file_type_find='';
		$file_type_value='';
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$custom_field_id=$_REQUEST['id'];
			$custom_field_data=$obj_custome_field->mj_smgt_get_single_custom_field_data($custom_field_id);
		}
		?>
		<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

		<div class="panel-body margin_top_40">	
			<form class="form form-horizontal" name="custom_field_form" enctype="multipart/form-data" method="post" id="custom_field_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="custom_field_id" value="<?php if($edit){ echo esc_attr($custom_field_id); } ?>"/>
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Custom Field Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">

						<div class="col-md-6 input" id="smgt_select_class">
							<label class="ml-1 custom-top-label top" for="case_link"><?php esc_html_e('Form Name',	'school-mgt');?><span class="require-field">*</span></label>
							<select id="module_name" class="line_height_30px form-control validate[required]"  name="form_name" <?php if($edit){ ?> disabled <?php } ?>>
								<option value=""><?php esc_html_e('Select Form','school-mgt');?></option>
								<option value="student" <?php if($edit) selected('student',$custom_field_data->form_name);?>><?php esc_html_e('Student','school-mgt');?></option>
							</select>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input type="text" id="field_label" maxlength="50" class="form-control  validate[required,custom[address_description_validation]]" name="field_label" placeholder="<?php esc_html_e('Enter Name','school-mgt');?>" <?php if($edit){ ?> value="<?php echo esc_attr($custom_field_data->field_label); ?>" <?php } ?>>
									<label class="" for="case_link"><?php esc_html_e('Label',	'school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>

						<div class="col-md-6 has-feedback input">
							<label class="ml-1 custom-top-label top" for="case_link"><?php esc_html_e('Type',	'school-mgt');?><span class="require-field">*</span></label>
							<select id="field_type" class="line_height_30px form-control validate[required] dropdown_change"  name="field_type" <?php if($edit){ ?> disabled <?php } ?>>
								<option value=""><?php esc_html_e('Select Input Type','school-mgt');?></option>
								<option value="text" <?php if($edit) selected('text',$custom_field_data->field_type); ?>><?php esc_html_e('Text Box','school-mgt');?></option>
								<option value="textarea" <?php if($edit) selected('textarea',$custom_field_data->field_type);?>><?php esc_html_e('Textarea','school-mgt');?></option>
								<option value="dropdown" <?php if($edit) selected('dropdown',$custom_field_data->field_type);?>><?php esc_html_e('Dropdown','school-mgt');?></option>
								<option value="date" <?php if($edit) selected('date',$custom_field_data->field_type);?>><?php esc_html_e('Date Field','school-mgt');?></option>
								<option value="checkbox" <?php if($edit) selected('checkbox',$custom_field_data->field_type);?>><?php esc_html_e('Checkbox','school-mgt');?></option>
								<option value="radio" <?php if($edit) selected('radio',$custom_field_data->field_type);?>><?php esc_html_e('Radio','school-mgt');?></option>
								<option value="file" <?php if($edit) selected('file',$custom_field_data->field_type);?>><?php esc_html_e('File','school-mgt');?></option>
							</select>
							<?php 
							if($edit)
							{									
								$validation = explode("|",$custom_field_data->field_validation);
								$min = "";
								$max = "";
								$file_type = "";
								$file_size = "";
								$Tclass = $Dclass = NULL;
								foreach($validation as $key=>$value)
								{
									if (strpos($value, 'min') !== false)
									{
										$min = $value;
									}
									elseif(strpos($value, 'max') !== false)
									{
										$max = $value;
									}
									elseif(strpos($value, 'file_types') !== false)
									{
										$file_type = $value;
									}
									elseif(strpos($value, 'file_upload_size') !== false)
									{
										$file_size = $max;
									}	
								}
								//------------ VALUE CHECKED IN CHECKBOX EDIT TIME -----------//
								$input = preg_quote('max', '~'); // don't forget to quote input string! 
								$result_max = preg_grep('~' . $input . '~', $validation);
								
								$input = preg_quote('min', '~'); // don't forget to quote input string! 
								$result_min = preg_grep('~' . $input . '~', $validation);
								
								$exa = $custom_field_data->field_validation;
								$max_find = $max;
								$min_find = $min;
								$file_type_find = $file_type;
								$file_size_find = $file_size;
								$limit_max = substr($max_find,0,3);
								$limit_min = substr($min_find,0,3);
								$limit_value_max = substr($max_find,4);
								$limit_value_min = substr($min_find,4);
								$file_type_value = substr($file_type_find,11);
								$file_size_value = substr($file_size_find,17);
								
								if($custom_field_data->field_type=='dropdown' || $custom_field_data->field_type=='checkbox' || $custom_field_data->field_type=='radio'  )
								{
									$Tclass="disabled";
									$Dclass="disabled";										
								}
								else if($custom_field_data->field_type=='text' || $custom_field_data->field_type=='textarea')
								{
									$Dclass="disabled";
									$Tclass=NULL;									
								}
								else if($custom_field_data->field_type=='date')
								{
									$Tclass="disabled";
									$Dclass=NULL;
								}
							}			 
							?>
						</div>

						<div class="col-md-6 mb-3 smgt_main_custome_field rtl_margin_top_15px">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="">
											<label class="custom-top-label margin_left_0" for="case_link"><?php esc_html_e('Validation','school-mgt');?><span class="require-field">*</span></label>
											<div class="row custom-control custom-checkbox mr-1 margin_left_custom_field smgt_Validation_label has-feedback">

												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 margin_left_custom_field_new checkbox-inline mr-2 display_flex">
													<input type="checkbox" name="validation[]"  value="nullable" class="nullable_rule margin_top_0" <?php if($edit){ if(in_array("nullable",$validation)){ echo 'checked'; } }else{ echo 'checked'; } ?>><span class="span_left_custom" style="margin-bottom: -5px;"><?php esc_html_e('Nullable','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 display_flex">
													<input type="checkbox" name="validation[]"  value="required" class="required_rule margin_top_0" <?php if($edit){ if(in_array("required",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Required','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox"  name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="numeric" id="only_number_id" class="only_number margin_top_0" <?php if($edit){ if(in_array("numeric",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e ('Only Number','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="alpha" id="only_char_id" class="only_char margin_top_0"<?php if($edit){ if(in_array("alpha",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Only Character','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?>  value="alpha_space" id="char_space_id" class="char_space margin_top_0" <?php if($edit){ if(in_array("alpha_space",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Character with Space','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" id="char_num_id" class="char_num margin_top_0" <?php if($edit){ echo $Tclass; } ?> name="validation[]"  value="alpha_num " <?php if($edit){ if(in_array("alpha_num",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Number & Character','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" id="email_id" class="email margin_top_0" <?php if($edit){ echo $Tclass; } ?>  name="validation[]"  value="email" <?php if($edit){ if(in_array("email",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Email','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> class="opentext max margin_top_0" id="max_value" value="max" <?php if($edit){ if($result_max){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Maximum','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> class="opentext min margin_top_0" id="min_value" value="min" <?php if($edit){ if($result_min){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Minimum','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" class="url margin_top_0" name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="url" <?php if($edit){ if(in_array("url",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('URL','school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date0" class="date margin_top_0" value="before_or_equal:today" <?php if($edit){ if(in_array("before_or_equal:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("Before Or Equal(Today's Date)",'school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date1"  class="date margin_top_0"  value="date_equals:today" <?php if($edit){ if(in_array("date_equals:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("Today's Date",'school-mgt'); ?></span>
												</label>
												<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-2 checkbox-inline m-0 file_disable display_flex">
													<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date2"  class="date margin_top_0"   value="after_or_equal:today" <?php if($edit){ if(in_array("after_or_equal:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("After Or Equal(Today's Date)",'school-mgt'); ?></span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php 
						if($edit)
						{
							$custom_meta=$obj_custome_field->mj_smgt_get_single_custom_field_dropdown_meta_data($custom_field_id);	
							if($custom_field_data->field_type=='dropdown')
							{	
								?>	
								<div class="sub_cat">
									<div class="form-group row">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<div class="form-group input">
												<div class="col-md-6 form-control">
													<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] d_label" placeholder="<?php esc_html_e('','school-mgt');?>">
													<label class="" for="case_link"><?php esc_html_e('Dropdown Label','school-mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_drop" value="<?php esc_html_e('Add More','school-mgt'); ?>">
										</div>
									</div>
								</div>				
								
								<div class="row mb-3">
									<div class="col-md-12 drop_label">
										<?php
										if(!empty($custom_meta))
										{	
											foreach($custom_meta as $custom_metas)
											{
												?>												
												<div class="badge badge-danger label_data custom-margin" >
													<input type="hidden" value="<?php echo $custom_metas->option_label; ?>" name="d_label[]"><span><?php echo $custom_metas->option_label; ?></span><a href="#"><i label_id="<?php echo $custom_metas->id; ?>" class="fa fa-trash font-medium-2 delete_d_label" aria-hidden="true"></i></a>
												</div>
												&nbsp;
												<?php
											}
										}
										?>
									</div>
								</div>
								<?php
							}
							elseif($custom_field_data->field_type=='checkbox')
							{
								?>
								<div class="checkbox_cat">
									<div class="form-group row">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<div class="form-group input">
												<div class="col-md-6 form-control">
													<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] c_label" placeholder="<?php esc_html_e('','school-mgt');?>">
													<label class="" for="case_link"><?php esc_html_e('Checkbox Label','school-mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>

										<div class="col-md-2">
											<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_checkbox" value="<?php esc_html_e('Add More','school-mgt'); ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 checkbox_label">
										<?php
										if(!empty($custom_meta))
										{	
											foreach($custom_meta as $custom_metas)
											{
												?>												
												<div class="badge badge-danger label_data label_checkbox custom-margin"  >
													<input type="hidden" value="<?php echo $custom_metas->option_label; ?>"  name="c_label[]"><span><?php echo $custom_metas->option_label; ?></span><a href="#"><i label_id="<?php echo $custom_metas->id; ?>" class="fa fa-trash font-medium-2 delete_c_label" aria-hidden="true"></i></a>
												</div>
												&nbsp;
												<?php
											}
										}?>										
									</div>
								</div>
								<?php
							}
							elseif($custom_field_data->field_type=='radio')
							{
								?>
								<div class="radio_cat">
									<div class="form-group row">
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<div class="form-group input">
												<div class="col-md-6 form-control">
													<input type="text" maxlength="30" class="form-control r_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
													<label class="" for="case_link"><?php esc_html_e('Radio Label','school-mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_radio" value="<?php esc_attr_e('Add More','school-mgt'); ?>">
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-md-12 radio_label ">
										<?php
										if(!empty($custom_meta))
										{	
											foreach($custom_meta as $custom_metas)
											{
												?>												
												<div class="badge badge-danger label_data label_radio custom-margin custom_css" style="font-size:15px !important;">
													<input type="hidden" value="<?php echo $custom_metas->option_label; ?>"  name="r_label[]"><span><?php echo $custom_metas->option_label; ?></span><a href="#" class="ml_5"><i class="fa fa-trash font-medium-2 delete_r_label" label_id="<?php echo $custom_metas->id; ?>" aria-hidden="true"></i></a>
												</div>
												&nbsp;
												<?php
											}
										}
										?>
									</div>
								</div>
								<?php
							}
							?>
							<div class="file_type_and_size">
								<?php																		
								if(strpos($file_type_find, 'file_types') !== false)
								{
									?>
									<style>
									.file_disable
									{
										opacity: 0.6;
										cursor: not-allowed;
										pointer-events: none;
									}
									</style>
									<div class="form-group row mb-3 margin_top_custome">
									
										<input type="hidden" name="validation[]" value="<?php echo $file_type_find; ?>" class="file_types_value"> 

										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<div class="form-group input">
												<div class="col-md-6 form-control">
													<input class="form-control file_edit file_types_input validate[required]" maxlength="100" type="text" id="userinput11" value="<?php echo $file_type_value; ?>">
													<label class="" for="case_link"><?php esc_html_e('File Types (like png,jpg,pdf,doc)','school-mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
									<?php
								}																	
								if(strpos($file_size_find, 'file_upload_size') !== false)
								{
									?>
										<input type="hidden" name="validation[]" value="<?php echo $file_size_find; ?>" class="file_size_value"> 
										<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<div class="form-group input">
												<div class="col-md-6 form-control">
													<input class="form-control file_size_input validate[required]" maxlength="30" type="text" id="userinput9" value="<?php echo $file_size_value; ?>">
													<label class="" for="case_link"><?php esc_html_e('File Upload Size(kb','school-mgt');?><span class="require-field">*</span></label>
												</div>
											</div>
										</div>
									
									</div>										
								<?php
								}	
								?>	
							</div>	
								
							<div class="">									
								<?php
								if(strpos($max_find, 'max') !== false)
								{
									?>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control"  id="min_limit">
												<input type="number" class="form-control validate[required,custom[onlyNumberSp]]" value="<?php echo $limit_value_min; ?>"  id="min" >
												<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<?php
								}
								else	
								{
									?>
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control"  id="min_limit" style="display:none;">
												<input type="number" class="form-control validate[required,custom[onlyNumberSp]]"  id="min" >
												<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<?php
								}									
								if(strpos($min_find, 'min') !== false)
								{
									?>	
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control"  id="max_limit">
												<input type="number" class="form-control validate[required,custom[onlyNumberSp]]" value="<?php echo $limit_value_max; ?>"  id="max" >
												<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<?php
								}
								else	
								{
									?>	
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control" id="max_limit" style="display:none;">
												<input type="number" class="form-control validate[required,custom[onlyNumberSp]]"  id="max" >
												<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<?php
								}
								?>
							</div>
							<?php									
						}
						else
						{
							?>
							<div class="sub_cat" style="display:none;">
								<div class="form-group row mb-3">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control has-feedback">
												<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] d_label d_label_new" placeholder="<?php esc_html_e('','school-mgt');?>">
												<label class="" for="case_link"><?php esc_html_e('Dropdown Label','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_drop" value="<?php esc_attr_e('Add More','school-mgt'); ?>">
									</div>
								</div>
							</div>				
							<div class="row sub_cat">
								<div class="col-md-12 drop_label">
								</div>
							</div>	


							<div class="checkbox_cat" style="display:none;">
								<div class="form-group row mb-3">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control">
												<input type="text" maxlength="30" class="form-control c_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
												<label class="" for="case_link"><?php esc_html_e('Checkbox Label','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_checkbox" value="<?php esc_html_e('Add More','school-mgt'); ?>">
									</div>
								</div>
							</div>
							<div class="row checkbox_cat ">
								<div class="col-md-12 checkbox_label">
								</div>
							</div>
							
							<div class="radio_cat" style="display:none;">
								<div class="form-group row mb-3">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control">
												<input type="text" maxlength="30" class="form-control r_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
												<label class="" for="case_link"><?php esc_html_e('Radio Label','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_radio" value="<?php esc_html_e('Add More','school-mgt'); ?>">
									</div>
								</div>
							</div>
							<div class="row radio_cat">
								<div class="col-md-12 radio_label ">
								</div>
							</div>
							

							<div class="file_type_and_size" style="display:none;">
								<div class="form-group row mb-3 margin_top_custome">
									<input type="hidden" name="validation[]" value="<?php echo $file_type_find; ?>" class="file_types_value"> 
									
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control">
												<input class="form-control file_types_input validate[required]" maxlength="100" type="text" id="userinput11" value="<?php echo $file_type_value; ?>">
												<label class="" for="case_link"><?php esc_html_e('File Types (like png,jpg,pdf,doc)','school-mgt');?><span class="require-field">*</span></label>
											</div>
										</div>
									</div>
										
									<input type="hidden" name="validation[]" value="" class="file_size_value">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<div class="form-group input">
											<div class="col-md-6 form-control">
												<input class="form-control file_size_input validate[required]" maxlength="30" type="text" id="userinput9" >
												<label class="" for="case_link"><?php esc_html_e('File Upload Size(kb)','school-mgt');?><span class="require-field">*</span></label>	
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input mb-0">
									<div class="col-md-6 form-control" id="min_limit" style="display:none;">
										<input type="number" class="form-control validate[required,custom[onlyNumberSp]]"  id="min" >
										<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>	
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input mb-0">
									<div class="col-md-6 form-control" id="max_limit" style="display:none;">
										<input type="number" class="form-control validate[required,custom[onlyNumberSp]]"  id="max" >
										<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<?php
						}
						?>		
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 margin_bottom_15px_res">
							<div class="form-group">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label " for="case_link"><?php esc_html_e('Visibility','school-mgt');?><span class="require-field">*</span></label>
											<input type="checkbox"  value="1" <?php if($edit){  echo checked($custom_field_data->field_visibility,'1'); }else{  echo 'checked'; } ?> class="custom-control-input hideattar" name="field_visibility" >&nbsp;
											<label class=""  style="margin-bottom: -5px;" for="colorCheck1"><?php esc_html_e('Yes','school-mgt'); ?></label>
										</div>
									</div>		 
								</div>
							</div>		
						</div>	
					<div class="col-md-6 rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control input_height_50px">
								<div class="row padding_radio">
									<div class="input-group input_checkbox">
										<label class="custom-top-label"><?php esc_html_e('Show field in student list','school-mgt');?></label>													
										<div class="checkbox checkbox_lebal_padding_8px">
											<label class="display_flex">
											<input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="show_in_table" value="1" <?php if($edit){  echo checked($custom_field_data->show_in_table,'1');} ?>/><?php esc_html_e('Yes','school-mgt');?>
											</label>
										</div>
									</div>
								</div>												
							</div>
						</div>
					</div>	 
				</div>	
				<div class="form-body user_form mt-3">
					<div class="row">
						<div class="col-sm-6">           	
							<input type="submit" id="add_custom_field" value="<?php if($edit){ esc_attr_e('Submit','school-mgt'); }else{ esc_attr_e('Add Custom Field','school-mgt');}?>" name="add_custom_field" class="btn btn-success save_btn" />
						</div>    
					</div>
				</div>        
			</form>
			<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/customfield.js'; ?>" ></script>
			<?php
	}
	?>
</div> 
<?php ?>