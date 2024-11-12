<div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
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
                    <select name="class_section" class="line_height_30px section_id_exam form-control" id="class_section">
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
                <div class="col-md-6 input">
                    <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
                    <?php
                    $tablename="exam";
                    $retrieve_class = mj_smgt_get_all_data($tablename);
                    $exam_id="";
                    if(isset($_REQUEST['exam_id']))
                    {
                        $exam_id=$_REQUEST['exam_id'];
                    }
                    ?>
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
            </div>
        </div>
        <div class="form-body user_form">
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" name="report_1" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                </div>
            </div>
        </div>	
    </form>
</div>
<!-- penal body div -->
<div class="clearfix"> </div>
<div class="clearfix"> </div>
<?php 
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
                'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
                'legend' =>Array('position' => 'right',
                        'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
                    
                'hAxis' => Array(
                        'title' =>  esc_attr__('Subject','school-mgt'),
                        'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
                        'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
                        'maxAlternation' => 2
                ),
                'vAxis' => Array(
                        'title' =>  esc_attr__('No. of Student','school-mgt'),
                        'minValue' => 0,
                        'maxValue' => 5,
                        'format' => '#',
                        'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
                        'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
                ),
                'colors' => array('#5840bb')
        );
    }

if(!empty($report_1))
{

    if(isset($_REQUEST['report_1']))
    {
        
        if(!empty($report_1))
        {				
            $chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
        }
        else 
        {
            echo esc_attr_e('result not found','school-mgt');
        }
        
    }
    ?>
    <div id="chart_div" class="w-100 h-100 margin_top_20px rtl_margin_0px padding_top_15px_res"></div>
    <!-- Javascript --> 
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript">
        <?php
        echo $chart;?>
    </script>
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