<div class="panel-body clearfix  rtl_margin_0px padding_top_15px_res">
    <?php
    //-------------- ADMISSION REPORT - DATA ---------------//
        $admission=MJ_smgt_admission_student_list();
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#admission_list_report').DataTable({
                //stateSave: true,
                "responsive": true,
                "order": [[ 2, "Desc" ]],
                "dom": 'Qlfrtip',
                layout: {
                    top1: 'searchBuilder'
                },
                buttons:[
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Admission Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Admission Report","school-mgt"); ?>',
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
    <div class="panel-body  rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($admission))
        {
            ?>
            <div class="table-responsive">
                <div  class="btn-place"></div>
                <form id="frm-admisssion" name="frm-admisssion" method="post">
                    <table id="admission_list_report" class="display admission_report_tbl" cellspacing="0" width="100%">
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th><?php esc_attr_e('Admission No','school-mgt');?>.</th>
                                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Email Id','school-mgt');?></th>
                                <!-- <th><?php esc_attr_e('Class (Section)','school-mgt');?></th> -->
                                <th><?php esc_attr_e('Date of Birth','school-mgt');?></th>
                                <th><?php esc_attr_e('Admission Date','school-mgt');?></th>
                                <th><?php esc_attr_e('Gender','school-mgt');?></th>
                                <th><?php esc_attr_e('Mobile Number','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            foreach ($admission as $retrieved_data)
                            { 
                                $student_data=get_userdata($retrieved_data->ID);
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                            if(get_user_meta($retrieved_data->ID, 'admission_no', true))
                                            {
                                                echo get_user_meta($retrieved_data->ID, 'admission_no',true);
                                            }
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Number','school-mgt');?>"></i>
                                    </td>
                                    <td>  
                                        <?php echo $student_data->display_name; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
                                    </td>
                                    <td>  
                                        <?php echo $retrieved_data->user_email;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Email ID','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date of Birth','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo $student_data->admission_date; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Date','school-mgt');?>"></i> 
                                    </td>
                                    <td>
                                        <?php 
                                            if($student_data->gender=='male') 
                                            {
                                                echo esc_attr__('Male','school-mgt');
                                            }
                                            elseif($student_data->gender=='female') 
                                            {
                                                echo esc_attr__('Female','school-mgt');
                                            }
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