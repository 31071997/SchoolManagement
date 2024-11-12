<?php
    $fees_pay_id = $_REQUEST['idtest'];
    $fees_detail_result = mj_smgt_get_single_fees_payment_record($fees_pay_id);
    $fees_history_detail_result = mj_smgt_get_payment_history_by_feespayid($fees_pay_id);
    $obj_feespayment= new mj_smgt_feespayment();
?>
<div class="penal-body"><!----- penal Body --------->
    <div id="Fees_invoice"><!----- Fees Invoice --------->
        <div class="modal-body border_invoice_page margin_top_25px_rs invoice_model_body float_left_width_100 padding_0_res height_1000px">
            
            <img class="rtl_image_set_invoice invoiceimage float_left image_width_98px invoice_image_model"  src="<?php echo plugins_url('/school-management/assets/images/listpage_icon/invoice.png'); ?>" width="100%">
                
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
                                <label class="popup_label_heading"><?php esc_html_e('Address','school-mgt');
                                ?>
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
                            <div class="col-md-8 col-sm-8 col-xs-5 padding_0 float_left display_grid display_inherit_res margin_bottom_20px">
                                <div class="billed_to display_flex display_inherit_res invoice_address_heading">								
                                    <h3 class="billed_to_lable invoice_model_heading bill_to_width_12"><?php esc_html_e('Bill To','school-mgt');?> : </h3>
                                    <?php
                                        $student_id=$fees_detail_result->student_id;
                                        $patient=get_userdata($student_id);	
                                        if($patient){
                                            echo "<h3 class='display_name invoice_width_100'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
                                        }else{
                                            echo "N/A";
                                        }					
                                        
                                    ?>
                                </div>
                                <div class="width_60b2 address_information_invoice">								
                                    <?php 	
                                    $student_id=$fees_detail_result->student_id;	
                                    $patient=get_userdata($student_id);
                                    if($patient){
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
                                        
                                            $issue_date='DD-MM-YYYY';
                                            $issue_date=$fees_detail_result->paid_by_date;	
                                            $payment_status = mj_smgt_get_payment_status($fees_detail_result->fees_pay_id);	
                                            ?>
                                            <h5 class="align_left"> <label class="popup_label_heading text-transfer-upercase"><?php   echo esc_html__('Date :','school-mgt') ?> </label>&nbsp;  <label class="invoice_model_value"><?php echo mj_smgt_getdate_in_input_box(date("Y-m-d", strtotime($issue_date))); ?></label></h5>
                                            <h5 class="align_left"><label class="popup_label_heading text-transfer-upercase"><?php echo esc_html__('Status :','school-mgt')?> </label>  &nbsp;<label class="invoice_model_value"><?php 
                                                if($payment_status=='Fully Paid') 
                                                { echo '<span class="green_color">'.esc_attr__('Fully Paid','school-mgt').'</span>';}
                                                if($payment_status=='Partially Paid')
                                                { echo '<span class="perpal_color">'.esc_attr__('Partially Paid','school-mgt').'</span>';}
                                                if($payment_status=='Not Paid')
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
                                    <h3 class="display_name"><?php esc_attr_e('Invoice Entries','school-mgt');?></h3>
                                <td>	
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive padding_bottom_15px rtl_padding-left_40px">
                        <table class="table model_invoice_table">
                            <thead class="entry_heading invoice_model_entry_heading">					
                                <tr>
                                    <th class="entry_table_heading align_left">#</th>
                                    <th class="entry_table_heading align_left"><?php esc_attr_e('Date','school-mgt');?></th>
                                    <th class="entry_table_heading align_left"> <?php esc_attr_e('Fees Type','school-mgt');?></th>
                                    <th class="entry_table_heading align_left"><?php esc_attr_e('Total','school-mgt');?> </th>					
                                </tr>						
                            </thead>
                            <tbody>
                                <?php 
                                $fees_id=explode(',',$fees_detail_result->fees_id);
                                $x=1;
                                $amounts = 0;
                                foreach($fees_id as $id)
                                { 
                                    ?>
                                    <tr>
                                        <td class="align_left invoice_table_data"> <?php echo $x; ?></td>
                                        <td class="align_left invoice_table_data"> <?php echo mj_smgt_getdate_in_input_box($fees_detail_result->created_date);?></td>
                                        <td class="align_left invoice_table_data"> <?php echo mj_smgt_get_fees_term_name($id); ?></td>
                                        <td class="align_left invoice_table_data">
                                            <?php
                                            $amount=$obj_feespayment->mj_smgt_feetype_amount_data($id);
                                            $amounts += $amount;
                                            echo MJ_smgt_currency_symbol_position_language_wise(number_format($amount,2,'.','')); 
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $x++;
                                }
                                $sub_total = $amounts;
                                if(!empty($fees_detail_result->tax)){

                                    $tax_name = MJ_smgt_tax_name_by_tax_id_array_for_invoice(esc_html($fees_detail_result->tax));
    
                                }
    
                                else{
    
                                    $tax_name = '';
    
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive rtl_padding-left_40px rtl_float_left_width_100px">
                        <table width="100%" border="0">
                            <tbody>							
                                <tr style="">
                                    <td  align="right" class="rtl_float_left_label padding_bottom_15px total_heading"><?php esc_attr_e('Sub Total :','school-mgt');?></td>
                                    <td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($sub_total,2,'.','')); ?></td>
                                </tr>
                                <?php if(isset($fees_detail_result->tax_amount))
                                {
                                    ?>
                                <tr>
                                    <td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php echo esc_attr__('Tax Amount','school-mgt').'('.$tax_name.')'.'  :';?></td>
                                    <td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo "+".MJ_smgt_currency_symbol_position_language_wise(number_format($fees_detail_result->tax_amount,2,'.',''));?></td>
                                </tr>
                                <?php
                                }?>
                                <tr>
                                    <td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php esc_attr_e('Payment Made :','school-mgt');?></td>
                                    <td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($fees_detail_result->fees_paid_amount,2,'.',''));?></td>
                                </tr>
                                <tr>
                                    <td width="85%" class="rtl_float_left_label padding_bottom_15px total_heading" align="right"><?php esc_attr_e('Due Amount :','school-mgt');?></td>
                                    <?php $Due_amount = $fees_detail_result->total_amount - $fees_detail_result->fees_paid_amount; ?>
                                    <td align="right" class="rtl_width_15px padding_bottom_15px rtl_text_align_left total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($Due_amount,2,'.','')); ?></td>
                                </tr>				
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $subtotal = $fees_detail_result->total_amount;
                    $paid_amount = $fees_detail_result->fees_paid_amount;
                    $grand_total = $subtotal - $paid_amount;
                    ?>
                    <div id="res_rtl_width_100" class="rtl_float_left row margin_top_10px_res col-md-4 col-sm-4 col-xs-4 view_invoice_lable_css inovice_width_100px_rs float_left grand_total_div invoice_table_grand_total" style="float: right;margin-right:0px;">
                        <div class="width_50_res align_right col-md-5 col-sm-5 col-xs-5 view_invoice_lable padding_11 padding_right_0_left_0 float_left grand_total_label_div invoice_model_height line_height_1_5 padding_left_0_px"><h3 style="float: right;" class="padding color_white margin invoice_total_label"><?php esc_html_e('Grand Total','school-mgt');?> </h3></div>
                        <div class="width_50_res align_right col-md-7 col-sm-7 col-xs-7 view_invoice_lable  padding_right_5_left_5 padding_11 float_left grand_total_amount_div"><h3 class="padding margin text-right color_white invoice_total_value"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($subtotal,2,'.','')); ?></h3></div>
                    </div>
                    <?php
                    $notice = get_option('smgt_invoice_notice');
                    if(!empty($notice))
                    {
                    ?>
                    <div class="smgt_invoce_notice row">
                        <div class="heading"><h2 class=""><?php esc_attr_e('Instruction :','school-mgt'); ?></h2></div>
                        <div class="col-md-9 p-0"><label for=""><?php echo get_option('smgt_invoice_notice');?></label></div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php if(!empty($fees_history_detail_result))
                    { 
                        ?>
                        <table class="width_100 margin_top_10px_res">	
                            <tbody>		
                                <tr>
                                    <td>
                                        <h3 class="display_name res_pay_his_mt_10px"><?php esc_attr_e('Payment History','school-mgt');?></h3>
                                    <td>	
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-responsive rtl_padding-left_40px table_max_height_350px">
                            <table class="table model_invoice_table">
                                <thead class="entry_heading invoice_model_entry_heading">
                                    <tr>
                                        <th class="entry_table_heading align_left"><?php esc_attr_e('Date','school-mgt');?></th>
                                        <th class="entry_table_heading align_left"> <?php esc_attr_e('Amount','school-mgt');?></th>
                                        <th class="entry_table_heading align_left"><?php esc_attr_e('Method','school-mgt');?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($fees_history_detail_result as  $retrive_date)
                                    {
                                        ?>
                                        <tr>
                                            <td class="align_left invoice_table_data"><?php echo mj_smgt_getdate_in_input_box($retrive_date->paid_by_date);?></td>
                                            <td class="align_left invoice_table_data"><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrive_date->amount,2,'.',''));?></td>
                                            <td class="align_left invoice_table_data"><?php  $data=$retrive_date->payment_method;
                                                echo esc_attr_e($data,"school-mgt");
                                                ?>
                                            </td>
                                        </tr>
                                        <?php 
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php 
                    } ?>
                    <div class="col-md-12 grand_total_main_div total_padding_15px rtl_float_none">
                        <div class="row margin_top_10px_res width_50_res col-md-6 col-sm-6 col-xs-6 print-button pull-left invoice_print_pdf_btn">
                            <div class="col-md-2 print_btn_rs width_50_res">
                                <a href="?page=smgt_fees_payment&print=print&payment_id=<?php echo $_REQUEST['idtest'];?>&fee_paymenthistory=<?php echo 'fee_paymenthistory';?>" target="_blank" class="btn btn save_btn invoice_btn_div"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/print.png" ?>" > </a>
                            </div>
                            <div class="col-md-3 pdf_btn_rs width_50_res">
                                <a href="?page=smgt_fees_payment&print=pdf&payment_id=<?php echo $_REQUEST['idtest'];?>&fee_paymenthistory=<?php echo "fee_paymenthistory";?>" target="_blank" class="btn color_white invoice_btn_div btn save_btn"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/pdf.png" ?>" ></a>
                            </div>
                        </div>
                    </div>
                    <div class="margin_top_20px"></div>
                </div>
            </div>
        </div>
    </div><!----- Fees Invoice --------->
</div><!----- penal Body --------->