<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
        <?php
        $class_id="";
        ?>
        <form method="post" id="student_attendance">  
            <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-3 mb-3 input">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
                        <select name="class_id"  id="class_list" class="line_height_30px form-control validate[required]">
                            
                            <option value="all class"><?php esc_attr_e('All Class','school-mgt');?></option>
                            <?php 
							
							if(isset($_REQUEST['class_id']))
							{
								$class_id=$_REQUEST['class_id'];
							}?>
							<!-- <option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option> -->
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
                            <option ><?php esc_attr_e('Select year','school-mgt');?></option>
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
						<label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Month','school-mgt');?><span class="require-field">*</span></label>
                        <select id="month" name="month" class="line_height_30px form-control class_id_exam validate[required]">
                            <option ><?php esc_attr_e('Select Month','school-mgt');?></option>
                            <?php
                            $selected_month = date('m'); //current month
                            for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                $selected = ($selected_month == $i_month ? ' selected' : '');
                                $data = date('F', mktime(0,0,0,$i_month));
                                echo '<option value="'.$i_month.'"'.$selected.'>'.esc_html__($data,'school-mgt').'</option>'."\n";
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
    //--- Download Monthly Attendance CSV file --start --//
    if(isset($_POST['monthly_attendance_csv_download']))
    {
        $class_id = $_POST['class_id'];
        $class_section = $_POST['class_section'];
        $year = $_POST['year'];
        $month = $_POST['month'];

        // fetch day and date by year,Month
        $list=array();
        
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
        if($class_id=="all class" && $class_section == "")
        {
            $student = get_users(array('role'=>'student'));
            sort($student);
        }
        elseif($class_section == "")
        {
            $student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
            sort($student);
        }
        else
        { 
            $student = 	get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id)),'role'=>'student'));
            sort($student);
        } 

		$header = array();			
		$header[] = 'Student';
		$header[] = 'Present';
		$header[] = 'Late';
		$header[] = 'Absent';
		$header[] = 'Half Day';
        $header[] = 'Holiday';
          
        foreach($day_date as $data)
        { 
            $header[] = $data;
        }
		$filename='Reports/monthly_attendance.csv';
		$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
		fputcsv($fh, $header);
		
        foreach ($student as $user) 
        {
            $row = array();
            $class_id = get_user_meta($user->ID,'class_name',true);
            $student_name = mj_smgt_get_display_name($user->ID);
           
            $Present='Present';
            $total_present=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Present);
            $total_present_count =count($total_present);
            
            $Late='Late';
            $total_late=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Late);
            $total_late_count =count($total_late);
        
            $Absent='Absent';
            $total_absent=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Absent);
            $total_absent_count =count($total_absent);
        
            $Half_Day='Half Day';
            $total_Half_day=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Half_Day);
            $total_Half_day_count =count($total_Half_day);
            
            $total_Holiday_day=mj_smgt_get_all_holiday_by_month_year($month,$year);
            $total_Holiday_day_count =count($total_Holiday_day);

            $row[] = $student_name;
			$row[] = $total_present_count;
			$row[] = $total_late_count;
			$row[] = $total_absent_count;
			$row[] = $total_Half_day_count;
            $row[] = $total_Holiday_day_count;
            foreach($date_list as $date)
            {
                $status=mj_smgt_attendance_report_all_status_value($date,$class_id,$user->ID);
                $row[] =  $status;
            }
		 	fputcsv($fh, $row);
        }
		fclose($fh);
		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/monthly_attendance.csv';//file location
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
    //--- Download Monthly Attendance CSV file -- End --//

    //-------------- MONTHLY ATTENDANCE Report ---------------//
	if(isset($_REQUEST['view_attendance']))
	{
        $class_id = $_POST['class_id'];
        $class_section = $_POST['class_section'];
        $year = $_POST['year'];
        $month = $_POST['month'];
    }
    else
    {
        $class_id = "all class";
        $year = date("Y"); 
        $month = date("m");
    }

    // fetch day and date by year,Month
    $list=array();
    
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
    if($class_id=="all class" && $class_section == "")
    {
        $student = get_users(array('role'=>'student'));
        sort($student);
    }
    elseif($class_section == "")
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
    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css" />

    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#class_attendance_list_report').DataTable({
                //stateSave: true,
                "responsive": false,
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
                    {"bSortable": false},
                    {"bSortable": false},
                    {"bSortable": false}, 
                    {"bSortable": false}, 
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
            $('.btn-place').html(table.buttons().container()); 
        });
    </script>
    <div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($student))
        {
            ?>
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
                    <div class="btn-place"></div>
                    <table id="class_attendance_list_report" class="display class_att_repost_tbl" cellspacing="0" width="100%">
                        <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
                        <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
                        <input type="hidden" name="year" value="<?php echo $year;?>" />
                        <input type="hidden" name="month" value="<?php echo $month;?>" />
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
                                    <th class="<?php echo $data;?>"><?php  echo esc_attr_e($data,'school-mgt');?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($student as $user) 
                            {
                                $class_id = get_user_meta($user->ID,'class_name',true);
                                foreach($date_list as $date)
                                {
                                    $count[] =  mj_smgt_attendance_report_all_status_value($date,$class_id,$user->ID);
                                }
                                
                                ?>
                                <tr>
                                    <td class ='monthly_atttendance_report'>
                                        <?php echo mj_smgt_student_display_name_with_roll($user->ID);?> 
                                    </td>
                                    <td class ='monthly_atttendance_report'> 
                                        <?php  
                                        // $Present='Present';
                                        // $total_present=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Present);
                                        
                                        // echo count($total_present);

                                        $countP = 0;
                                        foreach ($count as $value) {
                                            if ($value === 'P') {
                                                $countP++;
                                            }
                                        }
                                        echo $countP;
                                        ?>
                                    </td>
                                    <td class ='monthly_atttendance_report'>
                                        <?php 
                                            // $Late='Late';
                                            // $total_late=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Late);
                                            // echo count($total_late);
                                            $countL = 0;
                                            foreach ($count as $value) {
                                                if ($value === 'L') {
                                                    $countL++;
                                                }
                                            }
                                            echo $countL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // $Absent='Absent';
                                        // $total_absent=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Absent);
                                        // echo count($total_absent);
                                        
                                            $countA = 0;

                                            foreach ($count as $value) {
                                                if ($value === 'A') {
                                                    $countA++;
                                                }
                                            }
                                            echo $countA;
                                        
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            // $Half_Day='Half Day';
                                            // $total_Half_day=mj_smgt_attendance_report_get_status_for_student_id($month_first_date,$month_last_date, $class_id,$user->ID,$Half_Day);
                                            // echo count($total_Half_day);
                                            $countF = 0;
                                            foreach ($count as $value) {
                                                if ($value === 'F') {
                                                    $countF++;
                                                }
                                            }
                                            echo $countF;
                                        ?>
                                    </td>
                                    <td>
                                    <?php 
                                        // $total_Holiday_day=mj_smgt_get_all_holiday_by_month_year($month,$year);
                                        // echo count($total_Holiday_day);
                                        $countH = 0;
                                        foreach ($count as $value) {
                                            if ($value === 'H') {
                                                $countH++;
                                            }
                                        }
                                        echo $countH;
                                        ?>
                                    </td>
                                    <?php
                                    $count = array();
                                    foreach($date_list as $date)
                                    {
                                        
                                        ?>
                                        <td class="att_status_color">
                                            <span class="<?php echo mj_smgt_attendance_report_all_status_value($date,$class_id,$user->ID); ?>">
                                                <?php
                                                    echo mj_smgt_attendance_report_all_status_value($date,$class_id,$user->ID); 
                                                ?>
                                                </span>
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
                    <div class="print-button pull-left">
                        <button data-toggle="tooltip" title="<?php esc_html_e('Download CSV','school-mgt');?>" name="monthly_attendance_csv_download" class="att_download_csv_btn padding_0"><?php esc_html_e('Download Report in CSV','school-mgt');?></button>
                    </div>
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