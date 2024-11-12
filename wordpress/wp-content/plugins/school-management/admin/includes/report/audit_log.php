<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res"> <!------  penal body  -------->
    <div class="panel-body clearfix">
        <form method="post" id="student_attendance">  
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-6 mb-6 input">
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
                    <div class="col-md-6 mb-6 input">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Action','school-mgt');?></label>			
                        <select class="line_height_30px form-control date_action_filter" name="date_action" autocomplete="off">
                          
                            <option value="all"><?php esc_attr_e('All','school-mgt');?></option>
                            <option value="edit"><?php esc_attr_e('Edit Action','school-mgt');?></option>
                            <option value="insert"><?php esc_attr_e('Insert Action','school-mgt');?></option>
                            <option value="delete"><?php esc_attr_e('Delete Action','school-mgt');?></option>
                        </select>
                    </div>
                    <div id="date_type_div" class="date_type_div_none col-md-6 mb-2"></div>	
                    <div class="col-md-3 mb-2">
                        <input type="submit" name="audit_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                    </div>
                </div>
            </div>
        </form> 
    </div>	

    <?php
    if(isset($_REQUEST['audit_report']))
    {
        $date_type = $_POST['date_type'];
        $date_action = $_POST['date_action'];
    
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
    }
    else
    {
        $date_action = "all";
        $start_date = date('Y-m-d');
        $end_date= date('Y-m-d');
    }

    if($date_action == "all" || $date_action == "")
    {
        global $wpdb;
        $table_audit_log=$wpdb->prefix.'smgt_audit_log';
        $report_6 = $wpdb->get_results("SELECT * FROM $table_audit_log where created_at BETWEEN '$start_date' AND '$end_date'");
    }
    else
    {
        global $wpdb;
        $table_audit_log=$wpdb->prefix.'smgt_audit_log';
        $report_6 = $wpdb->get_results("SELECT * FROM $table_audit_log where action='$date_action' AND created_at BETWEEN '$start_date' AND '$end_date'");
    }

    if(!empty($report_6))
    {
        ?>
          <script type="text/javascript">
            jQuery(document).ready(function($){
                var table = jQuery('#tble_audit_log_').DataTable({
                    "initComplete": function(settings, json) {
                        $(".print-button").css({"margin-top": "-5%"});
                    },
                    //stateSave: true,
                    "responsive": true,
                    "order": [[ 2, "Desc" ]],
                    "dom": 'lifrtp',
                    buttons:[
                        {
                            extend: 'csv',
                            text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                            title: '<?php echo esc_attr_e("Audit Trail Report","school-mgt"); ?>',
                        },
                        {
                            extend: 'print',
                            text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                            title: '<?php echo esc_attr_e("Audit Trail Report","school-mgt"); ?>',
                        },
                    ],
                    "aoColumns":[
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": true},
                        {"bSortable": true}
                    ],
                    language:<?php echo mj_smgt_datatable_multi_language();?>
                });
                $('.btn-place').html(table.buttons().container()); 
                $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
                $("#delete_selected").on('click', function()
								{	
									if ($('.select-checkbox:checked').length == 0 )
									{
										confirm(language_translate2.one_record_select_alert);
										return false;
									}
									else
									{
											var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?', 'school-mgt') ?>");
											if(alert_msg == false)
											{
												return false;
											}
											else
											{
												return true;
											}
									}
								});
								$('.select_all').on('click', function(e)
								{
									if($(this).is(':checked',true))  
									{
										$(".smgt_sub_chk").prop('checked', true);  
									}  
									else  
									{  
										$(".smgt_sub_chk").prop('checked',false);  
									} 
								});
								$('.smgt_sub_chk').on('change',function()
								{ 
									if(false == $(this).prop("checked"))
									{ 
										$(".select_all").prop('checked', false); 
									}
									if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )
									{
										$(".select_all").prop('checked', true);
									}
								});
								jQuery('#checkbox-select-all').on('click', function(){     
									var rows = table.rows({ 'search': 'applied' }).nodes();
									jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
								}); 
					});
        </script>
        <?php
        $obj_feespayment= new mj_smgt_feespayment();
        if(isset($_REQUEST['delete_selected_audit_log']))
        {		
            if(!empty($_REQUEST['id']))
            foreach($_REQUEST['id'] as $id)
                    $result = mj_smgt_delete_audit_log($id);
       
            if($result)
            { 
                ?>
                <div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
                    <p><?php esc_attr_e('Record Deleted Successfully.','school-mgt'); ?></p>
                    <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
                <?php 
            }
        }

        
        ?>
        <div class="panel-body padding_top_15px_res"> <!------  penal body  -------->
            <div class="btn-place"></div>
            <div class="table-responsive"> <!------  table Responsive  -------->
                <form id="frm-example" name="frm-example" method="post">
                    <table id="tble_audit_log_" class="display" cellspacing="0" width="100%">
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th class="padding_0"><input type="checkbox" class=" multiple_select select_all" id="select_all"></th>
                                <th> <?php esc_attr_e( 'Message', 'school-mgt' ) ;?></th>
                                <th> <?php esc_attr_e( 'IP Address', 'school-mgt' ) ;?></th>
                                <th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($report_6 as $result)
                            {	
                                ?>
                                <tr>
                                    <td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $result->id;?>"></td>
                                    <td class="patient"><?php if(!empty($result->audit_action)){ echo $result->audit_action; }else{ echo "N/A"; } ?><?php echo ' '.'By'.' '.mj_smgt_get_user_name_byid($result->created_by); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Message','school-mgt');?>"></i></td>
                                    <td class="income_amount"><?php if(!empty($result->ip_address)){ echo $result->ip_address; }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('IP Address','school-mgt');?>"></i></td>
                                    <td class="status"><?php if(!empty($result->date_time)){ echo mj_smgt_getdate_in_input_box($result->date_time); }else{ echo "N/A"; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date & Time','school-mgt');?>"></i></td>
                                </tr>
                                <?php 
                            } 
                            ?>     
                        </tbody>        
                    </table>
                    <div class="print-button pull-left">
                        <button class="btn-sms-color button_reload">
                            <input type="checkbox" name="id[]" class="smgt_sub_chk select_all" value="<?php echo esc_attr($result->id); ?>" style="margin-top: 0px;">
                            <label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
                        </button>
                            <button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_audit_log" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
                    </div>
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
</div> <!------  penal body  -------->