<?php 	
	$obj_attend=new Attendence_Manage();
	$class_id =0;
	$current_date = date("y-m-d");
	$active_tab_1 = isset($_GET['tab1'])?$_GET['tab1']:'view_teacher_attendence'; 
?>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>
<div class="panel-body"> <!-- panel-body -->
	<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per mb-4" role="tablist">
		<li class="<?php if($active_tab_1 =='view_teacher_attendence'){?>active<?php }?>">
			<a href="?page=smgt_attendence&tab=teacher_attendence&tab1=view_teacher_attendence" class="padding_left_0 tab <?php echo $active_tab_1 == 'view_teacher_attendence' ? 'nav-tab-active' : ''; ?>">
			<?php echo esc_attr__('Teacher Attendance', 'school-mgt'); ?></a>
		</li>	
		<li class="<?php if($active_tab_1=='export_teacher_attendence'){?>active<?php }?>">
			<a href="?page=smgt_attendence&tab=teacher_attendence&tab1=export_teacher_attendence" class="padding_left_0 tab <?php echo $active_tab_1 == 'export_teacher_attendence' ? 'nav-tab-active' : ''; ?>">
			<?php echo esc_attr__('Export Teacher Attendance', 'school-mgt'); ?></a>
		</li>		
	</ul>
	<?php
	if($active_tab_1 == 'view_teacher_attendence')
	{
		?>
		<form method="post" id="teacher_attendance">           
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="curr_date_teacher" class="form-control date_picker" type="text" value="<?php if(isset($_POST['tcurr_date'])) echo $_POST['tcurr_date']; else echo  date("Y-m-d");?>" name="tcurr_date" readonly>	
								<label class="date_label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>	
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<!-- <label for="subject_id">&nbsp;</label> -->
						<input type="submit" value="<?php esc_attr_e('Take Attendance','school-mgt');?>" name="teacher_attendence"  class="save_btn"/>
					</div>
				</div>
			</div>
		</form>
		<?php
	}
	if($active_tab_1 == 'export_teacher_attendence')
	{
		?>
		<div class="panel-body"><!-- panel-body --> 
			<form name="upload_form" action="" method="post" class="form-horizontal" id="upload_form" enctype="multipart/form-data">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<div class="form-body user_form">
					<div class="row">	
						<div class="col-md-6 error_msg_left_margin input">
							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Teacher','school-mgt');?><span class="required">*</span></label>
							<?php if($edit){ $workrval=$user_info->teacher_name; }elseif(isset($_POST['teacher_name'])){$workrval=$_POST['teacher_name'];}else{$workrval='';}?>
							<select name="teacher_name" class="form-control validate[required] width_100 class_by_teacher" id="teacher_name">
								<option value=""><?php echo esc_attr_e( 'Select Teacher', 'school-mgt' ) ;?></option>
								<?php
								$teacherdata_array=mj_smgt_get_usersdata('teacher');
								foreach($teacherdata_array as $techer_data)
								{
									
									?>
									<option value="<?php echo $techer_data->ID;?>" <?php selected($techer_data->ID);  ?>><?php echo $techer_data->display_name;?></option> 
									<?php 
								}
								?>
							</select>                            
						</div> 
						<div class="col-sm-3">        	
							<input type="submit" value="<?php esc_attr_e('Export Teacher Attendance','school-mgt');?>" name="export_teacher_attendance_in_csv" class="save_att_btn"/>
						</div>
					</div>
				</div>
			</form>
		</div><!-- panel-body --> 
		<?php
	}
	?>
</div><!-- panel-body -->
<div class="clearfix"> </div>
<?php 
if(isset($_REQUEST['teacher_attendence']) || isset($_REQUEST['save_teach_attendence']))
{	
	$attendanace_date=$_REQUEST['tcurr_date'];
	$holiday_dates=mj_smgt_get_all_date_of_holidays();
	if (in_array($attendanace_date, $holiday_dates))
	{
		?>
		<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
			<p><?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<?php 
	}
	else
	{
		?>
		<div class="panel-body"> <!-- panel-body -->
			<form method="post">        
				<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
				<input type="hidden" name="tcurr_date" value="<?php echo $_POST['tcurr_date'];?>" />
				<div class="panel-heading">
					<h4 class="panel-title"><?php esc_attr_e('Teacher Attendance','school-mgt');?> , 
					<?php esc_attr_e('Date','school-mgt')?> : <?php echo $_POST['tcurr_date'];?></h4>
				</div>
				<div class="col-md-12 padding_payment smgt_att_tbl_list">
					<div class="table-responsive">
						<table class="table">
							<tr>
								<th style="width: 0px!important;"><?php esc_attr_e('Srno','school-mgt');?></th>
								<th><?php esc_attr_e('Teacher','school-mgt');?></th>
								<th style="width: 250px!important;"><?php esc_attr_e('Attendance','school-mgt');?></th>
								<th><?php esc_attr_e('Comment','school-mgt');?></th>
							</tr>
							<?php 
							$date = $_POST['tcurr_date'];
							$i=1;
							$teacher = get_users(array('role'=>'teacher'));
							foreach ($teacher as $user)
							{
								$class_id=0;
								$check_attendance = $obj_attend->mj_smgt_check_attendence($user->ID,$class_id,$date);
								
								$attendanc_status = "Present";
								if(!empty($check_attendance))
								{
									$attendanc_status = $check_attendance->status;
									
								}
								echo '<tr>';  
								echo '<tr>';
							
								echo '<td>'.$i.'</td>';
								echo '<td style="padding-left:0px!important;"><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
								?>
								<td style="padding-left:0px!important;">
									<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>
									<?php esc_attr_e('Present','school-mgt');?></label>
									<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>
									<?php esc_attr_e('Absent','school-mgt');?></label><br>
									<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>
									<?php esc_attr_e('Late','school-mgt');?></label>
									<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Half Day" <?php checked( $attendanc_status, 'Half Day' );?>>
									<?php esc_attr_e('Half Day','school-mgt');?></label>
								</td>

								<td class="">
									<div class="form-group input margin_bottom_0px">
										<div class="col-md-12 form-control">
											<input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>">
										</div>
									</div>
								</td>

								
								<?php 
								
								echo '</tr>';
								$i++;
							}
							?>   
						</table>
					</div>
				</div>		
				<div class="cleatrfix"></div>
				<div class="col-sm-12 padding_top_10px rtl_res_att_save">    
					<input type="submit" value="<?php esc_attr_e("Save Attendance","school-mgt");?>" name="save_teach_attendence" id="res_rtl_width_100" class="col-sm-12 save_att_btn " />
				</div>       
			</form>
		</div><!-- panel-body -->
		<?php
	}
} ?>