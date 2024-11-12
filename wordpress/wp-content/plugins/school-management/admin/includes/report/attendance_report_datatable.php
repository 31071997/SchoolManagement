<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
		<form method="post">  
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
						<select name="class_id"  id="class_list" class="line_height_30px form-control class_id_exam validate[required]">
							<?php $class_id="";
							if(isset($_REQUEST['class_id']))
							{
								$class_id=$_REQUEST['class_id'];
							}?>
							<option value="all_class"><?php esc_attr_e('All Class','school-mgt');?></option>
							<?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{
								?>
								<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
								<?php 
							}?>
						</select>         
					</div>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
						<select name="status" class="line_height_30px form-control" >
							<option value="all_status"><?php esc_attr_e('All Status','school-mgt');?></option>
							<option value="Present"><?php esc_attr_e('Present','school-mgt');?></option>
							<option value="Absent"><?php esc_attr_e('Absent','school-mgt');?></option>
							<option value="Late"><?php esc_attr_e('Late','school-mgt');?></option>
							<option value="Half Day"><?php esc_attr_e('Half Day','school-mgt');?></option>
						</select>      
					</div>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>			
							<select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">
								<option value="today"><?php esc_attr_e('Today','school-mgt');?></option>
								<option value="this_week"><?php esc_attr_e('This Week','school-mgt');?></option>
								<option value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>
								<option value="this_month" 	selected><?php esc_attr_e('This Month','school-mgt');?></option>
								<option value="last_month"><?php esc_attr_e('Last Month','school-mgt');?></option>
								<option value="last_3_month"><?php esc_attr_e('Last 3 Months','school-mgt');?></option>
								<option value="last_6_month"><?php esc_attr_e('Last 6 Months','school-mgt');?></option>
								<option value="last_12_month"><?php esc_attr_e('Last 12 Months','school-mgt');?></option>
								<option value="this_year"><?php esc_attr_e('This Year','school-mgt');?></option>
								<option value="last_year"><?php esc_attr_e('Last Year','school-mgt');?></option>
								<option value="period"><?php esc_attr_e('Period','school-mgt');?></option>
							</select>
					</div>
					<div id="date_type_div" class="date_type_div_none col-md-6"></div>	
						<div class="col-md-6">
							<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
						</div>
				</div>
			</div>
			
		</form>
	</div>		
	<?php
	if(isset($_REQUEST['view_attendance']))
	{
		$date_type = $_POST['date_type'];
		if($date_type=="period")
		{
			$start_date = $_REQUEST['start_date'];
			$end_date = $_REQUEST['end_date'];
			$class_id = $_POST['class_id'];
			$status = $_POST['status'];
			$attendance=mj_smgt_view_attendance_for_report($start_date,$end_date,$class_id,$status);
		}
		elseif($date_type=="today" || $date_type=="this_week" || $date_type=="last_week" || $date_type=="this_month" || $date_type=="last_month" || $date_type=="last_3_month" || $date_type=="last_6_month" || $date_type=="last_12_month" || $date_type=="this_year" ){
			$result =  mj_smgt_all_date_type_value($date_type);
			
			$response =  json_decode($result);
			$start_date = $response[0];
			$end_date = $response[1];
			$class_id = $_POST['class_id'];
			$status = $_POST['status'];
			$attendance=mj_smgt_view_attendance_for_report($start_date,$end_date,$class_id,$status);
		}
	}
	else
	{
		$start_date = date('Y-m-d',strtotime('first day of this month'));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
		if ($school_obj->role == 'teacher') 
		{
			$attendance = mj_smgt_view_attendance_report_for_start_date_enddate_for_teacher($start_date,$end_date,get_current_user_id());

		}
		else{
			
			$attendance=mj_smgt_view_attendance_report_for_start_date_enddate($start_date,$end_date);
		}
		
	}
	?>
    <div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
		<?php
        if(!empty($attendance))
        {
            ?>
			<div class="table-responsive">
				<div class="btn-place"></div>
				<form id="frm-example" name="frm-example" method="post">
					<table id="attendance_list_report" class="display" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
								<th><?php esc_attr_e('Student Name','school-mgt');?></th>
								<th><?php esc_attr_e('Class Name','school-mgt');?></th>
								<th><?php esc_attr_e('Date','school-mgt');?></th>
								<th><?php esc_attr_e('Day','school-mgt');?></th>
								<th><?php esc_attr_e('Status','school-mgt');?></th>
								<th><?php esc_attr_e('Attendance By','school-mgt');?></th>
								<th><?php esc_attr_e('Attendance With QR Code','school-mgt');?></th>
								<th><?php esc_attr_e('Comment','school-mgt');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($attendance))
							{
								$i=0;
								foreach($attendance as $attendance_data)
								{
									$class_section_sub_name=smgt_get_class_section_subject($attendance_data->class_id,$attendance_data->section_id,$attendance_data->sub_id);
									$created_by = get_userdata($attendance_data->attend_by);
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
									<td class="user_image width_50px profile_image_prescription padding_left_0">
										<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center margin_top_3px">
										</p>
									</td>
									<td><?php echo mj_smgt_student_display_name_with_roll($attendance_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>
									<td><?php echo $class_section_sub_name; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
									<td><?php echo mj_smgt_getdate_in_input_box($attendance_data->attendance_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
									<td><?php  $day=date("l", strtotime($attendance_data->attendance_date));
                                        echo esc_html__($day,"school-mgt"); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>
									<td class="name">
										<?php $status_color =  MJ_smgt_attendance_status_color($attendance_data->status);?>
										<span style="color:<?php echo $status_color;?>;">
											<?php echo esc_html__($attendance_data->status,"school-mgt"); ?>
										</span>
										<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
									</td>
									<td class="name">
										<?php echo esc_html__($created_by->display_name,"school-mgt"); ?>
										<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
									</td>
									<?php
									$comment =$attendance_data->comment;
									$description = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
									?>
									<td><?php if ($attendance_data->attendence_type == 'QR') {
												echo esc_html__("Yes","school-mgt");
											}
											else{
												echo esc_html__("No","school-mgt");
											} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Attendance With QR Code','school-mgt');?>"></i></td>              
									<td class="name">
                                    <?php
                                    if(!empty($attendance_data->comment))
                                    {   $comment = $attendance_data->comment;
                                        $grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
                                        echo $grade_comment;
                                    }
                                    else
                                    {
                                        echo "N/A";
                                    }
                                    ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
                                    if(!empty($attendance_data->comment))
                                    { 
                                        echo $attendance_data->comment;
                                    } 
                                    else
                                    {  _e('Comment','school-mgt');
                                    }
                                    ?>
                                    "></i>
                                </td>
									<?php
									echo '</tr>';
									$i++;
								}
							}
							?>
						</tbody>        
					</table>
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
        }  ?>
    </div>
</div>