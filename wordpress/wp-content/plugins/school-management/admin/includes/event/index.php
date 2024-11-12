<?php
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
$obj_event = new event_Manage(); 
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('event');
	$user_access_add=$user_access['add'];
	$user_access_edit=$user_access['edit'];
	$user_access_delete=$user_access['delete'];
	$user_access_view=$user_access['view'];
	
	if (isset ( $_REQUEST ['page'] ))
	{	
		if($user_access_view=='0')
		{	
			mj_smgt_access_right_page_not_access_message_admin_side();
			die;
		}
		if(!empty($_REQUEST['action']))
		{
			if ('event' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('event' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('event' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
			{
				if($user_access_add=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			} 
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'eventlist';

//------------------ SAVE EVENT --------------------//
if(isset($_POST['save_event']))	
{
    $nonce = $_POST['_wpnonce'];
    if (wp_verify_nonce( $nonce, 'save_event_nonce' ) )
    {
        if($_FILES['upload_file']['name'] != "" && $_FILES['upload_file']['size'] > 0)	
        {
            if($_FILES['upload_file']['size'] > 0)
            {
                $file_name=mj_smgt_load_documets_new($_FILES['upload_file'],$_FILES['upload_file'],$_POST['upload_file']);	
            }
          
        }
        else
        {
            if(isset($_REQUEST['hidden_upload_file']))
            {
                $file_name=$_REQUEST['hidden_upload_file'];
            }
        }
     
        if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit')//EDIT NOTICE
        {
            $result=$obj_event->mj_smgt_insert_event($_POST,$file_name);
            if($result)
            {
                wp_redirect ( admin_url().'admin.php?page=smgt_event&tab=eventlist&message=2');
            }
        }
        else
        {
            $start_am_pm='';
            $end_am_pm='';
            
            $start_date = $_POST['start_date'];
            
            $end_date = $_POST['end_date'];
          
            $start_time_1 = $_POST['start_time'];

            $end_time_1 = $_POST['end_time'];

            $start_time_data = explode(":", $start_time_1);

            $start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);

            $start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);

            $start_am_pm=$start_time_data[2];

            $start_time_new=$start_hour.':'.$start_min.' '.$start_am_pm;
            
            $start_time_in_24_hour_format  = date("H:i", strtotime($start_time_new));

            $end_time_data = explode(":", $end_time_1);

            $end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);

            $end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);

            $end_am_pm=$end_time_data[2];

            $end_time_new=$end_hour.':'.$end_min.' '.$end_am_pm;

            $end_time_in_24_hour_format  = date("H:i", strtotime($end_time_new));

            if($start_date == $end_date && $start_time_in_24_hour_format >= $end_time_in_24_hour_format)
            {
                wp_redirect ( admin_url().'admin.php?page=smgt_event&tab=eventlist&message=4');
            }
            else
            {
                $result=$obj_event->mj_smgt_insert_event($_POST,$file_name);
                if($result)
                {
                    wp_redirect ( admin_url().'admin.php?page=smgt_event&tab=eventlist&message=1');
                }
            }
        }
            
    }
}

//--------------- DELETE SINGLE EVENT ----------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
    $result=$obj_event->mj_smgt_delete_event($_REQUEST['event_id']);
    if($result){
        wp_redirect ( admin_url().'admin.php?page=smgt_event&tab=eventlist&message=3');
    }
}

//--------------- DELETE MULTIPLE EVENT -----------------//
if(isset($_REQUEST['delete_selected']))
{		
    if(!empty($_REQUEST['id']))
    {
        foreach($_REQUEST['id'] as $id)
        {
            $result=$obj_event->mj_smgt_delete_event($id);
            wp_redirect ( admin_url().'admin.php?page=smgt_event&tab=eventlist&message=3');
        }
    }
}

?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		//EVENT LIST
		"use strict";
		jQuery('#event_list').DataTable(
		{
            "initComplete": function(settings, json) {
                $(".print-button").css({"margin-top": "-5%"});
                $('#event_list th:first-child').removeClass('sorting_asc');
            },
            // stateSave: true,
			"responsive": true,
			dom: 'lifrtp',
			"aoColumns":[
				{"bSortable": false},
				{"bSortable": false},
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
	
		jQuery('.select_all').on('click', function(e)
		{
			 if($(this).is(':checked',true))  
			 {
				$(".sub_chk").prop('checked', true);  
			 }  
			 else  
			 {  
				$(".sub_chk").prop('checked',false);  
			 } 
		});
		$("body").on("change",".sub_chk",function(){
			if(false == $(this).prop("checked"))
			{ 
				$(".select_all").prop('checked', false); 
			}
			if ($('.sub_chk:checked').length == $('.sub_chk').length )
			{
				$(".select_all").prop('checked', true);
			}
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
				var alert_msg=confirm(language_translate2.delete_record_alert);
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
	});
</script>
<!-- View Popup Code -->	
<div class="popup-bg">
    <div class="overlay-content">   
    	<div class="notice_content"></div>  
        <div class="modal-content">
			<div class="view_popup"></div>     
		</div>  
    </div>     
</div>
<div class="page-inner"><!-- page-inner -->
	<div class="main_list_margin_15px"><!-- main_list_margin_15px -->
        <?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Event Inserted successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Event Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Event Deleted Successfully.','school-mgt');
				break;
            case '4':
                $message_string = esc_attr__('End time must be greater than start time.','school-mgt');
                break;
		}
       
        if($message)
		{ 
            ?>
			<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
			<?php 
		} 
        ?>
        <div class="row"><!-- row -->
			<div class="col-md-12 padding_0"><!-- col-md-12 -->
				<div class="smgt_main_listpage"><!-- smgt_main_listpage -->
                    <?php
                  	if($active_tab == 'eventlist')
                    {	
                       
                        $retrieve_event = $obj_event->MJ_smgt_get_all_event();
                        
                        if(!empty($retrieve_event))
                        {
                          
                            ?>
                            <div class="">
                                <div class="table-responsive"><!-------- Table Responsive --------->
                                    <!-------- Exam List Form --------->
                                    <form id="frm-example" name="frm-example" method="post">
                                        <table id="event_list" class="display" cellspacing="0" width="100%">
                                            <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                                                <tr>
                                                    <th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
                                                    <th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
                                                    <th><?php esc_attr_e('Event Title','school-mgt');?></th>
                                                    <th><?php esc_attr_e('Start Date','school-mgt');?></th>
                                                    <th><?php esc_attr_e('End Date','school-mgt');?></th>
                                                    <th><?php esc_attr_e('Start Time','school-mgt');?></th>
                                                    <th><?php esc_attr_e('End Time','school-mgt');?></th>
                                                    <th><?php esc_attr_e('Description','school-mgt');?></th>
                                                    <th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $i=0;
                                                foreach ($retrieve_event as $retrieved_data)
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
														<td class="checkbox_width_10px">
															<input type="checkbox" class="smgt_sub_chk sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->event_id;?>">
														</td>
														<td class="user_image width_50px profile_image_prescription padding_left_0">
                                                            <a href="#" class="view_details_popup" id="<?php echo $retrieved_data->event_id;?>" type="event_view" >
                                                                <p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
                                                                    <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/notice.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
                                                                </p>
                                                            </a>
														</td>
														<td>
                                                            <a href="#" class="view_details_popup" id="<?php echo $retrieved_data->event_id;?>" type="event_view" >
                                                                <?php echo $retrieved_data->event_title;?>
                                                            </a> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Event Title','school-mgt');?>" ></i>
														</td>
														<td>
															<?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Start Date','school-mgt');?>" ></i>
														</td>
                                                        <td>
															<?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('End Date','school-mgt');?>" ></i>
														</td>
                                                        <td>
															<?php echo MJ_smgt_timeremovecolonbefoream_pm($retrieved_data->start_time); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Start Time','school-mgt');?>" ></i>
														</td>
                                                        <td>
															<?php echo MJ_smgt_timeremovecolonbefoream_pm($retrieved_data->end_time); ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('End Time','school-mgt');?>" ></i>
														</td>
                                                        <td>
															<?php
                                                            if(!empty($retrieved_data->description))
                                                            {
                                                                $comment =$retrieved_data->description;
                                                                $grade_comment = strlen($comment) > 30 ? substr($comment,0,30)."..." : $comment;
                                                                echo $grade_comment;
                                                            }
                                                            else{
                                                                echo 'N/A';
                                                            }
                                                                 
                                                             ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->description)){ echo $retrieved_data->description; }else{ _e('Description','school-mgt');}?>" ></i>
														</td>
                                                        <td class="action">  
                                                            <div class="smgt-user-dropdown">
                                                                <ul class="" style="margin-bottom: 0px !important;">
                                                                    <?php
                                                                    if(!empty($retrieved_data->exam_syllabus))
                                                                    {
                                                                        $doc_data=json_decode($retrieved_data->exam_syllabus);
                                                                    }
                                                                    ?>
                                                                    <li class="">
                                                                        <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
                                                                        </a>
                                                                        <ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
                                                                            <li class="float_left_width_100 ">
                                                                                <a href="#" class="float_left_width_100 view_details_popup" id="<?php echo $retrieved_data->event_id;?>" type="event_view" ><i class="fa fa-eye" aria-hidden="true"></i><?php esc_attr_e('View','school-mgt');?></a>
                                                                            </li>
                                                                            <?php
                                                                            if(!empty($retrieved_data->event_doc))
                                                                            {
                                                                                ?>
                                                                                <li class="float_left_width_100">
                                                                                    <a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$retrieved_data->event_doc; ?>" class="status_read float_left_width_100" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-eye"></i><?php esc_html_e('View Document', 'school-mgt');?></a>
                                                                                </li>
                                                                                <?php
                                                                            }
                                                                            if($user_access_edit == '1')
                                                                            {
                                                                                ?>
                                                                                <li class="float_left_width_100 border_bottom_menu">
                                                                                    <a href="admin.php?page=smgt_event&tab=add_event&action=edit&event_id=<?php echo $retrieved_data->event_id;?>" class="float_left_width_100"><i class="fa fa-edit"> </i><?php esc_html_e('Edit', 'school-mgt' ) ;?></a>
                                                                                </li>
                                                                                <?php 
                                                                            } 
                                                                            if($user_access_delete =='1')
                                                                            {
                                                                                ?>
                                                                                <li class="float_left_width_100 ">
                                                                                    <a href="admin.php?page=smgt_event&tab=eventlist&action=delete&event_id=<?php echo $retrieved_data->event_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
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
                                                <input type="checkbox" name="" class="sub_chk select_all" value="" style="margin-top: 0px;">
                                                <label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
                                            </button>
                                            <?php if($user_access_delete =='1')
                                            { 
                                                ?>
                                                <button data-toggle="tooltip" id="delete_selected" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </form><!-------- Exam List Form --------->
                                </div><!-------- Table Responsive --------->
                            </div>
                            <?php 
                        }
                        else
                        {
                            if($user_access_add=='1')
							{
                                ?>
                                <div class="no_data_list_div no_data_img_mt_30px"> 
                                    <a href="<?php echo admin_url().'admin.php?page=smgt_event&tab=add_event';?>">
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
                    }
                    if($active_tab == 'add_event')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/event/add_event.php';
					} 
                    ?>
                </div><!-- smgt_main_listpage -->
            </div><!-- col-md-12 -->
        </div><!-- row -->
	</div><!-- main_list_margin_15px -->
</div><!-- page-inner -->