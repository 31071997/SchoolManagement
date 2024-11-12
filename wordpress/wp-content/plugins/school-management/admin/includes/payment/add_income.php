<?php 
$obj_invoice= new Smgtinvoice();	
if($active_tab == 'addincome')
{
	$income_id=0;
	if(isset($_REQUEST['income_id']))
		$income_id=$_REQUEST['income_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
			$edit=1;
			$result = $obj_invoice->mj_smgt_get_income_data($income_id);			
		} ?>
			
	<script type="text/javascript">
	$(document).ready(function() {
		$('#income_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
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
	});
	</script>	
	<div class="panel-body margin_top_20px padding_top_15px_res"><!--------- Penal Body --------->
		<form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
			<input type="hidden" name="invoice_type" value="income">
			<div class="form-body user_form"><!--------- Form Body --------->
				<div class="row"><!--------- Row Div --------->
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
						<?php
						if($edit){ $classval=$result->class_id; }else{$classval='';}?>
						<select name="class_id" id="class_list" class="form-control validate[required] max_width_100">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
								<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{ ?>
									<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
							<?php } ?>
						</select>                        
					</div>
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
									} ?>
						</select>                        
					</div>
					<div class="col-md-6 input">
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?><span class="require-field">*</span></label>
						<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>                     
						<select name="supplier_name" id="student_list" class="form-control validate[required] max_width_100">                    
							<?php if(isset($result->supplier_name))
							{ 
								$student=get_userdata($result->supplier_name);
								?>
								<option value="<?php echo $result->supplier_name;?>" ><?php echo mj_smgt_student_display_name_with_roll($result->supplier_name);?></option>
								<?php 
							}
							else
							{ 
								?>
								<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
								<?php 
							} ?>
						</select>                    
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
					<?php wp_nonce_field( 'save_income_fees_admin_nonce' ); ?>
					<div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->income_create_date);}elseif(isset($_POST['invoice_date'])){ echo mj_smgt_getdate_in_input_box($_POST['invoice_date']);}else{ echo mj_smgt_getdate_in_input_box(date("Y-m-d"));}?>" name="invoice_date" readonly>
								<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?><span class="required">*</span></label>
							</div>
						</div>
					</div>
				</div><!--------- Row Div --------->
			</div><!--------- Form Body --------->
			<hr>
			<div class="header">	
				<h3 class="first_hed margin_top_0px_image"><?php esc_html_e('Income Entry','school-mgt');?></h3>
			</div>
			<div id="income_entry_main">
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
					{
						?>
						<div id="income_entry">
							<div class="form-body user_form income_fld">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]">
												<label for="userinput1" class=""><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
												<label for="userinput1" class=""><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label>
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
				{?>
					<div id="income_entry">
						<div class="form-body user_form income_fld">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]">
											<label for="userinput1" class=""><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]">
											<label for="userinput1" class=""><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label>
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
				} ?>
			</div>
			<hr>
			<div class="form-body user_form income_fld">
				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="<?php if($edit){ esc_attr_e('Save Income','school-mgt'); }else{ esc_attr_e('Create Income Entry','school-mgt');}?>" name="save_income" class="btn btn-success save_btn"/>
					</div>
				</div>
			</div>
		</form>
	</div><!--------- Penal Body --------->
	<script>
		// CREATING BLANK INVOICE ENTRY
		var blank_income_entry ='';
		$(document).ready(function() 
		{
			
		blank_income_entry = '<div class="padding_top_15px_res form-body user_form income_fld"><div class="row"><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]"><label for="userinput1" class="active"><?php esc_html_e('Income Amount','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-3"><div class="form-group input"><div class="col-md-12 form-control"><input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]"><label for="userinput1" class="active"><?php esc_html_e('Income Entry Label','school-mgt');?><span class="required">*</span></label></div></div></div><div class="col-md-2 symptoms_deopdown_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" onclick="deleteParentElement(this)" alt="" class="rtl_margin_top_15px"></div></div></div>';			
		}); 

		function add_entry()
		{
			
			$("#income_entry_main").append(blank_income_entry);		
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
	<?php 
}
?>
