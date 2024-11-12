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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('custom_field');
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
			if ('custom_field' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('custom_field' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('custom_field' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
<?php 
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
				wp_redirect ( admin_url() . 'admin.php?&page=custom_field&tab=custome_field_list&message=1');
			}			
		}
		else
		{		
			//update Custom Field data
			$result=$obj_custome_field->mj_smgt_add_custom_field($_POST);			
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?&page=custom_field&tab=custome_field_list&message=2');	
			}	
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{		
		$result=$obj_custome_field->mj_smgt_delete_custome_field($_REQUEST['id']);
		
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=custom_field&tab=custome_field_list&message=3');
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
				wp_redirect ( admin_url() . 'admin.php?page=custom_field&tab=custome_field_list&message=3');
			}	
		}
		else
		{
			?>
				<div class="alert_msg alert alert-warning alert-dismissible " role="alert">
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					</button>
					<?php esc_html_e('Please Select At least One Record.','school-mgt');?>
				</div>
		<?php		
		}	
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=custom_field&tab=custome_field_list&message=3');
		}	
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'custome_field_list';
?>
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Custom Field Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Custom Field  Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Custom Field Deleted Successfully.','school-mgt');
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
		<div class="row"><!-- row -->
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
					<?php
					if($active_tab == 'custome_field_list')
					{	
						$retrieve_class=$obj_custome_field->mj_smgt_get_all_custom_field_data();
						if(!empty($retrieve_class))
						{	
							?>
							<script>
								jQuery(document).ready(function() 
								{
									var table =  jQuery('#custome_field_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										// stateSave: true,
										responsive: true,
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

									$("#custome_delete_selected").on('click', function()
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
							</script>
							<div class="panel-body"><!-- panel-body -->
								<div class="table-responsive">
									<form id="frm-example" name="frm-example" method="post">	
										<table id="custome_field_list" class="display" cellspacing="0" width="100%">
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
														<td class="checkbox_width_10px">
															<input type="checkbox" name="selected_id[]" class="smgt_sub_chk sub_chk" value="<?php echo esc_html($retrieved_data->id); ?>">
														</td>	
														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/custome_field.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
															</p>
														</td>
														<td class="added">
															<?php  esc_attr_e($retrieved_data->form_name,'school-mgt');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Form Name','school-mgt');?>" ></i>
														</td>	
														<td class="added">
															<?php  esc_attr_e($retrieved_data->field_label);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Lable','school-mgt');?>" ></i>
														</td>	
														<td class="added">
															<?php esc_attr_e($retrieved_data->field_type,'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Type','school-mgt');?>" ></i>
														</td>
														<td class="added">
															<?php esc_attr_e($retrieved_data->id,'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Custom Field Id','school-mgt');?>" ></i>
														</td>
														<td class="added">
															<?php  esc_attr_e($retrieved_data->field_validation,'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Validation','school-mgt');?>" ></i>
														</td>
														<td class="action"> 
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																			<?php 
																			if($user_access_edit == '1')
																			{ ?>
																				<li class="float_left_width_100 border_bottom_item">
																					<a href="?page=custom_field&tab=add_custome_field&action=edit&id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a> 
																				</li>
																					<?php
																				} ?>
																			<?php 
																			if($user_access_delete =='1')
																			{ ?>
																				<li class="float_left_width_100">
																					<a href="?page=custom_field&tab=custome_field_list&action=delete&id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i><?php esc_attr_e('Delete','school-mgt');?></a> 
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
												<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
												<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
											</button>
											<?php if($user_access_delete =='1')
											{ ?>
												<!-- <input id="custome_delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="custome_delete_selected" class="btn btn-danger delete_selected"/> -->
												<button id="custome_delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="custome_delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php 
											} ?>
										</div>
									</form>
								</div>
							</div><!-- panel-body -->
							<?php 
						}
						else
						{
							if($user_access_add=='1')
							{
								?>
								<div class="no_data_list_div"> 
									<a href="<?php echo admin_url().'admin.php?page=custom_field&tab=add_custome_field';?>">
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
					if($active_tab == 'add_custome_field')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/customfield/add_customfield.php';
						
					}
					?>
				</div><!-- smgt_main_listpage -->
	 		</div><!-- col-md-12 -->
	 	</div><!-- row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->
<?php ?>