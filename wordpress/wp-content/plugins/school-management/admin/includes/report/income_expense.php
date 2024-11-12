<?php
$active_tab = isset($_GET['tab2'])?$_GET['tab2']:'income_expense_graph'; 
$role=mj_smgt_get_roles(get_current_user_id());
?>
<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
    <li class="<?php if($active_tab=='income_expense_graph'){?>active<?php }?>">			
        <a href="<?php if($role == 'administrator'){ echo "?page=smgt_report"; }else{ echo "?dashboard=user&page=report"; }?>&tab=fianance_report&tab1=income_expense_payment&tab2=income_expense_graph" class="padding_left_0 tab <?php echo $active_tab == 'income_expense_graph' ? 'active' : ''; ?>">
        <?php esc_html_e('Graph', 'school-mgt'); ?></a> 
    </li>
    <li class="<?php if($active_tab=='income_expense_datatable'){?>active<?php }?>">
        <a href="<?php if($role == 'administrator'){ echo "?page=smgt_report"; }else{ echo "?dashboard=user&page=report"; }?>&tab=fianance_report&tab1=income_expense_payment&tab2=income_expense_datatable" class="padding_left_0 tab <?php echo $active_tab == 'income_expense_datatable' ? 'active' : ''; ?>">
        <?php esc_html_e('DataTable', 'school-mgt'); ?></a> 
    </li>
</ul>
<?php

if($active_tab == 'income_expense_graph')
{

	$current_year = Date("Y");
	$month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),'5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),'9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('October','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);
    $result = array();
	$dataPoints_2 = array();
	//array_push($dataPoints_2, array('Month','Income','Expense'));
	array_push($dataPoints_2, array(esc_html__('Month','school-mgt'),esc_html__('Income','school-mgt'),esc_html__('Expense','school-mgt'),esc_html__('Net Profite','school-mgt')));
	$dataPoints_1 = array();
	$expense_array = array();
	$currency_symbol = MJ_smgt_get_currency_symbol(get_option( 'smgt_currency_code' ));
	foreach($month as $key=>$value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix."smgt_income_expense";

		$q = "SELECT * FROM $table_name WHERE YEAR(income_create_date) = $current_year AND MONTH(income_create_date) = $key and invoice_type='income'";

		$q1 = "SELECT * FROM $table_name WHERE YEAR(income_create_date) = $current_year AND MONTH(income_create_date) = $key and invoice_type='expense'";

		$result=$wpdb->get_results($q);
		$result1=$wpdb->get_results($q1);
       
		$expense_yearly_amount = 0;
		foreach($result1 as $expense_entry)
		{
            $all_entry=json_decode($expense_entry->entry);
            $amount=0;
            foreach($all_entry as $entry)
            {
                $amount+=$entry->amount;
            }
		    $expense_yearly_amount += $amount;
		}

			$expense_amount = $expense_yearly_amount;
		

        $income_yearly_amount = 0;
        foreach($result as $income_entry)
		{
            $all_entry=json_decode($income_entry->entry);
            $amount=0;
            foreach($all_entry as $entry)
            {
                $amount+=$entry->amount;
            }
		    $income_yearly_amount += $amount;
		}

			$income_amount = $income_yearly_amount;

		$expense_array[] = $expense_amount;
		$income_array[] = $income_amount;
        $net_profit_array = $income_amount - $expense_amount;
		array_push($dataPoints_2, array($value,$income_amount,$expense_amount,$net_profit_array));
		
	}
    $income_filtered = array_filter($income_array);
    $expense_filtered = array_filter($expense_array);
	$new_array = json_encode($dataPoints_2);
 
	if(!empty($income_filtered) || !empty($expense_filtered))
	{
		$new_currency_symbol = html_entity_decode($currency_symbol);
	
		?>
		
		<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/chart_loder.js'; ?>"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable(<?php echo $new_array; ?>);

				var options = {
				
					bars: 'vertical', // Required for Material Bar Charts.
					colors: ['#104B73', '#FF9054', '#70ad46'],
                    
				};
			
				var chart = new google.charts.Bar(document.getElementById('barchart_material_income_expence'));

				chart.draw(data, google.charts.Bar.convertOptions(options));
			}
		</script>
		<div id="barchart_material_income_expence" style="width:100%;height: 430px; padding:20px;"></div>
		<?php
	}
	else
	{
		?>
		<div class="calendar-event-new"> 
			<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
		</div>
		<?php	
	}
}

if($active_tab == 'income_expense_datatable')
{
    ?>
    <div class="panel-body clearfix margin_top_20px rtl_margin_0px rtl_margin_0px padding_top_15px_res">
        <div class="panel-body clearfix">
            <form method="post" id="student_income_expence_payment">  
                <div class="form-body user_form">
                    <div class="row">
                        <div class="col-md-3 mb-3 input">
                            <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date Type','school-mgt');?><span class="require-field">*</span></label>			
                            <select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">
                            <option value=""><?php esc_attr_e('Select','school-mgt');?></option>
                                <option value="today"><?php esc_attr_e('Today','school-mgt');?></option>
                                <option value="this_week"><?php esc_attr_e('This Week','school-mgt');?></option>
                                <option value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>
                                <option value="this_month"><?php esc_attr_e('This Month','school-mgt');?></option>
                                <option value="last_month"><?php esc_attr_e('Last Month','school-mgt');?></option>
                                <option value="last_3_month"><?php esc_attr_e('Last 3 Months','school-mgt');?></option>
                                <option value="last_6_month"><?php esc_attr_e('Last 6 Months','school-mgt');?></option>
                                <option value="last_12_month"><?php esc_attr_e('Last 12 Months','school-mgt');?></option>
                                <option value="this_year"><?php esc_attr_e('This Year','school-mgt');?></option>
                                <option value="last_year"><?php esc_attr_e('Last Year','school-mgt');?></option>
                                <option value="period"><?php esc_attr_e('Period','school-mgt');?></option>
                            </select>
                        </div>
                        <div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	
                        <div class="col-md-3 mb-2">
                            <input type="submit" name="income_expense_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                        </div>
                    </div>
                </div>
            </form> 
        </div>	
        <?php
        if(isset($_REQUEST['income_expense_report']))
        {
            $date_type = $_POST['date_type'];
            if($date_type=="period")
            {
                $start_date = $_REQUEST['start_date'];
                $end_date = $_REQUEST['end_date'];
            }
            else
            {
                $result =  mj_smgt_all_date_type_value($date_type);
        
                $response =  json_decode($result);
                $start_date = $response[0];
                $end_date = $response[1];
            }

            $income_data = MJ_get_total_income($start_date,$end_date);
            $expense_data = MJ_get_total_expense($start_date,$end_date);

            //----------- Expense Record Sum ------------//
            $expense_yearly_amount = 0;
            foreach($expense_data as $expense_entry)
            {
                $all_entry=json_decode($expense_entry->entry);
                $amount=0;
                foreach($all_entry as $entry)
                {
                    $amount+=$entry->amount;
                }
                $expense_yearly_amount += $amount;
            }
        
            if($expense_yearly_amount == 0)
            {
                $expense_amount = null;
            }
            else
            {
                $expense_amount = "$expense_yearly_amount";
            }
            //----------- Expense Record Sum ------------//


            //----------- Income Record Sum -------------//
            $income_yearly_amount = 0;
            foreach($income_data as $income_entry)
            {
                $all_entry=json_decode($income_entry->entry);
                $amount=0;
                foreach($all_entry as $entry)
                {
                    $amount+=$entry->amount;
                }
                $income_yearly_amount += $amount;
            }
    
            if($income_yearly_amount == 0)
            {
                $income_amount = null;
            }
            else
            {
                $income_amount = "$income_yearly_amount";
            }
            //----------- Income Record Sum -------------//

        }
        else
        {
            $start_date = date('Y-m-d');
            $end_date= date('Y-m-d');
            $income_data = MJ_get_total_income($start_date,$end_date);
            $expense_data = MJ_get_total_expense($start_date,$end_date);

           //----------- Expense Record Sum ------------//
           $expense_yearly_amount = 0;
           foreach($expense_data as $expense_entry)
           {
               $all_entry=json_decode($expense_entry->entry);
               $amount=0;
               foreach($all_entry as $entry)
               {
                   $amount+=$entry->amount;
               }
               $expense_yearly_amount += $amount;
           }
       
           if($expense_yearly_amount == 0)
           {
               $expense_amount = null;
           }
           else
           {
               $expense_amount = "$expense_yearly_amount";
           }
           //----------- Expense Record Sum ------------//

            //----------- Income Record Sum -------------//
            $income_yearly_amount = 0;
            foreach($income_data as $income_entry)
            {
                $all_entry=json_decode($income_entry->entry);
                $amount=0;
                foreach($all_entry as $entry)
                {
                    $amount+=$entry->amount;
                }
                $income_yearly_amount += $amount;
            }
    
            if($income_yearly_amount == 0)
            {
                $income_amount = null;
            }
            else
            {
                $income_amount = "$income_yearly_amount";
            }
            //----------- Income Record Sum -------------//
        }

        if(!empty($expense_amount) || !empty($income_amount))
        {
            ?>
            <script src="">
                
            </script>
            <div class="panel-body padding_top_15px_res"> <!------  penal body  -------->
                <div class="btn-place"></div>
                <div class="table-responsive"> <!------  table Responsive  -------->
                    <form id="frm-example1" name="frm-example1" method="post">
                        <table id="table_income_expense" class="display" cellspacing="0" width="100%">
                            <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                                <tr>
                                    <th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
                                    <th> <?php esc_attr_e( 'Total Income', 'school-mgt' ) ;?></th>
                                    <th> <?php esc_attr_e( 'Total Expense', 'school-mgt' ) ;?></th>
                                    <th> <?php esc_attr_e( 'Net Profite', 'school-mgt' ) ;?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $net_profit = $income_amount - $expense_amount;
                                ?>
                                <tr>
                                    <td class="user_image width_50px profile_image_prescription padding_left_0">
                                        <p class="prescription_tag padding_15px margin_bottom_0px smgt_class_color0">	
                                            <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
                                        </p>
                                    </td>
                                    <td class="patient"><?php if(!empty($income_amount)){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($income_amount,2,'.','')); }else{ echo MJ_smgt_currency_symbol_position_language_wise(number_format(0,2,'.','')); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Income','school-mgt');?>"></i></td>
                                    <td class="patient_name"><?php if(!empty($expense_amount)){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($expense_amount,2,'.','')); }else{ echo MJ_smgt_currency_symbol_position_language_wise(number_format(0,2,'.','')); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Expense','school-mgt');?>"></i></td>
                                    <td class="income_amount" style="<?php if($net_profit < 0){ echo "color: red !important"; } ?>"><?php if(!empty($net_profit)){ echo MJ_smgt_currency_symbol_position_language_wise(number_format($net_profit,2,'.','')); }else{ echo MJ_smgt_currency_symbol_position_language_wise(number_format(0,2,'.','')); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Net Profit/Loss','school-mgt');?>"></i></td>
                                </tr>
                                  
                            </tbody>        
                        </table>
                    </form>
                </div> <!------  table responsive  -------->
            </div> <!------  penal body  -------->
            <?php
        }
        else
        {
            ?>
            <div class="calendar-event-new"> 
                <img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
            </div>		
            <?php
        }
        ?>
    </div>
    <?php
}