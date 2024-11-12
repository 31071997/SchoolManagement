<?php
	// This is Dashboard at admin side!!!!!!!!!
	$role='student_temp';
?>
<!--Group POP up code -->
<!-- <div class="popup-bg">
	<div class="overlay-content admission_popup">
		<div class="modal-content">
			<div class="category_list">
			</div>
		</div>
	</div>
</div> -->
<div class="popup-bg">
		<div class="overlay-content max_height_overflow">
			<div class="modal-content">
				
				<div class="result"></div>
				<div class="view-parent"></div>
				<div class="category_list">
				</div>
			</div>
		</div>    
	</div>
	
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>
<script>
	jQuery(document).ready(function($)
	{
		jQuery("body").on("change", ".input-file[type=file]", function ()
		{
			"use strict";

			var elmId = $(this).attr("name");
			var file = this.files[0];
			var ext = $(this).val().split('.').pop().toLowerCase();
			//Extension Check
			if($.inArray(ext, [,'pdf','doc','docx','gif','png','jpg','jpeg','']) == -1)
			{
				alert('Only pdf,doc,docx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
				$(this).replaceWith('<input class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file" name="'+elmId+'" value=""  type="file" />');
				return false;
			}
			//File Size Check
			if (file.size > 20480000)
			{
				alert(language_translate2.large_file_Size_alert);
				$(this).replaceWith('<input class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file" name="'+elmId+'" value=""  type="file" />');
				return false;
			}
		});
		jQuery('.birth_date').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			maxDate : 0,
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+25',
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			},
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "/" + year);
			}
		});
		$('.sdate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",});
		$('.edate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",});
		jQuery('#admission_date').datepicker({
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			changeMonth: true,
			changeYear: true,
			yearRange:'-10:+10',
			beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			},
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "/" + year);
			}
		});
	});
	function add_more_siblings()
	{
		var click_val = $(".click_value").val();
		var curr_data = {
			action: 'mj_smgt_load_siblings_dropdown',
			click_val: click_val,
			dataType: 'json'
		};
		$.post(smgt.ajax, curr_data, function(response)
		{
			var value =  parseInt(click_val) + 1;
			$(".click_value").val(value);
			$("#sibling_div").append(response);
		});
   	}

	function deleteParentElement(n)
	{
		var alert = confirm(language_translate2.delete_record_alert);
			if (alert == true){
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}
   	}
</script>
<?php
if($active_tab == 'admission_form')
{
	
	?>
	
	<script>
		jQuery(document).ready(function($)
		{
		});
	</script>
	<?php
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$student_data = get_userdata($_REQUEST['id']);
		$user_ID = (int)$_REQUEST['id'];
		$key = 'status';
		$single = true;
		$user_status = get_user_meta( $user_ID, $key, $single );
		$sibling_data = $student_data->sibling_information;
		$sibling = json_decode($sibling_data);
	} ?>
	<div class="panel-body"><!-------- penal body -------->
		<form name="admission_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="admission_form"><!------ Form End ----->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<input type="hidden" name="user_id" value="<?php if($edit){ echo $_REQUEST['id'];}?>"  />
			<input type="hidden" name="status" value="<?php if($edit){ echo $user_status;}?>"  />

			<!--- Hidden User and password --------->
			<input id="username" type="hidden"  name="username">
			<input id="password" type="hidden"  name="password">
			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Admission Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form"> <!------  Form Body -------->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="admission_no" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $student_data->admission_no;}elseif(isset($_POST['admission_no'])){ echo mj_smgt_generate_admission_number(); }else{ echo mj_smgt_generate_admission_number(); }?>"  name="admission_no">
								<label for="userinput1" class=""><?php esc_html_e('Admission Number','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="admission_date" class="form-control date_picker validate[required]" type="text"  name="admission_date" readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->admission_date);}elseif(isset($_POST['admission_date'])){ echo mj_smgt_getdate_in_input_box($_POST['admission_date']); }else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d")); } ?>">
								<label for="userinput1" class="date_label"><?php esc_html_e('Admission Date','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<?php
					if(get_option("smgt_admission_fees") == "yes")
					{   $fees_id = get_option('smgt_admission_amount');
				        $obj_fees= new Smgt_fees();
						$amount=$obj_fees->mj_smgt_get_single_feetype_data_amount($fees_id);
						if(!empty($amount))
						{
						?>
							<div class="col-md-6 error_msg_left_margin">
								<div class="form-group input">
									<div class="col-md-12 form-control">
										<input id="admission_fees" name="admission_fees" disabled class="form-control" type="text" readonly value="<?php echo mj_smgt_get_currency_symbol() .' '. $amount ?>">
										<label for="userinput1" class="active"><?php esc_html_e('Admission Fees','school-mgt');?><span class="required">*</span></label>
									</div>
								</div>
							</div>
							<input id="admission_fees" class="form-control" type="hidden" name="admission_fees_id"  value="<?php echo $fees_id; ?>">
							<input id="" class="form-control" type="hidden" name="admission_fees_amount" value="<?php echo $amount;?>">
						<?php
						}
					}
					?>
				</div>
			</div> <!------  Form Body -------->

			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Student Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="first_name" value="<?php if($edit){ echo $student_data->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>">
								<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  name="middle_name"  value="<?php if($edit){ echo $student_data->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>">
								<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="last_name" value="<?php if($edit){ echo $student_data->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>">
								<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="birth_date" class="form-control date_picker validate[required] birth_date" type="text"  name="birth_date"  readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->birth_date);}elseif(isset($_POST['birth_date'])) echo mj_smgt_getdate_in_input_box($_POST['birth_date']); else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d")); }?>">
								<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 margin_bottom_15px_res rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control">
								<div class="row padding_radio">
									<div class="input-group">
										<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?><span class="required">*</span></label>
										<div class="d-inline-block">
											<?php $genderval = "male"; if($edit){ $genderval=$student_data->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
											<input type="radio"  value="male" name="gender"  class="custom-control-input" <?php  checked( 'male', $genderval);  ?>  id="male">
											<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
											&nbsp;&nbsp;<input type="radio" value="female"  name="gender" <?php  checked( 'female', $genderval);  ?> class="custom-control-input" id="female">
											<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
											&nbsp;&nbsp;<input type="radio" value="other"  name="gender" <?php  checked( 'other', $genderval);  ?> class="custom-control-input" id="other">
											<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="120" type="text"  name="address" value="<?php if($edit){ echo $student_data->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
								<label for="userinput1"><?php esc_html_e('Address','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $student_data->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
								<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $student_data->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
								<label for="userinput1"><?php esc_html_e('City','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="zip_code" class="form-control validate[required,custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="zip_code" value="<?php if($edit){ echo $student_data->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
								<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phonecode">
										<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?><span class="required red">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-8 mobile_error_massage_left_margin">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="mobile_number" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number" value="<?php if($edit){ echo $student_data->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
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
										<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="alter_mobile_number">
										<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group input margin_bottom_0">
									<div class="col-md-12 form-control">
										<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number" value="<?php if($edit){ echo $student_data->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
										<label for="userinput6"><?php esc_html_e('Alternate Mobile Number','school-mgt');?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="email" class="form-control validate[required,custom[email]] text-input email" maxlength="100" type="text"  name="email" value="<?php if($edit){ echo $student_data->user_email;}elseif(isset($_POST['user_email'])) echo $_POST['user_email'];?>">
								<label for="userinput1"><?php esc_html_e('Email','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="preschool_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="preschool_name" value="<?php if($edit){ echo $student_data->preschool_name;}elseif(isset($_POST['preschool_name'])) echo $_POST['preschool_name'];?>">
								<label for="userinput1"><?php esc_html_e('Previous School','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php wp_nonce_field( 'save_admission_form' ); ?>
			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Siblings Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-md-12 form-control input_height_50px">
								<div class="row padding_radio">
									<div class="input-group input_checkbox">
										<label class="custom-top-label"><?php esc_html_e('Siblings','school-mgt');?></label>
										<div class="checkbox checkbox_lebal_padding_8px">
											<label>
												<input type="checkbox" id="chkIsTeamLead"
												<?php
												if($edit)
												{
													$sibling_data = $student_data->sibling_information;
													$sibling = json_decode($sibling_data);
													if(!empty($student_data->sibling_information))
													{
														foreach ($sibling as $value)
														{
															if(!empty($value->siblingsclass) && !empty($value->siblingsstudent))
															{?> checked <?php }
														}
													}
												 } ?>/>&nbsp;&nbsp;<?php esc_html_e('In case of any sibling ? click here','school-mgt');?>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<?php
			if($edit)
			{
				if(!empty($student_data->sibling_information))
				{
					$sibling_data = $student_data->sibling_information;

					$sibling = json_decode($sibling_data);

					if(!empty($sibling))
					{
						$count_array=count($sibling);
					}
					else
					{
						$count_array=0;
					}
					$i=1;
					?>
					<div id="sibling_div" class="sibling_div_none">
						<?php
						foreach ($sibling as $value)
						{
							?>
							<script>
							jQuery(document).ready(function($)
							{
								$("body").on("change", "#sibling_class_change_<?php echo $i; ?>", function(event)
								{
									$('#sibling_student_list_<?php echo $i; ?>').html('');

									var selection = $(this).val();
									var optionval = $(this);
									var curr_data = {
										action: 'mj_smgt_load_user',
										class_list: selection,
										dataType:'json'
									};

									$.post(smgt.ajax, curr_data, function(response)
									{
										$('#sibling_student_list_<?php echo $i;?>').append(response);
									});
								});
								$("body").on("change", "#sibling_class_change_<?php echo $i; ?>", function(){
									$('#sibling_class_section_<?php echo $i; ?>').html('');
									$('#sibling_class_section_<?php echo $i; ?>').append('<option value="remove">Loading..</option>');
									var selection = $("#sibling_class_change_<?php echo $i; ?>").val();
									var optionval = $(this);
									var curr_data = {
										action: 'mj_smgt_load_class_section',
										class_id: selection,
										dataType: 'json'
									};
									$.post(smgt.ajax, curr_data, function(response)
									{
										$("#sibling_class_section_<?php echo $i;?> option[value='remove']").remove();
										$('#sibling_class_section_<?php echo $i;?>').append(response);
									});
								});
								$("#sibling_class_section_<?php echo $i;?>").on('change',function()
								{
									$('#sibling_student_list_<?php echo $i;?>').html('');
										var selection = $(this).val();
										var class_id = $("#sibling_class_change_<?php echo $i; ?>").val();
										var optionval = $(this);
										var curr_data = {
											action: 'mj_smgt_load_section_user',
											section_id: selection,
											class_id: class_id,
											dataType: 'json'
										};

										$.post(smgt.ajax, curr_data, function(response)
										{
											$('#sibling_student_list_<?php echo $i; ?>').append(response);
										});
								});
							});
							</script>
							<input type="hidden" id="admission_sibling_id" name="admission_sibling_id" value="<?php echo $count_array; ?>"  />
							<div class="form-body user_form">
								<div class="row">
									<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 input smgt_form_select">
										<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
										<select name="siblingsclass[]" class="form-control validate[required] class_in_student max_width_100" id="sibling_class_change_<?php echo $i;?>">
											<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
											<?php
												foreach(mj_smgt_get_allclass() as $classdata)
												{
													?>
													<option value="<?php echo $classdata['class_id'];?>" <?php selected($value->siblingsclass, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
													<?php
												} 	?>
										</select>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input smgt_form_select">
										<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
										<select name="siblingssection[]" class="form-control max_width_100" id="sibling_class_section_<?php echo $i;?>">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
											<?php
											if($edit)
											{
												foreach(mj_smgt_get_class_sections($value->siblingsclass) as $sectiondata)
												{
													?>
													<option value="<?php echo $sectiondata->id;?>" <?php selected($value->siblingssection,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
													<?php
												}
											}
											?>
										</select>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input class_section_hide">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?></label>
										<select name="siblingsstudent[]" id="sibling_student_list_<?php echo $i;?>" class="form-control max_width_100">
											<option value=""><?php esc_attr_e('Select Student','school-mgt');?></option>
											<?php
											if($edit)
											{
												if(mj_smgt_student_display_name_with_roll($value->siblingsstudent) != 'N/A'){
													echo '<option value="'.$value->siblingsstudent.'" '.selected($value->siblingsstudent,$value->siblingsstudent).'>'.mj_smgt_student_display_name_with_roll($value->siblingsstudent).'</option>';
												}
											}
											?>
										</select>
									</div>
									<input type="hidden"  class="click_value" name="" value="<?php echo $count_array + 1; ?>">
									<?php
									if($i == 1)
									{
									?>
									<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
										<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_more_siblings()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
									</div>
									<?php
									}
									else
									{
										?>
										<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
											<input type="image" onclick="deleteParentElement(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"; ?>" class="rtl_margin_top_15px remove_cirtificate input_btn_height_width">
										</div>
										<?php
									}
									?>

								</div>
							</div>
							<?php
							$i++;
						}
						?>
					</div>
					<?php

				}
			}
			else
			{
				?>
				<div id="sibling_div">
					<div class="form-body user_form">
						<div class="row">
							<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 input smgt_form_select">
								<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<select name="siblingsclass[]" class="form-control validate[required] class_in_student max_width_100" id="sibling_class_change">
									<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
										<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{
											?>
											<option value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
											<?php
										}
										?>
								</select>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input smgt_form_select">
								<label class="custom-top-label lable_top top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
								<select name="siblingssection[]" class="form-control max_width_100" id="sibling_class_section">
									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
								</select>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 input class_section_hide">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
								<select name="siblingsstudent[]" id="sibling_student_list" class="form-control max_width_100 validate[required]">
									<option value=""><?php esc_attr_e('Select Student','school-mgt');?></option>
								</select>
							</div>
							<input type="hidden"  class="click_value" name="" value="1">
							<div class="col-md-1 col-sm-3 col-xs-12 width_20px_res">
								<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_more_siblings()" alt="" class="rtl_margin_top_15px add_cirtificate" id="add_more_sibling">
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<div class="header">
				<h3 class="first_hed"><?php esc_html_e('Family Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6 margin_bottom_20px rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control ">
								<div class="row padding_radio">
									<div class="input-group ">
										<label class="custom-top-label margin_left_0"><?php esc_html_e('Parental Status','school-mgt');?></label>
										<div class="d-inline-block family_information">
											<?php $pstatus = "Both"; if($edit){ $pstatus=$student_data->parent_status; }elseif(isset($_POST['pstatus'])) {$pstatus=$_POST['pstatus'];}?>
											<input type="radio" name="pstatus" class="tog" value="Father"  id="sinfather" <?php  checked( 'Father', $pstatus);  ?>>
											<label class="custom-control-label margin_right_20px" for="Father" ><?php esc_html_e('Father','school-mgt');?></label>
											&nbsp;&nbsp; <input type="radio" name="pstatus"  id="sinmother" class="tog" value="Mother" <?php  checked( 'Mother', $pstatus);  ?>>
											<label class="custom-control-label" for="Mother"><?php esc_html_e('Mother','school-mgt');?></label>
											&nbsp;&nbsp; <input type="radio" name="pstatus" id="boths" class="tog" value="Both"  <?php  checked( 'Both', $pstatus);  ?>>
											<label class="custom-control-label" for="Both" ><?php esc_html_e('Both','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					<?php
					$m_display_none='';
					$f_display_none='';
					if($edit)
					{
						$pstatus = $student_data->parent_status;
						if($pstatus == 'Father')
						{
							$m_display_none = 'display_none';
						}
						elseif($pstatus == 'Mother')
						{
							$f_display_none = 'display_none';
						}
					}
					?>
					<!-- Father Information -->
					<div class="row father_div <?php echo $f_display_none;  ?>">
						<div class="header" id="fatid">
							<h3 class="first_hed"><?php esc_html_e('Father Information','school-mgt');?></h3>
						</div>
						<div id="fatid1" class="col-md-6 input">
							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Salutation','school-mgt');?></label>
							<select class="form-control validate[required]" name="fathersalutation" id="fathersalutation">
								<option value="Mr"><?php esc_attr_e('Mr','school-mgt');?></option>
							</select>
						</div>
						<div id="fatid2" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_first_name" value="<?php if($edit){ echo $student_data->father_first_name;}elseif(isset($_POST['father_first_name'])) echo $_POST['father_first_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid3" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_middle_name" value="<?php if($edit){ echo $student_data->father_middle_name;}elseif(isset($_POST['father_middle_name'])) echo $_POST['father_middle_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid4" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_last_name" value="<?php if($edit){ echo $student_data->father_last_name;}elseif(isset($_POST['father_last_name'])) echo $_POST['father_last_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid13" class="col-md-6 rtl_margin_top_15px">
							<div class="form-group radio_button_bottom_margin_rs">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?></label>
											<div class="d-inline-block">
												<?php $father_gender = "male"; if($edit){ $father_gender=$student_data->fathe_gender; }elseif(isset($_POST['fathe_gender'])) {$father_gender=$_POST['fathe_gender'];}?>
												<input type="radio" value="male" class="tog" name="fathe_gender" <?php  checked( 'male', $father_gender);  ?>/>
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
												<input type="radio" value="female" class="tog" name="fathe_gender" <?php  checked( 'female', $father_gender);  ?> />
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
												<input type="radio" value="other" class="tog" name="fathe_gender" <?php  checked( 'other', $father_gender);  ?> />
												<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="fatid14" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_birth_date" class="form-control date_picker birth_date" type="text"  name="father_birth_date" value="<?php if($edit){ if($student_data->father_birth_date==""){ echo ""; }else{ echo mj_smgt_getdate_in_input_box($student_data->father_birth_date);}}elseif(isset($_POST['father_birth_date'])) echo $_POST['father_birth_date'];?>" readonly>
									<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid15" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_address" class="form-control validate[custom[address_description_validation]]" maxlength="120" type="text"  name="father_address" value="<?php if($edit){ echo $student_data->father_address;}elseif(isset($_POST['father_address'])) echo $_POST['father_address'];?>">
									<label for="userinput1"><?php esc_html_e('Address','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid16" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_state_name" value="<?php if($edit){ echo $student_data->father_state_name;}elseif(isset($_POST['father_state_name'])) echo $_POST['father_state_name'];?>">
									<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid17" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_city_name" value="<?php if($edit){ echo $student_data->father_city_name;}elseif(isset($_POST['father_city_name'])) echo $_POST['father_city_name'];?>">
									<label for="userinput1"><?php esc_html_e('City','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid18" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_zip_code" class="form-control validate[custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text" name="father_zip_code" value="<?php if($edit){ echo $student_data->father_zip_code;}elseif(isset($_POST['father_zip_code'])) echo $_POST['father_zip_code'];?>">
									<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid5" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_email" class="form-control validate[custom[email]] text-input father_email" maxlength="100" type="text"  name="father_email" value="<?php if($edit){ echo $student_data->father_email;}elseif(isset($_POST['father_email'])) echo $_POST['father_email'];?>">
									<label for="userinput1"><?php esc_html_e('Email','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid6" class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="father_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="father_mobile" value="<?php if($edit){ echo $student_data->father_mobile;}elseif(isset($_POST['father_mobile'])) echo $_POST['father_mobile'];?>">
											<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="fatid7" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_school" value="<?php if($edit){ echo $student_data->father_school;}elseif(isset($_POST['father_school'])) echo $_POST['father_school'];?>">
									<label for="userinput1"><?php esc_html_e('School Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid8" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_medium" value="<?php if($edit){ echo $student_data->father_medium;}elseif(isset($_POST['father_medium'])) echo $_POST['father_medium'];?>">
									<label for="userinput1"><?php esc_html_e('Medium of Instruction','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid9" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_education" value="<?php if($edit){ echo $student_data->father_education;}elseif(isset($_POST['father_education'])) echo $_POST['father_education'];?>">
									<label for="userinput1"><?php esc_html_e('Educational Qualification','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid10" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="fathe_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" maxlength="50" type="text" name="fathe_income" value="<?php if($edit){ echo $student_data->fathe_income;}elseif(isset($_POST['fathe_income'])) echo $_POST['fathe_income'];?>">
									<label for="userinput1"><?php esc_html_e('Annual Income','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="fatid9" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="father_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="father_occuption" value="<?php if($edit){ echo $student_data->father_occuption;}elseif(isset($_POST['father_occuption'])) echo $_POST['father_occuption'];?>">
									<label for="userinput1"><?php esc_html_e('Occupation','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div class="col-md-6" id="fatid12">
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">
									<label for="" class="custom-control-label custom-top-label ml-2 label_position_rtl margin_left_30px"><?php _e('Proof of Qualification','school-mgt');?></label>
									<div class="col-sm-12">
										<input type="file" name="father_doc" class="col-md-12 file_validation input-file" value="<?php if($edit){ echo $student_data->father_doc;}elseif(isset($_POST['father_doc'])) echo $_POST['father_doc'];?>">
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Mother Information -->
					<div class="row mother_div <?php echo $m_display_none;?> ">
						<div class="header" id="motid">
							<h3 class="first_hed"><?php esc_html_e('Mother Information','school-mgt');?></h3>
						</div>
						<div id="motid1" class="col-md-6 input mother_info">
							<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Salutation','school-mgt');?></label>
							<select class="form-control validate[required]" name="mothersalutation" id="mothersalutation">
								<option value="Ms"><?php esc_attr_e('Ms','school-mgt'); ?></option>
								<option value="Mrs"><?php esc_attr_e('Mrs','school-mgt'); ?></option>
								<option value="Miss"><?php esc_attr_e('Miss','school-mgt');?></option>
							</select>
						</div>
						<div id="motid2" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_first_name" value="<?php if($edit){ echo $student_data->mother_first_name;}elseif(isset($_POST['mother_first_name'])) echo $_POST['mother_first_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('First Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid3" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_middle_name" value="<?php if($edit){ echo $student_data->mother_middle_name;}elseif(isset($_POST['mother_middle_name'])) echo $_POST['mother_middle_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Middle Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid4" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_last_name" value="<?php if($edit){ echo $student_data->mother_last_name;}elseif(isset($_POST['mother_last_name'])) echo $_POST['mother_last_name'];?>">
									<label for="userinput1" class=""><?php esc_html_e('Last Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid13" class="col-md-6 rtl_margin_top_15px">
						<?php $mother_gender = "female"; if($edit){ $mother_gender=$student_data->mother_gender; }elseif(isset($_POST['mother_gender'])) {$mother_gender=$_POST['mother_gender'];}?>
							<div class="form-group radio_button_bottom_margin_rs ">
								<div class="col-md-12 form-control">
									<div class="row padding_radio">
										<div class="input-group">
											<label class="custom-top-label margin_left_0"><?php esc_html_e('Gender','school-mgt');?></label>
											<div class="d-inline-block">
												<?php $father_gender = "male"; if($edit){ $father_gender=$student_data->fathe_gender; }elseif(isset($_POST['fathe_gender'])) {$father_gender=$_POST['fathe_gender'];}?>
												<input type="radio" value="male" class="tog" name="mother_gender" <?php  checked( 'male', $mother_gender);  ?>/>
												<label class="custom-control-label margin_right_20px" for="male"><?php esc_html_e('Male','school-mgt');?></label>
												<input type="radio" value="female" class="tog" name="mother_gender" <?php  checked( 'female', $mother_gender);  ?> />
												<label class="custom-control-label" for="female"><?php esc_html_e('Female','school-mgt');?></label>
												<input type="radio" value="other" class="tog" name="mother_gender" <?php  checked( 'other', $mother_gender);  ?> />
												<label class="custom-control-label" for="other"><?php esc_html_e('Other','school-mgt');?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="motid14" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_birth_date" class="form-control date_picker birth_date" type="text" name="mother_birth_date" value="<?php if($edit){ if($student_data->mother_birth_date==""){ echo ""; }else{ echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date); }}elseif(isset($_POST['mother_birth_date'])) echo $_POST['mother_birth_date'];?>" readonly>
									<label for="userinput1" class="date_label"><?php esc_html_e('Date of Birth','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid15" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_address" class="form-control validate[custom[address_description_validation]]" maxlength="120" type="text"  name="mother_address" value="<?php if($edit){ echo $student_data->mother_address;}elseif(isset($_POST['mother_address'])) echo $_POST['mother_address'];?>">
									<label for="userinput1"><?php esc_html_e('Address','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid16" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_state_name" value="<?php if($edit){ echo $student_data->mother_state_name;}elseif(isset($_POST['mother_state_name'])) echo $_POST['mother_state_name'];?>">
									<label for="userinput1"><?php esc_html_e('State','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid17" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_city_name" value="<?php if($edit){ echo $student_data->mother_city_name;}elseif(isset($_POST['mother_city_name'])) echo $_POST['mother_city_name'];?>">
									<label for="userinput1"><?php esc_html_e('City','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid18" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_zip_code" class="form-control  validate[custom[zipcode],minSize[4],maxSize[8]]" maxlength="15" type="text"  name="mother_zip_code" value="<?php if($edit){ echo $student_data->mother_zip_code;}elseif(isset($_POST['mother_zip_code'])) echo $_POST['mother_zip_code'];?>">
									<label for="userinput1"><?php esc_html_e('Zip Code','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid5" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_email" class="form-control  validate[custom[email]]  text-input mother_email" maxlength="100" type="text"  name="mother_email" value="<?php if($edit){ echo $student_data->mother_email;}elseif(isset($_POST['mother_email'])) echo $_POST['mother_email'];?>">
									<label for="userinput1"><?php esc_html_e('Email','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid6" class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
											<label for="phonecode" class="pl-2"><?php esc_html_e('Country Code','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group input margin_bottom_0">
										<div class="col-md-12 form-control">
											<input id="mother_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mother_mobile" value="<?php if($edit){ echo $student_data->mother_mobile;}elseif(isset($_POST['mother_mobile'])) echo $_POST['mother_mobile'];?>">
											<label for="userinput6"><?php esc_html_e('Mobile Number','school-mgt');?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="motid7" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_school" value="<?php if($edit){ echo $student_data->mother_school;}elseif(isset($_POST['mother_school'])) echo $_POST['mother_school'];?>">
									<label for="userinput1"><?php esc_html_e('School Name','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid8" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_medium" value="<?php if($edit){ echo $student_data->mother_medium;}elseif(isset($_POST['mother_medium'])) echo $_POST['mother_medium'];?>">
									<label for="userinput1"><?php esc_html_e('Medium of Instruction','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid9" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_education" value="<?php if($edit){ echo $student_data->mother_education;}elseif(isset($_POST['mother_education'])) echo $_POST['mother_education'];?>">
									<label for="userinput1"><?php esc_html_e('Educational Qualification','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid10" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" type="text" name="mother_income" value="<?php if($edit){ echo $student_data->mother_income;}elseif(isset($_POST['mother_income'])) echo $_POST['mother_income'];?>">
									<label for="userinput1"><?php esc_html_e('Annual Income','school-mgt');?></label>
								</div>
							</div>
						</div>
						<div id="motid9" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control">
									<input id="mother_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="mother_occuption" value="<?php if($edit){ echo $student_data->mother_occuption;}elseif(isset($_POST['mother_occuption'])) echo $_POST['mother_occuption'];?>">
									<label for="userinput1"><?php esc_html_e('Occupation','school-mgt');?></label>
								</div>
							</div>
						</div>

						<div id="motid12" class="col-md-6">
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">
									<label for="" class="custom-control-label custom-top-label ml-2 label_position_rtl margin_left_30px"><?php _e('Proof of Qualification','school-mgt');?></label>
									<div class="col-sm-12">
										<input type="file" name="mother_doc" class="col-md-12 file_validation input-file" value="<?php if($edit){ echo $student_data->mother_doc;}elseif(isset($_POST['mother_doc'])) echo $_POST['mother_doc'];?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="submit" value="<?php if($edit){ esc_attr_e('Save Admission','school-mgt'); }else{ esc_attr_e('New Admission','school-mgt');}?>" name="student_admission" class="save_btn"/>
						</div>
					</div>
				</div>
			</div>

		</form> <!------ Form End ----->
	</div><!-------- penal body -------->
	<?php
}
?>