<?php ?>

<script type="text/javascript">

jQuery(document).ready(function($)
{

	"use strict";	

	var table =  jQuery('#meeting_list').DataTable(
	{
		//stateSave: true,
		'order': [1, 'asc'],

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
				 {"bSortable": true},
				 {"bSortable": false}],

			language:<?php echo mj_smgt_datatable_multi_language();?>	
	
       });	



	 $('#meeting_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1}); 

    $("#start_date").datepicker({

        dateFormat: "yy-mm-dd",

		minDate:0,

        onSelect: function (selected) {

            var dt = new Date(selected);

            dt.setDate(dt.getDate() + 0);

            $("#end_date").datepicker("option", "minDate", dt);

        }

    });

    $("#end_date").datepicker({

       dateFormat: "yy-mm-dd",

	   minDate:0,

        onSelect: function (selected) {

            var dt = new Date(selected);

            dt.setDate(dt.getDate() + 0);

            $("#start_date").datepicker("option", "maxDate", dt);

        }

    });




	var table =  jQuery('#past_participle_list').DataTable({

		'order': [1, 'asc'],

		"dom": 'lifrtp',

		"aoColumns":[

				{"bSortable": true},
				{"bSortable": true},

				{"bSortable": true},

			],

		language:<?php echo mj_smgt_datatable_multi_language();?>

		});

	$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

});

</script>

<?php 

$obj_virtual_classroom = new mj_smgt_virtual_classroom;

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'meeting_list';

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

// EDIT MEETING IN ZOOM

if(isset($_POST['edit_meeting']))

{

	$nonce = $_POST['_wpnonce'];

	if ( wp_verify_nonce( $nonce, 'edit_meeting_nonce' ) )

	{

		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);

		if($result)

		{

			wp_redirect ( home_url().'?dashboard=user&page=virtual_classroom&tab=meeting_list&message=2');

		}		

	}

}

// DELETE STUDENT IN ZOOM

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

{

	$result= $obj_virtual_classroom->mj_smgt_delete_meeting_in_zoom($_REQUEST['meeting_id']);

	if($result)

	{

		wp_redirect ( home_url().'?dashboard=user&page=virtual_classroom&tab=meeting_list&message=3');

	}

}

?>

<!-- Nav tabs -->

<?php

$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

switch($message)

{

	case '1':

		$message_string = esc_attr__('Virtual Class Added Successfully.','school-mgt');

		break;

	case '2':

		$message_string = esc_attr__('Virtual Class Updated Successfully.','school-mgt');

		break;

	case '3':

		$message_string = esc_attr__('Virtual Class Deleted Successfully.','school-mgt');

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

} 

?>

<!-- POP up code -->

<div class="popup-bg">

    <div class="overlay-content">

	    <div class="modal-content">

		    <div class="view_meeting_detail_popup">

		    </div>

		</div>

	</div>

</div>

<div class="panel-body panel-white frontend_list_margin_30px_res">


	<!-- Tab panes -->

	<?php

	if($active_tab == 'meeting_list')

	{

		$user_id=get_current_user_id();

		if($school_obj->role == 'student')
		{
			$class_id = get_user_meta(get_current_user_id(),'class_name',true);
			$section_id = get_user_meta(get_current_user_id(),'class_section',true);
			if($section_id)
			{
				$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_and_section_id_data_in_zoom($class_id,$section_id);
			}
			else
			{
				$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_data_in_zoom($class_id);
			}
		}
		elseif ($school_obj->role == 'teacher')
		{
			//$meeting_list_data = mj_smgt_get_allclass();
			$retrieve_class = mj_smgt_get_allclass();
			foreach ($retrieve_class as $data)
			{
				$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_data_in_zoom($data['class_id']);
			}
			
		}
		elseif($school_obj->role == 'parent')
		{
			$chil_array =$school_obj->child_list;
			if(!empty($chil_array))
			{
				foreach($chil_array as $child_id)
				{
					$class_id = get_user_meta($child_id,'class_name',true);
					$section_id = get_user_meta($child_id,'class_section',true);
					if($section_id)
					{
						$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_and_section_id_data_in_zoom($class_id,$section_id);
					}
					else
					{
						$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_data_in_zoom($class_id);
					}
				}
			}
			
		}
		//------- MEETING DATA FOR SUPPORT STAFF ---------//
		else
		{
			$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_all_meeting_data_in_zoom();
		} 
		if(!empty($meeting_list_data))
		{

			?>

			<div class="panel-body">

				<form id="frm-example" name="frm-example" method="post">

					<div class="table-responsive">

						<table id="meeting_list" class="display datatable" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
								<tr>
									<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr_e( 'Subject Name', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Class Name', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Section Name', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Teacher Name', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Day', 'school-mgt' ) ;?></th>
									<th> <?php esc_attr_e( 'Start To End Date', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Start To End Time', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Agenda', 'school-mgt' ) ;?></th>
									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</thead>
							<tbody>

							<?php 

							if($school_obj->role == 'parent')

							{

								$chil_array =$school_obj->child_list;

								if(!empty($chil_array))

								{

									foreach($chil_array as $child_id)

									{

										$class_id = get_user_meta($child_id,'class_name',true);

										$section_id = get_user_meta($child_id,'class_section',true);

										if($section_id)

										{

											$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_and_section_id_data_in_zoom($class_id,$section_id);

										}

										else

										{

											$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_data_in_zoom($class_id);

										}

										$i=0;

										foreach ($meeting_list_data as $retrieved_data)

										{

											if($retrieved_data->weekday_id == '2')

											{

												$day = esc_attr__('Monday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '3')

											{

												$day = esc_attr__('Tuesday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '4')

											{

												$day = esc_attr__('Wednesday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '5')

											{

												$day = esc_attr__('Thursday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '6')

											{

												$day = esc_attr__('Friday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '7')

											{

												$day = esc_attr__('Saturday','school-mgt');

											}

											elseif($retrieved_data->weekday_id == '1')

											{

												$day = esc_attr__('Sunday','school-mgt');

											}

											$route_data = mj_smgt_get_route_by_id($retrieved_data->route_id);

											$stime = explode(":",$route_data->start_time);

											$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

											$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

											$start_am_pm=$stime[2];

											$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;

											$etime = explode(":",$route_data->end_time);

											$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

											$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

											$end_am_pm=$etime[2];

											$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
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

													<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Virtual_class.png"?>" alt="" class="massage_image center">

													</p>

												</td>
												<td><?php $subid=$retrieved_data->subject_id;

													echo mj_smgt_get_single_subject_name($subid);

												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i></td>

												<td><?php $cid=$retrieved_data->class_id;

													echo  $clasname=mj_smgt_get_class_name($cid);

												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

												<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Section Name','school-mgt');?>"></i></td>

												<td><?php echo mj_smgt_get_teacher($retrieved_data->teacher_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

												<td><?php echo $day; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>

												<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?>
												<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Date','school-mgt');?>"></i></td>

												<td><?php echo MJ_smgt_timeremovecolonbefoream_pm($start_time); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo MJ_smgt_timeremovecolonbefoream_pm($end_time); ?>
												<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Time','school-mgt');?>"></i></td>

												<td>
													<?php
													if(!empty($retrieved_data->agenda))
													{
														$strlength= strlen($retrieved_data->agenda);
														if($strlength > 50)
															echo substr($retrieved_data->agenda, 0,30).'...';
														else
															echo $retrieved_data->agenda;
													}
													else
													{
														echo "N/A";
													}
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Agenda','school-mgt');?>"></i>
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
																		<a href="<?php echo $retrieved_data->meeting_join_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Join Virtual Class','school-mgt');?> </a>
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

									}

								}

							}

							elseif ($school_obj->role == 'teacher')

							{

								$retrieve_class = mj_smgt_get_allclass();

								foreach ($retrieve_class as $data)

								{

									$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_meeting_by_class_id_data_in_zoom($data['class_id']);
									$i=0;
									foreach ($meeting_list_data as $retrieved_data)

									{

										if($retrieved_data->weekday_id == '2')

										{

											$day = esc_attr__('Monday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '3')

										{

											$day = esc_attr__('Tuesday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '4')

										{

											$day = esc_attr__('Wednesday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '5')

										{

											$day = esc_attr__('Thursday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '6')

										{

											$day = esc_attr__('Friday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '7')

										{

											$day = esc_attr__('Saturday','school-mgt');

										}

										elseif($retrieved_data->weekday_id == '1')

										{

											$day = esc_attr__('Sunday','school-mgt');

										}

										$route_data = mj_smgt_get_route_by_id($retrieved_data->route_id);

										$stime = explode(":",$route_data->start_time);

										$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

										$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

										$start_am_pm=$stime[2];

										$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;

										$etime = explode(":",$route_data->end_time);

										$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

										$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

										$end_am_pm=$etime[2];

										$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
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

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Virtual_class.png"?>" alt="" class="massage_image center">

												</p>

											</td>
											<td><?php $subid=$retrieved_data->subject_id;

											echo mj_smgt_get_single_subject_name($subid);

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i></td>

											<td><?php $cid=$retrieved_data->class_id;

											echo  $clasname=mj_smgt_get_class_name($cid);

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

											<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Section Name','school-mgt');?>"></i></td>

											<td><?php echo mj_smgt_get_teacher($retrieved_data->teacher_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

											<td><?php echo $day; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>

											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?>

											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Date','school-mgt');?>"></i></td>

											<td><?php echo MJ_smgt_timeremovecolonbefoream_pm($start_time); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo MJ_smgt_timeremovecolonbefoream_pm($end_time); ?>

											<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Time','school-mgt');?>"></i></td>

											<td>

												<?php

												if(!empty($retrieved_data->agenda))

												{

													$strlength= strlen($retrieved_data->agenda);

													if($strlength > 50)

														echo substr($retrieved_data->agenda, 0,30).'...';

													else

														echo $retrieved_data->agenda;

												}

												else

												{

													echo "N/A";

												}

												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Agenda','school-mgt');?>"></i>

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
																if ($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="" class="float_left_width_100 show-popup" meeting_id="<?php echo $retrieved_data->meeting_id; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></a>
																	</li>
																	<li class="float_left_width_100 ">
																		<a href="<?php echo $retrieved_data->meeting_start_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Start Virtual Class','school-mgt');?> </a> 
																	</li>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=virtual_classroom&tab=view_past_participle_list&action=view&meeting_uuid=<?php echo $retrieved_data->uuid;?>" class="float_left_width_100"><i class="fa fa-eye" aria-hidden="true"></i> <?php esc_attr_e('View Participant List','school-mgt');?> </a>
																	</li>
																	<?php
																}
																elseif ($school_obj->role == 'student')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="<?php echo $retrieved_data->meeting_join_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Join Meeting','school-mgt');?> </a>
																	</li>
																	<?php
																}
																if($user_access['edit']=='1')
																{
																	?>
																	<li class="float_left_width_100 border_bottom_menu">
																		<a href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100"><i class="fa fa-edit"></i> <?php esc_attr_e('Edit','school-mgt');?> </a>
																	</li>
																	<?php
																}
																if($user_access['delete']=='1')
																{
																	?>
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
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

								}

							}
							else
							{

								$i=0;
								foreach ($meeting_list_data as $retrieved_data)

								{

									if($retrieved_data->weekday_id == '2')

									{

										$day = esc_attr__('Monday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '3')

									{

										$day = esc_attr__('Tuesday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '4')

									{

										$day = esc_attr__('Wednesday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '5')

									{

										$day = esc_attr__('Thursday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '6')

									{

										$day = esc_attr__('Friday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '7')

									{

										$day = esc_attr__('Saturday','school-mgt');

									}

									elseif($retrieved_data->weekday_id == '1')

									{

										$day = esc_attr__('Sunday','school-mgt');

									}

									$route_data = mj_smgt_get_route_by_id($retrieved_data->route_id);

									$stime = explode(":",$route_data->start_time);

									$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);

									$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);

									$start_am_pm=$stime[2];

									$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;

									$etime = explode(":",$route_data->end_time);

									$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);

									$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);

									$end_am_pm=$etime[2];

									$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
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

											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Virtual_class.png"?>" alt="" class="massage_image center">

											</p>

										</td>
										<td><?php $subid=$retrieved_data->subject_id;

											echo mj_smgt_get_single_subject_name($subid);

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i></td>

										<td><?php $cid=$retrieved_data->class_id;

											echo  $clasname=mj_smgt_get_class_name($cid);

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>

										<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Section Name','school-mgt');?>"></i></td>

										<td><?php echo mj_smgt_get_teacher($retrieved_data->teacher_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>

										<td><?php echo $day; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>

										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?>

										<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Date','school-mgt');?>"></i></td>

										<td><?php echo MJ_smgt_timeremovecolonbefoream_pm($start_time); ?> <?php esc_html_e('To','school-mgt'); ?> <?php echo MJ_smgt_timeremovecolonbefoream_pm($end_time); ?>

										<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Time','school-mgt');?>"></i></td>

										<td>

										<?php

										if(!empty($retrieved_data->agenda))

										{

											$strlength= strlen($retrieved_data->agenda);

											if($strlength > 50)

												echo substr($retrieved_data->agenda, 0,30).'...';

											else

												echo $retrieved_data->agenda;

										}

										else

										{

											echo "N/A";

										}

										?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Agenda','school-mgt');?>"></i>

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
															if ($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="" class="float_left_width_100 show-popup" meeting_id="<?php echo $retrieved_data->meeting_id; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></a>
																</li>
																<li class="float_left_width_100 ">
																	<a href="<?php echo $retrieved_data->meeting_start_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Start Virtual Class','school-mgt');?> </a> 
																</li>
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=virtual_classroom&tab=view_past_participle_list&action=view&meeting_uuid=<?php echo $retrieved_data->uuid;?>" class="float_left_width_100"><i class="fa fa-eye" aria-hidden="true"></i> <?php esc_attr_e('View Participant List','school-mgt');?> </a>
																</li>
																<?php
															}
															elseif ($school_obj->role == 'student')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="<?php echo $retrieved_data->meeting_join_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Join Meeting','school-mgt');?> </a>
																</li>
																<?php
															}
															if($user_access['edit']=='1')
															{
																?>
																<li class="float_left_width_100 border_bottom_menu">
																	<a href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100"><i class="fa fa-edit"></i> <?php esc_attr_e('Edit','school-mgt');?> </a>
																</li>
																<?php
															}
															if($user_access['delete']=='1')
															{
																?>
																<li class="float_left_width_100 ">
																	<a href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>
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

							}

							?>

							</tbody>

						</table>

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

	elseif($active_tab == 'edit_meeting')

	{

		$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_data_in_zoom($_REQUEST['meeting_id']);

		$route_data = mj_smgt_get_route_by_id($meeting_data->route_id);

		$start_time_data = explode(":", $route_data->start_time);

		$end_time_data = explode(":", $route_data->end_time);

		if ($start_time_data[1] == 0 OR $end_time_data[1] == 0)

		{

			$start_time_minit = '00';

			$end_time_minit = '00';

		}

		else

		{

			$start_time_minit = $start_time_data[1];

			$end_time_minit = $end_time_data[1];

		}

		$start_time  = date("H:i A", strtotime("$start_time_data[0]:$start_time_minit $start_time_data[2]"));

		$end_time  = date("H:i A", strtotime("$end_time_data[0]:$end_time_minit $end_time_data[2]"));

		?>

		<div class="panel-body">   

	        <form name="route_form" action="" method="post" class="form-horizontal" id="meeting_form">

	        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

			<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">

			<input type="hidden" name="meeting_id" value="<?php echo $_REQUEST['meeting_id'];?>">

			<input type="hidden" name="route_id" value="<?php echo $meeting_data->route_id;?>">

			<input type="hidden" name="class_id" value="<?php echo $route_data->class_id;?>">

			<input type="hidden" name="subject_id" value="<?php echo $route_data->subject_id;?>">

			<input type="hidden" name="class_section_id" value="<?php echo $route_data->section_name;?>">

			<input type="hidden" name="duration" value="<?php echo $meeting_data->duration;?>">

			<input type="hidden" name="weekday" value="<?php echo $route_data->weekday;?>">

			<input type="hidden" name="start_time" value="<?php echo $start_time;?>">

			<input type="hidden" name="end_time" value="<?php echo $end_time;?>">

			<input type="hidden" name="teacher_id" value="<?php echo $route_data->teacher_id;?>">

			<input type="hidden" name="zoom_meeting_id" value="<?php echo $meeting_data->zoom_meeting_id;?>">

			<input type="hidden" name="uuid" value="<?php echo $meeting_data->uuid;?>">

			<input type="hidden" name="meeting_join_link" value="<?php echo $meeting_data->meeting_join_link;?>">

			<input type="hidden" name="meeting_start_link" value="<?php echo $meeting_data->meeting_start_link;?>">

			<div class="header">	

				<h3 class="first_hed"><?php esc_html_e('Virtual Classroom Information','school-mgt');?></h3>

			</div>

			<div class="form-body user_form">

				<div class="row">	

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="class_name" class="form-control" maxlength="50" type="text" value="<?php echo mj_smgt_get_class_name($route_data->class_id); ?>" name="class_name" disabled>

								<label for="userinput1" class=""><?php esc_html_e('Class Name','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="class_section" class="form-control" maxlength="50" type="text" value="<?php echo mj_smgt_get_section_name($route_data->section_id); ?>" name="class_section" disabled>

								<label for="userinput1" class=""><?php esc_html_e('Class Section','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="subject" class="form-control" type="text" value="<?php echo mj_smgt_get_single_subject_name($route_data->subject_id); ?>" name="class_section" disabled>

								<label for="userinput1" class=""><?php esc_html_e('Subject','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="start_time" class="form-control" type="text" value="<?php echo $start_time; ?>" name="start_time" disabled>

								<label for="userinput1" class=""><?php esc_html_e('Start Time','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="end_time" class="form-control" type="text" value="<?php echo $end_time; ?>" name="end_time" disabled>

								<label for="userinput1" class=""><?php esc_html_e('End Time','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="start_date" class="form-control validate[required] text-input" type="text" placeholder="<?php esc_html_e('Enter Start Date','school-mgt');?>" name="start_date" value="<?php echo date("Y-m-d",strtotime($meeting_data->start_date)); ?>" readonly>

								<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="end_date" class="form-control validate[required] text-input" type="text" placeholder="<?php esc_html_e('Enter Exam Date','school-mgt');?>" name="end_date" value="<?php echo date("Y-m-d",strtotime($meeting_data->end_date)); ?>" readonly>

								<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>

							</div>

						</div>

					</div>

					<div class="col-md-6 note_text_notice">

						<div class="form-group input">

							<div class="col-md-12 note_border margin_bottom_15px_res">

								<div class="form-field">

									<textarea name="agenda" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="250" id=""><?php echo $meeting_data->agenda; ?></textarea>

									<span class="txt-title-label"></span>

									<label class="text-area address"><?php esc_html_e('Topic','wpnc');?></label>

								</div>

							</div>

						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group input">

							<div class="col-md-12 form-control">

								<input id="password" class="form-control validate[minSize[8],maxSize[12]]" type="password" value="<?php echo $meeting_data->password; ?>" name="password">

								<label for="userinput1" class=""><?php esc_html_e('Password','school-mgt');?></label>

							</div>

						</div>

					</div>

				</div>

			</div>

			<?php wp_nonce_field( 'edit_meeting_nonce' ); ?>
			<div class="form-body user_form">

				<div class="row">	

					<div class="col-md-6">        	

						<input type="submit" value="<?php esc_attr_e('Save Meeting','school-mgt'); ?>" name="edit_meeting" class="btn btn-success save_btn" />

					</div>   

				</div>   

			</div>    
			<div class="offset-sm-2 col-sm-8">        	

	        	

	        </div>        

	     	</form>

	    </div>

		<?php

	}

	elseif($active_tab == 'view_past_participle_list')

	{

		$past_participle_list = $obj_virtual_classroom->mj_smgt_view_past_participle_list_in_zoom($_REQUEST['meeting_uuid']);
		if(!empty($past_participle_list))
		{
			?>

			<div class="panel-body">

				<form id="frm-example" name="frm-example" method="post">

					<div class="table-responsive">

						<table id="past_participle_list" class="display datatable" cellspacing="0" width="100%">
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
									<th><?php esc_attr_e('Student Name','school-mgt');?></th>
									<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
									<th><?php esc_attr_e('Class Name','school-mgt');?> </th>
									<th><?php esc_attr_e('Payment Title','school-mgt');?></th>
									<th><?php esc_attr_e('Amount','school-mgt');?></th>
									<th><?php esc_attr_e('Status','school-mgt');?></th>
									<th><?php esc_attr_e('Date','school-mgt');?></th>
									<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$i=0;
							foreach ($past_participle_list->participants as $retrieved_data)
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
									<td class="user_image width_50px profile_image_prescription padding_left_0">
										<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Virtual_class.png"?>" alt="" class="massage_image center">
										</p>
									</td>
									<td><?php echo $retrieved_data->name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Name','school-mgt');?>"></i></td>
									<td><?php echo $retrieved_data->user_email;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Email','school-mgt');?>"></i></td>
								</tr>

								<?php
								$i++; 

							}

							?>

							</tbody>

						</table>

					</div>

				</form>

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

<?php ?>