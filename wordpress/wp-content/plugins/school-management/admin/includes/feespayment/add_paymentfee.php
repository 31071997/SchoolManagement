<?php 	
if($active_tab == 'addpaymentfee')
{
    $fees_pay_id=0;
    if(isset($_REQUEST['fees_pay_id']))
        $fees_pay_id=$_REQUEST['fees_pay_id'];
    $edit=0;
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
    {
        $edit=1;
        $result = $obj_feespayment->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);
        
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($)
	{
        $('#selected_users').multiselect({ 
			nonSelectedText :"<?php esc_attr_e('Select Users','school-mgt');?>",
			includeSelectAllOption: true,
			selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		});
        $('.tax_charge').multiselect(
        {
            nonSelectedText: '<?php esc_html_e('Select Tax','school-mgt');?>',
            includeSelectAllOption: true,
            allSelectedText: '<?php esc_html_e('All Selected','school-mgt'); ?>',
            selectAllText: '<?php esc_html_e('Select all','school-mgt'); ?>',
            templates: {
                button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
            },
            buttonContainer: '<div class="dropdown" />'
        });

        var start = new Date();
		var end = new Date(new Date().setYear(start.getFullYear()+1));
		$("#start_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() + 0);
				$("#end_date_event").datepicker("option", "minDate", dt);
			},
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
	    });
	    $("#end_date_event").datepicker(
		{
			dateFormat: "<?php echo get_option('smgt_datepicker_format');?>",
			minDate:0,
			changeMonth: true,
			changeYear: true,
	       onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 0);
				$("#start_date_event").datepicker("option", "maxDate", dt);
			},
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
	    });
        
    });
    </script>
    <div class="panel-body margin_top_20px padding_top_15px_res"><!----- penal Body --------->
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
            <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
            <input type="hidden" name="action" value="<?php echo $action;?>">
            <input type="hidden" name="fees_pay_id" value="<?php echo $fees_pay_id;?>">
            <input type="hidden" name="invoice_type" value="expense">
            <input type="hidden" name="recurrence_type" value="one_time">
            <div class="form-body user_form">
				<div class="row">
                <?php 
                if(!$edit)
                {
                $recurring_option=get_option('smgt_enable_recurring_invoices');
                if($recurring_option == 'yes')
                {
                ?>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox">
                    <div class="form-group">

                        <div class="col-md-12 form-control">

                            <div class="row padding_radio">

                                <div class="input-group">

                                    <label class="custom-top-label" for="classis_limit"><?php esc_html_e('Recurrence Type','school-mgt');?></label>

                                    <div class="d-inline-block gender_line_height_24px">

                                        <?php $recurrence_type = "one_time"; if($edit){ $recurrence_type=$result->recurrence_type; }elseif(isset($_POST['recurrence_type'])) {$recurrence_type=sanitize_text_field($_POST['recurrence_type']);}?>

                                        <label class="radio-inline">

                                            <input type="radio" value="one_time" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('one_time',esc_html($recurrence_type)); ?>/><?php esc_html_e('One Time','school-mgt');?>

                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" value="weekly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('weekly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Weekly','school-mgt');?> 

                                        </label>
                                        
                                        <label class="radio-inline">

                                            <input type="radio" value="monthly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('monthly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Monthly','school-mgt');?>

                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" value="quarterly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('quarterly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Quarterly','school-mgt');?> 

                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="half_yearly" class="recurrence_type validate[required]" name="recurrence_type" <?php checked('half_yearly',esc_html($recurrence_type)); ?>/><?php esc_html_e('Half- Yearly','school-mgt');?> 

                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>		

                    </div>
				</div>
                <div class="col-md-6 input">
                </div>
                <?php
                }
                }
                
                    if($edit)
                    {
                    ?>
                        <div class="col-md-6 input">
                            <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
                            <?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                            <select name="class_id" id="class_list" class="form-control validate[required] load_fees_drop max_width_100">
                                <?php 
                                if($addparent)
                                { 
                                    $classdata=mj_smgt_get_class_by_id($student->class_name);
                                    ?>
                                    <option value="<?php echo $student->class_name;?>"><?php echo $classdata->class_name;?></option>
                                    <?php 
                                }?>
                                <option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                                <?php
                                foreach(mj_smgt_get_allclass() as $classdata)
                                { ?>
                                    <option value="<?php echo $classdata['class_id'];?>"
                                        <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?>
                                    </option>
                                    <?php 
                                }?>
                            </select>                         
                        </div>
                        <div class="col-md-6 input class_section_hide">
                            <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
                            <?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                            <select name="class_section" class="form-control max_width_100" id="class_section">
                                <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
                                <?php
                                        if($edit){
                                            foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
                                            {  ?>
                                <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>>
                                    <?php echo $sectiondata->section_name;?></option>
                                <?php } 
                                        }?>
                            </select>                      
                        </div>
                        <div class="col-md-6 input class_section_hide">
                            <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Student','school-mgt');?></label>
                            <?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                            <select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
                                <option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
                                <?php 
                                    if($edit)
                                    {
                                        echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_student_display_name_with_roll($result->student_id).'</option>';
                                    }
                                ?>
                            </select>                    
                        </div>
                        <?php
                    }
                    else{
                        ?>
                        <div id="smgt_select_class" class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input single_class_div rtl_margin_0px">
                            <label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
                            <select name="class_id"  id="fees_class_list_id" class="form-control load_fees min_width_100 validate[required]">
                                <option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                                <option value="all_class"><?php esc_attr_e('All Class','school-mgt');?></option>
                                <?php
                                foreach(mj_smgt_get_allclass() as $classdata)
                                {  
                                ?>
                                <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
                            <?php }?>
                            </select>
                        </div>
                            
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_section_id rtl_margin_0px">
                            <label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
                            <?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                            <select name="class_section" class="form-control min_width_100" id="fees_class_section_id">
                                <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
                                <?php
                                if($edit){
                                    foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
                                    {  ?>
                                    <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
                                <?php } 
                                }?>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
                            <div id="messahe_test"></div>
                            <div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
                                <span class="user_display_block">
                                    <select name="selected_users[]" id="selected_users" class="form-control min_width_250px validate[required]" multiple="multiple">					
                                        <?php 
                                        // $student_list = mj_smgt_get_all_student_list();
                                        // foreach($student_list  as $retrive_data)
                                        // {
                                        //     echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->display_name.'</option>';
                                        // }
                                        ?>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
            
                    <?php wp_nonce_field( 'save_payment_fees_admin_nonce' ); ?>
                   
                    <div class="col-md-6 padding_bottom_15px_res rtl_margin_top_15px">
                        <div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
                            <select name="fees_id[]" multiple="multiple" id="fees_data" class="form-control validate[required] max_width_100">
                                <?php 	
                                if($edit)
                                {
                                    $fees_id=explode(',',$result->fees_id);
                                    foreach($fees_id as $id)
                                    {
                                        if(mj_smgt_get_fees_term_name($id) !== " ")
                                                {
                                        echo '<option value="'.$id.'" '.selected($id,$id).'>'.mj_smgt_get_fees_term_name($id).'</option>';
                                    }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group input">
							<div class="col-md-12 form-control">
                                <input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="text" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) { echo $_POST['fees_amount']; }else{ echo "0"; }  ?>" name="fees_amount" readonly>
								<label for="userinput1" class=""><?php esc_html_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="required">*</span></label>
							</div>
						</div>
					</div>
                    <div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 multiselect_validation_member smgt_multiple_select">
                        <select class="form-control tax_charge" id="tax_id" name="tax[]" multiple="multiple">
                            <?php
                                if($edit)
                                {
                                    if ($result->tax !== null) {
                                        $tax_id = explode(',', $result->tax);
                                    } else {
                                        $tax_id[]='';
                                    }
                                }
                                else
                                {
                                    $tax_id[]='';
                                }
                                $obj_tax=new tax_Manage();
                                $smgt_taxs=$obj_tax->MJ_smgt_get_all_tax();
                                if(!empty($smgt_taxs))
                                {
                                    foreach($smgt_taxs as $data)
                                    {
                                        $selected = "";
                                        if(in_array($data->tax_id,$tax_id))
                                            $selected = "selected";

                                        ?>
                                        <option value="<?php echo esc_attr($data->tax_id); ?>" <?php echo esc_html($selected); ?>>
                                        <?php echo esc_html($data->tax_title);?> - <?php echo esc_html($data->tax_value);?></option>
                                    <?php
                                    }

                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 note_text_notice">
						<div class="form-group input rtl_margin_0px">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
                                    <textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-md-6 input rtl_margin_0px" style="margin:0px;">
                        <div class="form-group input rtl_margin_0px">
                            <div class="col-md-12 form-control">
                                <input id="start_date_event" class="form-control date_picker validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_year" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_year)));}elseif(isset($_POST['start_year'])) echo mj_smgt_getdate_in_input_box($_POST['start_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
                                <label class="active date_label" for="start"><?php esc_html_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 input rtl_margin_0px" style="margin:0px;">
                        <div class="form-group input">
                            <div class="col-md-12 form-control">
                                <input id="end_date_event" class="form-control date_picker validate[required] start_date datepicker2" type="text"  name="end_year" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_year)));}elseif(isset($_POST['end_year'])) echo mj_smgt_getdate_in_input_box($_POST['end_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
                                <label class="date_label" for="end"><?php esc_html_e('End Date','school-mgt');?><span class="require-field">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 padding_bottom_15px_res rtl_margin_top_15px margin_top_15px">
                        <div class="form-group">
                            <div class="col-md-12 form-control input_height_50px">
                                <div class="row padding_radio">
                                    <div class="input-group input_checkbox">
                                        <label class="custom-top-label label_right_position"><?php esc_html_e('Send Email To Students & Parents','school-mgt');?></label>													
                                        <div class="checkbox checkbox_lebal_padding_8px">
                                            <label>
                                            <input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_mail" value="1" <?php echo checked(get_option('smgt_enable_feesalert_mail'),'yes');?> />&nbsp;&nbsp;<?php esc_html_e('Enable','school-mgt');?>
                                            </label>
                                        </div>
                                    </div>
                                </div>												
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 rtl_margin_top_15px margin_top_15px mb-3">
                        <div class="form-group">
                            <div class="col-md-12 form-control input_height_50px">
                                <div class="row padding_radio">
                                    <div class="input-group input_checkbox">
                                        <label class="custom-top-label label_right_position"><?php esc_html_e('Send SMS To Student','school-mgt');?></label>													
                                        <div class="checkbox checkbox_lebal_padding_8px">
                                            <label>
                                                <input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_sms_student"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_sms'),'yes');?>/>&nbsp;&nbsp;<?php esc_html_e('Enable','school-mgt');?>
                                            </label>
                                        </div>
                                    </div>
                                </div>												
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 rtl_margin_top_15px margin_top_15px">
                        <div class="form-group">
                            <div class="col-md-12 form-control input_height_50px">
                                <div class="row padding_radio">
                                    <div class="input-group input_checkbox">
                                        <label class="custom-top-label label_right_position"><?php esc_html_e('Send SMS To Parents','school-mgt');?></label>													
                                        <div class="checkbox checkbox_lebal_padding_8px">
                                            <label>
                                                <input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_sms_parent"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_sms'),'yes');?>/>&nbsp;&nbsp;<?php esc_html_e('Enable','school-mgt');?>
                                            </label>
                                        </div>
                                    </div>
                                </div>												
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body user_form padding_top_15px_res">
				<div class="row">
                    <div class="col-sm-6">
                        <input type="submit" value="<?php if($edit){ esc_attr_e('Save Invoice','school-mgt'); }else{ esc_attr_e('Create Invoice','school-mgt');}?>" name="save_feetype_payment" class="btn btn-success rtl_margin_0px save_btn" />
                    </div>
                </div>
            </div>
        </form>
    </div><!----- penal Body --------->
    <?php  
} 
?>