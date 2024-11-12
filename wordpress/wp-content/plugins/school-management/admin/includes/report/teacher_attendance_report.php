<?php
$active_tab = isset($_GET['tab2'])?$_GET['tab2']:'teacher_attendance_report_datatable'; 
?>
<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
    <li class="<?php if($active_tab=='teacher_attendance_report_datatable'){?>active<?php }?>">
        <a href="?page=smgt_report&tab=attendance_report&tab1=teacher_attendance_report&tab2=teacher_attendance_report_datatable" class="padding_left_0 tab <?php echo $active_tab == 'teacher_attendance_report_datatable' ? 'active' : ''; ?>">
        <?php esc_html_e('Attendance Report In Datatable', 'school-mgt'); ?></a> 
    </li> 
    
    <li class="<?php if($active_tab=='teacher_attendance_report_graph'){?>active<?php }?>">			
        <a href="?page=smgt_report&tab=attendance_report&tab1=teacher_attendance_report&tab2=teacher_attendance_report_graph" class="padding_left_0 tab <?php echo $active_tab == 'teacher_attendance_report_graph' ? 'active' : ''; ?>">
        <?php esc_html_e('Attendance Report In Graph', 'school-mgt'); ?></a> 
    </li>
</ul>
<?php
if($active_tab == 'teacher_attendance_report_datatable')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/teacher_attendance_report_datatable.php';
}	
if($active_tab == 'teacher_attendance_report_graph')
{ 				
	require_once SMS_PLUGIN_DIR.'/admin/includes/report/teacher_attendance_report_graph.php';
}
?>