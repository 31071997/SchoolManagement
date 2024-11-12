<?php $obj_invoice= new Smgtinvoice(); ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#expense_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('#invoice_date').datepicker({
		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
    }); 		
} );
</script>
<?php 	
if($active_tab == 'addexpense')
{
	$expense_id=0;
	if(isset($_REQUEST['expense_id']))
		$expense_id=$_REQUEST['expense_id'];
	$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
			$edit=1;
			$result = $obj_invoice->mj_smgt_get_income_data($expense_id);
	}
	?>
    <div class="panel-body margin_top_20px padding_top_15px_res"><!--------- Penal Body --------->
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
        	<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="expense_id" value="<?php echo $expense_id;?>">
			<input type="hidden" name="invoice_type" value="expense">	
			<div class="form-body user_form"><!--------- Form Body --------->
				<div class="row"><!--------- Row Div --------->
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="supplier_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->supplier_name;}elseif(isset($_POST['supplier_name'])) echo $_POST['supplier_name'];?>" name="supplier_name">
								<label for="userinput1" class=""><?php esc_html_e('Supplier Name','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Status','school-mgt');?></label>
						<select name="payment_status" id="payment_status" class="form-control validate[required] max_width_100">
							<option value="Paid"
								<?php if($edit)selected('Paid',$result->payment_status);?> ><?php esc_attr_e('Paid','school-mgt');?></option>
							<option value="Part Paid"
								<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php esc_attr_e('Part Paid','school-mgt');?></option>
							<option value="Unpaid"
								<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php esc_attr_e('Unpaid','school-mgt');?></option>
						</select>              
					</div>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="invoice_date" class="form-control validate[required]" type="text"  value="<?php if($edit){ echo $result->income_create_date;}elseif(isset($_POST['invoice_date'])){ echo mj_smgt_getdate_in_input_box($_POST['invoice_date']);}else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" name="invoice_date" readonly>
								<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
				</div><!--------- Row Div --------->
			</div><!--------- Form Body --------->
			<hr>
			<div id="expense_entry_main">
				<?php 			
				if($edit)
				{
					$all_entry=json_decode($result->entry);
				}
				else
				{
					if(isset($_POST['income_entry']))
					{					
						$all_data=$obj_invoice->mj_smgt_get_entry_records($_POST);
						$all_entry=json_decode($all_data);
					}					
				}
				if(!empty($all_entry))
				{
					$i=0;
					foreach($all_entry as $entry)
					{ ?>
						<div id="expense_entry">
							<div class="form-body user_form income_fld">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_amount" class="form-control btn_top amt validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]" >
												<label for="userinput1" class=""><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_entry" class="form-control entry btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
												<label for="userinput1" class=""><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label>
											</div>
										</div>
									</div>
									<?php
									if($i == 0 )
									{ 
										?>
										<div class="col-md-2 symptoms_deopdown_div">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
										</div>
										<?php
									}
									else
									{
										?>
										<div class="col-md-2 symptoms_deopdown_div">
											<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px">
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php 
						$i++; 
					}				
				}
				else 
				{ 
					?>
					<div id="expense_entry">
						<div class="form-body user_form income_fld">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]">
											<label for="userinput1" class=""><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_entry" class="form-control btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="" name="income_entry[]">
											<label for="userinput1" class=""><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-2 symptoms_deopdown_div">
									<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_entry()" alt="" name="add_new_entry" class="rtl_margin_top_15px daye_name_onclickr" id="add_new_entry">
								</div>
							</div>
						</div>
					</div>					
					<?php 
				} 
				?>
			</div>
			<?php wp_nonce_field( 'save_expense_fees_admin_nonce' ); ?>
			<hr>
			<div class="form-body user_form">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Expense','school-mgt'); }else{ esc_attr_e('Create Expense Entry','school-mgt');}?>" name="save_expense" class="btn btn-success save_btn"/>
					</div>
				</div>
			</div>
		</form>
    </div><!--------- Form Body --------->
	<?php  
}
?>
<script>
	// CREATING BLANK INVOICE ENTRY
	$(document).ready(function() 
	{
		blank_expense_entry = '<div class="padding_top_15px_res form-body user_form income_fld"><div class="row"><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]"><label for="userinput1" class="active"><?php esc_html_e('Expense Amount','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control btn_top validate[required,custom[description_validation]] text-input" maxlength="50" type="text" value="" name="income_entry[]"><label for="userinput1" class="active"><?php esc_html_e('Expense Entry Label','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-2 symptoms_deopdown_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px"></div></div></div>';			
	}); 

	function add_entry()
	{
		$("#expense_entry_main").append(blank_expense_entry);		
	}
	// REMOVING INVOICE ENTRY
	function deleteParentElement(n)
	{
		var alert = confirm(language_translate2.delete_record_alert);
		if (alert == true){
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
		else{

		}
	}
</script> 