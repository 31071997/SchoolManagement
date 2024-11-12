<div class="panel-body overflow-hidden"><!-- panel-body -->
	<form name="class_form" action="" method="post" class="form-horizontal" id="notification_form">
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Notification Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-md-6 input">
					<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
					<select name="class_id"  id="notification_class_list_id" class="form-control max_width_100">
						<option value="All"><?php esc_attr_e('All','school-mgt');?></option>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{  
							?>
							<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
							<?php 
						}?>
					</select>
				</div>
			
				<div class="col-md-6 input notification_class_section_id">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<select name="class_section" class="form-control max_width_100" id="notification_class_section_id">
						<option value="All"><?php esc_attr_e('All','school-mgt');?></option>
					</select>
				</div>
				<div class="col-md-6 input">
					<label class="ml-1 custom-top-label top"><?php esc_attr_e('Select Users','school-mgt');?></label>
					<span class="notification_user_display_block">
						<select name="selected_users" id="notification_selected_users" class="form-control max_width_100">
						<option value="All"><?php esc_attr_e('All','school-mgt');?></option>				
						</select>
					</span>
				</div>
				<?php wp_nonce_field( 'save_notice_admin_nonce' ); ?>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="title" class="form-control validate[required,custom[description_validation]] text-input" type="text" maxlength="100" name="title" >
							<label class="" for="subject"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="message_body" id="message_body" maxlength="1000" class="textarea_height_60px form-control validate[required,custom[description_validation]] text-input"></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="message"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>		
			</div>
		</div>  
		
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">    
					<input type="submit" value="<?php esc_html_e('Add Notification','school-mgt');?>" name="save_notification" class="btn btn-success save_btn"/>
				</div>
			</div>
		</div>     
	</form>
</div><!-- panel-body -->