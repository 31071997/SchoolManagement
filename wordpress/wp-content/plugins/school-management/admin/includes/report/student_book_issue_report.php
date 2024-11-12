<div class="panel-body clearfix margin_top_20px padding_top_15px_res">
	<div class="panel-body clearfix">
       <?php
       $class_id="";
       ?>
    <form method="post" id="student_book_issue_report">  
            <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-6 mb-3 input rtl_margin_bottom_0px">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
                        <select name="class_id"  id="class_list" class="form-control validate[required]">
                            <?php 
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
                    <div class="col-md-6 mb-3 input rtl_margin_bottom_0px">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>			
                        <?php 
                        $class_section="";
                        if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
                        <select name="class_section" class="form-control" id="class_section">
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
                    <div class="col-md-6 mb-3 input">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Date Type','school-mgt');?><span class="require-field">*</span></label>			
                            <select class="form-control date_type validate[required]" name="date_type" autocomplete="off">
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
    <?php
    //-------------- ADMISSION REPORT - DATA ---------------//
    $class_id = "";
    $class_section = ""; 
    $date_type= "";
    if(isset($_REQUEST['library_report']))
    {
        //var_dump($_REQUEST);

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
                //stateSave: true,
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
                                        <?php echo mj_smgt_student_display_name_with_roll($retrieved_data->student_id);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
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

</div>	