<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
        <form method="post">  
			<div class="form-body user_form">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group input">
							<div class="col-md-12 form-control">
								<input type="text"  id="sdate" class="form-control" name="date" value="<?php if(isset($_REQUEST['date'])) echo mj_smgt_getdate_in_input_box($_REQUEST['date']);else echo mj_smgt_getdate_in_input_box(date('Y-m-d'));?>" readonly>
								<label for="userinput1" class=""><?php esc_html_e('Date','school-mgt');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<input type="submit" name="daily_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
					</div>
				</div>
			</div>
		</form>
    </div>	
	<?php
	// ----Download Daily attendance Report in CSV -- start ---/
	if(isset($_POST['download_daily_attendance']))
	{
		$daily_date = $_POST['daily_date'];

		$header = array();			
		$header[] = 'Class Name';
		$header[] = 'Present Student';
		$header[] = 'Absent Student';
		$header[] = 'Present %';
		$header[] = 'Absent %';
		$header[] = 'Total Student';
		$filename='Reports/export_attendance.csv';
		$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
		fputcsv($fh, $header);
		
		foreach(mj_smgt_get_allclass() as $classdata)
		{

			$row = array();
			$class_id=$classdata['class_id'];
			$classname=mj_smgt_get_class_name($class_id);
			$total=mj_smgt_view_attendance_report_for_start_date_enddate_total($class_id);
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

			
			$row[] = $classname;
			$row[] = $total_present;
			$row[] = $total_absent;
			$row[] = $present_per;
			$row[] = $absent_per;
			$row[] = $total;
			fputcsv($fh, $row);
		}
		fclose($fh);
		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_attendance.csv';//file location
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
		exit;
	}
	//---- Download Daily attendance Report in CSV -- End ---/

	if(isset($_REQUEST['daily_attendance']))
	{
		$daily_date = $_POST['date'];
	}
	else
	{
		$daily_date = date('Y-m-d');
		
	}

	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			"use strict";
			var table = jQuery('#daily_attendance_list_report').DataTable({
				"initComplete": function(settings, json) {
						$(".print-button").css({"margin-top": "-5%"});
					},
					//stateSave: true,
				"order": [[ 2, "Desc" ]],
				"dom": 'lifrtp',
				"buttons": [
					{
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Attendance Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Attendance Report","school-mgt"); ?>',
                    },
                ],
				"aoColumns":[                 
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
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
				<h4 class="report_heder"><?php esc_html_e('Daily Attendance Report','school-mgt');?></h4>
			</div>
		</div>
		<div class="table-responsive">
			<div class="btn-place"></div>
			<form id="frm-daily-attendance" name="frm-daily-attendance" method="post">
				<table id="daily_attendance_list_report" class="display" cellspacing="0" width="100%">
					<input type="hidden" name="daily_date" value="<?php echo $daily_date;?>" />
					<thead class="<?php echo MJ_smgt_datatable_heder() ?>">
						<tr>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Total Present','school-mgt');?></th>
							<th><?php esc_attr_e('Total Absent','school-mgt');?></th>
							<th><?php esc_attr_e('Present','school-mgt');?><?php esc_attr_e(' %','school-mgt');?></th>
							<th><?php esc_attr_e('Absent','school-mgt');?><?php esc_attr_e(' %','school-mgt');?></th>
							<th><?php esc_attr_e('Total Student','school-mgt');?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{
							$class_id=$classdata['class_id'];
							$total=mj_smgt_view_attendance_report_for_start_date_enddate_total($class_id);
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
								<td><?php echo $total;?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<div class="print-button pull-left">
                    <!-- <button data-toggle="tooltip" title="<?php esc_html_e('Download Report in CSV','school-mgt');?>" name="download_daily_attendance" class=" padding_0"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/export_csv.png" ?>" alt=""></button> -->
					<button data-toggle="tooltip" title="<?php esc_html_e('Download Report in CSV','school-mgt');?>" name="download_daily_attendance" class="att_download_csv_btn padding_0"><?php esc_html_e('Download CSV','school-mgt');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php
	?>
</div>	