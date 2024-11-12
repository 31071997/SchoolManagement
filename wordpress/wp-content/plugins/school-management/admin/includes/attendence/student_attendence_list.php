<?php
if(isset($_POST['date_type']))
{
	$date_type_value=$_POST['date_type'];
}
else
{
	$date_type_value='this_month';
}
?>
<div class="popup-bg">
    <div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="category_list">
			</div>
		</div>
    </div>    
</div>
<form method="post" id="attendance_list" class="attendance_list">  
    <div class="form-body user_form margin_top_15px">
        <div class="row">
            <div class="col-md-3 mb-3 input">
                <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>			
                    <select class="line_height_30px form-control date_type validate[required]" name="date_type" autocomplete="off">
                        <!-- <option value=""><?php esc_attr_e('Select','school-mgt');?></option> -->
                        <option <?php selected($date_type_value,'today');  ?> value="today"><?php esc_attr_e('Today','school-mgt');?></option>
                        <option value="this_week" <?php selected($date_type_value,'this_week');  ?>><?php esc_attr_e('This Week','school-mgt');?></option>
                        <option <?php selected($date_type_value,'last_week');  ?> value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>
                        <option value="this_month" <?php selected($date_type_value,'this_month');  ?> ><?php esc_attr_e('This Month','school-mgt');?></option>
                        <option value="last_month" <?php selected($date_type_value,'last_month');  ?>><?php esc_attr_e('Last Month','school-mgt');?></option>
                        <option value="last_3_month" <?php selected($date_type_value,'last_3_month');  ?>><?php esc_attr_e('Last 3 Months','school-mgt');?></option>
                        <option value="last_6_month" <?php selected($date_type_value,'last_6_month');  ?>><?php esc_attr_e('Last 6 Months','school-mgt');?></option>
                        <option value="last_12_month" <?php selected($date_type_value,'last_12_month');  ?>><?php esc_attr_e('Last 12 Months','school-mgt');?></option>
                        <option value="this_year" <?php selected($date_type_value,'this_year');  ?>><?php esc_attr_e('This Year','school-mgt');?></option>
                        <option value="last_year" <?php selected($date_type_value,'last_year');  ?>><?php esc_attr_e('Last Year','school-mgt');?></option>
                        <option value="period" <?php selected($date_type_value,'period');  ?>><?php esc_attr_e('Period','school-mgt');?></option>
                    </select>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 input">
                <label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
                <?php if(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>
                <select name="class_id"  id="attendance_class_list_id" class="form-control max_width_100">
                    <option value="all class"><?php esc_attr_e('All Class','school-mgt');?></option>
                    <?php
                    foreach(mj_smgt_get_allclass() as $classdata)
                    {  
                        ?>
                        <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
                        <?php 
                    }?>
                </select>
            </div>
            <div id="date_type_div" class="date_type_div_none row col-md-6 mb-2"></div>	
            <div class="col-md-3 mb-2">
                <input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
            </div>
        </div>
    </div>
</form> 
<div class="clearfix"></div>
<?php
if(isset($_REQUEST['view_attendance']))
{
    $date_type = $_POST['date_type'];
    $class_id = $_REQUEST['class_id'];
    $attendence_data = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);
}
else
{
    $class_id = '';
    $date_type = '';
    $start_date = date('Y-m-d',strtotime('first day of this month'));
    $end_date = date('Y-m-d',strtotime('last day of this month'));
    $attendence_data = smgt_get_student_attendence_beetween_satrt_date_to_enddate($start_date,$end_date,$class_id,$date_type);
}
if($start_date > $end_date )
{
    echo '<script type="text/javascript">alert("'.esc_html__('End Date should be greater than the Start Date','school-mgt').'");</script>';
}
if(!empty($attendence_data))
{
    
?>
<script type="text/javascript">
    $(document).ready(function() 
    {
        "use strict";
        var table = jQuery('#attend_list').DataTable({
            "initComplete": function(settings, json) {
                $(".print-button").css({"margin-top": "-5%"});
            },
            //stateSave: true,
            dom: 'lifrtp',
            "aoColumns":[
                    {"bSortable": false},
                    {"bSortable": false},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
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
                alert(language_translate2.one_record_select_alert);
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
    } );
</script>
<?php
if(isset($_REQUEST['delete_selected_attendance']))
{		
    if(!empty($_REQUEST['id']))
   
    foreach($_REQUEST['id'] as $id)
    {
        $result = mj_smgt_delete_attendance($id);
    }
      
    if($result)
    { 
        wp_redirect ( admin_url().'admin.php?page=smgt_attendence&tab=student_attendance&message=2');
    }
}
?>
<div class="table-div"><!-- PANEL BODY DIV START -->

    <div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
        <div class="btn-place"></div>	
        <form id="frm-example" name="frm-example" method="post">
        <table id="attend_list" class="display" cellspacing="0" width="100%">
            <thead class="<?php echo MJ_smgt_datatable_heder(); ?>">
                <tr>
                    <th class="checkbox_width_10px text-end"><input type="checkbox" class=" multiple_select select_all" id="select_all"></th>
                    <th><?php esc_html_e('Photo','school-mgt');?></th>
                    <th><?php esc_html_e('Student Name','school-mgt');?></th>
                    <th><?php esc_html_e('Class Name','school-mgt');?></th>
                    <th><?php esc_html_e('Date','school-mgt');?></th>
                    <th><?php esc_html_e('Day','school-mgt');?></th>
                    <th><?php esc_html_e('Attendance Status','school-mgt');?></th>
                    <th><?php esc_html_e('Attendance By','school-mgt');?></th>
                    <th><?php esc_html_e('Attendance With QR Code','school-mgt');?></th>
                    <th><?php esc_html_e('Comment','school-mgt');?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                    foreach ($attendence_data as $retrieved_data)
                    {
                        
                        if(isset($retrieved_data->class_id) && $retrieved_data->class_id)
                        {
                            $class_section_sub_name=smgt_get_class_section_subject($retrieved_data->class_id,$retrieved_data->section_id,$retrieved_data->sub_id);
                            $member_data = get_userdata($retrieved_data->user_id);
                            $created_by = get_userdata($retrieved_data->attend_by);
                            if(!empty($member_data->parent_id))
                            {
                                $parent_data = get_userdata($member_data->parent_id);
                            }
                            if($i == 10)
                            {
                                $i=0;
                            }
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
                                <td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->attendance_id;?>"></td>
                                <td class="cursor_pointer user_image width_50px profile_image_prescription padding_left_0">
                                    <p class="remainder_title_pr Bold prescription_tag para_margin <?php echo $color_class; ?>">	
                                        <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Attendance.png"?>" alt="" class="massage_image center" style="padding-top:28%;">
                                    </p>
                                </td>
                                <td class="name">

                                    <?php if(!empty($member_data->ID)){ echo mj_smgt_student_display_name_with_roll($member_data->ID);}else{echo "N/A";}?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i>
                                </td>
                                <td class="name">
                                    <?php echo $class_section_sub_name; ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i>
                                </td>
                                <td class="name">
                                    <?php echo mj_smgt_getdate_in_input_box($retrieved_data->attendance_date); ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Date','school-mgt');?>" ></i>
                                </td>
                                <td class="name">
                                    <?php 
                                        $day=date("l", strtotime($retrieved_data->attendance_date));    
                                        echo esc_html__($day,"school-mgt");
                                    ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Day','school-mgt');?>" ></i>
                                </td>
                                <td class="name">
                                    <?php $status_color =  MJ_smgt_attendance_status_color($retrieved_data->status);?>
                                    <span style="color:<?php echo $status_color;?>;">
                                        <?php echo esc_html__($retrieved_data->status,"school-mgt"); ?>
                                    </span>
                                    
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance Status','school-mgt');?>" ></i>
                                </td>

                                <td class="name">
                                    <?php echo esc_html__($created_by->display_name,"school-mgt"); ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance By','school-mgt');?>" ></i>
                                </td>

                                <td class="name">
                                    <?php
                                    if ($retrieved_data->attendence_type == 'QR') {
                                        echo esc_html__("Yes","school-mgt");
                                    }
                                    else{
                                        echo esc_html__("No","school-mgt");
                                    }
                                     ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Attendance With QR Code','school-mgt');?>" ></i>
                                </td>

                                <td class="name">
                                    <?php
                                    if(!empty($retrieved_data->comment))
                                    {   $comment = $retrieved_data->comment;
                                        $grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
                                        echo $grade_comment;
                                    }
                                    else
                                    {
                                        echo "N/A";
                                    }
                                    ?>
                                    <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php 
                                    if(!empty($retrieved_data->comment))
                                    { 
                                        echo $retrieved_data->comment;
                                    } 
                                    else
                                    {  _e('Comment','school-mgt');
                                    }
                                    ?>
                                    "></i>
                                </td>
                                
                                
                            </tr>
                            <?php 
                        }
                        $i++;
                    }
                ?>
            </tbody>
        </table>
        <div class="print-button pull-left">
            <button class="btn-sms-color button_reload">
                <input type="checkbox" name="" class="smgt_sub_chk select-checkbox select_all" value="" style="margin-top: 0px;">
                <label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
            </button>
                <button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_attendance" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
                <input type="hidden" name="filtered_date_type" value="<?php echo $date_type;?>" />
                <input type="hidden" name="filtered_class_id" value="<?php echo $class_id;?>" />
                <button data-toggle="tooltip" title="<?php esc_html_e('Export Attendance','school-mgt');?>" name="export_attendance_in_csv" class="export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button>

                <button data-toggle="tooltip"  title="<?php esc_html_e('Import Attendance','school-mgt');?>" type="button" class="import_attendance_popup export_import_csv_btn padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/import_csv.png" ?>" alt=""></button>
        </div>
        </form>
    </div><!-- TABLE RESPONSIVE DIV END -->
</div>
<?php
}
else
{
    ?>
    <div class="no_data_list_div row">
        
            <a data-toggle="tooltip"  name="import_csv" type="button" class="import_attendance_popup">
                <img src="<?php echo SMS_PLUGIN_URL."/assets/images/thumb_icon/Import_list.png" ?>" alt="">
            </a>
            <div class="col-md-12 dashboard_btn margin_top_20px">
                <label class="no_data_list_label"><?php esc_html_e('Tap on above icon to import CSV.','school-mgt'); ?> </label>
            </div> 
        
    </div>		
    <?php
}
?>