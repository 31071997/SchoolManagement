<div class="panel-body clearfix  rtl_margin_0px padding_top_15px_res">
    <?php
    //-------------- STUDENT REPORT -DATA ---------------//
    $studentdata = get_users(array('role'=>'student'));
    sort($studentdata);
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#student_report').DataTable({
                //stateSave: true,
                // "responsive": true,
                "order": [[ 1, "Desc" ]],
                "dom": 'Qlfrtip',
                language:<?php echo mj_smgt_datatable_multi_language();?>,
                buttons:[
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Report","school-mgt"); ?>',
                    }
                ],
                "aoColumns":[                 
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true}, 
                    {"bSortable": true}, 
                    {"bSortable": true}, 
                    {"bSortable": true}],
               
                
                });
            $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
            $('.btn-place').html(table.buttons().container()); 
        });
    </script>
    <div class="panel-body  rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($studentdata))
        {
            ?>
            <div class="table-responsive">
                <form id="frm_student_report" name="frm_student_report" method="post">
                    <div class="btn-place"></div>
                    <table id="student_report" class="display student_report_tbl" cellspacing="0" width="100%">
                        <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
                        <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
                        <input type="hidden" name="gender" value="<?php echo $gender;?>" />
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th><?php esc_attr_e('Class','school-mgt');?></th>
                                <th><?php esc_attr_e('Admission No','school-mgt');?>.</th>
                                <th><?php esc_attr_e('Roll No.','school-mgt');?>.</th>
                                <th><?php esc_attr_e('Student Name & Email','school-mgt');?></th>
                                <th><?php esc_attr_e('Parent Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Date of Birth','school-mgt');?></th>
                                <th><?php esc_attr_e('Gender','school-mgt');?></th>
                                <th><?php esc_attr_e('Mobile Number','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($studentdata as $retrieved_data)
                            { 
                                $student_data=get_userdata($retrieved_data->ID);
                                $parent_id =get_user_meta($retrieved_data->ID, 'parent_id', true);
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                            $class_name = smgt_get_class_section_name_wise($student_data->class_name,$student_data->class_section); 
                                            echo $class_name;

                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php 
                                            if(get_user_meta($retrieved_data->ID, 'admission_no', true))
                                            {
                                                echo get_user_meta($retrieved_data->ID, 'admission_no',true);
                                            }
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Number','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php 
                                            if(get_user_meta($retrieved_data->ID, 'roll_id', true))
                                            {
                                                echo get_user_meta($retrieved_data->ID, 'roll_id',true);
                                            }
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Roll No.','school-mgt');?>"></i>
                                    </td>
                                    <td>  
                                        <?php echo $retrieved_data->display_name;?>
                                        <br>
										<label class="list_page_email"><?php echo $retrieved_data->user_email;?></label> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name & Email','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($parent_id)) { 
                                            $parents_name = array();
                                            
                                            foreach ($parent_id as $parents_data) {
                                                $parents_name[] = mj_smgt_get_display_name($parents_data);
                                            }
                                            
                                            // Get unique parent names
                                            $unique_parents_name = array_unique($parents_name);
                                            
                                            // Get the count of unique names
                                            $length = count($unique_parents_name);
                                            
                                            // Loop through unique names and echo them
                                            foreach ($unique_parents_name as $index => $parent) {
                                                echo $parent;
                                                
                                                // Add a comma if it's not the last element
                                                if ($index < $length - 1) {
                                                    echo ', ';
                                                }
                                            }
                                        }
                                        
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Father Name','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date of Birth','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                    <?php 
                                        if($student_data->gender=='male') 
                                            echo esc_attr__('Male','school-mgt');
                                        elseif($student_data->gender=='female') 
                                            echo esc_attr__('Female','school-mgt');
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Gender','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php if(!empty($student_data->mobile_number)){ echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.$student_data->mobile_number;}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile Number','school-mgt');?>"></i>
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