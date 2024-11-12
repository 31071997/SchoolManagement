<div class="panel-body clearfix">
		 <?php
		 if(isset($_REQUEST['download_attendance']))
		 {
           $start_date = $_POST['sdate'];
           $end_date = $_POST['edate'];
		 }
		 else
		 {
			 $start_date = date('Y-m-d');
             $end_date = date('Y-m-d');
			
		 }
		    $header = array();			
			$header[] = 'Class Name';
			$header[] = 'Present Student';
			$header[] = 'Absent Student';
			$header[] = 'Total Student';
			$filename='Reports/export_attendance.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			
			foreach(mj_smgt_get_allclass() as $classdata)
			{
				$class_id=$classdata['class_id'];
				$row = array();
				$total_present=mj_smgt_view_attendance_report_for_start_date_enddate_total_present($start_date,$end_date,$class_id);
				$total_absent=mj_smgt_view_attendance_report_for_start_date_enddate_absent($start_date,$end_date,$class_id);
				$total=mj_smgt_view_attendance_report_for_start_date_enddate_total($class_id);
				$classname=mj_smgt_get_class_name($class_id);	
				$row[] = $classname;
				$row[] = $total_present;
				$row[] = $total_absent;
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
         ?>
    </div>