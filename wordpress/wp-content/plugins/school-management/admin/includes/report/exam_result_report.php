
    <!-- penal body div  -->
    <div class="panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
        <form method="post" id="fee_payment_report">  <!-- form Start  -->
            <div class="form-body user_form">
                <div class="row">
                    <div class="col-md-6 input">
                        <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
                        <select name="class_id"  id="class_list" class="line_height_30px form-control class_id_exam validate[required]">
                            <?php $class_id="";
                            if(isset($_REQUEST['class_id'])){
                                $class_id=$_REQUEST['class_id'];
                                }
                                ?>
                            <option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
                            <?php
                            foreach(mj_smgt_get_allclass() as $classdata)
                            {
                                ?>
                                <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
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
                                {  
                                    ?>
                                    <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
                                    <?php 
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-6 input">
                        <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
                        <?php
                        $tablename="exam";
                        $retrieve_class = mj_smgt_get_all_data($tablename);?>
                        <?php
                        $exam_id="";
                        if(isset($_REQUEST['exam_id'])){
                                    $exam_id=$_REQUEST['exam_id'];
                        } ?>
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
                        <input type="submit" name="report_5" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info save_btn"/>
                    </div>
                </div>
            </div>
        </form> <!-- form end  -->
    </div>
    <div class="clearfix"> </div>
    <!-- penal body div end  -->
    <div class="clearfix panel-body margin_top_20px rtl_margin_0px padding_top_15px_res">
        <?php 
        if(isset($_POST['report_5']))
        { 
            $exam_id=$_REQUEST['exam_id'];
            $class_id=$_REQUEST['class_id'];
            if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
                $subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id'],$_REQUEST['class_section']);
                $exlude_id = mj_smgt_approve_student_list();
                $student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
                                'meta_query'=> array(array('key' => 'class_name','value' =>$_REQUEST['class_id'],'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
            }
            else
            { 
                $subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id']);
                $exlude_id = mj_smgt_approve_student_list();
                $student = get_users(array('meta_key' => 'class_name', 'meta_value' => $_REQUEST['class_id'],'role'=>'student','exclude'=>$exlude_id));
            }
            if(!empty($student))
            {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($)
                    {
                        var table = jQuery('#example5').DataTable({
                            //stateSave: true,
                            "responsive": true,
                            "order": [[ 1, "Desc" ]],
                            "dom": 'lifrtp',
                            "buttons": [
                                {
                                extend: 'csv',
                                text:'<?php echo esc_attr_e("csv","school-mgt"); ?>',
                                title: '<?php echo esc_attr_e("Exam Result Report","school-mgt"); ?>',
                            },
                            {
                                extend: 'print',
                                text:'<?php echo esc_attr_e("Print","school-mgt"); ?>',
                                title: '<?php echo esc_attr_e("Exam Result Report","school-mgt"); ?>',
                            },
                            ],
                            "aoColumns":[
                                {"bSortable": true},
                                {"bSortable": true},
                                <?php 
                                    if(!empty($subject_list))
                                    {			
                                        foreach($subject_list as $sub_id)
                                        {
                                            ?>
                                            {"bSortable": true},
                                            <?php
                                        }
                                    } 
                                ?>
                                {"bSortable": true},
								{"bSortable": true}
                            ],
                            language:<?php echo mj_smgt_datatable_multi_language();?>
                        });
                        $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
                        $('.btn-place').html(table.buttons().container()); 
                    });
                </script>
                <div class="table-responsive">
                    <div class="btn-place"></div>
                    <table id="example5" class="display" cellspacing="0" width="100%">
                        <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                            <tr>
                                <th><?php esc_attr_e('Roll No.','school-mgt');?></th>  
                                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                                <?php 
                                    if(!empty($subject_list))
                                    {			
                                        foreach($subject_list as $sub_id)
                                        {
                                            
                                            echo "<th> ".$sub_id->sub_name." </th>";
                                        }
                                    } 
                                ?>
                                <th><?php esc_attr_e('Total','school-mgt');?></th>
								<th><?php esc_attr_e('Result Status','school-mgt');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($student))
                            {
                                $i=0;
                                foreach ($student as $user)
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
                                    $total=0;
                                    ?>
                                    <tr>
                                        <td><?php echo $user->roll_id;?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Roll No.','school-mgt');?>"></i></td>
                                        <td><?php echo mj_smgt_get_user_name_byid($user->ID);?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Student Name','school-mgt');?>"></i></td>
                                        <?php 
                                        if(!empty($subject_list))
                                        {		
											$result=array();
											$result1=array();
                                            foreach($subject_list as $sub_id)
                                            {
                                                $mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$sub_id->subid,$user->ID);
												$marks_from = $obj_marks->mj_smgt_get_pass_marks($exam_id);
                                                if($mark_detail)
                                                {
                                                    $mark_id=$mark_detail->mark_id;
                                                    $marks=$mark_detail->marks;
                                                    $total+=$marks;
                                                }
                                                else
                                                {
                                                    $marks=0;
                                                    $attendance=0;
                                                    $marks_comment="";
                                                    $total+=0;
                                                    $mark_id="0";
                                                }
												
												if($mark_detail >= $marks_from)
												{
													$result[] = "pass";
												}
												else 
												{
													$result1[] = "fail";
												}
                                                ?>
                                                    <td><?php echo $marks; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php echo $sub_id->sub_name; ?> <?php esc_html_e('Mark','school-mgt'); ?>"></i></td>
                                                <?php
                                            }
                                            ?>
                                                <td><?php echo $total; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Marks','school-mgt');?>"></i></td>
												
												<td><?php 
													if(isset($result) && in_array("pass", $result) && isset($result1) && in_array("fail", $result1))
													{
														echo  esc_attr_e('Fail','school-mgt');
													}
													elseif(isset($result) && in_array("pass", $result))
													{
														echo  esc_attr_e('Pass','school-mgt');
													}
													elseif(isset($result1) && in_array("fail", $result1))
													{
														echo  esc_attr_e('Fail','school-mgt');
													}
													else 
													{
														echo "-";
													}
												?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Result Status','school-mgt');?>"></i></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td><?php echo $total; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" title="<?php esc_html_e('Total Marks','school-mgt');?>"></i></td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php 
                                    $i++;
                                }
                            }
                            
                            ?>
                        </tbody>
                    </table>
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
            <!-- end panel body div -->
            <?php
        }
        ?>
    </div>