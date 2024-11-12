<?php
$role_name=mj_smgt_get_user_role(get_current_user_id());
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		"use strict";
		$('#transport_list').DataTable({
			"initComplete": function(settings, json) {
					$(".print-button").css({"margin-top": "-5%"});
				},
				//stateSave: true,
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

		$('#transport_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
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
//----------Add-update record---------------------//
	$tablename="transport";
	if(isset($_POST['save_transport']))
	{
        $nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_transpoat_admin_nonce' ) )
		{

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


			$route_data=array(
				'route_name'=>mj_smgt_address_description_validation(stripslashes($_POST['route_name'])),
				'number_of_vehicle'=>mj_smgt_onlyNumberSp_validation($_POST['number_of_vehicle']),
				'vehicle_reg_num'=>mj_smgt_address_description_validation(stripslashes($_POST['vehicle_reg_num'])),
				'smgt_user_avatar'=>$photo,
				'driver_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['driver_name']),
				'driver_phone_num'=>mj_smgt_phone_number_validation($_POST['driver_phone_num']),
				'driver_address'=>mj_smgt_address_description_validation(stripslashes($_POST['driver_address'])),
				'route_description'=>mj_smgt_address_description_validation(stripslashes($_POST['route_description'])),
				'route_fare'=>mj_smgt_address_description_validation($_POST['route_fare'])	,
				'created_by'=>get_current_user_id()
			);

			//table name without prefix
			$tablename="transport";
			if($_REQUEST['action']=='edit')
			{
				$transport_id=	array('transport_id'=>$_REQUEST['transport_id']);
				$result	=	mj_smgt_update_record($tablename,$route_data,$transport_id);
				/* if($result)
				{ */
					wp_redirect ( home_url() . '?dashboard=user&page=transport&tab=transport_list&message=2');
				 /* } */
			}
			else
			{
				$result	=	mj_smgt_insert_record($tablename,$route_data);

				if($result)
				{
					$SearchArr['{{route_name}}']	=	$_POST['route_name'];
					$SearchArr['{{vehicle_identifier}}']	=	$_POST['number_of_vehicle'];
					$SearchArr['{{vehicle_registration_number}}']	=	$_POST['vehicle_reg_num'];
					$SearchArr['{{driver_name}}']	=	$_POST['driver_name'];
					$SearchArr['{{driver_phone_number}}']	=	$_POST['driver_phone_num'];
					$SearchArr['{{driver_address}}']	=	$_POST['driver_address'];
					$SearchArr['{{route_fare}}']	=	$_POST['route_fare'];
					$SearchArr['{{school_name}}']	=	 get_option('smgt_school_name');
					$MSG = mj_smgt_string_replacement($SearchArr,get_option('school_bus_alocation_mail_content'));

					$AllUsr = mj_smgt_get_all_user_in_plugin();
					$device_token = array();
					$to = array();
					foreach($AllUsr as $key=>$usr)
					{
						$device_token[] = get_user_meta($usr->ID, 'token_id' , true);
						 $to[] = $usr->user_email;
					}
					mj_smgt_send_mail($to,get_option('school_bus_alocation_mail_subject'),$MSG);

					/* Send Push Notification */

					$title = esc_attr__('New Notification For Bus Allocation','school-mgt');
			        $text = esc_attr__('Bus Allocate to you for ','school-mgt').' '.$_POST['route_name'];
					$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
					$json = json_encode($notification_data);
					$message = MJ_smgt_send_push_notification($json);

					/* Send Push Notification */
				  wp_redirect ( home_url() . '?dashboard=user&page=transport&tab=transport_list&message=1');
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
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=transport&tab=transport_list&message=3');
				}
			}
		}


		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=transport&tab=transport_list&message=3');
		}
	}
	//----------Delete record---------------------------
	$tablename="transport";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_transport($tablename,$_REQUEST['transport_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=transport&tab=transport_list&message=3');
		}
	}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'transport_list';
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
<div class="panel-body panel-white frontend_list_margin_30px_res">
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
	}

	if($message)
	{ ?>
		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<?php echo $message_string;?>
		</div>
		<?php
	} ?>
	<?php
	if($active_tab == 'transport_list')
	{
		$user_id=get_current_user_id();
		//------- Transport DATA FOR STUDENT ---------//
		if($school_obj->role == 'student')
		{
			$retrieve_class = mj_smgt_get_all_data('transport');
		}
		//------- Transport DATA FOR TEACHER ---------//
		elseif($school_obj->role == 'teacher')
		{
			$retrieve_class = mj_smgt_get_all_data('transport');
		}
		//------- Transport DATA FOR PARENT ---------//
		elseif($school_obj->role == 'parent')
		{
			$retrieve_class = mj_smgt_get_all_data('transport');
		}
		//------- Transport DATA FOR SUPPORT STAFF ---------//
		else
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{
				$retrieve_class	=mj_smgt_get_all_transport_created_by($user_id);
			}
			else
			{
				$retrieve_class = mj_smgt_get_all_data('transport');
			}
		}
		//------- Transport DATA FOR SUPPORT STAFF ---------//
		if(!empty($retrieve_class))
		{

			?>
			<div class="panel-body">
				<div class="table-responsive">
					<form id="frm-example" name="frm-example" method="post">
						<table id="transport_list" class="display dataTable transport_datatable" cellspacing="0" width="100%">
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
								foreach ($retrieve_class as $retrieved_data)
								{
									?>
									<tr>
										<?php
										if($role_name == "supportstaff")
										{
											?>
											<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->transport_id;?>"></td>
											<?php
										}
										?>
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
													{
														echo '<img src='.$umetadata['smgt_user_avatar'].' height="50px" width="50px" class="img-circle" title="No image" />';
													} 
												?>
											</a>
										</td>
										<td>
											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" >
												<?php echo $retrieved_data->route_name;?>
											</a> 
											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Route Name','school-mgt');?>"></i>
										</td>
										<td><?php echo $retrieved_data->number_of_vehicle;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Vehicle Identifier','school-mgt');?>"></i></td>
										<td><?php echo $retrieved_data->vehicle_reg_num;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Vehicle Reg. No.','school-mgt');?>"></i></td>
										<td><?php echo $retrieved_data->driver_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Driver Name','school-mgt');?>"></i></td>
										<td><?php echo "+" .mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?> <?php echo $retrieved_data->driver_phone_num;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile No.','school-mgt');?>"></i></td>
										<td><?php if($retrieved_data->route_fare){ echo mj_smgt_get_currency_symbol().' '.number_format($retrieved_data->route_fare,2,'.',''); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Route Fare','school-mgt');?>"></i></td>
										<td class="action">
											<div class="smgt-user-dropdown">
												<ul class="" style="margin-bottom: 0px !important;">
													<li class="">
														<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
														</a>
														<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
															<li class="float_left_width_100 ">
																<a href="#" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" class="float_left_width_100 view_details_popup"><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View Transport Detail','school-mgt');?></a>
															</li>
															<?php
															if($user_access['edit']=='1')
															{
																?>
																<li class="float_left_width_100 border_bottom_menu">
																	<a href="?dashboard=user&page=transport&tab=addtransport&action=edit&transport_id=<?php echo $retrieved_data->transport_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>
																</li>
																<?php
															}
															if($user_access['delete']=='1')
															{ ?>
																<li class="float_left_width_100">
																	<a href="?dashboard=user&page=transport&tab=transport&action=delete&transport_id=<?php echo $retrieved_data->transport_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
																</li>
																<?php
															} ?>
														</ul>
													</li>
												</ul>
											</div>
										</td>
									</td>
									</tr>
								<?php
								} ?>
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
								if($user_access['delete']=='1')
								{ ?>
									<button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
									<?php
								} ?>
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
					<a href="<?php echo home_url().'?dashboard=user&page=transport&tab=addtransport';?>">
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
	{ ?>
		<div class="add_transport">
			<?php
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$transport_data= mj_smgt_get_transport_by_id($_REQUEST['transport_id']);
				}
			?>

			<div class="panel-body">
				<form name="transport_form" action="" method="post" class="form-horizontal" id="transport_form" enctype="multipart/form-data">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">

					<div class="header">
						<h3 class="first_hed"><?php esc_html_e('Transport Information','school-mgt');?></h3>
					</div>
					<div class="form-body user_form">
						<div class="row">

							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="route_name" class="form-control validate[required,custom[description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $transport_data->route_name;}?>" name="route_name">
										<label class="" for="route_name"><?php esc_attr_e('Route Name','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6 error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="number_of_vehicle" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="15" type="text" value="<?php if($edit){ echo $transport_data->number_of_vehicle;}?>" name="number_of_vehicle">
										<label class="" for="number_of_vehicle"><?php esc_attr_e('Vehicle Identifier','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="vehicle_reg_num" class="form-control validate[required,custom[address_description_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $transport_data->vehicle_reg_num;}?>" name="vehicle_reg_num">
										<label class="" for="vehicle_reg_num"><?php esc_attr_e('Vehicle Registration Number','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<?php wp_nonce_field( 'save_transpoat_admin_nonce' ); ?>
							<div class="col-md-6 error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="driver_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" value="<?php if($edit){ echo $transport_data->driver_name;}?>" name="driver_name">
										<label class="" for="driver_name"><?php esc_attr_e('Driver Name','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6 margin_bottom_15px_res">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="driver_phone_num" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" value="<?php if($edit){ echo $transport_data->driver_phone_num;}?>" name="driver_phone_num">
										<label class="" for="driver_phone_num"><?php esc_attr_e('Driver Phone Number','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-md-6 note_text_notice margin_bottom_15px_res error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 note_border margin_bottom_15px_res">
										<div class="form-field">
											<textarea name="driver_address" class="textarea_height_47px form-control validate[required,custom[address_description_validation]]" maxlength="150" id="driver_address"><?php if($edit){ echo $transport_data->driver_address;}?></textarea>
											<span class="txt-title-label"></span>
											<label class="text-area address active" for="driver_address"><?php esc_attr_e('Driver Address','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6 note_text_notice">
								<div class="form-group input">
									<div class="col-md-12 note_border margin_bottom_15px_res">
										<div class="form-field">
											<textarea name="route_description" class="textarea_height_47px form-control validate[custom[description_validation]]" maxlength="150" id="route_description"><?php if($edit){ echo $transport_data->route_description;}?></textarea>
											<span class="txt-title-label"></span>
											<label class="text-area address active" for="route_description"><?php esc_attr_e('Description','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="route_fare" class="form-control validate[required,custom[onlyNumberSp],min[0],maxSize[10]]" type="text"value="<?php if($edit){ echo $transport_data->route_fare;}?>" name="route_fare">
										<label class="" for="route_fare"><?php esc_attr_e('Route Fare','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-12 form-control upload-profile-image-frontend res_rtl_height_50px">	
										<label class="custom-control-label custom-top-label ml-2 label_position_rtl" for="photo"><?php esc_html_e('Image Upload','school-mgt');?></label>
										<div class="col-sm-12">
											<input type="hidden" id="amgt_user_avatar_url" class="form-control" name="smgt_user_avatar" value="<?php if($edit)echo ( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" />
											<input type="hidden" class="form-control" name="hidden_upload_user_avatar_image" value="<?php if($edit)echo ( $user_info->smgt_user_avatar );elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image']; ?>" />
											<input id="upload_user_avatar" class="btn_top" name="upload_user_avatar_image" onchange="fileCheck(this);" type="file" />
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<div id="upload_user_avatar_preview" >
											<?php 
											if($edit) 
											{
												if($transport_data->smgt_user_avatar == "")
												{ ?>
													<img class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb_new' ); ?>">
													<?php 
												}
												else
												{
													?>
													<img class="image_preview_css" src="<?php if($edit)echo esc_url( $transport_data->smgt_user_avatar ); ?>" />
													<?php 
												}
											}
											else 
											{
												?>
												<img class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb_new' ); ?>">
												<?php 
											}
											?>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="form-body user_form">
						<div class="row">
							<div class="col-sm-6">
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Transport','school-mgt'); }else{ esc_attr_e('Add Transport','school-mgt');}?>" name="save_transport" class="btn btn-success save_btn"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}
	?>

</div>
<?php
?>
