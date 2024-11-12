<?php
$obj_custome_field=new Smgt_custome_field;
$file_type_find='';
$file_type_value='';
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit=1;
	$custom_field_id=$_REQUEST['id'];
    $custom_field_data=$obj_custome_field->mj_smgt_get_single_custom_field_data($custom_field_id);
}
?>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

<script type="text/javascript">
	$("body").on("click","#add_custom_field",function()
	{
		var checked = $("#validation_msg input:checked").length > 0;
		// if (!checked){
		// 	alert(language_translate2.one_select_Validation_alert);
		// 	return false;
		// }
		if(!checked)
		{
			alert(language_translate2.one_select_Validation_alert);
			return false;
		}
	});
</script>


<div class="panel-body">
	<form class="form form-horizontal" name="custom_field_form" enctype="multipart/form-data" method="post" id="custom_field_form">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="custom_field_id" value="<?php if($edit){ echo esc_attr($custom_field_id); } ?>"/>
		<div class="header">
			<h3 class="first_hed"><?php esc_html_e('Custom Field Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">

				<!-- <div class="form-group row mb-3"> -->

					<div class="col-md-6 input" id="smgt_select_class">
						<label class="ml-1 custom-top-label top" for="case_link"><?php esc_html_e('Form Name',	'school-mgt');?><span class="require-field">*</span></label>
						<select id="module_name" class="form-control validate[required]"  name="form_name" <?php if($edit){ ?> disabled <?php } ?>>
							<option value=""><?php esc_html_e('Select Form','school-mgt');?></option>
							<option value="student" <?php if($edit) selected('student',$custom_field_data->form_name);?>><?php esc_html_e('Student','school-mgt');?></option>
						</select>
					</div>

					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text" id="field_label" maxlength="30" class="placeholder_color form-control  validate[required,custom[address_description_validation]]" name="field_label" placeholder="<?php esc_html_e('Enter Name','school-mgt');?>" <?php if($edit){ ?> value="<?php echo esc_attr($custom_field_data->field_label); ?>" <?php } ?>>
								<label class="" for="case_link"><?php esc_html_e('Label','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>


					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="case_link"><?php esc_html_e('Type','school-mgt');?><span class="require-field">*</span></label>
						<select id="field_type" class="form-control validate[required] dropdown_change"  name="field_type" <?php if($edit){ ?> disabled <?php } ?>>
							<option value=""><?php esc_html_e('Select Input Type','school-mgt');?></option>
							<option value="text" <?php if($edit) selected('text',$custom_field_data->field_type); ?>><?php esc_html_e('Text Box','school-mgt');?></option>
							<option value="textarea" <?php if($edit) selected('textarea',$custom_field_data->field_type);?>><?php esc_html_e('Textarea','school-mgt');?></option>
							<option value="dropdown" <?php if($edit) selected('dropdown',$custom_field_data->field_type);?>><?php esc_html_e('Dropdown','school-mgt');?></option>
							<option value="date" <?php if($edit) selected('date',$custom_field_data->field_type);?>><?php esc_html_e('Date Field','school-mgt');?></option>
							<option value="checkbox" <?php if($edit) selected('checkbox',$custom_field_data->field_type);?>><?php esc_html_e('Checkbox','school-mgt');?></option>
							<option value="radio" <?php if($edit) selected('radio',$custom_field_data->field_type);?>><?php esc_html_e('Radio','school-mgt');?></option>
							<option value="file" <?php if($edit) selected('file',$custom_field_data->field_type);?>><?php esc_html_e('File','school-mgt');?></option>
						</select>
						<?php
						if($edit)
						{
							$validation = explode("|",$custom_field_data->field_validation);
							$min = "";
							$max = "";
							$file_type = "";
							$file_size = "";
							$Tclass = $Dclass = NULL;
							foreach($validation as $key=>$value)
							{
								if (strpos($value, 'min') !== false)
								{
									$min = $value;
								}
								elseif(strpos($value, 'max') !== false)
								{
									$max = $value;
								}
								elseif(strpos($value, 'file_types') !== false)
								{
									$file_type = $value;
								}
								elseif(strpos($value, 'file_upload_size') !== false)
								{
									$file_size = $max;
								}
							}
							//------------ VALUE CHECKED IN CHECKBOX EDIT TIME -----------//
							$input = preg_quote('max', '~'); // don't forget to quote input string!
							$result_max = preg_grep('~' . $input . '~', $validation);

							$input = preg_quote('min', '~'); // don't forget to quote input string!
							$result_min = preg_grep('~' . $input . '~', $validation);

							$exa = $custom_field_data->field_validation;
							$max_find = $max;
							$min_find = $min;
							$file_type_find = $file_type;
							$file_size_find = $file_size;
							$limit_max = substr($max_find,0,3);
							$limit_min = substr($min_find,0,3);
							$limit_value_max = substr($max_find,4);
							$limit_value_min = substr($min_find,4);
							$file_type_value = substr($file_type_find,11);
							$file_size_value = substr($file_size_find,17);

							if($custom_field_data->field_type=='dropdown' || $custom_field_data->field_type=='checkbox' || $custom_field_data->field_type=='radio'  )
							{
								$Tclass="disabled";
								$Dclass="disabled";
							}
							else if($custom_field_data->field_type=='text' || $custom_field_data->field_type=='textarea')
							{
								$Dclass="disabled";
								$Tclass=NULL;
							}
							else if($custom_field_data->field_type=='date')
							{
								$Tclass="disabled";
								$Dclass=NULL;
							}
						}
						?>
					</div>

					<div class="col-md-6 mb-3 smgt_main_custome_field rtl_margin_top_15px">
						<div class="form-group">
							<div class="col-md-12 form-control" id="validation_msg">
								<div class="row padding_radio">
									<div class="">
										<label class="custom-top-label margin_left_0" for="case_link"><?php esc_html_e('Validation','school-mgt');?><span class="require-field">*</span></label>

										<div class="row custom-control custom-checkbox mr-1 margin_left_custom_field smgt_Validation_label">
											<label class="rtl_margin_left_10px col-lg-6 col-md-6 col-sm-6 col-xs-12 margin_left_custom_field_new checkbox-inline mr-2">
											<input type="checkbox" name="validation[]"  value="nullable" class="nullable_rule margin_top_0" <?php if($edit){ if(in_array("nullable",$validation)){ echo 'checked'; } }else{ echo 'checked'; } ?>><span class="span_left_custom" style="margin-bottom: -5px;"><?php esc_html_e('Nullable','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2">
											<input type="checkbox" name="validation[]"  value="required" class="required_rule margin_top_0" <?php if($edit){ if(in_array("required",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Required','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox"  name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="numeric" id="only_number_id" class="only_number margin_top_0" <?php if($edit){ if(in_array("numeric",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e ('Only Number','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="alpha" id="only_char_id" class="only_char margin_top_0"<?php if($edit){ if(in_array("alpha",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Only Character','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?>  value="alpha_space" id="char_space_id" class="char_space margin_top_0" <?php if($edit){ if(in_array("alpha_space",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Character with Space','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?>   value="alpha_num" id="char_num_id" class="char_num margin_top_0" <?php if($edit){ if(in_array("alpha_num",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Number & Character','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" id="email_id" class="email margin_top_0" <?php if($edit){ echo $Tclass; } ?>  name="validation[]"  value="email" <?php if($edit){ if(in_array("email",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Email','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> class="opentext max margin_top_0" id="max_value" value="max" <?php if($edit){ if($result_max){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Maximum','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Tclass; } ?> class="opentext min margin_top_0" id="min_value" value="min" <?php if($edit){ if($result_min){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('Minimum','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" class="url margin_top_0" name="validation[]" <?php if($edit){ echo $Tclass; } ?> value="url" <?php if($edit){ if(in_array("url",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e('URL','school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date0" class="date margin_top_0" value="before_or_equal:today" <?php if($edit){ if(in_array("before_or_equal:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("Before Or Equal(Today's Date)",'school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date1"  class="date margin_top_0"  value="date_equals:today" <?php if($edit){ if(in_array("date_equals:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("Today's Date",'school-mgt'); ?></span>
											</label>
											<label class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox-inline mr-2 file_disable">
											<input type="checkbox" name="validation[]" <?php if($edit){ echo $Dclass; } ?> id="date2"  class="date margin_top_0"   value="after_or_equal:today" <?php if($edit){ if(in_array("after_or_equal:today",$validation)){ echo 'checked'; } }  ?>><span class="span_left_custom"><?php esc_html_e("After Or Equal(Today's Date)",'school-mgt'); ?></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
				if($edit)
				{
					$custom_meta=$obj_custome_field->mj_smgt_get_single_custom_field_dropdown_meta_data($custom_field_id);
					if($custom_field_data->field_type=='dropdown')
					{
						?>
						<div class="sub_cat">
							<div class="form-group row ">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-6 form-control">
											<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] d_label" placeholder="<?php esc_html_e('','school-mgt');?>">
											<label class="" for="case_link"><?php esc_html_e('Dropdown Label','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_drop" value="<?php esc_html_e('Add More','school-mgt'); ?>">
								</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-12 drop_label">
								<?php
								if(!empty($custom_meta))
								{
									foreach($custom_meta as $custom_metas)
									{
										?>
										<div class="badge badge-danger label_data custom-margin" >
											<input type="hidden" value="<?php echo $custom_metas->option_label; ?>" name="d_label[]"><span><?php echo $custom_metas->option_label; ?></span class=""><a href="#" ><i label_id="<?php echo $custom_metas->id; ?>" class="fa fa-trash font-medium-2 delete_d_label" aria-hidden="true" ></i></a>
										</div>
										&nbsp;
										<?php
									}
								}
								?>
							</div>
						</div>
						<?php
					}
					elseif($custom_field_data->field_type=='checkbox')
					{
						?>

						<div class="checkbox_cat">
							<div class="form-group row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-6 form-control">
											<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] c_label" placeholder="<?php esc_html_e('','school-mgt');?>">
											<label class="" for="case_link"><?php esc_html_e('Checkbox Label','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_checkbox" value="<?php esc_html_e('Add More','school-mgt'); ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 checkbox_label mb-4">
							<?php
								if(!empty($custom_meta))
								{
									foreach($custom_meta as $custom_metas)
									{
										?>
										<div class="badge badge-danger label_data label_checkbox custom-margin"  >
											<input type="hidden" value="<?php echo $custom_metas->option_label; ?>"  name="c_label[]"><span><?php echo $custom_metas->option_label; ?></span><a href="#"><i label_id="<?php echo $custom_metas->id; ?>" class="fa fa-trash font-medium-2 delete_c_label" aria-hidden="true"></i></a>
										</div>
										&nbsp;
										<?php
									}
								}
							?>
							</div>
						</div>
						<?php
					}
					elseif($custom_field_data->field_type=='radio')
					{
						?>
						<div class="radio_cat">
							<div class="form-group row">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-6 form-control">
											<input type="text" maxlength="30" class="form-control r_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
											<label class="" for="case_link"><?php esc_html_e('Radio Label','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_radio" value="<?php esc_attr_e('Add More','school-mgt'); ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 radio_label">
								<?php
								if(!empty($custom_meta))
								{
									foreach($custom_meta as $custom_metas)
									{
										?>
										<div class="badge badge-danger label_radio custom-margin custom_css" style="">
											<input type="hidden" value="<?php echo $custom_metas->option_label; ?>"  name="r_label[]"><span><?php echo $custom_metas->option_label; ?></span><a href="#" class="ml_5"><i class="fa fa-trash font-medium-2 delete_r_label" label_id="<?php echo $custom_metas->id; ?>" aria-hidden="true"></i></a>
										</div>
										&nbsp;
										<?php
									}
								}
								?>
							</div>
						</div>
						<?php
					}
					?>

					<div class="file_type_and_size">
						<?php
						if(strpos($file_type_find, 'file_types') !== false)
						{
							?>
							<style>
							.file_disable
							{
								opacity: 0.6;
								cursor: not-allowed;
								pointer-events: none;
							}
							</style>
							<div class="form-group row mb-3 margin_top_custome">
								<input type="hidden" name="validation[]" value="<?php echo $file_type_find; ?>" class="file_types_value">

								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-6 form-control">
											<input class="form-control file_edit file_types_input validate[required]" maxlength="100" type="text" id="userinput11" value="<?php echo $file_type_value; ?>">
											<label class="" for="case_link"><?php esc_html_e('File Types (like png,jpg,pdf,doc)','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

							<?php
						}
						if(strpos($file_size_find, 'file_upload_size') !== false)
						{
							?>
								<input type="hidden" name="validation[]" value="<?php echo $file_size_find; ?>" class="file_size_value">
								<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group input">
										<div class="col-md-6 form-control">
											<input class="form-control file_size_input validate[required]" maxlength="30" type="text" id="userinput9" value="<?php echo $file_size_value; ?>">
											<label class=" " for="case_link"><?php esc_html_e('File Upload Size(kb','school-mgt');?><span class="require-field">*</span></label>
										</div>
									</div>
								</div>

							</div>
						<?php
						}
						?>
					</div>

					<div class="">
						<?php
						if(strpos($min_find, 'min') !== false)
						{
							?>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control"  id="min_limit">
										<input type="number" class="form-control min_value validate[required,custom[onlyNumberSp]]" value="<?php echo $limit_value_min;?>"  id="min" >
										<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<?php
						}
						else
						{
							?>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group">
									<div class="col-md-6 form-control"  id="min_limit" style="display:none;" >
										<input type="number" class="form-control min_value validate[required,custom[onlyNumberSp]]"  id="min" value="">
										<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<?php
						}
						if(strpos($max_find, 'max') !== false)
						{
							?>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group">
									<div class="col-md-6 form-control"  id="max_limit">
										<input type="number" class="form-control max_value validate[required,custom[onlyNumberSp]]" value="<?php echo $limit_value_max;?>"  id="max" >
										<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
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
									<div class="col-md-6 form-control" id="max_limit" style="display:none;">
										<input type="number" class="form-control max_value validate[required,custom[onlyNumberSp]]"  id="max" value="">
										<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="sub_cat" style="display:none;">
						<div class="form-group row mb-3">
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control">
										<input type="text" maxlength="30" class="form-control validate[custom[popup_category_validation]] d_label d_label_new" placeholder="<?php esc_html_e('','school-mgt');?>">
										<label class="" for="case_link"><?php esc_html_e('Dropdown Label','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_drop" value="<?php esc_attr_e('Add More','school-mgt'); ?>">
							</div>
						</div>
					</div>
					<div class="row sub_cat">
						<div class="col-md-12 drop_label">
						</div>
					</div>
					<div class="checkbox_cat" style="display:none;">
						<div class="form-group row mb-3">
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control">
										<input type="text" maxlength="30" class="form-control c_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
										<label class="" for="case_link"><?php esc_html_e('Checkbox Label','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_checkbox" value="<?php esc_html_e('Add More','school-mgt'); ?>">
							</div>
						</div>
					</div>

					<div class="row checkbox_cat">
						<div class="col-md-12 checkbox_label">
						</div>
					</div>

					<div class="radio_cat" style="display:none;">
						<div class="form-group row mb-3">
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control">
										<input type="text" maxlength="30" class="form-control r_label validate[custom[popup_category_validation]]" placeholder="<?php esc_html_e('','school-mgt');?>">
										<label class="" for="case_link"><?php esc_html_e('Radio Label','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<input type="button"  name="menu_web" class="btn btn-primary save_btn add_more_radio" value="<?php esc_html_e('Add More','school-mgt'); ?>">
							</div>
						</div>
					</div>
					<div class="row radio_cat">
						<div class="col-md-12 radio_label">
						</div>
					</div>

					<div class="file_type_and_size" style="display:none;">
						<div class="form-group row mb-3 margin_top_custome">
							<input type="hidden" name="validation[]" value="<?php echo $file_type_find; ?>" class="file_types_value">

							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control">
										<input class="form-control file_types_input validate[required]" maxlength="100" type="text" id="userinput11" value="<?php echo $file_type_value; ?>">
										<label class="" for="case_link"><?php esc_html_e('File Types (like png,jpg,pdf,doc)','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

							<input type="hidden" name="validation[]" value="" class="file_size_value">
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div class="form-group input">
									<div class="col-md-6 form-control">
										<input class="form-control file_size_input validate[required]" maxlength="30" type="text" id="userinput9" >
										<label class="" for="case_link"><?php esc_html_e('File Upload Size(kb)','school-mgt');?><span class="require-field">*</span></label>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group">
							<div class="col-md-6 form-control" id="min_limit" style="display:none;">
								<input type="number" class="form-control min_value validate[required,custom[onlyNumberSp]]"  id="min" >
								<label class="" for="case_link"><?php esc_html_e('Minimum Limit','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group">
							<div class="col-md-6 form-control" id="max_limit" style="display:none;">
								<input type="number" class="form-control max_value validate[required,custom[onlyNumberSp]]"  id="max" >
								<label class="" for="case_link"><?php esc_html_e('Maximum Limit','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>

					<?php
				}
				?>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
					<div class="form-group">
						<div class="col-md-12 form-control">
							<div class="row padding_radio">
								<div class="">
									<label class="custom-top-label" for="case_link"><?php esc_html_e('Visibility','school-mgt');?><span class="require-field">*</span></label>
									<input type="checkbox"  value="1" <?php if($edit){  echo checked($custom_field_data->field_visibility,'1'); }else{  echo 'checked'; } ?> class="custom-control-input hideattar" name="field_visibility" >
									<label class=""  style="margin-bottom: -5px;" for="colorCheck1"><?php esc_html_e('Yes','school-mgt'); ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-3">
					<div class="form-group">
						<div class="col-md-12 form-control input_height_50px">
							<div class="row padding_radio">
								<div class="input-group input_checkbox">
									<label class="custom-top-label"><?php esc_html_e('Show field in student list','school-mgt');?></label>
									<div class="checkbox checkbox_lebal_padding_8px">
										<label>
										<input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="show_in_table" value="1" <?php if($edit){  echo checked($custom_field_data->show_in_table,'1');} ?>/>&nbsp;<?php esc_html_e('Yes','school-mgt');?>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">
					<input type="submit" id="add_custom_field" value="<?php if($edit){ esc_attr_e('Submit','school-mgt'); }else{ esc_attr_e('Add Custom Field','school-mgt');}?>" name="add_custom_field" class="btn btn-success save_btn" />
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/customfield.js'; ?>" ></script>