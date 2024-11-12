<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role_name=mj_smgt_get_user_role(get_current_user_id());
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
//--------------- SAVE NOTIFICATION ---------------//
if(isset($_POST['save_notification']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_notice_admin_nonce' ) )
	{
		global $wpdb;
		$smgt_notification = $wpdb->prefix. 'smgt_notification';
		$exlude_id = mj_smgt_approve_student_list();
		if(isset($_POST['selected_users']) && $_POST['selected_users'] != 'All')
		{
            /* Send Push Notification */
            $device_token = array();
			$device_token[]=get_user_meta($_POST['selected_users'], 'token_id' , true);
            $title=esc_attr__('You have a New Notification','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['title']));
			$text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
			$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
			$json = json_encode($notification_data);
			$message = MJ_smgt_send_push_notification($json);
            /* Send Push Notification */
			
			$data['student_id'] = $_POST['selected_users'];
			$data['title'] = mj_smgt_popup_category_validation(stripslashes($_POST['title']));
			$data['message'] = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
			$data['created_date'] = date('Y-m-d');
			$data['created_by'] = get_current_user_id();
			$result=$wpdb->insert( $smgt_notification,$data );
		}
		elseif(isset($_POST['class_id']) && $_POST['class_id'] == 'All')
		{
			foreach(mj_smgt_get_allclass() as $class)
			{
                    $query_data['exclude']=$exlude_id;
                    $query_data['meta_query'] = array(array('key' => 'class_name','value' => $class['class_id'],'compare' => '=') );
                    $results = get_users($query_data);
                    if(!empty($results))
                    {
                        foreach($results as $retrive_data)
                        {
                            
                            /* Send Push Notification */
                            $device_token = array();
                            $device_token[]=get_user_meta($retrive_data->ID, 'token_id' , true);
                            $title=esc_attr__('You have a New Notification','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['title']));
                            $text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
                            $notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
                            $json = json_encode($notification_data);
                            MJ_smgt_send_push_notification($json);
                            var_dump(MJ_smgt_send_push_notification($json));
                            /* Send Push Notification */

                            $data['student_id'] = $retrive_data->ID;
                            $data['title'] = mj_smgt_popup_category_validation(stripslashes($_POST['title']));
                            $data['message'] = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
                            $data['created_date'] = date('Y-m-d');
                            $data['created_by'] = get_current_user_id();
                            $result=$wpdb->insert( $smgt_notification,$data );
                        }
                    }
			}
		}
		elseif(isset($_POST['class_section']) && $_POST['class_section'] == 'All')
		{
			$query_data['exclude']=$exlude_id;
			$query_data['meta_query'] = array(array('key' => 'class_name','value' => $_POST['class_id'],'compare' => '=') );
			$results = get_users($query_data);
			
			if(!empty($results))
			{
				foreach($results as $retrive_data)
				{
                    /* Send Push Notification */
                    $device_token = array();
					$device_token[]=get_user_meta($retrive_data->ID, 'token_id' , true);
					$title=esc_attr__('You have a New Notification','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['title']));
			        $text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
					$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
					$json = json_encode($notification_data);
					MJ_smgt_send_push_notification($json);
                   
                    /* Send Push Notification */

					$data['student_id'] = $retrive_data->ID;
					$data['title'] = mj_smgt_popup_category_validation(stripslashes($_POST['title']));
					$data['message'] = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
					$data['created_date'] = date('Y-m-d');
					$data['created_by'] = get_current_user_id();
					$result=$wpdb->insert( $smgt_notification,$data );
				}
			}
		}
		else
		{
			$query_data['exclude']=$exlude_id;
			$query_data['meta_key'] = 'class_section';
			$query_data['meta_value'] = $_POST['class_section'];
			$query_data['meta_query'] = array(array('key' => 'class_name','value' => $_POST['class_id'],'compare' => '=') );
			$results = get_users($query_data);
			if(!empty($results))
			{
				foreach($results as $retrive_data)
				{
                    /* Send Push Notification */
                    $device_token = array();
					$device_token[]=get_user_meta($retrive_data->ID, 'token_id' , true);
					$title=esc_attr__('You have a New Notification','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['title']));
			        $text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
					$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'notification'));
					$json = json_encode($notification_data);
					MJ_smgt_send_push_notification($json);
					/* Send Push Notification */

					$data['student_id'] = $retrive_data->ID;
					$data['title'] = mj_smgt_popup_category_validation(stripslashes($_POST['title']));
					$data['message'] = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
					$data['created_date'] = date('Y-m-d');
					$data['created_by'] = get_current_user_id();
					$result=$wpdb->insert( $smgt_notification,$data );
				}
			}
		}
		if($result)
		{
		  wp_redirect ( home_url().'?dashboard=user&page=notification&tab=notificationlist&message=1');
		
		}
        else{
            ?>
             <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			    <button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			    </button>
			    <?php echo esc_attr__('Please Add least one student','school-mgt');?>
		    </div>
            <?php
        }
    }
}

//----------- DELETE NOTIFICATION ----------------//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$result=mj_smgt_delete_notification($_REQUEST['notification_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=notification&tab=notificationlist&message=2');
	}
}

//----------- Add Multiple Delete record ----------//
if(isset($_REQUEST['delete_selected']))
{
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_notification($id);
			wp_redirect ( home_url().'?dashboard=user&page=notification&tab=notificationlist&message=2');
		}
	}
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=notification&tab=notificationlist&message=2');
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'notificationlist';
?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	$('#notification_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
});
</script>
<div class="panel-body panel-white frontend_list_margin_30px_res"><!--------------- PENAL BODY ---------------------> 
    <?php
    $message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
    switch($message)
    {
        case '1':
            $message_string = esc_attr__('Notification Inserted Successfully.','school-mgt');
            break;
        case '2':
            $message_string = esc_attr__('Notification Deleted Successfully.','school-mgt');
            break;	
        case '3':
            $message_string = esc_attr__('','school-mgt');
            break;
    }
    if($message)
    { ?>
       <div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<?php echo $message_string;?>
		</div>
        <?php 
    }

    if($active_tab == 'notificationlist')
    { 
        $user_id=get_current_user_id();
        //------- NOTIFICATION DATA FOR STUDENT ---------//
		if($school_obj->role == 'student')
		{
            $own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
                $result = mj_smgt_get_student_own_notification_created_by($user_id);
            }
            else 
            {
                global $wpdb;
                $smgt_notification = $wpdb->prefix. 'smgt_notification';	
                $result =$wpdb->get_results("SELECT * FROM $smgt_notification");  
            }
        }
        //------- NOTIFICATION DATA FOR TEACHER ---------//
		elseif($school_obj->role == 'teacher')
		{
            $own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
                $result = mj_smgt_get_all_notification_created_by($user_id);
            }
            else 
            {  
                global $wpdb;
                $smgt_notification = $wpdb->prefix. 'smgt_notification';	
                $result =$wpdb->get_results("SELECT * FROM $smgt_notification");
            }
        }
        //------- NOTIFICATION DATA FOR PARENT ---------//
		elseif($school_obj->role == 'parent')
		{
            $own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
                $result = mj_smgt_get_all_notification_for_parent($user_id);
            }
            else 
            {  
                global $wpdb;
                $smgt_notification = $wpdb->prefix. 'smgt_notification';	
                $result =$wpdb->get_results("SELECT * FROM $smgt_notification");
            }
        }
        //------- NOTIFICATION DATA FOR SUPPORT STAFF ---------//
		else
		{
            $own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
                $result = mj_smgt_get_all_notification_created_by($user_id);
            }
            else 
            {  
                global $wpdb;
                $smgt_notification = $wpdb->prefix. 'smgt_notification';	
                $result =$wpdb->get_results("SELECT * FROM $smgt_notification");
            }
        }
        if(!empty($result))
        {	
            ?>	
            <script type="text/javascript">
                jQuery(document).ready(function($)
                {
                    "use strict";	
                    var table =  jQuery('#notification_list').DataTable({
                        "initComplete": function(settings, json) {
                            $(".print-button").css({"margin-top": "-5%"});
                        },
                        //stateSave: true,
                        "dom": 'lifrtp',
                        "order": [[ 2, "asc" ]],
                        "aoColumns":[		                  
                            <?php
								if($role_name == "supportstaff")
								{
									?>
									{"bSortable": false},
									<?php
								}
								?>	                 
                                {"bSortable": false},
                                {"bSortable": true},
                                {"bSortable": true},
                                {"bSortable": true},
                                {"bSortable": true},
                                <?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
									?>
									{"bSortable": false}
									<?php
								}
								?>
                            ],
                        language:<?php echo mj_smgt_datatable_multi_language();?>
                    });
                    $('.dataTables_filter input').attr("placeholder", "<?php esc_html_e('Search...', 'school-mgt') ?>");
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
                    $("#delete_selected").on('click', function()
                    {	
                        if ($('.smgt_sub_chk:checked').length == 0 )
                        {
                            alert(language_translate2.one_record_select_alert);
                            return false;
                        }
                        else
                        {
                            var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?','school-mgt');?>");
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
            <div class="panel-body"><!-- panel-body-->
                <div class="table-responsive"><!--table-responsive-->
                    <form name="frm-example" action="" method="post">
                        <table id="notification_list" class="display admin_notification_datatable" cellspacing="0" width="100%">
                            <thead class="<?php echo MJ_smgt_datatable_heder() ?>">
                                <tr>
                                    <?php
                                    if($role_name == "supportstaff")
                                    {
                                        ?>
                                        <th class="padding_0"><input type="checkbox" class="select_all" id="select_all"></th>
                                        <?php
                                    }
                                    ?>
                                    <th><?php  _e( 'Image', 'school-mgt' ) ;?></th>
                                    <th><?php echo esc_attr_e('Student Name', 'school-mgt' ) ;?></th>
                                    <th><?php  _e( 'Class name', 'school-mgt' ) ;?></th>
                                    <th><?php echo esc_attr_e( 'Title', 'school-mgt' ) ;?></th>
                                    <th> <?php echo esc_attr_e( 'Message', 'school-mgt' ) ;?></th>
                                    <?php
                                    if($user_access['edit']=='1' || $user_access['delete']=='1')
                                    {
                                        ?>
                                        <th class="text_align_end"><?php _e( 'Action', 'school-mgt' ) ;?></th>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i=0;	
                                if($result)
                                {
                                    foreach ($result as $retrieved_data)
                                    { 

                                        $class_id=get_user_meta($retrieved_data->student_id, 'class_name',true);

										$section_name=get_user_meta($retrieved_data->student_id, 'class_section',true);
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
                                            <?php
											if($role_name == "supportstaff")
											{
												?>
												<td class="checkbox_width_10px">
                                                    <input type="checkbox" class="smgt_sub_chk select-checkbox" name="id[]" value="<?php echo $retrieved_data->notification_id;?>">
                                                </td>
												<?php
											}
											?>
                                            <td class="user_image width_50px profile_image_prescription padding_left_0">
                                                <p class="prescription_tag padding_15px margin_bottom_0px <?php echo $color_class; ?>">	
                                                    <img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/White_icons/Notification.png"?>" height= "30px" width ="30px" alt="" class="massage_image center">
                                                </p>
                                            </td>
                                            <td>
                                            <?php $sname = mj_smgt_student_display_name_with_roll($retrieved_data->student_id); if($sname != ''){echo $sname;}else{echo 'N/A';} ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Student Name','school-mgt');?>" ></i>
                                            </td>
                                            <td>
                                                <?php echo smgt_get_class_section_name_wise($class_id,$section_name);?>
                                                <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Class Name','school-mgt');?>" ></i>
                                            </td>
                                            <td>
                                                <?php echo $retrieved_data->title; ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php _e('Title','school-mgt');?>" ></i>
                                            </td>
                                            <td>
                                                <?php $strlength= strlen($retrieved_data->message); ?> 
                                                <?php  if($strlength > 40){ echo substr($retrieved_data->message, 0,40).'...'; }else{ echo $retrieved_data->message; } ?> <i class="fa fa-info-circle fa_information_bg" data-toggle="tooltip" data-placement="top" title="<?php if(!empty($retrieved_data->message)){echo $retrieved_data->message;}else{ _e('Message','school-mgt');}?>" ></i>
                                            </td>
                                            <?php
                                            if($user_access['edit']=='1' || $user_access['delete']=='1')
                                            {
                                                ?>
                                                <td class="action"> 
                                                    <div class="smgt-user-dropdown">
                                                        <ul class="" style="margin-bottom: 0px !important;">
                                                            <li class="">
                                                                <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/More.png"?>" >
                                                                </a>
                                                                <ul class="dropdown-menu heder-dropdown-menu action_dropdawn" aria-labelledby="dropdownMenuLink">
                                                                    <?php 
                                                                    if($user_access['delete']=='1')
                                                                    { 
                                                                        ?>
                                                                        <li class="float_left_width_100">
                                                                            <a href="?dashboard=user&page=notification&tab=notificationlist&action=delete&notification_id=<?php echo $retrieved_data->notification_id;?>" class="float_left_width_100" style="color: #fd726a !important;" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><i class="fa fa-trash"></i>
                                                                            <?php esc_attr_e('Delete','school-mgt');?>
                                                                            </a> 
                                                                        </li>
                                                                        <?php 
                                                                    } ?> 
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>	
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php 
                                        $i++;
                                    } 
                                }?>
                            </tbody>
                        </table>
                        <?php
                        if($role_name == "supportstaff")
                        {
                            ?>
                            <div class="print-button pull-left">
                                <button class="btn btn-success btn-sms-color button_reload">
                                    <input type="checkbox" name="" class="smgt_sub_chk select_all" value="" style="margin-top: 0px;">
                                    <label for="checkbox" class="margin_right_5px"><?php esc_html_e( 'Select All', 'school-mgt' ) ;?></label>
                                </button>
                                <?php
                                if($user_access['delete']=='1')
                                { ?>
                                    <button id="delete_selected" data-toggle="tooltip" title="<?php esc_html_e('Delete Selected','school-mgt');?>" name="delete_selected" class="delete_selected" ><img src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png" ?>" alt=""></button>
                                    <?php 
                                } ?>
                            </div>
                            <?php
                        }
                        ?>
                    </form>
                </div><!--table-responsive-->
            </div><!-- panel-body-->
            <?php 
        }
        else
        {
            if($user_access['add']=='1')
			{
                ?>
                <div class="no_data_list_div"> 
                    <a href="<?php echo home_url().'?dashboard=user&page=notification&tab=addnotification';?>">
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
    if($active_tab == 'addnotification')
    {
        ?>
        <div class="panel-body overflow-hidden"><!-- panel-body -->
            <form name="class_form" action="" method="post" class="form-horizontal" id="notification_form">
                <div class="header">	
                    <h3 class="first_hed"><?php esc_html_e('Notification Information','school-mgt');?></h3>
                </div>
                <div class="form-body user_form">
                    <div class="row">
                        <div class="col-md-6 input">
                            <label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
                            <select name="class_id"  id="notification_class_list_id" class="line_height_30px form-control max_width_100">
                                <option value="All"><?php esc_attr_e('All','school-mgt');?></option>
                                <?php
                                foreach(mj_smgt_get_allclass() as $classdata)
                                {  
                                    ?>
                                    <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
                                    <?php 
                                }?>
                            </select>
                        </div>
                    
                        <div class="col-md-6 input notification_class_section_id">
                            <label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
                            <select name="class_section" class="line_height_30px form-control max_width_100" id="notification_class_section_id">
                                <option value="All"><?php esc_attr_e('All','school-mgt');?></option>
                            </select>
                        </div>
                        <div class="col-md-6 input">
                            <label class="ml-1 custom-top-label top"><?php esc_attr_e('Select Users','school-mgt');?></label>
                            <span class="notification_user_display_block">
                                <select name="selected_users" id="notification_selected_users" class="line_height_30px form-control max_width_100">
                                <option value="All"><?php esc_attr_e('All','school-mgt');?></option>				
                                </select>
                            </span>
                        </div>
                        <?php wp_nonce_field( 'save_notice_admin_nonce' ); ?>
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
                            <div class="form-group input">
                                <div class="col-md-12 form-control">
                                    <input id="title" class="form-control validate[required,custom[description_validation]] text-input" type="text" maxlength="100" name="title" >
                                    <label class="" for="subject"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 note_text_notice">
                            <div class="form-group input">
                                <div class="col-md-12 note_border margin_bottom_15px_res">
                                    <div class="form-field">
                                        <textarea name="message_body" id="message_body" maxlength="1000" class="textarea_height_60px form-control validate[required,custom[description_validation]] text-input"></textarea>
                                        <span class="txt-title-label"></span>
                                        <label class="text-area address active" for="message"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>		
                    </div>
                </div>  
                
                <div class="form-body user_form">
                    <div class="row">
                        <div class="col-sm-6">    
                            <input type="submit" value="<?php esc_html_e('Add Notification','school-mgt');?>" name="save_notification" class="btn btn-success save_btn"/>
                        </div>
                    </div>
                </div>     
            </form>
        </div><!-- panel-body -->
        <?php
    }
    ?>
</div><!--------------- PENAL BODY ---------------------> 