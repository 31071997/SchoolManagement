<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
    <div class="row">
		<div class="col-md-3 input">
			<select class="line_height_30px form-control teacher_graph date_type validate[required]" name="date_type" autocomplete="off">
				<option value="today"><?php esc_attr_e('Today','school-mgt');?></option>
				<option value="this_week"><?php esc_attr_e('This Week','school-mgt');?></option>
				<option value="last_week"><?php esc_attr_e('Last Week','school-mgt');?></option>
				<option value="this_month" 	selected><?php esc_attr_e('This Month','school-mgt');?></option>
				<option value="last_month"><?php esc_attr_e('Last Month','school-mgt');?></option>
				<option value="last_3_month"><?php esc_attr_e('Last 3 Months','school-mgt');?></option>
				<option value="last_6_month"><?php esc_attr_e('Last 6 Months','school-mgt');?></option>
				<option value="last_12_month"><?php esc_attr_e('Last 12 Months','school-mgt');?></option>
				<option value="this_year"><?php esc_attr_e('This Year','school-mgt');?></option>
				<option value="last_year"><?php esc_attr_e('Last Year','school-mgt');?></option>
			</select>
		</div>
	</div>

    <div class="events1" id="teacher_graph_id">
    <?php
	global $wpdb;
	$table_attendance = $wpdb->prefix . 'attendence';

	if(isset($_REQUEST['view_attendance']))
	{
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	}
	else
	{
		$sdate = date('Y-m-d',strtotime('first day of this month'));
		$edate = date('Y-m-d',strtotime('last day of this month'));
	}

	$report_2 =$wpdb->get_results("SELECT  at.user_id, 
	SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
	SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
	from $table_attendance as at where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.user_id AND at.role_name = 'teacher' GROUP BY at.user_id") ;
	$chart_array = array();
	$chart_array[] = array(esc_attr__('teacher', 'school-mgt'), esc_attr__('Present', 'school-mgt'), esc_attr__('Absent', 'school-mgt'));
	if (!empty($report_2))
	{
		foreach ($report_2 as $result) 
		{

			$class_id = mj_smgt_get_user_name_byid($result->user_id);
			$chart_array[] = array("$class_id", (int)$result->Present, (int)$result->Absent);
		}
	}
	$options = array(
		'title' => esc_attr__('This Month Attendance Report', 'school-mgt'),
		'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
		'legend' => array(
			'position' => 'right',
			'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
		),

		'hAxis' => array(
			'title' =>  esc_attr__('Teacher', 'school-mgt'),
			'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'maxAlternation' => 2


		),
		'vAxis' => array(
			'title' =>  esc_attr__('No. of Days', 'school-mgt'),
			'minValue' => 0,
			'maxValue' => 4,
			'format' => '#',
			'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
		),
		'colors' => array('#5840bb', '#f25656')
	);
	require_once SMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
	$GoogleCharts = new GoogleCharts;
	if (!empty($report_2)) 
	{
		$chart = $GoogleCharts->load('column', 'chart_div_last_month')->get($chart_array, $options);
	}
	else
	{
		?>
		<div class="calendar-event-new"> 
			<img class="no_data_img" src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/no_data_img.png"?>" >
		</div>	
		<?php
	}

	if (isset($report_2) && count($report_2) > 0) 
	{
		?>
		<div id="chart_div_last_month" class="w-100 h-500-px"></div>

		<!-- Javascript -->
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			<?php echo $chart; ?>
		</script>
	<?php
	}
	?>
    </div>
</div>