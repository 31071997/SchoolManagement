<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
		<form method="post">  
			<div class="form-body user_form">
				<div class="row">
					<!-- <div class="col-md-6 input">
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
					</div> -->
                    <div class="col-md-6 input">
                        <!-- <label class="ml-1 custom-top-label top" for="staff_name"><?php //esc_html_e('Member','gym_mgt');?><span class="require-field">*</span></label> -->
                        <?php if(isset($_POST['teacher_name'])){$workrval=$_POST['teacher_name'];}else{$workrval='';}?>
                        <select id="teacher_list" class="form-control display-members" name="teacher_name" style="height:47px !important;">
                            <option value="all_teacher"><?php esc_html_e('All Teacher','school-mgt');?></option>
                                <?php $teacherdata=mj_smgt_get_usersdata('teacher');
                                if(!empty($teacherdata))
                                {
                                    foreach ($teacherdata as $teacher)
                                    {
                                        ?>
                                            <option value="<?php echo $teacher->ID;?>" <?php selected($teacher->ID);  ?>><?php echo $teacher->display_name;?></option>
                                            <?php		
                                    }
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
				<!-- <div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
								<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
								<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
							</div>
						</div>
					</div> -->
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
			$status = $_POST['status'];
            $teacher_id = $_REQUEST['teacher_name'];
			$attendance=mj_smgt_teacher_view_attendance_for_report($start_date,$end_date,$teacher_id,$status);
		}
		elseif($date_type=="today" || $date_type=="this_week" || $date_type=="last_week" || $date_type=="this_month" || $date_type=="last_month" || $date_type=="last_3_month" || $date_type=="last_6_month" || $date_type=="last_12_month" || $date_type=="this_year" ){
			$result =  mj_smgt_all_date_type_value($date_type);
			
			$response =  json_decode($result);
			$start_date = $response[0];
			$end_date = $response[1];
			$teacher_id = $_REQUEST['teacher_name'];
			$status = $_POST['status'];
			$attendance=mj_smgt_teacher_view_attendance_for_report($start_date,$end_date,$teacher_id,$status);
		}
	}
	else
	{
		$start_date = date('Y-m-d',strtotime('first day of this month'));
		$end_date = date('Y-m-d',strtotime('last day of this month'));
		$attendance=mj_smgt_view_teacher_for_report_attendance_report_for_start_date_enddate($start_date,$end_date);
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
					<table id="teacher_attendance_list_report" class="display" cellspacing="0" width="100%">
						<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
								<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
								<th><?php esc_attr_e('Class Name','school-mgt');?></th>
								<th><?php esc_attr_e('Date','school-mgt');?></th>
								<th><?php esc_attr_e('Day','school-mgt');?></th>
								<th><?php esc_attr_e('Status','school-mgt');?></th>
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
                                    $member_data = get_userdata($attendance_data->user_id);
                            $class = smgt_get_class_name_by_teacher_id($member_data->data->ID);
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
									<td><?php echo mj_smgt_get_display_name($attendance_data->user_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Teacher Name','school-mgt');?>"></i></td>
									<td><?php echo mj_smgt_get_class_name($class->class_id); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
									<td><?php echo mj_smgt_getdate_in_input_box($attendance_data->attendence_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
									<td><?php echo esc_attr_e(date("D", strtotime($attendance_data->attendence_date)),'school-mgt'); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Day','school-mgt');?>"></i></td>
									<td class="name">
										<?php $status_color =  MJ_smgt_attendance_status_color($attendance_data->status);?>
										<span style="color:<?php echo $status_color;?>;">
											<?php echo esc_html__($attendance_data->status,"school-mgt"); ?>
										</span>
										<i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
									</td>
									<?php
									$comment =$attendance_data->comment;
									$description = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
									?>
									<td><?php if(!empty($description)){ echo $description; }else{ echo 'N/A'; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php if(!empty($comment)){ echo $comment;}else{ esc_html_e('Comment','school-mgt');} ?>"></i></td>              
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