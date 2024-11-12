<script type="text/javascript">

	function fileCheck(obj) {

    "use strict";

    var fileExtension = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

    var maxSizeMB = 10; // Set the maximum allowed file size in megabytes

    

    var file = obj.files[0];

    var fileSizeMB = file.size / (1024 * 1024); // Calculate file size in megabytes

    

    if ($.inArray(file.name.split('.').pop().toLowerCase(), fileExtension) == -1) {

        alert("<?php esc_html_e('Sorry, only JPG, PDF, DOC, DOCX, JPEG, PNG, and GIF files are allowed.','school-mgt');?>");

        $(obj).val('');

    } else if (fileSizeMB > maxSizeMB) {

        alert("File size exceeds the maximum allowed size of " + maxSizeMB + "MB.");

        $(obj).val('');

    }

}

</script>

<script type="text/javascript">

	$(document).ready(function()

	{

		//DOCUMENT FORM VALIDATIONENGINE

		"use strict";

		<?php

			if (is_rtl())

			{

				?>	

					$('#document_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

				<?php

			}

			else

			{

				?>

				$('#document_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

				<?php

			}

		?>

		$('.onlyletter_number_space_validation').keypress(function( e ) 

		{   

			"use strict";  

			var regex = new RegExp("^[0-9a-zA-Z \b]+$");

			var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);

			if (!regex.test(key)) 

			{

				event.preventDefault();

				return false;

			} 

		});  

	} );

</script>

<script type="text/javascript">

	$(document).ready(function() {

		$('.add-search-single-select-js').select2({

		});

	})

</script>

    <?php 	

		$document_id=0;

		if(isset($_REQUEST['document_id']))

		$document_id=$_REQUEST['document_id'];

		$edit=0;

		if(isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action']) == 'edit')

		{					

			$edit=1;

			$result = $obj_document->mj_smgt_get_single_document($document_id);

		} ?>



		<div class="panel-body padding_0"><!--PANEL BODY-->

		    <!--DOCUMENT FORM-->

			<form name="document_form" action="" method="post" class="form-horizontal" id="document_form" enctype="multipart/form-data">

				<?php $action = sanitize_text_field(isset($_REQUEST['action'])?$_REQUEST['action']:'insert');?>

				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">

				<input type="hidden" name="document_id" value="<?php echo esc_attr($document_id);?>"  />

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Document Information','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 



						<div class="col-md-6 input">

							<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>

							<?php if($edit){ $classval=$result->class_id; }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>

							<select name="class_id"   class="form-control validate[required] max_width_100 class_list_document">

								<option value="all class" <?php selected('all class', $classval);  ?>><?php esc_attr_e('All Class','school-mgt');?></option>

								<?php

								foreach(mj_smgt_get_allclass() as $classdata)

								{  

								?>

									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>

									<?php 

								}?>

							</select>

						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input error_msg_left_margin">

							<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>

							<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>

							<select name="class_section" class="form-control max_width_100 class_section_document">

								<option value="all section" <?php selected('all section', $sectionval);?>><?php esc_attr_e('All Section','school-mgt');?></option>

								<?php

								if($edit)

								{

									foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)

									{  ?>

									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>

									<?php 

									} 

										

								} 

								?>

							</select>    

						</div>

						<div class="col-md-6 input select_Student_div">

							<label class="ml-1 custom-top-label top"><?php esc_attr_e('Select Student','school-mgt');?><span class="require-field">*</span></label>								

							<span class="document_user_display_block">

							<?php if($edit){ $student_val=$result->student_id; }elseif(isset($_POST['selected_users'])){$student_val=$_POST['selected_users'];}else{$student_val='all student';}?>

								<select name="selected_users"  class="form-control validate[required] max_width_100 student_list">

								<?php 

								if($student_val == 'all student')

								{

								?>

								   <option value="all student"><?php esc_attr_e('All Student','school-mgt');?></option>

								<?php

								}								

								else

								{ ?>

								<?php

									echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_student_display_name_with_roll($result->student_id).'</option>';

								}?>

								</select>

							</span>

						</div>

						

					</div>

				</div>

				<div class="header">	

					<h3 class="first_hed"><?php esc_html_e('Upload Document','school-mgt');?></h3>

				</div>

				<div class="form-body user_form"> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 

						<?php

						if($edit)

						{

							$doc_data=json_decode($result->document_content);

							?>

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="doc_title" maxlength="50" name="doc_title" class="form-control validate[required,custom[description_validation]] text-input" type="text"  value="<?php if(!empty($doc_data[0]->title)) { echo esc_attr($doc_data[0]->title);}elseif(isset($_POST['doc_title'])) echo esc_attr($_POST['doc_title']);?>">

										<label class="" for="doc_title"><?php esc_html_e('Document Title','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>



							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control res_rtl_height_75px">	

									<label class="ustom-control-label custom-top-label ml-2 label_position_rtl" for="photo"><?php esc_html_e('Upload Document','school-mgt');?><span class="require-field">*</span></label>

										<div class="col-sm-12">	

											<input type="file" name="document_content" class="file_validation input-file"/>						

											<input type="hidden" name="old_hidden_document" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['document_content'])) echo esc_attr($_POST['document_content']);?>">

										</div>

										<?php

										if(!empty($doc_data[0]->value))

										{

											?>

											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">

												<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" record_id="<?php echo $result->document_id;?>">

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

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control">

										<input id="doc_title" maxlength="50" class="form-control validate[required,custom[description_validation]] text-input" type="text"  value="" name="doc_title">

										<label class="" for="doc_title"><?php esc_html_e('Document Title','school-mgt');?><span class="require-field">*</span></label>

									</div>

								</div>

							</div>

								

							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

								<div class="form-group input">

									<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">

										<label class="ustom-control-label custom-top-label ml-2 label_position_rtl" for="photo"><?php esc_html_e('Upload Document','school-mgt');?><span class="require-field">*</span></label>

										<div class="col-sm-12 display_flex">

											<input id="upload_file" onchange="fileCheck(this);" name="upload_file"  type="file" <?php if($edit){ ?>class="margin_left_15_res" <?php }else{ ?>class="validate[required] margin_left_15_res margin_top_5_res"<?php } ?>  />

										</div>

									</div>

								</div>

							</div>

							<?php

						}

						?>



						<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 note_text_notice">

							<div class="form-group input">

								<div class="col-md-12 note_border margin_bottom_15px_res">

									<div class="form-field">

										<textarea name="description" maxlength="150"  class="textarea_height_47px form-control validate[custom[description_validation]] text-input resize"><?php if($edit) echo esc_textarea($result->description);?></textarea>

										<span class="txt-title-label"></span>

										<label class="text-area address active" for="desc"><?php esc_html_e('Description','school-mgt');?></label>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<!----------   save btn    --------------> 

				<div class="form-body user_form  "> <!-- user_form Strat-->   

					<div class="row"><!--Row Div Strat--> 

						<div class="col-md-6 col-sm-6 col-xs-12"> 	

							<?php wp_nonce_field( 'save_document_nonce' ); ?>

							<input type="submit" value="<?php if($edit){ esc_html_e('Save Document','school-mgt'); }else{ esc_html_e('Add Document','school-mgt');}?>" name="save_document" class="btn save_btn"/>

						</div>

					</div><!--Row Div End--> 

				</div><!-- user_form End--> 

			</form><!--END DOCUMENT FORM-->

        </div><!--END PANEL BODY-->

<?php ?>