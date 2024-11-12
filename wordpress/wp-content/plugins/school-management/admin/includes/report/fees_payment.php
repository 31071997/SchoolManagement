<?php
$active_tab = isset($_GET['tab2'])?$_GET['tab2']:'fees_payment_graph'; 
$role=mj_smgt_get_roles(get_current_user_id());

?>
<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
    <li class="<?php if($active_tab=='fees_payment_graph'){?>active<?php }?>">
        <a href="<?php if($role == 'administrator'){ echo "?page=smgt_report"; }else{ echo "?dashboard=user&page=report"; }?>&tab=fianance_report&tab1=fees_payment&tab2=fees_payment_graph"
            class="padding_left_0 tab <?php echo $active_tab == 'fees_payment_graph' ? 'active' : ''; ?>">
            <?php esc_html_e('Graph', 'school-mgt'); ?></a>
    </li>
    <li class="<?php if($active_tab=='fees_payment_datatable'){?>active<?php }?>">
        <a href="<?php if($role == 'administrator'){ echo "?page=smgt_report"; }else{ echo "?dashboard=user&page=report"; }?>&tab=fianance_report&tab1=fees_payment&tab2=fees_payment_datatable"
            class="padding_left_0 tab <?php echo $active_tab == 'fees_payment_datatable' ? 'active' : ''; ?>">
            <?php esc_html_e('DataTable', 'school-mgt'); ?></a>
    </li>
</ul>
<?php
if($active_tab == "fees_payment_graph")
{
    ?>
<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
    <?php	
        $month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),'5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),'9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('October','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);
    
        $year =isset($_POST['year'])?$_POST['year']:date('Y');
    
        $chart_array = array();
        //$chart_array[] = array(esc_html__('Month','school-mgt'),esc_html__('Fees Payment','school-mgt'));
        array_push($chart_array, array(esc_html__('Month','school-mgt'),esc_html__('Payment','school-mgt')));
        $sumArray = array(); 
        foreach($month as $key=>$value)
        {
            global $wpdb;
            $table_name = $wpdb->prefix."smgt_fees_payment";
            $q="SELECT * FROM ".$table_name." WHERE MONTH(paid_by_date) = $key AND YEAR(paid_by_date) =$year group by month(paid_by_date) ORDER BY paid_by_date ASC";
            $result=$wpdb->get_results($q);		
            $currency = mj_smgt_get_currency_symbol();
            $income_yearly_amount = 0;
		    $currency_1 = html_entity_decode($currency);
            if(!empty($result))
            {
                $amount=0;
                foreach ($result as $retrieved_data) 
                { 
                    $amount+= $retrieved_data->total_amount;
                }
                $income_yearly_amount += $amount;
            }
            if($income_yearly_amount == 0)
            {
                $income_amount = null;
            }
            else
            {
                $income_amount = $currency_1.''.$income_yearly_amount;
            }
            $feepayment_array[] = $income_amount;
            array_push($chart_array, array($value,$income_amount));
        }
        $new_array = json_encode($chart_array);
        $feepayment = array_filter($feepayment_array);
        if(!empty($feepayment)){
        ?>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['bar']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(<?php echo $new_array; ?>);

                var options = {

                    bars: 'vertical', // Required for Material Bar Charts.
                    colors: ['<?php echo get_option('smgt_system_color_code'); ?>'],

                };

                var chart = new google.charts.Bar(document.getElementById('barchart_material_fees'));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
            </script>
            <div id="barchart_material_fees" style="width:100%;height: 430px; padding:20px;"></div>
        <?php
        }
        else{
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
if($active_tab == "fees_payment_datatable")
{
    ?>
<div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
    <!--- penal body --->
    <!--------------- FEES PAYMENT FORM -------------------->
    <form method="post" id="fee_payment_report">
        <div class="form-body user_form">
            <!-------------- FORM BODY ------------------>
            <div class="row">
                <div class="col-md-6 input">
                    <label class="ml-1 custom-top-label top"
                        for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span
                            class="require-field">*</span></label>
                    <select name="class_id" id="class_list"
                        class="line_height_30px form-control load_fee_type_single validate[required]">
                        <?php
                                $select_class = isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
                            ?>
                        <option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
                        <option value="all_class" <?php echo selected($select_class,'all_class');?>><?php esc_attr_e('All Class','school-mgt');?></option>
                        <?php
                            foreach(mj_smgt_get_allclass() as $classdata)
                            {
                                ?>
                        <option value="<?php echo $classdata['class_id'];?>"
                            <?php echo selected($select_class,$classdata['class_id']);?>>
                            <?php echo $classdata['class_name'];?></option>
                        <?php 
                            }?>
                    </select>
                </div>
                <div class="col-md-6 input">
                    <label class="ml-1 custom-top-label top"
                        for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
                    <?php
                        $class_section="";
                        if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
                    <select name="class_section" class="line_height_30px form-control" id="class_section">
                        <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
                        <?php if(isset($_REQUEST['class_section'])){
                                    echo $class_section=$_REQUEST['class_section'];
                                    foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
                                    {  ?>
                        <option value="<?php echo $sectiondata->id;?>"
                            <?php selected($class_section,$sectiondata->id);  ?>>
                            <?php echo $sectiondata->section_name;?></option>
                        <?php }
                                }?>
                    </select>
                </div>
                <div class="col-md-6 input">
                    <label class="ml-1 custom-top-label top"
                        for="hmgt_contry"><?php esc_html_e('FeesType','school-mgt');?><span
                            class="require-field">*</span></label>
                    <select id="fees_data" class="line_height_30px form-control validate[required]" name="fees_id">
                        <option value=" "><?php esc_attr_e('Select Fee Type','school-mgt');?></option>
                        <?php
                                if(isset($_REQUEST['fees_id']))
                                {
                                    echo '<option value="'.$_REQUEST['fees_id'].'" '.selected($_REQUEST['fees_id'],$_REQUEST['fees_id']).'>'.mj_smgt_get_fees_term_name($_REQUEST['fees_id']).'</option>';
                                }
                            ?>
                    </select>
                </div>
                <div class="col-md-6 input error_msg_left_margin">
                    <label class="ml-1 custom-top-label top"
                        for="hmgt_contry"><?php esc_html_e('Payment Status','school-mgt');?><span
                            class="require-field">*</span></label>
                    <select id="fee_status" class="line_height_30px form-control validate[required]" name="fee_status">
                        <?php
                            $select_payment = isset($_REQUEST['fee_status'])?$_REQUEST['fee_status']:'';?>
                        <option value=" "><?php esc_attr_e('Select Payment Status','school-mgt');?></option>
                        <option value="0" <?php echo selected($select_payment,0);?>>
                            <?php esc_attr_e('Not Paid','school-mgt');?></option>
                        <option value="1" <?php echo selected($select_payment,1);?>>
                            <?php esc_attr_e('Partially Paid','school-mgt');?></option>
                        <option value="2" <?php echo selected($select_payment,2);?>>
                            <?php esc_attr_e('Fully paid','school-mgt');?></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-group input">
                        <div class="col-md-12 form-control">
                            <input type="text" id="sdate" class="form-control" name="sdate"
                                value="<?php if(isset($_REQUEST['sdate'])){ echo mj_smgt_getdate_in_input_box($_REQUEST['sdate']);}else { echo mj_smgt_getdate_in_input_box(date('Y-m-d',strtotime('first day of this month')));}?>"
                                readonly>
                            <label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group input">
                        <div class="col-md-12 form-control">
                            <input type="text" id="edate" class="form-control" name="edate"
                                value="<?php if(isset($_REQUEST['edate'])) echo mj_smgt_getdate_in_input_box($_REQUEST['edate']);else echo mj_smgt_getdate_in_input_box(date('Y-m-d'));?>"
                                readonly>
                            <label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="submit" name="report_4" Value="<?php esc_attr_e('Go','school-mgt');?>"
                        class="btn btn-info save_btn" />
                </div>
            </div>
        </div>
        <!-------------- FORM BODY ------------------>
    </form>
    <!--------------- FEES PAYMENT FORM -------------------->
</div>
<!--- penal body --->
<div class="clearfix"> </div>
<?php
    if(isset($_POST['report_4']))
    {
        if($_POST['class_id']!=' ' && $_POST['fees_id']!=' ' && $_POST['sdate']!=' ' && $_POST['edate']!=' ')
        {
            $class_id = $_POST['class_id'];
            $section_id=0;
            if(isset($_POST['class_section']))
                $section_id = $_POST['class_section'];
            $fee_term =$_POST['fees_id'];
            $payment_status = $_POST['fee_status'];
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            $result_feereport = mj_smgt_get_payment_report_front($class_id,$fee_term,$payment_status,$sdate,$edate,$section_id);
        }
        if(!empty($result_feereport))
        {
            ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var table = jQuery('.example4').DataTable({
        //stateSave: true,
        responsive: true,
        "order": [
            [1, "asc"]
        ],
        "dom": 'lifrtp',
        buttons: [{
                extend: 'csv',
                text: '<?php echo esc_attr_e("csv","school-mgt"); ?>',
                title: '<?php echo esc_attr_e("Fees Payment Report","school-mgt"); ?>',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7] // Include only columns 2 to 7 (index 1 to 6)
                }
            },
            {
                extend: 'print',
                text: '<?php echo esc_attr_e("Print","school-mgt"); ?>',
                title: '<?php echo esc_attr_e("Fees Payment Report","school-mgt"); ?>',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7] // Include only columns 2 to 7 (index 1 to 6)
                }
            },
        ],
        "aoColumns": [
                {"bSortable": false},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true,'width':'180px'},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true}
        ],
        language: <?php echo mj_smgt_datatable_multi_language(); ?>
    });
    $('.btn-place').html(table.buttons().container());
    $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
});
</script>
<div class="table-responsive">
    <!-------------- TABLE RESPONSIVE ---------------->
    <div class="btn-place"></div>
    <table id="" class="display example4" cellspacing="0" width="100%">
        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
            <tr>
                <th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Fees Term', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Class Name', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Payment Status', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Total Amount', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Due Amount', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Start To End Year', 'school-mgt' ) ;?></th>
                <th> <?php esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                        if(!empty($result_feereport))
                        {
                            $i=0;
                            foreach ($result_feereport as $retrieved_data)
                            { 
                                if($i == 0)
                                {
                                    $color_class='smgt_class_color0';
                                }
                                elseif($i == 1)
                                {
                                    $color_class='smgt_class_color1';
                                }
                                elseif($i == 2)
                                {
                                    $color_class='smgt_class_color2';
                                }
                                elseif($i == 3)
                                {
                                    $color_class='smgt_class_color3';
                                }
                                elseif($i == 4)
                                {
                                    $color_class='smgt_class_color4';
                                }
                                elseif($i == 5)
                                {
                                    $color_class='smgt_class_color5';
                                }
                                elseif($i == 6)
                                {
                                    $color_class='smgt_class_color6';
                                }
                                elseif($i == 7)
                                {
                                    $color_class='smgt_class_color7';
                                }
                                elseif($i == 8)
                                {
                                    $color_class='smgt_class_color8';
                                }
                                elseif($i == 9)
                                {
                                    $color_class='smgt_class_color9';
                                }
                                ?>
            <tr>
                <td class="user_image width_50px profile_image_prescription padding_left_0">
                    <p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">
                        <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>"
                            alt="" class="massage_image center margin_top_3px">
                    </p>
                </td>
                <?php
                                    $fees_id=explode(',',$retrieved_data->fees_id);
                                    $fees_type=array();
                                    foreach($fees_id as $id)
                                    { 
                                        $fees_type[] = mj_smgt_get_fees_term_name($id);
                                    }
                                    
                                    ?>
                <td><?php echo implode(" , " ,$fees_type); ?> <i class="fa fa-info-circle fa_information_bg"
                        data-toggle="tooltip" title="<?php esc_html_e('Fees Term','school-mgt');?>"></i></td>
                <td><?php if(!empty($retrieved_data)){echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);}else{echo '';}?>
                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip"
                        title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>
                        <td><?php if($retrieved_data->class_id == "0"){ esc_html_e('All Class','school-mgt');}else{ echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
                <td>
                    <?php 
                                        $payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);
                                        if($payment_status == 'Not Paid')
                                        {
                                        echo "<span class='red_color'>";
                                        }
                                        elseif($payment_status == 'Partially Paid')
                                        {
                                            echo "<span class='perpal_color'>";
                                        }
                                        else
                                        {
                                            echo "<span class='green_color'>";
                                        }
                                        echo esc_html__("$payment_status","school-mgt");
                                        echo "</span>";	
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip"
                        title="<?php esc_html_e('Payment Status','school-mgt');?>"></i>
                </td>
                <td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->total_amount,2,'.',''));?> <i
                        class="fa fa-info-circle fa_information_bg" data-toggle="tooltip"
                        title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
                <?php
                                    $Due_amt = $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;
                                    ?>
                <td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($Due_amt,2,'.',''));?> <i
                        class="fa fa-info-circle fa_information_bg" data-toggle="tooltip"
                        title="<?php esc_html_e('Due Amount','school-mgt');?>"></i></td>
                <td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?> <i
                        class="fa fa-info-circle fa_information_bg" data-toggle="tooltip"
                        title="<?php esc_html_e('Start To End Year','school-mgt');?>"></i></td>
                <td class="action">
                    <div class="smgt-user-dropdown">
                        <ul class="" style="margin-bottom: 0px !important;">
                            <li class="">
                                <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>">
                                </a>
                                <ul class="dropdown-menu heder-dropdown-menu action_dropdawn"
                                    aria-labelledby="dropdownMenuLink">
                                    <li class="float_left_width_100 ">
                                        <a href="<?php if($role == 'administrator'){ echo "?page=smgt_fees_payment&tab=view_fesspayment"; }else{ echo "?dashboard=user&page=feepayment&tab=view_fesspayment"; }?>&idtest=<?php echo $retrieved_data->fees_pay_id; ?>&view_type=view_payment"
                                            class="float_left_width_100"><i
                                                class="fa fa-eye"></i><?php esc_attr_e('View','school-mgt');?></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php 
                                $i++;
                            }
                        }
                        ?>
        </tbody>
    </table>
</div>
<!-------------- TABLE RESPONSIVE ---------------->
<?php
        }
        else
        {
            ?>
<div class="calendar-event-new">
    <img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>">
</div>
<?php
        }
    }
}