<?php 	
if($active_tab == 'addfeetype')
{
	$fees_id=0;
	if(isset($_REQUEST['fees_id']))
		$fees_id=$_REQUEST['fees_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_fees->mj_smgt_get_single_feetype_data($fees_id);
	} ?>
		
    <div class="panel-body margin_top_20px padding_top_15px_res"><!----- penal Body --------->
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="fees_id" value="<?php echo $fees_id;?>">
			<input type="hidden" name="invoice_type" value="expense">
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-4 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
						<select class="form-control validate[required] smgt_feetype max_width_100" name="fees_title_id" id="category_data">
							<option value=""><?php esc_attr_e('Select Fee Type','school-mgt');?></option>
							<?php 
							$activity_category=mj_smgt_get_all_category('smgt_feetype');
							if(!empty($activity_category))
							{
								if($edit)
								{
									$fees_val=$result->fees_title_id; 
								}
								else
								{
									$fees_val=''; 
								}
							
								foreach ($activity_category as $retrive_data)
								{ 		 	
								?>
									<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
								<?php }
							} 
							?> 
						</select>	                         
					</div>
					<div class="col-sm-2 padding_bottom_15px_res">
						<button id="addremove_cat" class="rtl_margin_top_15px btn btn-info add_btn" model="smgt_feetype"><?php esc_attr_e('Add','school-mgt');?></button>
					</div>
					<div class="col-md-6 input error_msg_left_margin">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
						<?php $classval = 0;
						if($edit)
						$classval = $result->class_id;?>
						<select name="class_id" class="form-control validate[required] max_width_100" id="class_list">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
							<option value="all_class" <?php selected($classval, 'all_class');  ?>><?php esc_attr_e('All Class','school-mgt');?></option>
							<?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{  
								?>
								<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
							<?php }?>
						</select>                         
					</div>
					<?php wp_nonce_field( 'save_fees_type_admin_nonce' ); ?>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
						<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
						<select name="class_section" class="form-control max_width_100" id="class_section">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
							<?php
							if($edit){
								foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
								{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>
						</select>                       
					</div>
					<div class="col-md-6 error_msg_left_margin">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>" name="fees_amount">
								<label for="userinput1" class=""><?php esc_html_e('Fees Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 note_text_notice">
						<div class="form-group input">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
									<textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Fee Type','school-mgt'); }else{ esc_attr_e('Create Fee Type','school-mgt');}?>" name="save_feetype" class="btn btn-success save_btn"/>
					</div>
				</div>
			</div>
    	</form>
	</div><!----- penal Body --------->
	<?php 
} 
?>