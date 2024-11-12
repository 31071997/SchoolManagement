<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
$obj_hostel=new smgt_hostel;
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('hostel');
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
			if ('hostel' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('hostel' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('hostel' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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
	jQuery(document).ready(function($){
		"use strict";	

		$('#bed_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#bed_form_new').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#hostel_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#room_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('.datepicker').datepicker({
					defaultDate: null,
					changeMonth: true,
					changeYear: true,
					yearRange:'-75:+10',
					dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
					
				});
				
				function checkselectvalue(value,i) {
				
					$('#assigndate_'+i).hide();
					$('.students_list_'+i).removeClass('student_check');
					$(".student_check").each(function()
					{
						var valueSelected1=$(this).val();
						if(valueSelected1 == value)
						{
							alert(language_translate2.select_different_student_alert);
							$('.students_list_'+i).val('0');	
							return false;	
						}
					});
					var value=$('.students_list_'+i).val();
					if(value =='0' )
					{
						$('#assigndate_'+i).hide();
						var name=0;
						$(".new_class").each(function()
						{
							var new_class=$(this).val();
							if(new_class != '0')
							{
								name=name+1;
							}
						});
						if(name < 1)
						{
							$("#Assign_bed").prop("disabled", true);
						}
					}
					else
					{
						$('#assigndate_'+i).show();
						$("#Assign_bed").prop("disabled", false);
					}
					$('.students_list_'+i).addClass('student_check');
				}
				
				
		$('body').on('change','.student_check',function(){
				// alert(this);
				let index = $(this).attr('data-index');

				
				if($('#students_list_'+index).val() != 0)
				{
					$('#assign_date_'+index).addClass('validate[required]');
				}else{
					$('#assign_date_'+index).removeClass('validate[required]');

				}

		});

		$(".assign_room_for_alert").on('click',function()
		{	
			var select_student = $(".select_student").val();
            if (select_student == "0") {
                alert(language_translate2.one_assign_room__alert);
                return false;
            }
            return true;
        });

	});
</script>
<!-- POP up code -->

<!-- End POP-UP Code -->
<?php 
$obj_hostel=new smgt_hostel;
$tablename='smgt_hostel'; 
//----------insert and update--------------------
if(isset($_POST['save_hostel']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_hostel_admin_nonce' ) )
	{
		if($_REQUEST['action']=='edit')
		{
			$result=$obj_hostel->mj_smgt_insert_hostel($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=hostel_list&message=2');
			}
		}
		else
		{
			$result=$obj_hostel->mj_smgt_insert_hostel($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=hostel_list&message=1');
			}
		}
	}
}
//---------delete record--------------------
	 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=$obj_hostel->mj_smgt_delete_hostel($_REQUEST['hostel_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=hostel_list&message=3');
	}
}
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=$obj_hostel->mj_smgt_delete_hostel($id);
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=hostel_list&message=3');
	}
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=hostel_list&message=3');
	}
}	
//----------insert and update ROOM--------------------
if(isset($_POST['save_room']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_room_admin_nonce' ) )
	{
		if($_REQUEST['action']=='edit_room')
		{
			$result=$obj_hostel->mj_smgt_insert_room($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=5');
			}
		}
		else
		{
			$result=$obj_hostel->mj_smgt_insert_room($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=4');
			}
		}
	}
}
//--------- delete record ROOM --------------------
	 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_room')
{
	$result=$obj_hostel->mj_smgt_delete_room($_REQUEST['room_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=6');
	}
}
if(isset($_REQUEST['delete_selected_room']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=$obj_hostel->mj_smgt_delete_room($id);
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=6');
	}
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=6');
	}
}	
//----------insert and update Beds--------------------
if(isset($_POST['save_bed']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_bed_admin_nonce' ) )
	{
		if($_REQUEST['action']=='edit_bed')
		{
			$bed_id=$_REQUEST['bed_id'];
			$room_id=$_REQUEST['room_id'];
				
			global $wpdb;
			$table_smgt_beds=$wpdb->prefix.'smgt_beds';
			$result_bed =$wpdb->get_results("SELECT * FROM $table_smgt_beds WHERE room_id=$room_id and id !=".$bed_id);
			$bed=count($result_bed);
			$bed_capacity=mj_smgt_get_bed_capacity_by_id($room_id);
			if($bed < $bed_capacity)
			{
				$result=$obj_hostel->mj_smgt_insert_bed($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=bed_list&message=8');
				}
			}
			else
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=add_bed&action=edit_bed&bed_id='.$bed_id.'&message=10');
				die;
			}
		}
		else
		{
			$assign_bed=mj_smgt_hostel_room_bed_count($_POST['room_id']);
			$bed_capacity=mj_smgt_get_bed_capacity_by_id($_POST['room_id']);
			$bed_capacity_int=(int)$bed_capacity;
				
			if($assign_bed >= $bed_capacity_int)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=add_bed&message=10');
				die;
			}
			else
			{
				$result=$obj_hostel->mj_smgt_insert_bed($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=bed_list&message=7');
				}
			}
		}
	}
}
//--------- delete record BED --------------------
	 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_bed')
{
	$result=$obj_hostel->mj_smgt_delete_bed($_REQUEST['bed_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=bed_list&message=9');
	}
}
if(isset($_REQUEST['delete_selected_bed']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$result=$obj_hostel->mj_smgt_delete_bed($id);
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=bed_list&message=9');
	}
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=bed_list&message=9');
	}
}
//---------- Assign Beds -------------------
if(isset($_POST['assign_room']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_assign_room_admin_nonce' ) )
	{
		$result=$obj_hostel->mj_smgt_assign_room($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=11');
		}
	} 
}	
//--------- delete Assign BED --------------------
	 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_assign_bed')
{
	$result=$obj_hostel->mj_smgt_delete_assigned_bed($_REQUEST['room_id'],$_REQUEST['bed_id'],$_REQUEST['student_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_hostel&tab=room_list&message=12');
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hostel_list';

?>
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Hostel Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Hostel Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Hostel Deleted Successfully.','school-mgt');
				break;
			case '4':
				$message_string = esc_attr__('Room Added Successfully.','school-mgt');
				break;
			case '5':
				$message_string = esc_attr__('Room Updated Successfully.','school-mgt');
				break;	
			case '6':
				$message_string = esc_attr__('Room Deleted Successfully.','school-mgt');
				break;
			case '7':
				$message_string = esc_attr__('Bed Added Successfully.','school-mgt');
				break;
			case '8':
				$message_string = esc_attr__('Bed Updated Successfully.','school-mgt');
				break;	
			case '9':
				$message_string = esc_attr__('Bed Deleted Successfully.','school-mgt');
				break;
			case '10':
				$message_string = esc_attr__('This room has no extra bed capacity','school-mgt');
				break;
			case '11':
				$message_string = esc_attr__('Room Assigned Successfully','school-mgt');
				break;
			case '12':
				$message_string = esc_attr__('Assigned Bed Deleted Successfully.','school-mgt');
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
					if($active_tab == 'hostel_list')
					{	
						?>
						<div class="popup-bg">
							<div class="overlay-content admission_popup">
								<div class="modal-content">
									<div class="category_list">
									</div>     
								</div>
							</div>     
						</div>
						<?php
						if(get_option('smgt_enable_video_popup_show') == 'yes')
						{
						?>
						<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/CZQzPhCPIr4?si=Hg16bHUL2gzi9xLA";?>" title="Hostel Module">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
						</a>
						<?php
						}
						$retrieve_class = mj_smgt_get_all_data($tablename);
						if(!empty($retrieve_class))
						{
							?>	
							<script type="text/javascript">
								jQuery(document).ready(function($){
									"use strict";	
									var table =  jQuery('#hostel_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
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
														<?php 
														if($user_access_edit == '1' || $user_access_delete =='1')
														{ ?>
													{"bSortable": false}
													<?php } ?>
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
								
									$("#delete_selected").on('click', function()
										{	
											if ($('.smgt_sub_chk:checked').length == 0 )
											{
												alert(language_translate2.one_record_select_alert);
												return false;
											}
										else{
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
										<table id="hostel_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
													<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
													<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
													<th><?php esc_attr_e('Hostel Type','school-mgt');?></th>
													<th><?php esc_attr_e('Hostel Address','school-mgt');?></th>
													<th><?php esc_attr_e('Intake/Capacity','school-mgt');?></th>
													<th><?php esc_attr_e('Description','school-mgt');?></th>
													<?php 
													if($user_access_edit == '1' || $user_access_delete =='1')
													{ ?>
														<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
													<?php } ?>
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
															<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">
														</td>
														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
															</p>
														</td>
														<td>
															<?php echo $retrieved_data->hostel_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i>
														</td>
														<td>
															<?php
															if(!empty($retrieved_data->hostel_type))
															{ 
																echo $retrieved_data->hostel_type;
															}
															else
															{
																echo "N/A"; 
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Type','school-mgt');?>" ></i>
														</td>
														<td>
															<?php
															if(!empty($retrieved_data->hostel_address))
															{ 
																$strlength= strlen($retrieved_data->hostel_address);
																if($strlength > 25)
																	echo substr($retrieved_data->hostel_address, 0,25).'...';
																else
																	echo $retrieved_data->hostel_address;
															}
															else
															{
																echo "N/A"; 
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->hostel_address)){ echo $retrieved_data->hostel_address; }else{ _e('Hostel Address','school-mgt');}?>" ></i>
														</td>
														<td>
															<?php
															if(!empty($retrieved_data->hostel_intake))
															{ 
																echo $retrieved_data->hostel_intake;
															}
															else
															{
																echo "N/A"; 
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Intake/Capacity','school-mgt');?>" ></i>
														</td>
														<td>
														<?php 
															if(!empty($retrieved_data->Description))
															{
																$strlength= strlen($retrieved_data->Description);
																if($strlength > 40)
																	echo substr($retrieved_data->Description, 0,40).'...';
																else
																	echo $retrieved_data->Description;
															}else{
																echo 'N/A';
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->Description)){echo $retrieved_data->Description;}else{ _e('Description','school-mgt');}?>" ></i>
														</td>
														<?php 
														if($user_access_edit == '1' || $user_access_delete =='1')
														{ ?>
														<td class="action"> 
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			<li class="float_left_width_100">
																				<a href="?page=smgt_hostel&tab=hostel_room_list&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/hostel_room_list.png"?>" style="height:13px;">&nbsp;&nbsp;&nbsp;<?php esc_attr_e('Hostel Room List','school-mgt');?></a> 
																			</li>
																			<?php 
																			if($user_access_edit == '1')
																			{ ?>
																				<li class="float_left_width_100 border_bottom_item">
																					<a href="?page=smgt_hostel&tab=add_hostel&action=edit&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"></i><?php esc_attr_e('Edit','school-mgt');?></a> 
																				</li>
																				<?php 
																			} ?>
																			<?php 
																			if($user_access_delete =='1')
																			{ ?>
																				<li class="float_left_width_100 ">
																					<a href="?page=smgt_hostel&tab=hostel_list&action=delete&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;"  onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
																				</li>
																				<?php 
																			} ?>
																		</ul>
																	</li>
																</ul>
															</div>	
														</td>
														<?php } ?>
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
									<a href="<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_hostel';?>">
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
					if($active_tab == 'room_list')
					{	
						?>
						<div class="popup-bg">
							<div class="overlay-content admission_popup">
								<div class="modal-content">
									<div class="view_popup">
									</div>     
								</div>
							</div>     
						</div>
						<?php
						$table_name_smgt_room='smgt_room';
						$retrieve_class = mj_smgt_get_all_data($table_name_smgt_room);
						if(!empty($retrieve_class))
						{

						?>	
							<script type="text/javascript">
								jQuery(document).ready(function($){
									"use strict";	

									var table =  jQuery('#room_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
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
							
									$("#delete_selected_room").on('click', function()
									{	
										if ($('.smgt_sub_chk:checked').length == 0 )
										{
											alert(language_translate2.one_record_select_alert);
											return false;
										}
										else{
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
										<table id="room_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
													<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
													<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
													<th><?php esc_attr_e('Room Type','school-mgt');?></th>
													<th><?php esc_attr_e('Remaining Bed Capacity','school-mgt');?></th>
													<th><?php esc_attr_e('Availability','school-mgt');?></th>
													<th><?php esc_attr_e('Description','school-mgt');?></th>
													<th><?php esc_attr_e('View & Assign Room','school-mgt');?></th>
													<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$i=0;	
												foreach ($retrieve_class as $retrieved_data)
												{ 	
													$capacity = $obj_hostel->mj_smgt_remaining_bed_capacity($retrieved_data->id);
													
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
															<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">
														</td>
														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" >
																<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
																</p>
															</a>
														</td>
														<td>
															<?php echo $retrieved_data->room_unique_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i>
														</td>
														<td>
															<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" >
															<?php if(!empty($retrieved_data->hostel_id)){ echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id); }else{ echo "N/A"; } ?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo get_the_title($retrieved_data->room_category);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Type','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo $capacity.' ';
														esc_attr_e('Out Of', 'school-mgt');
														echo ' '.$retrieved_data->beds_capacity; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Bed Capacity','school-mgt');?>" ></i>
														</td>
														<?php 
															$room_cnt =mj_smgt_hostel_room_status_check($retrieved_data->id);

															$bed_capacity=(int)$retrieved_data->beds_capacity;
															
															if($room_cnt >= $bed_capacity)
															{
																?> 
																<td>
																	<label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php
															}
															else 
															{ ?>
																<td>
																	<label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php 
															}
															?>
														<td>
															<?php
															if(!empty($retrieved_data->room_description))
															{

																$strlength= strlen($retrieved_data->room_description);
																if($strlength > 30)
																	echo substr($retrieved_data->room_description, 0,30).'...';
																else
																	echo $retrieved_data->room_description;
															}else{
																echo 'N/A';
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->room_description)){ echo $retrieved_data->room_description;}else{ _e('Description','school-mgt');}?>" ></i>
														</td>
														<td>
															<?php
															if($room_cnt >= $bed_capacity)
															{
																echo esc_attr_e('No Bed Available In This Room.','school-mgt');
															}
															else
															{
																?>
																<button class="btn btn-default assign_room_btn_design"><a href="?page=smgt_hostel&tab=assign_room&action=view_assign_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i> <?php esc_attr_e('Assign Room','school-mgt');?></a></button>
																<?php
															}
															?>
														</td>
														<td class="action"> 
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			
																			<li class="float_left_width_100 ">
																				<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
																			</li>
																			<li class="float_left_width_100">
																				<a href="?page=smgt_hostel&tab=assign_bed_list&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i><?php esc_attr_e('Assign Bed List','school-mgt');?></a> 
																			</li>
																			<?php 
																			if($user_access_edit == '1')
																			{ ?>
																				<li class="float_left_width_100 border_bottom_item">
																					<a href="?page=smgt_hostel&tab=add_room&action=edit_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"></i><?php esc_attr_e('Edit','school-mgt');?></a> 
																				</li>
																				<?php 
																			} ?>
																			<?php 
																			if($user_access_delete =='1')
																			{ ?>
																				<li class="float_left_width_100">
																					<a href="?page=smgt_hostel&tab=room_list&action=delete_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;"  onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a> 
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
												}
												?>
											</tbody>
										</table>
										<div class="print-button pull-left">
											<button class="btn btn-success btn-sms-color button_reload">
												<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
												<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
											</button>
											<?php 
											if($user_access_delete =='1')
											{ ?>
												<button id="delete_selected_room" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_room" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
												<?php 
											} ?>
											
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
									<a href="<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_room';?>">
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
					if($active_tab == 'bed_list')
					{	
						?>
						<div class="popup-bg">
							<div class="overlay-content admission_popup">
								<div class="modal-content">
									<div class="view_popup">
									</div>     
								</div>
							</div>     
						</div>
						<?php
						$table_name_ssmgt_beds='smgt_beds';
						$retrieve_class = mj_smgt_get_all_data($table_name_ssmgt_beds);
						if(!empty($retrieve_class))
						{
							?>	
							<script type="text/javascript">
								jQuery(document).ready(function($){
									"use strict";	

									var table =  jQuery('#bed_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										//stateSave: true,
										responsive: true,
										"dom": 'lifrtp',
										"order": [[ 2, "asc" ]],
										"aoColumns":[                    
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

									$("#delete_selected_bed").on('click', function()
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
							<div class="panel-body">
								<div class="table-responsive">
									<form id="frm-example" name="frm-example" method="post">
										<table id="bed_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
													<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
													<th><?php esc_attr_e('Bed Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Occupied Student','school-mgt');?></th>
													<th><?php esc_attr_e('Bed Charge','school-mgt');?></th>
													<th><?php esc_attr_e('Availability','school-mgt');?></th>
													<th><?php esc_attr_e('Description','school-mgt');?></th>
													<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 	
												$a=0;	
												
												foreach ($retrieve_class as $retrieved_data)
												{ 		
													$student_id = $obj_hostel->mj_smgt_get_assign_bed_student_by_id($retrieved_data->id);
													
													$hostel_id = $obj_hostel->mj_smgt_get_hostel_id_by_room_id($retrieved_data->room_id);
													
													if($a == 0)
													{
														$color_class='smgt_class_color0';
													}
													elseif($a == 1)
													{
														$color_class='smgt_class_color1';
													}
													elseif($a == 2)
													{
														$color_class='smgt_class_color2';
													}
													elseif($a == 3)
													{
														$color_class='smgt_class_color3';
													}
													elseif($a == 4)
													{
														$color_class='smgt_class_color4';
													}
													elseif($a == 5)
													{
														$color_class='smgt_class_color5';
													}
													elseif($a == 6)
													{
														$color_class='smgt_class_color6';
													}
													elseif($a == 7)
													{
														$color_class='smgt_class_color7';
													}
													elseif($i == 8)
													{
														$color_class='smgt_class_color8';
													}
													elseif($a == 9)
													{
														$color_class='smgt_class_color9';
													}
													?>
														<tr>
															<td class="checkbox_width_10px">
																<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">
															</td>
															<td class="user_image width_50px profile_image_prescription padding_left_0">
																<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >
																	<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
																	</p>
																</a>
															</td>
															<td>
																<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >
																<?php echo $retrieved_data->bed_unique_id;?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Unique ID','school-mgt');?>" ></i>
															</td>
															<td>
																<?php echo mj_smgt_get_room_unique_id_by_id($retrieved_data->room_id);?>(<?php echo mj_smgt_get_hostel_name_by_id($hostel_id);?>) <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i>
															</td>
															<td>
																<?php if($student_id){ echo mj_smgt_student_display_name_with_roll($student_id->student_id);}else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Occupied Student','school-mgt');?>" ></i>
															</td>
															<td>
																<?php if($retrieved_data->bed_charge){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->bed_charge,2,'.','')); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Charge','school-mgt');?>" ></i>
															</td>
															<?php 
															if($retrieved_data->bed_status == '0')
															{	?>
																<td>
																	<label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php 
															}
															else 
															{?>
																<td>
																	<label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
															<?php 
															}?>
															<td>
																<?php
																if(!empty($retrieved_data->bed_description))
																{
		
																	$strlength= strlen($retrieved_data->bed_description);
																	if($strlength > 40)
																		echo substr($retrieved_data->bed_description, 0,40).'...';
																	else
																		echo $retrieved_data->bed_description;
																}else{
																	echo 'N/A';
																}

																?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->bed_description)){ echo $retrieved_data->bed_description;}else{ _e('Description','school-mgt');}?>" ></i>
															</td>
															
															<td class="action"> 
																<div class="smgt-user-dropdown">
																	<ul class="" style="margin-bottom: 0px !important;">
																		<li class="">
																			<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																				<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																			</a>
																			<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																				<li class="float_left_width_100 ">
																					<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
																				</li>
																				<?php 
																				if($user_access_edit == '1')
																				{ ?>
																					<li class="float_left_width_100 border_bottom_item">
																						<a href="?page=smgt_hostel&tab=add_bed&action=edit_bed&bed_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a> 
																					</li>
																					<?php 
																				} ?>
																				<?php 
																				if($user_access_delete =='1')
																				{ ?>
																					<li class="float_left_width_100">
																						<a href="?page=smgt_hostel&tab=bed_list&action=delete_bed&bed_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
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
													$a++;
												} ?>
											</tbody>
										</table>
										<div class="print-button pull-left">
											<button class="btn btn-success btn-sms-color button_reload">
												<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
												<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
											</button>
											<?php 
											if($user_access_delete =='1')
											{ ?>
												<button id="delete_selected_bed" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_bed" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
												<?php 
											} ?>
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
									<a href="<?php echo admin_url().'admin.php?page=smgt_hostel&tab=add_bed';?>">
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
					if($active_tab == 'assign_bed_list')
					{
						?>
						<div class="popup-bg">
							<div class="overlay-content admission_popup">
								<div class="modal-content">
									<div class="view_popup">
									</div>     
								</div>
							</div>     
						</div>
						<?php
						$retrieve_class = $obj_hostel->mj_smgt_get_assign_bed_by_room_id($_REQUEST['room_id']);
						if(!empty($retrieve_class))
						{
							?>	
							<script type="text/javascript">
								jQuery(document).ready(function($){
									"use strict";	

									var table =  jQuery('#assign_bed_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										responsive: true,
										"dom": 'lifrtp',
										"order": [[ 2, "asc" ]],
										"aoColumns":[                    
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

									$("#delete_selected_bed").on('click', function()
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
							<div class="panel-body">
								<div class="table-responsive">
									<form id="frm-example" name="frm-example" method="post">
										<table id="assign_bed_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
													<th><?php esc_attr_e('Bed Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Occupied Student','school-mgt');?></th>
													<th><?php esc_attr_e('Bed Charge','school-mgt');?></th>
													<th><?php esc_attr_e('Availability','school-mgt');?></th>
													<th><?php esc_attr_e('Description','school-mgt');?></th>
													<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 	
												$a=0;	
												
												foreach ($retrieve_class as $retrieved_data)
												{ 		
													$retrieved_data = $obj_hostel->mj_smgt_get_bed_by_id($retrieved_data->bed_id);
													$student_id = $obj_hostel->mj_smgt_get_assign_bed_student_by_id($retrieved_data->id);
													$hostel_id = $obj_hostel->mj_smgt_get_hostel_id_by_room_id($retrieved_data->room_id);
													
													if($a == 0)
													{
														$color_class='smgt_class_color0';
													}
													elseif($a == 1)
													{
														$color_class='smgt_class_color1';
													}
													elseif($a == 2)
													{
														$color_class='smgt_class_color2';
													}
													elseif($a == 3)
													{
														$color_class='smgt_class_color3';
													}
													elseif($a == 4)
													{
														$color_class='smgt_class_color4';
													}
													elseif($a == 5)
													{
														$color_class='smgt_class_color5';
													}
													elseif($a == 6)
													{
														$color_class='smgt_class_color6';
													}
													elseif($a == 7)
													{
														$color_class='smgt_class_color7';
													}
													elseif($i == 8)
													{
														$color_class='smgt_class_color8';
													}
													elseif($a == 9)
													{
														$color_class='smgt_class_color9';
													}
													?>
														<tr>
															<td class="user_image width_50px profile_image_prescription padding_left_0">
																<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >
																	<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
																	</p>
																</a>
															</td>
															<td>
																<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >
																<?php echo $retrieved_data->bed_unique_id;?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Unique ID','school-mgt');?>" ></i>
															</td>
															<td>
																<?php echo mj_smgt_get_room_unique_id_by_id($retrieved_data->room_id);?>(<?php echo mj_smgt_get_hostel_name_by_id($hostel_id);?>) <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i>
															</td>
															<td>
																<?php if($student_id){ echo mj_smgt_student_display_name_with_roll($student_id->student_id);}else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Occupied Student','school-mgt');?>" ></i>
															</td>
															<td>
																<?php if($retrieved_data->bed_charge){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->bed_charge,2,'.','')); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Charge','school-mgt');?>" ></i>
															</td>
															<?php 
															if($retrieved_data->bed_status == '0')
															{	?>
																<td>
																	<label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php 
															}
															else 
															{?>
																<td>
																	<label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
															<?php 
															}?>
															<td>
																<?php
																if(!empty($retrieved_data->bed_description))
																{
		
																	$strlength= strlen($retrieved_data->bed_description);
																	if($strlength > 40)
																		echo substr($retrieved_data->bed_description, 0,40).'...';
																	else
																		echo $retrieved_data->bed_description;
																}else{
																	echo 'N/A';
																}

																?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->bed_description)){ echo $retrieved_data->bed_description;}else{ _e('Description','school-mgt');}?>" ></i>
															</td>
															
															<td class="action"> 
																<div class="smgt-user-dropdown">
																	<ul class="" style="margin-bottom: 0px !important;">
																		<li class="">
																			<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																				<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																			</a>
																			<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

																				<li class="float_left_width_100 ">
																					<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>	
															</td>
														</tr>
													<?php
													$i++;
													$a++;
												} ?>
											</tbody>
										</table>
									</form>
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
					if($active_tab == 'hostel_room_list')
					{
						?>
						<div class="popup-bg">
							<div class="overlay-content admission_popup">
								<div class="modal-content">
									<div class="view_popup">
									</div>     
								</div>
							</div>     
						</div>
						<?php

						$retrieve_class = $obj_hostel->mj_smgt_get_room_by_hostel_id($_REQUEST['hostel_id']);
						
						if(!empty($retrieve_class))
						{

						?>	
							<script type="text/javascript">
								jQuery(document).ready(function($){
									"use strict";	

									var table =  jQuery('#room_list').DataTable({
										"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
										responsive: true,
										"dom": 'lifrtp',
										"order": [[ 2, "asc" ]],
										"aoColumns":[                      
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
							
									$("#delete_selected_room").on('click', function()
									{	
										if ($('.smgt_sub_chk:checked').length == 0 )
										{
											alert(language_translate2.one_record_select_alert);
											return false;
										}
										else{
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
										<table id="room_list" class="display" cellspacing="0" width="100%">
											<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
												<tr>
													<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
													<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
													<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
													<th><?php esc_attr_e('Room Type','school-mgt');?></th>
													<th><?php esc_attr_e('Remaining Bed Capacity','school-mgt');?></th>
													<th><?php esc_attr_e('Availability','school-mgt');?></th>
													<th><?php esc_attr_e('Description','school-mgt');?></th>
													<th><?php esc_attr_e('View & Assign Room','school-mgt');?></th>
													<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$i=0;	
												foreach ($retrieve_class as $retrieved_data)
												{ 	
													$capacity = $obj_hostel->mj_smgt_remaining_bed_capacity($retrieved_data->id);
													
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
														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" >
																<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
																</p>
															</a>
														</td>
														<td>
															<?php echo $retrieved_data->room_unique_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i>
														</td>
														<td>
															<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" >
															<?php if(!empty($retrieved_data->hostel_id)){ echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id); }else{ echo "N/A"; } ?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo get_the_title($retrieved_data->room_category);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Type','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo $capacity.' ';
														esc_attr_e('Out Of', 'school-mgt');
														echo ' '.$retrieved_data->beds_capacity; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Bed Capacity','school-mgt');?>" ></i>
														</td>
														<?php 
															$room_cnt =mj_smgt_hostel_room_status_check($retrieved_data->id);

															$bed_capacity=(int)$retrieved_data->beds_capacity;
															
															if($room_cnt >= $bed_capacity)
															{
																?> 
																<td>
																	<label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php
															}
															else 
															{ ?>
																<td>
																	<label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i>
																</td>
																<?php 
															}
															?>
														<td>
															<?php
															if(!empty($retrieved_data->room_description))
															{

																$strlength= strlen($retrieved_data->room_description);
																if($strlength > 30)
																	echo substr($retrieved_data->room_description, 0,30).'...';
																else
																	echo $retrieved_data->room_description;
															}else{
																echo 'N/A';
															}
															?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->room_description)){ echo $retrieved_data->room_description;}else{ _e('Description','school-mgt');}?>" ></i>
														</td>
														<td>
															<?php
															if($room_cnt >= $bed_capacity)
															{
																echo esc_attr_e('No Bed Available In This Room.','school-mgt');
															}
															else
															{
																?>
																<button class="btn btn-default assign_room_btn_design"><a href="?page=smgt_hostel&tab=assign_room&action=view_assign_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i> <?php esc_attr_e('Assign Room','school-mgt');?></a></button>
																<?php
															}
															?>
														</td>
														<td class="action"> 
															<div class="smgt-user-dropdown">
																<ul class="" style="margin-bottom: 0px !important;">
																	<li class="">
																		<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																			<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																		</a>
																		<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																			
																			<li class="float_left_width_100 ">
																				<a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
																			</li>
																			<li class="float_left_width_100">
																				<a href="?page=smgt_hostel&tab=assign_bed_list&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i><?php esc_attr_e('Assign Bed List','school-mgt');?></a> 
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
												?>
											</tbody>
										</table>
									</form>
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
					if($active_tab == 'add_hostel')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/add_hostel.php';
					}
					if($active_tab == 'add_room')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/add_room.php';
					}
					if($active_tab == 'add_bed')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/add_bed.php';
					}
					if($active_tab == 'assign_room')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/assign_room.php';
					}
					?>
				</div><!-- smgt_main_listpage -->
	 		</div><!-- col-md-12 -->
	 	</div><!-- row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->