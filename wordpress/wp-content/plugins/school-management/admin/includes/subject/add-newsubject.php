<?php 	
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{	
	$edit=1;
	$subject=mj_smgt_get_subject($_REQUEST['subject_id']);
}
?>
<div class="panel-body">
    <form name="student_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="subject_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Subject Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">	
				<div class="col-md-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject_code"class="form-control validate[required,custom[onlyNumberSp],maxSize[8],min[0]] text-input" type="text" maxlength="50" value="<?php if($edit){ echo $subject->subject_code;}?>" name="subject_code">
							<label for="userinput1" class=""><?php esc_html_e('Subject Code','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject_name" class="form-control validate[required,custom[address_description_validation]] margin_top_10_res" type="text" maxlength="50" value="<?php if($edit){ echo $subject->sub_name;}?>" name="subject_name">
							<label for="userinput1" class=""><?php esc_html_e('Subject Name','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="required">*</span></label>
					<select name="subject_class" class="form-control validate[required] width_100 class_by_teacher" id="class_list">
						<option value=""><?php echo esc_attr_e( 'Select Class', 'school-mgt' ) ;?></option>
						<?php $classval='';
						if($edit){  
							$classval=$subject->class_id; 
							foreach(mj_smgt_get_allclass() as $class)
							{ ?>
							<option value="<?php echo $class['class_id'];?>" <?php selected($class['class_id'],$classval);  ?>>
							<?php echo mj_smgt_get_class_name($class['class_id']);?></option> 
						<?php }
						}else
						{
							foreach(mj_smgt_get_allclass() as $classdata)
							{ ?>
							<option value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$classval);  ?>><?php echo $classdata['class_name'];?></option> 
						<?php }
						}
						?>
					</select>                            
				</div>
				<?php wp_nonce_field( 'save_subject_admin_nonce' ); ?>
				<div class="col-md-6 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
					<?php if($edit){ $sectionval=$subject->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control width_100" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($subject->class_id) as $sectiondata)
							{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>                       
				</div>
				<div class="col-md-6 rtl_margin_top_15px teacher_list_multiselect">
					<div class="col-sm-12 multiselect_validation_teacher smgt_multiple_select rtl_padding_left_right_0px res_rtl_width_100">
						<?php 
						$teachval = array();
						if($edit)
						{      
							$teachval = mj_smgt_teacher_by_subject($subject);  
							$teacherdata_array	= 	mj_smgt_get_teacher_by_class_id($subject->class_id);	
						}
						else
						{
							$teacherdata_array=mj_smgt_get_usersdata('teacher');
							
						}
						?>
						<select name="subject_teacher[]" multiple="multiple" id="subject_teacher" class="form-control validate[required] teacher_list">               
						<?php 
								foreach($teacherdata_array as $teacherdata)
								{ ?>
								<option value="<?php echo $teacherdata->ID;?>" <?php echo $teacher_obj->mj_smgt_in_array_r($teacherdata->ID, $teachval) ? 'selected' : ''; ?>><?php echo $teacherdata->display_name;?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="col-md-6 padding_top_15px_res">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject_edition" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $subject->edition;}?>" name="subject_edition">
							<label for="userinput1" class=""><?php esc_html_e('Edition','school-mgt');?></label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject_author" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="100" type="text" value="<?php if($edit){ echo $subject->author_name;}?>" name="subject_author">
							<label for="userinput1" class=""><?php esc_html_e('Author Name','school-mgt');?></label>
						</div>
					</div>
				</div>
				<?php
				if($edit)
				{
					$syllabus=$subject->syllabus;
					?>
					<div class="col-md-6">	
						<div class="form-group input"> 
							<div class="col-md-12 form-control res_rtl_height_50px">	
								<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl"><?php _e('Syllabus','school-mgt');?></label>
								<div class="col-sm-12">
									<input type="file" accept=".pdf" name="subject_syllabus" class='file_validation'  id="subject_syllabus"/>	
									<input type="hidden" name="sylybushidden" value="<?php if($edit){ echo $subject->syllabus;} else echo "";?>">
								</div>
								<?php if(!empty($syllabus))
								{ 
									?>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$syllabus; ?>" record_id="<?php echo $subject->subject;?>"><i class="fa fa-download"></i>  <?php echo esc_html_e("Download" , "school-mgt");?></a>
									</div>
									<?php	
								} ?>
							</div>
						</div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="col-md-6">	
						<div class="form-group input">
							<div class="col-md-12 form-control res_rtl_height_50px">	
								<label for="" class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl"><?php _e('Syllabus','school-mgt');?></label>
								<div class="col-sm-12">
									<input type="file" accept=".pdf" class="col-md-12 file_validation" name="subject_syllabus"  id="subject_syllabus"/>				 
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="col-md-6 rtl_margin_top_15px">
					<div class="form-group">
						<div class="col-md-12 form-control input_height_50px checkbox_input_height_47px">
							<div class="row padding_radio">
								<div class="input-group input_checkbox">
									<label class="custom-top-label"><?php esc_html_e('Send Email to Teacher','school-mgt');?></label>													
									<div class="checkbox checkbox_lebal_padding_8px">
										<label>
											<input id="chk_subject_mail" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">&nbsp;&nbsp;<?php esc_html_e('Enable','school-mgt');?>
										</label>
									</div>
								</div>
							</div>												
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-body user_form padding_top_15px_res">
			<div class="row">	
				<div class="col-sm-6">
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Subject','school-mgt'); }else{ esc_attr_e('Add Subject','school-mgt');}?>" name="subject" class="btn btn-success save_btn teacher_for_alert"/>
				</div>
			</div>
		</div>
    </form>
</div>
<?php

?>