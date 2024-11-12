<?php 
$obj_mark = new Marks_Manage();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'stud_attendance';
$role='student';
if(isset($_REQUEST['student_id'])) $student_id=$_REQUEST['student_id'];?>
 
 <script type="text/javascript" >
 jQuery(document).ready(function($){
	"use strict";	
	
	$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
	$('.edate').datepicker({dateFormat: "yy-mm-dd"});  

	var table =  jQuery('#attendance_list').DataTable({
		//stateSave: true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[	                  
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},	           
				{"bSortable": false}],		
			});
	$('#subject_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});	 
	$('#curr_date').datepicker({dateFormat: "yy-mm-dd"}); 	
	
});
 </script>

<div class="panel-body panel-white">
<ul class="nav nav-tabs panel_tabs flex-nowrap" role="tablist">
      <li class="<?php if($active_tab=='stud_attendance'){?>active<?php }?>">
          <a href="?dashboard=user&page=view-attendance&tab=stud_attendance&student_id=<?php echo $student_id;?>" class="nav-tab2">
             <i class="fa fa-align-justify"></i> <?php esc_attr_e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
     <li class="<?php if($active_tab=='sub_attendance'){?>active<?php }?>">
			<a href="?dashboard=user&page=view-attendance&tab=sub_attendance&student_id=<?php echo $student_id;?>" class="nav-tab2 <?php echo $active_tab == 'sub_attendance' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_attr_e('Subject Wise Attendance', 'school-mgt'); ?></a>
          </a>
      </li>     
</ul>

 <div class="tab-content">
     <?php if($active_tab == 'stud_attendance')
     {
		$student_data=get_userdata($_REQUEST['student_id']);
	?> 
		<div class="panel-body">
		<form name="wcwm_report" action="" method="post">
			<input type="hidden" name="attendance" value=1> 
			<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>>  
					<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-12">	
							<?php
							$umetadata=mj_smgt_get_user_image($_REQUEST['student_id']);
							if(empty($umetadata))
							{
								echo '<img class="img-circle img-responsive member-profile w-150-px h-150-px" src='.get_option( 'smgt_student_thumb_new' ).'/>';
							}
							else
								echo '<img class="img-circle img-responsive member-profile w-150-px h-150-px" src='.$umetadata.' />';
							?>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-12 ">
							<div class="row">
								<h2><?php echo $student_data->display_name;?></h2>
							</div>
							<div class="row">
								<div class="col-md-4 col-sm-3 col-xs-12">
									<i class="fa fa-envelope"></i>&nbsp;
									
									<span class="email-span"><?php echo $student_data->user_email;?></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<i class="fa fa-phone"></i>&nbsp;
									<span><?php echo $student_data->phone;?></span>
								</div>
								<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
									<i class="fa fa-list-alt"></i>&nbsp;
									<span><?php echo $student_data->roll_id;?></span>
								</div>
							</div>					
						</div>
					</div>
			<div class="form-group col-md-3">
				<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
			   <input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'] ;else echo date('Y-m-d');?>" readonly>								
			</div>
			<div class="form-group col-md-3">
				<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
				<input type="text" class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate']; else echo date('Y-m-d');?>" readonly>								
			</div>
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
			</div>	
		</form>
		<div class="clearfix"></div>
		<?php if(isset($_REQUEST['view_attendance']))
		{
			
			$start_date = $_REQUEST['sdate'];			
			$end_date = $_REQUEST['edate'];			
			$user_id = $_REQUEST['user_id'];
				
			 $period = new DatePeriod(
				 new DateTime($start_date),
				 new DateInterval('P1D'),
				 new DateTime($end_date)
			); 			
			$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);			
			$curremt_date = $start_date;
		?>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="attendance_list" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php esc_attr_e('Student Name','school-mgt');?></th>
						<th><?php esc_attr_e('Class Name','school-mgt');?></th>
						<th><?php esc_attr_e('Date','school-mgt');?></th>
						<th><?php esc_attr_e('Day','school-mgt');?></th>
						<th><?php esc_attr_e('Attendance','school-mgt');?></th>
						<th><?php esc_attr_e('Comment','school-mgt');?></th>
					</tr>
				</thead> 
				<tfoot>
					<tr>
						<th><?php esc_attr_e('Student Name','school-mgt');?></th>
						<th><?php esc_attr_e('Class Name','school-mgt');?></th>
						<th><?php esc_attr_e('Date','school-mgt');?></th>
						<th><?php esc_attr_e('Day','school-mgt');?></th>
						<th><?php esc_attr_e('Attendance','school-mgt');?></th>
						<th><?php esc_attr_e('Comment','school-mgt');?></th>
					</tr>
				</tfoot> 
				<tbody>
					<?php
						while ($end_date >= $curremt_date)
						{	
							echo '<tr>';
							echo '<td>';
								echo mj_smgt_get_display_name($user_id);
							echo '</td>';
							echo '<td>';
								echo mj_smgt_get_class_name_by_id(get_user_meta($user_id, 'class_name',true));
							echo '</td>';
							echo '<td>';
								echo mj_smgt_getdate_in_input_box($curremt_date);
							echo '</td>';
							
							$attendance_status = mj_smgt_get_attendence($user_id,$curremt_date);
							echo '<td>';
							echo date("D", strtotime($curremt_date));
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
				</tbody>        
			</table>
		</div>
	</div>
	

				<?php } ?>
				</div>
	<?php }
	
	if($active_tab == 'sub_attendance')
	{ 
		$student_data=get_userdata($_REQUEST['student_id']);
	?>
		 <div class="panel-body">
				<form name="wcwm_report" action="" id="subject_attendance" method="post">
				<input type="hidden" name="attendance" value=1> 
				<input type="hidden" name="user_id" value=<?php echo $student_id;?>> 
				<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-12">	
							<?php
							$umetadata=mj_smgt_get_user_image($_REQUEST['student_id']);
							if(empty($umetadata))
							{
								echo '<img class="img-circle img-responsive member-profile w-150-px h-150-px" src='.get_option( 'smgt_student_thumb_new' ).'/>';
							}
							else
								echo '<img class="img-circle img-responsive member-profile w-150-px h-150-px" src='.$umetadata.' />';
							?>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-12 ">
							<div class="row">
								<h2><?php echo $student_data->display_name;?></h2>
							</div>
							<div class="row">
								<div class="col-md-4 col-sm-3 col-xs-12">
									<i class="fa fa-envelope"></i>&nbsp;
									
									<span class="email-span"><?php echo $student_data->user_email;?></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<i class="fa fa-phone"></i>&nbsp;
									<span><?php echo $student_data->phone;?></span>
								</div>
								<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
									<i class="fa fa-list-alt"></i>&nbsp;
									<span><?php echo $student_data->roll_id;?></span>
								</div>
							</div>					
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>									
							<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo mj_smgt_getdate_in_input_box($_REQUEST['sdate']);else echo date('Y-m-d');?>" readonly>							
					</div>
					<div class="form-group col-md-3">
						<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
						<input type="text"   class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo mj_smgt_getdate_in_input_box($_REQUEST['edate']);else echo date('Y-m-d');?>" readonly>								
					</div>
					
					<div class="form-group col-md-3">
						<label for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field">*</span></label>			
						<?php $class_id=get_user_meta($student_id,'class_name',true); ?>
							<select name="sub_id"  class="form-control validate[required]">
								<option value=" "><?php esc_attr_e('Select Subject','school-mgt');?></option>
								<?php 
								$sub_id=0;
								if(isset($_POST['sub_id'])){
									$sub_id=$_POST['sub_id'];
								}
								$allsubjects = mj_smgt_get_subject_by_classid($class_id);
								foreach($allsubjects as $subjectdata)
								{ ?>
									<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>
							<?php } ?>
							</select>						
					</div>
					<div class="form-group col-md-3 button-possition">
						<label for="subject_id">&nbsp;</label>
						<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
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
					<div class="table-responsive">
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
							echo mj_smgt_getdate_in_input_box($curremt_date);
							echo '</td>';
							
							$sub_attendance_status = mj_smgt_get_sub_attendence($user_id,$curremt_date,$sub_id);
							echo '<td>';
							echo date("D", strtotime($curremt_date));
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
						} ?>
						</table>
					</div>
				<?php }?>
				</div>
 <?php } ?>
</div>
</div>
<?php ?>