<?php ?>

<script type="text/javascript">

jQuery(document).ready(function($)

{

  "use strict";

  $('#rout_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

  $(".create_virtual_classroom").click(function ()

	{

	  var value = $('input:checkbox[name=create_virtual_classroom]').is(':checked');

	  if(value == true)

	  {

		$(".create_virtual_classroom_div").addClass("create_virtual_classroom_div_block");

		$(".create_virtual_classroom_div").removeClass("create_virtual_classroom_div_none");

	  }

	  else

	  {

		  $(".create_virtual_classroom_div").addClass("create_virtual_classroom_div_none");

		  $(".create_virtual_classroom_div").removeClass("create_virtual_classroom_div_block");

	  }

	});





	$("#start_date").datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			maxDate: 0,
			changeMonth: true,
			changeYear: true,

	        onSelect: function (selected) {

	            var dt = new Date(selected);

	            dt.setDate(dt.getDate() + 0);

	            $("#end_date").datepicker("option", "minDate", dt);

	        }

	    });

	    $("#end_date").datepicker({

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			maxDate: 0,
			changeMonth: true,
			changeYear: true,

	        onSelect: function (selected) {

	            var dt = new Date(selected);

	            dt.setDate(dt.getDate() + 0);

	            $("#start_date").datepicker("option", "maxDate", dt);

	        }

	    });

});

</script>

<?php

// Schedule

$obj_route = new Class_routine ();

$obj_virtual_classroom = new mj_smgt_virtual_classroom();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'schedulelist';

if(isset($_POST['create_meeting']))

{

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'create_meeting_admin_nonce' ) )

	{

		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);

		if($result)

		{

			wp_redirect ( home_url().'?dashboard=user&page=virtual_classroom&tab=meeting_list&message=1');

		}

	}

}

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

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')

{

	$tablenm = "smgt_time_table";

	$result=mj_smgt_delete_route($tablenm,$_REQUEST['route_id']);

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=5');

		exit;

	}

}

//-------------- DELETE TEACHER CLASS ----------------------//

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_teacher')

{

	$tablenm = "smgt_time_table";

	$result=mj_smgt_delete_route($tablenm,$_REQUEST['route_id']);

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=teacher_timetable&message=5');

		exit;

	}

}



if(isset($_GET['message']) && $_GET['message'] == 1 )
{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Routine Added Successfully.','school-mgt');?>

	</div>

<?php

}

if(isset($_GET['message']) && $_GET['message'] == 2 )

{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Routine Alredy Added For This Time Period.Please Try Again.','school-mgt');?>

	</div>



<?php

}

if(isset($_GET['message']) && $_GET['message'] == 3 )

{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Teacher Is Not Available.','school-mgt');?>

	</div>

<?php

}

if(isset($_GET['message']) && $_GET['message'] == 4 )

{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Routine Updated Successfully.','school-mgt');?>

	</div>

<?php

}

if(isset($_GET['message']) && $_GET['message'] == 5 )

{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('Routine Deleted Successfully.','school-mgt');?>

	</div>

<?php

}

if(isset($_GET['message']) && $_GET['message'] == 6 )

{

?>

	<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

		</button>

		<?php esc_attr_e('End Time should be greater than Start Time.','school-mgt');?>

	</div>

<?php

}

?>

<div class="popup-bg">

    <div class="overlay-content">

		<div class="create_meeting_popup"></div>

    </div>

</div>

<div class="panel-body panel-white frontend_list_margin_30px_res"><!----------- PENAL  BODY ------------->

	<!---------------------- TABING UL ---------------------->

	<?php

	if($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')

	{

		?>

		<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

			<li class="<?php if($active_tab=='schedulelist'){?>active<?php }?>">

				<a href="?dashboard=user&page=schedule&tab=schedulelist" class="padding_left_0 tab <?php echo $active_tab == 'schedulelist' ? 'active' : ''; ?>">

				<?php esc_html_e('Route List', 'school-mgt'); ?></a>

			</li>

			<li class="<?php if($active_tab=='teacher_timetable'){?>active<?php }?>">

				<a href="?dashboard=user&page=schedule&tab=teacher_timetable" class="padding_left_0 tab <?php echo $active_tab == 'teacher_timetable' ? 'active' : ''; ?>">

				<?php esc_html_e('Teacher TimeTable', 'school-mgt'); ?></a>

			</li>

			<?php

			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'addroute')

			{ ?>

				<li class="<?php if($active_tab=='addroute'){?>active<?php }?>">

					<a href="#" class="padding_left_0 tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">

					<?php esc_attr_e('Edit Class Time Table', 'school-mgt'); ?></a>

				</li>

				<?php

			}

			elseif($page_name == 'schedule' && $active_tab == 'addroute')

			{ ?>

				<li class="<?php if($active_tab=='addroute'){?>active<?php }?>">

					<a href="?page=smgt_library&tab=addbook" class="padding_left_0 tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">

					<?php echo esc_attr__('Add Class Time Table', 'school-mgt'); ?></a>

				</li>

				<?php

			}

			?>

		</ul>

		<?php

	}

	?>

	<div class="tab-content class_schedule_tab_content"><!------------ TAB CONTENT ------------>

		<div class="panel-body"><!----------- PENAL  BODY ------------->

			<div class="panel-group accordion accordion-flush padding_top_15px_res" id="accordionExample">

				<?php

				$i = 0;

				if($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')
				{

					//------------- SCHEDULE-LIST TAB ---------------//

					if($active_tab=='schedulelist')
					{

						$retrieve_class = mj_smgt_get_allclass();

						$i=0;

						if(!empty($retrieve_class))
						{

							foreach ( $retrieve_class as $class )
							{

								if(!empty($class))
								{

									?>

									<div class="mt-1 accordion-item class_border_div">

										<h4 class="accordion-header" id="heading<?php echo $i;?>">

											<a data-bs-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">

											<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">

												<?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo $class['class_name'];?>

											</a>

										</h4>

										<div id="collapse<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

											<div class="panel-body">

												<table class="table table-bordered " cellspacing="0" cellpadding="0" border="0">

													<?php

													foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
													{

														?>

														<tr>

															<th width="100"><?php echo $dayname;?></th>

															<td>

																<?php

																//------- NEW LINE ADDED FOR ERROR ---------//

																$sectionid=0;

																//-----------------------------------------//

																$period = $obj_route->mj_smgt_get_periad ( $class['class_id'],$sectionid, $daykey);



																if (! empty ( $period ))

																{

																	foreach ( $period as $period_data )

																	{

																		echo '<div class="btn-group m-b-sm">';

																		if($period_data->multiple_teacher =="yes")
																		{
																			echo '<button class="btn btn-primary class_list_button dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id).'('.mj_smgt_get_display_name($period_data->teacher_id).')';
																		}
																		else
																		{
																			echo '<button class="btn btn-primary class_list_button dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
																		}

																					$start_time_data = explode(":", $period_data->start_time);

																					$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

																					$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

																					$end_time_data = explode(":", $period_data->end_time);

																					$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																					$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

																					if (get_option('smgt_enable_virtual_classroom') == 'yes')
																					{

																						$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																						if(empty($meeting_data))
																						{

																							$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																						}
																						else
																						{

																							$create_meeting = '';

																						}

																						if(!empty($meeting_data))
																						{

																							$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																							$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

																							$meeting_statrt_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Start Virtual Class','school-mgt').'</a></li>';

																						}
																						else
																						{

																							$update_meeting = '';

																							$delete_meeting = '';

																							$meeting_statrt_link = '';

																						}

																					}

																					echo '<span class="time"> ('.$start_hour.':'.$start_min.' - '.$end_hour.':'.$end_min.') </span>';

																					echo '</span><span class="caret"></span></button>';

																					if($user_access['edit']=='1')
																					{

																						$edit_route='<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=schedule&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit Route','school-mgt').'</a></li>';

																					}
																					else
																					{

																						$edit_route="";

																					}

																				if($user_access['delete']=='1')

																			{

																				$delete_route='<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');" href="?dashboard=user&page=schedule&tab=schedulelist&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>';

																			}

																			else {

																				$delete_route="";

																			}

																			echo "</span></span> </button>";

																			if(($edit_route == '') && ($delete_route == '') && ($create_meeting == '') && ($update_meeting == '') && ($delete_meeting == '')  && ($meeting_statrt_link == '')){

																				echo'';

																			}

																			else{

																				echo '<ul role="menu" class="dropdown-menu schedule_menu">

																					'.$edit_route.''.$delete_route.''.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'

																				</ul>';

																			}

																			echo '</div>';

																	}

																}

																?>

															</td>

														</tr>

														<?php

													}

													?>

												</table>

											</div>

										</div>

									</div>

									<?php

								}



								$sectionname="";

								$sectionid="";

								$class_sectionsdata=mj_smgt_get_class_sections($class['class_id']);

								if(!empty($class_sectionsdata))

								{

									foreach($class_sectionsdata as $section)

									{

										$i++;

										?>

										<div class="accordion-item mt-1 class_border_div">

											<h4 class="accordion-header" id="heading<?php echo $i;?>">

												<a data-bs-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">

													<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">

													<?php esc_attr_e('Class', 'school-mgt'); ?> : <?php echo smgt_get_class_section_name_wise($section->class_id,$section->id); ?> &nbsp;&nbsp;&nbsp;&nbsp;

												</a>

											</h4>

											<div id="collapse<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" show" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample">

												<div class="panel-body">

													<table class="table table-bordered table_left" cellspacing="0" cellpadding="0" border="0">

														<?php

														foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )

														{

															?>

															<tr>

																<th width="100"><?php echo $dayname;?></th>

																<td>

																	<?php

																	$period = $obj_route->mj_smgt_get_periad ( $class['class_id'],$section->id, $daykey );

																	if (! empty ( $period ))

																	{

																		foreach ( $period as $period_data )

																		{

																			echo '<div class="btn-group m-b-sm">';

																			if($period_data->multiple_teacher =="yes")
																			{
																				echo '<button class="btn btn-primary class_list_button dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id).'('.mj_smgt_get_display_name($period_data->teacher_id).')';
																			}
																			else
																			{
																				echo '<button class="btn btn-primary class_list_button dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
																			}



																			$start_time_data = explode(":", $period_data->start_time);

																			$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

																			$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

																			$start_am_pm=$start_time_data[2];



																			$end_time_data = explode(":", $period_data->end_time);

																			$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																			$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

																			$end_am_pm=$end_time_data[2];

																			$create_meeting = '';

																			$update_meeting = '';

																			$delete_meeting = '';

																			echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';

																			echo "</span><span class='caret'></span></button>";

																			$virtual_classroom_page_name = 'virtual_classroom';

																			$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);

																			if (get_option('smgt_enable_virtual_classroom') == 'yes')

																			{

																				if ($virtual_classroom_access_right['view'] == '1')

																				{

																					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																					if(empty($meeting_data))

																					{

																						if ($virtual_classroom_access_right['add'] == '1')

																						{

																							$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'" >'.esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																						}

																					}

																					else

																					{

																						$create_meeting = '';

																					}



																					if(!empty($meeting_data))

																					{

																						if ($virtual_classroom_access_right['edit'] == '1')

																						{

																							$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'.esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																						}

																						if ($virtual_classroom_access_right['delete'] == '1')

																						{

																							$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''.esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'.esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

																						}

																						$meeting_statrt_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'.esc_attr__('Start Virtual Class','school-mgt').'</a></li>';

																					}

																					else

																					{

																						$update_meeting = '';

																						$delete_meeting = '';

																						$meeting_statrt_link = '';

																					}

																				}

																			}

																			if($user_access['edit']=='1')

																			{

																				$edit_route='<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=schedule&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit Route','school-mgt').'</a></li>';

																			}

																			else {

																				$edit_route="";

																			}

																			if($user_access['delete']=='1')

																			{

																				$delete_route='<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');" href="?dashboard=user&page=schedule&tab=schedulelist&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>';

																			}

																			else {

																				$delete_route="";

																			}

																			echo "</span></span> </button>";

																			if(($edit_route == '') && ($delete_rout == '') && ($create_meeting == '') && ($update_meeting == '') && ($delete_meeting == '')  && ($meeting_statrt_link == '')){

																				echo'';

																			}

																			else{

																				echo '<ul role="menu" class="dropdown-menu schedule_menu">

																					'.$edit_route.''.$delete_route.''.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'

																				</ul>';

																			}

																			echo '</div>';

																		}

																	}

																	?>

																</td>

															</tr>

															<?php

														}

														?>

													</table>

												</div>

											</div>

										</div>

										<?php

									}

								}

								$i++;

							}

						}

						else

						{

							if($role == 'admin' || $user_access['add']=='1')

							{

								?>

								<div class="no_data_list_div">

									<a href="<?php echo home_url().'?dashboard=user&page=schedule&tab=addroute';?>">

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

					//---------------- ADD ROUTE TAB ---------------//

					if($active_tab=='addroute')

					{

						//----------- SAVE ROUTE CODE -------------//

						if(isset($_POST['save_route']))

						{

							$nonce = $_POST['_wpnonce'];

							if ( wp_verify_nonce( $nonce, 'save_root_admin_nonce' ) )

							{
								$teacher_id = $_POST['subject_teacher'];

								$start_time=MJ_start_time_convert($_POST['start_time']);

								$end_time=MJ_end_time_convert($_POST['end_time']);

								$start_time_1 = $_POST['start_time'];

								$end_time_1 = $_POST['end_time'];

								$start_time_convert = date('h:i',strtotime($_POST['start_time']));

								$end_time_convert = date('h:i',strtotime($_POST['end_time']));



								$start_time_data = explode(":", $start_time_1);

								$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

								$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);



								$start_time_new=$start_hour.':'.$start_min;

								$start_time_in_24_hour_format  = date("H:i", strtotime($start_time_new));



								$end_time_data = explode(":", $end_time_1);

								$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

								$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);



								$end_time_new=$end_hour.':'.$end_min;

								$end_time_in_24_hour_format  = date("H:i", strtotime($end_time_new));

								if(($end_time_in_24_hour_format == "00:00" && $start_time_in_24_hour_format > "00:00") ||
								($end_time_in_24_hour_format == "12:00" && $start_time_in_24_hour_format > "12:00") ||
								($end_time_in_24_hour_format > $start_time_in_24_hour_format))
								{
									if($_REQUEST['action']=='edit')
									{
										$route_data = array();
										$route_data['subject_id'] = $_POST['subject_id'];
										$route_data['class_id'] = $_POST['class_id'];
										$route_data['section_name'] = $_POST['class_section'];
										$route_data['teacher_id'] = $teacher_id;
										$route_data['start_time'] = $start_time_new;
										$route_data['end_time'] = $end_time_new;
										$route_data['weekday'] = $_POST['weekday'];
										$route_data['multiple_teacher'] = 'yes';
									}
									else
									{
										$route_data = array();
										foreach($teacher_id as $teacher)
										{
											foreach($_POST['weekday'] as $week_days)
											{
												$route_data[] = array(

													'subject_id'=>$_POST['subject_id'],

													'class_id'=>$_POST['class_id'],

													'section_name'=>$_POST['class_section'],

													'teacher_id'=>$teacher,

													'start_time'=>$start_time_new,

													'end_time'=>$end_time_new,

													'weekday'=>$week_days,

													'multiple_teacher'=>'yes',

												);
											}
										}
									}

									if($_REQUEST['action']=='edit')
									{

										$route_id=array('route_id'=>$_REQUEST['route_id']);

										$obj_route->mj_smgt_update_route($route_data,$route_id);

										wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=4');

										exit;

									}
									else
									{
										foreach($route_data as $route)
										{
											$retuen_val = $obj_route->mj_smgt_is_route_exist($route);
										}


										if($retuen_val == 'success')
										{
											$route_id_array=$obj_route->mj_smgt_save_route_with_virtual_class($route_data);

											if($route_id_array)

											{
												foreach($route_id_array as $route_id)
												{
													if($_POST['create_virtual_classroom'] == 1)

													{

														$start_date=$_POST['start_date'];

														$end_date=$_POST['end_date'];

														$agenda=$_POST['agenda'];



														$obj_mark = new Class_routine();

														$route_data = mj_smgt_get_route_by_id($route_id);

														$start_time=MJ_start_time_convert($route_data->start_time);

														$end_time=MJ_end_time_convert($route_data->end_time);



														if(empty($_POST['password']))

														{

															$password = wp_generate_password( 10, true, true );

														}

														else

														{

															$password = $_POST['password'];

														}

														$metting_data=array(

														'teacher_id'=>$route_data->teacher_id,

														'password'=>$password,

														'start_date'=>$start_date,

														'start_time'=>$start_time,

														'end_date'=>$end_date,

														'end_time'=>$end_time,

														'weekday'=>$route_data->weekday,

														'agenda'=>$agenda,

														'route_id'=>$route_id,

														'class_id'=>$route_data->class_id,

														'class_section_id'=>$route_data->section_name,

														'subject_id'=>$route_data->subject_id,

														'action'=>'insert',

														);

														$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($metting_data);

													}
												}


												wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=1');

												exit;

											}



										}

										elseif($retuen_val == 'duplicate')

										{

											wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=2');

											exit;

										}

										elseif($retuen_val == 'teacher_duplicate')

										{

											wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=3');

											exit;

										}

									}

								}

								else

								{

									wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=6');

									exit;

								}

							}

						}

						?>

						<script>

							//-------- timepicker ---------//

							jQuery(document).ready(function($){

								jQuery('#subject_teacher').multiselect({
									nonSelectedText : "<?php esc_attr_e('Select Teacher','school-mgt'); ?>",
									includeSelectAllOption: true,
									selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
									templates: {
										button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
									},
									buttonContainer: '<div class="dropdown" />'
								});
								jQuery('.multiple_select_day').multiselect({
									nonSelectedText : "<?php esc_attr_e('Select Days','school-mgt'); ?>",
									includeSelectAllOption: true,
									selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
									templates: {
										button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
									},
									buttonContainer: '<div class="dropdown" />'
								});
								mdtimepicker('#timepicker', {

								events: {

										timeChanged: function (data) {



										}

									},

								theme: 'purple',

								readOnly: false,

								});

							})

						</script>

						<div class="panel-white"><!--------------- PENAL WHITE ------------------>

							<?php

							$edit=0;

							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

							{

								$edit=1;

								$route_data= mj_smgt_get_route_by_id($_REQUEST['route_id']);

							}

							?>

							<div class="panel-body"><!--------------- PENAL BODY -------------------->

								<!-------------- ROUTE FORM START --------------------->

								<form name="route_form" action="" method="post" class="form-horizontal" id="rout_form">

									<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

									<input type="hidden" name="action" value="<?php echo $action;?>">

									<div class="form-body user_form">

										<div class="row">

											<div class="col-md-6 input">

												<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="required">*</span></label>

												<?php if($edit){ $classval=$route_data->class_id; }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>

												<select name="class_id"  id="class_list" class="form-control validate[required] line_height_30px max_width_100">

													<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>

													<?php

													foreach(mj_smgt_get_allclass() as $classdata)

													{

													?>

													<option  value="<?php echo $classdata['class_id'];?>" <?php   selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

													<?php }?>

												</select>

											</div>

											<?php wp_nonce_field( 'save_root_admin_nonce' ); ?>

											<div class="col-md-6 input">

												<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>

												<select name="class_section" class="form-control max_width_100 line_height_30px section_id_exam" id="class_section">

													<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>

													<?php

													if($edit){

														foreach(mj_smgt_get_class_sections($route_data->class_id) as $sectiondata)

														{  ?>

															<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

													<?php }

													}?>

												</select>

											</div>

											<div class="col-md-6 input">

												<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Subject','school-mgt');?><span class="required">*</span></label>

												<?php if($edit){ $subject_id=$route_data->subject_id; }elseif(isset($_POST['subject_id'])){$subject_id=$_POST['subject_id'];}else{$subject_id='';}?>

												<select name="subject_id" id="subject_list" class="form-control change_subject validate[required] line_height_30px max_width_100">

													<?php

													if( $edit )

													{

														$subject = mj_smgt_get_subject_by_classid($route_data->class_id);

														if(!empty($subject))

														{

															foreach ($subject as $ubject_data)

															{

															?>

																<option value="<?php echo $ubject_data->subid ;?>" <?php selected($subject_id, $ubject_data->subid);  ?>><?php echo $ubject_data->sub_name;?></option>

															<?php

															}

														}

													}

													else

													{

													?>

														<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>

													<?php

													}

													?>

												</select>

											</div>

											<?php
											if($edit)
											{
												$teachval = mj_smgt_teacher_by_subject_id($subject_id);
												?>
												<div class="col-md-6 input">
													<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Teacher','school-mgt');?><span class="required">*</span></label>
														<select name="subject_teacher" class="form-control validate[required] teacher_list" style="height: 47px;">
															<option value=""><?php esc_attr_e('Select Teacher','school-mgt');?></option>
															<?php
															foreach ($teachval as $teacher)
															{
																?>
																<option value="<?php echo $teacher ;?>" <?php selected($route_data->teacher_id, $teacher);  ?>><?php echo mj_smgt_get_display_name($teacher);?></option>
																<?php
															}
															?>

														</select>
												</div>
												<?php
											}
											else
											{
											?>
											<div class="col-md-6 input teacher_list_multiselect">
												<div class="col-sm-12 multiselect_validation_teacher smgt_multiple_select rtl_padding_left_right_0px res_rtl_width_100">
													<select name="subject_teacher[]" multiple="multiple" id="subject_teacher" class="form-control validate[required] teacher_list">
													</select>
												</div>
											</div>
											<?php
											}
											?>
											<?php if($edit){ $day_key=$route_data->weekday; }elseif(isset($_POST['weekday'])){$day_key=$_POST['weekday'];}else{$day_key='';}?>
											<?php
											if($edit){
												?>
												<div class="col-md-6 input">

													<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Day','school-mgt');?></label>

													<select name="weekday" class="form-control validate[required] line_height_30px max_width_100" id="weekday">

														<?php

														foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)

															echo '<option  value="'.$daykey.'" '.selected($day_key,$daykey).'>'.$dayname.'</option>';

														?>

													</select>

												</div>
											<?php
											}
											else{
												?>
												<div class="col-md-6 input smgt_multiple_select">

													<select name="weekday[]" class="form-control validate[required] line_height_30px max_width_100 multiple_select_day" id="weekday" multiple="multiple">

														<?php

														foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)

															echo '<option  value="'.$daykey.'" '.selected($day_key,$daykey).'>'.$dayname.'</option>';

														?>

													</select>

												</div>
												<?php
												}
												?>


											<div class="col-md-3">

												<div class="form-group input">

													<div class="col-md-12 form-control">

														<input type="text" id="timepicker" name="start_time" class="form-control validate[required] start_time" value="<?php if(!empty($route_data->start_time)){ echo $route_data->start_time; } ?>" />

														<label for="userinput1" class=""><?php esc_html_e('Start Time','school-mgt');?><span class="required">*</span></label>

													</div>

												</div>

											</div>

											<div class="col-md-3">

												<div class="form-group input">

													<div class="col-md-12 form-control">

														<input type="text" id="timepicker" name="end_time" class="form-control validate[required] end_time" value="<?php if(!empty($route_data->end_time)){ echo $route_data->end_time; } ?>" />

														<label for="userinput1" class=""><?php esc_html_e('End Time','school-mgt');?><span class="required">*</span></label>

													</div>

												</div>

											</div>

										</div>

									</div>

									<?php

									if(get_option('smgt_enable_virtual_classroom') == "yes")

									{

										if(!$edit)

										{

											$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array('virtual_classroom');

											if (get_option('smgt_enable_virtual_classroom') == 'yes' && $virtual_classroom_access_right['add'] == '1')

											{

												?>

												<!-- Create Virtual Classroom -->

												<div class="form-body user_form">

													<div class="row">

														<div class="col-md-6 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">

															<div class="form-group">

																<div class="col-md-12 form-control input_height_50px">

																	<div class="row padding_radio">

																		<div class="input-group input_checkbox">

																			<label class="custom-top-label"><?php esc_html_e('Create Virtual Class','school-mgt');?></label>

																			<div class="checkbox checkbox_lebal_padding_8px">

																				<label>

																					<input type="checkbox" id="isCheck" class="margin_right_checkbox_css create_virtual_classroom" name="create_virtual_classroom"  value="1" />

																				</label>

																			</div>
																			<label>&nbsp;&nbsp;<?php esc_attr_e('Enable','school-mgt');?></label>
																		</div>

																	</div>

																</div>

															</div>

														</div>

													</div>

												</div>

												<div class="form-body user_form create_virtual_classroom_div create_virtual_classroom_div_none ">

													<div class="row">

														<div class="col-md-6">

															<div class="form-group input">

																<div class="col-md-12 form-control">

																	<input id="start_date" class="form-control validate[required] text-input start_date" type="text" placeholder="<?php esc_html_e('Enter Start Date','school-mgt');?>" name="start_date" value="<?php echo mj_smgt_getdate_in_input_box(date("Y-m-d")); ?>" readonly>

																	<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>

																</div>

															</div>

														</div>

														<div class="col-md-6">

															<div class="form-group input">

																<div class="col-md-12 form-control">

																	<input id="end_date" class="form-control validate[required] text-input end_date" type="text" placeholder="<?php esc_html_e('Enter End Date','school-mgt');?>" name="end_date" value="<?php echo mj_smgt_getdate_in_input_box(date("Y-m-d")); ?>" readonly>

																	<label for="userinput1"><?php esc_html_e('End Date','school-mgt');?></label>

																</div>

															</div>

														</div>

														<div class="col-md-6">

															<div class="form-group input">

																<div class="col-md-12 form-control">

																	<input id="end_date" class="form-control validate[custom[address_description_validation]]" type="text" name="password" value="">

																	<label for="userinput1" class=""><?php esc_html_e('Topic','school-mgt');?></label>

																</div>

															</div>

														</div>

														<div class="col-md-6">

															<div class="form-group input">

																<div class="col-md-12 form-control">

																	<input id="end_date" class="form-control validate[required,minSize[8],maxSize[12]] text-input" type="password" name="agenda" value="">

																	<label for="userinput1" class=""><?php esc_html_e('Password','school-mgt');?><span class="required">*</span></label>

																</div>

															</div>

														</div>

													</div>

												</div>

												<?php

											}

										}

									}

									?>

									<div class="form-body">

										<div class="row">

											<div class="col-sm-6">

												<input type="submit" value="<?php if($edit){ esc_attr_e('Save Route','school-mgt'); }else{ esc_attr_e('Add Route','school-mgt');}?>" name="save_route" class="btn btn-success save_btn" />

											</div>

										</div>

									</div>

								</form>

							</div>

						</div>

						<?php

					}

					//------------- SCHEDULE-LIST TAB ---------------//

					if($active_tab=='teacher_timetable')

					{

						?>

						<div class="panel-white margin_top_20px"><!-------- penal White ------->

		 					<div class="panel-body"><!-------- penal Body ------->

		 						<div id="accordion" class="panel-group accordion accordion-flush padding_top_15px_res" aria-multiselectable="true" role="tablist">

       								<?php
									$page = 'schedule';
									$user_access = mj_smgt_get_userrole_wise_access_right_page_wise_array_for_dashboard($page);
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{
										$user_id=get_current_user_id();
										$teacherdata[]=get_userdata($user_id);
									}
									else
									{
										$teacherdata=mj_smgt_get_usersdata('teacher');
									}


									if(!empty($teacherdata))

									{

										$i=0;

										foreach($teacherdata as $retrieved_data)

										{
											$teacher_obj = new Smgt_Teacher;

											$classes="";
											$classes = $teacher_obj->mj_smgt_get_class_by_teacher($retrieved_data->ID);
											$classname = "";
											foreach($classes as $class)
											{
												$classname .= mj_smgt_get_class_name($class['class_id']).",";
											}
											$classname_rtrim=rtrim($classname,", ");
											$classname_ltrim=ltrim($classname_rtrim,", ");
											?>

											<div class="mt-1 accordion-item class_border_div">

												<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

													<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

														<span class="Title_font_weight"><?php esc_attr_e('Teacher', 'school-mgt'); ?></span> : <?php  if(!empty($classname_ltrim)){ echo $retrieved_data->display_name.'('.$classname_ltrim.')';} else{ echo $retrieved_data->display_name;} ?> </a>

													</button>

												</h4>

												<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionFlushExample">

            										<div class="panel-body">

        												<table class="table table-bordered ">

															<?php

															$i++;

															foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)

															{

																?>

																<tr>

      															<th width="100"><?php echo $dayname;?></th>

																		<td>

																		<?php

																		$period_1 = $obj_route->mj_smgt_get_periad_by_teacher($retrieved_data->ID,$daykey);
																		$period_2 = $obj_route->mj_smgt_get_periad_by_particular_teacher($retrieved_data->ID,$daykey);
																		if(!empty($period_1) && !empty($period_2))
																		{
																			$period = array_merge($period_1, $period_2);

																		}
																		elseif(!empty($period_1) && empty($period_2))
																		{
																			$period = $period_1;
																		}
																		elseif(empty($period_1) && !empty($period_2))
																		{
																			$period = $period_2;
																		}
																		if(!empty($period))
																		{

																			foreach($period as $period_data)
																			{

																				echo '<div class="btn-group m-b-sm">';

																				echo '<button class="btn btn-primary class_list_button  dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);

																				$start_time_data = explode(":", $period_data->start_time);

																				$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

																				$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);



																				$end_time_data = explode(":", $period_data->end_time);

																				$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																				$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);



																				echo '<span class="time"> ('.$start_hour.':'.$start_min.' - '.$end_hour.':'.$end_min.') </span>';

																				$create_meeting = '';

																				$update_meeting = '';

																				$delete_meeting = '';

																				$meeting_statrt_link = '';

																				if (get_option('smgt_enable_virtual_classroom') == 'yes')
																				{

																					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																					if(empty($meeting_data))
																					{

																						$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																					}
																					else
																					{

																						$create_meeting = '';

																					}

																					if(!empty($meeting_data))
																					{

																						$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																						$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

																						$meeting_statrt_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Virtual Class Start','school-mgt').'</a></li>';

																					}
																					else
																					{

																						$update_meeting = '';

																						$delete_meeting = '';

																						$meeting_statrt_link = '';

																					}

																				}

																				echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';

																				echo '</span></span><span class="caret"></span></button>';

																				if($user_access['edit']=='1' || $user_access['delete']=='1')
																				{

																					?>

																					<ul role="menu" class="pt-2 dropdown-menu">

																						<?php

																						if($user_access['edit']=='1')

																						{

																							?>

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=schedule&tab=addroute&action=edit&route_id=<?php echo $period_data->route_id; ?>"><?php echo esc_attr__('Edit','school-mgt'); ?></a></li>

																							<?php

																						}

																						if($user_access['delete']=='1')

																						{

																							?>

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?dashboard=user&page=schedule&tab=teacher_timetable&action=delete_teacher&route_id=<?php echo $period_data->route_id; ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php echo esc_attr__('Delete','school-mgt'); ?></a></li>

																							<?php echo $create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link; ?>

																							<?php

																						}

																						?>

																					</ul>

																					<?php

																				}

																				echo '</div>';

																			}

																		}

																		?>
																	</td>
																</tr>
																<?php
															}

															?>
														</table>

													</div>

												</div>

											</div>
											<?php
										}

									}
									else
									{

										esc_attr_e( 'Teacher data not avilable', 'school-mgt' );

									}

									?>

								</div>

							</div><!-------- penal Body ------->

						</div><!-------- penal White ------->

						<?php

					}

				}

				elseif($school_obj->role == 'student')

				{

					$class = $school_obj->class_info;

					$sectionname="";

					$section=0;

					$section = get_user_meta(get_current_user_id(),'class_section',true);

					if($section!="")

					{

						$sectionname = mj_smgt_get_section_name($section);

					}

					else

					{

						$section=0;

					}

					?>

					<div class="accordion-item mt-1 class_border_div">
						<h4 class="accordion-header" id="heading<?php echo $i;?>">
							<a  class="class_section_a_tag" data-bs-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">
								<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
									<?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo smgt_get_class_section_name_wise($class->class_id,$section); ?> &nbsp;&nbsp;
								</button>
							</a>
						</h4>
						<div id="collapse<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample">
							<div class="panel-body">
								<div class="table-responsive_div"> <!-- Added wrapper div -->
									<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">
										<?php foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname ) { ?>
											<tr>
												<th width="100"><?php echo $dayname;?></th>
												<td>
													<?php
													$period = $obj_route->mj_smgt_get_periad ( $class->class_id,$section,$daykey );
													if (! empty ( $period ))
														foreach ( $period as $period_data ) {
															$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
															if(!empty($meeting_data)) {
																$data_toggle = 'data-bs-toggle="dropdown"';
															} else {
																$data_toggle = '';
															}
															echo '<div class="btn-group m-b-sm">';
															echo '<button class="btn btn-primary class_list_button dropdown-toggle" aria-expanded="false" '.$data_toggle.'><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );
																$start_time_data = explode(":", $period_data->start_time);
																$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																$start_am_pm=$start_time_data[2];
																$end_time_data = explode(":", $period_data->end_time);
																$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																$end_am_pm=$end_time_data[2];
																echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
																$virtual_classroom_page_name = 'virtual_classroom';
																$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);
															if (get_option('smgt_enable_virtual_classroom') == 'yes') {
																if ($virtual_classroom_access_right['view'] == '1') {
																	if(!empty($meeting_data)) {
																		$meeting_join_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_join_link.'" target="_blank">'.esc_attr__('Join Virtual Class','school-mgt').'</a></li>';
																	} else {
																		$meeting_join_link = '';
																	}
																}

															}
															echo "<span class='caret'></span></button>";
															echo '<ul role="menu" class="dropdown-menu schedule_menu">'.$meeting_join_link.'</ul>';
															echo '</div>';
														}
													?>
												</td>
											</tr>
										<?php } ?>
									</table>
								</div> <!-- End of wrapper div -->
							</div>
						</div>
					</div>


					<?php

				}

				elseif($school_obj->role == 'parent')

				{

					$chil_array =$school_obj->child_list;

					$i = 0;

					if(!empty($chil_array))

					{

						foreach($chil_array as $child_id)

						{

							$i++;

							$sectionname="";

							$section=0;

							$class = $school_obj->mj_smgt_get_user_class_id($child_id);

							$section = get_user_meta($child_id,'class_section',true);

							if($section!="")

							{

								$sectionname = mj_smgt_get_section_name($section);

							}

							else

							{

								$section=0;

							}

							?>





						<div class="accordion-item mt-1 class_border_div">

							<h4 class="accordion-header" id="heading<?php echo $i;?>">

								<a  class="class_section_a_tag" data-bs-toggle="collapse" data-parent="#accordion"

									href="#collapse<?php echo $i;?>">

								<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">

											<?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo smgt_get_class_section_name_wise($class->class_id,$section) ?> &nbsp;&nbsp;

								</a>
							</h4>



								<div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php if($i== 1) echo 'in';?>">

									<div class="panel-body">

										<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">

										<?php

											foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )

											{

											?>

												<tr>

													<th width="100"><?php echo $dayname;?></th>

													<td>

														<?php

														$period = $obj_route->mj_smgt_get_periad ( $class->class_id,$section,$daykey );

															if (! empty ( $period ))

																foreach ( $period as $period_data )

																{

																	$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																	if(!empty($meeting_data))

																	{

																		$data_toggle = 'data-bs-toggle="dropdown"';

																	}

																	else

																	{

																		$data_toggle = '';

																	}

																	echo '<div class="btn-group m-b-sm">';

																	echo '<button class="btn btn-primary class_list_button dropdown-toggle" aria-expanded="false" '.$data_toggle.'><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );

																	$start_time_data = explode(":", $period_data->start_time);

																	$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

																	$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

																	$start_am_pm=$start_time_data[2];



																	$end_time_data = explode(":", $period_data->end_time);

																	$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																	$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

																	$end_am_pm=$end_time_data[2];

																	echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';

																	$virtual_classroom_page_name = 'virtual_classroom';

																	$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);

																	if (get_option('smgt_enable_virtual_classroom') == 'yes')

																	{

																		if ($virtual_classroom_access_right['view'] == '1')

																		{

																			if(!empty($meeting_data))

																			{

																				$meeting_join_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_join_link.'" target="_blank">'.esc_attr__('Join Virtual Class','school-mgt').'</a></li>';

																			}

																			else

																			{

																				$meeting_join_link = '';

																			}

																		}



																	}
																	echo "<span class='caret'></span></button>";

																		echo '<ul role="menu" class="dropdown-menu schedule_menu">

																			'.$meeting_join_link.'

																		</ul>';

																		echo '</div>';

																}

															?>

													</td>

												</tr>

									<?php 	} ?>

										</table>

									</div>

								</div>

								</div>

							<?php

						}

					}

					else

					{

						esc_attr_e( 'Child data not avilable', 'school-mgt' );

					}

				}

				?>

			</div>

		</div>	<!----------- PENAL  BODY ------------->

	</div><!------------ TAB CONTENT ------------>

</div><!----------- PENAL  BODY ------------->

<?php ?>