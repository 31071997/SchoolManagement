<?php

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

							<input id="class_section" class="form-control" maxlength="50" type="text" value="<?php echo mj_smgt_get_section_name($route_data->section_name); ?>" name="class_section" disabled>

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

								<label class="text-area address"><?php esc_html_e('Topic','school-mgt');?></label>

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

		<?php wp_nonce_field( 'edit_meeting_admin_nonce' ); ?>

		<div class="form-body user_form">

			<div class="row">	

				<div class="col-md-6">        	

					<input type="submit" value="<?php if(!empty($route_data)){ esc_attr_e('Save Meeting','school-mgt'); }else{ esc_attr_e('Create Meeting','school-mgt');}?>" name="edit_meeting" class="btn save_btn btn-success" />

				</div>   

			</div>   

		</div>        

     </form>

    </div>

    <?php

?>