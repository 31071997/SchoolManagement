<?php
    $tablename="smgt_class";
    $retrieve_class = mj_smgt_get_all_data($tablename);
?>

<script type="text/javascript">
    jQuery(document).ready(function($){
        "use strict";
        var table = jQuery('#class_section_report').DataTable({
            "order": [[ 1, "Desc" ]],
            "dom": 'lifrtp',
            //stateSave: true,
            // buttons:[
            //     {
            //         extend: 'csv',
            //         text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
            //         title: '<?php echo esc_attr_e("Class & Section Report","school-mgt"); ?>',
            //     },
            //     {
            //         extend: 'print',
            //         text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
            //         title: '<?php echo esc_attr_e("Class & Section Report","school-mgt"); ?>',
            //     },
            // ],
            "aoColumns":[                 
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": true}],
            language:<?php echo mj_smgt_datatable_multi_language();?>
            });
        $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
    });
</script>
<div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
    <?php
    if(!empty($retrieve_class))
    {
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                <h4 class="report_heder"><?php esc_html_e('Class & Section Report','school-mgt');?></h4>
            </div>
        </div>
        <div class="table-responsive">
            <div class="btn-place"></div>
            <form id="frm_student_report" name="frm_student_report" method="post">
                <table id="class_section_report" class="display class_section_report_tbl" cellspacing="0" width="100%">
                    <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                        <tr>
                            <th><?php esc_attr_e('Sr. No.','school-mgt');?></th>
                            <th><?php esc_attr_e('Class','school-mgt');?></th>
                            <th><?php esc_attr_e('Students','school-mgt');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach ($retrieve_class as $retrieved_data)
                        { 
                            ?>
                            <tr>
                                <td><?php echo $i; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Sr. No.','school-mgt');?>"></i></td>
                                <td>
                                    <?php echo ($retrieved_data->class_name); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i>
                                </td>
                                <td>
                                    <?php
                                        $studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $retrieved_data->class_id,'role'=>'student'));
                                        echo count($studentdata);
                                    ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student','school-mgt');?>"></i>

                                </td>
                            </tr>
                            <?php
                            $i++;
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