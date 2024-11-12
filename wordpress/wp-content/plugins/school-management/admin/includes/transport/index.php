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
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('transport');
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
			if ('transport' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('transport' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('transport' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	$('#transport_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>     
			<div class="assign_route"></div>    
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<?php 
	// This is Class at admin side!!!!!!!!! 
	//----------Add-update record---------------------//
	$tablename="transport";
	if(isset($_POST['save_transport']))
	{	
        $nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_transpoat_admin_nonce' ) )
		{
			if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
			{
				$photo=$_POST['smgt_user_avatar'];
			}
			else
			{
				$photo="";
			}
			
			$route_data=array(
				'route_name'=>mj_smgt_address_description_validation(stripslashes($_POST['route_name'])),
				'number_of_vehicle'=>mj_smgt_onlyNumberSp_validation($_POST['number_of_vehicle']),
				'vehicle_reg_num'=>mj_smgt_address_description_validation(stripslashes($_POST['vehicle_reg_num'])),
				'smgt_user_avatar'=>$photo,
				'driver_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['driver_name']),
				'driver_phone_num'=>mj_smgt_phone_number_validation($_POST['driver_phone_num']),
				'driver_address'=>mj_smgt_address_description_validation(stripslashes($_POST['driver_address'])),
				'route_description'=>mj_smgt_address_description_validation(stripslashes($_POST['route_description'])),					
				'route_fare'=>mj_smgt_address_description_validation($_POST['route_fare']),
				'created_by'=>get_current_user_id()
			);
					
			//table name without prefix
			$tablename="transport";
			if($_REQUEST['action']=='edit')
			{
				$transport_id=	array('transport_id'=>$_REQUEST['transport_id']);
				$result	=	mj_smgt_update_record($tablename,$route_data,$transport_id);
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=2');
			}
			else
			{		
				$result	=	mj_smgt_insert_record($tablename,$route_data);
				
				if($result)
				{	
				   	wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=1');
				}
			}
	    }
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		{
			foreach($_REQUEST['id'] as $id)
			{
				$result=mj_smgt_delete_transport($tablename,$id);
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=3');	
			}
		}
		
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=3');				
		}
	}

	//---------------- ASSIGN ROUTE -----------------//
	if(isset($_REQUEST['save_assign_route']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_assign_transpoat_admin_nonce' ) )
		{
			$assign_route_table_name = "assign_transport";

			$assign_transport_data = mj_smgt_get_assign_transport_by_id($_POST['transport_id']);
			$transport_data = mj_smgt_get_transport_by_id($_POST['transport_id']);

			$assign_route_data=array(
				'transport_id'=>$_POST['transport_id'],
				'route_name'=>mj_smgt_address_description_validation($transport_data->route_name),
				'route_fare'=>mj_smgt_address_description_validation($transport_data->route_fare),
				'route_user' => json_encode($_POST['selected_users']),
				'created_by'=>get_current_user_id()
			);

			if(!empty($assign_transport_data))
			{
				$transport_id=	array('transport_id'=>$_REQUEST['transport_id']);
				$result	= mj_smgt_update_record($assign_route_table_name,$assign_route_data,$transport_id);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=assign_transport_list&message=4');
				}
			}
			else
			{
				$result	= mj_smgt_insert_record($assign_route_table_name,$assign_route_data);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=assign_transport_list&message=5');
				}
			}

			
		}
	}

	//----------Delete record---------------------------
	$tablename="transport";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_transport($tablename,$_REQUEST['transport_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=3');					
			}
	}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'transport';
?>
<div class="page-inner"><!--------- Page Inner ------->
	<div class="transport_list main_list_margin_5px" id=""> 
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Transport Added successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Transport Updated successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Transport Deleted Successfully.','school-mgt');
				break;
			case '4':
				$message_string = esc_attr__('Assign Transport Route Updated Successfully.','school-mgt');
				break;
			case '5':
				$message_string = esc_attr__('Assign Transport Route Inserted Successfully.','school-mgt');
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
		<div class="panel-white"><!--------- penal White ------->
			<div class="panel-body"> <!--------- penal body ------->
   				<?php
				if($active_tab == 'transport')
				{		
					$retrieve_class = mj_smgt_get_all_data($tablename);
					if(!empty($retrieve_class))
					{	
						?>  
						<script type="text/javascript">
							jQuery(document).ready(function($)
							{
								"use strict";	
								var table =  jQuery('#transport_list').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true,
									responsive: true,
									"order": [[ 2, "asc" ]],
									"dom": 'lifrtp',
									"aoColumns":[	                  
												{"bSortable": false},
												{"bSortable": false},
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
								jQuery('#checkbox-select-all').on('click', function(){
								
									var rows = table.rows({ 'search': 'applied' }).nodes();
									jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
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
									<table id="transport_list" class="display admin_transport_datatable" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
												<th><?php  _e( 'Photo', 'school-mgt' ) ;?></th>
												<th><?php echo esc_attr_e( 'Route Name', 'school-mgt' ) ;?></th>
												<th><?php echo esc_attr_e( 'Vehicle Identifier', 'school-mgt' ) ;?></th>
												<th ><?php echo esc_attr_e( 'Vehicle Reg. No.', 'school-mgt' ) ;?></th>				
												<th><?php echo esc_attr_e( 'Driver Name', 'school-mgt' ) ;?></th>
												<th><?php echo esc_attr_e( 'Mobile No.', 'school-mgt' ) ;?></th>
												<th><?php echo esc_attr_e( 'Route Fare', 'school-mgt' ) ;?>(<?php echo mj_smgt_get_currency_symbol();?>)</th>
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
													<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->transport_id;?>"></td>
													<td class="user_image">
														<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" >
															<?php
																$tid=$retrieved_data->transport_id;
																$umetadata=mj_smgt_get_user_driver_image($tid);
																if(empty($umetadata) || $umetadata['smgt_user_avatar'] == "")
																{	
																	echo '<img src="'.get_option( 'smgt_driver_thumb_new' ).'" height="50px" width="50px" class="img-circle" />';
																}
																else
																echo '<img src='.$umetadata['smgt_user_avatar'].' height="50px" width="50px" class="img-circle" />';
															?>		
														</a>		
													</td>
													<td>
														<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" >
															<?php echo $retrieved_data->route_name;?>
														</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Route Name','school-mgt');?>"></i></td>
													<td><?php echo $retrieved_data->number_of_vehicle;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Vehicle Identifier','school-mgt');?>"></i></td> 
													<td><?php echo $retrieved_data->vehicle_reg_num;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Vehicle Reg. No.','school-mgt');?>"></i></td>				
													<td><?php echo $retrieved_data->driver_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Driver Name','school-mgt');?>"></i></td>
													<td><?php echo "+" .mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?> <?php echo $retrieved_data->driver_phone_num;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile No.','school-mgt');?>"></i></td>
													<td><?php echo mj_smgt_get_currency_symbol(); ?> <?php echo number_format($retrieved_data->route_fare,2,'.','');?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Route Fare','school-mgt');?>"></i></td>         
													<td class="action">  
														<div class="smgt-user-dropdown">
															<ul class="" style="margin-bottom: 0px !important;">
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																	</a>
																	<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																		<li class="float_left_width_100 ">
																			<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Transport Detail','school-mgt');?></a>
																		</li>
																		<?php
																		if($user_access_edit == '1')
																		{
																			?>
																			<li class="float_left_width_100 border_bottom_menu">
																				<a href="?page=smgt_transport&tab=addtransport&action=edit&transport_id=<?php echo $retrieved_data->transport_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
																			</li>
																			<?php 
																		} 
																		if($user_access_delete =='1')
																		{
																			?>
																			<li class="float_left_width_100 ">
																				<a href="?page=smgt_transport&tab=transport&action=delete&transport_id=<?php echo $retrieved_data->transport_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
									<div class="print-button pull-left">
										<button class="btn-sms-color button_reload">
											<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
											<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
										</button>
										<?php if($user_access_delete =='1')
										{ 
											?>
											<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
											<?php
										}
										?>
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
								<a href="<?php echo admin_url().'admin.php?page=smgt_transport&tab=addtransport';?>">
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
				if($active_tab == 'addtransport')
				{
					require_once SMS_PLUGIN_DIR. '/admin/includes/transport/add-transport.php';
				}
				?>
	 		</div><!--------- penal body ------->
		</div><!--------- penal White ------->
	</div>
</div><!--------- Page Inner ------->
