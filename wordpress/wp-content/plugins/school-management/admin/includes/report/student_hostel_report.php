<div class="panel-body clearfix margin_top_20px padding_top_15px_res">
	<div class="panel-body clearfix">
        <?php
        $class_id="";
        $class_section="";
        $gender='';
        ?>
        <form method="post" id="student_attendance">  
            <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
            <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
            <input type="hidden" name="id" value="<?php echo $hostel_id;?>" />
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-3 mb-3 input">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
                        <select name="class_id"  id="class_list" class="line_height_30px form-control validate[required]">
                            <!-- <option value="all class"><?php esc_attr_e('All Class','school-mgt');?></option> -->
                            <?php 
							$class_id="";
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
                    <div class="col-md-3 mb-3 input">
                        <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>			
                        <?php 
                        
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
                    <div class="col-md-3 input">
                        <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Hostel','school-mgt');?></label>
                        <?php
                        $tablename="smgt_hostel";
                        $retrieve_hostel = mj_smgt_get_all_data($tablename);
                        $id="";
                        if(isset($_REQUEST['id'])){
                                    $id=$_REQUEST['id'];
                        } ?>
                        <select name="id" class="line_height_30px form-control">
                            <option value=""><?php esc_attr_e('Select Hostel','school-mgt');?></option>
                            <?php
                            foreach($retrieve_hostel as $retrieved_data)
                            {
                                ?>
                                <option value="<?php echo $retrieved_data->id;?>" <?php selected($retrieved_data->id,$id)?>><?php echo $retrieved_data->hostel_name;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                  
                    <div class="col-md-3 mb-2">
                        <input type="submit" name="hostel_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                    </div>
                </div>
            </div>
        </form> 
	</div>	
    <?php
    //-------------- STUDENT REPORT -DATA ---------------//
    //die;


    $class_id = "";
    $class_section = ""; 
    $hostel_id = "";
    if(isset($_REQUEST['hostel_report']))
    {
       // var_dump($_REQUEST);
        $class_id = $_POST['class_id'];
        $class_section = $_POST['class_section'];
        $hostel_id = $_POST['id'];
    }
 
    if(!empty($hostel_id))
    {
        $hostel_data=mj_smgt_get_assign_beds_by_hostel_id($hostel_id);
    }
    else
    {
        $hostel_data=mj_smgt_get_all_assign_beds();
    }
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            "use strict";
            var table = jQuery('#student_report').DataTable({
                //stateSave: true,
                "responsive": true,
                "order": [[ 1, "Desc" ]],
                "dom": 'lifrtp',
                "buttons": [
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Hostel Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Student Hostel Report","school-mgt"); ?>',
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
                    {"bSortable": true}],
                language:<?php echo mj_smgt_datatable_multi_language();?>
                });
            $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
            $('.btn-place').html(table.buttons().container()); 
        });
    </script>
    <div class="panel-body margin_top_20px padding_top_15px_res">
        <?php
        if(!empty($hostel_data))
        {
            ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                    <h4 class="report_heder"><?php esc_html_e('Student Hostel Report','school-mgt');?></h4>
                </div>
            </div>
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
                                <th><?php esc_attr_e('Parents Phone','school-mgt');?></th>
                                <th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Room Number / Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Room Type','school-mgt');?></th>
                                <th><?php esc_attr_e('Bed Charge','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($hostel_data))
                            {
                                foreach ($hostel_data as $retrieved_data)
                                { 
                                    $student_id =$retrieved_data->student_id;
                                    $student_class_id =get_user_meta($student_id ,'class_name', true);
                                    $student_class_section =get_user_meta($student_id ,'class_section', true);
                            
                                    if(!empty($class_id) && !empty($class_section))
                                    {
                                        if($student_class_id == $class_id && $student_class_section == $class_section)
                                        {
                                            $student_data=get_userdata($student_id);
                                        }
                                        else
                                        {
                                            $student_data="";
                                           
                                        }
                                    }
                                    elseif(!empty($class_id ))
                                    {
                                        if($student_class_id == $class_id)
                                        {
                                            $student_data=get_userdata($student_id);
                                        }
                                        else
                                        {
                                            $student_data=""; 
                                        }
                                    
                                    }
                                    else
                                    {
                                        $student_data=get_userdata($student_id);
                                        
                                    }
                                    if(!empty($student_data))
                                    {
                                        
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $class_name = mj_smgt_get_class_name($student_data->class_name); 
                                                echo $class_name;
                                                if(!empty($student_data->class_section))
                                                {
                                                    echo " (". mj_smgt_get_section_name($student_data->class_section).")"; 
                                                }
                                                ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class Name','school-mgt');?>"></i>
                                            </td>

                                            <td>
                                                <?php 
                                                    $admission = get_user_meta($student_data->ID, 'admission_no',true);
                                                    if(!empty($admission)){
                                                        echo  $admission;
                                                    }
                                                    
                                                ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission No','school-mgt');?>"></i>
                                            </td>
                                            <td>
                                                <?php echo mj_smgt_student_display_name_with_roll($student_data->ID); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
                                            </td>
                                            <td>
                                                <?php echo $student_data->mobile_number;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Mobile Number','school-mgt');?>"></i>
                                            </td>
                                            <td>
                                                <?php if($student_data->father_mobile){echo $student_data->father_mobile;}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Parents Phone','school-mgt');?>"></i>
                                            </td>
                                            <td>
                                                <?php if(!empty($retrieved_data->hostel_id)){ echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id); } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Hostel Name','school-mgt');?>"></i>
                                            </td>
                                            <td>
                                                <?php if(!empty($retrieved_data->room_id)){ echo mj_smgt_get_room_unique_id_by_room_id($retrieved_data->room_id);}?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Room Name','school-mgt');?>"></i>
                                            </td>
                                            
                                            <td>
                                                <?php echo get_the_title(mj_smgt_get_room_type_by_room_id($retrieved_data->room_id));?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Room Type','school-mgt');?>"></i>
                                            </td>
                                            <td> 
                                                <?php 
                                                if(mj_smgt_get_bed_charge_by_id($retrieved_data->bed_id)){ echo mj_smgt_get_currency_symbol().''.number_format(mj_smgt_get_bed_charge_by_id($retrieved_data->bed_id),2,'.',''); } 
                                                ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Bed Charge','school-mgt');?>"></i>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
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
        }
        ?>
	</div>
</div>	