<?php
$active_tab = isset($_GET['tab2'])?$_GET['tab2']:'monthly_attendance_report'; 
?>
<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
    <li class="<?php if($active_tab=='monthly_attendance_report'){?>active<?php }?>">			
        <a href="?page=smgt_report&tab=attendance_report&tab1=student_attendance_report&tab2=monthly_attendance_report" class="padding_left_0 tab <?php echo $active_tab == 'monthly_attendance_report' ? 'active' : ''; ?>">
        <?php esc_html_e('Monthly Attendance Report', 'school-mgt'); ?></a> 
    </li>
    <li class="<?php if($active_tab=='daily_attendance_report'){?>active<?php }?>">			
        <a href="?page=smgt_report&tab=attendance_report&tab1=student_attendance_report&tab2=daily_attendance_report" class="padding_left_0 tab <?php echo $active_tab == 'daily_attendance_report' ? 'active' : ''; ?>">
        <?php esc_html_e('Daily Attendance Report', 'school-mgt'); ?></a> 
    </li>
    <li class="<?php if($active_tab=='attendance_report_datatable'){?>active<?php }?>">
        <a href="?page=smgt_report&tab=attendance_report&tab1=student_attendance_report&tab2=attendance_report_datatable" class="padding_left_0 tab <?php echo $active_tab == 'attendance_report_datatable' ? 'active' : ''; ?>">
        <?php esc_html_e('Attendance Report In Datatable', 'school-mgt'); ?></a> 
    </li> 
    
    <li class="<?php if($active_tab=='attendance_report_graph'){?>active<?php }?>">			
        <a href="?page=smgt_report&tab=attendance_report&tab1=student_attendance_report&tab2=attendance_report_graph" class="padding_left_0 tab <?php echo $active_tab == 'attendance_report_graph' ? 'active' : ''; ?>">
        <?php esc_html_e('Attendance Report In Graph', 'school-mgt'); ?></a> 
    </li>
</ul>
<?php
if($active_tab == 'monthly_attendance_report')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/monthly_attendence_report.php';
}	
if($active_tab == 'daily_attendance_report')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/daily_attendance_report.php';
}	
if($active_tab == 'attendance_report_datatable')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/attendance_report_datatable.php';
}	
if($active_tab == 'attendance_report_graph')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/attendance_report_graph.php';
} 
?>