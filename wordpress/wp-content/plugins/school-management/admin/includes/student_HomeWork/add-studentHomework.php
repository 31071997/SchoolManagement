<?php 			
$class_obj=new Smgt_Homework();
?>
<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$objj=new Smgt_Homework();
		$classdata= $objj->mj_smgt_get_edit_record($_REQUEST['homework_id']);
			
	} 
?>
<script>
	$(document).ready(function ()
	{
		$('#homework_form_admin').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('.file_validation ').change(function () 
		{
			var val = $(this).val().toLowerCase();
			var regex = new RegExp("(.*?)\.(docx|doc|pdf|ppt|jpg|jpeg|png|xls|xlsx|ppt|pptx|gif)$");
			 if(!(regex.test(val)))
			{
				$(this).val('');
				alert("<?php _e('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.','school-mgt');?>");
			} 
		}); 
	});
</script> 	
<div class="panel-body"><!-- panel body div start-->	
	<form name="homework_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="homework_form_admin">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Homework Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="title" class="form-control validate[required,custom[address_description_validation]]" maxlength="100" type="text" value="<?php if($edit){ echo $classdata->title;}?>" name="title">
							<label class="" for="class_name"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>

					<?php if($edit){ $classval=$classdata->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>
					<select name="class_name" class="form-control validate[required] max_width_100" id="class_list">
						<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
						<?php
							foreach(mj_smgt_get_allclass() as $classdata1)
							{  
							?>
							<option value="<?php echo $classdata1['class_id'];?>" <?php selected($classval, $classdata1['class_id']);  ?>><?php echo $classdata1['class_name'];?></option>
						<?php }?>
					</select>
				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<?php if($edit){ $sectionval=$classdata->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control max_width_100" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($classdata->class_name) as $sectiondata)
							{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>
				</div>


				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field">*</span></label>
					<?php
						$subject = ($edit)?mj_smgt_get_subject_by_classid($classval):array();
					?>
					<select name="subject_id" id="subject_list" class="form-control validate[required] text-input max_width_100">
						<?php
						if($edit)
						{
								foreach($subject as $record)
								{
									$select = ($record->subid == $classdata->subject)?"selected":"";
								?>
									<option value="<?php echo $record->subid;?>" <?php echo $select; ?>><?php echo $record->sub_name; ?></option>
								<?php
								}
						}
						else
						{
							?>
							<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Homework Document','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<?php
				if($edit)
				{
					$doc_data=json_decode($classdata->homework_document);
					?>
					<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text"  name="document_name" id="title_value" value="<?php if(!empty($doc_data[0]->title)) { echo esc_attr($doc_data[0]->title);}elseif(isset($_POST['document_name'])) echo esc_attr($_POST['document_name']);?>"  class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
								<label class="" for="Exam Syllabu"><?php esc_attr_e('Documents Title','school-mgt');?></label>	
							</div>	
						</div>
					</div>
					<div class="col-md-6">	
						<div class="form-group input">
							<div class="col-md-12 form-control res_rtl_height_50px">	
								<div class="col-sm-12">	
									<input type="file" name="homework_document" class="file_validation input-file"/>						
									<input type="hidden" name="old_hidden_homework_document" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['homework_document'])) echo esc_attr($_POST['homework_document']);?>">
								</div>
								<?php
								if(!empty($doc_data[0]->value))
								{
									?>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" record_id="<?php echo $classdata->homework_id;?>">
										<i class="fa fa-download"></i>&nbsp;&nbsp;<?php esc_attr_e('Download','school-mgt');?></a>
									</div>
									<?php
								}
									?>
							</div>
						</div>
					</div>
					<?php 
				}
				else 
				{
					?>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text"  name="document_name" id="title_value"    class="form-control validate[custom[description_validation],maxSize[50]] margin_cause"/>
								<label class="" for="Exam Syllabu"><?php esc_attr_e('Documents Title','school-mgt');?></label>
							</div>	
						</div>
					</div>
					<div class="col-md-6">	
						<div class="form-group input">
							<div class="col-md-12 form-control res_rtl_height_50px">	
								<div class="col-sm-12">	
									<input type="file" name="homework_document" class="col-md-12 file_validation input-file ">
								</div>
							</div>
						</div>
					</div>
					<?php 
				}
				?>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="sdate" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($classdata->submition_date);}?>" class="datepicker date_picker form-control validate[required] text-input" type="text" name="sdate" readonly>
							<label class="date_label" for="class_capacity"><?php esc_attr_e('Submission Date','school-mgt');?> <span class="require-field">*</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Homework Content','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 input ">
					<label class="ml-1 custom-top-label top" for="class_capacity"><?php esc_attr_e('Content','school-mgt');?> </label>
					<div class="form-control">
							<?php 
							$setting=array(
							'media_buttons' => false
							);
							
						if(!empty($classdata))
						{
							$content=$classdata->content;
						}
						else
						{
							$content="";
						}
						
							wp_editor(isset($edit)?stripslashes($content) : '','content',$setting); ?>
					</div>
				</div>

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
									<label class="custom-top-label label_right_position" for="smgt_enable_homework_mail"><?php esc_attr_e('Send Mail To Parents & Students','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_homework_mail"  value="1" <?php echo checked(get_option('smgt_enable_homework_mail'),'yes');?>/> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio rtl_relative_position">
								<div class="">
									<label class="custom-top-label label_right_position" for="smgt_enable_homework_sms_student"><?php esc_attr_e('Enable Send SMS To Student','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_homework_sms_student"  value="1" /> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mb-3 rtl_margin_bottom_0px">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio rtl_relative_position">
								<div class="">
									<label class="custom-top-label label_right_position" for="smgt_enable_homework_sms_parent "><?php esc_attr_e('Enable Send SMS To Parent','school-mgt');?></label>
									<input type="checkbox" class="check_box_input_margin" name="smgt_enable_homework_sms_parent"  value="1" /> <?php esc_attr_e('Enable','school-mgt');?>
								</div>												
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>

			<?php wp_nonce_field( 'save_homework_admin_nonce' ); ?>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Homework','school-mgt'); }else{ esc_attr_e('Add Homework','school-mgt');}?>" name="Save_Homework" class="save_btn " />
					</div>
				</div>
			</div>
	</form>
</div><!-- End panel body div start-->	