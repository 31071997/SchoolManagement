<script type="text/javascript">
$(document).ready(function() {
	$('#payment_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('.tax_charge').multiselect(
	{
		nonSelectedText: '<?php esc_html_e('Select Tax','school-mgt');?>',
		includeSelectAllOption: true,
		allSelectedText: '<?php esc_html_e('All Selected','school-mgt'); ?>',
		selectAllText: '<?php esc_html_e('Select all','school-mgt'); ?>',
		templates: {
			button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
		},
		buttonContainer: '<div class="dropdown" />'
	});
} );
</script>
<?php
$edit = 0;
if (isset ( $_REQUEST ['action'] ) && $_REQUEST ['action'] == 'edit')
{	
  $edit = 1;
  $payment_data = mj_smgt_get_payment_by_id($_REQUEST['payment_id']);
} 
?>
  
<div class="panel-body margin_top_20px padding_top_15px_res"><!--------- Penal Body --------->
	<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="payment_id" value="<?php if($edit){ echo $payment_data->payment_id;}?>" />
		<div class="form-body user_form"><!--------- Form Body --------->
			<div class="row"><!--------- Row Div --------->
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="payment_title" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $payment_data->payment_title;}?>" name="payment_title"/>
							<label for="userinput1" class=""><?php esc_html_e('Title','school-mgt');?><span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
					<?php
					if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
                    <select name="class_id" id="class_list" class="form-control validate[required] max_width_100">
                    	<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                            <?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{ ?>
								<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
						<?php }?>
                     </select>                           
				</div>
				<div class="col-md-6 input">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
					<?php if($edit){ $sectionval=$payment_data->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control max_width_100" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($payment_data->class_id) as $sectiondata)
							{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>                         
				</div>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
					<?php if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
                    <select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
						<?php if(isset($payment_data->student_id)){ 
						$student=get_userdata($payment_data->student_id);
						?>
						<option value="<?php echo $payment_data->student_id;?>" ><?php echo mj_smgt_student_display_name_with_roll($payment_data->student_id);?></option>
						<?php }
						else
						{?>
							<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
						<?php } ?>
					</select>                       
				</div>
				<div class="col-md-6">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="amount" class="form-control validate[required,min[0],maxSize[12]]" type="number" step="0.01" value="<?php if($edit){ echo $payment_data->fees_amount;}?>" name="amount">
							<label for="userinput1" class=""><?php esc_html_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 rtl_margin_bottom_0px mb-3 multiselect_validation_member smgt_multiple_select">
					<select class="form-control tax_charge" id="tax_id" name="tax[]" multiple="multiple">
						<?php
							if($edit)
							{
								if ($payment_data->tax !== null) {
									$tax_id = explode(',', $payment_data->tax);
								} else {
									$tax_id[]='';
								}
							}
							else
							{
								$tax_id[]='';
							}
							$obj_tax=new tax_Manage();
							$smgt_taxs=$obj_tax->MJ_smgt_get_all_tax();
							if(!empty($smgt_taxs))
							{
								foreach($smgt_taxs as $data)
								{
									$selected = "";
									if(in_array($data->tax_id,$tax_id))
										$selected = "selected";

									?>
									<option value="<?php echo esc_attr($data->tax_id); ?>" <?php echo esc_html($selected); ?>>
									<?php echo esc_html($data->tax_title);?> - <?php echo esc_html($data->tax_value);?></option>
								<?php
								}

							}
						?>
					</select>
				</div>
				<?php wp_nonce_field( 'save_payment_admin_nonce' ); ?>
				<div class="col-md-6 input error_msg_left_margin">
					<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
					<select name="payment_status" id="payment_status" class="form-control max_width_100">
						<option value="Paid"
							<?php if($edit)selected('Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Paid','school-mgt');?></option>
						<option value="Part Paid"
							<?php if($edit)selected('Part Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Part Paid','school-mgt');?></option>
						<option value="Unpaid"
							<?php if($edit)selected('Unpaid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Unpaid','school-mgt');?></option>
					</select>                    
				</div>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="description" id="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo $payment_data->description;}?></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
							</div>
						</div>
					</div>
				</div>
			</div><!--------- Row Div --------->
		</div><!--------- Form Body --------->
		<div class="form-body user_form">
			<div class="row">
				<div class="col-sm-6">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Payment','school-mgt'); }else{ esc_attr_e('Add Payment','school-mgt');}?>" name="save_payment" class="btn btn-success save_btn" />
				</div>
			</div>
		</div>
	</form>
</div><!--------- table-responsive --------->