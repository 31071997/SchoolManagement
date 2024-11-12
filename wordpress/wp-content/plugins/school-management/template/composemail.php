<?php ?>
<div id="demo"></div>
<?php
$role = mj_smgt_get_user_role(get_current_user_id());
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(isset($_POST['save_message']))
{
	$created_date = date("Y-m-d H:i:s");
	$subject = mj_smgt_popup_category_validation($_POST['subject']);
	$message_body = mj_smgt_address_description_validation($_POST['message_body']);
	$created_date = date("Y-m-d H:i:s");
	$tablename="smgt_message";
	$smgt_sms_service_enable=isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;


	$role=$_POST['receiver'];
	if(isset($_POST['smgt_message_mail_service_enable']) == "1")
	{
		$MailBody  		= 	get_option('message_received_mailcontent');
		$SchoolName 	=  	get_option('smgt_school_name');
		$SubArr['{{school_name}}'] 	= $SchoolName;
		$SubArr['{{from_mail}}'] = mj_smgt_get_display_name(get_current_user_id());
		$MailSub = mj_smgt_string_replacement($SubArr,get_option('message_received_mailsubject'));
	}


	if(isset($_REQUEST['class_id']))
	$class_id = $_REQUEST['class_id'];


	$role = $_REQUEST['receiver'];
	$class_id = isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
	$class_section = isset($_REQUEST['class_section'])?$_REQUEST['class_section']:'';
	$selected_users = isset($_REQUEST['selected_users'])?$_REQUEST['selected_users']:array();
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
		) );

		$reci_number =array();
		$device_token = array();
		foreach($selected_users as $user_id)
		{
			$user_info = get_userdata($user_id);
			if($user_id != get_current_user_id())
			{
				$reci_number[]= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				$device_token[] = get_user_meta($user_id, 'token_id' , true);
			}
		}

		/* Start Send Push Notification */
		$title = esc_attr__('You have received new message','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['subject']));
		$text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
		$notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
		$json = json_encode($notification_data);
		$message =	MJ_smgt_send_push_notification($json);
		/* End Send Push Notification */

		$result=add_post_meta($post_id, 'message_for',$role);
		$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
		$result=add_post_meta($post_id, 'message_attachment',$attachment);
		foreach($selected_users as $user_id)
		{
			$user_info = get_userdata($user_id);
			// SEND SMS NOTIFICATION
			if($smgt_sms_service_enable)
			{
				$message_content = $_POST['sms_template'];

				$type = "Message";

				MJ_smgt_send_sms_notification($user_id,$type,$message_content);
			}

			$message_data=array('sender'=>get_current_user_id(),
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
					if(get_option('smgt_mail_notification') == '1')
					{
						wp_mail($to, $MailSub, $messg,$headers,$mailattachment);
					}
				}
				else
				{
					if(get_option('smgt_mail_notification') == '1')
					{
						wp_mail($to, $MailSub, $messg);
					}
				}
			}
		}
	}
	else
	{
		$user_list = array();
		$class_list = $class_id ;
		$query_data['role']=$role;
		$exlude_id = mj_smgt_approve_student_list();
		$multi_class_id=$_POST['multi_class_id'];
		if(isset($_POST['class_selection_type']))
		{
			$class_selection_type=$_POST['class_selection_type'];
		}
		else
		{
			$class_selection_type='single';
		}
		if($role == 'student')
		{
			if($class_selection_type == 'single')
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
			if($class_selection_type == 'single')
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
			if($class_selection_type == 'single')
			{
				if($class_list == '')
				{
					$results = get_users($query_data);
				}
				else
				{
					$query_data['role'] = 'student';
					$query_data['exclude']=$exlude_id;
					if($class_section){
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
						//var_dump($parent);
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
						$parent = get_user_meta($users->ID, 'parent_id', true);
						if(!empty($parent))
						foreach($parent as $p)
						{
							$user_list[]=$p;
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
			if($class_selection_type == 'single')
			{
				$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
			}
			else
			{
				$result=add_post_meta($post_id, 'smgt_class_id',implode(',',$multi_class_id));
			}
			$result=add_post_meta($post_id, 'message_attachment',$attachment);
			$device_token = array();
			foreach($user_data_list as $user_id)
			{
				if($user_id != get_current_user_id())
				{
					$device_token[] = get_user_meta($user_id, 'token_id' , true);
					$user_info = get_userdata($user_id);
					$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
					$message_content = $_POST['sms_template'];
					$current_sms_service = get_option( 'smgt_sms_service');
					if($smgt_sms_service_enable)
					{
						if($current_sms_service == 'clickatell')
						{
							$clickatell=get_option('smgt_clickatell_sms_service');
							$to = $reciever_number;
							$message = str_replace(" ","%20",$message_content);
							$username = $clickatell['username']; //clickatell username
							$password = $clickatell['password']; // clickatell password
							$api_key = $clickatell['api_key'];//clickatell apikey
							$sender_id = $clickatell['sender_id'];//clickatell sender_id
							$baseurl ="http://api.clickatell.com";

							$ret = file($url);
							$sess = explode(":",$ret[0]);
							if ($sess[0] == "OK")
							{
								$sess_id = trim($sess[1]); // remove any whitespace
								$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message&from=$sender_id";
								$ret = file($url);
								$send = explode(":",$ret[0]);
							}
						}
						if($current_sms_service == 'twillo')
						{
							//Twilio lib
							require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
							$twilio=get_option( 'smgt_twillo_sms_service');
							$account_sid = $twilio['account_sid']; //Twilio SID
							$auth_token = $twilio['auth_token']; // Twilio token
							$from_number = $twilio['from_number'];//My number
							$receiver = $reciever_number; //Receiver Number
							$message = $message_content; // Message Text
							//twilio object
							$client = new Services_Twilio($account_sid, $auth_token);
							$message_sent = $client->account->messages->sendMessage(
								$from_number, // From a valid Twilio number
								$receiver, // Text this number
								$message
							);
						}
						if($current_sms_service == 'msg91')
						{
							//MSG91
							$mobile_number=get_user_meta($user_id, 'mobile_number',true);
							$country_code="+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));
							$message = $message_content; // Message Text
							smgt_msg91_send_mail_function($mobile_number,$message,$country_code);
						}
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
							if(get_option('smgt_mail_notification') == '1')
							{
								wp_mail($to, $MailSub, $messg,$headers,$mailattachment);
							}
						}
						else
						{
							if(get_option('smgt_mail_notification') == '1')
							{
								wp_mail($to, $MailSub, $messg);
							}
						}
					}
				}
			}
			 /* Start Send Push Notification */

			 $title = esc_attr__('You have received new message','school-mgt').' '.mj_smgt_popup_category_validation(stripslashes($_POST['subject']));
			 $text = mj_smgt_address_description_validation(stripslashes($_POST['message_body']));
			 $notification_data = array('registration_ids'=>$device_token,'notification'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
			 $json = json_encode($notification_data);
			 $message =	MJ_smgt_send_push_notification($json);

			 /* Start Send Push Notification */
		}
	}
	if(isset($result))
	{
		wp_redirect ( home_url() . '?dashboard=user&page=message&tab=compose&message=1');
	}
}

if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{ ?>
	<div class="ps-3">
		<div id="message" class="alert_msg alert alert-success alert-dismissible margin_top_15px" role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
			</button>
			<?php esc_attr_e('Message Sent Successfully','school-mgt');	?>
		</div>
	</div>
		
	<?php
	}
	elseif($message == 2)
	{
		?>
		<div class="ps-3">
			<div id="message" class="alert_msg alert alert-success alert-dismissible margin_top_15px" role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Close.png"?>" alt=""></span>
				</button>
				<?php esc_attr_e("Message deleted successfully",'school-mgt');?>
			</div>
		</div>
		
		<?php
	}
}
?>

<div class="overflow-hidden mailbox-content">
	<h2>
		<?php
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				echo esc_html( esc_attr__( 'Edit Message', 'school-mgt') );
				$edit=1;
				$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
			}
		?>
	</h2>
	<form name="class_form" action="" method="post" class="form-horizontal" id="message_form" enctype="multipart/form-data">
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-body user_form"><!--user form -->
			<div class="row"><!--row -->

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">
					<label class="ml-1 custom-top-label top" for="to"><?php esc_attr_e('Message To','school-mgt');?><span class="require-field">*</span></label>
					<select name="receiver" class="line_height_30px form-control validate[required] text-input" id="send_to">
						<?php
						if($school_obj->role == 'parent')
						{
							if(get_option('parent_send_message') == 1)
							{
								?>
								<option value="student"><?php esc_attr_e('Students','school-mgt');?></option>
								<?php
							}
						}
						elseif($school_obj->role == 'student')
						{
							if(get_option('student_send_message') == 1)
							{
							?>
								<option value="student"><?php esc_attr_e('Student','school-mgt');?></option>
							<?php
							}
						}
						else
						{
							?>
							<option value="student"><?php esc_attr_e('Students','school-mgt');?></option>
							<?php
						}
						?>
						<option value="teacher"><?php esc_attr_e('Teachers','school-mgt');?></option>
						<?php if($school_obj->role != 'student' &&  $school_obj->role != 'parent' ) /* Student should not send SMS to parents */
						{ ?>
							<option value="parent"><?php esc_attr_e('Parents','school-mgt');?></option>
						<?php
						} ?>
						<option value="supportstaff"><?php esc_attr_e('Support Staff','school-mgt');?></option>
						<?php
						if($school_obj->role != 'student')
						{
							?>
							<option value="administrator"><?php esc_attr_e('Admin','school-mgt');?></option>
						<?php
						}
						?>
						<?php //echo mj_smgt_get_all_user_in_message();?>
					</select>
				</div>
				<?php
				if($school_obj->role == "teacher" || $school_obj->role == "supportstaff")
				{
					?>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 class_selection input">
						<label class="ml-1 custom-top-label top" for="to"><?php esc_attr_e('Class Selection Type','school-mgt');?></label>
						<select name="class_selection_type" class="line_height_30px form-control validate[required] text-input class_selection_type">
							<option value="single"><?php esc_attr_e('Single','school-mgt');?></option>
							<option value="multiple"><?php esc_attr_e('Multiple','school-mgt');?></option>
						</select>
					</div>

					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 multiple_class_div" style="display:none;">
						<div class="col-sm-12 smgt_msg_multiple smgt_multiple_select multiselect_validation1">
							<label class="ml-1 custom-top-label top" ><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
							<select name="multi_class_id[]" class="line_height_30px form-control validate[required]" id="selected_class" multiple="true">
								<?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{
									?>
										<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<?php
				}
				?>

				<div id="smgt_select_class" class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_list_id single_class_div">
					<label class="ml-1 custom-top-label top" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
					<?php
						$result = array();
						$role = mj_smgt_get_user_role(get_current_user_id());

						if($role=="parent")
						{
							$class_id=array();
							$parentdata = get_user_meta(get_current_user_id(),'child',true);
							foreach($parentdata as $student_key=>$student_id)
							{
								$class_id[] = get_user_meta($student_id,'class_name',true);
							}
							$class_id_arr = array_unique($class_id);
						}
						if($role=="student")
						{
							$student_class_id = get_user_meta(get_current_user_id(),'class_name',true);
							$student_class_name = mj_smgt_get_class_by_id($student_class_id);
						}
						if($role=="teacher")
						{
							$classdatas = array_filter(mj_smgt_get_all_teacher_data(get_current_user_id()));

							foreach($classdatas as $class_key=>$class_val)
							{
								$result[]= mj_smgt_get_class_by_id($class_val->class_id);

							}
						}
					?>
					<select name="class_id"  id="class_list_id" class="line_height_30px form-control validate[required]">
						<option value=""><?php esc_html_e('Select Class','school-mgt');?></option>
						<?php
						if($role=="teacher")
						{
							foreach($result as $key=>$value)
							{ ?>
							<option value="<?php print $value->class_id; ?>"><?php print $value->class_name ?></option>
						<?php } ?>

						<?php
						}
						elseif($role=="student")
						{
							print '<option value="'.$student_class_id.'"> '.$student_class_name->class_name.' </option>';
						}
						elseif($role=="parent")
						{
							foreach($class_id_arr as $key=>$class_id_val)
							{
								print '<option value="'.$class_id_val.'">'.mj_smgt_get_class_name_by_id($class_id_val).'</option>';
							}
						}
						else
						{
							foreach(mj_smgt_get_allclass() as $classdata)
							{   ?>
							<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
							<?php
							}
						}
						?>
					</select>
				</div>
				<?php
				if($school_obj->role == "parent")
				{
					$class_selection_id_css="display:none";
				}
				else
				{
					$class_selection_id_css="display:auto";
				}
				if($school_obj->role != "parent" && $school_obj->role != "student")
				{
					?>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input class_section_id" style="<?php echo $class_selection_id_css; ?>">
						<label class="ml-1 custom-top-label top" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
						<?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
						<select name="class_section" class="line_height_30px form-control" id="class_section_id">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
							<?php
							if($edit)
							{
								foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
								{  ?>
									<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php
								}
							} ?>
						</select>
					</div>
					<?php
				}
				?>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 single_class_div support_staff_user_div input">
					<div id="messahe_test"></div>
						<div class="col-sm-12 smgt_multiple_select rtl_padding_left_right_0px">
							<span class="user_display_block">
							<select name="selected_users[]" id="selected_users" class="form-control" multiple="multiple">
								<?php
								if($role=="teacher")
								{
									$student_list = mj_smgt_get_teacher_class_student(get_current_user_id());
								}
								elseif($role=="student")
								{
									if(get_option('student_send_message') == 1)
									{
										$std_list = mj_smgt_get_student_by_class_id($student_class_id);
										foreach($std_list as $std_list_ley=>$std_list_val)
										{
											if($std_list_val->ID != get_current_user_id())
											{
												echo '<option value="'.$std_list_val->ID.'">'.$std_list_val->display_name.'</option>';
											}
										}
									}
									else
									{
										$query_data['role']='teacher';
										$student_list = get_users($query_data);
									}
								}
								elseif($role=="parent")
								{
									if(get_option('parent_send_message') == 1)
									{
										foreach($class_id_arr as $key=>$class_id_val)
										{
											$query_data['role']='student';
											$exlude_id = mj_smgt_approve_student_list();
											$query_data['meta_key'] = 'class_name';
											$query_data['meta_value'] = $class_id_val;
											$results = get_users($query_data);
											foreach($results as $userdata)
											{
												echo '<option value="'.$userdata->ID.'">'.$userdata->display_name.'</option>';
											}
										}
									}
									else
									{
										$query_data['role']='teacher';
										$student_list = get_users($query_data);

									}
								}
								else
								{
									$student_list = mj_smgt_get_all_student_list();
								}
								if(!empty($student_list))
								{
									foreach($student_list  as $retrive_data)
									{
										if($retrive_data->ID != get_current_user_id())
										{
											echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->display_name.'</option>';
										}
									}
								}
								?>
							</select>
						</span>
					</div>
				</div>
				<!-- <div id="class_student_list"></div> -->
				<div id="class_student_list" class="col-sm-6 col-md-6 col-lg-6 col-xl-6 error_msg_left_margin">
					<div class="form-group input">
						<div class="col-md-12 form-control">
							<input id="subject" class="form-control validate[required,custom[description_validation]] text-input" maxlength="100" type="text" name="subject" >
							<label class="" for="subject"><?php esc_attr_e('Subject','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<div class="col-md-6 note_text_notice">
					<div class="form-group input mb-0">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<div class="form-field">
								<textarea name="message_body" id="message_body" maxlength="500" class="textarea_height_60px form-control validate[required,custom[description_validation]] text-input"></textarea>
								<span class="txt-title-label"></span>
								<label class="text-area address active" for="subject"><?php esc_attr_e('Message Content','school-mgt');?><span class="require-field">*</span></label>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6 attachment_div">
					<div class="row">
						<div class="col-md-10">
							<div class="form-group input">
								<div class="col-md-12 form-control res_rtl_height_50px">
									<label class="custom-control-label custom-top-label ml-2 margin_left_30px label_position_rtl" for="photo"><?php esc_attr_e('Attachment ','school-mgt');?></label>
									<div class="col-sm-12">
										<input  class="file_line_height_26px col-md-12 input-file" name="message_attachment[]" type="file" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<!-- <input type="button" value="<?php esc_attr_e('Add More Attachment','school-mgt') ?>"  onclick="add_new_attachment()" class="btn more_attachment btn-default"> -->
							<img src="<?php echo SMS_PLUGIN_URL."/assets/images/dashboard_icon/Add_new_plus_btn.png"?>" onclick="add_new_attachment()" alt="" class="rtl_margin_top_15px more_attachment add_cirtificate float_right" id="add_more_sibling">
						</div>
					</div>
				</div>
				<?php
				if($role == 'teacher' || $role == 'supportstaff'){
				?>
				<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mt-3">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="display_flex">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
									<input id="" type="checkbox"  value="1" name="smgt_message_mail_service_enable">&nbsp;
									<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3 rtl_margin_top_15px mt-3">
					<div class="form-group">
						<div class="col-md-12 form-control rtl_relative_position">
							<div class="row padding_radio">
								<div class="display_flex">
									<label class="custom-top-label label_position_rtl" for="enable"><?php esc_attr_e('Send SMS','school-mgt');?></label>
									<input id="chk_sms_sent" type="checkbox"  value="1" name="smgt_sms_service_enable">&nbsp;
									<lable> <?php esc_attr_e('Enable','school-mgt');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 hmsg_message_none" id="hmsg_message_sent" >
					<div class="form-group input">
						<div class="col-md-12 note_border margin_bottom_15px_res">
							<textarea name="sms_template" class="textarea_height_47px form-control validate[required]" maxlength="160"></textarea>
							<span class="txt-title-label"></span>
							<label class="text-area address active" for="sms_template"><?php esc_attr_e('SMS Text','school-mgt');?><span class="require-field">*</span></label>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div><!--row -->
		</div><!--user form -->
		<div class="form-body user_form mt-3"><!--user form -->
			<div class="row"><!--row -->
				<div class="col-sm-6">
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Message','school-mgt'); }else{ esc_attr_e('Send Message','school-mgt');}?>" name="save_message" class="btn btn-success save_btn rtl_margin_0px save_message_selected_user"/>
				</div>
			</div>
		</div>
	</form>
</div>
<?php ?>