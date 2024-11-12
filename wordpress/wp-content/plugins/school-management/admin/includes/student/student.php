<?php 
$custom_field_obj =new Smgt_custome_field;
$role='student'; 	
if($active_tab == 'addstudent') //-------  Add Student tab ---------//
{
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){ //-------- Edit Student -----//
		
		$edit=1;
		$user_info = get_userdata(esc_html($_REQUEST['student_id']));
		
	} ?>
	<script>
		function mj_smgt_custom_filed_fileCheck(obj)
			{	
				"use strict";
				var fileExtension = $(obj).attr('file_types');

				var fileExtensionArr = fileExtension.split(',');

				var file_size = $(obj).attr('file_size');

				

				var sizeInkb = obj.files[0].size/1024;

				

				if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)

				{										

					alert("Only "+fileExtension+" formats are allowed.");

					$(obj).val('');

				}	

			}
	</script>
    <div class="panel-body"><!------ panel body -------->
		<!--------- Student Form ---------->
        <form name="student_form" action="" method="post" class="form-horizontal" id="student_form" enctype='multipart/form-data'>
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<div class="header">	
				<h3 class="first_hed"><?php esc_html_e('Personal Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form"> <!--form Body div-->   
				<div class="row"><!--Row Div--> 
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input smgt_form_select">
						<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
						<?php if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>
						<select name="class_name" class="form-control validate[required] class_in_student max_width_100" id="class_list">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
							<?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{  
								?>
									<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
							<?php 
								} 	?>
						</select>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input smgt_form_select">
						<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
						<?php if($edit){ $sectionval=$user_info->class_section; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
						<select name="class_section" class="form-control max_width_100" id="class_section">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
							<?php
							if($edit)
							{
								foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
								{  
									?>
									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php 
								} 
							}?>
						</select>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="roll_id" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text" <?php if($edit){ ?>value="<?php  echo $user_info->roll_id;}elseif(isset($_POST['roll_id'])) echo $_POST['roll_id'];?>" name="roll_id">
								<label class="" for="roll_id"><?php esc_attr_e('Roll Number','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
								<label class="" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" <?php if($edit){ ?> value="<?php echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
								<label class="" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" <?php if($edit){ ?>value="<?php echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
								<label class="" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 res_margin_bottom_20px rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control">
								<div class="row padding_radio">
									<div class="input-group">
										<label class="custom-top-label" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
										<div class="d-inline-block gender_line_height_24px">
											<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
											<label class="radio-inline custom_radio">
											<input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?>
											</label>
											<label class="radio-inline custom_radio">
											<input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
											</label>
											<label class="radio-inline custom_radio">
											<input type="radio" value="other" class="tog validate[required]" name="gender"  <?php  checked( 'other', $genderval);  ?>/><?php esc_attr_e('Other','school-mgt');?> 
											</label>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="birth_date" class="form-control date_picker validate[required]" type="text"  name="birth_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])){ echo mj_smgt_getdate_in_input_box($_POST['birth_date']);}else{ echo mj_smgt_getdate_in_input_box(date('Y-m-d')); }?>" readonly>
								<label class="col-form-label date_label text-md-end col-sm-2 control-label" for="birth_date"><?php esc_attr_e('Date of Birth','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>	
					</div>	
				</div>
			</div>
			<div class="header">	
				<h3 class="first_hed"><?php esc_html_e('Contact Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form"> <!--Card Body div-->  
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text"  name="address" 
								<?php if($edit){ ?>value="<?php echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
								<label class="" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
								<?php if($edit){ ?>value="<?php echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
								<label class="" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
								<?php if($edit){ ?>value="<?php echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
								<label class="" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
							</div>
						</div>
					</div>
					<?php wp_nonce_field( 'save_teacher_admin_nonce' ); ?>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="zip_code" class="form-control validate[required,custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="zip_code" <?php if($edit){ ?>value="<?php echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
								<label class="" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="phonecode" name="phonecode" type="text" class="form-control validate[required] onlynumber_and_plussign" value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>" maxlength="5" disabled>
										<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>
									</div>											
								</div>
							</div>
							<div class="col-md-8 mobile_error_massage_left_margin">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="mobile_number" class="form-control margin_top_10_res text-input validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mobile_number"
										value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
										<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?><span class="required red">*</span></label>
									</div>
								</div>
							</div>
						</div>
					</div> 
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="phonecode" name="alter_mobile_number" type="text" class="form-control validate[required] onlynumber_and_plussign" value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>" maxlength="5" disabled>
										<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
									</div>											
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="alternet_mobile_number" class="form-control margin_top_10_res text-input" type="text"  name="alternet_mobile_number" 
										value="<?php if($edit){ echo $user_info->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
										<label for="userinput6"><?php esc_html_e('Alternate Mobile Number','school-mgt');?></label>
									</div>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
			<div class="header">	
				<h3 class="first_hed"><?php esc_html_e('Login Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form"> <!--Card Body div-->  
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="email" class="form-control validate[required,custom[email]] text-input student_email_id" maxlength="100" type="text"  name="email" <?php if($edit){ ?> value="<?php echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
								<label class="" for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
							</div>
							<div class="email_validation_div">
						        <div class="formError" style="opacity: 0.87; position: absolute; top: 33px; left: 482.5px; margin-top: 0px; display: block;"><div class="formErrorArrow formErrorArrowBottom"><div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div></div><div class="formErrorContent"><?php esc_html_e('Email id Already Exist.','school-mgt');?><br></div></div>
						    </div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password">
								<label class="" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
								<i class="fa fa-eye-slash" id="togglePassword"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="header">	
				<h3 class="first_hed"><?php esc_html_e('Profile Image','school-mgt');?></h3>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px ">	
								<label for="photo" class="custom-control-label custom-top-label ml-2 label_position_rtl"><?php esc_attr_e('Image','school-mgt');?></label>
								<div class="col-sm-12 display_flex">
									<input type="text" id="smgt_user_avatar_url" class="image_path_dots form-control" name="smgt_user_avatar" value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" readonly />
									<input id="upload_user_avatar_button" type="button" class="button upload_image_btn" style="float: right;" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />   
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<div id="upload_user_avatar_preview">
									
									<?php if($edit) 
									{
									if($user_info->smgt_user_avatar == "")
									{?>
									<img class="image_preview_css" alt="" src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>">
									<?php }
									else {
										?>
									<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar ); ?>" />
									<?php 
									}
									}
									else {
										?>
										<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb_new' ) ?>">
										<?php 
									}?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10">
					<?php echo  get_post_meta(get_the_ID()); ?>
				</div>
			</div>
			<!-- Custom Fields Data -->	
			<?php
			//--------- Get Module Wise Custom Field Data --------------//
			$custom_field_obj =new Smgt_custome_field;
			
			$module='student';	
			
			$compact_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
			
			if(!empty($compact_custom_field))
			{	
				?>		
				<div class="header">	
					<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Custom Fields','school-mgt');?></h3>
				</div>	
				<div class="form-body user_form">
					<div class="row">			
						<?php
						foreach($compact_custom_field as $custom_field)
						{
							if($edit)
							{
								$custom_field_id=$custom_field->id;
								
								$module_record_id=$_REQUEST['student_id'];
								
								$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
							}
							
							// Custom Field Validation // 
							$exa = explode('|',$custom_field->field_validation);
							$min = "";
							$max = "";
							$required = "";
							$red = "";
							$limit_value_min = "";
							$limit_value_max = "";
							$numeric = "";
							$alpha = "";
							$space_validation = "";
							$alpha_space = "";
							$alpha_num = "";
							$email = "";
							$url = "";
							$minDate="";
							$maxDate="";
							$file_types="";
							$file_size="";
							$datepicker_class="";
							foreach($exa as $key=>$value)
							{
								if (strpos($value, 'min') !== false)
								{
								$min = $value;
								$limit_value_min = substr($min,4);
								}
								elseif(strpos($value, 'max') !== false)
								{
								$max = $value;
								$limit_value_max = substr($max,4);
								}
								elseif(strpos($value, 'required') !== false)
								{
									$required="required";
									$red="*";
								}
								elseif(strpos($value, 'numeric') !== false)
								{
									$numeric="onlyNumberSp";
								}
								elseif($value == 'alpha')
								{
									$alpha="onlyLetterSp";
									$space_validation="space_validation";
								}
								elseif($value == 'alpha_space')
								{
									$alpha_space="onlyLetterSp";
								}
								elseif(strpos($value, 'alpha_num') !== false)
								{
									$alpha_num="onlyLetterNumber";
								}
								elseif(strpos($value, 'email') !== false)
								{
									$email = "email";
								}
								elseif(strpos($value, 'url') !== false)
								{
									$url="url";
								}
								elseif(strpos($value, 'after_or_equal:today') !== false )
								{
									$minDate=1;
									$datepicker_class='after_or_equal';
								}
								elseif(strpos($value, 'date_equals:today') !== false )
								{
									$minDate=$maxDate=1;
									$datepicker_class='date_equals';
								}
								elseif(strpos($value, 'before_or_equal:today') !== false)
								{	
									$maxDate=1;
									$datepicker_class='before_or_equal';
								}	
								elseif(strpos($value, 'file_types') !== false)
								{	
									$types = $value;													
								
									$file_types=substr($types,11);
								}
								elseif(strpos($value, 'file_upload_size') !== false)
								{	
									$size = $value;
									$file_size=substr($size,17);
								}
							}
							$option =$custom_field_obj->mj_smgt_getDropDownValue($custom_field->id);
							$data = 'custom.'.$custom_field->id;
							$datas = 'custom.'.$custom_field->id;
							if($custom_field->field_type =='text')
							{
								?>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input class="form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>>
											<label for="<?php echo $custom_field->id; ?>" class=""><?php esc_html_e($custom_field->field_label ,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>
										</div>
									</div>
								</div>
								<?php
							}
							elseif($custom_field->field_type =='textarea')
							{
								?>
								<div class="col-md-6 note_text_notice">	
									<div class="form-group input">
										<div class="col-md-12 note_border">
											<div class="form-field">
												<textarea rows="3" class="textarea_height_47px form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>"label="<?php echo $custom_field->field_label; ?>"><?php if($edit){ echo $custom_field_value; } ?></textarea>
												<span class="txt-title-label"></span>
												<label for="photo" class="text-area address"><?php esc_attr_e($custom_field->field_label,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>
											</div>
										</div>
									</div>
								</div>
								<?php 
							}
							elseif($custom_field->field_type =='date')
							{
								?>	
									<div class="col-md-6">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input class="form-control date_picker custom_datepicker <?php echo $datepicker_class; ?> hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]"<?php if($edit){ ?> value="<?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value);} ?>" <?php }else{ ?> value="<?php echo mj_smgt_getdate_in_input_box(date('Y-m-d')); ?><?php } ?>"id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">
												<label for="" class="date_label"><?php esc_html_e($custom_field->field_label ,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>
											</div>
										</div>
									</div>
								<?php 
							}
							elseif($custom_field->field_type =='dropdown')
							{
								?>	
								<div class="col-md-6 col-sm-6 input">
									<label for="photo" class="ml-1 custom-top-label top"><?php esc_attr_e($custom_field->field_label,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>
									<select class="form-control standard_category line_height_30px  hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]"	id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">
										<option value=""><?php esc_attr_e( 'Select', 'school-mgt' ); ?></option>
										<?php
										if(!empty($option))
										{															
											foreach ($option as $options)
											{
												?>
												<option value="<?php echo $options->option_label; ?>" <?php if($edit){ echo selected($custom_field_value,$options->option_label); } ?>> <?php echo $options->option_label; ?></option>
												<?php
											}
										}
										?>
									</select>                               
								</div>
								<?php 
							}
							elseif($custom_field->field_type =='checkbox')
							{
								?>	
								<div class="col-md-6 mb-3 smgt_main_custome_field">
									<div class="form-group">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="">
													<label class="custom-top-label margin_left_0"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
													<?php
													if(!empty($option))
													{
														foreach ($option as $options)
														{ 
															if($edit)
															{
																$custom_field_value_array=explode(',',$custom_field_value);
															}
																?>	
																<label class="me-2">
																	<input type="checkbox" value="<?php echo $options->option_label; ?>"  <?php if($edit){  echo checked(in_array($options->option_label,$custom_field_value_array)); } ?> class="hideattar<?php echo $custom_field->form_name; ?><?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>][]" >&nbsp;&nbsp;<span class="span_left_custom" style="margin-bottom: -5px;"><?php echo $options->option_label; ?></span>
																</label>

																<?php
														}
													}
													?>
												</div>												
											</div>
										</div>
									</div>
								</div>
								<?php 
							}
							elseif($custom_field->field_type =='radio')
							{
								?>
								<div class="col-md-6 mb-3 rtl_margin_top_15px">
									<div class="form-group">
										<div class="col-md-12 form-control">
											<div class="row padding_radio">
												<div class="input-group">
													<label class="custom-top-label margin_left_0"><?php esc_html_e($custom_field->field_label,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>													
													<?php
													if(!empty($option))
													{
														foreach ($option as $options)
														{
															?>
															<div class="d-inline-block">
																<label class="radio-inline">
																	<input type="radio" value="<?php echo $options->option_label; ?>" <?php if($edit){ echo checked( $options->option_label, $custom_field_value); } ?> name="custom[<?php echo $custom_field->id; ?>]"  class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" id="<?php echo $options->option_label; ?>">
																	<?php echo $options->option_label; ?>
																</label>&nbsp;&nbsp;
															</div>
															<?php
														}
													}
													?>
												</div>												
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							elseif($custom_field->field_type =='file')
							{
								?>	
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">	
											<label for="photo" class="custom-control-label custom-top-label ml-2"><?php esc_attr_e($custom_field->field_label,'school-mgt');?><span class="required red"><?php echo $red; ?></span></label>
											<div class="col-sm-12 display_flex">
												<input type="hidden" name="hidden_custom_file[<?php echo $custom_field->id; ?>]" value="<?php if($edit){ echo $custom_field_value; } ?>">
												<input type="file"  onchange="mj_smgt_custom_filed_fileCheck(this);" Class="hideattar<?php echo $custom_field->form_name; if($edit){ if(!empty($required)){ if($custom_field_value==''){ ?> validate[<?php echo $required; ?>] <?php } } }else{ if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } } ?>" name="custom_file[<?php echo $custom_field->id;?>]" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?> id="<?php echo $custom_field->id; ?>" file_types="<?php echo $file_types; ?>" file_size="<?php echo $file_size; ?>">
											</div>
											<?php
											if(!empty($custom_field_value))
											{
												?>
												<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
													<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$custom_field_value; ?>">
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
						}	
						?>	 
					</div>
				</div>
				<?php
			}
			?>
			<!------- Save Student Button ---------->
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Student','school-mgt'); }else{ esc_attr_e('Add Student','school-mgt');}?>" name="save_student" class="btn btn-success save_btn"/>
					</div>
				</div>
			</div>
        </form><!--------- Student Form ---------->
    </div><!------ panel body -------->
    <?php 
}
?>