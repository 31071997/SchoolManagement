<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$taxdata= $obj_tax->MJ_smgt_get_single_tax($_REQUEST['tax_id']);

	} 
?>
       
<div class="panel-body"><!-------- penal body -------->
	<form name="tax_form" action="" method="post" class="form-horizontal" id="tax_form"><!------- form Start --------->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="tax_id" value="<?php if($edit){ echo esc_attr($_REQUEST['tax_id']);}?>"  />
		<div class="header">	
			<h3 class="first_hed"><?php esc_html_e('Tax Information','school-mgt');?></h3>
		</div>
		<div class="form-body user_form">
			<div class="row">	
				<div class="col-md-6">
				<div class="form-group input">
					<div class="col-md-12 form-control">
						<input id="tax_title" class="form-control validate[required,custom[popup_category_validation]]" maxlength="30" type="text" value="<?php if($edit){ echo $taxdata->tax_title;}?>" name="tax_title">
						<label for="userinput1" class=""><?php esc_html_e('Tax Name','school-mgt');?><span class="required">*</span></label>
					</div>
				</div>
			</div>
				<div class="col-md-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="tax" class="form-control validate[required,custom[number]] text-input" onkeypress="if(this.value.length==6) return false;" step="0.01" type="number" value="<?php if($edit){ echo esc_attr($taxdata->tax_value);}elseif(isset($_POST['tax_value'])) echo esc_attr($_POST['tax_value']);?>" name="tax_value" min="0" max="100">
							<label class="" for="date"><?php esc_html_e('Tax Value(%)','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<?php wp_nonce_field( 'save_tax_admin_nonce' ); ?>	
			</div>
		</div>
		<div class="form-body user_form">
			<div class="row">	
				<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Tax','school-mgt'); }else{ esc_attr_e('Add Tax','school-mgt');}?>" name="save_tax" class="save_btn" />
				</div> 
			</div>        
		</div>               
	</form> <!------- form end --------->
</div><!-------- penal body -------->