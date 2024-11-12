<div class="panel-body clearfix rtl_margin_0px padding_top_15px_res">
    <?php
    //-------------- STUDENT REPORT -DATA ---------------//
        $studentdata = get_users(array('role'=>'student'));
    
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#student_report').DataTable({
                //stateSave: true,
                "responsive": true,
                "order": [[ 1, "Desc" ]],
                "dom": 'Qlfrtip',
                buttons:[
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Guardian Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Guardian Report","school-mgt"); ?>',
                    },
                ],
                "aoColumns":[                 
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
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
    <div class="panel-body rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($studentdata ))
        {
            ?>
            <div class="table-responsive">
                <div class="btn-place"></div>
                <form id="frm_student_report" name="frm_student_report" method="post">
                    <table id="student_report" class="display student_report_tbl" cellspacing="0" width="100%">
                        <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
                        <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
                        <input type="hidden" name="gender" value="<?php echo $gender;?>" />
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th><?php esc_attr_e('Class','school-mgt');?></th>
                                <th><?php esc_attr_e('Admission No','school-mgt');?>.</th>
                                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Mobile Number','school-mgt');?></th>
                                <th><?php esc_attr_e('Date of Birth','school-mgt');?></th>
                                <th><?php esc_attr_e('Guardian Relation','school-mgt');?></th>
                                <th><?php esc_attr_e('Father Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Father Phone','school-mgt');?></th>
                                <th><?php esc_attr_e('Mother Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Mother Phone','school-mgt');?></th> 
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
                                        <?php echo mj_smgt_student_display_name_with_roll($student_data->ID); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php if(!empty($student_data->mobile_number)){  echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.$student_data->mobile_number;} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile Number','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Date of Birth','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($parent_id))
                                        {
                                            $relation_name=array();
                                            foreach($parent_id as $parents_data)
                                            {
                                                $relation = get_user_meta($parents_data, 'relation',true);
                                                $relation_name[] = get_user_meta($parents_data, 'relation',true);
                                            }
                                            if(!empty($relation_name))
                                            {
                                                echo implode(" / ",$relation_name);
                                            }
                                            
                                        }
                                       
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Guardian Relation','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($parent_id))
                                        {
                                            foreach($parent_id as $parents_data)
                                            {
                                                $relation = get_user_meta($parents_data, 'relation',true);
                                                if($relation=="Father")
                                                {
                                                    $parents=get_userdata($parents_data);
                                                    echo $parents->first_name." ".$parents->last_name.'<br>';
                                                }
                                                
                                            }
                                        }
                                        
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Father Name','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                    <?php
                                        if(!empty($parent_id))
                                        {
                                            foreach($parent_id as $parents_data)
                                            {
                                                $relation = get_user_meta($parents_data, 'relation',true);
                                                if($relation=="Father")
                                                {
                                                    $parents=get_userdata($parents_data);
                                                    if(!empty(get_user_meta($parents_data, 'mobile_number',true)))
                                                    {
                                                        echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.get_user_meta($parents_data, 'mobile_number',true);
                                                    }
                                                }
                                                
                                            }
                                        }
                                       
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Father Phone','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($parent_id))
                                        {
                                            foreach($parent_id as $parents_data)
                                            {
                                                //var_dump($parents_data);
                                                $relation = get_user_meta($parents_data, 'relation',true);
                                                if($relation=="Mother")
                                                {
                                                    $parents=get_userdata($parents_data);
                                                    
                                                    echo $parents->first_name." ".$parents->last_name.'<br>';
                                                }
                                               
                                            }
                                        }
                                       
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mother Name','school-mgt');?>"></i>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($parent_id))
                                        {
                                            foreach($parent_id as $parents_data)
                                            {
                                                $relation = get_user_meta($parents_data, 'relation',true);
                                                if($relation=="Mother")
                                                {
                                                    $parents=get_userdata($parents_data);
                                                    if(!empty(get_user_meta($parents_data, 'mobile_number',true)))
                                                    {
                                                        echo '+'.mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).' '.get_user_meta($parents_data, 'mobile_number',true);
                                                    }
                                                    
                                                }
                                                
                                            }
                                        }
                                        
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mother Phone','school-mgt');?>"></i>
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