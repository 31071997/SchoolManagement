<?php
	$obj_hostel=new smgt_hostel;
?>
<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$hostel_data=$obj_hostel->mj_smgt_get_hostel_by_id($_REQUEST['hostel_id']);
	}
	?>
       
	<div class="panel-body"><!-- start panel-body -->
        <form name="hostel_form" action="" method="post" class="form-horizontal" id="hostel_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="hostel_id" value="<?php if($edit){ echo $hostel_data->id;}?>"/> 
			<div class="header">	
				<h3 class="first_hed"><?php esc_html_e('Hostel Information','school-mgt');?></h3>
			</div>
			<div class="form-body user_form"> <!--Card Body div-->   
				<div class="row"><!--Row Div--> 
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="hostel_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_name;}?>" name="hostel_name">
								<label class="" for="hostel_name"><?php esc_attr_e('Hostel Name','school-mgt');?> <span class="require-field">*</span></label>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="hostel_type" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_type;}?>" name="hostel_type">
								<label class="" for="hostel_type"><?php esc_attr_e('Hostel Type','school-mgt');?> <span class="require-field">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="hostel_address" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_address;}?>" name="hostel_address">
								<label class="" for="hostel_type"><?php esc_attr_e('Hostel Address','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="hostel_intake" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_intake;}?>" name="hostel_intake">
								<label class="" for="hostel_intake"><?php esc_attr_e('Intake/Capacity','school-mgt');?></label>
							</div>
						</div>
					</div>
					<?php wp_nonce_field( 'save_hostel_admin_nonce' ); ?>
					<div class="col-md-6 note_text_notice">
						<div class="form-group input">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
									<textarea name="Description" id="Description" maxlength="150" class="textarea_height_47px form-control validate[custom[description_validation]]"><?php if($edit){ echo $hostel_data->Description;}?></textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active" for="Description"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hostel','school-mgt'); }else{ esc_attr_e('Add Hostel','school-mgt');}?>" name="save_hostel" class="btn btn-success save_btn" />
					</div>
				</div>
			</div>
        </form>
	</div><!-- End panel-body -->