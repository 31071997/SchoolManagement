<?php
class SmgtLeave
{	
	public function hrmgt_add_leave($data)
	{		
		global $wpdb;
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$leavedata['student_id']=$data['student_id'];
		$leavedata['leave_type']=($data['leave_type']);
		$leavedata['leave_duration']=($data['leave_duration']);
		$leavedata['start_date']=date("Y-m-d", strtotime($data['start_date']));
		if(isset($data['end_date']))
		
		$leavedata['end_date']=date("Y-m-d", strtotime($data['end_date']));;
		$leavedata['status']=$data['status'];
		$leavedata['reason']= stripslashes($data['reason']);
		$leavedata['created_by']=get_current_user_id();

		if($data['action']=='edit')
		{
			$whereid['id']=$data['leave_id'];
			if($data['leave_duration']!='more_then_day'){
				$leavedata['end_date']='';
			}
			$result=$wpdb->update( $table_hrmgt_leave, $leavedata ,$whereid);
			
			$student = mj_smgt_get_user_name_byid($leavedata['student_id']);
			school_append_audit_log(''.esc_html__('Leave Updated','hospital_mgt').'('.$student.')'.'',get_current_user_id(),get_current_user_id(),'edit',$_REQUEST['page']);
			return $result;
		}
		else
		{
			$resultdata=$wpdb->insert( $table_hrmgt_leave, $leavedata );
			$student = mj_smgt_get_user_name_byid($leavedata['student_id']);
			school_append_audit_log(''.esc_html__('Leave Added','hospital_mgt').'('.$student.')'.'',get_current_user_id(),get_current_user_id(),'insert',$_REQUEST['page']);
			if($resultdata)
			{
				if(isset($_POST['end_date']))
				{
					$date = mj_smgt_getdate_in_input_box($_POST['start_date']).' '.esc_html__('To','school-mgt').' '.$_POST['end_date'];
				}
				else
				{
					$date = mj_smgt_getdate_in_input_box($_POST['start_date']);
				}
				
				if(isset($data['smgt_enable_leave_mail']) == '1' || isset($data['smgt_enable_leave_sms_student']) == '1' || isset($data['smgt_enable_leave_sms_parent']) == '1') 
				{

					if(isset($data['smgt_enable_leave_mail']) == '1')
					{
						// LEAVE REQUEST MAIL FOR STUDENT START //
						$arr['{{date}}'] = $date;
						$arr['{{leave_type}}'] = get_the_title($_POST['leave_type']);
						$arr['{{leave_duration}}'] = hrmgt_leave_duration_label($_POST['leave_duration']);
						$arr['{{reason}}'] = mj_smgt_strip_tags_and_stripslashes($_POST['reason']);
						$arr['{{student_name}}'] = mj_smgt_get_display_name($_POST['student_id']);
						$arr['{{school_name}}'] = get_option('smgt_school_name');				
						$message = get_option('addleave_email_template_student');			
						$replace_message =  stripslashes(mj_smgt_string_replacement($arr,$message));  /* Student Leave Mail Content */
						
						if($replace_message)
						{
							$to= mj_smgt_get_emailid_byuser_id($_POST['student_id']);		
							$subject = stripslashes(get_option('add_leave_subject_for_student'));  /* Student Leave Mail Subject */
							$result =  mj_smgt_send_mail($to,$subject,$replace_message);
						}
						// LEAVE REQUEST MAIL FOR STUDENT END //

						// LEAVE REQUEST MAIL FOR PARENT START //
						$parent = get_user_meta($_POST['student_id'], 'parent_id', true);

						if(!empty($parent))
						{
							foreach($parent as $p)
							{
								$user_info	 	=    get_userdata($p);
								$arr_1['{{date}}'] = $date;
								$arr_1['{{leave_type}}'] = get_the_title($_POST['leave_type']);
								$arr_1['{{leave_duration}}'] = hrmgt_leave_duration_label($_POST['leave_duration']);
								$arr_1['{{reason}}'] = mj_smgt_strip_tags_and_stripslashes($_POST['reason']);
								$arr_1['{{student_name}}'] = mj_smgt_get_display_name($_POST['student_id']);
								$arr_1['{{parent_name}}'] = $user_info->display_name;
								$arr_1['{{school_name}}'] = get_option('smgt_school_name');				
								$message_1 = get_option('addleave_email_template_parent');			
								$replace_message_1 =  stripslashes(mj_smgt_string_replacement($arr_1,$message_1));  /* Parent Leave Mail Content */

								if($replace_message_1)
								{
									$to = $user_info->user_email;		
									$subject = stripslashes(get_option('add_leave_subject_for_parent'));  /* Parent Leave Mail Subject */
									$result =  mj_smgt_send_mail($to,$subject,$replace_message_1);
								}
							}
						}
						// LEAVE REQUEST MAIL FOR PARENT END //
					}
					// LEAVE SMS NOTIFICATION FOR STUDENT //
					if(isset($data['smgt_enable_leave_sms_student']) == '1')
					{
						$message_content = "Your Leave for ".$date." are Added Successfully.";

						$type = "Leave";

						MJ_smgt_send_sms_notification($userdata->ID,$type,$message_content);
					}

					// LEAVE SMS NOTIFICATION FOR PARENT //
					if(isset($data['smgt_enable_leave_sms_parent']) == '1')
					{
						$parent = get_user_meta($_POST['student_id'], 'parent_id', true);

						if(!empty($parent))
						{
							foreach($parent as $p)
							{
								$message_content = "Your child ".mj_smgt_get_display_name($_POST['student_id']).", has been added leave of ".$date.".";

								$type = "Leave";

								MJ_smgt_send_sms_notification($p,$type,$message_content);
							}
				
						}
						
					}
				
				}
			
				// LEAVE REQUEST MAIL FOR ADMIN START //
				$admin_data =get_users(array('role'=>'administrator'));

				if(!empty($admin_data))
				{
					foreach($admin_data as $admin)
					{
						$arr['{{date}}'] = $date;
						$arr['{{leave_type}}'] = get_the_title($_POST['leave_type']);
						$arr['{{leave_duration}}'] = hrmgt_leave_duration_label($_POST['leave_duration']);
						$arr['{{reason}}'] = mj_smgt_strip_tags_and_stripslashes($_POST['reason']);
						$arr['{{student_name}}'] = mj_smgt_get_display_name($_POST['student_id']);
						$arr['{{school_name}}'] = get_option('smgt_school_name');				
						$message = get_option('addleave_email_template_of_admin');			
						$replace_message =  stripslashes(mj_smgt_string_replacement($arr,$message));  /* Admin Leave Mail Content */
						if($replace_message)
						{
							$to= mj_smgt_get_emailid_byuser_id($admin->ID);				
							$subject = stripslashes(get_option('add_leave_subject_of_admin'));  /* Admin Leave Mail Subject */
							$result =  mj_smgt_send_mail($to,$subject,$replace_message);
						}
					}

				}
				// LEAVE REQUEST MAIL FOR ADMIN END //

				$empdata = get_userdata((int)$data['student_id']);		
				$device_token[] = get_user_meta($data['student_id'], 'token_id' , true);
				/* Start Send Push Notification */
				if($data['leave_duration']=='more_then_day'){
					$end_date = esc_attr__('To','school-mgt').' '.(isset($_POST['end_date'])? $_POST['end_date']:'');
				}else{
					$end_date = '';
				}
				$title = esc_attr__('Request For Leave','school-mgt');
				$text = $_POST['start_date'].' '.$end_date;
				$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
				$json = json_encode($notification_data);
				$result = MJ_smgt_send_push_notification($json);

				/* End Send Push Notification */

			}
			
			return $result = $resultdata ;

		}	
	}
	
	public function get_all_leaves()
	{
		global $wpdb;
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$result = $wpdb->get_results("SELECT * FROM $table_hrmgt_leave");
		return $result;	
	}
	public function get_single_user_leaves($id)
	{
		global $wpdb;				
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$result = $wpdb->get_results("SELECT * FROM $table_hrmgt_leave WHERE student_id=$id");
		return $result;

	}

	public function get_leave_by_status($status)
	{
	    global $wpdb;				
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$result = $wpdb->get_results("SELECT * FROM $table_hrmgt_leave WHERE status='$status'");
		return $result;
	}

	public function get_leave_by_date($date)
	{

	    global $wpdb;				
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$result = $wpdb->get_results("SELECT * FROM $table_hrmgt_leave WHERE start_date='$date'");
		return $result;

	}
	public function get_single_user_leaves_for_report($employee_id,$start_date,$end_date)
	{		
		global $wpdb;				
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$sql = "SELECT * FROM $table_hrmgt_leave WHERE start_date between '".$start_date."' AND '".$end_date."' AND employee_id='".$employee_id."' ";
		$result = $wpdb->get_results($sql);
		return $result;	
	}

	public function hrmgt_get_single_leave($id)
	{
		global $wpdb;
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$result = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave where id=".$id);			
		return $result;
	}
	public function hrmgt_approve_leave($data)
	{
		global $wpdb;
		$id = $data['leave_id'];
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$row = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave WHERE id=".$id);
		$update = $wpdb->query("UPDATE $table_hrmgt_leave SET status='Approved' where id=".$id);	
		$empdata = get_userdata((int)$row->student_id);
		if($update)
		{	
		    $data['start_date'] = $row->start_date;
			$data['end_date'] = $row->end_date;
			$data['student_id'] = $row->student_id;
			$data['leave_duration'] = $row->leave_duration;
			$leave_data = $this->hrmgt_get_single_leave($id);
			
			$arr=array();

			if(!empty($leave_data->end_date))
			{
				$date = smgt_change_dateformat($leave_data->start_date) .' To '. smgt_change_dateformat($leave_data->end_date);
			}
			else
			{
				//$date  = smgt_change_dateformat($leave_data->start_date);
				$date  = smgt_change_dateformat($leave_data->start_date);
			}

			$arr['{{date}}']= $date;					
			$arr['{{system_name}}'] = get_option('smgt_school_name');
			$arr['{{user_name}}'] = mj_smgt_get_display_name($leave_data->student_id);
			$arr['{{comment}}'] = mj_smgt_strip_tags_and_stripslashes($data['comment']);
			$message = get_option('leave_approve_email_template');		
			
			$replace_message =  stripslashes(mj_smgt_string_replacement($arr,$message));
			
			if($replace_message)
			{
				$subject = stripslashes(get_option('leave_approve_subject'));						
				$to[]= mj_smgt_get_emailid_byuser_id($leave_data->student_id);				
				$emails = get_option('leave_approveemails');
				$emails = explode(",",$emails);
				
				foreach($emails as $email)
				{
					$to[]=$email;
				}
				$mail = mj_smgt_send_mail($to,$subject,$replace_message);	
				if($mail)
				{
					return true;
				}
			}
			/* Start Send Push Notification */
			
			$device_token[] = get_user_meta($row->student_id, 'token_id' , true);
			
			$title = esc_attr__('Your leave approved','school-mgt');
			$text = $date;
			$notification_data = array('registration_ids'=>$device_token,'data'=>array('title'=>$title,'body'=>$text,'type'=>'Message'));
			$json = json_encode($notification_data);
			$result = MJ_smgt_send_push_notification($json);
			if($result)
			{
				return true;
			}
			/* End Send Push Notification */
		}
	}

	public function hrmgt_approve_leave_selected($data1)
	{
		global $wpdb;

		$id = $data1;

		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';

		$row = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave WHERE id=".$id);

		if($row->status !='Rejected')
		{

			$update = $wpdb->query("UPDATE $table_hrmgt_leave SET status='Approved' where id=".$id);		

			if($update)
			{	
				$data['start_date'] = $row->start_date;

				$data['end_date'] = $row->end_date;

				$data['employee_id'] = $row->employee_id;
				$data['leave_duration'] = $row->leave_duration;

				//$this->add_leave_entry_in_attendance_details($data['start_date'],isset($data['end_date'])?$data['end_date']:'',$data['leave_duration'],$data['employee_id']);		
				$leave_data = $this->hrmgt_get_single_leave($id);
				$arr=array();
				if(!empty($leave_data->end_date))
				{
					$date = smgt_change_dateformat($leave_data->start_date) .' To '. smgt_change_dateformat($leave_data->end_date);
				}
				else
				{
					//$date  = smgt_change_dateformat($leave_data->start_date);
					$date  = smgt_change_dateformat($leave_data->start_date) .' To -';
				}

				$arr['{{date}}']= $date;					
				$arr['{{system_name}}'] = get_option('smgt_school_name');
				$arr['{{user_name}}'] = hrmgt_get_display_name($leave_data->employee_id);
				$arr['{{comment}}'] = mj_smgt_strip_tags_and_stripslashes($data['comment']);
				$message = get_option('leave_approve_email_template');		
				$replace_message =  stripslashes(mj_smgt_string_replacement($arr,$message));			
				if($replace_message)
				{
					$subject = stripslashes(get_option('leave_approve_subject'));						
					$to[]= hrmgt_get_emailid_byuser_id($leave_data->employee_id);				
					$emails = get_option('leave_approveemails');
					$emails = explode(",",$emails);
					foreach($emails as $email)
					{
						$to[]=$email;
					}
					$mail = hmgt_send_mail($to,$subject,$replace_message);				
					if($mail)
					{
						return true;
					}
				}
			  return  $update;			
			}
			else
			{
			 	return  $update;
			}
		}	
	}
	// LEAVE REJECT FUCTION
	public function hrmgt_reject_leave($data)
	{
		global $wpdb;
		$id = $data['leave_id'];
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$row = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave WHERE id=".$id);
		$update = $wpdb->query("UPDATE $table_hrmgt_leave SET status='Rejected' where id=".$id);
		$empdata = get_userdata((int)$row->student_id);
		if($update)
		{
			$leave_data = $this->hrmgt_get_single_leave($id);
			$arr=array();
			if(!empty($leave_data->end_date))
			{
				$date = mj_smgt_getdate_in_input_box($leave_data->start_date) .' To '. mj_smgt_getdate_in_input_box($leave_data->end_date);
			}
			else
			{
				$date  = mj_smgt_getdate_in_input_box($leave_data->start_date);
			}
			// LEAVE REJECT MAIL START

			$arr['{{date}}']= $date;	

			$arr['{{school_name}}'] = get_option('smgt_school_name');

			$arr['{{student_name}}'] = mj_smgt_student_display_name_with_roll($leave_data->student_id);

			$arr['{{comment}}'] = mj_smgt_strip_tags_and_stripslashes($data['comment']);

			$message = get_option('leave_reject_email_template');		
			
			$replace_message =  stripslashes(mj_smgt_string_replacement($arr,$message));

			$subject = stripslashes(get_option('leave_reject_subject'));

			$to = mj_smgt_get_emailid_byuser_id($leave_data->student_id);
			
			$mail = mj_smgt_send_mail($to,$subject,$replace_message);

			// LEAVE REJECT MAIL END	
		}
		return $empdata;

	}

	public function hrmgt_reject_leave_selected($data1)
	{
		global $wpdb;
		$id = $data1;

		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$row = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave WHERE id=".$id);
		if($row->status !='Approved')
		{
			$update = $wpdb->query("UPDATE $table_hrmgt_leave SET status='Rejected' where id=".$id);		
			if($update)
			{ 	
				$replace_message="";
				$to = array();
				$leave_data = $this->hrmgt_get_single_leave($id);
				$to[]= hrmgt_get_emailid_byuser_id($leave_data->employee_id);
				$emails= explode(",",get_option('leave_approveemails'));
				foreach($emails as $email)
				{
					$to[] = $email;
				}			
				$subject="Reject Leave";
				$replace_message .= "Hello, \r\n \r\n Leave of ". hrmgt_get_display_name($leave_data->employee_id) . " is  rejected.";
				$replace_message .="\r\n \r\n";
				$replace_message .= "Comment  : ". $data['comment'];
				$mail = hmgt_send_mail($to,$subject,$replace_message);			
				if($mail)
				{
					return true;					
				}
			}	
		}
	}

	public function hrmgt_delete_leave($leave_id)	
	{
		
		global $wpdb;
		$table_hrmgt_leave = $wpdb->prefix. 'smgt_leave';
		$leave_data=$this->hrmgt_get_single_leave($leave_id);
		$event = $wpdb->get_row("SELECT * FROM $table_hrmgt_leave where id=$leave_id");
		$student = mj_smgt_get_user_name_byid($event->student_id);
		school_append_audit_log(''.esc_html__('Leave Deleted','hospital_mgt').'('.$student.')'.'',get_current_user_id(),get_current_user_id(),'delete',$_REQUEST['page']);	
		$result = $wpdb->query("DELETE FROM $table_hrmgt_leave where id= ".$leave_id);	
		return $result;

	}
}

?>