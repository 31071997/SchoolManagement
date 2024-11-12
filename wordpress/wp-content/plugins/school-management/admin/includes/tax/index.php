<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
$active_tab = isset($_GET['tab'])?$_GET['tab']:'tax';
$obj_tax = new tax_Manage(); 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('class');
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
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('class' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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

//------------------ SAVE TAX --------------------//
if(isset($_POST['save_tax']))	
{
	
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_tax_admin_nonce' ) )
    {
		if(isset($_POST['action']) && $_POST['action'] == "edit")
		{
			$result=$obj_tax->mj_smgt_insert_tax($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_tax&tab=tax&message=2');
			}
		}
		else
		{
			$result=$obj_tax->mj_smgt_insert_tax($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_tax&tab=tax&message=1');
			}
		}
		

		
	}
}
//------------------ DELETE TAX --------------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=$obj_tax->mj_smgt_delete_tax($_REQUEST['tax_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_tax&tab=tax&message=3');
		exit;
	}
}

//------------------ DELETE MULTIPLE TAX --------------------//
if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		{
			foreach($_REQUEST['id'] as $id)
			{
				$result=$obj_tax->mj_smgt_delete_tax($id);
			}
		}
		
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_tax&tab=tax&message=3'); 
			exit;
		}
	}
?>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";	
		$('#tax_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	
	});
</script>
<div class="list_padding_5px"> <!--------- list page padding ---------->
	<div  id="" class="class_list"> <!--------- list page main wrapper ---------->
		<div class="panel-white"> <!------ penal white -------->
			<?php
			$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
			switch($message)
			{
				case '1':
					$message_string = esc_attr__('Tax Added successfully.','school-mgt');
					break;
				case '2':
					$message_string = esc_attr__('Tax Updated Successfully.','school-mgt');
					break;	
				case '3':
					$message_string = esc_attr__('Tax Deleted Successfully.','school-mgt');
					break;
			}
			if($message)
			{ 
				?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php 
			} 
			?>
			<div class="panel-body">
				<?php
				if($active_tab == 'tax')
				{
					$retrieve_tax = $obj_tax->MJ_smgt_get_all_tax();

					if(!empty($retrieve_tax))
					{
						?>
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#tax_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
											$('#class_list th:first-child').removeClass('sorting_asc');
										},
									//stateSave: true,
									"ordering": true,
									"dom": 'lifrtp',
									"aoColumns":[	                  
										{"bSortable": false, "className": 'sorting_disabled'},
												{"bSortable": false},
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
						<div class="panel-body">
							<div class="table-responsive">
								<form id="frm-example" name="frm-example" method="post">
									<table id="tax_list" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
												<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
												<th><?php esc_attr_e('Tax Title','school-mgt');?></th>
												<th><?php esc_attr_e('Tax Value(%)','school-mgt');?></th>
												<th><?php esc_attr_e('Created Date','school-mgt');?></th>
												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$i=0;
											foreach ($retrieve_tax as $retrieved_data)
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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->tax_id;?>"></td>
													<td class="user_image width_50px profile_image_prescription">	
														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/tax.png"?>" alt="" class="massage_image center image_icon_height_25px margin_top_3px">
														</p>
													</td>
													<td ><?php if($retrieved_data->tax_title){ echo $retrieved_data->tax_title; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Tax Title','school-mgt');?>" ></i></td>
													<td><?php if($retrieved_data->tax_value){ echo $retrieved_data->tax_value; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Tax Value(%)','school-mgt');?>" ></i></td>
													<td><?php if($retrieved_data->created_date){ echo mj_smgt_getdate_in_input_box($retrieved_data->created_date); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Created Date','school-mgt');?>" ></i></td>
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
																		{
																			?>
																			<li class="float_left_width_100 border_bottom_menu">
																				<a href="?page=smgt_tax&tab=add_tax&action=edit&tax_id=<?php echo $retrieved_data->tax_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																			</li>

																			<?php 
																		} 
																		if($user_access_delete =='1')
																		{
																			?>
																			<li class="float_left_width_100 ">
																				<a href="?page=smgt_tax&tab=tax&action=delete&tax_id=<?php echo $retrieved_data->tax_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
										} ?>
										</tbody>
									</table>
									<div class="print-button pull-left">
										<button class="btn-sms-color button_reload">
											<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
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
							<div class="no_data_list_div"> 
								<a href="<?php echo admin_url().'admin.php?page=smgt_tax&tab=add_tax';?>">
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
				if($active_tab == 'add_tax')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/tax/add-tax.php';
				}
				?>
			</div>
		</div>
	</div>
</div>