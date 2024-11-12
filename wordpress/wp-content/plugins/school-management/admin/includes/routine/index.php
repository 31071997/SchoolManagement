<?php

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

	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('schedule');

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

			if ('schedule' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))

			{

				if($user_access_edit=='0')

				{

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}

			}

			if ('schedule' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))

			{

				if($user_access_delete=='0')

				{

					mj_smgt_access_right_page_not_access_message_admin_side();

					die;

				}

			}

			if ('schedule' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))

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

		$('#rout_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#import_class_csv').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

		$('#export_class_csv').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

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





		$("#start_date_new").datepicker(

		{

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

			minDate:0,
			changeMonth: true,
			changeYear: true,
	        onSelect: function (selected) {

	            var dt = new Date(selected);

	            dt.setDate(dt.getDate() + 0);

	            $(".end_date").datepicker("option", "minDate", dt);

	        }

	    });

	    $("#end_date_new").datepicker(

		{

			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",

		   minDate:0,
		   changeMonth: true,
			changeYear: true,
	        onSelect: function (selected) {

	            var dt = new Date(selected);

	            dt.setDate(dt.getDate() - 0);

	            $(".start_date").datepicker("option", "maxDate", dt);

	        }

	    });

	});

</script>

<?php

$obj_route = new Class_routine();

$obj_virtual_classroom = new mj_smgt_virtual_classroom();

//---------- save class Routine  ------------//

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

		// $start_am_pm=$start_time_data[2];

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
				foreach ($teacher_id as $teacher)
				{
					foreach ($_POST['weekday'] as $week_days)
					{
							$route_data[]=array('subject_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_id']),

							'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_id']),

							'section_name'=>mj_smgt_onlyNumberSp_validation($_POST['class_section']),

							'teacher_id'=>$teacher,

							'start_time'=>$start_time_new,

							'end_time'=>$end_time_new,

							'weekday'=>mj_smgt_onlyNumberSp_validation($week_days),

							'multiple_teacher'=>'yes',

						);
					}
				}

			}
			if($_REQUEST['action']=='edit') //------- Edit class Routine --------//
			{
				$route_id=array('route_id'=>$_REQUEST['route_id']);

				$obj_route->mj_smgt_update_route($route_data,$route_id);

				wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=2');

				exit;

			}

			else //------- record Insert ---------//

			{

				/* Setup Wizard */

				$wizard = smgt_setup_wizard_steps_updates('step5_class_time_table');


				foreach($route_data as $route)
				{
					$retuen_val = $obj_route->mj_smgt_is_route_exist($route);
				}


				if($retuen_val == 'success')

				{

					//Create Virtual Class //

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



								$start_time=MJ_start_time_convert($_POST['start_time']);

								$end_time=MJ_end_time_convert($_POST['end_time']);



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

						wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=1');

						exit;

					}

				}

				elseif($retuen_val == 'duplicate')

				{

					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=4');

					exit;

				}

				elseif($retuen_val == 'teacher_duplicate')

				{

					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=5');

					exit;

				}

			}
		}

		else

		{

			wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=6');

			exit;

		}

	}

}



//--------------- SAVE IMPORT CLASS ROUTE DATA --------------------//

if(isset($_POST['save_import_csv']))
{
	if(isset($_FILES['csv_file']))
	{

		$errors= array();

		$file_name = $_FILES['csv_file']['name'];

		$file_size =$_FILES['csv_file']['size'];

		$file_tmp =$_FILES['csv_file']['tmp_name'];

		$file_type=$_FILES['csv_file']['type'];

		$value = explode(".", $_FILES['csv_file']['name']);

		$file_ext = strtolower(array_pop($value));

		$extensions = array("csv");

		$upload_dir = wp_upload_dir();

		if(in_array($file_ext,$extensions )=== false)
		{

			$err=esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');

			$errors[]=$err;

			wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=import_class_route&message=7');

		}

		//------------ Check File Size ------------//

		if($file_size > 2097152)
		{

			$errors[]='File size limit 2 MB';

			wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=import_class_route&message=8');

		}
		if(empty($errors)==true)
		{

			$rows = array_map('str_getcsv', file($file_tmp));
			$header = array_map('strtolower',array_shift($rows));
			$csv = array();

			foreach ($rows as $row)

			{

				$csv = array_combine($header, $row);

				global $wpdb;
				$smgt_time_table = $wpdb->prefix. 'smgt_time_table';

				$subject_code = $csv['subject code'];

				$table_subject = $wpdb->prefix . "subject";

				$subject_data = $wpdb->get_row("SELECT * FROM $table_subject where subject_code=$subject_code");


				if(isset($_POST['class_id']))

					$routedata['class_id']=(int)$_POST['class_id'];

				if(isset($_POST['class_section']))

					$routedata['section_name']=(int)$_POST['class_section'];

				if(isset($csv['start time']))

					$routedata['start_time']=$csv['start time'];

				if(isset($csv['end time']))

					$routedata['end_time']=$csv['end time'];

				if(isset($csv['weekday']))

					$routedata['weekday']=(int)$csv['weekday'];

				$teacher_data=get_user_by_email($csv['username'] );

				$teacher_id = $teacher_data->ID;

				$routedata['teacher_id'] = $teacher_id;

				$routedata['subject_id'] = (int) $subject_data->subid;

				$all_class_route = $wpdb->get_results("SELECT * FROM $smgt_time_table");

				if($all_class_route){

					foreach ($all_class_route as $route_data)

					{

						$start_time[]=$route_data->start_time;

						$end_time[]=$route_data->end_time;

						$class_id[]=$route_data->class_id;

					}
					if((in_array($routedata['start_time'], $start_time)) && (in_array($routedata['end_time'], $end_time)))
					{
						wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=4');
						exit;
					}
				}

				if($_POST['class_id'] != $subject_data->class_id)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=11');
					exit;
				}
				else
				{
					$insert = $wpdb->insert( $smgt_time_table, $routedata);

					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=10');
					exit;
				}



			}

		}

	}

}

//--------- virtual class meeting create  -------//

if(isset($_POST['create_meeting']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'create_meeting_admin_nonce' ) )
	{
		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);

		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=1');
		}
	}

}

//-------- delete class routine ---------//

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')

{

	$tablenm = "smgt_time_table";

	$result=mj_smgt_delete_route($tablenm,$_REQUEST['route_id']);

	if($result)

	{

		wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=3');

		exit;

	}

}

if(isset($_REQUEST['action']) && $_REQUEST['action']=="routine_export_csv")
{
	global $wpdb;

	$table_name_class = $wpdb->prefix . "smgt_time_table";

	$class_id = $_REQUEST['class_id'];

	$section_name = $_REQUEST['class_section'];



	if($_REQUEST['class_id'] != "" && $_REQUEST['class_section'] == "remove" || $_REQUEST['class_section'] == "") //------- Only Class Select -------//

	{

		$class_route_list = $wpdb->get_results("SELECT * FROM $table_name_class where class_id = $class_id and section_name=0");

	}

	else

	{

		$class_route_list = $wpdb->get_results("SELECT * FROM $table_name_class where class_id = $class_id and section_name = $section_name ");

	}



	if(!empty($class_route_list))

	{
		$header = array();

		$header[] = 'Class Name';

		$header[] = 'Section Name';

		$header[] = 'Subject Code';

		$header[] = 'Subject Name';

		$header[] = 'username';

		$header[] = 'Teacher Name';

		$header[] = 'Start Time';

		$header[] = 'End Time';

		$header[] = 'Weekday';

		$filename='Reports/export_class_route.csv';

		$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");

		fputcsv($fh, $header);

		foreach($class_route_list as $retrive_data)

		{

			$row = array();

			$classname=mj_smgt_get_class_name($retrive_data->class_id);

			$table_subject = $wpdb->prefix . "subject";



			if($retrive_data->section_name != "0")

			{

				$section_name_new = mj_smgt_get_section_name($retrive_data->section_name);

			}

			else

			{

				$section_name_new = "No Section";

			}

			$sub_name = mj_smgt_get_single_subject_name($retrive_data->subject_id);

			$teacher_first_name = get_user_meta($retrive_data->teacher_id,'first_name',true);

			$teacher_last_name = get_user_meta($retrive_data->teacher_id,'last_name',true);

			$teacher_name = $teacher_first_name .' ' .$teacher_last_name;



			$row[] = $classname;

			$row[] = $section_name_new;

			$row[] = mj_smgt_get_single_subject_code($retrive_data->subject_id);

			$row[] = $sub_name;



			$student_data=get_userdata($retrive_data->teacher_id);

			$email=$student_data->user_email;

			$row[] = $email;



			$row[] = $teacher_name;

			$row[] = $retrive_data->start_time;

			$row[] = $retrive_data->end_time;

			$row[] = $retrive_data->weekday;

			//var_dump($row);



			fputcsv($fh, $row);

		}

		//die;

		fclose($fh);



		//download csv file.

		ob_clean();

		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_class_route.csv';//file location



		$mime = 'text/plain';

		header('Content-Type:application/force-download');

		header('Pragma: public');       // required

		header('Expires: 0');           // no cache

		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');

		header('Cache-Control: private',false);

		header('Content-Type: '.$mime);

		header('Content-Disposition: attachment; filename="'.basename($file).'"');

		header('Content-Transfer-Encoding: binary');

		//header('Content-Length: '.filesize($file_name));      // provide file size

		header('Connection: close');

		readfile($file);

		exit;

	}

	else

	{

		wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=9');

	}
}
?>

<?php $active_tab = isset($_GET['tab'])?$_GET['tab']:'route_list';	?>

<div class="page-inner"><!------- page inner --------->

	<div id="" class="grade_page main_list_margin_15px">

		<?php

		//-------- class routine messages ---------//

		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';

		switch($message)

		{

			case '1':

				$message_string = esc_attr__('Routine Added Successfully.','school-mgt');

				break;

			case '2':

				$message_string = esc_attr__('Routine Updated Successfully.','school-mgt');

				break;

			case '3':

				$message_string = esc_attr__('Routine Deleted Successfully.','school-mgt');

				break;

			case '4':

				$message_string = esc_attr__('Routine Alredy Added For This Time Period.Please Try Again.','school-mgt');

				break;

			case '5':

				$message_string = esc_attr__('Teacher Is Not Available.','school-mgt');

				break;

			case '6':

				$message_string = esc_attr__('End Time should be greater than Start Time.','school-mgt');

				break;



			case '7':

				$message_string = esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');

				break;

			case '8':

				$message_string = esc_attr__('File size limit 2 MB.','school-mgt');

				break;

			case '9':

				$message_string = esc_attr__('Records not found.','school-mgt');

				break;

			case '10':

				$message_string = esc_attr__('CSV Imported Successfully.','school-mgt');

				break;

			case '11':

				$message_string = esc_attr__('Subject Not Found For This Class','school-mgt');

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

		} ?>

		<div class="panel-white"><!-------- penal White ------->

			<div class="panel-body"><!-------- penal Body ------->

				<div class=" class_list">

					<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">

						<li class="<?php if($active_tab=='route_list'){?>active<?php }?>">

							<a href="?page=smgt_route&tab=route_list" class="padding_left_0 tab <?php echo $active_tab == 'route_list' ? 'active' : ''; ?>">

							<?php esc_html_e('Route List', 'school-mgt'); ?></a>

						</li>

						<li class="<?php if($active_tab=='teacher_timetable'){?>active<?php }?>">

							<a href="?page=smgt_route&tab=teacher_timetable" class="padding_left_0 tab <?php echo $active_tab == 'teacher_timetable' ? 'active' : ''; ?>">

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

						elseif($page_name == 'smgt_route' && $active_tab == 'addroute')

						{ ?>

							<li class="<?php if($active_tab=='addroute'){?>active<?php }?>">

								<a href="?page=smgt_library&tab=addbook" class="padding_left_0 tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">

								<?php echo esc_attr__('Add Class Time Table', 'school-mgt'); ?></a>

							</li>

							<?php

						}

						?>

						<!-- <li class="<?php if($active_tab=='import_class_route'){?>active<?php }?>">

							<a href="?page=smgt_route&tab=import_class_route" class="padding_left_0 tab <?php echo $active_tab == 'import_class_route' ? 'active' : ''; ?>">

							<?php esc_html_e('Import Class Route', 'school-mgt'); ?></a>

						</li>  -->

					</ul>

					<?php

					if($active_tab == 'route_list')

					{

						?>

   						<div class="panel-white margin_top_20px"> <!-------- penal White ------->

    						<div class="panel-body"><!-------- penal Body ------->
								<div class="popup-bg">
									<div class="overlay-content max_height_overflow">
										<div class="modal-content">
											<div class="category_list"></div>
										</div>
									</div>
								</div>

        						<div id="accordion" class="panel-group accordion accordion-flush padding_top_15px_res" id="accordionFlushExample" aria-multiselectable="true" role="tablist">

									<?php

									$retrieve_class = mj_smgt_get_all_data('smgt_class');

									$i=0;

									if(!empty($retrieve_class))

									{

										foreach($retrieve_class as $class)

										{

											if(!empty($class))

											{
												?>

												<div class="mt-1 accordion-item class_border_div">

													<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">
														<button class="accordion-button class_route_list collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">
															<div class="col-md-10 col-7 route_rtl_list">
																<span class="Title_font_weight"><?php echo esc_attr__('Class', 'school-mgt').":"; ?>&nbsp;</span><?php echo $class->class_name;?>
															</div>
															<div class="col-md-2 col-5 row justify-content-end smt_view_result">
																<div class="col-md-5 width_50">
																	<a href="?page=smgt_route&tab=route_list&action=routine_export_csv&class_id=<?php echo $class->class_id;?>&class_section=<?php echo $class->class_section;?>" title="<?php esc_html_e('Export CSV','school-mgt');?>" type="submit" data-toggle="tooltip" class="float_right rootine_export_import_button padding_0" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></a>
																</div>
																<div class="col-md-4 width_50 rtl_margin_left_20px exam_result_pdf_margin" style="margin-right:22px;">
																	<a href="#" title="<?php esc_html_e('Import CSV','school-mgt');?>" type="submit" data-toggle="tooltip" class_id="<?php echo $class->class_id;?>" section_id="<?php echo $class->class_section;?>" class="float_right routine_import_csv rootine_export_import_button padding_0" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></a>
																</div>
															</div>
														</button>
													</h4>

													<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionFlushExample">

														<div class="panel-body">

															<table class="table table-bordered">

																<?php

																$sectionid=0;

																foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)

																{

																	?>

																	<tr>

																		<th width="100"><?php echo $dayname;?></th>

																		<td>

																			<?php

																			$period = $obj_route->mj_smgt_get_periad($class->class_id,$sectionid,$daykey);


																			if(!empty($period))

																				foreach($period as $period_data)
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

																							$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show_virtual_popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																						}

																						else

																						{

																							$create_meeting = '';

																						}



																						if(!empty($meeting_data))

																						{

																							$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																							$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

																							$meeting_statrt_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Virtual Class Start','school-mgt').'</a></li>';

																						}

																						else

																						{

																							$update_meeting = '';

																							$delete_meeting = '';

																							$meeting_statrt_link = '';

																						}

																					}

																					//echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';

																					echo '</span><span class="caret"></span></button>';

																					echo '<ul role="menu" class="pt-2 dropdown-menu">

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit Route','school-mgt').'</a></li>

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Route','school-mgt').'</a></li>'.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'

																						</ul>';

																					echo '</div>';

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

											$create_meeting = '';

											$update_meeting = '';

											$delete_meeting  = '';

											$meeting_statrt_link  = '';

											$sectionname="";

											$sectionid="";

											$class_sectionsdata=mj_smgt_get_class_sections($class->class_id);

											if(!empty($class_sectionsdata))

											{

												foreach($class_sectionsdata as $section)

												{

													$i++;

													$sectionname=$section->section_name;

													$sectionid=$section->id;

													?>

													<div class="mt-1 accordion-item class_border_div">

														<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">

															<button class="accordion-button class_route_list collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_<?php echo $i;?>" aria-controls="flush-collapse_<?php echo $i;?>">
																<div class="col-md-10 col-7 route_rtl_list">
																	<span class="Title_font_weight"><?php echo esc_attr__('Class', 'school-mgt').':'; ?>&nbsp;</span><?php echo smgt_get_class_section_name_wise($section->class_id,$sectionid); ?> &nbsp;&nbsp;&nbsp;&nbsp;
																</div>
																<div class="col-md-2 col-5 row justify-content-end smt_view_result">
																	<div class="col-md-5 width_50">
																		<a href="?page=smgt_route&tab=route_list&action=routine_export_csv&class_id=<?php echo $class->class_id;?>&class_section=<?php echo $sectionid;?>" title="<?php esc_html_e('Export CSV','school-mgt');?>" type="submit" data-toggle="tooltip"  class="float_right rootine_export_import_button padding_0" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></a>
																	</div>
																	<div class="col-md-4 width_50 rtl_margin_left_20px exam_result_pdf_margin" style="margin-right:22px;">
																		<a href="#" title="<?php esc_html_e('Import CSV','school-mgt');?>" type="submit" data-toggle="tooltip" class_id="<?php echo $class->class_id;?>" section_id="<?php echo $sectionid;?>" class="float_right routine_import_csv rootine_export_import_button padding_0" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></a>
																	</div>
																</div>
															</button>

														</h4>

					   									<div id="flush-collapse_<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" data-bs-parent="#accordionFlushExample" >

					  										<div class="panel-body">

					   											<table class="table table-bordered">

																	<?php

																	foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)

																	{

																		?>

																		<tr>

																			<th width="100"><?php echo $dayname;?></th>

																			<td>

																				<?php

																				$period = $obj_route->mj_smgt_get_periad($class->class_id,$section->id,$daykey);

																				if(!empty($period))

																				foreach($period as $period_data)

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
																					$start_am_pm='';
																					//$start_am_pm=$start_time_data[2];
																					$end_time_data = explode(":", $period_data->end_time);

																					$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

																					$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

																					//$end_am_pm=$end_time_data[2];
																					$end_am_pm='';

																					if (get_option('smgt_enable_virtual_classroom') == 'yes')

																					{

																						$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);

																						if(empty($meeting_data))

																						{

																							$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show_virtual_popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																						}

																						else

																						{

																							$create_meeting = '';

																						}



																						if(!empty($meeting_data))

																						{

																							$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																							$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

																							$meeting_statrt_link = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Start Virtual Class','school-mgt').'</a></li>';

																						}

																						else

																						{

																							$update_meeting = '';

																							$delete_meeting = '';

																							$meeting_statrt_link = '';

																						}

																					}

																					//echo '<span class="time"> ('.$period_data->start_time.' - '.$period_data->end_time.') </span>';

																					echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';

																					echo '</span><span class="caret"></span></button>';

																					echo '<ul class="pt-2 dropdown-menu edit_delete_drop">

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit','school-mgt').'</a></li>

																							<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'. esc_attr__('Delete','school-mgt').'</a></li>

																							'.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'

																						</ul>';

																					echo '</div>';

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

										esc_attr_e( 'Class data not avilable', 'school-mgt' );

									}

									?>

								</div>

        					</div><!-------- penal Body ------->

        				</div><!-------- penal White ------->

    					<?php

					}

					if($active_tab == 'addroute')

					{

						require_once SMS_PLUGIN_DIR. '/admin/includes/routine/add-route.php';

					}

					if($active_tab == 'import_class_route')

					{

						require_once SMS_PLUGIN_DIR. '/admin/includes/routine/import_class_route.php';

					}

					if($active_tab == 'teacher_timetable')

					{

						?>
						<div class="popup-bg">
							<div class="overlay-content max_height_overflow">
								<div class="modal-content">
									<div class="category_list"></div>
								</div>
							</div>
						</div>
						<div class="panel-white margin_top_20px"><!-------- penal White ------->

		 					<div class="panel-body"><!-------- penal Body ------->

		 						<div id="accordion" class="panel-group accordion accordion-flush padding_top_15px_res" aria-multiselectable="true" role="tablist">

       								<?php

									$teacherdata=mj_smgt_get_usersdata('teacher');

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

													<button class="accordion-button class_route_list collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">

														<span class="Title_font_weight"><?php esc_attr_e('Teacher', 'school-mgt'); ?></span> : <?php  if(!empty($classname_ltrim)){ echo $retrieved_data->display_name.'('.$classname_ltrim.')';} else{ echo $retrieved_data->display_name;} ?> </a>

													</button>

												</h4>

												<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse wizard_accordion_rtl collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionFlushExample">

            										<div class="panel-body">

        												<table class="table table-bordered">

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
																		//$period = array_merge($period_1, $period_2);

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

																						$create_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none show_virtual_popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';

																					}

																					else

																					{

																						$create_meeting = '';

																					}



																					if(!empty($meeting_data))

																					{

																						$update_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';

																						$delete_meeting = '<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';

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

																				echo '<ul role="menu" class="pt-2 dropdown-menu">

																						<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit','school-mgt').'</a></li>

																						<li class="float_left_width_100"><a class="float_left_width_100 text-decoration-none" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete','school-mgt').'</a></li>

																						'.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'

																					</ul>';

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
									?>
									<div class="calendar-event-new">
									    <img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
								    </div>
									<?php
									}

									?>

								</div>

							</div><!-------- penal Body ------->

						</div><!-------- penal White ------->

						<?php

					} ?>

				</div>

			</div><!-------- penal Body ------->

		</div><!-------- penal White ------->

	</div>

</div><!------- page inner --------->