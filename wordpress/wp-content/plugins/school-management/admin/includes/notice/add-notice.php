<?php
$logo = get_option('smgt_system_logo');
$system_color = get_option('smgt_system_color_code');
?>
<?php
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$post = get_post($_REQUEST['notice_id']);
	}
?>
<div class="panel-body"> <!-- panel-body -->
	<form name="class_form" action="" method="post" class="form-horizontal" id="notice_form"><!-- notice form -->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="notice_id"   value="<?php if($edit){ echo $post->ID;}?>"/> 
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e(' Notice Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="notice_title" class="form-control validate[required,custom[description_validation]] text-input" maxlength="100" type="text" value="<?php if($edit){ echo $post->post_title;}?>" name="notice_title">
							<label class="" for="notice_title"><?php esc_attr_e('Notice Title','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="notice_content" class="textarea_height_60px form-control validate[custom[description_validation]]" maxlength="1000" id="notice_content"><?php if($edit){ echo $post->post_content;}?></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="notice_content"><?php esc_attr_e('Notice Comment','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>				
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="notice_Start_date" class="form-control date_picker validate[required] text-input" type="text" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime(get_post_meta($post->ID,'start_date',true))));}else{echo mj_smgt_getdate_in_input_box(date("Y-m-d"));} ?>" name="start_date" readonly>
							<label class="date_label" for="notice_content"><?php esc_attr_e('Notice Start Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_notice_admin_nonce' ); ?>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="notice_end_date" class="form-control date_picker validate[required] text-input" type="text" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime(get_post_meta($post->ID,'end_date',true))));}else{echo mj_smgt_getdate_in_input_box(date("Y-m-d"));} ?>" name="end_date" readonly>
							<label class="date_label" for="notice_content"><?php esc_attr_e('Notice End Date','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-md-6 input">
					<label class="ml-1 custom-top-label top" for="notice_for"><?php esc_attr_e('Notice For','school-mgt');?></label>
					<select name="notice_for" id="notice_for" class="form-control notice_for_ajax max_width_100">
						<option value = "all"><?php esc_attr_e('All','school-mgt');?></option>
						<option value="teacher" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'teacher');?>><?php esc_attr_e('Teacher','school-mgt');?></option>
						<option value="student" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'student');?>><?php esc_attr_e('Student','school-mgt');?></option>
						<option value="parent" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'parent');?>><?php esc_attr_e('Parent','school-mgt');?></option>
						<option value="supportstaff" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'supportstaff');?>><?php esc_attr_e('Support Staff','school-mgt');?></option>
					</select>	
				</div>
				<!-- <div id="smgt_select_class"> -->
				<div class="col-md-6 input" id="smgt_select_class">
					<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
					<?php 
					if($edit)
					{ 
						$classval=get_post_meta( $post->ID, 'smgt_class_id',true); 
					}
					elseif(isset($_POST['class_id']))
					{
						$classval=$_POST['class_id'];}else{$classval='';
					}?>
					<select name="class_id"  id="class_list" class="form-control max_width_100">
						<option value="all"><?php esc_attr_e('All','school-mgt');?></option>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{  
						?>
							<option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
							<?php 
						}?>
					</select>
				</div>
				<!-- </div> -->

				<div class="col-md-6 input" id="smgt_select_section">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<?php 
					if($edit)
					{ 
					$sectionval=get_post_meta( $post->ID, 'smgt_section_id',true); 
					}elseif(isset($_POST['class_section']))
					{
						$sectionval=$_POST['class_section'];}else{$sectionval='';
					}?>
					<select name="class_section" class="form-control max_width_100" id="class_section">
						<option value=""><?php esc_attr_e('All','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($classval) as $sectiondata)
							{  ?>
							<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>
				</div>
				<?php
				if(!$edit)
				{ 
				?>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 mb-3 rtl_margin_top_15px rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
									<input id="chk_sms_sent_mail" class="check_box_input_margin" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">
									<lable><?php esc_attr_e('Mail','school-mgt');?></label>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 mb-3 rtl_margin_top_15px rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send SMS','school-mgt');?></label>
									<input id="chk_sms_sent" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
									<lable> <?php esc_attr_e('SMS','school-mgt');?></label>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
				<div class="col-md-6 hmsg_message_none" id="hmsg_message_sent" >
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<textarea name="sms_template" class="textarea_height_47px form-control validate[required]" maxlength="160"></textarea>
							<span class="txt-title-label"></span>
							<label class="text-area address active" for="sms_template"><?php esc_attr_e('SMS Text','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>		
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">          	
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Notice','school-mgt'); }else{ esc_attr_e('Add Notice','school-mgt');}?>" name="save_notice" class="btn btn-success save_btn" />
					</div>    
				</div>
			</div>     
		</div>
	</form><!-- notice form -->
</div><!-- panel-body -->