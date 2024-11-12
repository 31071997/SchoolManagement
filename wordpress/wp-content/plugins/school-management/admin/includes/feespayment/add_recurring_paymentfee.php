<?php 	
if($active_tab == 'addrecurringpayment')
{
    $recurring_fees_id=0;
    if(isset($_REQUEST['recurring_fees_id']))
        $recurring_fees_id=$_REQUEST['recurring_fees_id'];
    $edit=0;
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
    {
        $edit=1;
        $result = $obj_feespayment->mj_smgt_get_single_recurring_fees($recurring_fees_id);
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
            <input type="hidden" name="recurring_fees_id" value="<?php echo $recurring_fees_id;?>">
            <input type="hidden" name="last_recurrence_date" value="<?php echo $result->recurring_enddate;?>">
            <div class="form-body user_form">
				<div class="row">
                    <?php 
                    if($edit)
                    {
                    ?>

                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox">
                        <div class="form-group">

                            <div class="col-md-12 form-control">

                                <div class="row padding_radio">

                                    <div class="input-group">

                                        <label class="custom-top-label" for="classis_limit"><?php esc_html_e('Recurrence Type','school-mgt');?></label>

                                        <div class="d-inline-block gender_line_height_24px">

                                            <?php $recurrence_type = "one_time"; if($edit){ $recurrence_type=$result->recurring_type; }elseif(isset($_POST['recurrence_type'])) {$recurrence_type=sanitize_text_field($_POST['recurrence_type']);}?>

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
                    if($edit)
                    {
                    ?>
                        <div class="col-md-6 input">
                            <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class','school-mgt');?><span class="require-field">*</span></label>
                            <?php 
							if($edit){ $classval=$result->class_id; }else{$classval='';}
							
							?>
                            <select name="class_id" id="fees_class_list_id" class="form-control validate[required] load_fees_drop max_width_100">
                                    <option value="<?php echo $classval;?>"
                                      <?php selected($classval, $classval);  ?>><?php echo mj_smgt_get_class_name_by_id($classval);?>
                            </select>                         
                        </div>
                        <div class="col-md-6 input class_section_hide">
                            <label class="ml-1 custom-top-label top" for="hmgt_contry"><?php esc_html_e('Class Section','school-mgt');?></label>
                            <?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                            <select name="class_section" class="form-control max_width_100" id="fees_class_section_id">
                                <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
                                <?php
                                        if($edit)
										{
                                            foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
                                            {  ?>
                                <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>>
                                    <?php echo $sectiondata->section_name;?></option>
                                <?php } 
                                        }?>
                            </select>                      
                        </div>
                                    
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
                            <div id="messahe_test"></div>
                            <div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
                                <span class="user_display_block" id="user_display_block">
                                    <select name="selected_users[]" id="selected_users" class="form-control min_width_250px validate[required]" multiple="multiple">					
                                    <?php
                                        $student_list =  get_student_by_class_id_and_section($result->class_id,$result->section_id);
                                        if(!empty($student_list)){
                                            $student_data = explode(',',$result->student_id);
                                            foreach($student_list  as $student_id)
                                            {
                                                
                                                $selected = "";
                                                if(in_array($student_id->ID,$student_data))
                                                {
                                                    $selected = "selected";
                                                }
                                               ?>
                                               <option value="<?php echo $student_id->ID; ?>" <?php echo $selected; ?>><?php echo mj_smgt_student_display_name_with_roll($student_id->ID);?></option>
                                               <?php
                                                
                                            }
                                        }
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
                                $fees_data = mj_smgt_get_fees_by_class_id($result->class_id);
                                if(!empty($fees_data))
                                {
                                    $fees_id=explode(',',$result->fees_id);
                                    foreach($fees_data as $id)
                                    {
                                        if(mj_smgt_get_fees_term_name($id->fees_id) !== " ")
                                        {
                                            $selected = "";
                                            if(in_array($id->fees_id,$fees_id))
                                            {
                                                $selected = "selected";
                                            }
                                        ?>
                                        <option value="<?php echo $id->fees_id;?>" <?php echo $selected; ?>><?php echo mj_smgt_get_fees_term_name($id->fees_id);?></option>
                                        <?php
                                    }
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
                   <div class="rtl_margin_top_15px col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3 multiselect_validation_member smgt_multiple_select rtl_margin_bottom_0px">
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
						<div class="form-group input">
							<div class="col-md-12 note_border margin_bottom_15px_res">
								<div class="form-field">
                                    <textarea name="description" class="textarea_height_47px form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
									<span class="txt-title-label"></span>
									<label class="text-area address active"><?php esc_attr_e('Description','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>

                   <div class="col-md-6 input" style="margin:0px;">
                        <div class="form-group input rtl_margin_0px">
                            <div class="col-md-12 form-control ">
                                <input id="start_date_event" class="form-control date_picker validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_year" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->start_year)));}elseif(isset($_POST['start_year'])) echo mj_smgt_getdate_in_input_box($_POST['start_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
                                <label class="active date_label" for="start"><?php esc_html_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 input" style="margin:0px;">
                        <div class="form-group input rtl_margin_0px">
                            <div class="col-md-12 form-control">
                                <input id="end_date_event" class="form-control date_picker validate[required] start_date datepicker2" type="text"  name="end_year" autocomplete="off" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box(date("Y-m-d",strtotime($result->end_year)));}elseif(isset($_POST['end_year'])) echo mj_smgt_getdate_in_input_box($_POST['end_year']); else echo mj_smgt_getdate_in_input_box(date("Y-m-d"));?>">
                                <label class="date_label" for="end"><?php esc_html_e('End Date','school-mgt');?><span class="require-field">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 recurring_option_checkbox margin_15px_rtl" style="margin:0px;">
                        <div class="form-group">

                            <div class="col-md-12 form-control">

                                <div class="row padding_radio">

                                    <div class="input-group">

                                        <label class="custom-top-label" for="classis_limit"><?php esc_html_e('Status','school-mgt');?></label>

                                        <div class="d-inline-block gender_line_height_24px">

                                            <?php $status = "no"; if($edit){ $recurrence_type=$result->status; }elseif(isset($_POST['status'])) {$recurrence_type=sanitize_text_field($_POST['status']);}?>

                                            
                                            <label class="radio-inline">

                                                <input type="radio" value="yes" class="recurrence_type validate[required]" name="status" <?php checked('yes',esc_html($recurrence_type)); ?>/><?php esc_html_e('Yes','school-mgt');?>

                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" value="no" class="recurrence_type validate[required]" name="status" <?php checked('no',esc_html($recurrence_type)); ?>/><?php esc_html_e('No','school-mgt');?> 

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
                        <input type="submit" value="<?php if($edit){ esc_attr_e('Save Recurring Invoice','school-mgt'); }else{ esc_attr_e('Create Invoice','school-mgt');}?>" name="save_recurring_feetype_payment" onclick="return confirm('<?php esc_attr_e('Are you sure you want to edit this record? This data change in next recurring invoice details.','school-mgt');?>');" class="btn btn-success save_btn" />
                    </div>
                </div>
            </div>
        </form>
    </div><!----- penal Body --------->
    <?php  
} 
?>