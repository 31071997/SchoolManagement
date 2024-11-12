<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
    <?php
    $chart_array[] = array(esc_attr__('Teacher','school-mgt'),esc_attr__('fail','school-mgt'));
    global $wpdb;
    $table_subject = $wpdb->prefix .'subject';
    $table_name_mark = $wpdb->prefix .'marks';
    $table_name_users = $wpdb->prefix .'users';
    $table_teacher_subject = $wpdb->prefix .'teacher_subject';		
    $teachers = get_users(array("role"=>"teacher"));
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
                $total_fail =0;
            }
            $teacher_name = mj_smgt_get_display_name($teacher_id);
            $chart_array[] = [$teacher_name , $total_fail];
        }
    }
    $color = get_option('smgt_system_color_code');
    $options = Array(
        'title' => esc_attr__('Teacher Performance Report','school-mgt'),
        'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
        'legend' =>Array('position' => 'right',
            'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
            'hAxis' => Array(
                'title' =>  esc_attr__('Teacher Name','school-mgt'),
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
            'colors' => array($color)
    );
    if(!empty($report_3))
    {
        require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
        $GoogleCharts = new GoogleCharts;
        ?>

        <!-- <div class="clearfix"> </div> -->
        <?php 
            if(!empty($report_3))
            {
                $chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
            }
            else 
            {
                esc_attr_e('result not found','school-mgt');
            }
        ?>
        <div id="chart_div" class="w-100 h-100 margin_top_20px rtl_margin_0px padding_top_15px_res"></div>

        <!-- Javascript --> 
        <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
        <script type="text/javascript">
            <?php echo $chart;?>
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
</div>