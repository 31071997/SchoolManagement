<?php
if($active_tab == 'exam_time_table')
{
	?>
	<script>
		//-------- timepicker ---------//
		jQuery(document).ready(function($){
			mdtimepicker('#timepicker', {
			events: {
					timeChanged: function (data) {
						
					}
				},
			theme: 'purple',
			readOnly: false,
			});
		})
	</script>
    <div class="panel-body margin_top_20px padding_top_25px_res"><!-----  penal body ------->
		<!----------- Exam Time table Form ---------->
        <form name="exam_form" action="" method="post" class="mb-3 form-horizontal" enctype="multipart/form-data" id="exam_form">
			<div class="form-body user_form padding_top_25px_res">
				<div class="row">
					<div class="col-md-8 input exam_time_table_error_msg">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="required">*</span></label>
						<?php
						$tablename="exam"; 
						$retrieve_class = mj_smgt_get_all_data($tablename);
						$exam_id="";
						if(isset($_REQUEST['exam_id']))
						{
							$exam_id=$_REQUEST['exam_id']; 
						}
						?>
						<select name="exam_id" class="form-control validate[required] width_100">
							<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
							<?php
							foreach($retrieve_class as $retrieved_data)
							{
								$cid=$retrieved_data->class_id;
								$clasname=mj_smgt_get_class_name($cid);
								if($retrieved_data->section_id!=0)
								{
									$section_name=mj_smgt_get_section_name($retrieved_data->section_id); 
								}
								else
								{
									$section_name=esc_attr__('No Section', 'school-mgt');
								}
							?>
								<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.'('.smgt_get_class_section_name_wise($cid,$retrieved_data->section_id).')';?></option>
							<?php	
							}
							?>
						</select>               
					</div>
					<div class="col-md-4 col-sm-3 col-xs-12">        	
						<input type="submit" id="save_exam_time_table" value="<?php  esc_attr_e('Manage Exam Time','school-mgt');?>" name="save_exam_time_table" class="btn btn-success save_btn" />
					</div>  
				</div>
			</div>
        </form><!----------- Exam Time table Form ---------->
  		<?php
		//   save exam time table 
		if(isset($_POST['save_exam_time_table']))
		{
			$exam_data= mj_smgt_get_exam_by_id($_POST['exam_id']);
			$school_obj= new School_Management;
			if($exam_data->section_id != 0) //------- any section ----------//
			{
				$subject_data=$school_obj->mj_smgt_subject_list_with_calss_and_section($exam_data->class_id,$exam_data->section_id);
			}
			else //--------- section empty -----------//
			{
				$subject_data=$school_obj->mj_smgt_subject_list($exam_data->class_id);
			}
			$start_date=$exam_data->exam_start_date;
			$end_date=$exam_data->exam_end_date;
			
			?>
			<input type="hidden" id="start" value="<?php echo date("Y-m-d",strtotime($start_date));?>">
			<input type="hidden" id="end" value="<?php echo date("Y-m-d",strtotime($end_date));?>">
			<div class="form-group"><!-------- Form Body -------->
				<div class="col-md-12 rtl_padding_0px">
					<div class="exam_table_res">
						<table class="table" style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;">
							<thead>
								<tr>
									<th class="exam_hall_receipt_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Exam','school-mgt');?></th>
									<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Class','school-mgt');?></th>							
									<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Section','school-mgt');?></th>							
									<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Term','school-mgt');?></th>							
									<th  class="exam_hall_receipt_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('Start Date','school-mgt');?></th>							
									<th  class="exam_hall_receipt_table_heading" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php  esc_attr_e('End Date','school-mgt');?></th>							
								</tr>
							</thead>
							<tbody>							
								<tr>
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo $exam_data->exam_name;?></td>							
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo get_the_title($exam_data->exam_term);?></td>
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>
									<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>			
			</div><!-------- Form Body -------->
			<?php
			if(isset($subject_data))
			{
				$obj_exam=new smgt_exam;
				foreach ($subject_data as $retrieved_data) 
				{ 
					$exam_time_table_data=$obj_exam->mj_smgt_check_exam_time_table($exam_data->class_id,$exam_data->exam_id,$retrieved_data->subid);	
				}
				
				if(!empty($subject_data))
				{
					?>
					<div class="col-md-12 rtl_padding_0px" style="margin-top: 20px !important">
						<div class="exam_table_res">
							<form id="exam_form2" name="exam_form2" method="post">	<!-------- Exam Form -------->
								<input type='hidden' name='subject_data' id="subject_data" value='<?php echo json_encode($subject_data);?>'>
								<input type="hidden" name="class_id" value="<?php echo $exam_data->class_id;?>">
								<input type="hidden" name="section_id" value="<?php echo $exam_data->section_id;?>">
								<input type="hidden" name="exam_id" value="<?php echo $exam_data->exam_id;?>">
								<div class="exam_time_table_main_div">
									<table style="border: 1px solid #D9E1ED;text-align: center;margin-bottom: 0px;" class="exam_timelist_admin width_100" >
										<thead>
											<tr>    
												<th class="exam_hall_receipt_add_table_heading" style="border-top: medium none;border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Subject Code','school-mgt');?></th>
												<th class="exam_hall_receipt_add_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Subject Name','school-mgt');?></th>
												<th class="exam_hall_receipt_add_table_heading" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam Date','school-mgt');?></th>
												<th class="exam_hall_receipt_add_table_heading min_width_115" style="border-right: 1px solid #D9E1ED;background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>
												<th  class="exam_hall_receipt_add_table_heading min_width_115" style="background-color: #F2F5FA;border-bottom: 1px solid #D9E1ED;text-align: center;"><?php esc_attr_e('Exam End Time','school-mgt');?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
											
											$i = 1;
										
											foreach ($subject_data as $retrieved_data) 
											{ 

												//------- View Exam Time Table Data ------------//
												$exam_time_table_data=$obj_exam->mj_smgt_check_exam_time_table($exam_data->class_id,$exam_data->exam_id,$retrieved_data->subid);	

												?>
												<script>
													$(document).ready(function(){
														var start = $( "#start" ).val();
														var end = $( "#end" ).val();
														$(".exam_date").datepicker({ 
															minDate: start,
															maxDate: end,
															changeYear: true,
															changeMonth: true,
															dateFormat: "yy-mm-dd",
															
														});  
													});
												</script>
												<tr class="main_date_css" style="border: 1px solid #D9E1ED;">
													<input type="hidden" name="subject_id" value="<?php echo $retrieved_data->subid;?>">
													<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><input type="hidden" name = "subject_code_<?php echo $retrieved_data->subid;?>"  value="<?php echo $retrieved_data->subject_code;?>"><?php echo $retrieved_data->subject_code;?></td>
													<td class="exam_hall_receipt_table_value" style="border-right: 1px solid #D9E1ED;"><input type="hidden" name = "subject_name_<?php echo $retrieved_data->subid;?>" value="<?php echo $retrieved_data->sub_name;?>"><?php echo $retrieved_data->sub_name;?></td>
													<td class="exam_hall_receipt_table_value exam_timetbl_validation" style="border-right: 1px solid #D9E1ED;">
														<input id="exam_date_<?php echo $retrieved_data->subid; ?>" class="datepicker form-control datepicker_icon validate[required] text-input exam_date min_width_160 date_border_css" placeholder="<?php esc_html_e("Select Date" , "school-mgt"); ?>" type="text" name="exam_date_<?php echo $retrieved_data->subid; ?>" value="<?php if(!empty($exam_time_table_data->exam_date)) { echo mj_smgt_getdate_in_input_box($exam_time_table_data->exam_date); } ?>" readonly>
													</td>
													<?php
													if(!empty($exam_time_table_data->start_time))
													{
														//------------ Start time convert --------------//
														$stime = explode(":", $exam_time_table_data->start_time);
														$start_hour=$stime[0];
														$start_min=$stime[1];
														$shours = str_pad($start_hour, 2, "0", STR_PAD_LEFT);
														$smin = str_pad($start_min, 2, "0", STR_PAD_LEFT);
														$start_am_pm=$stime[2];
														$start_time=$shours.':'.$smin.':'.$start_am_pm;
													}
													if(!empty($exam_time_table_data->end_time))
													{
														//-------------------- end time convert -----------------//
														$etime = explode(":", $exam_time_table_data->end_time);
														$end_hour=$etime[0];
														$end_min=$etime[1];
														$ehours = str_pad($end_hour, 2, "0", STR_PAD_LEFT);
														$emin = str_pad($end_min, 2, "0", STR_PAD_LEFT);
														$end_am_pm=$etime[2];
														$end_time=$ehours.':'.$emin.':'.$end_am_pm;
													}
													?>
													<td class="exam_hall_receipt_table_value exam_timetbl_validation" style="border-right: 1px solid #D9E1ED;">
														<input type="text" id="timepicker" name="start_time_<?php echo $retrieved_data->subid;?>" class="start_time form-control validate[required] text-input date_border_css start_time_<?php echo $retrieved_data->subid;?>" placeholder="<?php esc_html_e("Start Time" , "school-mgt"); ?>" value="<?php if(!empty($exam_time_table_data->start_time)){ echo $start_time; } ?>" />
													</td>
													<td class="exam_hall_receipt_table_value exam_timetbl_validation" style="border-right: 1px solid #D9E1ED;">
														<input type="text" id="timepicker" name="end_time_<?php echo $retrieved_data->subid;?>" class="end_time form-control validate[required] text-input date_border_css end_time_<?php echo $retrieved_data->subid;?> " placeholder="<?php esc_html_e("End Time" , "school-mgt"); ?>" value="<?php if(!empty($exam_time_table_data->end_time)){ echo $end_time; } ?>" />
													</td>
												</tr>
												<?php 
												$i++;
											} ?>
										</tbody>
									</table>
								</div>
								<?php
								if(!empty($subject_data))
								{
									?>
									<div class="col-md-3 margin_top_20px padding_top_25px_res rtl_padding_0px rtl_margin_0px">
										<input type="submit" id="save_exam_time" value="<?php  esc_attr_e('Save Time Table','school-mgt');?>" name="save_exam_table" class="btn width-auto btn-success save_btn" />
									</div>
									<?php
								}
								?>
							</form><!-------- Exam Form -------->
						</div>
					</div>
					<?php
				}
				else
				{
					?>
					<div style="margin-top:20px !important;" id="message" class="rtl_message_display_inline_block alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
						<p><?php esc_html_e('No Any Subject', 'school-mgt'); ?></p>
						<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
					<?php
				}
			}
		}
		?>
	</div><!-----  penal body ------->
	<?php
}
?>