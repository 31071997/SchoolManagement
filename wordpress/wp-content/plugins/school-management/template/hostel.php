<?php 

//-------- CHECK BROWSER JAVA SCRIPT ----------//

mj_smgt_browser_javascript_check();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'hostel_list';

$role_name=mj_smgt_get_user_role(get_current_user_id());

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

				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=2'); 			

			}

		}

		else

		{

			$result=$obj_hostel->mj_smgt_insert_hostel($_POST);

			if($result)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=1'); 	

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

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=3'); 	

	}

}

if(isset($_REQUEST['delete_selected_hostel']))

{	

	if(!empty($_REQUEST['id']))

	foreach($_REQUEST['id'] as $id)

	{

		$result=$obj_hostel->mj_smgt_delete_hostel($id);

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=3'); 	

	}

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=3');

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

				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=5'); 	

			}

		}

		else

		{

			$result=$obj_hostel->mj_smgt_insert_room($_POST);

			if($result)

			{

				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=4'); 	

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

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=6'); 	

	}

}



if(isset($_REQUEST['delete_selected_room']))

{	

	if(!empty($_REQUEST['id']))

	foreach($_REQUEST['id'] as $id)

	{

		$result=$obj_hostel->mj_smgt_delete_room($id);

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=6');

	}

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=6');

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

					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=8'); 	

				}

			}

			else

			{

				wp_redirect ( home_url().'?dashboard=user&page=hostel&tab=add_bed&action=edit_bed&bed_id='.$bed_id.'&message=10');

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

				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=10'); 	

				die;

			}

			else

			{

				$result=$obj_hostel->mj_smgt_insert_bed($_POST);

				if($result)

				{

					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=7'); 	

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

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=9'); 	

	}

}

if(isset($_REQUEST['delete_selected_bed']))

{		

	if(!empty($_REQUEST['id']))

	foreach($_REQUEST['id'] as $id)

	{

		$result=$obj_hostel->mj_smgt_delete_bed($id);

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=9');

	}

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=9');

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

			wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=11'); 	

		}

	} 

}	

//--------- delete Assign BED --------------------

	 

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_assign_bed')

{

	$result=$obj_hostel->mj_smgt_delete_assigned_bed($_REQUEST['room_id'],$_REQUEST['bed_id'],$_REQUEST['student_id']);

	if($result)

	{

		wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=12'); 	

	}

}

?>

<!-- Nav tabs -->

<div class="panel-body panel-white frontend_list_margin_30px_res">

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

		<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">

			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>

			</button>

			<?php echo $message_string;?>

		</div>

		<?php 

	} ?> 

	<script type="text/javascript" >

		jQuery(document).ready(function($)

		{

			"use strict";	

			$('#hostel_list_frontend').DataTable({

				"initComplete": function(settings, json) {

					$(".print-button").css({"margin-top": "-5%"});

				},
				//stateSave: true,
				"dom": 'lifrtp',

				"order": [[ 2, "asc" ]],

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

			



			$('#room_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



			$('#bed_list').DataTable({

				"initComplete": function(settings, json) {

					$(".print-button").css({"margin-top": "-5%"});

				},
				//stateSave: true,
				"dom": 'lifrtp',

				"order": [[ 2, "asc" ]],

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



			

			$('#bed_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

			$('#assign_bed_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



			$('.datepicker').datepicker({

				defaultDate: null,

				changeMonth: true,

				changeYear: true,

				yearRange:'-75:+10',

				dateFormat: 'yy-mm-dd'

			});

					

			function checkselectvalue(value,i) 

			{	

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



			$('#hostel_form_fornt').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});



			$('#room_list_fornt').DataTable({

				"initComplete": function(settings, json) {

					$(".print-button").css({"margin-top": "-5%"});

				},
				//stateSave: true,
				"dom": 'lifrtp',

				"order": [[ 2, "asc" ]],

				"aoColumns":[	                  

								<?php

								if($role_name == "supportstaff")

								{

									?>

									{"bSortable": false},

									<?php

								}

								// if($user_access['add']=='1')

								// {

									?>

									{"bSortable": false},

									<?php

								// }

								?>

								{"bSortable": true},

								{"bSortable": true},

								{"bSortable": true},

								{"bSortable": true},

								{"bSortable": true},

								{"bSortable": true},

								<?php

								if($user_access['add']=='1')

								{

									?>

									{"bSortable": true},

									<?php

								}

								?>

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

			$("#delete_selected_room").on('click', function()

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







			$('body').on('change','.student_check',function(){

				let index = $(this).attr('data-index');

				if($('#students_list_'+index).val() != 0)

				{

					$('#assign_date_'+index).addClass('validate[required]');

				}else{

					$('#assign_date_'+index).removeClass('validate[required]');



				}

			});

		});

	</script>

	<!-- Tab panes -->

	<?php

	if($active_tab == 'hostel_list')

	{
		$user_id=get_current_user_id();

		//------- BED LIST FOR STUDENT ---------//
		$own_data=$user_access['own_data'];
		if($own_data == '1')
		{ 
			$retrieve_class = mj_smgt_get_hostel_data_user_access_right_wise($user_id,$school_obj->role);
		}
		else{
			$tablename='smgt_hostel';

			$retrieve_class = mj_smgt_get_all_data($tablename);
		}

		if(!empty($retrieve_class))

		{

			?>

			<div class="panel-body">

				<div class="table-responsive">

					<form id="frm-example" name="frm-example" method="post">

						<table id="hostel_list_frontend" class="display dataTable" width="100%" cellspacing="0" width="100%">	

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

									<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>

									<th><?php esc_attr_e('Hostel Type','school-mgt');?></th>

									<th><?php esc_attr_e('Hostel Address','school-mgt');?></th>

									<th><?php esc_attr_e('Intake/Capacity','school-mgt');?></th>

									<th><?php esc_attr_e('Description','school-mgt');?></th>

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

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<td class="checkbox_width_10px">

												<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">

											</td>

											<?php

										}

										?>

										<td class="user_image width_50px profile_image_prescription padding_left_0">

											<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

												<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">

											</p>

										</td>

										<td><?php echo $retrieved_data->hostel_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i></td>

										<td> <?php if(!empty($retrieved_data->hostel_type)){ echo $retrieved_data->hostel_type;}else{ echo "N/A"; }?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Type','school-mgt');?>" ></i></td>

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

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->hostel_address)){ echo $retrieved_data->hostel_address;}else{ _e('Hostel Address','school-mgt');}?>" ></i>

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

												if($strlength > 50)

													echo substr($retrieved_data->Description, 0,50).'...';

												else

													echo $retrieved_data->Description;

											}

											else

											{

												echo 'N/A';

											}

											?><i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->Description)){ echo $retrieved_data->Description;}else{ _e('Description','school-mgt');}?>" ></i>

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
																if($role_name == "supportstaff" || $role_name == "teacher")
																{
																?>
																<li class="float_left_width_100">

																	<a href="?dashboard=user&page=hostel&tab=hostel_room_list&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/hostel_room_list.png"?>" style="height:13px;">&nbsp;&nbsp;&nbsp;<?php esc_attr_e('Hostel Room List','school-mgt');?></a>

																</li>
																<?php
																}
																?>

																<?php

																if($user_access['edit']=='1')

																{

																	?>

																	<li class="float_left_width_100 border_bottom_item">

																		<a href="?dashboard=user&page=hostel&tab=add_hostel&action=edit&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																	?>

																	<li class="float_left_width_100 ">

																		<a href="?dashboard=user&page=hostel&tab=hostel_list&action=delete&hostel_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>

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

										} ?>

									</tr>

									<?php

									$i++;

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

									<input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">

									<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

								</button>

								<?php

								if($user_access['delete']=='1')

								{

									?>

									<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_hostel" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<?php

								}?>

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

					<a href="<?php echo home_url().'?dashboard=user&page=hostel&tab=add_hostel&action=insert';?>">

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
	if($active_tab == 'hostel_room_list')
	{
		$retrieve_class = $obj_hostel->mj_smgt_get_room_by_hostel_id($_REQUEST['hostel_id']);
		if(!empty($retrieve_class))

		{

			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					"use strict";	

					var table =  jQuery('#hostel_room_list').DataTable({
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
			<!-- POP up code -->

			<div class="popup-bg">

				<div class="overlay-content">

					<div class="modal-content">

						<div class="view_popup"></div>     

					</div>

				</div>    

			</div>

			<!-- End POP-UP Code -->

			<div class="panel-body">

				<div class="table-responsive">

					<form id="frm-example" name="frm-example" method="post">

						<table id="hostel_room_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">	

							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

								<tr>

									<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

									<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>

									<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>

									<th><?php esc_attr_e('Room Type','school-mgt');?></th>

									<th><?php esc_attr_e('Remaining	Bed Capacity','school-mgt');?></th>

									<th><?php esc_attr_e('Availability','school-mgt');?></th>

									<th><?php esc_attr_e('Description','school-mgt');?></th>

									<?php

									if($user_access['add']=='1')

									{

										?>

										<th><?php esc_attr_e('View & Assign Room','school-mgt');?></th>

										<?php

									}

										?>

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

										<td><?php echo $retrieved_data->room_unique_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i></td>

										<td><a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" ><?php echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i></td>

										<td><?php echo get_the_title($retrieved_data->room_category);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Type','school-mgt');?>" ></i></td>

										<td><?php 
												echo $capacity.' ';
												esc_attr_e('Out Of', 'school-mgt');
												echo ' '.$retrieved_data->beds_capacity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Bed Capacity','school-mgt');?>" ></i></td>

										<?php 

											$room_cnt =mj_smgt_hostel_room_status_check($retrieved_data->id);

											

											$bed_capacity=(int)$retrieved_data->beds_capacity;

											

											if($room_cnt >= $bed_capacity)

											{

											?> 

												<td><label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

											<?php

											}

											else 

											{?>

												<td><label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

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

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->room_description)){ echo $retrieved_data->room_description;}else{ _e('Description','school-mgt');} ?>" ></i>

										</td>

										<?php

										if($user_access['add']=='1')

										{

											?>

											<td>

												<?php

												if($room_cnt >= $bed_capacity)

												{

													echo esc_attr_e('No Bed Available In This Room.','school-mgt');

												}

												else

												{

													// if($user_access['add']=='1')

													// {

														?>

														<button class="btn btn-default assign_room_btn_design"><a href="?dashboard=user&page=hostel&tab=assign_room&action=view_assign_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i> <?php esc_attr_e('Assign Room','school-mgt');?></a></button>

														<?php

													// }

												}

												?>

											</td>

											<?php

										}

											?>

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
																<?php

																if($role_name == "supportstaff" || $role_name == "teacher")

																{
																?>
																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=hostel&tab=assign_bed_list&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i><?php esc_attr_e('Assign Bed List','school-mgt');?></a>

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

		$obj_hostel=new smgt_hostel;

		?>

		<?php 

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')

			{

				$edit=1;

				$hostel_data=$obj_hostel->mj_smgt_get_hostel_by_id($_REQUEST['hostel_id']);

			}

			?>

		<div class="panel-body">

			<form name="hostel_form" action="" method="post" class="mt-3 form-horizontal" id="hostel_form_fornt">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" name="hostel_id" value="<?php if($edit){ echo $hostel_data->id;}?>"/> 

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Hostel Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!--Card Body div-->   

					<div class="row"><!--Row Div--> 

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="hostel_name" class="form-control col-form-label validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_name;}?>" name="hostel_name">

									<label class="" for="hostel_name"><?php esc_attr_e('Hostel Name','school-mgt');?> <span class="require-field">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="hostel_type" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_type;}?>" name="hostel_type">

									<label class="" for="hostel_type"><?php esc_attr_e('Hostel Type','school-mgt');?> <span class="require-field">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="hostel_address" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_address;}?>" name="hostel_address">

									<label class="" for="hostel_type"><?php esc_attr_e('Hostel Address','school-mgt');?></label>

								</div>

							</div>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="hostel_intake" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_intake;}?>" name="hostel_intake">

									<label class="" for="hostel_intake"><?php esc_attr_e('Intake/Capacity','school-mgt');?></label>

								</div>

							</div>

						</div>

						<?php wp_nonce_field( 'save_hostel_admin_nonce' ); ?>

						<div class="col-md-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="Description" id="Description" maxlength="150" class="textarea_height_47px form-control col-form-label  validate[custom[description_validation]]"><?php if($edit){ echo $hostel_data->Description;}?></textarea>

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="Description"><?php esc_attr_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">     	

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hostel','school-mgt'); }else{ esc_attr_e('Add Hostel','school-mgt');}?>" name="save_hostel" class="save_btn btn btn-success" />

						</div>

					</div>

				</div>

			</form>

		</div>

		<?php

	}

	if($active_tab == 'room_list')

	{
		$user_id=get_current_user_id();

		//------- ROOM LIST FOR STUDENT ---------//
		$own_data=$user_access['own_data'];
		if($own_data == '1')
		{ 
			$retrieve_class = mj_smgt_get_room_data_user_access_right_wise($user_id,$school_obj->role);
		}
		else{
			$tablename='smgt_room';

			$retrieve_class = mj_smgt_get_all_data($tablename);
		}
		
		// $tablename='smgt_room';

		// $retrieve_class = mj_smgt_get_all_data($tablename);

		if(!empty($retrieve_class))

		{

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

			<div class="panel-body">

				<div class="table-responsive">

					<form id="frm-example" name="frm-example" method="post">

						<table id="room_list_fornt" class="display dataTable exam_datatable" cellspacing="0" width="100%">	

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

									<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>

									<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>

									<th><?php esc_attr_e('Room Type','school-mgt');?></th>

									<th><?php esc_attr_e('Remaining	Bed Capacity','school-mgt');?></th>

									<th><?php esc_attr_e('Availability','school-mgt');?></th>

									<th><?php esc_attr_e('Description','school-mgt');?></th>

									<?php

									if($user_access['add']=='1')

									{

										?>

										<th><?php esc_attr_e('View & Assign Room','school-mgt');?></th>

										<?php

									}

										?>

							
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

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<td class="checkbox_width_10px">

												<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">

											</td>

											<?php

										}

										?>

										<td class="user_image width_50px profile_image_prescription padding_left_0">

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" >

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">

												</p>

											</a>

										</td>

										<td><?php echo $retrieved_data->room_unique_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i></td>

										<td><a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="room_view" ><?php echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id);?></a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Hostel Name','school-mgt');?>" ></i></td>

										<td><?php echo get_the_title($retrieved_data->room_category);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Type','school-mgt');?>" ></i></td>

										<td><?php 
												echo $capacity.' ';
												esc_attr_e('Out Of', 'school-mgt');
												echo ' '.$retrieved_data->beds_capacity;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Remaining Bed Capacity','school-mgt');?>" ></i></td>

										<?php 

											$room_cnt =mj_smgt_hostel_room_status_check($retrieved_data->id);

											

											$bed_capacity=(int)$retrieved_data->beds_capacity;

											

											if($room_cnt >= $bed_capacity)

											{

											?> 

												<td><label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

											<?php

											}

											else 

											{?>

												<td><label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

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

											?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->room_description)){ echo $retrieved_data->room_description;}else{ _e('Description','school-mgt');} ?>" ></i>

										</td>

										<?php

										if($user_access['add']=='1')

										{

											?>

											<td>

												<?php

												if($room_cnt >= $bed_capacity)

												{

													echo esc_attr_e('No Bed Available In This Room.','school-mgt');

												}

												else

												{

													// if($user_access['add']=='1')

													// {

														?>

														<button class="btn btn-default assign_room_btn_design"><a href="?dashboard=user&page=hostel&tab=assign_room&action=view_assign_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i> <?php esc_attr_e('Assign Room','school-mgt');?></a></button>

														<?php

													// }

												}

												?>

											</td>

											<?php

										}

											?>

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
																<?php

																if($role_name == "supportstaff" || $role_name == "teacher")

																{
																?>
																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=hostel&tab=assign_bed_list&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-bed"></i><?php esc_attr_e('Assign Bed List','school-mgt');?></a>

																	</li>
																<?php
																}
																

																if($user_access['edit']=='1')

																{

																?>

																	<li class="float_left_width_100 border_bottom_item">

																		<a href="?dashboard=user&page=hostel&tab=add_room&action=edit_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100"><i class="fa fa-edit"></i><?php esc_attr_e('Edit','school-mgt');?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																?>

																	<li class="float_left_width_100 ">

																		<a href="?dashboard=user&page=hostel&tab=room_list&action=delete_room&room_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i><?php esc_attr_e('Delete','school-mgt');?></a>

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

									<button id="delete_selected_room" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_room" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<?php

								}?>

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

					<a href="<?php echo home_url().'?dashboard=user&page=hostel&tab=add_room&action=insert';?>">

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

			<div class="overlay-content">

				<div class="modal-content">

					<div class="view_popup"></div>     

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
						//stateSave: true,
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
	if($active_tab == 'add_room')

	{

		$obj_hostel=new smgt_hostel;

		?>

		<!--Group POP up code -->

		<div class="popup-bg">

			<div class="overlay-content admission_popup">

				<div class="modal-content">

					<div class="category_list">

					</div>     

				</div>

			</div>     

		</div>

		<?php 

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_room')

		{

			$edit=1;

			$room_data=$obj_hostel->mj_smgt_get_room_by_id($_REQUEST['room_id']);

		}

		?>

		<div class="panel-body">

			<form name="room_form" action="" method="post" class="mt-3 form-horizontal" id="room_form">

				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" name="room_id" value="<?php if($edit){ echo $room_data->id;}?>"/> 

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Room Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!--Card Body div-->   

					<div class="row"><!--Row Div--> 

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="room_unique_id" class="form-control col-form-label  validate[required] text-input" type="text" value="<?php if($edit){ echo $room_data->room_unique_id; } else { echo mj_smgt_generate_room_code(); } ?>"  name="room_unique_id" readonly>		

									<label class="col-sm-2 control-label col-form-label text-md-end" for="room_unique_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>

								</div>

							</div>

						</div>

						<div class="col-md-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="hostel_type"><?php esc_attr_e('Select Hostel','school-mgt');?> <span class="require-field">*</span></label>	

							<select name="hostel_id" class="line_height_30px form-control col-form-label  validate[required] width_100" id="hostel_id">

								<option value=""><?php echo esc_attr_e( 'Select Hostel', 'school-mgt' ) ;?></option>

								<?php $hostelval='';

								$hostel_data=$obj_hostel->mj_smgt_get_all_hostel();

								if($edit){  

									$hostelval=$room_data->hostel_id; 

									foreach($hostel_data as $hostel)

									{ ?>

									<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>>

									<?php echo $hostel->hostel_name;?></option> 

								<?php }

								}else

								{

									foreach($hostel_data as $hostel)

									{ ?>

									<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>><?php echo $hostel->hostel_name;?></option> 

								<?php }

								}

								?>

							</select>

						</div>

						<div class="col-md-4 input">

							<label class="ml-1 custom-top-label top" for="hostel_type"><?php esc_attr_e('Room Type','school-mgt');?> <span class="require-field">*</span></label>

							<select class="line_height_30px form-control col-form-label  validate[required] room_category width_100" name="room_category" id="room_category">

								<option value=""><?php esc_html_e('Select Standard','school-mgt');?></option>

								<?php 

								$activity_category=mj_smgt_get_all_category('room_category');

								if(!empty($activity_category))

								{

									if($edit)

									{

										$room_val=$room_data->room_category; 

									}

									else

									{

										$room_val=''; 

									}

									foreach ($activity_category as $retrive_data)

									{ 		 	

									?>

										<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$room_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>

									<?php }

								} 

								?> 

							</select>	

						</div>

						<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">

							<button id="addremove_cat" class="save_btn  sibling_add_remove" model="room_category"><?php esc_attr_e('Add','school-mgt');?></button>		

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin padding_top_15px_res">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="beds_capacity" class="form-control col-form-label  validate[required,custom[onlyNumberSp],maxSize[2],min[1]] text-input" placeholder="<?php esc_html_e('Enter Beds Capacity','school-mgt');?>"  type="text" value="<?php if($edit){ echo $room_data->beds_capacity; } ?>"  name="beds_capacity">

									<label class="" for="Bed Capacity"><?php esc_attr_e('Beds Capacity','school-mgt');?> <span class="require-field">*</span></label> 

								</div>

							</div>

						</div>

						<?php wp_nonce_field( 'save_room_admin_nonce' ); ?>

					

						<div class="col-md-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="room_description" id="room_description" maxlength="150" class="textarea_height_47px form-control col-form-label  validate[custom[description_validation]]"><?php if($edit){ echo $room_data->room_description;}?></textarea>		

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="room_description"><?php esc_attr_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div><!--Row Div--> 

				</div><!--Card Body div-->  

				<!-- Save Btn Start-->

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Room','school-mgt'); }else{ esc_attr_e('Add Room','school-mgt');}?>" name="save_room" class="save_btn btn btn-success" />

						</div>

					</div>

				</div>

				<!-- Save Btn End-->

			</form>

		</div>

		<?php

	}

	if($active_tab == 'bed_list')

	{
		$user_id=get_current_user_id();

		//------- BED LIST FOR STUDENT ---------//
		$own_data=$user_access['own_data'];
		if($own_data == '1')
		{ 
			$retrieve_class = mj_smgt_get_bed_data_user_access_right_wise($user_id,$school_obj->role);
		}
		else{
			$tablename='smgt_beds';

			$retrieve_class = mj_smgt_get_all_data($tablename);
		}
		

		if(!empty($retrieve_class))

		{

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

			<div class="panel-body">

				<div class="table-responsive">

					<form id="frm-example" name="frm-example" method="post">

						<table id="bed_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">

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

								$i=0;

								foreach ($retrieve_class as $retrieved_data)

								{ 	
									$student_id = $obj_hostel->mj_smgt_get_assign_bed_student_by_id($retrieved_data->id);
									$hostel_id = $obj_hostel->mj_smgt_get_hostel_id_by_room_id($retrieved_data->room_id);
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

										<?php

										if($role_name == "supportstaff")

										{

											?>

											<td class="checkbox_width_10px">

												<input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>">

											</td>

											<?php

										}

										?>

										<td class="user_image width_50px profile_image_prescription padding_left_0">

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >

												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/hostel.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">

												</p>

											</a>

										</td>

										<td>

											<a href="#" class="view_details_popup" id="<?php echo $retrieved_data->id;?>" type="beds_view" >

												<?php echo $retrieved_data->bed_unique_id;?>

											</a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Unique ID','school-mgt');?>" ></i>

										</td>

										<td><?php echo mj_smgt_get_room_unique_id_by_id($retrieved_data->room_id);?>(<?php echo mj_smgt_get_hostel_name_by_id($hostel_id);?>) <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Room Unique ID','school-mgt');?>" ></i></td>
										
										<td>
											<?php if($student_id){ echo mj_smgt_student_display_name_with_roll($student_id->student_id);}else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Occupied Student','school-mgt');?>" ></i>
										</td>

										<td>

											<?php if($retrieved_data->bed_charge){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->bed_charge,2,'.','')); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Bed Charge','school-mgt');?>" ></i>

										</td>

										<?php 

										if($retrieved_data->bed_status == '0')

										{	?>

											<td><label class="hoste-lbl2"><?php esc_attr_e('Available','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

											<?php 

										}

										else 

										{?>

											<td><label class="hostel-lbl"><?php esc_attr_e('Occupied','school-mgt');?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Availability','school-mgt');?>" ></i></td>

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

										<?php

										

											?>

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

																if($user_access['edit']=='1')

																{

																?>

																	<li class="float_left_width_100 border_bottom_item">

																		<a href="?dashboard=user&page=hostel&tab=add_bed&action=edit_bed&bed_id=<?php echo $retrieved_data->id;?>"class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_attr_e('Edit','school-mgt');?></a>

																	</li>

																	<?php

																}

																if($user_access['delete']=='1')

																{

																?>

																	<li class="float_left_width_100">

																		<a href="?dashboard=user&page=hostel&tab=bed_list&action=delete_bed&bed_id=<?php echo $retrieved_data->id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>

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

									<button id="delete_selected_bed" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_bed" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

									<?php

								}?>

							</div>

							<?php

						}

						?>

					</form>

				</div>

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

					<a href="<?php echo home_url().'?dashboard=user&page=hostel&tab=add_bed&action=insert';?>">

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

	if($active_tab == 'add_bed')

	{

		$obj_hostel=new smgt_hostel;

		?>

		<!--Group POP up code -->

		<div class="popup-bg">

			<div class="overlay-content admission_popup">

				<div class="modal-content">

					<div class="category_list">

					</div>     

				</div>

			</div>     

		</div>

		<?php 

		$edit=0;

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_bed')

		{

			$edit=1;

			$bed_data=$obj_hostel->mj_smgt_get_bed_by_id($_REQUEST['bed_id']);

		}

		?>

		<div class="panel-body">

			<form name="bed_form" action="" method="post" class="mt-3 form-horizontal" id="bed_form">

			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>

				<input type="hidden" name="action" value="<?php echo $action;?>">

				<input type="hidden" name="bed_id" value="<?php if($edit){ echo $bed_data->id;}?>"/>  

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Beds Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!--Card Body div-->   

					<div class="row"><!--Row Div--> 



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="bed_unique_id" class="form-control col-form-label  validate[required] text-input" type="text" value="<?php if($edit){ echo $bed_data->bed_unique_id; } else { echo mj_smgt_generate_bed_code(); } ?>"  name="bed_unique_id" readonly>		

									<label class="" for="bed_unique_id"><?php esc_attr_e('Bed Unique ID','school-mgt');?> <span class="require-field">*</span></label>

								</div>

							</div>

						</div>



						<div class="col-md-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="room_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>							

							<select name="room_id" class="line_height_30px form-control col-form-label validate[required] width_100" id="room_id">

								<option value=""><?php echo esc_attr_e( 'Select Room Unique ID', 'school-mgt' ) ;?></option>

								<?php $roomval='';

								$room_data=$obj_hostel->mj_smgt_get_all_room();

								if($edit){  

									$roomval=$bed_data->room_id; 

									foreach($room_data as $room)

									{ ?>

									<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>>

									<?php echo $room->room_unique_id;?></option> 

								<?php }

								}else

								{

									foreach($room_data as $room)

									{ ?>

									<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>><?php echo $room->room_unique_id;?></option> 

								<?php }

								}

								?>

							</select>

						</div>

						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">

							<div class="form-group input">

								<div class="col-md-12 form-control">

									<input id="bed_charge" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="number" value="<?php if($edit){ echo $bed_data->bed_charge;}?>" name="bed_charge">

									<label class="" for="bed_charge"><?php esc_attr_e('Charge','school-mgt');?> (<?php echo mj_smgt_get_currency_symbol(); ?>)</label>

								</div>

							</div>

						</div>

						<?php wp_nonce_field( 'save_bed_admin_nonce' ); ?>

					

						<div class="col-md-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="bed_description" id="bed_description" maxlength="150" class="textarea_height_47px form-control col-form-label  validate[custom[description_validation]]"><?php if($edit){ echo $bed_data->bed_description;}?></textarea>		

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="bed_description"><?php esc_attr_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6"> 	

							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Bed','school-mgt'); }else{ esc_attr_e('Add Bed','school-mgt');}?>" name="save_bed" class="btn btn-success save_btn" />

						</div>

					</div>

				</div>

			</form>

		</div>

		<?php

	}

	if($active_tab == 'assign_room')

	{

		$obj_hostel=new smgt_hostel;

		

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_assign_room')

		{

			$room_id=$_REQUEST['room_id'];

		}

		$bed_data=$obj_hostel->mj_smgt_get_all_bed_by_room_id($room_id);

		$hostel_id=$obj_hostel->mj_smgt_get_hostel_id_by_room_id($room_id);



		$exlude_id = mj_smgt_approve_student_list();

		$student_all= get_users(array('role'=>'student','exclude'=>$exlude_id));

		

		foreach($student_all as $aa)

		{

			$student_id[]=$aa->ID;

		}

		//--------- GET ASSIGNED STUDENT DATA -------//

		$assign_data=mj_smgt_all_assign_student_data();

		

		if(!empty($assign_data))

		{

			foreach($assign_data as $bb)

			{

				$student_new_id[]=$bb->student_id;

			} 

			$Student_result=array_diff($student_id,$student_new_id);

		}

		else

		{

			$Student_result=$student_id;

		}

		?>

		<div class="panel-body margin_top_20px margin_top_15px_rs">

			<?php

			$i=0;

			if(!empty($bed_data))

			{

				foreach($bed_data as $data)

				{

					$student_data =mj_smgt_student_assign_bed_data($data->id);

				?>

				<form name="bed_form" action="" method="post" class="mt-3 form-horizontal" id="assign_bed_form">

						<input type="hidden" name="room_id_new[]" value="<?php echo $data->room_id;?>">

						<input type="hidden" name="bed_id[]" value="<?php echo $data->id;?>">

						<input type="hidden" name="hostel_id" value="<?php echo $hostel_id;?>">

						<div class="header">	

							<h3 class="first_hed"><?php esc_html_e('Assign Beds Information','school-mgt');?></h3>

						</div>

						<div class="form-body user_form mt-2" id="main_assign_room"> <!--Card Body div-->  

							<div class="row">

								<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

									<div class="form-group input">

										<div class="col-md-12 form-control">

											<input id="bed_unique_id_<?php echo $i;?>" class="form-control col-form-label  validate[required]" type="text" value="<?php echo $data->bed_unique_id;;?>" name="bed_unique_id[]" readonly>

											<label class="" for="bed_unique_id"><?php esc_attr_e( 'Bed Unique ID', 'school-mgt' ) ;?><span class="require-field"></span></label>

										</div>

									</div>

								</div>



								<?php

									if(!empty($student_data))

									{

										$new_class='';

									}

									else

									{

										$new_class='new_class';

									}

								?>



								<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 input">

									<select name="student_id[]" id="students_list_<?php echo $i ;?>" data-index="<?php echo $i;?>" class="line_height_30px form-control col-form-label validate[required] student_check <?php echo $new_class; ?> students_list_<?php echo $i ;?>">

										<?php

										if(!empty($student_data))

										{

											$roll_no = get_user_meta( $student_data->student_id, 'roll_id' , true );

											$class_id = get_user_meta( $student_data->student_id, 'class_name' , true );

										?>

											<option value="<?php echo $student_data->student_id; ?>" ><?php echo mj_smgt_get_display_name($student_data->student_id).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>

											<?php 

										}

										else

										{?>

											<option value="0"><?php  esc_attr_e( 'Select Student', 'school-mgt' );?></option>

											<?php foreach($Student_result as $student)

											{

												$roll_no = get_user_meta( $student, 'roll_id' , true );

												$class_id = get_user_meta( $student, 'class_name' , true );

											?>

												<option value="<?php echo $student; ?>"><?php echo mj_smgt_get_display_name($student).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>

											<?php 

											}

										}

										?>

									</select>

								</div>

								

								<?php

								if(!empty($student_data))

								{

								?>

									<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

										<div class="form-group input">

											<div class="col-md-12 form-control">

												<input id="assign_date_<?php echo $i ;?>"  value="<?php  echo mj_smgt_getdate_in_input_box($student_data->assign_date); ?>" class="form-control col-form-label validate[required] text-input margin_top_10_res" type="text" name="assign_date[]" readonly>

											</div>

										</div>

									</div>

								<?php

								}

								else

								{ ?>

									<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

										<div class="form-group input ">

											<div class="col-md-12 col-sm-12 col-xs-12 form-control assigndate_<?php echo $i;?>" id="assigndate_<?php echo $i ;?>" name="assigndate" >

												<input id="assign_date_<?php echo $i;?>" placeholder="<?php esc_attr_e( 'Enter Date', 'school-mgt' );?>" class="datepicker form-control col-form-label validate[required] text-input margin_top_10_res" type="text" value="<?php echo date('Y-m-d');?>" name="assign_date[]">

											</div>

										</div>

									</div>

								 <?php

								}

								if($student_data)

								{

									?>

									<div class="col-md-3 col-sm-3 col-xs-12 input">

										<label class="col-md-2 col-sm-2 col-xs-12 control-label col-form-label occupied occupied_available_btn" for="available" ><?php esc_attr_e( 'Occupied', 'school-mgt' );?></label>

									</div>



									<div class="col-md-3 col-sm-3 col-xs-12 input">

										<a href="?dashboard=user&page=hostel&tab=room_list&action=delete_assign_bed&room_id=<?php echo $data->room_id;?>&bed_id=<?php echo $data->id;?>&student_id=<?php echo $student_data->student_id;?>" class="btn btn-danger delete_btn" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this bed?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>

									</div>



									<?php

								}

								else

								{ ?>

									<div class="col-md-3 col-sm-3 col-xs-12 input">

										<label class="col-md-2 col-sm-2 col-xs-12 control-label col-form-label available occupied_available_btn m-0" for="available" ><?php esc_attr_e( 'Available', 'school-mgt' );?></label>

									</div>

									<?php

								}

								?>

							</div>

						</div>

					<?php

					$i++;

				}

				?>

				<?php wp_nonce_field( 'save_assign_room_admin_nonce' ); ?>

				<div class="form-body user_form">

					<div class="row">

						<div class="col-sm-6">  	

							<input type="submit" id="Assign_bed" value="<?php esc_attr_e('Assign Room','school-mgt');?>" name="assign_room" class="btn btn-success save_btn" />

						</div>

					</div>

				</div>

			</form>

			<?php

			}

			else

			{ ?>

				<h4 class="require-field"><?php esc_attr_e('No Bed Available','school-mgt');?></h4>

			<?php

			}

			?>

		</div>

		<?php

	}

	?>

</div> <!-- End panel-body -->