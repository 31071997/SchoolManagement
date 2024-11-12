<?php //-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$role=mj_smgt_get_user_role(get_current_user_id());
if($role == 'administrator')
{
	$user_access_add=1;
	$user_access_edit=1;
	$user_access_delete=1;
	$user_access_view=1;
}
else
{
	$user_access=mj_smgt_get_userrole_wise_filter_access_right_array('message');
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
			if ('message' == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
			{
				if($user_access_edit=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}			
			}
			if ('message' == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
			{
				if($user_access_delete=='0')
				{	
					mj_smgt_access_right_page_not_access_message_admin_side();
					die;
				}	
			}
			if ('message' == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
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

?>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		//"use strict";	
		$('#message_form').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		$('#selected_users').multiselect({ 
			nonSelectedText :"<?php esc_attr_e('Select Users','school-mgt');?>",
			includeSelectAllOption: true,
			selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		});
		$('#selected_class').multiselect({ 
			nonSelectedText :'<?php esc_attr_e('Select Class','school-mgt');?>',
			includeSelectAllOption: true,
			selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			},
		      
		});
		$("body").on("click",".save_message_selected_user",function()
		{		
			var class_selection_type = $(".class_selection_type").val();	
					
			if(class_selection_type == 'multiple')
			{
				var checked = $(".multiselect_validation1 .dropdown-menu input:checked").length;

				if(!checked)
				{
					alert(language_translate2.one_class_select_alert);
					return false;
				}	
			}			
		});  
		jQuery("body").on("change", ".input-file[type=file]", function ()
		{ 
			"use strict";
			var file = this.files[0]; 		
			var ext = $(this).val().split('.').pop().toLowerCase(); 
			//Extension Check 
			if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg','']) == -1)
			{
				alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed.'  + ext + ' formate are not allowed.');
				$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
				return false; 
			} 
			//File Size Check 
			if (file.size > 20480000) 
			{
				alert(language_translate2.large_file_Size_alert);
				$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
				return false; 
			}
		}); 

		jQuery('#message-replay').validationEngine({promptPosition : "bottomLeft",maxErrorsPerField: 1});
		jQuery('span.timeago').timeago();

	});	

	function add_new_attachment()
	{
		$(".attachment_div").append('<div class="row"><div class="col-md-10"><div class="form-group input"><div class="col-md-12 form-control res_rtl_height_50px"><label class="custom-control-label custom-top-label ml-2 margin_left_30px" for="photo"><?php esc_attr_e('Attachment','school-mgt');?></label><div class="col-sm-12">	<input  class="col-md-12 input-file" name="message_attachment[]" type="file" /></div></div></div></div><div class="col-sm-2"><input type="image" onclick="delete_attachment(this)" alt="" src="<?php echo SMS_PLUGIN_URL."/assets/images/listpage_icon/Delete.png"?>" class="rtl_margin_top_15px remove_cirtificate doc_label float_right input_btn_height_width"></div></div>');
	}

	function delete_attachment(n)
	{
		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
	}	
</script>
<?php 
if(isset($_POST['save_message']))
{	
	
	$created_date 	= 	date("Y-m-d H:i:s");
	$subject 		= 	mj_smgt_popup_category_validation($_POST['subject']);
	$message_body 	= 	mj_smgt_address_description_validation($_POST['message_body']);	
	$created_date 	=	 date("Y-m-d H:i:s");
	$tablename		=	"smgt_message";
	$smgt_sms_service_enable	=	isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;
	
	
	if(isset($_POST['smgt_message_mail_service_enable']) == "1")
	{
		$role	=	$_POST['receiver'];
		$MailBody  		= 	get_option('message_received_mailcontent');	
		$SchoolName 	=  	get_option('smgt_school_name');
		$SubArr['{{school_name}}'] 	= 	$SchoolName;
		$SubArr['{{from_mail}}'] 	= 	mj_smgt_get_display_name(get_current_user_id());
		$MailSub 	= 	mj_smgt_string_replacement($SubArr,get_option('message_received_mailsubject'));
	}
	if(isset($_REQUEST['class_id']))
		$class_id 	= 	$_REQUEST['class_id'];
	
	$role 	= 	$_REQUEST['receiver'];
	$class_id 	= 	isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
	$class_section 		= 	isset($_REQUEST['class_section'])?$_REQUEST['class_section']:'';
	$selected_users 	= 	isset($_REQUEST['selected_users'])?$_REQUEST['selected_users']:array();
	$selected_users 	= 	array_unique($selected_users);
	
	$upload_docs_array=array();	
	if(!empty($_FILES['message_attachment']['name']))
	{
		$count_array=count($_FILES['message_attachment']['name']);

		for($a=0;$a<$count_array;$a++)
		{			
			foreach($_FILES['message_attachment'] as $image_key=>$image_val)
			{		
				$document_array[$a]=array(
				'name'=>$_FILES['message_attachment']['name'][$a],
				'type'=>$_FILES['message_attachment']['type'][$a],
				'tmp_name'=>$_FILES['message_attachment']['tmp_name'][$a],
				'error'=>$_FILES['message_attachment']['error'][$a],
				'size'=>$_FILES['message_attachment']['size'][$a]
				);							
			}
		}				
		foreach($document_array as $key=>$value)		
		{	
			$get_file_name=$document_array[$key]['name'];	
			
			$upload_docs_array[]=mj_smgt_load_documets_new($value,$value,$_POST['subject']);
						
		} 				
	}
	$upload_docs_array_filter=array_filter($upload_docs_array);	
	if(!empty($upload_docs_array_filter))
	{
		$attachment=implode(',',$upload_docs_array_filter);
	}
	else
	{
		$attachment='';
	}
	
	if(!empty($selected_users))
	{		
		$post_id = wp_insert_post( array(
			'post_status' => 'publish',
			'post_type' => 'message',
			'post_title' => $subject,
			'post_content' =>$message_body
		));
		
		$result=add_post_meta($post_id, 'message_for',$role);
		$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
		$result=add_post_meta($post_id, 'message_attachment',$attachment);
		$m=0;
		$reci_number =array();

		$device_token = array();
		foreach($selected_users as $user_id)
		{
			$user_info = get_userdata($user_id);				 	
			$reci_number[]= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
			$device_token[] = get_user_meta($user_id, 'token_id' , true);
		}
		
		/* Start Send Push Notification */

		$title = esc_attr__('You have received new message','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['subject']));
		$text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
		$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
		$json = json_encode($notification_data);
		$message =	MJ_smgt_send_push_notification($json);

		/* End Send Push Notification */

		foreach($selected_users as $user_id)
		{			
			$message_content = $_POST['sms_template'];						
			 $current_sms_service = get_option( 'smgt_sms_service');
			 // SEND SMS NOTIFICATION	
			if($smgt_sms_service_enable)
			{	
				$type = 'Message';		
				MJ_smgt_send_sms_notification($user_id,$type,$message_content);		
			}
			
			$message_data=array(
				'sender'=>get_current_user_id(),
				'receiver'=>$user_id,
				'subject'=>$subject,
				'message_body'=>$message_body,
				'date'=>$created_date,
				'post_id'=>$post_id,
				'status' =>0
			);
			
			mj_smgt_insert_record($tablename,$message_data);
			$user_info 		= 	get_userdata($user_id);
			$to				= 	$user_info->user_email; 
			if(isset($_POST['smgt_message_mail_service_enable']) == "1")
			{
				$MesArr['{{receiver_name}}']	=	mj_smgt_get_display_name($user_id);
				$MesArr['{{message_content}}']	=	$message_body;
				$MesArr['{{school_name}}']		=	$SchoolName;
				$messg = mj_smgt_string_replacement($MesArr,$MailBody);
				if(!empty($upload_docs_array_filter))
				{
					$mailattachment=array();
					foreach($upload_docs_array_filter as $attachment_data)
					{
						$mailattachment[]= WP_CONTENT_DIR . '/uploads/school_assets/'.$attachment_data;					
					}
					$headers="";
					$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
					wp_mail($to, $MailSub, $messg,$headers,$mailattachment); 
				}
				else
				{ 
					wp_mail($to, $MailSub, $messg); 
				}
			}
			$m++;
		}

          		
	}
	else
	{		
		$user_list = array();
		$class_list = $class_id ;
		$query_data['role']=$role;
		$exlude_id = mj_smgt_approve_student_list();
		$multi_class_id=$_POST['multi_class_id'];
		if($role == 'student')
		{
			if($_POST['class_selection_type'] == 'single')
			{
				$query_data['exclude']=$exlude_id;
				if($class_section)
				{
					$query_data['meta_key'] = 'class_section';
					$query_data['meta_value'] = $class_section;
					$query_data['meta_query'] = array(array('key' => 'class_name','value' => $class_list,'compare' => '=')
								 );
				}
				elseif($class_list != '')
				{
					$query_data['meta_key'] = 'class_name';
					$query_data['meta_value'] = $class_list;
				}	
			}
			else
			{
				$query_data['exclude']=$exlude_id;
				
				$query_data['meta_query'] = array(array('key' => 'class_name','value' => $multi_class_id,'compare' => 'IN')
								 );
			}			
			$results = get_users($query_data);
		}
		
		if($role == 'teacher')
		{
			if($_POST['class_selection_type'] == 'single')
			{
				if($class_list != '')
				{
					global $wpdb;
					$table_smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';	
					$teacher_list = $wpdb->get_results("SELECT * FROM $table_smgt_teacher_class where class_id = $class_list");
					if($teacher_list)
					{
						foreach($teacher_list as $teacher)
						{
							$user_list[] = $teacher->teacher_id;
						}
					}				
				}
				else
				{
					$results = get_users($query_data);
				}
			}
			else
			{
				global $wpdb;
				$table_smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';	
				$teacher_list = $wpdb->get_results("SELECT * FROM $table_smgt_teacher_class where class_id IN (".implode(',', $multi_class_id).")");
				if($teacher_list)
				{
					foreach($teacher_list as $teacher)
					{
						$user_list[] = $teacher->teacher_id;
					}
				}
			}
		}
		if($role == 'supportstaff')
		{		
			$results = get_users($query_data);
		}
		if($role == 'parent')
		{	
			if($_POST['class_selection_type'] == 'single')
			{
				if($class_list == '')
				{
					$results = get_users($query_data);
				}
				else
				{
					$query_data['role'] = 'student';
					$query_data['exclude']=$exlude_id;
					if($class_section)
					{
						$query_data['meta_key'] = 'class_section';
						$query_data['meta_value'] = $class_section;
						$query_data['meta_query'] = array(array('key' => 'class_name','value' => $class_list,'compare' => '=')
									 );
					}
					elseif($class_list != '')
					{
						$query_data['meta_key'] = 'class_name';
						$query_data['meta_value'] = $class_list;
					}
							
					$userdata=get_users($query_data);
					foreach($userdata as $users)
					{
						$parent = get_user_meta($users->ID, 'parent_id', true);					
						if(!empty($parent))
						foreach($parent as $p)
						{
							$user_list[]=$p;
						}
					}				
				}
			}
			else
			{
				$query_data['role'] = 'student';
				$query_data['exclude']=$exlude_id;
				
				$query_data['meta_query'] = array(array('key' => 'class_name','value' => $multi_class_id,'compare' => 'IN')
								 );
				$userdata=get_users($query_data);				
				
				foreach($userdata as $users)
				{
					$parent_data = get_user_meta($users->ID, 'parent_id', true);
					
					if(!empty($parent_data))
					{
						foreach($parent_data as $p_data)
						{
							$user_list[]=$p_data;
						}
					}
				}				
			}
		}
		if(isset($results))
		{
			foreach($results as $user_datavalue)
			{
				$user_list[] = $user_datavalue->ID;
			}
		}
		$user_data_list = array_unique($user_list);		
		if(!empty($user_data_list))
		{			
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'message',
				'post_title' => $subject,
				'post_content' =>$message_body
			) );
				$result=add_post_meta($post_id, 'message_for',$role);
				if($_POST['class_selection_type'] == 'single')
				{
					$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
				}
				else
				{
					$result=add_post_meta($post_id, 'smgt_class_id',implode(',',$multi_class_id));
				}	
				$result=add_post_meta($post_id, 'message_attachment',$attachment);
				$reci_number =array();
				foreach($user_data_list as $user_id)
				{
					$user_info = get_userdata($user_id);				 	
					$reci_number[]= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				}
				$device_token = array();
			foreach($user_data_list as $user_id)
			{			
					
				$message_content = $_POST['sms_template'];
				$type = "Message";			 	
				// SEND SMS NOTIFICATION
				if($smgt_sms_service_enable)
				{					
					MJ_smgt_send_sms_notification($user_id,$type,$message_content);
				}
				$message_data=array(
					'sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'subject'=>$subject,
					'message_body'=>$message_body,
					'date'=>$created_date,
					'post_id'=>$post_id,
					'status' =>0
				);
				mj_smgt_insert_record($tablename,$message_data);
				$user_info = get_userdata($user_id);
				if(isset($_POST['smgt_message_mail_service_enable']) == "1")
				{
					$to = $user_info->user_email;
					$MesArr['{{receiver_name}}']	= 	mj_smgt_get_display_name($user_id);	
					$MesArr['{{message_content}}']	=	$message_body;
					$MesArr['{{school_name}}']		=	$SchoolName;
					$messg = mj_smgt_string_replacement($MesArr,$MailBody);
					if(!empty($upload_docs_array_filter))
					{
						$mailattachment=array();
						foreach($upload_docs_array_filter as $attachment_data)
						{
							$mailattachment[]= WP_CONTENT_DIR . '/uploads/school_assets/'.$attachment_data;					
						}
						
						$headers="";
						$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
						wp_mail($to, $MailSub, $messg,$headers,$mailattachment); 
						
					}
					else
					{
						wp_mail($to,$MailSub,$messg); 
					}
				}
			}
            /* Start Send Push Notification */

			$title = esc_attr__('You have received new message','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['subject']));
			$text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
			$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
			$json = json_encode($notification_data);
			$message =	MJ_smgt_send_push_notification($json); 

			/* Start Send Push Notification */
		}		
	}
}
if(isset($result))
{
	wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=sentbox&message=1'); 
}
?>
<?php $active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';?>
<div class="page-inner"><!--page-inner -->	
	<div class="main_list_margin_15px"><!--main_list_margin_15px-->	
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Message Sent Successfully!','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Message Deleted Successfully','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('','school-mgt');
				break;
		}
			 ?>
		<!-- <div class="row mailbox-header">
        </div> -->
		<div class="row"><!--row-->	
			<?php
			if($message)
			{ ?>
				<div id="message" class="alert message_disabled_css below-h2 notice is-dismissible alert-dismissible">
					<p><?php echo $message_string;?></p>
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php 
			} ?>
			<div class="col-md-12 padding_0"><!--col-md-12 padding_0-->	
				<ul class="nav nav-tabs panel_tabs flex-nowrap margin_left_1per list-unstyled mailbox-nav">
					<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
						<a href="?page=smgt_message&tab=inbox" class="smgt_inbox_tab"><i class="fa fa-inbox"></i> <?php esc_attr_e('Inbox','school-mgt');?><span class="smgt_inbox_count_number badge badge-success  pull-right ms-1"><?php echo mj_smgt_count_unread_message(get_current_user_id());?></span></a>
					</li>
					<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>>
						<a href="?page=smgt_message&tab=sentbox" class="padding_left_0 tab"><i class="fas fa-sign-out-alt"></i><?php esc_attr_e('Sent','school-mgt');?></a>
					</li>
					<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view_all_message'){?>class="active"<?php }?>>
						<a href="?page=smgt_message&tab=view_all_message" class="padding_left_0 tab"><?php esc_attr_e('View All Message','school-mgt');?></a>
					</li>
						<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view_all_message_reply'){?>class="active"<?php }?>>
						<a href="?page=smgt_message&tab=view_all_message_reply" class="padding_left_0 tab"><?php esc_attr_e('View All Reply Message','school-mgt');?></a>
					</li>		
					</li>
						<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'compose'){?>class="active"<?php }?>>
						<a href="?page=smgt_message&tab=compose" class="padding_left_0 tab"><?php esc_attr_e('Compose','school-mgt');?></a>
					</li>	   
				</ul>
			</div><!--col-md-12 padding_0-->	
				<?php
				if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/sendbox.php';
				if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/inbox.php';
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/composemail.php';
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_message.php';
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_all_message'))
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_all_message.php';
				if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_all_message_reply'))
					require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_all_message_reply.php';
				?>
		</div><!--row-->	
	</div><!--main_list_margin_15px-->	
</div><!-- Page-inner -->