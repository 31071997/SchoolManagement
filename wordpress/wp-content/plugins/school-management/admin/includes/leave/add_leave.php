<script type="text/javascript">
	$(document).ready(function() {
		$('#leave_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.add-search-single-select-js').select2({
		});
	})
</script>
<?php 	                                 
	$leave_id=0;
	if(isset($_REQUEST['leave_id']))
		$leave_id=$_REQUEST['leave_id'];
		$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_leave->hrmgt_get_single_leave($leave_id);
	}
	$students = mj_smgt_get_student_groupby_class();
?>
<!-- Start Panel body -->
<div class="panel-body margin_top_20px padding_top_15px_res"><!--------- penal body ------->
	<!-- Start Leave form -->
    <form name="leave_form" action="" method="post" class="form-horizontal" id="leave_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input id="action" type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="leave_id" value="<?php echo $leave_id;?>"  />
		<input type="hidden" name="status" value="<?php echo "Not Approved";?>"  />
		<input type="hidden" name="leave_id" value="<?php echo $leave_id;?>"  />
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Leave Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-md-6 input single_selecte">
					<select class="form-control add-search-single-select-js display-members max_width_700" name="student_id">
						<option value=""><?php _e('Select Student','school-mgt');?></option>
						<?php 
						if($edit)
							$student =$result->student_id;
						elseif(isset($_REQUEST['student_id']))
							$student =$_REQUEST['student_id'];  
						else 
							$student = "";					
							$studentdata=mj_smgt_get_all_student_list('student');
						
						foreach ($students as $label => $opt){ ?>
							<optgroup label="<?php echo esc_html__('Class :','school-mgt')." ".$label; ?>">
								<?php foreach ($opt as $id => $name): ?>
								<option value="<?php echo $id; ?>" <?php selected($id, $student);  ?> ><?php echo $name; ?></option>
								<?php endforeach; ?>
							</optgroup>
							<?php } ?>
					</select>
				</div>
				<div class="col-md-4 input">
					<label class="ml-1 custom-top-label top" for="leave_type"><?php esc_attr_e('Leave Type','school-mgt');?> <span class="require-field">*</span></label>
					<select class="form-control validate[required] leave_type width_100" name="leave_type" id="leave_type">
						<option value=""><?php esc_html_e('Select Leave Type','school-mgt');?></option>
						<?php 
						if($edit)
							$category =$result->leave_type;
						elseif(isset($_REQUEST['leave_type']))
							$category =$_REQUEST['leave_type'];  
						else 
							$category = "";
						$activity_category=mj_smgt_get_all_category('leave_type');
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
							}

						} 
						?> 
					</select>	
				</div>
				<div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 mb-3 rtl_margin_0px">
					<button id="addremove_cat" class="save_btn sibling_add_remove" model="leave_type"><?php esc_attr_e('Add','school-mgt');?></button>		
				</div>

				<div class="col-md-6 res_margin_bottom_20px rtl_margin_top_15px">
					<div class="form-group mb-3">
						<div class="col-md-12 form-control" style="min-height: 47px !important;">
							<div class="row padding_radio">
								<div class="input-group">
									<label class="custom-top-label margin_left_0" for="reason"><?php esc_html_e('Leave Duration','school-mgt');?><span class="required">*</span></label>
									<div class="d-inline-block">
										<?php 
										$durationval = ""; if($edit){ $durationval=$result->leave_duration; }elseif(isset($_POST['duration'])) {$durationval=$_POST['duration'];}
										?>
										<label class="radio-inline">
											<input id="half_day" type="radio" value="half_day" class="tog duration" name="leave_duration" idset ="<?php if($edit) print $result->id; ?>"  <?php  checked( 'half_day', $durationval);  ?>/><?php _e('Half Day','school-mgt');?>
										</label>
										<label class="radio-inline">
											<?php
											if($edit)
											{
												?>
												<input id="full_day" type="radio" value="full_day" class="tog duration" idset ="<?php if($edit) print $result->id; ?>"  name="leave_duration"  <?php  checked( 'full_day', $durationval);  ?> /><?php _e('Full Day','school-mgt');?> 
												<?php
											}
											else
											{
												?>
												<input id="full_day" type="radio" value="full_day" class="tog duration" idset ="<?php if($edit) print $result->id; ?>"  name="leave_duration"  <?php  checked( 'full_day', $durationval);  ?> checked /><?php _e('Full Day','school-mgt');?> 
												<?php
											}
											?>
										</label>
										<label class="radio-inline margin_left_top">
											<input id="more_then_day" type="radio" idset ="<?php if($edit) print $result->id; ?>" value="more_then_day" class="tog duration" name="leave_duration"  <?php  checked( 'more_then_day', $durationval);  ?>/><?php _e('More Than One Day','school-mgt');?> 
										</label>
									</div>
								</div>												
							</div>
						</div>
					</div>
				</div>		
				<div id="leave_date" class="col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>	
				<div class="col-md-6 note_text_notice">
					<div class="form-group input ">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea id="reason" maxlength="150" class="textarea_height_47px form-control validate[required,custom[address_description_validation]]"maxlength="150" name="reason"><?php if($edit){echo $result->reason; }elseif(isset($_POST['reason'])) echo $_POST['reason']; ?> </textarea>
								<span class="txt-title-label"></span>
								<label  class="text-area address active" for="note"><?php esc_attr_e('Reason','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>
				
				<?php wp_nonce_field( 'save_leave_nonce' ); ?>
			</div>
		</div>
		<div class="form-body user_form">
		<?php
			if(!$edit)
			{
			?>
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio rtl_relative_position">
								<div class="">
									<label class="custom-top-label label_right_position" for="smgt_enable_leave_mail"><?php esc_attr_e('Send Mail To Parents & Students','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_mail"  value="1" /> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 padding_top_15px_res rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio rtl_relative_position">
								<div class="">
									<label class="custom-top-label label_right_position" for="smgt_enable_leave_sms_student"><?php esc_attr_e('Enable Send SMS To Student','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_sms_student"  value="1" /> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 padding_top_15px_res rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio rtl_relative_position">
								<div class="">
									<label class="custom-top-label label_right_position" for="smgt_enable_leave_sms_parent"><?php esc_attr_e('Enable Send SMS To Parent','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_leave_sms_parent"  value="1" /> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6 ">
					<input type="submit" value="<?php if($edit){ _e('Save','school-mgt'); }else{ _e('Add Leave','school-mgt');}?>" name="save_leave" class="btn btn-success save_btn rtl_margin_0px save_leave_validate"/>
				</div>
			</div>
		</div>
	</form>
	<!-- End Leave form -->
</div>