<?php  $edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit=1;
	$hall_data= mj_smgt_get_hall_by_id($_REQUEST['hall_id']);
}
?>

<div class="panel-body margin_top_20px padding_top_15px_res"><!-------- Penal Body -------->
	<form name="hall_form" action="" method="post" class="form-horizontal" id="hall_form">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="hall_id" value="<?php if($edit){ echo $hall_data->hall_id;}?>"/> 
		<div class="form-body user_form"><!-------- Form Body -------->
			<div class="row"><!-------- Row Div -------->
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="hall_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($hall_data->hall_name);}?>" name="hall_name">
							<label for="userinput1" class=""><?php esc_html_e('Hall Name','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="number_of_hall" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="5" type="text" value="<?php if($edit){ echo $hall_data->number_of_hall;}?>" name="number_of_hall">				
							<label for="userinput1" class=""><?php esc_html_e('Hall Numeric Value','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_hall_admin_nonce' ); ?>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="hall_capacity" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="5" type="text" value="<?php if($edit){ echo $hall_data->hall_capacity;}?>" name="hall_capacity">				
							<label for="userinput1" class=""><?php esc_html_e('Hall Capacity','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="description" id="description" maxlength="150" class="textarea_height_47px form-control validate[custom[address_description_validation]]"><?php if($edit){ echo stripslashes($hall_data->description);}?></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div><!-------- Row Div -------->
		</div><!-------- Form Body -------->
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hall','school-mgt'); }else{ esc_attr_e('Add Hall','school-mgt');}?>" name="save_hall" class="save_btn" />
				</div>
			</div>
		</div>
	</form>
</div><!-------- Penal Body -------->