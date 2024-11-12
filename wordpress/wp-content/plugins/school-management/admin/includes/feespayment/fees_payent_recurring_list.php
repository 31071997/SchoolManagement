<?php
$retrieve_class = $obj_feespayment->mj_smgt_get_all_recurring_fees();	
if(!empty($retrieve_class))
{
    ?>
    <script type="text/javascript">

        jQuery(document).ready(function($){

            var table =  jQuery('#recurring_fees_paymnt_list').DataTable({

                "initComplete": function(settings, json) {

                        $(".print-button").css({"margin-top": "-5%"});
                        $('#recurring_fees_paymnt_list th:first-child').removeClass('sorting_asc');

                    },

                responsive: true,
                "dom": 'lifrtp',
                "aoColumns":[
                {"bSortable": false},


                {"bSortable": true},

                {"bSortable": true},

                {"bSortable": true},

                {"bSortable": true},

                {"bSortable": true},

                {"bSortable": true},
                {"bSortable": true},

                {"bSortable": false}],

                language:<?php echo mj_smgt_datatable_multi_language();?>

            });

            $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");

            jQuery('#checkbox-select-all').on('click', function(){     

                var rows = table.rows({ 'search': 'applied' }).nodes();

                jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);

            }); 

        

            $("#delete_selected").on('click', function()

            {	

                if ($('.select-checkbox:checked').length == 0 )

                {

                    alert(language_translate2.one_record_select_alert);

                    return false;

                }

                else

                {

                        var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?', 'school-mgt') ?>");

                        if(alert_msg == false)

                        {

                            return false;

                        }

                        else

                        {

                            return true;

                        }

                }

            });

            

            $("#fees_reminder").on('click', function()

            {	

                    if ($('.select-checkbox:checked').length == 0 )

                    {

                        alert(language_translate2.one_record_select_alert);

                        return false;

                    }

                else

                {

                        var alert_msg=confirm("<?php esc_html_e("Are you sure you want to send a mail reminder?",'school-mgt') ?>");

                        if(alert_msg == false)

                        {

                            return false;

                        }

                        else

                        {

                            return true;

                        }

                }

            });

            $('.select_all').on('click', function(e)

            {

                if($(this).is(':checked',true))  

                {

                    $(".smgt_sub_chk").prop('checked', true);  

                }  

                else  

                {  

                    $(".smgt_sub_chk").prop('checked',false);  

                } 

            });

            $('.smgt_sub_chk').on('change',function()

            { 

                if(false == $(this).prop("checked"))

                { 

                    $(".select_all").prop('checked', false); 

                }

                if ($('.smgt_sub_chk:checked').length == $('.smgt_sub_chk').length )

                {

                    $(".select_all").prop('checked', true);

                }

            });

            $("#fees_reminder_single").on('click', function()

            {	

                alert(language_translate2.mail_reminder);

                return true;

            });

        });

    </script>



    <div class="panel-body">

        <div class="table-responsive">

            <form id="frm-example" name="frm-example" method="post">	

                <table id="recurring_fees_paymnt_list" class="display" cellspacing="0" width="100%">

                    <thead class="<?php echo MJ_smgt_datatable_heder() ?>">

                        <tr>
                            <th class="checkbox_width_10px text-end"><input type="checkbox" class="select_all" id="select_all"></th>
                            <th><?php esc_attr_e('Fees Title','school-mgt');?></th>  
                            <th><?php esc_attr_e('Recurring Type','school-mgt');?></th>  
                            <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                            <th><?php esc_attr_e('Class Name','school-mgt');?> </th>  
                            <th><?php esc_attr_e('Status','school-mgt'); ?></th>
                            <th><?php esc_attr_e('Total Amount','school-mgt');?></th>
                            <th><?php esc_attr_e('Start Date To End Date','school-mgt');?></th>
                            <th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php 

                        $i=0;			

                        foreach ($retrieve_class as $retrieved_data)
                        { 
                            if($i == 10)
                            {

                                $i=0;

                            }
                            if($i == 0)
                            {
                                $color_class='smgt_class_color0';
                            }
                            elseif($i == 1)

                            {

                                $color_class='smgt_class_color1';

                            }

                            elseif($i == 2)

                            {

                                $color_class='smgt_class_color2';

                            }

                            elseif($i == 3)

                            {

                                $color_class='smgt_class_color3';

                            }

                            elseif($i == 4)

                            {

                                $color_class='smgt_class_color4';

                            }

                            elseif($i == 5)

                            {

                                $color_class='smgt_class_color5';

                            }

                            elseif($i == 6)

                            {

                                $color_class='smgt_class_color6';

                            }

                            elseif($i == 7)

                            {

                                $color_class='smgt_class_color7';

                            }

                            elseif($i == 8)

                            {

                                $color_class='smgt_class_color8';

                            }

                            elseif($i == 9)

                            {

                                $color_class='smgt_class_color9';

                            }

                            ?>

                            <tr>

                                <td class="checkbox_width_10px"><input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->recurring_id ;?>"></td>
                                <td>

                                <?php
                                $fees_id=explode(',', $retrieved_data->fees_id);
                                $fees_type=array();
                                foreach($fees_id as $id)
                                { 
                                    $fees_type[] = mj_smgt_get_fees_term_name($id);
                                }
                                echo implode(" , " ,$fees_type);	
                                ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Fees Title','school-mgt');?>"></i></td>
                                <td>
                                    <?php 
                                    if($retrieved_data->recurring_type == "monthly")
                                    {
                                        esc_html_e('Monthly','school-mgt');
                                    }
                                    elseif($retrieved_data->recurring_type == "weekly")
                                    {
                                        esc_html_e('Weekly','school-mgt');
                                    }
                                    elseif($retrieved_data->recurring_type == "quarterly")
                                    {
                                        esc_html_e('Quarterly','school-mgt');
                                    }
                                    elseif($retrieved_data->recurring_type == "half_yearly")
                                    {
                                        esc_html_e('Half- Yearly','school-mgt');
                                    }
                                    else{
                                        esc_html_e('One Time','school-mgt');
                                    }
                                    ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Recurring Type','school-mgt');?>"></i></td>

                                <?php
                                $student_id_array=explode(',', $retrieved_data->student_id);
                                $student_data=array();
                                foreach($student_id_array as $student_id)
                                { 
                                    $student_data[] =mj_smgt_student_display_name_with_roll($student_id);
                                }
                                ?>
                                <td><?php echo implode(", " ,$student_data);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo implode(", " ,$student_data);?>"></i></td>

                                <td><?php if($retrieved_data->class_id == "0"){ esc_html_e('All Class','school-mgt');}else{ echo smgt_get_class_section_name_wise($retrieved_data->class_id,$retrieved_data->section_id);} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i></td>
                                <td><?php echo $retrieved_data->status;?></td>
                          
                                <td><?php echo MJ_smgt_currency_symbol_position_language_wise(number_format($retrieved_data->total_amount,2,'.','')); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Amount','school-mgt');?>"></i></td>

                            
                                <td><?php echo $retrieved_data->start_year.' '.esc_html__('To','school-mgt').' '.$retrieved_data->end_year;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Start Date To End Date','school-mgt');?>"></i></td>

                                <td class="action">  

                                    <div class="smgt-user-dropdown">

                                        <ul class="" style="margin-bottom: 0px !important;">

                                            <li class="">

                                                <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">

                                                    <img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >

                                                </a>

                                                <ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">

                                                    <?php

                                                    if($user_access_edit == '1')

                                                    {

                                                        ?>

                                                        <li class="float_left_width_100 border_bottom_menu">

                                                            <a href="?page=smgt_fees_payment&tab=addrecurringpayment&action=edit&recurring_fees_id=<?php echo $retrieved_data->recurring_id ;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>

                                                        </li>

                                                        <?php 

                                                    } 

                                                    if($user_access_delete =='1')

                                                    {

                                                        ?>

                                                        <li class="float_left_width_100 ">

                                                            <a href="?page=smgt_fees_payment&tab=feespaymentlist&action=delete&recurring_fees_id=<?php echo $retrieved_data->recurring_id ;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">

                                                            <i class="fa fa-trash"></i> <?php esc_html_e( 'Delete', 'school-mgt' ) ;?> </a>

                                                        </li>

                                                        <?php

                                                    }

                                                    ?>

                                                </ul>

                                            </li>

                                        </ul>

                                    </div>	

                                </td>

                            </tr>

                            <?php 

                            $i++;

                        } 

                        ?>     

                    </tbody>       

                </table>

                <div class="print-button pull-left">

                    <button class="btn-sms-color button_reload">

                        <input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">

                        <label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>

                    </button>

                    <?php if($user_access_delete =='1')

                    { 

                        ?>

                        <button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected_recurring_feelist" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>

                        <?php

                    }
                    ?>
                </div>

            </form>

        </div>

    </div>

    <?php 

}

else

{

    if($user_access_add=='1')

    {

        ?>

        <div class="no_data_list_div no_data_img_mt_30px"> 

            <a href="<?php echo admin_url().'admin.php?page=smgt_fees_payment&tab=addpaymentfee';?>">

                <img class="col-md-12 width_100px" src="<?php echo get_option( 'smgt_no_data_img' ) ?>" >

            </a>

            <div class="col-md-12 dashboard_btn margin_top_20px">

                <label class="no_data_list_label"><?php esc_html_e('Tap on above icon to add your first Record.','school-mgt'); ?> </label>

            </div> 

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

    }

}
?>