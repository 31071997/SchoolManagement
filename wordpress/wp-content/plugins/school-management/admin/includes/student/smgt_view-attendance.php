<?php 
$obj_mark = new Marks_Manage();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'stud_attendance';
$role='student';
if(isset($_REQUEST['student_id'])) $student_id=$_REQUEST['student_id'];?>

<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/view_student.js'; ?>" ></script>

<div class="panel-body panel-white">
 <ul class="nav nav-tabs flex-nowrap panel_tabs" role="tablist">
      <li class="<?php if($active_tab=='stud_attendance'){?>active<?php }?>">
          <a href="?page=smgt_view-attendance&tab=stud_attendance&student_id=<?php echo $student_id;?>">
             <i class="fa fa-align-justify"></i> <?php esc_attr_e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
     <li class="<?php if($active_tab=='sub_attendance'){?>active<?php }?>">
			<a href="?page=smgt_view-attendance&tab=sub_attendance&student_id=<?php echo $student_id;?>" class="tab <?php echo $active_tab == 'sub_attendance' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_attr_e('Subject Wise Attendance', 'school-mgt'); ?></a>
          </a>
      </li>     
</ul>
<div class="tab-content">
<?php
if($active_tab == 'stud_attendance')
{
?> 	<div class="panel-body">
		<form name="wcwm_report" action="" method="post">
			<input type="hidden" name="attendance" value=1> 
			<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>>       
			<div class="row">
				<div class="form-group col-md-3">
					<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
					<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
					<input type="text"   class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
				</div>
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
				</div>
			</div>	
		</form>
		<div class="clearfix"></div>
		<?php if(isset($_REQUEST['view_attendance']))
		{
			$start_date = $_REQUEST['sdate'];
			$end_date = $_REQUEST['edate'];
			$user_id = $_REQUEST['user_id'];
			$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);
			$curremt_date =$start_date;
		?>
			<table class="table col-md-12">
				<tr>
					<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
					<th><?php esc_attr_e('Day','school-mgt');?></th>
					<th><?php esc_attr_e('Attendance','school-mgt');?></th>
					<th><?php esc_attr_e('Comment','school-mgt');?></th>
				</tr>
				<?php 
				while ($end_date >= $curremt_date)
				{
					echo '<tr>';
					echo '<td>';
					echo $curremt_date;
					echo '</td>';
					
					$attendance_status = mj_smgt_get_attendence($user_id,$curremt_date);
					echo '<td>';
					$day=date("D", strtotime($curremt_date));
					echo esc_attr__('$day','school-mgt'); 
					echo '</td>';
					
					if(!empty($attendance_status))
					{
						echo '<td>';
						echo mj_smgt_get_attendence($user_id,$curremt_date);
						echo '</td>';
					}
					else 
					{
						echo '<td>';
						echo esc_attr__('Absent','school-mgt');
						echo '</td>';
					}
					echo '<td>';
					echo mj_smgt_get_attendence_comment($user_id,$curremt_date);
					echo '</td>';
					echo '</tr>';
					$curremt_date = strtotime("+1 day", strtotime($curremt_date));
					$curremt_date = date("Y-m-d", $curremt_date);
				}
				?>
				</table>
		<?php } ?>
		</div>
	<?php }
	
	if($active_tab == 'sub_attendance')
	{ 

?>

		 <div class="panel-body">
				<form name="wcwm_report" action="" method="post">
				<input type="hidden" name="attendance" value=1> 
				<input type="hidden" name="user_id" value=<?php echo $student_id;?>>       
					<div class="row">
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
							<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
						</div>
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
							<input type="text"   class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
						</div>
						
						<div class="form-group col-md-3">
							<label for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?></label>
							<?php $class_id=get_user_meta($student_id,'class_name',true);
							?>
								 <select name="sub_id"  class="form-control ">
										<option value=" "><?php esc_attr_e('Select Subject','school-mgt');?></option>
										<?php
										$sub_id=0;
											if(isset($_POST['sub_id'])){
													$sub_id=$_POST['sub_id'];
											}
										  $allsubjects = mj_smgt_get_subject_by_classid($class_id);
										 foreach($allsubjects as $subjectdata)
										  {?>
											<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>
									 <?php } ?>
									</select>
						
						</div>
						<div class="form-group col-md-3 button-possition">
							<label for="subject_id">&nbsp;</label>
							<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
						</div>
					</div>	
				</form>
				<div class="clearfix"></div>
		
        
         <?php if(isset($_REQUEST['view_attendance']))
				{
					$start_date = $_REQUEST['sdate'];
					$end_date = $_REQUEST['edate'];
					$user_id = $_REQUEST['user_id'];
					 $sub_id = $_REQUEST['sub_id'];
					$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);
					
					$curremt_date =$start_date;
					?>
					
				<table class="table col-md-12">
					<tr>
					<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
					<th><?php esc_attr_e('Day','school-mgt');?></th>
					<th><?php esc_attr_e('Attendance','school-mgt');?></th>
					<th><?php esc_attr_e('Comment','school-mgt');?></th>
					</tr>
					<?php 
					while ($end_date >= $curremt_date)
					{
						echo '<tr>';
						echo '<td>';
						echo $curremt_date;
						echo '</td>';
						
						$sub_attendance_status = mj_smgt_get_sub_attendence($user_id,$curremt_date,$sub_id);
						echo '<td>';
						$day=date("D", strtotime($curremt_date));
						echo esc_attr__("$day","school-mgt"); 
						echo '</td>';
						
						
						if(!empty($sub_attendance_status))
						{
							echo '<td>';
							echo mj_smgt_get_sub_attendence($user_id,$curremt_date,$sub_id);
							echo '</td>';
						}
						else 
						{
							echo '<td>';
							echo esc_attr__('Absent','school-mgt');
							echo '</td>';
						}
						echo '<td>';
						echo mj_smgt_get_sub_attendence_comment($user_id,$curremt_date,$sub_id);
						echo '</td>';
						echo '</tr>';
						$curremt_date = strtotime("+1 day", strtotime($curremt_date));
						$curremt_date = date("Y-m-d", $curremt_date);
					}
				?>
				</table>

				<?php }?>
				</div>				

		
 <?php } ?>
</div>
</div>