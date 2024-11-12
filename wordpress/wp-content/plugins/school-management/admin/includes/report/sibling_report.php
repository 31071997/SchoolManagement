<div class="panel-body clearfix margin_top_20px rtl_margin_0px padding_top_15px_res">
	<div class="panel-body clearfix">
    <?php
    $class_id = "";
    $class_section = ""; 
    $gender='';
    ?>
    <form method="post" id="student_attendance">  
            <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
            <input type="hidden" name="class_section" value="<?php echo $class_section;?>" />
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-5 mb-3 input">
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
                    <div class="col-md-5 mb-3 input">
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
                    <div class="col-md-2 mb-2">
                        <input type="submit" name="sibling_report" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                    </div>
                </div>
            </div>
        </form> 
	</div>	
    <?php
//-------------- STUDENT REPORT -DATA ---------------//
    $class_id = "";
    $class_section = ""; 
    if(isset($_REQUEST['sibling_report']))
    {
        $class_id = $_POST['class_id'];
        $class_section = $_POST['class_section'];
    }

    if($class_id==""  && $class_section=="")
    {
        $studentdata = get_users(array('role'=>'student'));
    }
    elseif($class_section =="")
    {
        $studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
    }
    else
    {
        $studentdata = 	get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id)),'role'=>'student'));
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
                buttons:[
                    {
                        extend: 'csv',
                        text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Sibling Report","school-mgt"); ?>',
                    },
                    {
                        extend: 'print',
                        text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                        title: '<?php echo esc_attr_e("Sibling Report","school-mgt"); ?>',
                    },
                ],
                "aoColumns":[                 
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
    <div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
        <?php
        if(!empty($studentdata))
        {
            ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                    <h4 class="report_heder"><?php esc_html_e('Sibling Report','school-mgt');?></h4>
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
                                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Siblings Name','school-mgt');?></th>
                                <th><?php esc_attr_e('Class','school-mgt');?></th>
                                <th><?php esc_attr_e('Admission Date','school-mgt');?></th>
                                <th><?php esc_attr_e('Gender','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($studentdata as $retrieved_data)
                            { 
                                $student_data=get_userdata($retrieved_data->ID);
                                $subling_data =get_user_meta($retrieved_data->ID, 'sibling_information', true);
                                ?>
                                <tr>
                                    <td>  
                                        <?php 
                                            echo mj_smgt_student_display_name_with_roll($student_data->ID); 
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i>
                                    </td>

                                    <td>  
                                        <?php
                                        if(!empty($subling_data)){
                                            $subling = json_decode($subling_data);
                                            if(!empty($subling))
                                            {
                                                foreach($subling as $parents_data)
                                                {
                                                    
                                                    if(isset($parents_data->siblingsstudent))
                                                    { 
                                                        
                                                        $subling_first_name =get_user_meta($parents_data->siblingsstudent, 'first_name', true);
                                                        $subling_last_name =get_user_meta($parents_data->siblingsstudent, 'last_name', true);
                                                        echo $subling_first_name.' '.$subling_last_name;
                                                    }else{
                                                        echo $parents_data->siblingsname; '<br>';
                                                    }
                                                }
                                            }
                                        }
                                       
                                        ?>  <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Sibling Name','school-mgt');?>"></i>
                                    </td>

                                    <td>
                                        <?php
                                            $class_name = smgt_get_class_section_name_wise($student_data->class_name,$student_data->class_section); 
                                            echo $class_name;
                                        ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Class','school-mgt');?>"></i>
                                    </td>
                                    
                                    <td>
                                        <?php if($student_data->admission_date){echo $student_data->admission_date;} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Admission Date','school-mgt');?>"></i>
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