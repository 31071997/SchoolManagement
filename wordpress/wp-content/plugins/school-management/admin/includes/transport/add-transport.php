<div class="add_transport"><!--------- Add Transport Div ------->
	<?php  
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$transport_data= mj_smgt_get_transport_by_id($_REQUEST['transport_id']);
	}
	?>
	<div class="panel-body margin_top_20px padding_top_15px_res"><!--------- penal body ------->
        <form name="transport_form" action="" method="post" class="form-horizontal" id="transport_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="route_name" class="form-control validate[required,custom[description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $transport_data->route_name;}?>" name="route_name">
								<label for="userinput1" class=""><?php esc_html_e('Route Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="number_of_vehicle" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="15" type="text" value="<?php if($edit){ echo $transport_data->number_of_vehicle;}?>" name="number_of_vehicle">
								<label for="userinput1" class=""><?php esc_html_e('Vehicle Identifier','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="vehicle_reg_num" class="form-control validate[required,custom[address_description_validation]] " maxlength="15" type="text" value="<?php if($edit){ echo $transport_data->vehicle_reg_num;}?>" name="vehicle_reg_num">
								<label for="userinput1" class=""><?php esc_html_e('Vehicle Registration Number','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<?php wp_nonce_field( 'save_transpoat_admin_nonce' ); ?>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="driver_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" value="<?php if($edit){ echo $transport_data->driver_name;}?>" name="driver_name">
								<label for="userinput1" class=""><?php esc_html_e('Driver Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 margin_bottom_15px_res">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="driver_phone_num" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" value="<?php if($edit){ echo $transport_data->driver_phone_num;}?>" name="driver_phone_num">
								<label for="userinput1" class=""><?php esc_html_e('Driver Phone Number','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 note_text_notice margin_bottom_15px_res error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
									<textarea name="driver_address" class="textarea_height_47px form-control validate[required,custom[address_description_validation]]" maxlength="150" id="driver_address"><?php if($edit){ echo $transport_data->driver_address;}?></textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active"><?php esc_attr_e('Driver Address','school-mgt');?><span class="required">*</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 note_text_notice">
						<div class="form-group input">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
									<textarea name="route_description" class="textarea_height_47px form-control validate[custom[description_validation]]" maxlength="120" id="route_description"><?php if($edit){ echo $transport_data->route_description;}?></textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="route_fare" class="form-control validate[required,custom[onlyNumberSp],min[0],maxSize[10]]" type="text" value="<?php if($edit){ echo $transport_data->route_fare;}?>" name="route_fare">
								<label for="userinput1" class=""><?php esc_attr_e('Route Fare','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<div class="form-group input">
							<div class="col-md-12 form-control upload-profile-image-patient res_rtl_height_50px">
								<label class="custom-control-label custom-top-label ml-2 label_position_rtl" for="photo"><?php esc_attr_e('Image Upload','school-mgt');?></label>
								<div class="col-sm-12 display_flex">
									<input type="text" id="smgt_user_avatar_url" name="smgt_user_avatar" class="image_path_dots" value="<?php if($edit)echo esc_url( $transport_data->smgt_user_avatar ); ?>" readonly />
									<input id="upload_user_avatar_button" type="button" class="button upload_image_btn btn_top" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />
								</div>
							</div>
							<div class="clearfix"></div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div id="upload_user_avatar_preview" >
										<?php 
										if($edit) 
										{
										if($transport_data->smgt_user_avatar == "")
										{
											?><img alt="" class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb_new' ) ?>"><?php 
										}
										else {
											?>
										
										<img class="image_preview_css" src="<?php if($edit)echo esc_url( $transport_data->smgt_user_avatar ); ?>" />
										<?php 
										}
										}
										else 
										{
											?>
											<img alt="" class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb_new' ) ?>">
											<?php 
										}
										?>  
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
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Transport','school-mgt'); }else{ esc_attr_e('Add Transport','school-mgt');}?>" name="save_transport" class="btn btn-success rtl_margin_0px save_btn"/>
					</div>
				</div>
			</div>
		</form>
	</div><!--------- penal body ------->
</div><!--------- Add Transport Div ------->