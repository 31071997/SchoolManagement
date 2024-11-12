<div class="panel-body"><!-- panel-body -->
	<h2>
		<?php
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			echo esc_html( esc_attr__( 'Edit Message', 'school-mgt') );
			$edit=1;
			$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
		}
		?>
	</h2>

	<form name="class_form" action="" method="post" class="form-horizontal" id="message_form" enctype="multipart/form-data"><!-- form div -->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">

		<div class="form-body user_form"><!--user form -->
			<div class="row"><!--row -->
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
					<label class="ml-1 custom-top-label top" for="to"><?php esc_attr_e('Message To','school-mgt');?><span class="require-field">*</span></label>
					<select name="receiver" class="form-control validate[required] text-input min_width_100" id="send_to">
						<option value="student"><?php esc_attr_e('Students','school-mgt');?></option>
						<option value="teacher"><?php esc_attr_e('Teachers','school-mgt');?></option>
						<option value="parent"><?php esc_attr_e('Parents','school-mgt');?></option>
						<option value="supportstaff"><?php esc_attr_e('Support Staff','school-mgt');?></option>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_selection">
					<label class="ml-1 custom-top-label top" for="to"><?php esc_attr_e('Class Selection Type','school-mgt');?></label>
					<select name="class_selection_type" class="form-control text-input class_selection_type min_width_100">
						<option value="single"><?php esc_attr_e('Single','school-mgt');?></option>
						<option value="multiple"><?php esc_attr_e('Multiple','school-mgt');?></option>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 multiple_class_div input" style="display:none">
					<div class="col-sm-12 smgt_msg_multiple smgt_multiple_select multiselect_validation1 rtl_padding_0px">
						<select name="multi_class_id[]" class="form-control" id="selected_class" multiple="multiple">
							<?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{
								?>
									<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div id="smgt_select_class" class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input single_class_div class_list_id">
					<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
					<select name="class_id"  id="class_list_id" class="form-control min_width_100 validate[required]">
						<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{
						?>
						<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					<?php }?>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_section_id">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control min_width_100" id="class_section_id">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
							{  ?>
							<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php }
						}?>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
					<div id="messahe_test"></div>
					<div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
						<span class="user_display_block">
							<select name="selected_users[]" id="selected_users" class="form-control min_width_250px validate[required]" multiple="multiple">
								<?php
								$student_list = mj_smgt_get_all_student_list();
								foreach($student_list  as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->display_name.'</option>';
								}
								?>
							</select>
						</span>
					</div>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject" class="form-control validate[required,custom[description_validation]] text-input" maxlength="100" type="text" name="subject" >
							<label class="" for="subject"><?php esc_attr_e('Subject','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="message_body" id="message_body" maxlength="1000" class="textarea_height_60px form-control validate[required,custom[description_validation]] text-input"></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="subject"><?php esc_attr_e('Message Comment','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>

				<div  class="col-md-6 attachment_div">
					<div class="row">
						<div class="col-md-10">
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label>
									<div class="col-sm-12">
										<input class="col-md-12 input-file" name="message_attachment[]" type="file" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_new_attachment()" alt="" class="rtl_margin_top_15px more_attachment add_cirtificate float_right" id="add_more_sibling">
						</div>
					</div>
				</div>

				<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
									<input id="" type="checkbox"  value="1" name="smgt_message_mail_service_enable">
									<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send SMS','school-mgt');?></label>
									<input id="chk_sms_sent" type="checkbox"  value="1" name="smgt_sms_service_enable">
									<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 hmsg_message_none" id="hmsg_message_sent" >
					<div class="form-group input">
						<div class="col-md-12 note_border  margin_bottom_15px_res">
							<textarea name="sms_template" class="textarea_height_47px form-control validate[required]" maxlength="160"></textarea>
							<span class="txt-title-label"></span>
							<label class="text-area address active" for="sms_template"><?php esc_attr_e('SMS Text','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
			</div><!--row -->
		</div><!--user form -->
		<div class="form-body user_form"><!--user form -->
			<div class="row"><!--row -->
				<div class="col-sm-6">
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Message','school-mgt'); }else{ esc_attr_e('Send Message','school-mgt');}?>" name="save_message" class="btn btn-success save_message_selected_user save_btn rtl_margin_0px"/>
				</div>
			</div><!--row -->
		</div><!--user form -->
	</form><!-- form div -->
</div><!-- panel-body -->