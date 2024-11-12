<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
//--------------- ACCESS WISE ROLE -----------//
$role_name=mj_smgt_get_user_role(get_current_user_id());
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
$tablename="holiday";
//--------------------- DELETE HOLIDAY --------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=mj_smgt_delete_holiday($tablename,$_REQUEST['holiday_id']);
	if($result){ 
		wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=3'); 	
	}
}
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=mj_smgt_delete_holiday($tablename,$id);
		wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=3'); 	
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=3'); 	
	}
}
//------------------- SAVE HOLIDAYS --------------------/
if(isset($_POST['save_holiday']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_holiday_admin_nonce' ) )
	{
		$start_date = date('Y-m-d',strtotime($_REQUEST['date']));
		$end_date = date('Y-m-d',strtotime($_REQUEST['end_date']));
		$exlude_id = mj_smgt_approve_student_list();
		if($start_date > $end_date )
		{ ?>
			<script type="text/javascript">
				alert("End Date should be greater than the Start Date");
			</script>
			<?php
		}
		else
		{
			
			$query_data['exclude']=$exlude_id;
			$results = get_users($query_data);
			$haliday_data=array(
				'holiday_title'=>mj_smgt_popup_category_validation(stripslashes($_POST['holiday_title'])),
				'description'=>mj_smgt_address_description_validation(stripslashes($_POST['description'])),
				'date'=>date('Y-m-d', strtotime($_POST['date'])),
				'end_date'=>date('Y-m-d', strtotime($_POST['end_date'])),
				'created_by'=>get_current_user_id(),
				'created_date'=>date('Y-m-d H:i:s'),
				'status'=>1
			);
			//table name without prefix
			$tablename="holiday";		
			if($_REQUEST['action']=='edit')
			{
				$holiday_id=array('holiday_id'=>$_REQUEST['holiday_id']);			
				$result=mj_smgt_update_record($tablename,$haliday_data,$holiday_id);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=2'); 	
				}
			}
			else
			{
				$startdate = strtotime($_POST['date']);
				$enddate = strtotime($_POST['end_date']);
				if($startdate==$enddate)
				{
					$date = $_POST['date'];
				}
				else
				{
					$date = $_POST['date'] ." To ".$_POST['end_date'];
				}
				$AllUsr = mj_smgt_get_all_user_in_plugin();
				$device_token = array();
				$to = array();
				foreach($AllUsr as $key=>$usr)
				{
					
					$device_token[]=get_user_meta($usr->ID, 'token_id' , true);
					
					$to[] = $usr->user_email;
					
				}
				
				$result=mj_smgt_insert_record($tablename,$haliday_data);
				
				if($result)
				{
					if($_POST['smgt_enable_holiday_mail'] == "1")
					{
						foreach($to as $email)
						{
							$Search['{{holiday_title}}'] 	= 	mj_smgt_strip_tags_and_stripslashes($_POST['holiday_title']);

							$Search['{{holiday_date}}'] 	= 	$date;

							$Search['{{school_name}}'] 		= 	get_option('smgt_school_name');

							$message 	=	 mj_smgt_string_replacement($Search,get_option('holiday_mailcontent'));

							mj_smgt_send_mail($email,get_option('holiday_mailsubject'),$message);

						}
					}
					if(isset($_POST['smgt_enable_holiday_sms']) == "1")
					{
						foreach($AllUsr as $key=>$usr)
						{
							$message_content = "New Holiday ".mj_smgt_strip_tags_and_stripslashes($_POST['holiday_title'])." Announced";

							$type =	"Holiday";
							
							MJ_smgt_send_sms_notification($usr->ID,$type,$message_content);
						}
					}
					// Send Push Notification //
					$title = esc_attr__('Holiday Announcement','school-mgt');
					$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>mj_smgt_strip_tags_and_stripslashes($_POST['holiday_title']),'type'=>'holiday'));
					$json = json_encode($notification_data);
					MJ_smgt_send_push_notification($json);
					// End Send Push Notification//
					wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=1'); 	
				}
			}
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'holidaylist';
?>
<script type="text/javascript" >
	jQuery(document).ready(function($){
		jQuery.extend(jQuery.fn.dataTableExt.oSort, 
		{
			"date-uk-pre": function (a) 
			{
				return moment(a, '<?php echo mj_smgt_return_date_format_for_shorting();?>').unix();
			},
			"date-uk-asc": function (a, b) {
				return a - b;
			},
			"date-uk-desc": function (a, b) {
				return b - a;
			}
		});

		$('#holiday_list').DataTable({
			"initComplete": function(settings, json) {
				$(".print-button").css({"margin-top": "-5%"});
			},
			//stateSave: true,
			responsive: true,
			"columnDefs": [
				{
					"targets": 4, // Specify the column index
					"type": "date-uk", // Use the custom sorting plugin
					"render": function (data) {
							// Render the date in the original format
							return moment(data, '<?php echo mj_smgt_return_date_format_for_shorting();?>').format('<?php echo mj_smgt_return_date_format_for_shorting();?>');
						}
				}
			],
			"dom": 'lifrtp',
			"order": [[ 4, "asc" ]],
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
							<?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
									?>
									{"bSortable": false}
									<?php
								}
								?>						 
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

		$('#holiday_form_template').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
					
		$('#start_date').datepicker({		
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeMonth: true,
			changeYear: true,
			minDate:0,
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() + 0);
				$("#end_date").datepicker("option", "minDate", dt);
			}
		}); 
		$('#end_date').datepicker({		
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeMonth: true,
			changeYear: true,
			minDate:0,
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 0);
				$("#start_date").datepicker("option", "maxDate", dt);
			}
		}); 	 
	});
</script>

<div class="panel-body panel-white frontend_list_margin_30px_res">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Holiday Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Holiday Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Holiday Deleted Successfully.','school-mgt');
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
	if($active_tab=='holidaylist')
	{
		//--------------------- HOLIDAY LIST PAGE  --------------//
		$user_id=get_current_user_id();
		if($school_obj->role == 'supportstaff')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$retrieve_class = mj_smgt_get_all_holiday_created_by($user_id);
			}
			else
			{
				$retrieve_class = mj_smgt_get_all_data( 'holiday' );
			}
		}
		else
		{
			$retrieve_class = mj_smgt_get_all_data( 'holiday' );
			
		}
		?>
			<div class="panel-body">
				<?php
				if(!empty($retrieve_class))
				{	
					?>
					<div class="table-responsive">
						<form id="frm-example" name="frm-example" method="post">
							<table id="holiday_list" class="display dataTable" cellspacing="0" width="100%">
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
										<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
										<th><?php esc_attr_e('Holiday Title','school-mgt');?></th>
										<th><?php esc_attr_e('Description','school-mgt');?></th>
										<th><?php esc_attr_e('Holiday Start Date','school-mgt');?></th>
										<th><?php esc_attr_e('Holiday End Date','school-mgt');?></th>         
										<th><?php esc_attr_e('Status','school-mgt');?></th>       
										<?php
										if($user_access['edit']=='1' || $user_access['delete']=='1')
										{ 
											?>    
											<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
											<?php
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
									$i=0;
									foreach ( $retrieve_class as $retrieved_data ) 
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
										
										if($retrieved_data->status == '0' || $retrieved_data->created_by == get_current_user_id ())
										{
											?>
											<tr>
												<?php
												if($role_name == "supportstaff")
												{
													?>
													<td class="checkbox_width_10px">
														<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->holiday_id;?>">
													</td>
													<?php
												}
												?>
												
												<td class="user_image width_50px profile_image_prescription padding_left_0">
													<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Holiday.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
													</p>
												</td>
												<td><?php echo $retrieved_data->holiday_title;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Holiday Title','school-mgt');?>" ></i></td>
												<td>
													<?php 
														if(!empty($retrieved_data->description))
														{

															$strlength= strlen($retrieved_data->description);
															if($strlength > 30)
																echo substr($retrieved_data->description, 0,30).'...';
															else
																echo $retrieved_data->description;
														}else{
															echo 'N/A';
														}
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->description)){echo $retrieved_data->description;}else{ _e('Description','school-mgt');} ?>" ></i>
												</td>
												<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Holiday Start Date','school-mgt');?>" ></i></td>
												<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Holiday End Date','school-mgt');?>" ></i></td>
												<td>
													<?php 
													if($retrieved_data->status == 0)
													{
														echo "<span class='green_color'>";
														echo esc_attr_e('Approve','school-mgt');
														echo "</span>";
													}
													else
													{
														echo "<span class='red_color'>";
														echo esc_attr_e('Not Approve','school-mgt');
														echo "</span>";
													}
													?>  
													<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Status','school-mgt');?>" ></i>
												</td>
												<?php
												if($user_access['edit']=='1' || $user_access['delete']=='1')
												{ 
													?>
													<td class="action"> 
														<div class="smgt-user-dropdown">
															<ul class="" style="margin-bottom: 0px !important;">
																<li class="">
																	<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																		<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																	</a>
																	<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
														
																		<?php
																		if($user_access['edit']=='1')
																		{
																		?>
																			<li class="float_left_width_100 border_bottom_item">
																				<a href="?dashboard=user&page=holiday&tab=addholiday&action=edit&holiday_id=<?php echo $retrieved_data->holiday_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>
																			</li>
																		<?php
																		}
																		if($user_access['delete']=='1')
																		{ ?>
																			<li class="float_left_width_100">
																				<a href="?dashboard=user&page=holiday&tab=holidaylist&action=delete&holiday_id=<?php echo $retrieved_data->holiday_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
																			</li>
																		<?php
																		}
																		?>
																	</ul>
																</li>
															</ul>
														</div>	
													</td>
													<?php
												}
												?>
											</tr>
											<?php 
										}
										$i++;
									} ?>
								</tbody>
							</table>
							<?php
							if($role_name == "supportstaff")
							{
								?>
								<div class="print-button pull-left">
									<button class="btn btn-success btn-sms-color button_reload">
										<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
									</button>
									<?php 
									if($user_access['delete']=='1')
									{ 
										?>
										<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
										<?php 
									} ?>
								</div>
								<?php
							}
							?>
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
							<a href="<?php echo home_url().'?dashboard=user&page=holiday&tab=addholiday';?>">
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
	if($active_tab=='addholiday')
	{     
		//--------------------- HOLIDAY ADD PAGE  --------------//
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$holiday_data= mj_smgt_get_holiday_by_id($_REQUEST['holiday_id']);
		}
		?>
		<div class="panel-body">
			<form name="holiday_form" action="" method="post" class="form-horizontal" id="holiday_form_template">
			   	<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="holiday_id"   value="<?php if($edit){ echo $holiday_data->holiday_id;}?>"/> 
				<div class="header">	
					<h3 class="first_hed"><?php esc_html_e('Holiday Information','school-mgt');?></h3>
				</div>
				<div class="form-body user_form">
					<div class="row">

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="holiday_title" class="form-control validate[required,custom[description_validation]] text-input" maxlength="100" type="text" value="<?php if($edit){ echo $holiday_data->holiday_title;}?>" name="holiday_title">
									<label class="" for="holiday_title"><?php esc_attr_e('Holiday Title','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="holiday_title" class="form-control validate[custom[description_validation]]" maxlength="1000" type="text" value="<?php if($edit){ echo $holiday_data->description;}?>" name="description">				
									<label class="" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="start_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($holiday_data->date))); }else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d")); } ?>" name="date" readonly>				
									<label class="" for="date"><?php esc_attr_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php wp_nonce_field( 'save_holiday_admin_nonce' ); ?>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
							<div class="form-group input ">
								<div class="col-md-12 form-control">
									<input id="end_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($holiday_data->end_date)));}else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d")); } ?>" name="end_date" readonly>				
									<label class="" for="date"><?php esc_attr_e('End Date','school-mgt');?><span class="require-field">*</span></label>
								</div>
							</div>
						</div>
						<?php
						if(!$edit){
						?>
						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl" for="smgt_enable_holiday_mail"><?php esc_attr_e('Send Mail','school-mgt');?></label>
											<input type="checkbox" class="check_box_input_margin" name="smgt_enable_holiday_mail"  value="1" <?php echo checked(get_option('smgt_enable_holiday_mail'),'yes');?>/>&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px">
							<div class="form-group mb-3">
								<div class="col-md-12 form-control rtl_relative_position">
									<div class="row padding_radio ">
										<div class="display_flex">
											<label class="custom-top-label label_position_rtl" for="smgt_enable_holiday_sms"><?php esc_attr_e('Send SMS','school-mgt');?></label>
											<input type="checkbox" class="check_box_input_margin" name="smgt_enable_holiday_sms"  value="1" <?php echo checked(get_option('smgt_enable_holiday_sms'),'yes');?>/>&nbsp;<?php esc_attr_e('Enable','school-mgt');?>
										</div>												
									</div>
								</div>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>

				<div class="form-body user_form">
					<div class="row">
						<div class="col-sm-6">         	
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Holiday','school-mgt'); }else{ esc_attr_e('Add Holiday','school-mgt');}?>" name="save_holiday" class="btn btn-success save_btn" />
						</div>    
					</div>
				</div>                
			</form>
		</div>
		<?php
	}
	?>
</div>
<?php ?> 