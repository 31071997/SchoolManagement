<script type="text/javascript">

jQuery(document).ready(function($)

{

	"use strict";	

    var table =  jQuery('#meeting_list').DataTable({

		"initComplete": function( settings, json ) {
				$(".print-button").css({"margin-top": "-5%"});
			},
			//stateSave: true,
	responsive: true,

	 'order': [2, 'asc'],

	 "dom": 'lifrtp',

	 "aoColumns":[

	 				  {"bSortable": false},

	                  {"bSortable": true},

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

    $('#checkbox-select-all').on('click', function(){

      var rows = table.rows({ 'search': 'applied' }).nodes();

      $('input[type="checkbox"]', rows).prop('checked', this.checked);

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



		else{



				var alert_msg=confirm("<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>");



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







	var table =  jQuery('#past_participle_list').DataTable({



	responsive: true,



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







});



</script>



<?php 



require_once SMS_PLUGIN_DIR. '/lib/vendor/autoload.php';



$obj_virtual_classroom = new mj_smgt_virtual_classroom;



$active_tab = isset($_GET['tab'])?$_GET['tab']:'meeting_list';

$page_name = $_REQUEST ['page']; 

$user_access=mj_smgt_get_management_access_right_array($page_name);

// EDIT MEETING IN ZOOM



if(isset($_POST['edit_meeting']))



{



	$nonce = $_POST['_wpnonce'];



	if ( wp_verify_nonce( $nonce, 'edit_meeting_admin_nonce' ) )



	{



		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);



		if($result)



		{



			wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=2');



		}		



	}



}



// DELETE STUDENT IN ZOOM



if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')

{



	$result= $obj_virtual_classroom->mj_smgt_delete_meeting_in_zoom($_REQUEST['meeting_id']);

	if($result)



	{



		wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=3');



	}



}



/*Delete selected Subject*/



if(isset($_REQUEST['delete_selected']))



{		



	if(!empty($_REQUEST['id']))



	{



		foreach($_REQUEST['id'] as $meeting_id)



		{



			$result= $obj_virtual_classroom->mj_smgt_delete_meeting_in_zoom($meeting_id);



		}



	}



	if($result)



	{



		wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=3');



	}



}



?>



<!-- POP up code -->



<div class="popup-bg">



    <div class="overlay-content">



	    <div class="modal-content">



		    <div class="view_meeting_detail_popup">



		    </div>

			<div class="category_list">
			</div>

		</div>



	</div>



</div>



<!-- End POP-UP Code -->







<div class="page-inner">







	<div id="" class="class_list main_list_margin_5px">



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



			case '4':



				$message_string = esc_attr__('Your Access Token Is Updated.','school-mgt');



				break;



			case '5':



				$message_string = esc_attr__('Something Wrong.','school-mgt');



				break;



			case '6':



				$message_string = esc_attr__('First Start Your Virtual Class.','school-mgt');



				break;



		}



		



		if($message)



		{ ?>



		<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">



			<p><?php echo $message_string;?></p>



			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>



		</div>



		<?php 



		} 



		?>



		<div class="panel-white">



			<div class="panel-body">		



			    <?php



				if($active_tab == 'meeting_list')



				{	
					if(get_option('smgt_enable_video_popup_show') == 'yes')
					{
					?>
						<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/wJ7D1I8zOao?si=PbzhjGNMS-cVdTFr";?>" title="Zoom Meeting Setup">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
						</a>
					<?php
					}
					$meeting_list_data = $obj_virtual_classroom->mj_smgt_get_all_meeting_data_in_zoom();

					if(!empty($meeting_list_data))

					{

						?>	



						<div class="panel-body">



							<form id="frm-example" name="frm-example" method="post">



								<div class="table-responsive">



									<table id="meeting_list" class="display datatable" cellspacing="0" width="100%">

										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">

											<tr>

												<th class="padding_0"><input type="checkbox" class="smgt_sub_chk select_all" id="select_all"></th>

												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>

												<th><?php echo esc_attr_e( 'Subject Name', 'school-mgt' ) ;?></th>

												<th> <?php esc_attr_e( 'Teacher Name', 'school-mgt' ) ;?></th>

												<th> <?php echo esc_attr_e( 'Day', 'school-mgt' ) ;?></th>

												<th> <?php echo esc_attr_e( 'Created By', 'school-mgt' ) ;?></th>

												<th> <?php esc_attr_e( 'Start To End Date', 'school-mgt' ) ;?></th>

												<th> <?php echo esc_attr_e( 'Start To End Time', 'school-mgt' ) ;?></th>

												<th> <?php echo esc_attr_e( 'Agenda', 'school-mgt' ) ;?></th>

												<th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>

											</tr>

										</thead>

										<tbody>



										<?php 



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



												<td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->meeting_id;?>"></td>



												<td class="user_image width_50px profile_image_prescription padding_left_0">

													<a href="" class="show-popup" meeting_id="<?php echo $retrieved_data->meeting_id; ?>"> 

														<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	

															<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Virtual_class.png"?>" alt="" class="massage_image center">

														</p>

													</a>

												</td>



												<td><?php $subid=$retrieved_data->subject_id;



													echo mj_smgt_get_single_subject_name($subid);



												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Subject Name','school-mgt');?>"></i></td>



												



												<td><?php if(!empty($retrieved_data->teacher_id)){ echo mj_smgt_get_teacher($retrieved_data->teacher_id); }else{ echo "N/A"; }



												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>



												<td><?php echo $day; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>



												<td><?php echo mj_smgt_get_display_name($retrieved_data->created_by); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Created By','school-mgt');?>"></i></td>



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



																		<a href="" class="float_left_width_100 show-popup" meeting_id="<?php echo $retrieved_data->meeting_id; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></a> 



																	</li>



																	<li class="float_left_width_100 ">



																		<a href="<?php echo $retrieved_data->meeting_start_link;?>" class="float_left_width_100" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i> <?php esc_attr_e('Start Virtual Class','school-mgt');?> </a>



																	</li>



																	<li class="float_left_width_100 ">



																		<a href="?page=smgt_virtual_classroom&tab=view_past_participle_list&action=view&meeting_uuid=<?php echo $retrieved_data->uuid;?>" class="float_left_width_100"><i class="fa fa-eye" aria-hidden="true"></i> <?php esc_attr_e('View Participant List','school-mgt');?> </a>



																	</li>



																	<li class="float_left_width_100 border_bottom_menu">



																		<a href="?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100"><i class="fa fa-edit"></i> <?php esc_attr_e('Edit','school-mgt');?> </a>



																	</li>



																	<li class="float_left_width_100 ">



																		<a href="?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id=<?php echo $retrieved_data->meeting_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i> <?php esc_attr_e('Delete','school-mgt');?></a>



																	</li>



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



								</div>



								<div class="print-button pull-left">



									<button class="btn-sms-color button_reload">



										<input type="checkbox" name="id[]" class="select_all smgt_sub_chk" value="<?php echo esc_attr($retrieved_data->ID); ?>" style="margin-top: 0px;">



										<label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>



									</button>



									<button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>



								</div>



								



							</form>



						</div>



						<?php 

					}

					else

					{

						if($role == 'admin' || $user_access['add']=='1')

						{

							?>

							<div class="no_data_list_div pt-2"> 

								<a href="<?php echo admin_url().'admin.php?page=smgt_route&tab=addroute';?>">

									<img class="col-md-12 width_100px rtl_float_remove" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

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

				



				if($active_tab == 'edit_meeting')



				{



					require_once SMS_PLUGIN_DIR. '/admin/includes/virtual_classroom/edit_meeting.php';



				}



				elseif($active_tab == 'view_past_participle_list')



				{



					require_once SMS_PLUGIN_DIR. '/admin/includes/virtual_classroom/view_past_participle_list.php';



				}



				?>



		 	</div>



		</div>



	</div>



</div>



<?php ?>