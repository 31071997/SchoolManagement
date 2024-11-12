<?php
$obj_invoice= new Smgtinvoice();
if($_REQUEST['invoice_type']=='invoice')
{
    $invoice_data=mj_smgt_get_payment_by_id($_REQUEST['idtest']);
}
if($_REQUEST['invoice_type']=='income'){
    $income_data=$obj_invoice->mj_smgt_get_income_data($_REQUEST['idtest']);
}
if($_REQUEST['invoice_type']=='expense'){
    $expense_data=$obj_invoice->mj_smgt_get_income_data($_REQUEST['idtest']);	
}
 ?>
<div class="penal-body"><!----- penal Body --------->
	<div id="Payment_invoice"><!----- Payment Invoice --------->
		<div class="modal-body border_invoice_page margin_top_15px_rs invoice_model_body float_left_width_100 height_600px"><!---- model body  ----->
			<img class="rtl_image_set_invoice invoiceimage image_width_98px float_left invoice_image_model"  src="<?php echo plugins_url('/school-management/assets/images/listpage_icon/invoice.png'); ?>" width="100%">
			<div id="invoice_print" class="main_div float_left_width_100 payment_invoice_popup_main_div"> 
				<div class="invoice_width_100 float_left" border="0">
					<h3 class="school_name_for_invoice_view"><?php echo get_option( 'smgt_school_name' ) ?></h3>
					<div class="row margin_top_20px">
						<div class="col-md-1 col-sm-2 col-xs-3">
							<div class="width_1 rtl_width_80px">
								<img class="system_logo"  src="<?php echo esc_url(get_option( 'smgt_school_logo' )); ?>">
							</div>
						</div>						
						<div class="col-md-11 col-sm-10 col-xs-9 invoice_address invoice_address_css">	
							<div class="row">	
								<div class="col-md-12 col-sm-12 col-xs-12 invoice_padding_bottom_15px padding_right_0">	
									<label class="popup_label_heading"><?php esc_html_e('Address','school-mgt'); ?>
									</label><br>
									<label for="" class="label_value word_break_all">	<?php
											echo chunk_split(get_option( 'smgt_school_address' ),100,"<BR>").""; 
										?></label>
								</div>
								<div class="row col-md-12 invoice_padding_bottom_15px">	
									<div class="col-md-6 col-sm-6 col-xs-6 address_css padding_right_0 email_width_auto">	
										<label class="popup_label_heading"><?php esc_html_e('Email','school-mgt');?> </label><br>
										<label for="" class="label_value word_break_all"><?php echo get_option( 'smgt_email' ),"<BR>";  ?></label>
									</div>
							
									<div class="col-md-6 col-sm-6 col-xs-6 address_css padding_right_0 padding_left_30px">
										<label class="popup_label_heading"><?php esc_html_e('Phone','school-mgt');?> </label><br>
										<label for="" class="label_value"><?php echo get_option( 'smgt_contact_number' )."<br>";  ?></label>
									</div>
								</div>	
								<div align="right" class="width_24"></div>									
							</div>				
						</div>
					</div>
				<div class="col-md-12 col-sm-12 col-xl-12 mozila_display_css margin_top_20px">
					<div class="row">
						<div class="width_50a1 float_left_width_100">
							<div class="col-md-8 col-sm-8 col-xs-5 padding_0 float_left display_grid display_inherit_res margin_bottom_20px rs_main_billed_to">
								<div class="billed_to display_flex invoice_address_heading rs_width_billed_to">								
									<?php
									$issue_date='DD-MM-YYYY';
									if(!empty($income_data))
									{
										$issue_date=$income_data->income_create_date;
										$payment_status=$income_data->payment_status;
									}
									if(!empty($invoice_data))
									{
										$issue_date=$invoice_data->date;
										$payment_status=$invoice_data->payment_status;	
									}
									if(!empty($expense_data))
									{
										$issue_date=$expense_data->income_create_date;
										$payment_status=$expense_data->payment_status;
									}
									?>
									<h3 class="billed_to_lable invoice_model_heading bill_to_width_12 rs_bill_to_width_40"><?php esc_html_e('Bill To','school-mgt');?> : </h3>
									
									<?php
									if(!empty($expense_data))
									{
										$party_name=$expense_data->supplier_name; 
										if($party_name){
											echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
										}
										else{
											echo'N/A';
										}
									}
									else{
										if(!empty($income_data))
											$student_id=$income_data->supplier_name;
										if(!empty($invoice_data))
											$student_id=$invoice_data->student_id;
										$patient=get_userdata($student_id);			
										if($patient){
											echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
										}
										else
										{
											echo'N/A';
										}
									}
									?>
								</div>
								<div class="width_60b2 address_information_invoice">								
									<?php 	
									if(!empty($expense_data))
									{
										// $party_name=$expense_data->supplier_name; 
										// echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($party_name),30,"<BR>"). "</h3>";
									}
									else
									{
										if(!empty($income_data))
											$student_id=$income_data->supplier_name;
										if(!empty($invoice_data))
											$student_id=$invoice_data->student_id;
										$patient=get_userdata($student_id);						
										// echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
										$address=get_user_meta( $student_id,'address',true );

										echo chunk_split($address,30,"<BR>"); 
										echo get_user_meta( $student_id,'city',true ).","."<BR>"; ; 
										echo get_user_meta( $student_id,'zip_code',true ).",<BR>"; 
									}		
									?>	
									</div>
								</div>
								<div class="col-md-3 col-sm-4 col-xs-7 float_left">
									<div class="width_50a1112">
										<div class="width_20c" align="center">
											<?php
											if(!empty($invoice_data))
											{
												
											}
											?>
											<h5 class="align_left"> <label class="popup_label_heading text-transfer-upercase"><?php   echo esc_html__('Date :','school-mgt') ?> </label>&nbsp;  <label class="invoice_model_value"><?php echo mj_smgt_getdate_in_input_box(date("Y-m-d", strtotime($issue_date))); ?></label></h5>
											<h5 class="align_left"><label class="popup_label_heading text-transfer-upercase"><?php echo esc_html__('Status :','school-mgt')?> </label>  &nbsp;<label class="invoice_model_value"><?php 
												if($payment_status=='Paid') 
                                                { echo '<span class="green_color">'.esc_attr__('Fully Paid','school-mgt').'</span>';}
                                                if($payment_status=='Part Paid')
                                                { echo '<span class="perpal_color">'.esc_attr__('Partially Paid','school-mgt').'</span>';}
                                                if($payment_status=='Unpaid')
                                                {echo '<span class="red_color">'.esc_attr__('Not Paid','school-mgt').'</span>'; } ?></h5>														
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
					<table class="width_100 margin_top_10px_res">	
						<tbody>		
							<tr>
								<td>
									<?php
									if(!empty($invoice_data))
									{
										?>
										<h3 class="display_name"><?php esc_attr_e('Invoice Entries','school-mgt');?></h3>
										<?php
									}
									elseif(!empty($income_data))
									{
										?>
										<h3 class="display_name"><?php esc_attr_e('Income Entries','school-mgt');?></h3>
										<?php
									}
									elseif(!empty($expense_data))
									{
										?>
										<h3 class="display_name"><?php esc_attr_e('Expense Entries','school-mgt');?></h3>
										<?php
									}
									?>
									
								<td>	
							</tr>
						</tbody>
					</table>
					<div class="table-responsive table_max_height_180px rtl_padding-left_40px">
						<table class="table model_invoice_table">
							<thead class="entry_heading invoice_model_entry_heading">					
								<tr>
									<th class="entry_table_heading align_center">#</th>
									<th class="entry_table_heading align_center"> <?php esc_attr_e('Date','school-mgt');?></th>
									<th class="entry_table_heading align_center"><?php esc_attr_e('Entry','school-mgt');?> </th>
									<th class="entry_table_heading align_center"><?php esc_attr_e('Price','school-mgt');?></th>
									<th class="entry_table_heading align_center"> <?php esc_attr_e('Issue By','school-mgt');?> </th>
								</tr>						
							</thead>
							<tbody>
								<?php 
								$id=1;
								$total_amount=0;
								if(!empty($income_data) || !empty($expense_data))
								{
									if(!empty($expense_data))
									{
										$income_data=$expense_data;
									}
									
									$patient_all_income=$obj_invoice->mj_smgt_get_onestudent_income_data($_REQUEST['idtest']);
									
									foreach($patient_all_income as $result_income)
									{
										$income_entries=json_decode($result_income->entry);
										foreach($income_entries as $each_entry)
										{
											$total_amount+=$each_entry->amount;								
											?>
											<tr>
												<td class="align_center invoice_table_data"><?php echo $id;?></td>
												<td class="align_center invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($result_income->income_create_date);?></td>
												<td class="align_center invoice_table_data"><?php echo $each_entry->entry; ?> </td>
												<td class="align_center invoice_table_data"> <?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($each_entry->amount,2,'.','')) ; ?></td>
												<td class="align_center invoice_table_data"><?php echo esc_attr_e(mj_smgt_get_display_name($result_income->create_by),'school-mgt');?></td>
											</tr>
											<?php 
											$id+=1;
										}
									}
								}
								if(!empty($invoice_data))
								{
									$total_amount=$invoice_data->amount
									?>
									<tr>
										<td class="align_center invoice_table_data"><?php echo $id;?></td>
										<td class="align_center invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($invoice_data->date);?></td>
										<td class="align_center invoice_table_data"><?php echo $invoice_data->payment_title; ?> </td>
										<td class="align_center invoice_table_data"> <?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($invoice_data->amount,2,'.','')); ?></td>
										<td class="align_center invoice_table_data"><?php echo mj_smgt_get_display_name($invoice_data->payment_reciever_id);?></td>
									</tr>
									<?php 
								}?>
							</tbody>
						</table>
					</div>
					
										
					<?php 
					if(!empty($invoice_data))
					{								
						$grand_total= $total_amount;			
						$sub_total = $invoice_data->fees_amount;
						if(!empty($invoice_data->tax)){

							$tax_name = MJ_smgt_tax_name_by_tax_id_array_for_invoice(esc_html($invoice_data->tax));

						}

						else{

							$tax_name = '';

						}				
					}
					if(!empty($income_data))
					{
						$grand_total=$total_amount;
					}
					?>								
					<div class="table-responsive rtl_padding-left_40px rtl_float_left_width_100px">
						<table width="100%" border="0">
							<tbody>		
								<?php if(isset($invoice_data->tax_amount) && !empty($invoice_data->tax_amount))
                                {
                                    ?>
									<tr>
										<td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Sub Total','school-mgt').'  :';?></td>
										<td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($sub_total,2,'.',''));?></td>
									</tr>
                                <tr>
                                    <td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Tax Amount','school-mgt').'('.$tax_name.')'.'  :';?></td>
                                    <td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo "+".MJ_smgt_currency_symbol_position_language_wise(number_format($invoice_data->tax_amount,2,'.',''));?></td>
                                </tr>
                                <?php
                                }?>
							</tbody>	
						</table>
					</div>
					<div class="col-md-12 grand_total_main_div total_padding_15px rtl_float_none">
						<div class="row margin_top_10px_res width_50_res col-md-8 col-sm-8 col-xs-8 print-button pull-left invoice_print_pdf_btn">
							<div class="col-md-2 print_btn_rs width_50_res" style="width:10%;">
								<a  href="?page=smgt_payment&print=print&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_REQUEST['invoice_type'];?>" target="_blank"class="btn color_white btn save_btn invoice_btn_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/print.png" ?>" > </a>
							</div>
						
							<div class="col-md-3 pdf_btn_rs width_50_res">
								<a href="?page=smgt_payment&print=pdf&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_REQUEST['invoice_type'];?>" target="_blank" class="btn color_white invoice_btn_div btn save_btn"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/pdf.png" ?>" ></a>
							</div>
							
						</div>
						<div id="res_rtl_width_100" class="row margin_top_10px_res col-md-4 col-sm-4 col-xs-4 view_invoice_lable_css inovice_width_100px_rs float_left grand_total_div invoice_table_grand_total" style="float: right;">
							<div class="width_50_res align_right col-md-5 col-sm-5 col-xs-5 view_invoice_lable padding_11 padding_right_0_left_0 float_left grand_total_label_div invoice_model_height line_height_1_5 padding_left_0_px"><h3 style="float:right;" class="padding color_white margin invoice_total_label"><?php esc_html_e('Grand Total','school-mgt');?> </h3></div>
							<div class="width_50_res align_right col-md-7 col-sm-7 col-xs-7 view_invoice_lable  padding_right_5_left_5 padding_11 float_left grand_total_amount_div"><h3 class="padding margin text-right color_white invoice_total_value" style="float: right;"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($grand_total,2,'.','')); ?></h3></div>
						</div>
					</div>
					<div class="margin_top_20px"></div>
				</div>
			</div>
		</div><!---- model body  ----->
	</div><!----- Payment Invoice --------->
</div><!----- penal Body --------->