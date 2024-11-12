<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";
	$('#failed_report').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	$('#student_attendance').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

	$("#sdate").datepicker({
		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		changeYear: true,
		changeMonth: true,
		maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#edate").datepicker("option", "minDate", dt);
        }
    });
	var table = jQuery('#teacher_attendance_list_report').DataTable({
		//stateSave: true,
			"responsive": true,
			"order": [[ 2, "Desc" ]],
			"dom": 'lifrtp',
			"aoColumns":[
				{"bSortable": false},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true},
				{"bSortable": true}

			],
			language:<?php echo mj_smgt_datatable_multi_language();?>
		});
		$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

    $("#edate").datepicker({
		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
	   changeYear: true,
	   changeMonth: true,
	   maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $("#sdate").datepicker("option", "maxDate", dt);
        }
    });

     $('#fee_payment_report').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});

    $('.sdate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",changeYear: true,changeMonth:true});
    $('.edate').datepicker({dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",changeMonth: true,changeMonth:true});

			var table = jQuery('#tble_audit_log').DataTable({
				//stateSave: true,
				"responsive": true,
				"order": [[ 2, "Desc" ]],
				"dom": 'lifrtp',
				"aoColumns":[
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}
				],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

			var table = jQuery('#tble_login_log1').DataTable({
				"responsive": true,
				"order": [[ 2, "Desc" ]],
				"dom": 'lifrtp',
				"aoColumns":[
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}
				],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

			var table = jQuery('#table_income_expense').DataTable({
				//stateSave: true,
				"responsive": true,
				"order": [[ 2, "Desc" ]],
				"dom": 'lifrtp',
				"aoColumns":[
					{"bSortable": false},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}
				],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

	var table = jQuery('#attendance_list_report').DataTable({
		//stateSave: true,
		"responsive": true,
		"order": [[ 2, "Desc" ]],
		"dom": 'lifrtp',
		"aoColumns":[
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
	$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

	 $('#fee_payment_report').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
	 $('#sdate').datepicker({
		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
		 });
	 $('#edate').datepicker({
		dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance)
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'
				});
			}
		 });
});
</script>
<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{
	if($user_access['view']=='0')
	{
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{
				mj_smgt_access_right_page_not_access_message();
				die;
			}
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{
				mj_smgt_access_right_page_not_access_message();
				die;
			}
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{
				mj_smgt_access_right_page_not_access_message();
				die;
			}
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'report1';
$obj_marks = new Marks_Manage();
if($active_tab == 'report1')
{
	$chart_array = array();
	$chart_array[] = array( esc_attr__('Class','school-mgt'),esc_attr__('No. of Student Fail','school-mgt'));

	if(isset($_REQUEST['report_1']))
	{
		global $wpdb;
		$table_marks = $wpdb->prefix .'marks';
		$table_users = $wpdb->prefix .'users';
		$exam_id = $_REQUEST['exam_id'];
		$class_id = $_REQUEST['class_id'];
			if(isset($_REQUEST['class_section']) && $_REQUEST['class_section']!="")
			{
				$section_id = $_REQUEST['class_section'];
				$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
					FROM $table_marks as m, $table_users as u
					WHERE m.marks <40
					AND m.exam_id = $exam_id
					AND m.Class_id = $class_id
					AND m.section_id = $section_id
					AND m.student_id = u.id
					GROUP BY subject_id");
			}
			else
			{
				$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
					FROM $table_marks as m, $table_users as u
					WHERE m.marks <40
					AND m.exam_id = $exam_id
					AND m.Class_id = $class_id
					AND m.student_id = u.id
					GROUP BY subject_id");
			}
		if(!empty($report_1))
		foreach($report_1 as $result)
		{

			$subject =mj_smgt_get_single_subject_name($result->subject_id);
			$chart_array[] = array("$subject",(int)$result->count);
		}

		$options = Array(
				'title' => esc_attr__('Exam Failed Report','school-mgt'),
				'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins')),

				'hAxis' => Array(
						'title' =>  esc_attr__('Subject','school-mgt'),
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
						'textStyle' => Array('color' => '#222','fontSize' => 10),
						'maxAlternation' => 2
				),
				'vAxis' => Array(
						'title' =>  esc_attr__('No. of Students','school-mgt'),
						'minValue' => 0,
						'maxValue' => 5,
						'format' => '#',
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
						'textStyle' => Array('color' => '#222','fontSize' => 12)
				),
				'colors' => array('#22BAA0')
		);

	}
}
if($active_tab == 'report2')
{
	$chart_array[] = array(esc_attr__('Class','school-mgt'),esc_attr__('Present','school-mgt'),esc_attr__('Absent','school-mgt'));

	global $wpdb;
	$table_attendance = $wpdb->prefix .'attendence';
	$table_class = $wpdb->prefix .'smgt_class';
	if(isset($_POST['report_2']))
	{
		$sdate = $_POST['sdate'];
		$edate = $_POST['edate'];
	}
	else
	{
		$sdate = date('Y-m-d',strtotime('first day of this month'));
		$edate = date('Y-m-d',strtotime('last day of this month'));
	}

	$report_2 =$wpdb->get_results("SELECT  at.class_id,
	SUM(case when `status` ='Present' then 1 else 0 end) as Present,
	SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
	from $table_attendance as at,$table_class as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id") ;

	if(!empty($report_2))
		foreach($report_2 as $result)
		{
			$class_id =mj_smgt_get_class_name($result->class_id);
			$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
		}

	$options = Array(
			'title' => esc_attr__('Attendance Report','school-mgt'),
			'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),

			'hAxis' => Array(
					'title' =>  esc_attr__('Class','school-mgt'),
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'maxAlternation' => 2


			),
			'vAxis' => Array(
					'title' =>  esc_attr__('No. of Students','school-mgt'),
					'minValue' => 0,
					'maxValue' => 5,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
			),
			'colors' => array('#22BAA0','#f25656')
	);
}
if($active_tab == 'report3')
{
	$chart_array[] = array(esc_attr__('Teacher','school-mgt'),esc_attr__('Fail','school-mgt'));
	global $wpdb;
	$table_subject = $wpdb->prefix .'subject';
	$table_name_mark = $wpdb->prefix .'marks';
	$table_name_users = $wpdb->prefix .'users';
	$table_teacher_subject = $wpdb->prefix .'teacher_subject';
	$own_data=$user_access['own_data'];
	if($own_data == '1')
	{
		$teachers[] = get_userdata(get_current_user_id());
	}
	else
	{
		$teachers = get_users(array("role"=>"teacher"));
	}
	$report_3 = array();
	if(!empty($teachers))
	{
		foreach($teachers as $teacher)
		{
			$report_3[$teacher->ID] = mj_smgt_get_subject_id_by_teacher($teacher->ID);
		}
	}
	if(!empty($report_3))
	{
		foreach($report_3 as $teacher_id=>$subject)
		{
			if(!empty($subject))
			{
				$sub_str = implode(",",$subject);
				$count = $wpdb->get_results("SELECT COUNT(*) as count FROM {$table_name_mark} WHERE marks < 40 AND subject_id in ({$sub_str}) GROUP by subject_id",ARRAY_A);
				$total_fail = array_sum(array_column($count,"count"));
			}
			else
			{
				$total_fail=0;
			}
			$teacher_name = mj_smgt_get_display_name($teacher_id);
			$chart_array[] = [$teacher_name , $total_fail];
		}
	}
	$options = Array(
			'title' => esc_attr__('Teacher Perfomance Report','school-mgt'),
			'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins')),

			'hAxis' => Array(
					'title' =>  esc_attr__('Teacher Name','school-mgt'),
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
					'textStyle' => Array('color' => '#222','fontSize' => 10),
					'maxAlternation' => 2
			),
			'vAxis' => Array(
					'title' =>  esc_attr__('No. of Students','school-mgt'),
					'minValue' => 0,
					'maxValue' => 5,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
					'textStyle' => Array('color' => '#222','fontSize' => 12)
			),
			'colors' => array('#22BAA0')
	);
}
require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
?>
<!-- POP up code -->
<div class="popup-bg">
	<div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data">
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>
<!-- End POP-UP Code -->
<div class="panel-white"><!----------- PENAL WHITE ------------->
	<div class="panel-body frontend_list_margin_30px_res"> <!----------- PENAL BODY ------------->
		<!-- tabing start  -->

		<!-- tabing End  -->
		<?php
		if($active_tab == 'student_information_report')
		{
			$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'student_report';
			?>
			<div class="clearfix"> </div>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='student_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=student_report" class="padding_left_0 tab <?php echo $active_tab == 'student_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Student Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='class_section_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=class_section_report" class="padding_left_0 tab <?php echo $active_tab == 'class_section_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Class & Section Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='guardian_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=guardian_report" class="padding_left_0 tab <?php echo $active_tab == 'guardian_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Guardian Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='admission_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=admission_report" class="padding_left_0 tab <?php echo $active_tab == 'admission_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Admission Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='sibling_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=sibling_report" class="padding_left_0 tab <?php echo $active_tab == 'sibling_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Sibling Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='student_failed'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=student_failed" class="padding_left_0 tab <?php echo $active_tab == 'student_failed' ? 'active' : ''; ?>">
					<?php esc_html_e('Student Failed', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='teacher_performance_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=student_information_report&tab1=teacher_performance_report" class="padding_left_0 tab <?php echo $active_tab == 'teacher_performance_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Teacher Performance', 'school-mgt'); ?></a>
				</li>
			</ul>
			<div class="clearfix panel-body">
				<?php
				if($active_tab == 'student_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/student_report.php';
				}
				if($active_tab == 'class_section_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/class_section_report.php';
				}
				if($active_tab == 'guardian_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/guardian_report.php';
				}
				if($active_tab == 'admission_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/admission_report.php';
				}
				if($active_tab == 'sibling_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/sibling_report.php';
				}
				if($active_tab == 'student_failed')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/student_failed_report.php';
				}
				if($active_tab == 'teacher_performance_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/teacher_performance_report.php';
				}

				?>
			</div>
			<?php
		}
		//--- Attendance Report - start----//
		if($active_tab == 'attendance_report')
		{
			if ($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff') {
				$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'monthly_attendance_report';
			   ?>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='monthly_attendance_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=monthly_attendance_report" class="padding_left_0 tab <?php echo $active_tab == 'monthly_attendance_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Monthly Attendance Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='daily_attendance_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=daily_attendance_report" class="padding_left_0 tab <?php echo $active_tab == 'daily_attendance_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Daily Attendance Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='attendance_report_datatable'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=attendance_report_datatable" class="padding_left_0 tab <?php echo $active_tab == 'attendance_report_datatable' ? 'active' : ''; ?>">
					<?php esc_html_e('Attendance Report In Datatable', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='attendance_report_graph'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=attendance_report_graph" class="padding_left_0 tab <?php echo $active_tab == 'attendance_report_graph' ? 'active' : ''; ?>">
					<?php esc_html_e('Attendance Report In Graph', 'school-mgt'); ?></a>
				</li>
				<?php if ($school_obj->role == 'supportstaff') {
				?>
				<li class="<?php if($active_tab=='teacher_attendance_report_datatable'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=teacher_attendance_report_datatable" class="padding_left_0 tab <?php echo $active_tab == 'teacher_attendance_report_datatable' ? 'active' : ''; ?>">
					<?php esc_html_e('Teacher Attendance Report In Datatable', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='teacher_attendance_report_graph'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=attendance_report&tab1=teacher_attendance_report_graph" class="padding_left_0 tab <?php echo $active_tab == 'teacher_attendance_report_graph' ? 'active' : ''; ?>">
					<?php esc_html_e('Teacher Attendance Report In Graph', 'school-mgt'); ?></a>
				</li>
				<?php } ?>
			</ul>
			<div class="clearfix panel-body">
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
				if($school_obj->role == 'supportstaff'){
					if($active_tab == 'teacher_attendance_report_datatable')
					{
						require_once SMS_PLUGIN_DIR.'/admin/includes/report/teacher_attendance_report_datatable.php';
					}
					if($active_tab == 'teacher_attendance_report_graph')
					{
						require_once SMS_PLUGIN_DIR.'/admin/includes/report/teacher_attendance_report_graph.php';
					}
				}
				?>
			</div>
			<div class="clearfix"> </div>
				<?php
			}
		}
		//--- Attendance Report - End----//


		//--- Attendance Report - start----//
		if($active_tab == 'hostel_report')
		{
			$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'student_hostel_report';
			   ?>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='student_hostel_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=hostel_report&tab1=student_hostel_report" class="padding_left_0 tab <?php echo $active_tab == 'student_hostel_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Student Hostel Report', 'school-mgt'); ?></a>
				</li>
			</ul>
			<div class="clearfix panel-body">
				<?php
				if($active_tab == 'student_hostel_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/student_hostel_report.php';
				}
				?>
			</div>
			<div class="clearfix"> </div>
				<?php
		}
		//--- Attendance Report - End----//


		// fianance / Payment Report
		if($active_tab == 'fianance_report')
		{
			$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'fees_payment';
			?>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='fees_payment'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=fianance_report&tab1=fees_payment" class="padding_left_0 tab <?php echo $active_tab == 'fees_payment' ? 'active' : ''; ?>">
					<?php esc_html_e('Fees Payment Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='income_payment'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=fianance_report&tab1=income_payment" class="padding_left_0 tab <?php echo $active_tab == 'income_payment' ? 'active' : ''; ?>">
					<?php esc_html_e('Income Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='expense_payment'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=fianance_report&tab1=expense_payment" class="padding_left_0 tab <?php echo $active_tab == 'expense_payment' ? 'active' : ''; ?>">
					<?php esc_html_e('Expense Report', 'school-mgt'); ?></a>
				</li>
				<li class="<?php if($active_tab=='income_expense_payment'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=fianance_report&tab1=income_expense_payment" class="padding_left_0 tab <?php echo $active_tab == 'income_expense_payment' ? 'active' : ''; ?>">
					<?php esc_html_e('Income-Expense Report', 'school-mgt'); ?></a>
				</li>
			</ul>
			<!-- tabing end  -->
			<div class="clearfix panel-body">
				<?php
				if($active_tab == 'fees_payment')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/fees_payment.php';
				}
				if($active_tab == 'income_payment')
				{
					?>
					<script>
						jQuery(document).ready(function($){
						"use strict";
							$('#student_income_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						});
					</script>
					<?php
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/income_payment.php';
				}
				if($active_tab == 'expense_payment')
				{
					?>
					<script>
						jQuery(document).ready(function($){
						"use strict";
							$('#student_expence_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						});
					</script>
					<?php
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/expense_payment.php';
				}
				if($active_tab == 'income_expense_payment')
				{
					?>
					<script>
						jQuery(document).ready(function($){
						"use strict";
							$('#student_income_expence_payment').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
						});
					</script>
					<?php
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/income_expense.php';
				}

				?>
			</div>
			<div id="chart_div" class="chart_div">
			<?php
		}
		// Fees Payment Report

		// Examinations Report
		if($active_tab == 'examinations_report')
		{
			$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'exam_result_report';
			?>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='exam_result_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab=examinations_report&tab1=exam_result_report" class="padding_left_0 tab <?php echo $active_tab == 'exam_result_report' ? 'active' : ''; ?>">
					<?php esc_html_e('Result', 'school-mgt'); ?></a>
				</li>

			</ul>
			<!-- tabing end  -->
			<div class="clearfix panel-body">
				<?php
				if($active_tab == 'exam_result_report')
				{
					require_once SMS_PLUGIN_DIR.'/admin/includes/report/exam_result_report.php';
				}
				?>
			</div>
			<div id="chart_div" class="chart_div">
			<?php

		}

		if($active_tab == 'audit_log_report')
		{
			?>
			<div class="clearfix panel-body">
				<?php
				require_once SMS_PLUGIN_DIR.'/admin/includes/report/audit_log.php';
				?>
			</div>
			<?php
		}
		if($active_tab == 'user_log_report')
		{
			?>
			<div class="clearfix panel-body">
				<?php
				require_once SMS_PLUGIN_DIR.'/admin/includes/report/user_log.php';
				?>
			</div>
			<?php
		}


		if($active_tab == 'report1')
		{
			$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'student_book_issue_report';
			?>
			<!-- tabing start  -->
			<ul class="nav nav-tabs panel_tabs margin_left_1per" role="tablist">
				<li class="<?php if($active_tab=='student_book_issue_report'){?>active<?php }?>">
					<a href="?dashboard=user&page=report&tab1=student_book_issue_report" class="padding_left_0 tab <?php echo $active_tab == 'student_book_issue_report' ? 'active' : ''; ?>">
					<?php esc_html_e('About Issue Book', 'school-mgt'); ?></a>
				</li>
			</ul>
			<!-- penal body div  -->
			<div class="panel-body margin_top_20px padding_top_15px_res">
				<form method="post" id="failed_report">
					<!-- penal body div  -->
					<div class="panel-body margin_top_20px padding_top_15px_res">
						<form method="post" id="failed_report">
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
										<?php
										$class_id="";
										if(isset($_REQUEST['class_id']))
										{
											$class_id=$_REQUEST['class_id'];
										}
										?>
										<select name="class_id"  id="class_list" class="line_height_30px form-control validate[required] class_id_exam">
											<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
											<?php
											foreach(mj_smgt_get_allclass() as $classdata)
											{
											?>
												<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
											<?php
											}
											?>
										</select>
									</div>
									<div class="col-md-6 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Section','school-mgt');?></label>
										<?php
										$class_section="";
										if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
										<select name="class_section" class="line_height_30px form-control" id="class_section">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
											<?php if(isset($_REQUEST['class_section']))
											{
												echo $class_section=$_REQUEST['class_section'];
												foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
												{  ?>
													<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
												<?php
												}
											}
											?>
										</select>
									</div>
									<div class="col-md-6 mb-3 input">
										<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date Type','school-mgt');?><span class="require-field">*</span></label>
										<select class="form-control line_height_30px date_type validate[required]" name="date_type" autocomplete="off">
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
									<div class="col-md-6 mb-2">
										<input type="submit" name="library_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
									</div>

								</div>
							</div>
						</form>
					</div>
					<!-- penal body div -->
				</form>
			</div>
			<!-- penal body div -->
			<?php
			$class_id = "";
			$class_section = "";
			$date_type= "";
			if(isset($_REQUEST['library_report']))
			{
				$date_type = $_POST['date_type'];
				$class_id = $_POST['class_id'];
				$class_section = $_POST['class_section'];

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
				if(empty($class_section))
				{
					$book_issue_data=mj_smgt_check_book_issued_by_class_id_and_date($class_id,$start_date,$end_date);
				}
				else
				{
					$book_issue_data=mj_smgt_check_book_issued_by_class_id_and_class_section_and_date($class_id, $class_section,$start_date,$end_date);
				}
			}
			else
			{
				$start_date = date('Y-m-d');
				$end_date= date('Y-m-d');
				$book_issue_data=mj_smgt_check_book_issued_by_startdate_and_enddate($start_date,$end_date);
			}
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					"use strict";
					var table = jQuery('#book_issue_list_report').DataTable({
						"responsive": true,
						"order": [[ 2, "Desc" ]],
						"dom": 'lifrtp',
						"buttons": [
							{
								extend: 'csv',
								text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
								title: '<?php echo esc_attr_e("Book Issue Report","school-mgt"); ?>t',
							},
							{
								extend: 'print',
								text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
								title: '<?php echo esc_attr_e("Book Issue Report","school-mgt"); ?>',
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
			<div class="panel-body margin_top_20px padding_top_15px_res">
				<?php
				if(!empty($book_issue_data))
				{
					?>
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
							<h4 class="report_heder"><?php esc_html_e('Book Issue Report','school-mgt');?></h4>
						</div>
					</div>
					<div class="table-responsive">
						<div class="btn-place"></div>
						<form id="frm-admisssion" name="frm-admisssion" method="post">
							<table id="book_issue_list_report" class="display admission_report_tbl" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
									<tr>
										<th><?php esc_attr_e('Book Title','school-mgt');?></th>
										<th><?php esc_attr_e('Book Number','school-mgt');?></th>
										<th><?php esc_attr_e('ISBN','school-mgt');?></th>
										<th><?php esc_attr_e('Student Name','school-mgt');?></th>
										<th><?php esc_attr_e('Admission No','school-mgt');?></th>
										<th><?php esc_attr_e('Issue Date','school-mgt');?></th>
										<th><?php esc_attr_e('Return Date','school-mgt');?></th>

									</tr>
								</thead>
								<tbody>
									<?php

									foreach ($book_issue_data as $retrieved_data)
									{
										?>
										<tr>
											<td><?php echo mj_smgt_get_bookname($retrieved_data->book_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Book Title','school-mgt');?>"></i></td>
											<td><?php echo mj_smgt_get_book_number($retrieved_data->book_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Book Number','school-mgt');?>"></i></td>
											<td><?php echo mj_smgt_get_ISBN($retrieved_data->book_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('ISBN','school-mgt');?>"></i></td>
											<td>
												<?php echo mj_smgt_get_display_name($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
											</td>
											<td>
												<?php
												$admission_no = get_user_meta($retrieved_data->student_id, 'admission_no',true);
												if(!empty($admission_no))
												{
													echo get_user_meta($retrieved_data->student_id, 'admission_no',true);
												}
												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission No','school-mgt');?>"></i>
											</td>
											<td>
												<?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Issue Date','school-mgt');?>"></i>
											</td>
											<td>
												<?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Return Date','school-mgt');?>"></i>
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
			<?php
		}
		if($active_tab == 'report2')
		{
			$active_tab_1 = isset($_GET['tab1'])?$_GET['tab1']:'report2_graph';
			?>
			<div class="panel-body"><!-------------- PENAL BODY ------------------>
				<!--------------- INCOME TABING --------------->
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab_1=='report2_graph'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report2&tab1=report2_graph" class="padding_left_0 tab <?php echo $active_tab_1 == 'report2_graph' ? 'active' : ''; ?>">
						<?php esc_html_e('Attendance Report Graph', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab_1=='report2_attendance_report'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report2&tab1=report2_attendance_report" class="padding_left_0 tab <?php echo $active_tab_1 == 'report2_attendance_report' ? 'active' : ''; ?>">
						<?php esc_html_e('Attendance Report', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab_1=='report2_daily_attendance_report'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report2&tab1=report2_daily_attendance_report" class="padding_left_0 tab <?php echo $active_tab_1 == 'report2_daily_attendance_report' ? 'active' : ''; ?>">
						<?php esc_html_e('Daily Attendance Report', 'school-mgt'); ?></a>
					</li>
				</ul><!--------------- INCOME TABING --------------->
			</div><!-------------- PENAL BODY ------------------>
			<?php
			//Satrt Income Datatbale Report Tab //
			if($active_tab_1 == 'report2_graph')
			{
				?>
				<div class="clearfix"> </div>
				<div class="panel-body margin-top-10px" id="attendance_report"><!----------- PENAL BODY --------------->
					<form method="post">
						<div class="form-body user_form">
							<div class="row">
								<div class="col-md-5">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d',strtotime('first day of this month'));?>" readonly>
											<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
											<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input type="submit" name="report_2" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
								</div>
							</div>
						</div>
					</form>
				</div><!----------- PENAL BODY --------------->
				<div class="clearfix"> </div>
				<div class="clearfix"> </div>
				<?php

				if(!empty($report_2))
				{
					$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
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
				<div id="chart_div" class="w-100 h-500-px"></div>
				<!-- Javascript -->
				<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				<script type="text/javascript">
					<?php echo $chart;?>
				</script>
				<?php
			}

			if($active_tab_1 == 'report2_attendance_report')
			{
				?>
				<div class="panel-body clearfix margin_top_20px padding_top_15px_res">
					<div class="panel-body clearfix">
						<form method="post" id="student_attendance">
							<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-3 mb-3 input">
										<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
										<select name="class_id"  id="class_list" class="line_height_30px form-control validate[required]">
											<?php
											$class_id="";
											if(isset($_REQUEST['class_id']))
											{
												$class_id=$_REQUEST['class_id'];
											}?>
											<option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option>
											<?php
											foreach(mj_smgt_get_allclass() as $classdata)
											{
												?>
												<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
												<?php
											}?>
										</select>
									</div>
									<div class="col-md-3 mb-3 input">
										<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>
										<?php
										$class_section="";
										if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
										<select name="class_section" class="line_height_30px form-control" id="class_section">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
											<?php if(isset($_REQUEST['class_section']))
											{
												$class_section=$_REQUEST['class_section'];
												foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
												{  ?>
													<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
													<?php
												}
											} ?>
										</select>
									</div>
									<div class="col-md-2 mb-3 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Year','school-mgt');?><span class="require-field">*</span></label>
										<select name="year" class="line_height_30px form-control validate[required]">
											<option ><?php esc_attr_e('Selecte year','school-mgt');?></option>
												<?php
											$current_year = date('Y');
											$min_year = $current_year - 10;

											for($i = $min_year; $i <= $current_year; $i++){
												$year_array[$i] = $i;
													$selected = ($current_year == $i ? ' selected' : '');
													echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
												}
											?>
										</select>
									</div>
									<div class="col-md-2 mb-3 input">
										<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Months','school-mgt');?><span class="require-field">*</span></label>
										<select id="month" name="month" class="line_height_30px form-control class_id_exam validate[required]">
											<option ><?php esc_attr_e('Selecte Month','school-mgt');?></option>
											<?php
											$selected_month = date('m'); //current month
											for ($i_month = 1; $i_month <= 12; $i_month++) {
												$selected = ($selected_month == $i_month ? ' selected' : '');
												echo '<option value="'.$i_month.'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
											}
												?>
										</select>
									</div>
									<div class="col-md-2 mb-2">
										<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
									</div>
								</div>
							</div>
						</form>
					</div>
					<?php
					if(isset($_REQUEST['view_attendance']))
					{
						$class_id = $_POST['class_id'];
						$class_section = $_POST['class_section'];
						$year = $_POST['year'];
						$month = $_POST['month'];

						// fetch day and date by year,Month
						$list=array();
						$month = $month;
						$year = $year;
						if($month=="2")
						{
							$max_d="28";
						}
						elseif($month=="4" || $month=="6" || $month=="9" || $month=="11")
						{
							$max_d="30";
						}
						else
						{
							$max_d="31";
						}
						for($d=1; $d<= $max_d; $d++)
						{
							$time=mktime(12, 0, 0, $month, $d, $year);

							if (date('m', $time)==$month)

								$date_list[]=date('Y-m-d', $time);
								$day_date[]=date('d D', $time);

								$month_first_date = min($date_list);
								$month_last_date =   max($date_list);
						}
						if($class_section == "")
						{
							$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
							sort($student);
						}
						else
						{
							$student = 	get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id)),'role'=>'student'));
							sort($student);
						}

						?>
						<script type="text/javascript">
							jQuery(document).ready(function($){
								"use strict";
								var table = jQuery('#class_attendance_list_report').DataTable({
									"initComplete": function(settings, json) {
											$(".print-button").css({"margin-top": "-5%"});
										},
									"order": [[ 2, "Desc" ]],
									"dom": 'lifrtp',
									"aoColumns":[
										{"bSortable": true},
										{"bSortable": false},
										{"bSortable": false},
										{"bSortable": false},
										{"bSortable": false},
										// {"bSortable": true},
										<?php
										foreach($day_date as $data)
										{
											?>
											{"bSortable": false},
											<?php
										}
										?>
										{"bSortable": false}],
									language:<?php echo mj_smgt_datatable_multi_language();?>
								});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
							});
						</script>
						<div class="panel-body margin_top_20px padding_top_15px_res">
							<div class="row">
								<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12">
									<h4 class="report_heder"><?php esc_html_e('Student Attendance Report','school-mgt');?></h4>
								</div>
								<div class="col-sm-12 col-md-8 col-lg-8 col-xs-12">
									<div class="smgt-card-head">
										<ul class="smgt_att_repot_list smgt-right att_status_color">
											<!--set attnce-status header Start -->
											<li> <?php esc_html_e( 'Present', 'school-mgt' ); ?>: <span class="P"><?php esc_html_e( 'P', 'school-mgt' ); ?></span></li>
											<li> <?php esc_html_e( 'Late', 'school-mgt' );?>: <span class="L"><?php esc_html_e( 'L', 'school-mgt' ); ?></span></li>
											<li> <?php esc_html_e( 'Absent', 'school-mgt' );?>: <span class="A"><?php esc_html_e( 'A', 'school-mgt' ); ?></span></li>
											<li> <?php esc_html_e( 'Holiday', 'school-mgt' );?>: <span class="H"><?php esc_html_e( 'H', 'school-mgt' ); ?></span></li>
											<li> <?php esc_html_e( 'Half Day', 'school-mgt' );?>: <span class="F"><?php esc_html_e( 'F', 'school-mgt' ); ?></span></li>
										</ul>
									</div>
								</div>
							</div>
							<div id="smgt_overflow" class="table-responsive">
								<form id="frm-example" name="frm-example" method="post">
									<table id="class_attendance_list_report" class="display class_att_repost_tbl" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php esc_attr_e('Student','school-mgt');?></th>
												<!-- <th><?php esc_attr_e('%','school-mgt');?></th> -->
												<th><?php esc_attr_e('P','school-mgt');?></th>
												<th><?php esc_attr_e('L','school-mgt');?></th>
												<th><?php esc_attr_e('A','school-mgt');?></th>
												<th><?php esc_attr_e('F','school-mgt');?></th>
												<th><?php esc_attr_e('H','school-mgt');?></th>
												<?php
												foreach($day_date as $data)
												{

													?>
													<th class="<?php echo $data;?>"><?php  echo $data;?></th>
													<?php
												}
												?>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($student as $user)
											{
											?>
												<tr>
													<td>
														<?php echo mj_smgt_get_display_name($user->ID);?>
													</td>
													<td>
														<?php
														$Present='Present';
														$total_present=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Present);
														echo  count($total_present);
														?>
													</td>
													<td>
														<?php
															$Late='Late';
															$total_late=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Late);
															echo  count($total_late);
														?>
													</td>
													<td>
														<?php
														$Absent='Absent';
														$total_absent=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Absent);
														echo  count($total_absent);
														?>
													</td>
													<td>
														<?php
															$Half_Day='Half Day';
															$total_Half_day=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Half_Day);
															echo  count($total_Half_day);
														?>
													</td>
													<td>
													<?php
														$total_Holiday_day=mj_smgt_get_all_holiday_by_month_year($month,$year);
														echo count($total_Holiday_day);
														?>
													</td>
													<?php
													foreach($date_list as $date)
													{
														?>
														<td class="att_status_color">
															<?php
															echo mj_smgt_attendance_report_all_staus_value($date,$class_id,$user->ID)
															?>
														</td>
														<?php
													}
													?>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</form>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			if($active_tab_1 == 'report2_daily_attendance_report')
			{
				?>
				<div class="panel-body clearfix margin_top_20px padding_top_15px_res">
					<div class="panel-body clearfix">
						<form method="post">
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-8">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input type="text"  id="sdate" class="form-control" name="date" value="<?php if(isset($_REQUEST['date'])) echo $_REQUEST['date'];else echo date('Y-m-d');?>" readonly>
												<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<input type="submit" name="daily_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
									</div>
								</div>
							</div>
						</form>
					</div>
					<?php
					if(isset($_REQUEST['daily_attendance']))
					{
						$daily_date = $_POST['date'];
						//var_dump($daily_date );
						?>
						<script type="text/javascript">
							jQuery(document).ready(function($){
								"use strict";
								var table = jQuery('#daily_attendance_list_report').DataTable({
									//stateSave: true,
									"order": [[ 2, "Desc" ]],
									"dom": 'lifrtp',
									"aoColumns":[
										{"bSortable": true},
										{"bSortable": true},
										{"bSortable": true},
										{"bSortable": true},
										{"bSortable": true}],
									language:<?php echo mj_smgt_datatable_multi_language();?>
									});
								$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
							});
						</script>
						<div class="panel-body margin_top_20px padding_top_15px_res">
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
									<h4 class="report_heder"><?php esc_html_e('Daily Attendance Report','school-mgt');?></h4>
								</div>
							</div>
							<div class="table-responsive">
								<form id="frm-daily-attendance" name="frm-daily-attendance" method="post">
									<table id="daily_attendance_list_report" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php esc_attr_e('Class','school-mgt');?></th>
												<th><?php esc_attr_e('Total Present','school-mgt');?></th>
												<th><?php esc_attr_e('Total Absent','school-mgt');?></th>
												<th><?php esc_attr_e('Present','school-mgt');?><?php esc_attr_e(' %','school-mgt');?></th>
												<th><?php esc_attr_e('Absent','school-mgt');?><?php esc_attr_e(' %','school-mgt');?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach(mj_smgt_get_allclass() as $classdata)
											{
												$class_id=$classdata['class_id'];
												$total_present=mj_smgt_daily_attendance_report_for_date_total_present($daily_date,$class_id);
												$total_absent=mj_smgt_daily_attendance_report_for_date_total_absent($daily_date,$class_id);
												$total_pre_abs=$total_present + $total_absent;
												if($total_present=="0" && $total_absent=="0")
												{
													$present_per = 0;
													$absent_per = 0;
												}
												else
												{
													$present_per = ($total_present * 100)/$total_pre_abs;
													$absent_per = ($total_absent * 100)/$total_pre_abs;
												}
												?>
												<tr>
													<td><?php echo mj_smgt_get_class_name($class_id);?> </td>
													<td><?php echo round($total_present);?></td>
													<td><?php echo round($total_absent);?></td>
													<td><?php echo round($present_per);?>%</td>
													<td><?php echo round($absent_per);?>%</td>
												</tr>
												<?php
											}
											?>
										</tbody>
										<tbody>
											<?php
											$total_class_present=mj_smgt_daily_attendance_report_for_all_class_total_present($daily_date);
											$total_class_absent=mj_smgt_daily_attendance_report_for_all_class_total_absent($daily_date);

											$total_class_pre_abs=$total_class_present + $total_class_absent;
											if($total_class_present=="0" && $total_class_absent=="0")
											{
												$present_class_per = 0;
												$absent_class_per = 0;
											}
											else
											{
												$present_class_per = ($total_class_present * 100)/$total_class_pre_abs;
												$absent_class_per = ($total_class_absent * 100)/$total_class_pre_abs;
											}
											?>
											<tr id="daily_att_total">
												<td></td>
												<td ><?php echo round($total_class_present); ?></td>
												<td ><?php echo round($total_class_absent); ?></td>
												<td ><?php echo round($present_class_per);?>%</td>
												<td ><?php echo round($absent_class_per);?>%</td>
											</tr>
										</tbody>
									</table>
								</form>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
		}
		if($active_tab == 'report3')
		{
			?>
			<div class="clearfix"> </div>
			<?php
			if(!empty($report_3))
			{
				$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
			}
			else
			{
				?>
				<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
					</button>
					<?php echo esc_html__("Result Not Found","school-mgt"); ?>
				</div>
				<?php
			}
			?>
			<div id="chart_div" class="w-100 h-500-px"></div>
			<!-- Javascript -->
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
				<?php echo $chart;?>
			</script>
			<?php
		}
		//Satrt Expense Report Tab //
		if($active_tab == 'report7')
		{
			$active_tab_1 = isset($_GET['tab1'])?$_GET['tab1']:'report7_datatable';
			?>
			<div class="panel-body">
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab_1=='report7_datatable'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report7&tab1=report7_datatable" class="padding_left_0 tab <?php echo $active_tab_1 == 'report7_datatable' ? 'active' : ''; ?>">
						<?php esc_html_e('Expense Report Datatable', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab_1=='report7_graph'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report7&tab1=report7_graph" class="padding_left_0 tab <?php echo $active_tab_1 == 'report7_graph' ? 'active' : ''; ?>">
						<?php esc_html_e('Expense Report Graph', 'school-mgt'); ?></a>
					</li>
				</ul>
			</div>
			<?php
			//Satrt Expense Datatbale Report Tab //
			if($active_tab_1 == 'report7_datatable')
			{
				?>
				<div class="panel-body clearfix margin_top_20px padding_top_25px_res">
					<div class="panel-body clearfix">
						<form method="post">
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-5">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d',strtotime('first day of this month'));?>" readonly>
												<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
												<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<input type="submit" name="report_6" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
									</div>
								</div>
							</div>
						</form>
					</div>
					<?php
					//-------- IF SREACH DATE ---------//
					if(isset($_REQUEST['report_6']))
					{
						$start_date = $_POST['sdate'];
						$end_date = $_POST['edate'];
					}
					else
					{
						$start_date = date('Y-m-d',strtotime('first day of this month'));
						$end_date = date('Y-m-d',strtotime('last day of this month'));
					}
					global $wpdb;
					$table_income=$wpdb->prefix.'smgt_income_expense';
					$report_6 = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense' AND income_create_date BETWEEN '$start_date' AND '$end_date'");
					if(!empty($report_6))
					{
						?>
						<div class="panel-body"><!--------------- PENAL BODY --------------->
							<div class="table-responsive"><!--------------- TABLE RESPONSIVE --------------->
								<!--------------- EXPENSE LIST FORM --------------->
								<form id="frm-example" name="frm-example" method="post">
									<table id="tblexpence" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Supplier Name', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Amount', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Create Date', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php

											if(!empty($report_6))
											{
												$i=0;
												foreach($report_6 as $result)
												{
													$all_entry=json_decode($result->entry);
													$total_amount=0;
													foreach($all_entry as $entry)
													{
														$total_amount += $entry->amount;
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
															<td class="user_image width_50px profile_image_prescription padding_left_0">
																<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
																</p>
															</td>
															<td class="patient_name"><?php echo $result->supplier_name;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Supplier Name','school-mgt');?>"></i></td>
															<td class="income_amount"><?php echo MJ_smgt_currency_symbol_position_language_wise($total_amount);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
															<td class="status"><?php echo mj_smgt_getdate_in_input_box($result->income_create_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
														</tr>
														<?php
														$i++;
													}
												}
											} ?>
										</tbody>
									</table>
								</form><!--------------- EXPENSE LIST FORM --------------->
							</div><!--------------- TABLE RESPONSIVE --------------->
						</div><!--------------- PENAL BODY --------------->
						<?php
					}
					else
					{
						$page = 'payment';
						$payment=mj_smgt_get_userrole_wise_filter_access_right_array($page);
						if($payment['add']=='1')
						{
							?>
							<div class="no_data_list_div no_data_img_mt_30px">
								<a href="<?php echo home_url().'?dashboard=user&page=payment&tab=addexpense';?>">
									<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
								</a>
								<div class="col-md-12 dashboard_btn margin_top_20px">
									<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
								</div>
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
						}
					}
					?>
				</div>
				<?php
			}
			//End  Expense Datatbale Report Tab //
			if($active_tab_1 == 'report7_graph')
			{
				?>
				<div class="panel-body clearfix margin_top_30px padding_top_15px_res">
					<?php
					$month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),

					'5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),

					'9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('Octomber','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);

					$year =isset($_POST['year'])?$_POST['year']:date('Y');
					global $wpdb;
					$table_name = $wpdb->prefix."smgt_income_expense";
					$report_6 = $wpdb->get_results("SELECT * FROM $table_name where invoice_type='expense'");
					foreach($report_6 as $result)
					{
						$all_entry=json_decode($result->entry);
						$total_amount=0;
						foreach($all_entry as $entry)
						{
							$total_amount += $entry->amount;
							$q="SELECT EXTRACT(MONTH FROM income_create_date) as date, sum($total_amount) as count FROM ".$table_name." WHERE invoice_type='expense' AND YEAR(income_create_date) =".$year." group by month(income_create_date) ORDER BY income_create_date ASC";
							$result=$wpdb->get_results($q);
						}
					}
					$sumArray = array();
					foreach ($result as $value)
					{
						if(isset($sumArray[$value->date]))
						{
							$sumArray[$value->date] = $sumArray[$value->date] + (int)$value->count;
						}
						else
						{
							$sumArray[$value->date] = (int)$value->count;
						}
					}
					$chart_array = array();
					$chart_array[] = array(esc_html__('Month','school-mgt'),esc_html__('Expenses','school-mgt'));
					$i=1;
					foreach($sumArray as $month_value=>$count)
					{
						$chart_array[]=array( $month[$month_value],(int)$count);
					}
					$options = Array(
								'title' => esc_html__('Expenses Payment Report By Month','school-mgt'),
								'titleTextStyle' => Array('color' => '#66707e'),
								'legend' =>Array('position' => 'right',
								'textStyle'=> Array('color' => '#66707e')),
								'hAxis' => Array(
									'title' => esc_html__('Month','school-mgt'),
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'maxAlternation' => 2
									),
								'vAxis' => Array(
									'title' => esc_html__('Expenses Payment','school-mgt'),
									'minValue' => 0,
									'maxValue' => 6,
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins')
									),
							'colors' => array('#22BAA0')
								);
					require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
					$GoogleCharts = new GoogleCharts;
					$chart = $GoogleCharts->load('column','chart_div')->get( $chart_array , $options );
					?>
					<div id="chart_div" class="chart_div">
					<?php
					if(empty($result))
					{
						?>
						<div class="clear col-md-12"><h3><?php esc_html_e("There is not enough data to generate report.",'school-mgt');?> </h3></div>
						<?php
					}
					?>
				</div>
				<!-- Javascript -->
				<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				<script type="text/javascript">
					<?php
					if(!empty($result))
					{
						echo $chart;
					}
					?>
				</script>
				<?php
			}
		}
		//End Expense Report Tab //
		//Income Payment Report Tab //
		if($active_tab == 'report6')
		{
			$active_tab_1 = isset($_GET['tab1'])?$_GET['tab1']:'report6_datatable';
			?>
			<div class="panel-body"><!-------------- PENAL BODY ------------------>
				<!--------------- INCOME TABING --------------->
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab_1=='report6_datatable'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report6&tab1=report6_datatable" class="padding_left_0 tab <?php echo $active_tab_1 == 'report6_datatable' ? 'active' : ''; ?>">
						<?php esc_html_e('Income Report Datatable', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab_1=='report6_graph'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report6&tab1=report6_graph" class="padding_left_0 tab <?php echo $active_tab_1 == 'report6_graph' ? 'active' : ''; ?>">
						<?php esc_html_e('Income Report Graph', 'school-mgt'); ?></a>
					</li>
				</ul><!--------------- INCOME TABING --------------->
			</div><!-------------- PENAL BODY ------------------>
			<?php
			//Satrt Income Datatbale Report Tab //
			if($active_tab_1 == 'report6_datatable')
			{
				?>
				<div class="panel-body clearfix margin_top_20px padding_top_25px_res">
					<div class="panel-body clearfix"><!--------- PENAL BODY ---------------->
						<form method="post"><!--------- INCOME FORM ---------------->
							<div class="form-body user_form">
								<div class="row">
									<div class="col-md-5">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d',strtotime('first day of this month'));?>" readonly>
												<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group input">
											<div class="col-md-12 form-control">
												<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
												<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<input type="submit" name="report_6" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
									</div>
								</div>
							</div>
						</form><!--------- INCOME FORM ---------------->
					</div><!--------- PENAL BODY ---------------->
					<?php
					if(isset($_REQUEST['report_6']))
					{
						$start_date = $_POST['sdate'];
						$end_date = $_POST['edate'];
					}
					else
					{
						$start_date = date('Y-m-d',strtotime('first day of this month'));
						$end_date = date('Y-m-d',strtotime('last day of this month'));
					}
					global $wpdb;
					$table_income=$wpdb->prefix.'smgt_income_expense';
					$report_6 = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='income' AND income_create_date BETWEEN '$start_date' AND '$end_date'");
					if(!empty($report_6))
					{
						?>
						<div class="panel-body"><!------------------ PENAL BODY --------------->
							<div class="table-responsive"><!------------------ TABLE RESPONSIVE --------------->
								<!-------------- INCOME LIST FORM ------------------>
								<form id="frm-example" name="frm-example" method="post">
									<table id="tblincome" class="display" cellspacing="0" width="100%">
										<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
											<tr>
												<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Student Name & Roll No.', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Total Amount', 'school-mgt' ) ;?></th>
												<th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(!empty($report_6))
											{
												$i=0;
												foreach($report_6 as $result)
												{
													$all_entry=json_decode($result->entry);
													$total_amount=0;
													foreach($all_entry as $entry)
													{
														$total_amount += $entry->amount;
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
														<td class="user_image width_50px profile_image_prescription padding_left_0">
															<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">
																<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
															</p>
														</td>
														<td class="patient_name"><?php echo mj_smgt_get_user_name_byid($result->supplier_name);?>-<?php echo get_user_meta($result->supplier_name, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Roll No.','school-mgt');?>"></i></td>
														<td class="income_amount"><?php echo MJ_smgt_currency_symbol_position_language_wise($total_amount);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
														<td class="status"><?php echo mj_smgt_getdate_in_input_box($result->income_create_date);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date','school-mgt');?>"></i></td>
													</tr>
													<?php
													$i++;
												}
											} ?>
										</tbody>
									</table>
								</form><!-------------- INCOME LIST FORM ------------------>
							</div><!------------------ TABLE RESPONSIVE --------------->
						</div><!------------------ PENAL BODY --------------->
						<?php
					}
					else
					{
						$page = 'payment';
						$payment=mj_smgt_get_userrole_wise_filter_access_right_array($page);
						if($payment['add']=='1')
						{
							?>
							<div class="no_data_list_div no_data_img_mt_30px">
								<a href="<?php echo home_url().'?dashboard=user&page=payment&tab=addincome';?>">
									<img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >
								</a>
								<div class="col-md-12 dashboard_btn margin_top_20px">
									<label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>
								</div>
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
						}
					}
					?>
				</div>
				<?php
			}
			//End Income Datatbale Report Tab //

			//Start Income Graph Report Tab //
			if($active_tab_1 == 'report6_graph')
			{
				?>
				<div class="panel-body clearfix margin_top_30px padding_top_15px_res">
					<?php
					$month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),
					'5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),
					'9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('Octomber','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);
					$year =isset($_POST['year'])?$_POST['year']:date('Y');

					global $wpdb;
					$table_name = $wpdb->prefix."smgt_income_expense";
					$report_6 = $wpdb->get_results("SELECT * FROM $table_name where invoice_type='income'");
					foreach($report_6 as $result)
					{
						$all_entry=json_decode($result->entry);
						$total_amount=0;
						foreach($all_entry as $entry)
						{
							$total_amount += $entry->amount;
							$q="SELECT EXTRACT(MONTH FROM income_create_date) as date, sum($total_amount) as count FROM ".$table_name." WHERE invoice_type='income' AND YEAR(income_create_date) =".$year." group by month(income_create_date) ORDER BY income_create_date ASC";
							$result=$wpdb->get_results($q);
						}
					}
					$sumArray = array();
					foreach ($result as $value)
					{
						if(isset($sumArray[$value->date]))
						{
							$sumArray[$value->date] = $sumArray[$value->date] + (int)$value->count;
						}
						else
						{
							$sumArray[$value->date] = (int)$value->count;
						}
					}

					$chart_array = array();
					$chart_array[] = array(esc_html__('Month','school-mgt'),esc_html__('Income','school-mgt'));
					$i=1;
					foreach($sumArray as $month_value=>$count)
					{
						$chart_array[]=array( $month[$month_value],(int)$count);
					}

					$options = Array(
								'title' => esc_html__('Income Payment Report By Month','school-mgt'),
								'titleTextStyle' => Array('color' => '#66707e'),
								'legend' =>Array('position' => 'right',
								'textStyle'=> Array('color' => '#66707e')),
								'hAxis' => Array(
									'title' => esc_html__('Month','school-mgt'),
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'maxAlternation' => 2
									),
								'vAxis' => Array(
									'title' => esc_html__('Income Payment','school-mgt'),
									'minValue' => 0,
									'maxValue' => 6,
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins')
									),
							'colors' => array('#22BAA0')
								);
					require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
					$GoogleCharts = new GoogleCharts;
					$chart = $GoogleCharts->load('column','chart_div')->get( $chart_array , $options );
					?>
					<div id="chart_div" class="chart_div">
						<?php
						if(empty($result))
						{
							?>
							<div class="clear col-md-12"><h3><?php esc_html_e("There is not enough data to generate report.",'school-mgt');?> </h3></div>
							<?php
						}
						?>
					</div>
					<!-- Javascript -->
					<script type="text/javascript" src="https://www.google.com/jsapi"></script>
					<script type="text/javascript">
						<?php if(!empty($result))
						{
							echo $chart;
						}
						?>
					</script>
				</div>
				<?php
			} //END Start Income Graph Report Tab //
		} //End Income Payment Report Tab //
		//Start Fees Payment Report Tab //
		if($active_tab == 'report4')
		{
			$active_tab_1 = isset($_GET['tab1'])?$_GET['tab1']:'report4_datatable';
			?>
			<div class="panel-body"><!-------------- PENAL BODY ------------------>
				<!--------------- INCOME TABING --------------->
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per" role="tablist">
					<li class="<?php if($active_tab_1=='report4_datatable'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report4&tab1=report4_datatable" class="padding_left_0 tab <?php echo $active_tab_1 == 'report4_datatable' ? 'active' : ''; ?>">
						<?php esc_html_e('Fees Payment Datatable', 'school-mgt'); ?></a>
					</li>
					<li class="<?php if($active_tab_1=='report4_graph'){?>active<?php }?>">
						<a href="?dashboard=user&page=report&tab=report4&tab1=report4_graph" class="padding_left_0 tab <?php echo $active_tab_1 == 'report4_graph' ? 'active' : ''; ?>">
						<?php esc_html_e('Fees Payment Graph', 'school-mgt'); ?></a>
					</li>
				</ul><!--------------- INCOME TABING --------------->
			</div><!-------------- PENAL BODY ------------------>
			<?php
			//Fees Payment Datatbale Report Tab //
			if($active_tab_1 == 'report4_datatable')
			{
				?>
				<div class="panel-body margin_top_20px padding_top_25px_res"><!-------------- PENAL BODY ------------------>
					<!--------------- FEES PAYMENT FORM -------------------->
					<form method="post" id="fee_payment_report">
						<div class="form-body user_form"><!-------------- FORM BODY ------------------>
							<div class="row">
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
									<select name="class_id"  id="class_list" class="line_height_30px form-control load_fee_type_single validate[required]">
										<?php
											$select_class = isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
										?>
										<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
										<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{
											?>
											<option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($select_class,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
											<?php
										}?>
									</select>
								</div>
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
									<?php
									$class_section="";
									if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
									<select name="class_section" class="line_height_30px form-control" id="class_section">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
										<?php if(isset($_REQUEST['class_section'])){
												echo $class_section=$_REQUEST['class_section'];
												foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
												{  ?>
												<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
											<?php }
											}?>
									</select>
								</div>
								<div class="col-md-6 input">
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('FeesType','school-mgt');?><span class="require-field">*</span></label>
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
									<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Payment Status','school-mgt');?><span class="require-field">*</span></label>
									<select id="fee_status" class="line_height_30px form-control validate[required]" name="fee_status">
										<?php
										$select_payment = isset($_REQUEST['fee_status'])?$_REQUEST['fee_status']:'';?>
										<option value=" "><?php esc_attr_e('Select Payment Status','school-mgt');?></option>
										<option value="0" <?php echo selected($select_payment,0);?>><?php esc_attr_e('Not Paid','school-mgt');?></option>
										<option value="1" <?php echo selected($select_payment,1);?>><?php esc_attr_e('Partially Paid','school-mgt');?></option>
										<option value="2" <?php echo selected($select_payment,2);?>><?php esc_attr_e('Fully paid','school-mgt');?></option>
									</select>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d',strtotime('first day of this month'));?>" readonly>
											<label for="userinput1" class=""><?php esc_html_e('Start Date','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group input">
										<div class="col-md-12 form-control">
											<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
											<label for="userinput1" class=""><?php esc_html_e('End Date','school-mgt');?></label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<input type="submit" name="report_4" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
								</div>
							</div>
						</div><!-------------- FORM BODY ------------------>
					</form><!--------------- FEES PAYMENT FORM -------------------->
				</div><!-------------- PENAL BODY ------------------>
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
						<div class="table-responsive"><!-------------- TABLE RESPONSIVE ---------------->
							<table id="payment_report" class="display" cellspacing="0" width="100%">
								<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
									<tr>
										<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Fees Term', 'school-mgt' ) ;?></th>
										<th> <?php esc_attr_e( 'Student Name & Roll No.', 'school-mgt' ) ;?></th>
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
														<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
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
												<td><?php echo implode(" , " ,$fees_type); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Term','school-mgt');?>"></i></td>
												<td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?>-<?php echo get_user_meta($retrieved_data->student_id, 'roll_id',true);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Roll No.','school-mgt');?>"></i></td>
												<td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
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
													?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Payment Status','school-mgt');?>"></i>
												</td>
												<td><?php echo MJ_smgt_currency_symbol_position_language_wise($retrieved_data->total_amount);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>
												<?php
												$Due_amt = $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;
												?>
												<td><?php echo MJ_smgt_currency_symbol_position_language_wise($Due_amt);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Due Amount','school-mgt');?>"></i></td>
												<td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start To End Year','school-mgt');?>"></i></td>
												<td class="action">
													<div class="smgt-user-dropdown">
														<ul class="" style="margin-bottom: 0px !important;">
															<li class="">
																<a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
																	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
																</a>
																<ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
																	<li class="float_left_width_100 ">
																		<a href="?dashboard=user&page=feepayment&tab=view_fesspayment&idtest=<?php echo $retrieved_data->fees_pay_id; ?>&view_type=view_payment" class="float_left_width_100"><i class="fa fa-eye"></i><?php esc_attr_e('View','school-mgt');?></a>
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
						</div><!-------------- TABLE RESPONSIVE ---------------->
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
			}
			//End Fees Payment Datatbale Report Tab //
			//Fees Payment Graph Report Tab //
			if($active_tab_1 == 'report4_graph')
			{
				?>
				<div class="panel-body clearfix">
					<?php
					$month =array('1'=>esc_html__('January','school-mgt'),'2'=>esc_html__('February','school-mgt'),'3'=>esc_html__('March','school-mgt'),'4'=>esc_html__('April','school-mgt'),
					'5'=>esc_html__('May','school-mgt'),'6'=>esc_html__('June','school-mgt'),'7'=>esc_html__('July','school-mgt'),'8'=>esc_html__('August','school-mgt'),
					'9'=>esc_html__('September','school-mgt'),'10'=>esc_html__('Octomber','school-mgt'),'11'=>esc_html__('November','school-mgt'),'12'=>esc_html__('December','school-mgt'),);
					$year =isset($_POST['year'])?$_POST['year']:date('Y');

					global $wpdb;
					$table_name = $wpdb->prefix."smgt_fees_payment";
					$q="SELECT EXTRACT(MONTH FROM paid_by_date) as date, sum(fees_paid_amount) as count FROM ".$table_name." WHERE YEAR(paid_by_date) =".$year." group by month(paid_by_date) ORDER BY paid_by_date ASC";
					$result=$wpdb->get_results($q);

					$sumArray = array();
					foreach ($result as $value)
					{
						if(isset($sumArray[$value->date]))
						{
							$sumArray[$value->date] = $sumArray[$value->date] + (int)$value->count;
						}
						else
						{
							$sumArray[$value->date] = (int)$value->count;
						}
					}

					$chart_array = array();
					$chart_array[] = array(esc_html__('Month','school-mgt'),esc_html__('Fees Payment','school-mgt'));
					$i=1;
					foreach($sumArray as $month_value=>$count)
					{
						$chart_array[]=array( $month[$month_value],(int)$count);
					}

					$options = Array(
								'title' => esc_html__('Fees Payment Report By Month','school-mgt'),
								'titleTextStyle' => Array('color' => '#66707e'),
								'legend' =>Array('position' => 'right',
								'textStyle'=> Array('color' => '#66707e')),
								'hAxis' => Array(
									'title' => esc_html__('Month','school-mgt'),
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'maxAlternation' => 2
									),
								'vAxis' => Array(
									'title' => esc_html__('Fees Payment','school-mgt'),
									'minValue' => 0,
									'maxValue' => 6,
									'format' => '#',
									'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins'),
									'textStyle'=> Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'Poppins')
									),
							'colors' => array('#22BAA0')
								);
					require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
					$GoogleCharts = new GoogleCharts;
					$chart = $GoogleCharts->load('column','chart_div')->get( $chart_array , $options );
					?>
					<div id="chart_div" class="chart_div">
					<?php
					if(empty($result))
					{
						?>
						<div class="clear col-md-12"><h3><?php esc_html_e("There is not enough data to generate report.",'school-mgt');?> </h3></div>
						<?php
					} ?>
				</div>
				<!-- Javascript -->
				<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				<script type="text/javascript">
					<?php if(!empty($result))
					{
						echo $chart;
					}
					?>
				</script>
				<?php
			}
			//End Fees Payment Graph Report Tab //
			?>
			<div class="clearfix"> </div>
			<?php
		}
		//End Fees Payment  Report Tab //
		//Result Report Tab //
		if($active_tab == 'report5')
		{
			?>
			<div class="panel-body margin_top_20px padding_top_15px_res">
				<form method="post" id="result_report">
					<div class="form-body user_form">
						<div class="row">
							<div class="col-md-3 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<select name="class_id"  id="class_list" class="line_height_30px form-control validate[required] class_id_exam">
										<?php $class_id="";
										if(isset($_REQUEST['class_id'])){
											$class_id=$_REQUEST['class_id'];
											}?>
										<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
										<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{
											?>
											<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
											<?php
										}?>
								</select>
							</div>
							<div class="col-md-3 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
								<?php
								$class_section="";
								if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
								<select name="class_section" class="line_height_30px form-control" id="class_section">
										<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php if(isset($_REQUEST['class_section'])){
											echo $class_section=$_REQUEST['class_section'];
											foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
											{  ?>
											<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
										<?php }
										}?>
								</select>
							</div>
							<div class="col-md-3 input">
								<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Exam','school-mgt');?><span class="require-field">*</span></label>
								<?php
								$tablename="exam";
								$retrieve_class = mj_smgt_get_all_data($tablename);?>
								<?php
								$exam_id="";
								if(isset($_REQUEST['exam_id'])){
											$exam_id=$_REQUEST['exam_id'];
								} ?>
								<select name="exam_id" class="line_height_30px form-control exam_list validate[required]">
									<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
									<?php
									foreach($retrieve_class as $retrieved_data)
									{
									?>
									<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name;?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-3">
								<input type="submit" name="report_5" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="clearfix panel-body">
				<?php
				if(isset($_POST['report_5']))
				{
					$exam_id=$_REQUEST['exam_id'];
					$class_id=$_REQUEST['class_id'];
					if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
						$subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id'],$_REQUEST['class_section']);
						$exlude_id = mj_smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
										'meta_query'=> array(array('key' => 'class_name','value' =>$_REQUEST['class_id'],'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));
					}
					else
					{
						$subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id']);
						$exlude_id = mj_smgt_approve_student_list();
						$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $_REQUEST['class_id'],'role'=>'student','exclude'=>$exlude_id));
					} ?>
					<script type="text/javascript">
					jQuery(document).ready(function($)
					{
						var table = jQuery('#example5').DataTable({
							//stateSave: true,
								"order": [[ 2, "Desc" ]],
								"dom": 'lifrtp',
								"aoColumns":[
									{"bSortable": false},
									{"bSortable": true},
									{"bSortable": true},
									<?php
									if(!empty($subject_list))
									{
										foreach($subject_list as $sub_id)
										{
											?>
											{"bSortable": true},
											<?php
										}
									}
									?>
									{"bSortable": true}
								],
								language:<?php echo mj_smgt_datatable_multi_language();?>
						});
						$('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
					});
				</script>
					<div class="table-responsive">
						<table id="example5" class="display" cellspacing="0" width="100%">
							<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
							<tr>
								<th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
								<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
								<th><?php esc_attr_e('Student Name','school-mgt');?></th>
								<?php
									if(!empty($subject_list))
									{
										foreach($subject_list as $sub_id)
										{

											echo "<th> ".$sub_id->sub_name." </th>";
										}
									}
								?>
								<th><?php esc_attr_e('Total','school-mgt');?></th>
							</tr>
						</thead>
							<tbody>
								<?php
								if(!empty($student))
								{
									$i=0;
									foreach ($student as $user)
									{
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
										$total=0;
										?>
										<tr>
											<td class="user_image width_50px profile_image_prescription padding_left_0">
												<p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">
													<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Icons/White_icons/Payment.png"?>" alt="" class="massage_image center margin_top_3px">
												</p>
											</td>
											<td><?php echo $user->roll_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Roll No.','school-mgt');?>"></i></td>
											<td><?php echo mj_smgt_get_user_name_byid($user->ID);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>
											<?php
											if(!empty($subject_list))
											{
												foreach($subject_list as $sub_id)
												{
													$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$sub_id->subid,$user->ID);
													if($mark_detail)
													{
														$mark_id=$mark_detail->mark_id;
														$marks=$mark_detail->marks;
														$total+=$marks;
													}
													else
													{
														$marks=0;
														$attendance=0;
														$marks_comment="";
														$total+=0;
														$mark_id="0";
													}
													?>
														<td><?php echo $marks; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo $sub_id->sub_name; ?> <?php esc_html_e('Mark','school-mgt'); ?>"></i></td>
													<?php
												}
												?>
													<td><?php echo $total; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Marks','school-mgt');?>"></i></td>
												<?php
											}
											else
											{
												?>
												<td><?php echo $total; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Marks','school-mgt');?>"></i></td>
												<?php
											}
											?>
										</tr>
										<?php
										$i++;
									}
								}

								?>
							</tbody>
						</table>
					</div>
					<!-- end panel body div -->
					<?php
				}
				?>
			</div>
			<?php
		}
		//End Result Report Tab //
		?>
	</div><!----------- PENAL BODY ------------->
</div><!----------- PENAL WHITE ------------->