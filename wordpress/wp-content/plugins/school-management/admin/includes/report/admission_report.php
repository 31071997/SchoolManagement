<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
        <form method="post" id="student_attendance">  
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
                    <div id="date_type_div" class="date_type_div_none col-md-6 mb-2">
                    </div>	
                    <div class="col-md-3 mb-2">
                        <input type="submit" name="admission_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                    </div>
                </div>
            </div>
        </form> 
	</div>	
    <?php
    //-------------- ADMISSION REPORT - DATA ---------------//
    if(isset($_REQUEST['admission_report']))
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
        $admission=mj_smgt_get_all_admission_by_start_date_to_end_date($start_date,$end_date);
         
    }
    else
    {
        $start_date = date('Y-m-d');
		$end_date= date('Y-m-d');
        $admission=mj_smgt_get_all_admission_by_start_date_to_end_date($start_date,$end_date);
    }
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#admission_list_report').DataTable({
                //stateSave: true,
                "responsive": true,
                "order": [[ 2, "Desc" ]],
                "dom": 'lifrtp',
                buttons:[
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Admission Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Admission Report","school-mgt"); ?>',
                    },
                ],
                "aoColumns":[                 
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true}, 
                    {"bSortable": true}, 
                    {"bSortable": true}, 
                    {"bSortable": true}],
                language:<?php echo mj_smgt_datatable_multi_language();?>
                });
            $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
            $('.btn-place').html(table.buttons().container()); 
        });
    </script>
    <div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($admission))
        {
            ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                    <h4 class="report_heder"><?php esc_html_e('Admission Report','school-mgt');?></h4>
                </div>
            </div>
            <div class="table-responsive">
                <div  class="btn-place"></div>
                <form id="frm-admisssion" name="frm-admisssion" method="post">
                    <table id="admission_list_report" class="display admission_report_tbl" cellspacing="0" width="100%">
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th><?php esc_attr_e('Admission No','school-mgt');?>.</th>
                                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Email Id','school-mgt');?></th>
                                <!-- <th><?php esc_attr_e('Class (Section)','school-mgt');?></th> -->
                                <th><?php esc_attr_e('Date of Birth','school-mgt');?></th>
                                <th><?php esc_attr_e('Admission Date','school-mgt');?></th>
                                <th><?php esc_attr_e('Gender','school-mgt');?></th>
                                <th><?php esc_attr_e('Mobile Number','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            foreach ($admission as $retrieved_data)
                            { 
                                $student_data=get_userdata($retrieved_data->ID);
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                            if(get_user_meta($retrieved_data->ID, 'admission_no', true))
                                            {
                                                echo get_user_meta($retrieved_data->ID, 'admission_no',true);
                                            }
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Number','school-mgt');?>"></i>
                                    </td>
                                    <td>  
                                        <?php echo $student_data->display_name; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
                                    </td>
                                    <td>  
                                        <?php echo $retrieved_data->user_email;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Email ID','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date of Birth','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo $student_data->admission_date; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Date','school-mgt');?>"></i> 
                                    </td>
                                    <td>
                                        <?php 
                                            if($student_data->gender=='male') 
                                            {
                                                echo esc_attr__('Male','school-mgt');
                                            }
                                            elseif($student_data->gender=='female') 
                                            {
                                                echo esc_attr__('Female','school-mgt');
                                            }
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Gender','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo $student_data->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile Number','school-mgt');?>"></i>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>       
                        </tbody>        
                    </table>
                </form>
            </div>
            <?php
        } 
        else
        {
            ?>
            <div class="calendar-event-new"> 
                <img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
            </div>	
            <?php
        }  ?>
    </div>
</div>	